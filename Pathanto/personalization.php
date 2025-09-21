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
    $sql = "SELECT q.id, q.question_text, q.topic_id, q.difficulty, q.tags FROM questions q LEFT JOIN (
                SELECT question_id, AVG(correct) AS acc FROM question_attempts WHERE user_id = ? GROUP BY question_id
            ) a ON q.id = a.question_id
            WHERE COALESCE(a.acc, 0) < 0.7";

    $types = 'i';
    $params = [$userId];

    if (!empty($filters['topic'])) {
        $sql .= ' AND q.topic_id = ?';
        $types .= 'i';
        $params[] = (int) $filters['topic'];
    }
    if (!empty($filters['difficulty'])) {
        $sql .= ' AND q.difficulty = ?';
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
