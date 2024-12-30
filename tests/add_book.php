<?php
include 'connMysql.php';

// 確保表單數據是 POST 請求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 檢查是否有所有必要的欄位
    if (isset($_POST['title'], $_POST['price'], $_POST['quantity'])) {

        // 取得表單資料
        $title = $_POST['title'];
        $author = !empty($_POST['author']) ? $_POST['author'] : 'unknown'; // 如果作者為空，設定為 "unknown"
        $year = !empty($_POST['year']) ? $_POST['year'] : 'unknown'; // 如果年份為空，設定為 "unknown"
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        // 預設圖片為 default.jpg
        $imagePath = 'uploads/08.png';

        // 處理圖片上傳
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $imageName = $image['name'];
            $imageTmpName = $image['tmp_name'];
            $imageSize = $image['size'];

            // 設定圖片儲存路徑
            $uploadDir = 'uploads/';
            $imagePath = $uploadDir . basename($imageName);

            // 檢查圖片檔案類型和大小
            $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExt, $allowedExts) && $imageSize <= 5 * 1024 * 1024) {
                // 移動檔案到上傳資料夾
                if (!move_uploaded_file($imageTmpName, $imagePath)) {
                    echo json_encode(['success' => false, 'error' => '圖片上傳失敗。']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'error' => '圖片格式不正確或檔案太大。']);
                exit;
            }
        }

        // 新增書籍到資料庫
        $sql = "INSERT INTO books (title, author, year, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssds", $title, $author, $year, $price, $quantity, $imagePath);

        if ($stmt->execute()) {
            // 取得剛新增的書籍資料
            $bookId = $stmt->insert_id;
            $response = [
                'success' => true,
                'book' => [
                    'id' => $bookId,
                    'title' => $title,
                    'author' => $author,
                    'price' => $price,
                    'image' => $imagePath, // 如果沒有上傳圖片，會自動設為 default.jpg
                ]
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'error' => '新增書籍資料時發生錯誤。']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => '缺少必要的表單資料。']);
    }
}

// 關閉資料庫連接
$conn->close();
?>
