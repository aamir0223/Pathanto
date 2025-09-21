<?php
// Personalization utilities for setting goals and recommending questions.
require_once __DIR__ . '/config.php';

// Ensure the goals table exists so dashboard queries do not fail.
$createGoals = "CREATE TABLE IF NOT EXISTS user_goals (
    user_id INT PRIMARY KEY,
    questions_per_day INT NOT NULL DEFAULT 0
)";
if (!$conn->query($createGoals)) {
    error_log('Failed creating user_goals table: ' . $conn->error);
}

function get_question_table_metadata(): array
{
    static $meta = null;
    if ($meta !== null) {
        return $meta;
    }

    global $conn;
    $meta = [
        'text_column' => 'question_text',
        'order_column' => 'created_at',
        'topic_column' => 'topic_id',
        'difficulty_column' => 'difficulty',
        'has_tags' => false,
    ];

    $columnsResult = $conn->query('DESCRIBE questions');
    if ($columnsResult instanceof mysqli_result) {
        $fields = [];
        while ($column = $columnsResult->fetch_assoc()) {
            $fields[strtolower($column['Field'])] = strtolower($column['Type']);
        }
        $columnsResult->close();

        $textPriority = ['question_text', 'text', 'question', 'title', 'name'];
        foreach ($textPriority as $candidate) {
            if (isset($fields[$candidate])) {
                $meta['text_column'] = $candidate;
                break;
            }
        }
        if (!isset($fields[$meta['text_column']])) {
            foreach ($fields as $fieldName => $fieldType) {
                if (strpos($fieldType, 'char') !== false || strpos($fieldType, 'text') !== false) {
                    $meta['text_column'] = $fieldName;
                    break;
                }
            }
        }

        $orderPriority = ['created_at', 'updated_at', 'id'];
        foreach ($orderPriority as $candidate) {
            if (isset($fields[$candidate])) {
                $meta['order_column'] = $candidate;
                break;
            }
        }
        if (!isset($fields[$meta['order_column']])) {
            $meta['order_column'] = array_key_first($fields) ?? 'id';
        }

        $meta['has_tags'] = isset($fields['tags']);
        if (!isset($fields['topic_id'])) {
            $meta['topic_column'] = null;
        }
        if (!isset($fields['difficulty'])) {
            $meta['difficulty_column'] = null;
        }
    }

    return $meta;
}

function set_daily_goal($userId, $questionsPerDay)
{
    global $conn;
    $stmt = $conn->prepare('REPLACE INTO user_goals (user_id, questions_per_day) VALUES (?, ?)');
    if (!$stmt) {
        error_log('set_daily_goal prepare failed: ' . $conn->error);
        return;
    }
    $stmt->bind_param('ii', $userId, $questionsPerDay);
    $stmt->execute();
    $stmt->close();
}

