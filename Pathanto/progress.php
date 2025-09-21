<?php
// Progress tracking utilities for storing question attempts and fetching dashboard data.
require_once __DIR__ . '/config.php';

// Ensure required tables exist so dashboard queries do not fail on new setups.
$createAttempts = "CREATE TABLE IF NOT EXISTS question_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    correct TINYINT(1) NOT NULL,
    topic_id INT,
    difficulty VARCHAR(10),
    time_taken INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($createAttempts)) {
    error_log('Failed creating question_attempts table: ' . $conn->error);
}

// Dashboard relies on a points table; create it if missing.
$conn->query("CREATE TABLE IF NOT EXISTS user_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    points INT NOT NULL,
    reason VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$createFeed = "CREATE TABLE IF NOT EXISTS question_feed (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    correct TINYINT(1) NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_feed_created (created_at)
)";
if (!$conn->query($createFeed)) {
    error_log('Failed creating question_feed table: ' . $conn->error);
}

$createReactions = "CREATE TABLE IF NOT EXISTS question_feed_reactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    feed_id INT NOT NULL,
    user_id INT NOT NULL,
    reaction TINYINT(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_feed_user (feed_id, user_id),
    INDEX idx_reaction_feed (feed_id)
)";
if (!$conn->query($createReactions)) {
    error_log('Failed creating question_feed_reactions table: ' . $conn->error);
}

$createComments = "CREATE TABLE IF NOT EXISTS question_feed_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    feed_id INT NOT NULL,
    user_id INT NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_comment_feed (feed_id)
)";
if (!$conn->query($createComments)) {
    error_log('Failed creating question_feed_comments table: ' . $conn->error);
}

/**
 * Record a user's attempt at a question.
 */
function record_attempt($userId, $questionId, $correct, $topicId, $difficulty, $timeTaken)
{
    global $conn;
    $stmt = $conn->prepare('INSERT INTO question_attempts (user_id, question_id, correct, topic_id, difficulty, time_taken) VALUES (?, ?, ?, ?, ?, ?)');
    if (!$stmt) {
        error_log('record_attempt prepare failed: ' . $conn->error);
        return;
    }
    $c = $correct ? 1 : 0;
    $stmt->bind_param('iiiisi', $userId, $questionId, $c, $topicId, $difficulty, $timeTaken);
    $stmt->execute();
    $stmt->close();
}

function publish_attempt($userId, $questionId, $correct, $note = null)
{
    global $conn;
    $stmt = $conn->prepare('INSERT INTO question_feed (user_id, question_id, correct, note) VALUES (?, ?, ?, ?)');
    if (!$stmt) {
        error_log('publish_attempt prepare failed: ' . $conn->error);
        return false;
    }
    $c = $correct ? 1 : 0;
    $noteParam = $note !== null ? $note : '';
    $stmt->bind_param('iiis', $userId, $questionId, $c, $noteParam);
    $ok = $stmt->execute();
    if (!$ok) {
        error_log('publish_attempt execute failed: ' . $stmt->error);
    }
    $stmt->close();
    return $ok;
}

