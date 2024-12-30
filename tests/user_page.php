<?php
// 連接資料庫
include 'connMysql.php';
// 啟動會話以存取使用者登入狀態和購物車
session_start();

// 檢查用戶是否已經登入，若未登入則跳轉到登入頁面
if (!isset($_SESSION['member_id'])) {
    header("Location: login.html");
    exit();
}

// 處理加入購物車
if (isset($_GET['add_to_cart'])) {
    $bookId = $_GET['add_to_cart'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // 檢查書籍是否已經在購物車中
    if (array_key_exists($bookId, $_SESSION['cart'])) {
        // 如果書籍已在購物車中，則增加數量
        $_SESSION['cart'][$bookId]++;
    } else {
        // 如果書籍不在購物車中，則新增該書並設為數量1
        $_SESSION['cart'][$bookId] = 1;
    }
}
if (isset($_GET['logout'])) {
    unset($_SESSION['cart']);  // 清空購物車
    session_destroy();  // 注銷會話
    header("Location: login.html");  // 重定向到登入頁面
    exit();
}

// 查詢所有書籍資料
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

// 查詢用戶資料
$userId = $_SESSION['member_id'];
$sql_user = "SELECT * FROM `members` WHERE id = $userId";
$user_result = $conn->query($sql_user);
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>用戶頁面 - 書香書店</title>
    <style>
        /* 基本樣式設定 */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }
        header h1 {
            margin: 0;
        }
        .header-buttons {
            display: flex;
            gap: 5px;
        }
        header button {
            background-color: #555;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        header button:hover {
            background-color: #777;
        }
        .cart-icon {
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 30px;  /* 調整圖標大小 */
        }
        .cart-count {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 7px;
            font-size: 14px; /* 調整數字大小 */
            position: absolute;
            top: -5px;
            right: -5px;
        }

        .cart-dropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            min-width: 250px;
            z-index: 100;
        }

        .cart-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .cart-dropdown li {
            padding: 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            background-color: #333;
        }

        .cart-dropdown li img {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .cart-dropdown li:hover {
            background-color: rgb(25, 25, 25);
        }

        .cart-icon:hover .cart-dropdown {
            display: block;
        }
        .cart-button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .cart-button:hover {
            background-color: #555;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: auto;
            text-align: center;
            margin: 20px;
        }
        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .book-item {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        .book-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .book-item a {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }
        .book-item a:hover {
            color: #007bff;
        }

        /* 購物車顯示部分 */
        .cart-details {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin-top: 20px;
            width: 100%;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            width: 100%;
        }

        .cart-item img {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }

        .cart-item-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .cart-item-info p {
            margin: 5px 0;
            color: #333;
            font-size: 12px; /* 調整字體大小 */
        }
        .cart-item-info .book-title {
            font-weight: bold;
            font-size: 14px; /* 縮小書名字體 */
        }
        .cart-item-info .book-price {
            color: green;
            font-size: 12px; /* 縮小價格字體 */
        }
        .cart-item-info .book-quantity {
            color: #555;
            font-size: 12px; /* 縮小數量字體 */
        }
    </style>
</head>
<body>

<header>
    <h1>書香書店</h1>
    <div class="header-buttons">
        <div class="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
            <div class="cart-dropdown">
                <ul>
                    <?php
                    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                        $cart = array_count_values($_SESSION['cart']);
                        foreach ($cart as $bookId => $quantity) {
                            $sql_book = "SELECT * FROM books WHERE id = $bookId";
                            $book_result = $conn->query($sql_book);
                            $book = $book_result->fetch_assoc();
                            echo '<div class="cart-item">';
                            echo '<img src="' . $book['image'] . '" alt="' . $book['title'] . '">';
                            echo '<div class="cart-item-info">';
                            echo '<p class="book-title">' . $book['title'] . '</p>';
                            echo '<p class="book-price">價格: $' . $book['price'] . '</p>';
                            echo '<p class="book-quantity">數量: ' . $quantity . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>您的購物車是空的。</p>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <!-- 登出按鈕 -->
        <button onclick="location.href='?logout=true'">登出</button>
    </div>
</header>

<div class="container">
    <h2>歡迎回來，使用者<?php echo htmlspecialchars($user['username']); ?>！</h2>

    <h3>書籍列表</h3>
    <div class="book-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-item">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?> 封面">
                    <a href="book_details.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
                    <p>作者: <?php echo $row['author']; ?></p>
                    <p>價格: $<?php echo $row['price']; ?></p>
                    <a href="user_page.php?add_to_cart=<?php echo $row['id']; ?>" class="cart-button">加入購物車</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>沒有書籍可顯示。</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
// 關閉資料庫連接
$conn->close();
?>
