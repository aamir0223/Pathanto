
<?php
include "./../config.php";

// Get search term from the URL, or set it to an empty string if not provided
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

// Prepare and execute the SQL query to fetch questions
$sql = "SELECT q.id, q.question_text, q.likes, GROUP_CONCAT(t.tag_name) AS tags
        FROM questions q
        LEFT JOIN question_tags qt ON q.id = qt.question_id
        LEFT JOIN tags t ON qt.tag_id = t.id
        WHERE q.question_text LIKE ?
        GROUP BY q.id
        ORDER BY q.created_at DESC
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$searchPattern = "%" . $searchTerm . "%";
$stmt->bind_param("sii", $searchPattern, $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results into an array
$questions = [];

while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

$stmt->close();

// Calculate total pages for pagination
$totalQuestions = countQuestions();
$totalPages = ceil($totalQuestions / $itemsPerPage);

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode(['questions' => $questions, 'totalPages' => $totalPages]);

// Function to count total questions
function countQuestions() {
    global $conn, $searchTerm;
    
    $sqlCount = "SELECT COUNT(DISTINCT q.id) AS total
                 FROM questions q
                 LEFT JOIN question_tags qt ON q.id = qt.question_id
                 LEFT JOIN tags t ON qt.tag_id = t.id
                 WHERE q.question_text LIKE ?";

    $stmtCount = $conn->prepare($sqlCount);
    $searchPattern = "%" . $searchTerm . "%";  // Define $searchPattern within the function
    $stmtCount->bind_param("s", $searchPattern);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
    $rowCount = $resultCount->fetch_assoc();
    $totalQuestions = $rowCount['total'];
    $stmtCount->close();

    return $totalQuestions;
}

// Function to sanitize input
function sanitizeInput($input) {
    // Implement your input sanitization logic here
    return $input;
}
?>

