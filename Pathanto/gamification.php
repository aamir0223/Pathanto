<?php
// Gamification utilities for awarding points and tracking streaks.
// This file provides a minimal API that other pages can include
// to award points for correct answers, quiz completion, and daily streaks.

require_once __DIR__ . '/config.php';

// Create necessary tables for points, streaks, and badges if they don't exist.
$createPoints = "CREATE TABLE IF NOT EXISTS user_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    points INT NOT NULL,
    reason VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($createPoints)) {
    error_log('Failed creating user_points table: ' . $conn->error);
}

$createStreaks = "CREATE TABLE IF NOT EXISTS user_streaks (
    user_id INT PRIMARY KEY,
    current_streak INT NOT NULL DEFAULT 0,
    last_active_date DATE
)";
if (!$conn->query($createStreaks)) {
    error_log('Failed creating user_streaks table: ' . $conn->error);
}

$createBadges = "CREATE TABLE IF NOT EXISTS user_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    badge VARCHAR(100) NOT NULL,
    awarded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($createBadges)) {
    error_log('Failed creating user_badges table: ' . $conn->error);
}

/**
 * Award points to a user.
 *
 * @param int    $userId ID of the user.
 * @param int    $points Number of points to award (can be negative).
 * @param string $reason Optional description of why points were awarded.
 *
 * @return void
 */
function add_points($userId, $points, $reason = ''): void
{
    global $conn;
    $stmt = $conn->prepare('INSERT INTO user_points (user_id, points, reason) VALUES (?, ?, ?)');
    if (!$stmt) {
        error_log('add_points prepare failed: ' . $conn->error);
        return;
    }
    $stmt->bind_param('iis', $userId, $points, $reason);
    $stmt->execute();
    $stmt->close();
}

/**
 * Get the total number of points for a user.
 *
 * @param int $userId ID of the user.
 *
 * @return int Total points accumulated by the user.
 */
function get_total_points($userId): int
{
    global $conn;
    $stmt = $conn->prepare('SELECT COALESCE(SUM(points), 0) AS total FROM user_points WHERE user_id = ?');
    if (!$stmt) {
        error_log('get_total_points prepare failed: ' . $conn->error);
        return 0;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return (int) $result['total'];
}

/**
 * Update a user's daily streak. Call this when a user completes a quiz.
 * A streak increments if the user completes at least one quiz on a given day.
 *
 * @param int       $userId  ID of the user.
 * @param string $timezone PHP timezone identifier for the user.
 *
 * @return void
 */
function update_streak($userId, $timezone): void
{
    global $conn;
    $tz = new DateTimeZone($timezone);
    $today = new DateTime('now', $tz);
    $todayStr = $today->format('Y-m-d');

    // Fetch existing streak info
    $stmt = $conn->prepare('SELECT current_streak, last_active_date FROM user_streaks WHERE user_id = ?');
    if (!$stmt) {
        error_log('update_streak select failed: ' . $conn->error);
        return;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$result) {
        // First entry for this user
        $stmt = $conn->prepare('INSERT INTO user_streaks (user_id, current_streak, last_active_date) VALUES (?, 1, ?)');
        if (!$stmt) {
            error_log('update_streak insert failed: ' . $conn->error);
            return;
        }
        $stmt->bind_param('is', $userId, $todayStr);
        $stmt->execute();
        $stmt->close();
        return;
    }

    $lastActive = new DateTime($result['last_active_date'], $tz);
    $diff = (int) $lastActive->diff($today)->format('%a');

    if ($diff === 0) {
        // Already updated today
        return;
    } elseif ($diff === 1) {
        // Increment streak
        $stmt = $conn->prepare('UPDATE user_streaks SET current_streak = current_streak + 1, last_active_date = ? WHERE user_id = ?');
        if (!$stmt) {
            error_log('update_streak increment failed: ' . $conn->error);
            return;
        }
        $stmt->bind_param('si', $todayStr, $userId);
        $stmt->execute();
        $stmt->close();
    } else {
        // Streak broken
        $stmt = $conn->prepare('UPDATE user_streaks SET current_streak = 1, last_active_date = ? WHERE user_id = ?');
        if (!$stmt) {
            error_log('update_streak reset failed: ' . $conn->error);
            return;
        }
        $stmt->bind_param('si', $todayStr, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Return a leaderboard of users ordered by total points.
 *
 * @param string $period Either 'all' for all-time or 'weekly' for current week.
 * @param int    $limit  Maximum number of rows to return.
 *
 * @return array[]
 */
function get_leaderboard($period = 'all', $limit = 10): array
{
    global $conn;
    if ($period === 'weekly') {
        $sql = "SELECT u.id AS user_id, u.name, SUM(p.points) AS total, " .
               "MAX(p.created_at) AS last_activity " .
               "FROM user_points p JOIN users u ON p.user_id = u.id " .
               "WHERE YEARWEEK(p.created_at, 1) = YEARWEEK(NOW(), 1) " .
               "GROUP BY u.id, u.name ORDER BY total DESC, last_activity DESC LIMIT ?";
    } else {
        $sql = "SELECT u.id AS user_id, u.name, SUM(p.points) AS total, " .
               "MAX(p.created_at) AS last_activity " .
               "FROM user_points p JOIN users u ON p.user_id = u.id " .
               "GROUP BY u.id, u.name ORDER BY total DESC, last_activity DESC LIMIT ?";
    }
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('get_leaderboard prepare failed: ' . $conn->error);
        return [];
    }
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

/**
 * Award a badge to a user.
 */
function award_badge($userId, $badge): void
{
    global $conn;
    $stmt = $conn->prepare('INSERT INTO user_badges (user_id, badge) VALUES (?, ?)');
    if (!$stmt) {
        error_log('award_badge prepare failed: ' . $conn->error);
        return;
    }
    $stmt->bind_param('is', $userId, $badge);
    $stmt->execute();
    $stmt->close();
}

/**
 * Get all badges earned by a user.
 */
function get_badges($userId): array
{
    global $conn;
    $stmt = $conn->prepare('SELECT badge, awarded_at FROM user_badges WHERE user_id = ? ORDER BY awarded_at');
    if (!$stmt) {
        error_log('get_badges prepare failed: ' . $conn->error);
        return [];
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

// Configuration values for point awards. Adjust to tune the system.
define('POINTS_CORRECT_ANSWER', 10);
define('POINTS_QUIZ_COMPLETION', 50);
define('POINTS_STREAK_BONUS', 5); // Bonus per day after day 3

?>
