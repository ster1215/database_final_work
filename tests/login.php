<?php
session_start(); // 確保 session 正常啟用
include 'connMysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    $password = $data['password'];

    // 查找使用者
    $sql = "SELECT * FROM members WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // 驗證密碼是否正確
        if (password_verify($password, $user['password'])) {
            // 設置 session 變量
            $_SESSION['member_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // 回傳成功訊息
            echo json_encode(["success" => "Login successful"]);
            
        } else {
            echo json_encode(["error" => "Invalid password"]);
        }
    } else {
        echo json_encode(["error" => "No such user found"]);
    }

    $stmt->close();
    $conn->close();
}
?>
