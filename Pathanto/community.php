<?php
// Social and community utilities: comments and challenges.
require_once __DIR__ . '/config.php';

function add_comment($userId, $questionId, $content, $parentId = null)
{
    global $conn;
    $stmt = $conn->prepare('INSERT INTO comments (user_id, question_id, content, parent_id) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('iisi', $userId, $questionId, $content, $parentId);
    $stmt->execute();
    $stmt->close();
}

function get_comments($questionId)
{
    global $conn;
    $stmt = $conn->prepare('SELECT id, user_id, content, parent_id, created_at FROM comments WHERE question_id = ? ORDER BY created_at ASC');
    $stmt->bind_param('i', $questionId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

function create_challenge($inviterId, $inviteeId, $quizId)
{
    global $conn;
    $stmt = $conn->prepare('INSERT INTO challenges (inviter_id, invitee_id, quiz_id) VALUES (?, ?, ?)');
    $stmt->bind_param('iii', $inviterId, $inviteeId, $quizId);
    $stmt->execute();
    $stmt->close();
}

function get_challenge($id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM challenges WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row;
}
?>
