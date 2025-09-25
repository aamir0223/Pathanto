<?php
require_once __DIR__ . '/gamification.php';
require_once __DIR__ . '/auth.php';
include __DIR__ . '/header.php';

$leaders = get_leaderboard('all', 10);
$topThree = array_slice($leaders, 0, 3);
$remainingLeaders = array_slice($leaders, 3);
$maxPoints = 0;
foreach ($leaders as $row) {
    $points = (int) ($row['total'] ?? 0);
    if ($points > $maxPoints) {
        $maxPoints = $points;
    }
}
?>
<div class="leaderboard-page">
    <header class="leaderboard-hero">
        <h1 class="leaderboard-title">Community Leaderboard</h1>
        <p class="leaderboard-subtitle">Celebrate the top learners and see how you stack up this week.</p>
        <?php if (!current_user_id()): ?>
        <a class="leaderboard-cta" href="/Pathanto/login.php">Join the challenge</a>
        <?php endif; ?>
    </header>

    <?php if (!empty($leaders)): ?>
    <section class="leaderboard-top">
        <?php foreach ($topThree as $index => $row):
            $uid    = (int) $row['user_id'];
            $points = (int) $row['total'];
            $name   = trim((string) ($row['name'] ?? ''));
            $displayName = $name !== '' ? $name : ('User ' . $uid);
            $initials = strtoupper(substr($displayName, 0, 1));
            if ($initials === '') {
                $initials = 'U';
            }
            $rank = $index + 1;
            $badge = $rank === 1 ? 'Champion' : ($rank === 2 ? 'Trailblazer' : 'Rising Star');
        ?>
        <article class="leaderboard-top-card leaderboard-top-card--<?php echo $rank; ?>">
            <span class="leaderboard-top-rank">#<?php echo $rank; ?></span>
            <span class="leaderboard-top-avatar" aria-hidden="true"><?php echo htmlspecialchars($initials); ?></span>
            <h2 class="leaderboard-top-name"><?php echo htmlspecialchars($displayName); ?></h2>
            <p class="leaderboard-top-points"><?php echo $points; ?> pts</p>
            <span class="leaderboard-top-badge"><?php echo htmlspecialchars($badge); ?></span>
        </article>
        <?php endforeach; ?>
    </section>

    <?php if (!empty($remainingLeaders)): ?>
    <section class="leaderboard-list" aria-labelledby="leaderboard-table-heading">
        <h2 id="leaderboard-table-heading" class="leaderboard-list-title">More top performers</h2>
        <ol class="leaderboard-table">
            <?php foreach ($remainingLeaders as $index => $row):
                $rank = $index + 4;
                $uid    = (int) $row['user_id'];
                $points = (int) $row['total'];
                $name   = trim((string) ($row['name'] ?? ''));
                $displayName = $name !== '' ? $name : ('User ' . $uid);
                $percent = $maxPoints > 0 ? max(5, (int) round(($points / $maxPoints) * 100)) : 0;
            ?>
            <li class="leaderboard-table__row" style="--leaderboard-progress: <?php echo $percent; ?>%;">
                <span class="leaderboard-table__rank">#<?php echo $rank; ?></span>
                <span class="leaderboard-table__name"><?php echo htmlspecialchars($displayName); ?></span>
                <span class="leaderboard-table__bar"><span></span></span>
                <span class="leaderboard-table__points"><?php echo $points; ?> pts</span>
            </li>
            <?php endforeach; ?>
        </ol>
    </section>
    <?php endif; ?>
    <?php else: ?>
    <div class="leaderboard-empty">
        <p>No users have earned points yet. Be the first to start climbing!</p>
        <a class="leaderboard-empty__cta" href="/Pathanto/dashboard.php">Practice a question</a>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/footer.php'; ?>
