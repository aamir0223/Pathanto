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

// Dashboard relies on points and streak tables; create them if missing.
$conn->query("CREATE TABLE IF NOT EXISTS user_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    points INT NOT NULL,
    reason VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$conn->query("CREATE TABLE IF NOT EXISTS user_streaks (
    user_id INT PRIMARY KEY,
    current_streak INT NOT NULL DEFAULT 0,
    last_active_date DATE
)");

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

    // Current streak
    $stmt = $conn->prepare('SELECT current_streak FROM user_streaks WHERE user_id = ?');
    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            $data['streak'] = (int) $row['current_streak'];
        }
        $stmt->close();
    }

    // Accuracy by topic
    $sql = 'SELECT topic_id, AVG(correct) AS accuracy FROM question_attempts WHERE user_id = ? GROUP BY topic_id';
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data['topics'][$row['topic_id']] = (float) $row['accuracy'];
        }
        $stmt->close();
    }

    // Last 10 attempts
    $sql = 'SELECT question_id, correct, created_at FROM question_attempts WHERE user_id = ? ORDER BY created_at DESC LIMIT 10';
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['recent'] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    return $data;
}
?>
