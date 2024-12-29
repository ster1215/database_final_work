<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Enable error reporting for debugging
include 'connMysql.php';

header('Content-Type: application/json'); // Set content type to JSON

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the input fields from $_POST and $_FILES
    if (!isset($_POST['title']) || !isset($_POST['author']) || !isset($_POST['price']) || !isset($_POST['quantity'])) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = isset($_POST['year']) ? $_POST['year'] : null;
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . $_FILES['image']['name'];
        $imagePath = 'uploads/' . $imageName;

        if (move_uploaded_file($imageTmpPath, $imagePath)) {
            $image = $imagePath; // Store the relative path of the image
        } else {
            echo json_encode(["error" => "Image upload failed"]);
            exit;
        }
    }

    // Insert the book into the database
    $sql = "INSERT INTO books (title, author, year, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdis", $title, $author, $year, $price, $quantity, $image);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Book added successfully"]);
    } else {
        echo json_encode(["error" => "Failed to add book"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
