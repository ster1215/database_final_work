<?php
include 'connMysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // 設定預設角色為 'user'
    $role = 'user';

    // 檢查用戶名或電子郵件是否已存在
    $sql = "SELECT * FROM members WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Username or email already taken"]);
    } else {
        // 插入新會員，將role設為 'user'
        $sql = "INSERT INTO members (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $password, $role);
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
