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

$filters      = [];
$topicOptions = get_topic_options();
$topicLabelMap = [];
foreach ($topicOptions as $topicOption) {
    $topicLabelMap[(string) $topicOption['id']] = $topicOption['label'];
}
$dashboard    = get_dashboard($userId);
$summary      = get_progress_summary($userId);
$dailyGoal    = get_daily_goal($userId);
$recommended  = recommend_questions($userId, 5, $filters);
$recommendationFallback = false;
if (empty($recommended)) {
    $recommended = get_default_questions(5);
    $recommendationFallback = !empty($recommended);
}
$leaders      = get_leaderboard('all', 5);
$totalQuestions = (int) ($summary['total'] ?? 0);
$answeredQuestions = (int) ($summary['answered'] ?? 0);
$rawPercent = $totalQuestions > 0 ? ($answeredQuestions / $totalQuestions) * 100 : 0;
$percent = round(min(100, $rawPercent), 1);
$displayAnswered = min($answeredQuestions, $totalQuestions);
$extraAnswered = max(0, $answeredQuestions - $totalQuestions);
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
                <p class="metric-card__context">
                    <?php echo $displayAnswered; ?> of <?php echo $totalQuestions; ?> unique questions answered<?php if ($extraAnswered > 0) { echo ' <span class="metric-card__context-extra">(+' . $extraAnswered . ' beyond the tracked set)</span>'; } ?>
                </p>
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
                    <p class="dashboard-card__meta">Curated practice picked from your recent activity.</p>
                </header>
                <?php if (!empty($recommended)): ?>
                <?php if ($recommendationFallback): ?>
                <p class="dashboard-card__meta" style="color:#6366f1;">Here are a few fresh questions to get you started.</p>
                <?php endif; ?>
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
                            <button type="button" class="dashboard-question__toggle" data-dashboard-question-open>Answer</button>
                        </div>
                        <?php
                            $topicId = $question['topic_id'] ?? null;
                            $topicLabel = $topicId !== null ? ($topicLabelMap[(string) $topicId] ?? (is_numeric($topicId) ? 'Topic ' . $topicId : (string) $topicId)) : null;
                            $tags = [];
                            if (!empty($question['tags'])) {
                                $tags = array_filter(array_map('trim', explode(',', $question['tags'])));
                            }
                            $relatedQuestions = [];
                            if (empty($tags)) {
                                $relatedQuestions = get_related_questions((int) $question['id'], $topicId, 3);
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
                        <?php elseif (!empty($relatedQuestions)): ?>
                        <div class="dashboard-question__related">
                            <p class="dashboard-question__related-title">Related questions</p>
                            <ul class="dashboard-question__related-list">
                                <?php foreach ($relatedQuestions as $related):
                                    $relatedSlug = dashboard_slugify($related['question_text']);
                                    $relatedUrl = '/Pathanto/Questions/answer/' . (int) $related['id'] . '/' . $relatedSlug;
                                ?>
                                <li><a href="<?php echo htmlspecialchars($relatedUrl); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($related['question_text']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        <div class="dashboard-question__modal" data-dashboard-question-modal hidden>
                            <div class="dashboard-modal__backdrop" data-dashboard-question-close></div>
                            <div class="dashboard-modal" role="dialog" aria-modal="true"
                                aria-labelledby="question-modal-title-<?php echo (int) $question['id']; ?>">
                                <button type="button" class="dashboard-modal__close" data-dashboard-question-close
                                    aria-label="Close">
                                    &times;
                                </button>
                                <header class="dashboard-modal__header">
                                    <h3 class="dashboard-modal__title" id="question-modal-title-<?php echo (int) $question['id']; ?>">
                                        Log your attempt
                                    </h3>
                                    <p class="dashboard-modal__question"><?php echo htmlspecialchars($question['question_text']); ?></p>
                                </header>
                                <form class="dashboard-question__form" data-dashboard-question-form>
                                    <textarea class="dashboard-question__note" name="note" maxlength="400"
                                        placeholder="Add a short explanation (optional)"></textarea>
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
                                    <div class="dashboard-question__actions">
                                        <button type="submit" class="dashboard-form__button">Submit answer</button>
                                        <a class="dashboard-question__link" href="<?php echo htmlspecialchars($solutionUrl); ?>"
                                            target="_blank" rel="noopener">View solution</a>
                                    </div>
                                    <p class="dashboard-question__status" data-dashboard-question-status hidden></p>
                                </form>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="dashboard-empty-state">No recommendations right now. Check back after a few more attempts.</p>
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
                    <?php foreach ($dashboard['topics'] as $topic => $topicData):
                        $topicAccuracy = is_array($topicData) ? (float) ($topicData['accuracy'] ?? 0) : (float) $topicData;
                        $topicPercent = max(0, min(100, round($topicAccuracy * 100, 1)));
                        $topicCorrect = is_array($topicData) ? (int) ($topicData['correct'] ?? 0) : null;
                        $topicAttempts = is_array($topicData) ? (int) ($topicData['attempts'] ?? 0) : null;
                        $topicLabel = $topicLabelMap[(string) $topic] ?? (is_numeric($topic) ? 'Topic ' . $topic : (string) $topic);
                    ?>
                    <li class="dashboard-list__item">
                        <div class="topic-accuracy">
                            <p class="topic-accuracy__label"><?php echo htmlspecialchars($topicLabel); ?></p>
                            <div class="topic-accuracy__bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                aria-valuenow="<?php echo $topicPercent; ?>">
                                <span class="topic-accuracy__fill" style="width: <?php echo $topicPercent; ?>%"></span>
                            </div>
                            <?php if ($topicAttempts): ?>
                            <p class="topic-accuracy__stats"><?php echo $topicCorrect; ?> of <?php echo $topicAttempts; ?> correct</p>
                            <?php endif; ?>
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
                        $answerText = $item['answer_text'] ?? '';
                        $likes = (int) ($item['likes'] ?? 0);
                        $dislikes = (int) ($item['dislikes'] ?? 0);
                        $userReaction = $item['user_reaction'] ?? null;
                        $comments = $item['comments'] ?? [];
                    ?>
                    <li class="dashboard-feed__item <?php echo $statusClass; ?>" data-feed-item data-feed-id="<?php echo (int) $item['id']; ?>">
                        <div class="dashboard-feed__header">
                            <span class="dashboard-feed__user"><?php echo htmlspecialchars($item['user']); ?></span>
                            <span class="dashboard-feed__status"><?php echo $statusLabel; ?></span>
                        </div>
                        <p class="dashboard-feed__question"><?php echo htmlspecialchars($item['question_text']); ?></p>
                        <p class="dashboard-feed__question-id">Question #<?php echo (int) $item['question_id']; ?></p>
                        <?php if ($answerText !== ''): ?>
                        <p class="dashboard-feed__answer-label">Answer</p>
                        <p class="dashboard-feed__answer"><?php echo nl2br(htmlspecialchars($answerText)); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($item['note'])): ?>
                        <p class="dashboard-feed__note"><?php echo nl2br(htmlspecialchars($item['note'])); ?></p>
                        <?php endif; ?>
                        <div class="dashboard-feed__reactions">
                            <button type="button" class="dashboard-feed__reaction<?php echo $userReaction === 'like' ? ' is-active' : ''; ?>" data-feed-react data-reaction="like">
                                👍 <span><?php echo $likes; ?></span>
                            </button>
                            <button type="button" class="dashboard-feed__reaction<?php echo $userReaction === 'dislike' ? ' is-active' : ''; ?>" data-feed-react data-reaction="dislike">
                                👎 <span><?php echo $dislikes; ?></span>
                            </button>
                        </div>
                        <div class="dashboard-feed__comments">
                            <ul class="dashboard-feed__comments-list">
                                <?php foreach ($comments as $comment):
                                    $commentTime = new DateTime($comment['created_at']);
                                ?>
                                <li class="dashboard-feed__comment">
                                    <span class="dashboard-feed__comment-user"><?php echo htmlspecialchars($comment['user']); ?></span>
                                    <span class="dashboard-feed__comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></span>
                                    <time class="dashboard-feed__comment-time" datetime="<?php echo $commentTime->format(DateTime::ATOM); ?>"><?php echo $commentTime->format('M j, g:i a'); ?></time>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <form class="dashboard-feed__comment-form" data-feed-comment-form>
                                <input type="text" name="comment" maxlength="400" class="dashboard-feed__comment-input" placeholder="Add a comment...">
                                <button type="submit" class="dashboard-feed__comment-submit">Post</button>
                            </form>
                        </div>
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

    const body = document.body;

    const closeModal = (modal) => {
        if (!modal) {
            return;
        }
        modal.setAttribute('hidden', '');
        modal.classList.remove('is-visible');
        if (!document.querySelector('[data-dashboard-question-modal]:not([hidden])')) {
            body.classList.remove('dashboard-has-open-modal');
        }
    };

    document.querySelectorAll('[data-dashboard-question-open]').forEach((button) => {
        button.addEventListener('click', () => {
            const container = button.closest('.dashboard-question');
            const modal = container.querySelector('[data-dashboard-question-modal]');
            if (!modal) {
                return;
            }
            modal.removeAttribute('hidden');
            modal.classList.add('is-visible');
            body.classList.add('dashboard-has-open-modal');
            const firstField = modal.querySelector('input[type="radio"]');
            if (firstField) {
                firstField.focus();
            }
        });
    });

    document.querySelectorAll('[data-dashboard-question-close]').forEach((button) => {
        button.addEventListener('click', () => {
            const modal = button.closest('[data-dashboard-question-modal]');
            closeModal(modal);
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            const openModal = document.querySelector('[data-dashboard-question-modal]:not([hidden])');
            if (openModal) {
                closeModal(openModal);
            }
        }
    });

    document.querySelectorAll('[data-dashboard-question-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const container = form.closest('.dashboard-question');
            const questionId = container.dataset.questionId;
            const status = form.querySelector('[data-dashboard-question-status]');
            const modal = form.closest('[data-dashboard-question-modal]');
            const openButton = container.querySelector('[data-dashboard-question-open]');
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
                if (openButton) {
                    openButton.textContent = 'Completed';
                    openButton.disabled = true;
                }
                if (noteField) {
                    noteField.value = '';
                }

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
                    submitButton.textContent = submitButton.dataset.originalText || 'Submit answer';
                    submitButton.disabled = form.classList.contains('is-complete');
                }
            }
        });
    });
});
</script>
<?php include __DIR__ . '/footer.php'; ?>