function get_daily_goal($userId)
{
    global $conn;
    $stmt = $conn->prepare('SELECT questions_per_day FROM user_goals WHERE user_id = ?');
    if (!$stmt) {
        error_log('get_daily_goal prepare failed: ' . $conn->error);
        return 0;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ? (int) $row['questions_per_day'] : 0;
}

function recommend_questions($userId, $limit = 5, $filters = [])
{
    global $conn;
    $meta = get_question_table_metadata();
    $textColumn = $meta['text_column'];
    $topicColumn = $meta['topic_column'];
    $difficultyColumn = $meta['difficulty_column'];
    $tagSelect = $meta['has_tags'] ? 'q.tags' : 'NULL';
    $topicSelect = $topicColumn ? 'q.' . $topicColumn : 'NULL';
    $difficultySelect = $difficultyColumn ? 'q.' . $difficultyColumn : 'NULL';

    $sql = "SELECT q.id, q.$textColumn AS question_text, $topicSelect AS topic_id, $difficultySelect AS difficulty, $tagSelect AS tags FROM questions q LEFT JOIN (
                SELECT question_id, AVG(correct) AS acc FROM question_attempts WHERE user_id = ? GROUP BY question_id
            ) a ON q.id = a.question_id
            WHERE COALESCE(a.acc, 0) < 0.7";

    $types = 'i';
    $params = [$userId];

    if (!empty($filters['topic']) && $topicColumn) {
        $sql .= ' AND q.' . $topicColumn . ' = ?';
        $types .= 'i';
        $params[] = (int) $filters['topic'];
    }
    if (!empty($filters['difficulty']) && $difficultyColumn) {
        $sql .= ' AND q.' . $difficultyColumn . ' = ?';
        $types .= 's';
        $params[] = $filters['difficulty'];
    }

    $sql .= ' ORDER BY RAND() LIMIT ?';
    $types .= 'i';
    $params[] = $limit;

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('recommend_questions prepare failed: ' . $conn->error);
        return [];
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

function get_default_questions($limit = 5): array
{
    global $conn;
    $meta = get_question_table_metadata();
    $textColumn = $meta['text_column'];
    $orderColumn = $meta['order_column'];
    $topicColumn = $meta['topic_column'];
    $difficultyColumn = $meta['difficulty_column'];
    $tagSelect = $meta['has_tags'] ? 'q.tags' : 'NULL';
    $topicSelect = $topicColumn ? 'q.' . $topicColumn : 'NULL';
    $difficultySelect = $difficultyColumn ? 'q.' . $difficultyColumn : 'NULL';
    $limit = max(1, (int) $limit);
    $orderClause = 'q.' . $orderColumn;
    $stmt = $conn->prepare("SELECT q.id, $textColumn AS question_text, $topicSelect AS topic_id, $difficultySelect AS difficulty, $tagSelect AS tags FROM questions q ORDER BY $orderClause DESC LIMIT ?");
    if (!$stmt) {
        error_log('get_default_questions prepare failed: ' . $conn->error);
        return [];
    }
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

function get_related_questions($questionId, $topicId, $limit = 3): array
{
    global $conn;
    if ($topicId === null) {
        return [];
    }

    $meta = get_question_table_metadata();
    if (!$meta['topic_column']) {
        return [];
    }
    $textColumn = $meta['text_column'];
    $topicColumn = $meta['topic_column'];
    $topicIdInt = (int) $topicId;
    $questionIdInt = (int) $questionId;
    $limitInt = max(1, (int) $limit);

    $stmt = $conn->prepare("SELECT id, $textColumn AS question_text FROM questions WHERE $topicColumn = ? AND id <> ? ORDER BY RAND() LIMIT ?");
    if (!$stmt) {
        error_log('get_related_questions prepare failed: ' . $conn->error);
        return [];
    }
    $stmt->bind_param('iii', $topicIdInt, $questionIdInt, $limitInt);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

function get_topic_options(): array
{
    global $conn;
    $topics = [];

    $result = $conn->query('SELECT id, name FROM topics ORDER BY name');
    if ($result instanceof mysqli_result) {
        while ($row = $result->fetch_assoc()) {
            if ($row['id'] === null) {
                continue;
            }
            $topics[] = [
                'id' => $row['id'],
                'label' => $row['name'] !== null && $row['name'] !== '' ? $row['name'] : 'Topic ' . $row['id'],
            ];
        }
        $result->close();
        if (!empty($topics)) {
            return $topics;
        }
    }

    $fallback = $conn->query('SELECT DISTINCT topic_id FROM questions WHERE topic_id IS NOT NULL ORDER BY topic_id');
    if ($fallback instanceof mysqli_result) {
        while ($row = $fallback->fetch_assoc()) {
            $topicId = $row['topic_id'];
            if ($topicId === null || $topicId === '') {
                continue;
            }
            $label = is_numeric($topicId) ? 'Topic ' . $topicId : (string) $topicId;
            $topics[] = ['id' => $topicId, 'label' => $label];
        }
        $fallback->close();
    }

    return $topics;
}

function get_difficulty_options(): array
{
    global $conn;
    $options = [];
    $result = $conn->query("SELECT DISTINCT difficulty FROM questions WHERE difficulty IS NOT NULL AND difficulty <> '' ORDER BY difficulty");
    if ($result instanceof mysqli_result) {
        while ($row = $result->fetch_assoc()) {
            $value = $row['difficulty'];
            if ($value === null || $value === '') {
                continue;
            }
            $options[] = [
                'value' => $value,
                'label' => ucwords(str_replace(['-', '_'], ' ', $value)),
            ];
        }
        $result->close();
    }
    return $options;
}
?>