function build_feed_payload(array $rows, $currentUserId = null)
{
    global $conn;
    if (!$rows) {
        return [];
    }

    $feed = [];
    foreach ($rows as $row) {
        $feedId = (int) $row['id'];
        $questionText = $row['question_text'] !== null ? trim(strip_tags(htmlspecialchars_decode($row['question_text']))) : ('Question #' . (int) $row['question_id']);
        $answerText = $row['answer_text'] !== null ? trim(strip_tags(htmlspecialchars_decode($row['answer_text']))) : '';
        $feed[$feedId] = [
            'id' => $feedId,
            'question_id' => (int) $row['question_id'],
            'question_text' => $questionText,
            'answer_text' => $answerText,
            'user' => $row['name'],
            'correct' => (bool) $row['correct'],
            'note' => $row['note'] !== null ? trim($row['note']) : '',
            'created_at' => $row['created_at'],
            'likes' => 0,
            'dislikes' => 0,
            'user_reaction' => null,
            'comments' => [],
        ];
    }

    $feedIds = array_keys($feed);
    if (!empty($feedIds)) {
        $idList = implode(',', array_map('intval', $feedIds));

        $reactionResult = $conn->query("SELECT feed_id, reaction, COUNT(*) AS total FROM question_feed_reactions WHERE feed_id IN ($idList) GROUP BY feed_id, reaction");
        if ($reactionResult instanceof mysqli_result) {
            while ($reactionRow = $reactionResult->fetch_assoc()) {
                $feedId = (int) $reactionRow['feed_id'];
                if (!isset($feed[$feedId])) {
                    continue;
                }
                if ((int) $reactionRow['reaction'] === 1) {
                    $feed[$feedId]['likes'] = (int) $reactionRow['total'];
                } else {
                    $feed[$feedId]['dislikes'] = (int) $reactionRow['total'];
                }
            }
            $reactionResult->close();
        }

        if ($currentUserId) {
            $userReactionResult = $conn->query("SELECT feed_id, reaction FROM question_feed_reactions WHERE feed_id IN ($idList) AND user_id = " . (int) $currentUserId);
            if ($userReactionResult instanceof mysqli_result) {
                while ($userReactionRow = $userReactionResult->fetch_assoc()) {
                    $feedId = (int) $userReactionRow['feed_id'];
                    if (!isset($feed[$feedId])) {
                        continue;
                    }
                    $feed[$feedId]['user_reaction'] = ((int) $userReactionRow['reaction'] === 1) ? 'like' : 'dislike';
                }
                $userReactionResult->close();
            }
        }

        $commentsResult = $conn->query("SELECT c.feed_id, c.comment_text, c.created_at, u.name FROM question_feed_comments c JOIN users u ON c.user_id = u.id WHERE c.feed_id IN ($idList) ORDER BY c.created_at ASC");
        if ($commentsResult instanceof mysqli_result) {
            while ($commentRow = $commentsResult->fetch_assoc()) {
                $feedId = (int) $commentRow['feed_id'];
                if (!isset($feed[$feedId])) {
                    continue;
                }
                $feed[$feedId]['comments'][] = [
                    'user' => $commentRow['name'],
                    'comment_text' => trim($commentRow['comment_text']),
                    'created_at' => $commentRow['created_at'],
                ];
            }
            $commentsResult->close();
        }
    }

    $ordered = [];
    foreach ($rows as $row) {
        $feedId = (int) $row['id'];
        if (isset($feed[$feedId])) {
            $ordered[] = $feed[$feedId];
        }
    }

    return $ordered;
}

function get_public_feed($limit = 10, $currentUserId = null)
{
    global $conn;

    static $hasAnswersTable = null;
    if ($hasAnswersTable === null) {
        $check = $conn->query("SHOW TABLES LIKE 'answers'");
        $hasAnswersTable = $check && $check->num_rows > 0;
        if ($check instanceof mysqli_result) {
            $check->close();
        }
    }

    $answerSelect = $hasAnswersTable ? '(SELECT answer_text FROM answers WHERE question_id = f.question_id LIMIT 1)' : 'NULL';

    $sql = "SELECT f.id,
                   f.question_id,
                   f.correct,
                   f.note,
                   f.created_at,
                   COALESCE(q.question_text, CONCAT('Question #', f.question_id)) AS question_text,
                   $answerSelect AS answer_text,
                   u.name
            FROM question_feed f
            LEFT JOIN questions q ON f.question_id = q.id
            JOIN users u ON f.user_id = u.id
            ORDER BY f.created_at DESC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('get_public_feed prepare failed: ' . $conn->error);
        return [];
    }
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return build_feed_payload($rows, $currentUserId);
}

