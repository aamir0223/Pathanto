<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/progress.php';
require_once __DIR__ . '/personalization.php';
require_once __DIR__ . '/gamification.php';
require_login();
$userId = current_user_id();

// Allow users to update their daily question goal.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daily_goal'])) {
    $goal = max(0, (int) $_POST['daily_goal']);
    set_daily_goal($userId, $goal);
}

$dashboard    = get_dashboard($userId);
$dailyGoal    = get_daily_goal($userId);
$recommended  = recommend_questions($userId);
$leaders      = get_leaderboard('all', 5);

include __DIR__ . '/header.php';
?>
<div class="auth-container">
    <h1>Dashboard</h1>
    <p>Points: <?php echo (int)$dashboard['points']; ?></p>
    <p>Current Streak: <?php echo (int)$dashboard['streak']; ?> days</p>

    <h2>Daily Goal</h2>
    <form method="post">
        <label>
            Questions per day:
            <input type="number" name="daily_goal" min="0" value="<?php echo (int) $dailyGoal; ?>">
        </label>
        <button type="submit">Save Goal</button>
    </form>

    <h2>Recommended Questions</h2>
    <?php if (!empty($recommended)): ?>
    <ul>
        <?php foreach ($recommended as $qid): ?>
            <li>Question <?php echo (int) $qid; ?></li>
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
        <li>Question <?php echo $attempt['question_id']; ?> - <?php echo $attempt['correct'] ? 'Correct' : 'Incorrect'; ?> (<?php echo $attempt['created_at']; ?>)</li>
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
