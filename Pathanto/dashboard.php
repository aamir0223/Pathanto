<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/progress.php';
require_login();
$userId = current_user_id();
$dashboard = get_dashboard($userId);
include __DIR__ . '/header.php';
?>
<div class="auth-container">
    <h1>Dashboard</h1>
    <p>Points: <?php echo (int)$dashboard['points']; ?></p>
    <p>Current Streak: <?php echo (int)$dashboard['streak']; ?> days</p>
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
</div>
<?php include __DIR__ . '/footer.php'; ?>
