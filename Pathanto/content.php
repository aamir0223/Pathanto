<?php
// Content utilities for browsing questions and viewing explanations.
require_once __DIR__ . '/config.php';

/**
 * Retrieve questions from the archive with optional filters for topic and difficulty.
 */
function get_question_archive(array $filters = [])
{
    global $conn;
    $sql = 'SELECT id, text, topic_id, difficulty FROM questions WHERE 1=1';
    $params = [];
    $types = '';
    if (!empty($filters['topic_id'])) {
        $sql .= ' AND topic_id = ?';
        $types .= 'i';
        $params[] = $filters['topic_id'];
    }
    if (!empty($filters['difficulty'])) {
        $sql .= ' AND difficulty = ?';
        $types .= 's';
        $params[] = $filters['difficulty'];
    }
    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

/**
 * Fetch a question and its explanation after answering.
 */
function get_question_with_explanation($id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT text, correct_answer, explanation FROM questions WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row;
}
?>
