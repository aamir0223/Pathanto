<?php
require_once __DIR__ . '/gamification.php';
require_once __DIR__ . '/auth.php';
include __DIR__ . '/header.php';

$leaders = get_leaderboard('all', 10);
?>
<div class="auth-container">
    <h1>Leaderboard</h1>
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
    <?php else: ?>
    <p>No users have earned points yet.</p>
    <?php endif; ?>

    <?php if (!current_user_id()): ?>
    <p><a href="/Pathanto/login.php">Log in</a> to compete on the leaderboard.</p>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/footer.php'; ?>
