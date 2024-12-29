<?php
// 連接資料庫
include 'connMysql.php';

// 檢查是否有搜尋請求
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// 查詢書籍資料
$sql = "SELECT * FROM books WHERE title LIKE '%$searchQuery%' OR author LIKE '%$searchQuery%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書店首頁</title>
    <style>
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
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: auto;
            text-align: center;
            margin: 20px;
        }
        .search-box {
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
        }
        .search-box input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-box button {
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            background-color: #333;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .search-box button:hover {
            background-color: #555;
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
    </style>
</head>
<body>

<header>
    <h1>書香書店</h1>
    <div class="header-buttons">
        <!-- <button onclick="location.href='cart.php'">購物車</button> -->
        <button onclick="location.href='login.html'">登入會員</button>
        <button onclick="location.href='register.html'">註冊會員</button>
    </div>
</header>

<div class="container">
    <div class="search-box">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="輸入書籍名稱" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">搜尋</button>
        </form>
    </div>

    <div class="book-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-item">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?> 封面">
                    <a href="book_details.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
                    <p>作者: <?php echo $row['author']; ?></p>
                    <p>價格: $<?php echo $row['price']; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>沒有找到符合的書籍。</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
// 關閉資料庫連接
$conn->close();
?>