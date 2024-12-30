<?php
include 'connMysql.php';

header('Content-Type: application/json'); // Set content type to JSON

$response = ['status' => 'success', 'books' => [], 'message' => ''];

// Check if a search query is provided via GET
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// SQL query: Fetch all books or filter by title/author based on search query
try {
    if (!empty($searchQuery)) {
        $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement: " . $conn->error);
        }

        $searchParam = "%" . $searchQuery . "%";
        $stmt->bind_param("ss", $searchParam, $searchParam);
    } else {
        $sql = "SELECT * FROM books";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement: " . $conn->error);
        }
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Ensure sensitive data or unnecessary fields are not included in the output
            $books[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'author' => $row['author'],
                
                'year' => $row['year'],
                'price' => $row['price'],
                'quantity' => $row['quantity'],
                'image' => $row['image'], // Assuming this is a URL or path
            ];
        }
        $response['books'] = $books;
    } else {
        $response['message'] = 'No books found.';
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

// Return JSON response
echo json_encode($response);

// Close the database connection
$conn->close();
?>