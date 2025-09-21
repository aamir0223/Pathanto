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
$totalQuestions = (int) ($summary['total'] ?? 0);
$answeredQuestions = (int) ($summary['answered'] ?? 0);
$percent = $totalQuestions > 0 ? round($answeredQuestions / $totalQuestions * 100, 1) : 0;
$streakDays = (int) ($dashboard['streak'] ?? 0);
$pointsTotal = (int) ($dashboard['points'] ?? 0);

include __DIR__ . '/header.php';
?>
<main class="dashboard-page">
    <section class="dashboard-hero">
        <div class="dashboard-hero__content">
            <p class="dashboard-hero__eyebrow">Dashboard</p>
            <h1 class="dashboard-hero__title">Welcome back, <?php echo htmlspecialchars($userName); ?></h1>
            <p class="dashboard-hero__subtitle">You're on a <?php echo $streakDays; ?> day streak. Keep the momentum
                going!</p>
        </div>
        <div class="dashboard-hero__stats">
            <div class="metric-card">
                <p class="metric-card__label">Total points</p>
                <p class="metric-card__value"><?php echo $pointsTotal; ?></p>
                <span class="metric-card__hint">Earn more by completing quizzes</span>
            </div>
            <div class="metric-card">
                <p class="metric-card__label">Questions answered</p>
                <p class="metric-card__value"><?php echo $answeredQuestions; ?></p>
                <span class="metric-card__hint">Out of <?php echo $totalQuestions; ?> tracked</span>
            </div>
            <div class="metric-card metric-card--progress">
                <p class="metric-card__label">Overall progress</p>
                <p class="metric-card__value"><?php echo $percent; ?><span class="metric-card__unit">%</span></p>
                <div class="metric-card__progress" role="progressbar" aria-valuemin="0"
                    aria-valuemax="100" aria-valuenow="<?php echo $percent; ?>">
                    <span class="metric-card__progress-fill" style="width: <?php echo $percent; ?>%"></span>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-column">
            <article class="dashboard-card">
                <header class="dashboard-card__header">
                    <h2 class="dashboard-card__title">Daily goal</h2>
                    <p class="dashboard-card__meta">Set a realistic target to stay consistent.</p>
                </header>
                <form method="post" class="dashboard-form">
                    <label class="dashboard-form__label" for="daily_goal">Questions per day</label>
                    <div class="dashboard-form__row">
                        <input class="dashboard-form__input" type="number" name="daily_goal" id="daily_goal" min="0"
                            value="<?php echo (int) $dailyGoal; ?>">
                        <button type="submit" class="dashboard-form__button">Save goal</button>
                    </div>
                </form>
            </article>

            <article class="dashboard-card">
                <header class="dashboard-card__header">
                    <h2 class="dashboard-card__title">Recommended questions</h2>
                    <p class="dashboard-card__meta">Fine-tune suggestions by topic or difficulty.</p>
                </header>
                <form method="get" class="dashboard-form dashboard-form--filters">
                    <div class="dashboard-form__group">
                        <label class="dashboard-form__label" for="filter_topic">Topic</label>
                        <input class="dashboard-form__input" type="text" name="topic" id="filter_topic"
                            value="<?php echo htmlspecialchars($filters['topic'] ?? ''); ?>" placeholder="e.g. Algebra">
                    </div>
                    <div class="dashboard-form__group">
                        <label class="dashboard-form__label" for="filter_difficulty">Difficulty</label>
                        <input class="dashboard-form__input" type="text" name="difficulty" id="filter_difficulty"
                            value="<?php echo htmlspecialchars($filters['difficulty'] ?? ''); ?>"
                            placeholder="e.g. Medium">
                    </div>
                    <button type="submit" class="dashboard-form__button dashboard-form__button--full">Apply filters</button>
                </form>
                <?php if (!empty($recommended)): ?>
                <ul class="dashboard-list">
                    <?php foreach ($recommended as $question): ?>
                    <li class="dashboard-list__item">
                        <span class="dashboard-list__text"><?php echo htmlspecialchars($question['question_text']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="dashboard-empty-state">No recommendations right now. Try updating your filters.</p>
                <?php endif; ?>
            </article>

            <article class="dashboard-card">
                <header class="dashboard-card__header">
                    <h2 class="dashboard-card__title">Recent attempts</h2>
                    <p class="dashboard-card__meta">See how you performed on your latest questions.</p>
                </header>
                <?php if (!empty($dashboard['recent'])): ?>
                <ul class="dashboard-list dashboard-list--attempts">
                    <?php foreach ($dashboard['recent'] as $attempt):
                        $statusLabel = $attempt['correct'] ? 'Correct' : 'Incorrect';
                        $statusClass = $attempt['correct'] ? 'is-correct' : 'is-incorrect';
                    ?>
                    <li class="dashboard-list__item">
                        <span class="dashboard-list__text"><?php echo htmlspecialchars($attempt['question_text']); ?></span>
                        <span class="dashboard-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                        <span class="dashboard-list__meta"><?php echo htmlspecialchars($attempt['created_at']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="dashboard-empty-state">Attempt more questions to see your recent history here.</p>
                <?php endif; ?>
            </article>
        </div>

        <div class="dashboard-column">
            <article class="dashboard-card">
                <header class="dashboard-card__header">
                    <h2 class="dashboard-card__title">Topic accuracy</h2>
                    <p class="dashboard-card__meta">Identify areas that may need a bit more practice.</p>
                </header>
                <?php if (!empty($dashboard['topics'])): ?>
                <ul class="dashboard-list dashboard-list--topics">
                    <?php foreach ($dashboard['topics'] as $topic => $acc):
                        $topicPercent = max(0, min(100, round($acc * 100, 1)));
                    ?>
                    <li class="dashboard-list__item">
                        <div class="topic-accuracy">
                            <p class="topic-accuracy__label">Topic <?php echo htmlspecialchars($topic); ?></p>
                            <div class="topic-accuracy__bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                aria-valuenow="<?php echo $topicPercent; ?>">
                                <span class="topic-accuracy__fill" style="width: <?php echo $topicPercent; ?>%"></span>
                            </div>
                        </div>
                        <span class="topic-accuracy__value"><?php echo $topicPercent; ?>%</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="dashboard-empty-state">Topic accuracy will appear once you answer a few questions.</p>
                <?php endif; ?>
            </article>

            <article class="dashboard-card">
                <header class="dashboard-card__header">
                    <h2 class="dashboard-card__title">Leaderboard</h2>
                    <p class="dashboard-card__meta">Compare your progress with the community.</p>
                </header>
                <?php if (!empty($leaders)): ?>
                <ol class="dashboard-leaderboard">
                    <?php foreach ($leaders as $index => $row):
                        $uid    = (int) $row['user_id'];
                        $points = (int) $row['total'];
                        $name   = $row['name'] ?? ('User ' . $uid);
                    ?>
                    <li class="dashboard-leaderboard__item">
                        <span class="dashboard-leaderboard__rank">#<?php echo $index + 1; ?></span>
                        <span class="dashboard-leaderboard__name"><?php echo htmlspecialchars($name); ?></span>
                        <span class="dashboard-leaderboard__points"><?php echo $points; ?> pts</span>
                    </li>
                    <?php endforeach; ?>
                </ol>
                <p class="dashboard-card__footer"><a href="/Pathanto/leaderboard.php">View full leaderboard</a></p>
                <?php else: ?>
                <p class="dashboard-empty-state">No leaderboard data yet. Be the first to climb the ranks!</p>
                <?php endif; ?>
            </article>
        </div>
    </section>
</main>
<?php include __DIR__ . '/footer.php'; ?>
