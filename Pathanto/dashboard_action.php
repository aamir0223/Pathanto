<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/gamification.php';
require_once __DIR__ . '/progress.php';
require_once __DIR__ . '/personalization.php';

header('Content-Type: application/json');

$userId = current_user_id();
if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

$action = $_POST['action'] ?? '';
if ($action !== 'answer_question') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Unknown action.']);
    exit;
}

$questionId = isset($_POST['questionId']) ? (int) $_POST['questionId'] : 0;
$result     = $_POST['result'] ?? '';
$note       = trim($_POST['note'] ?? '');
$shareFlag  = isset($_POST['share']) ? filter_var($_POST['share'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : true;

if (!$questionId || !in_array($result, ['correct', 'incorrect'], true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid question submission.']);
    exit;
}

$questionMeta = fetch_question_meta($questionId);
if (!$questionMeta) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Question not found.']);
    exit;
}

$isCorrect = $result === 'correct';
record_attempt($userId, $questionId, $isCorrect, $questionMeta['topic_id'], $questionMeta['difficulty'], 0);

if ($isCorrect) {
    add_points($userId, POINTS_CORRECT_ANSWER, 'dashboard answer');
}

if ($shareFlag) {
    $trimmer = function_exists('mb_substr') ? 'mb_substr' : 'substr';
    $trimmedNote = $note !== '' ? $trimmer($note, 0, 400) : '';
    if (!publish_attempt($userId, $questionId, $isCorrect, $trimmedNote)) {
        error_log('dashboard_action: failed to publish attempt for question ' . $questionId . ' by user ' . $userId);
    }
}

$feed = get_public_feed(6, $userId);

echo json_encode([
    'success' => true,
    'message' => $isCorrect ? 'Great job! Progress recorded.' : 'Attempt recorded. Keep practicing!',
    'feed'    => array_map(static function ($item) {
        return [
            'question' => $item['question_text'],
            'user'     => $item['name'],
            'correct'  => (bool) $item['correct'],
            'note'     => $item['note'],
            'time'     => $item['created_at'],
        ];
    }, $feed),
]);
exit;

function fetch_question_meta(int $questionId): ?array
{
    global $conn;

    $meta = get_question_table_metadata();
    $topicColumn = $meta['topic_column'];
    $difficultyColumn = $meta['difficulty_column'];

    $selects = ['id'];
    if ($topicColumn) {
        $selects[] = $topicColumn . ' AS topic_id';
    } else {
        $selects[] = 'NULL AS topic_id';
    }
    if ($difficultyColumn) {
        $selects[] = $difficultyColumn . ' AS difficulty';
    } else {
        $selects[] = 'NULL AS difficulty';
    }

    $sql = 'SELECT ' . implode(', ', $selects) . ' FROM questions WHERE id = ? LIMIT 1';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('fetch_question_meta prepare failed: ' . $conn->error);
        return null;
    }
    $stmt->bind_param('i', $questionId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}