function get_feed_entry($feedId, $currentUserId = null)
{
    global $conn;

    static $hasAnswersTable = null;
    if ($hasAnswersTable === null) {
        $check = $conn->query("SHOW TABLES LIKE 'answers'");
        $hasAnswersTable = $check && $check->num_rows > 0;
        if ($check instanceof mysqli_result) {
            $check->close();
        }
    }

    $answerSelect = $hasAnswersTable ? '(SELECT answer_text FROM answers WHERE question_id = f.question_id LIMIT 1)' : 'NULL';

    $sql = "SELECT f.id,
                   f.question_id,
                   f.correct,
                   f.note,
                   f.created_at,
                   COALESCE(q.question_text, CONCAT('Question #', f.question_id)) AS question_text,
                   $answerSelect AS answer_text,
                   u.name
            FROM question_feed f
            LEFT JOIN questions q ON f.question_id = q.id
            JOIN users u ON f.user_id = u.id
            WHERE f.id = ?
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('get_feed_entry prepare failed: ' . $conn->error);
        return null;
    }
    $stmt->bind_param('i', $feedId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $payload = build_feed_payload($rows, $currentUserId);
    return $payload[0] ?? null;
}

/**
 * Calculate how many consecutive days the user has answered questions.
 */
function get_user_streak($userId)
{
    global $conn;
    $sql = "SELECT DATE(created_at) AS day FROM question_attempts WHERE user_id = ? GROUP BY day ORDER BY day DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('get_user_streak prepare failed: ' . $conn->error);
        return 0;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $days = [];
    while ($row = $result->fetch_assoc()) {
        $days[] = $row['day'];
    }
    $stmt->close();

    $streak = 0;
    $expected = date('Y-m-d');
    foreach ($days as $day) {
        if ($day === $expected) {
            $streak++;
            $expected = date('Y-m-d', strtotime($expected . ' -1 day'));
        } else {
            break;
        }
    }
    return $streak;
}

/**
 * Fetch dashboard statistics for a user, including points, streaks,
 * accuracy by topic, and the last ten attempts.
 */
function get_dashboard($userId)
{
    global $conn;
    $data = [
        'points' => 0,
        'streak' => 0,
        'topics' => [],
        'recent' => [],
    ];

    // Total points
    $stmt = $conn->prepare('SELECT COALESCE(SUM(points), 0) AS total FROM user_points WHERE user_id = ?');
    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $data['points'] = (int) $row['total'];
        $stmt->close();
    }

    // Current streak based on consecutive active days
    $data['streak'] = get_user_streak($userId);

    // Accuracy by topic
    $sql = 'SELECT topic_id,
                   SUM(correct) AS correct_total,
                   COUNT(*) AS attempt_total,
                   AVG(correct) AS accuracy
            FROM question_attempts
            WHERE user_id = ?
            GROUP BY topic_id';
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $topicKey = $row['topic_id'];
            $data['topics'][$topicKey] = [
                'accuracy' => (float) $row['accuracy'],
                'correct' => (int) $row['correct_total'],
                'attempts' => (int) $row['attempt_total'],
            ];
        }
        $stmt->close();
    }

    // Last 10 attempts per question with question text and timestamp
    $sql = 'SELECT qa.question_id, q.question_text, qa.correct, qa.created_at
            FROM question_attempts qa
            JOIN (
                SELECT question_id, MAX(created_at) AS last_attempt
                FROM question_attempts
                WHERE user_id = ?
                GROUP BY question_id
            ) r ON qa.question_id = r.question_id AND qa.created_at = r.last_attempt
            JOIN questions q ON qa.question_id = q.id
            WHERE qa.user_id = ?
            ORDER BY qa.created_at DESC
            LIMIT 10';
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ii', $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['recent'] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    return $data;
}

/**
 * Return how many unique questions the user has answered versus total available.
 */
function get_progress_summary($userId)
{
    global $conn;
    $summary = ['answered' => 0, 'total' => 0];

    $res = $conn->query('SELECT COUNT(*) AS total FROM questions');
    if ($res) {
        $row = $res->fetch_assoc();
        $summary['total'] = (int) $row['total'];
    }

    $stmt = $conn->prepare('SELECT COUNT(DISTINCT question_id) AS answered FROM question_attempts WHERE user_id = ?');
    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $summary['answered'] = (int) $row['answered'];
        $stmt->close();
    }

    return $summary;
}
?>
