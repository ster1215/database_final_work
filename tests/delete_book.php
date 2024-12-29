<?php
include 'connMysql.php';

header('Content-Type: application/json'); // Set content type to JSON

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input data
    $data = json_decode(file_get_contents("php://input"), true);
    $bookId = $data['id'];

    // Delete the book from the database
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Book deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete book"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
