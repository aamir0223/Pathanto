<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/progress.php';
require_once __DIR__ . '/personalization.php';
require_once __DIR__ . '/gamification.php';
require_login();
$userId = current_user_id();
$userName = get_user_name($userId) ?? ('User ' . $userId);

if (!function_exists('dashboard_slugify')) {
    function dashboard_slugify(string $text): string
    {
        $slug = strtolower($text);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }
}

// Allow users to update their daily question goal.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daily_goal'])) {
    $goal = max(0, (int) $_POST['daily_goal']);
    set_daily_goal($userId, $goal);
}

$selectedTopic = isset($_GET['topic']) && $_GET['topic'] !== '' ? (int) $_GET['topic'] : null;
$selectedDifficulty = isset($_GET['difficulty']) && $_GET['difficulty'] !== '' ? $_GET['difficulty'] : null;
$filters      = ['topic' => $selectedTopic, 'difficulty' => $selectedDifficulty];
$topicOptions = get_topic_options();
$difficultyOptions = get_difficulty_options();
$topicLabelMap = [];
foreach ($topicOptions as $topicOption) {
    $topicLabelMap[(string) $topicOption['id']] = $topicOption['label'];
}
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
$publicFeed = get_public_feed(6);

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
                        <select class="dashboard-form__input" name="topic" id="filter_topic">
                            <option value="">All topics</option>
                            <?php foreach ($topicOptions as $topic):
                                $value = $topic['id'];
                                $isSelected = $selectedTopic !== null && (string) $selectedTopic === (string) $value;
                            ?>
                            <option value="<?php echo htmlspecialchars($value); ?>" <?php echo $isSelected ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($topic['label']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="dashboard-form__group">
                        <label class="dashboard-form__label" for="filter_difficulty">Medium</label>
                        <select class="dashboard-form__input" name="difficulty" id="filter_difficulty">
                            <option value="">All levels</option>
                            <?php foreach ($difficultyOptions as $difficulty):
                                $value = $difficulty['value'];
                                $isSelected = $selectedDifficulty !== null && $selectedDifficulty === $value;
                            ?>
                            <option value="<?php echo htmlspecialchars($value); ?>" <?php echo $isSelected ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($difficulty['label']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="dashboard-form__button dashboard-form__button--full">Apply filters</button>
                </form>
                <?php if (!empty($recommended)): ?>
                <ul class="dashboard-list dashboard-list--questions">
                    <?php foreach ($recommended as $question):
                        $slug = dashboard_slugify($question['question_text']);
                        $solutionUrl = '/Pathanto/Questions/answer/' . (int) $question['id'] . '/' . $slug;
                        $difficultyLabel = !empty($question['difficulty']) ? ucfirst($question['difficulty']) : null;
                    ?>
                    <li class="dashboard-list__item dashboard-question" data-question-id="<?php echo (int) $question['id']; ?>"
                        data-question-solution="<?php echo htmlspecialchars($solutionUrl, ENT_QUOTES); ?>">
                        <div class="dashboard-question__header">
                            <span class="dashboard-question__text"><?php echo htmlspecialchars($question['question_text']); ?></span>
                            <button type="button" class="dashboard-question__toggle" data-dashboard-question-toggle>Answer</button>
                        </div>
                        <?php
                            $topicId = $question['topic_id'] ?? null;
                            $topicLabel = $topicId !== null ? ($topicLabelMap[(string) $topicId] ?? (is_numeric($topicId) ? 'Topic ' . $topicId : (string) $topicId)) : null;
                            $tags = [];
                            if (!empty($question['tags'])) {
                                $tags = array_filter(array_map('trim', explode(',', $question['tags'])));
                            }
                        ?>
                        <?php if ($topicLabel || $difficultyLabel): ?>
                        <p class="dashboard-question__meta">
                            <?php if ($topicLabel): ?>
                            <span><strong>Topic:</strong> <?php echo htmlspecialchars($topicLabel); ?></span>
                            <?php endif; ?>
                            <?php if ($difficultyLabel): ?>
                            <span><strong>Medium:</strong> <?php echo htmlspecialchars($difficultyLabel); ?></span>
                            <?php endif; ?>
                        </p>
                        <?php endif; ?>
                        <?php if (!empty($tags)): ?>
                        <div class="dashboard-question__tags" aria-label="Subtopics">
                            <?php foreach ($tags as $tag): ?>
                            <span class="dashboard-question__tag"><?php echo htmlspecialchars($tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <form class="dashboard-question__form" data-dashboard-question-form hidden>
                            <fieldset class="dashboard-question__choices">
                                <legend class="sr-only">How did you do?</legend>
                                <label class="dashboard-question__choice">
                                    <input type="radio" name="result-<?php echo (int) $question['id']; ?>" value="correct" required>
                                    <span>I answered correctly</span>
                                </label>
                                <label class="dashboard-question__choice">
                                    <input type="radio" name="result-<?php echo (int) $question['id']; ?>" value="incorrect" required>
                                    <span>I need more practice</span>
                                </label>
                            </fieldset>
                            <label class="dashboard-question__share">
                                <input type="checkbox" name="share" value="1" checked>
                                <span>Share this attempt with the community feed</span>
                            </label>
                            <textarea class="dashboard-question__note" name="note" maxlength="400"
                                placeholder="Add a short explanation (optional)"></textarea>
                            <div class="dashboard-question__actions">
                                <button type="submit" class="dashboard-form__button">Submit answer</button>
                                <a class="dashboard-question__link" href="<?php echo htmlspecialchars($solutionUrl); ?>"
                                    target="_blank" rel="noopener">View solution</a>
                            </div>
                            <p class="dashboard-question__status" data-dashboard-question-status hidden></p>
                        </form>
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
                        $topicLabel = $topicLabelMap[(string) $topic] ?? (is_numeric($topic) ? 'Topic ' . $topic : (string) $topic);
                    ?>
                    <li class="dashboard-list__item">
                        <div class="topic-accuracy">
                            <p class="topic-accuracy__label"><?php echo htmlspecialchars($topicLabel); ?></p>
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
                    <h2 class="dashboard-card__title">Community feed</h2>
                    <p class="dashboard-card__meta">See how other learners are progressing.</p>
                </header>
                <?php if (!empty($publicFeed)): ?>
                <ul class="dashboard-feed" id="dashboard-feed">
                    <?php foreach ($publicFeed as $item):
                        $statusLabel = $item['correct'] ? 'Correct' : 'Needs review';
                        $statusClass = $item['correct'] ? 'is-correct' : 'is-incorrect';
                        $timeObj = new DateTime($item['created_at']);
                        $displayTime = $timeObj->format('M j, g:i a');
                        $isoTime = $timeObj->format(DateTime::ATOM);
                    ?>
                    <li class="dashboard-feed__item <?php echo $statusClass; ?>">
                        <div class="dashboard-feed__header">
                            <span class="dashboard-feed__user"><?php echo htmlspecialchars($item['name']); ?></span>
                            <span class="dashboard-feed__status"><?php echo $statusLabel; ?></span>
                        </div>
                        <p class="dashboard-feed__question"><?php echo htmlspecialchars($item['question_text']); ?></p>
                        <?php if (!empty($item['note'])): ?>
                        <p class="dashboard-feed__note"><?php echo nl2br(htmlspecialchars($item['note'])); ?></p>
                        <?php endif; ?>
                        <time class="dashboard-feed__time" datetime="<?php echo $isoTime; ?>"><?php echo $displayTime; ?></time>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="dashboard-empty-state" id="dashboard-feed-empty">No public attempts yet. Share your progress to get things started!</p>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const feedEmpty = document.getElementById('dashboard-feed-empty');
    let feedContainer = document.getElementById('dashboard-feed');

    const ensureFeedContainer = () => {
        if (!feedContainer && feedEmpty) {
            feedContainer = document.createElement('ul');
            feedContainer.id = 'dashboard-feed';
            feedContainer.className = 'dashboard-feed';
            feedEmpty.before(feedContainer);
        }
        return feedContainer;
    };

    const renderFeed = (items) => {
        const container = ensureFeedContainer();
        if (!container) {
            return;
        }
        if (!items || !items.length) {
            container.innerHTML = '';
            container.hidden = true;
            if (feedEmpty) {
                feedEmpty.hidden = false;
            }
            return;
        }

        const formatter = new Intl.DateTimeFormat(undefined, {
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });

        container.hidden = false;
        container.innerHTML = items.map((item) => {
            const statusClass = item.correct ? 'is-correct' : 'is-incorrect';
            const statusLabel = item.correct ? 'Correct' : 'Needs review';
            const date = new Date(item.time);
            const displayTime = Number.isNaN(date.getTime()) ? '' : formatter.format(date);
            const safeNote = item.note ? item.note.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>') : '';
            const safeQuestion = item.question.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            const safeUser = item.user.replace(/</g, '&lt;').replace(/>/g, '&gt;');

            return `
                <li class="dashboard-feed__item ${statusClass}">
                    <div class="dashboard-feed__header">
                        <span class="dashboard-feed__user">${safeUser}</span>
                        <span class="dashboard-feed__status">${statusLabel}</span>
                    </div>
                    <p class="dashboard-feed__question">${safeQuestion}</p>
                    ${safeNote ? `<p class="dashboard-feed__note">${safeNote}</p>` : ''}
                    ${displayTime ? `<time class="dashboard-feed__time">${displayTime}</time>` : ''}
                </li>
            `;
        }).join('');

        if (feedEmpty) {
            feedEmpty.hidden = true;
        }
    };

    document.querySelectorAll('[data-dashboard-question-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const container = button.closest('.dashboard-question');
            const form = container.querySelector('[data-dashboard-question-form]');
            if (!form) {
                return;
            }
            const isHidden = form.hasAttribute('hidden');
            if (isHidden) {
                form.removeAttribute('hidden');
                button.textContent = 'Hide';
            } else {
                form.setAttribute('hidden', '');
                button.textContent = 'Answer';
            }
        });
    });

    document.querySelectorAll('[data-dashboard-question-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const container = form.closest('.dashboard-question');
            const questionId = container.dataset.questionId;
            const status = form.querySelector('[data-dashboard-question-status]');
            const toggle = container.querySelector('[data-dashboard-question-toggle]');
            const submitButton = form.querySelector('button[type="submit"]');
            const radio = form.querySelector('input[type="radio"]:checked');
            const shareInput = form.querySelector('input[name="share"]');
            const noteField = form.querySelector('textarea[name="note"]');

            if (!questionId || !radio) {
                if (status) {
                    status.textContent = 'Please choose whether you were correct or need more practice.';
                    status.classList.add('is-error');
                    status.removeAttribute('hidden');
                }
                return;
            }

            const formData = new FormData();
            formData.append('action', 'answer_question');
            formData.append('questionId', questionId);
            formData.append('result', radio.value);
            formData.append('share', shareInput && shareInput.checked ? 'true' : 'false');
            if (noteField && noteField.value.trim()) {
                formData.append('note', noteField.value.trim());
            }

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.dataset.originalText = submitButton.textContent;
                submitButton.textContent = 'Saving...';
            }
            if (status) {
                status.textContent = '';
                status.classList.remove('is-error', 'is-success');
            }

            try {
                const response = await fetch('/Pathanto/dashboard_action.php', {
                    method: 'POST',
                    body: formData
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Unable to save attempt.');
                }

                if (status) {
                    status.textContent = payload.message;
                    status.classList.add('is-success');
                    status.removeAttribute('hidden');
                }

                form.querySelectorAll('input, textarea').forEach((el) => {
                    el.disabled = true;
                });
                form.classList.add('is-complete');
                container.classList.add('dashboard-question--completed');
                if (toggle) {
                    toggle.textContent = 'Completed';
                    toggle.disabled = true;
                }
                if (noteField) {
                    noteField.value = '';
                }
                form.setAttribute('hidden', '');

                if (payload.feed) {
                    renderFeed(payload.feed);
                }
            } catch (error) {
                if (status) {
                    status.textContent = error.message;
                    status.classList.add('is-error');
                    status.removeAttribute('hidden');
                }
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = submitButton.dataset.originalText || 'Submit answer';
                }
            }
        });
    });
});
</script>
<?php include __DIR__ . '/footer.php'; ?>
