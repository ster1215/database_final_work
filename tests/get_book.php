<?php
include 'connMysql.php';

header('Content-Type: application/json'); // Set content type to JSON

// Check if a search query is provided via GET
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// SQL query: Fetch all books or filter by title/author based on search query
if (!empty($searchQuery)) {
    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
} else {
    $sql = "SELECT * FROM books";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row; // Add each book to the books array
    }
}

echo json_encode(['books' => $books]);

$conn->close();
?>

