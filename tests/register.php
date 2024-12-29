<?php
include 'connMysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $sql = "SELECT * FROM members WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Username or email already taken"]);
    } else {
        // Insert new member
        $sql = "INSERT INTO members (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            echo json_encode(["success" => "Member registered successfully"]);
        } else {
            echo json_encode(["error" => "Failed to register member"]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
