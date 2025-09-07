<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/progress.php';
require_once __DIR__ . '/personalization.php';
require_once __DIR__ . '/gamification.php';
require_login();
$userId = current_user_id();
$userName = get_user_name($userId) ?? ('User ' . $userId);

// Allow users to update their daily question goal.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daily_goal'])) {
    $goal = max(0, (int) $_POST['daily_goal']);
    set_daily_goal($userId, $goal);
}

$filters      = ['topic' => $_GET['topic'] ?? null, 'difficulty' => $_GET['difficulty'] ?? null];
$dashboard    = get_dashboard($userId);
$summary      = get_progress_summary($userId);
$dailyGoal    = get_daily_goal($userId);
$recommended  = recommend_questions($userId, 5, $filters);
$leaders      = get_leaderboard('all', 5);

include __DIR__ . '/header.php';
?>
<div class="auth-container">
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($userName); ?></p>
    <p>Points: <?php echo (int)$dashboard['points']; ?></p>
    <p>Current Streak: <?php echo (int)$dashboard['streak']; ?> days</p>
    <?php $percent = $summary['total'] > 0 ? round($summary['answered'] / $summary['total'] * 100, 2) : 0; ?>
    <p>Progress: <?php echo $summary['answered']; ?>/<?php echo $summary['total']; ?> (<?php echo $percent; ?>%)</p>
    <progress value="<?php echo $summary['answered']; ?>" max="<?php echo $summary['total']; ?>"></progress>

    <h2>Daily Goal</h2>
    <form method="post">
        <label>
            Questions per day:
            <input type="number" name="daily_goal" min="0" value="<?php echo (int) $dailyGoal; ?>">
        </label>
        <button type="submit">Save Goal</button>
    </form>

    <h2>Recommended Questions</h2>
    <form method="get">
        <label>Topic: <input type="text" name="topic" value="<?php echo htmlspecialchars($filters['topic'] ?? ''); ?>"></label>
        <label>Difficulty: <input type="text" name="difficulty" value="<?php echo htmlspecialchars($filters['difficulty'] ?? ''); ?>"></label>
        <button type="submit">Filter</button>
    </form>
    <?php if (!empty($recommended)): ?>
    <ul>
        <?php foreach ($recommended as $question): ?>
            <li><?php echo htmlspecialchars($question['question_text']); ?></li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p>No recommendations right now.</p>
    <?php endif; ?>

    <h2>Topic Accuracy</h2>
    <ul>
    <?php foreach ($dashboard['topics'] as $topic => $acc): ?>
        <li>Topic <?php echo htmlspecialchars($topic); ?>: <?php echo round($acc * 100, 2); ?>%</li>
    <?php endforeach; ?>
    </ul>
    <h2>Recent Attempts</h2>
    <ul>
    <?php foreach ($dashboard['recent'] as $attempt): ?>
        <li><?php echo htmlspecialchars($attempt['question_text']); ?> - <?php echo $attempt['correct'] ? 'Correct' : 'Incorrect'; ?> (<?php echo $attempt['created_at']; ?>)</li>
    <?php endforeach; ?>
    </ul>
    <h2>Leaderboard</h2>
    <?php if (!empty($leaders)): ?>
    <ol>
    <?php foreach ($leaders as $row):
        $uid    = (int) $row['user_id'];
        $points = (int) $row['total'];
        $name   = $row['name'] ?? ('User ' . $uid);
    ?>
        <li><?php echo htmlspecialchars($name); ?> - <?php echo $points; ?> pts</li>
    <?php endforeach; ?>
    </ol>
    <p><a href="/Pathanto/leaderboard.php">View full leaderboard</a></p>
    <?php else: ?>
    <p>No leaderboard data yet.</p>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/footer.php'; ?>
