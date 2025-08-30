<?php
// like.php
session_start();
include "./../config.php"; // Include your database connection file

$questionId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['like'])) {
    // Update the 'likes' count in the 'questions' table
    $sqlUpdateLikes = "UPDATE questions SET likes = likes + 1 WHERE id = ?";
    $stmtUpdateLikes = $conn->prepare($sqlUpdateLikes);
    $stmtUpdateLikes->bind_param("i", $questionId);
    $stmtUpdateLikes->execute();
    $stmtUpdateLikes->close();
    
     // Set/Unset the 'liked' session variable for this question
    if (isset($_POST['like'])) {  // Change 'like' to 'unlike' in unlike.php
        $_SESSION['liked_' . $questionId] = true;  // Change 'true' to 'false' in unlike.php
    }

    // ...Rest of your code...

  

    // Fetch the updated 'likes' count
    $sqlGetLikes = "SELECT likes FROM questions WHERE id = ?";
    $stmtGetLikes = $conn->prepare($sqlGetLikes);
    $stmtGetLikes->bind_param("i", $questionId);
    $stmtGetLikes->execute();
    $resultGetLikes = $stmtGetLikes->get_result();
    $rowGetLikes = $resultGetLikes->fetch_assoc();
    $likesCount = $rowGetLikes['likes'];
    $stmtGetLikes->close();
    
  // Get the updated 'hasLiked' status
    // Get the updated 'hasLiked' status
    $hasLiked = isset($_SESSION['liked_' . $questionId]) ? $_SESSION['liked_' . $questionId] : false;

    
    // Respond with a JSON success message including the question ID and the updated 'likes' count
    echo json_encode(['success' => true, 'message' => 'Like processed successfully', 'questionId' => $questionId, 'likesCount' => $likesCount]);
} else {
    // Invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request.', 'post_data' => $data]);
}
?>