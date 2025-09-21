<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/progress.php';

header('Content-Type: application/json');

$userId = current_user_id();
if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

$action = $_POST['action'] ?? '';
$feedId = isset($_POST['feed_id']) ? (int) $_POST['feed_id'] : 0;

if (!$feedId) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid feed identifier.']);
    exit;
}

$entry = get_feed_entry($feedId, $userId);
if (!$entry) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Feed item not found.']);
    exit;
}

switch ($action) {
    case 'react':
        handle_reaction($feedId, $userId);
        break;
    case 'comment':
        handle_comment($feedId, $userId);
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Unknown action.']);
        exit;
}

$updatedEntry = get_feed_entry($feedId, $userId);
if (!$updatedEntry) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Unable to refresh feed entry.']);
    exit;
}

echo json_encode([
    'success' => true,
    'feed' => $updatedEntry,
]);
exit;

function handle_reaction(int $feedId, int $userId): void
{
    global $conn;
    $reactionInput = $_POST['reaction'] ?? '';
    $reactionValue = null;

    if ($reactionInput === 'like') {
        $reactionValue = 1;
    } elseif ($reactionInput === 'dislike') {
        $reactionValue = 0;
    }

    if ($reactionValue === null) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Unknown reaction type.']);
        exit;
    }

    $stmt = $conn->prepare('INSERT INTO question_feed_reactions (feed_id, user_id, reaction) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE reaction = VALUES(reaction), created_at = CURRENT_TIMESTAMP');
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to update reaction.']);
        exit;
    }
    $stmt->bind_param('iii', $feedId, $userId, $reactionValue);
    if (!$stmt->execute()) {
        $stmt->close();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to update reaction.']);
        exit;
    }
    $stmt->close();
}

function handle_comment(int $feedId, int $userId): void
{
    global $conn;
    $comment = trim($_POST['comment'] ?? '');
    if ($comment === '') {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Comment cannot be empty.']);
        exit;
    }

    if (function_exists('mb_substr')) {
        $comment = mb_substr($comment, 0, 400);
    } else {
        $comment = substr($comment, 0, 400);
    }

    $stmt = $conn->prepare('INSERT INTO question_feed_comments (feed_id, user_id, comment_text) VALUES (?, ?, ?)');
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to add comment.']);
        exit;
    }
    $stmt->bind_param('iis', $feedId, $userId, $comment);
    if (!$stmt->execute()) {
        $stmt->close();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to add comment.']);
        exit;
    }
    $stmt->close();
}
