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
$shareFlagRaw = $_POST['share'] ?? null;
$shareFlag = false;
if ($shareFlagRaw !== null) {
    $s = strtolower((string)$shareFlagRaw);
    $shareFlag = in_array($s, ['1','true','on','yes'], true);
}

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
$trimmer = function_exists('mb_substr') ? 'mb_substr' : 'substr';
$trimmedNote = $note !== '' ? $trimmer($note, 0, 400) : '';

$attemptId = record_attempt($userId, $questionId, $isCorrect, $questionMeta['topic_id'], $questionMeta['difficulty'], 0, $trimmedNote);
if ($attemptId === null) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Unable to record your attempt. Please try again.']);
    exit;
}

if ($isCorrect) {
    add_points($userId, POINTS_CORRECT_ANSWER, 'dashboard answer');
}


$sharedToFeed = false;
if ($shareFlag) {
    $sql = "INSERT INTO question_feed (user_id, question_id, correct, note, created_at)
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $correctVal = $isCorrect ? 1 : 0;
        $noteVal = $trimmedNote !== '' ? $trimmedNote : null;
        $stmt->bind_param("iiis", $userId, $questionId, $correctVal, $noteVal);
        if ($stmt->execute()) {
            $sharedToFeed = true;
        } else {
            error_log('question_feed insert failed: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log('question_feed prepare failed: ' . $conn->error);
    }
}

$feed = get_public_feed(6, $userId);

$response = [
    'success' => true,
    'shared'  => $sharedToFeed,
    'message' => $isCorrect ? 'Great job! Progress recorded.' : 'Attempt recorded. Keep practicing!',
    'feed'    => array_map(static function ($item) {
        return [
            'question' => $item['question_text'],
            'question_id' => $item['question_id'] ?? null,
            'answer'   => $item['answer_text'] ?? '',
            'user'     => $item['user'] ?? '',
            'correct'  => (bool) $item['correct'],
            'note'     => $item['note'] ?? '',
            'time'     => $item['created_at'],
        ];
    }, $feed),
];

if ($shareFlag && !$sharedToFeed) {
    $response['message'] .= ' (Shared to community feed: failed)';
}

echo json_encode($response);
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
