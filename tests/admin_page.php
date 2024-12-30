<?php
// 連接資料庫
include 'connMysql.php';

// 檢查是否有搜尋請求
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// 查詢書籍資料
$sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%$searchQuery%";
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// 處理新增書本表單提交
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // 插入書籍資料
    $insertSql = "INSERT INTO books (title, author, price, image) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ssss", $title, $author, $price, $image);
    if ($insertStmt->execute()) {
        echo "<script>alert('書籍新增成功！');</script>";
    } else {
        echo "<script>alert('新增書籍時發生錯誤！');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書香書店</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
        /* 搜尋框樣式 */
        .search-box {
            display: flex;
            align-items: center;
        }
        .search-box input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-box button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-box button:hover {
            background-color: #555;
        }
        .add-book-form {
            display: none;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            position: absolute;
            top: 60px;
            right: 20px;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .add-book-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .add-book-form button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-book-form button:hover {
            background-color: #555;
        }

        .container {
            margin: 20px;
        }
        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
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
        .book-item button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .book-item button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <header>
        <h1>書香書店</h1>
        <!-- 移動的搜尋框 -->
        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="輸入書籍名稱或作者" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit">搜尋</button>
            </form>
        </div>
        <div class="header-buttons">
            <button id="addBookBtn">新增書籍</button>
            <button onclick="location.href='index.php'">回首頁</button>
            <button onclick="location.href='logout.php'">登出</button>
        </div>
    </header>

    <div class="container">
        <div class="book-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-item" id="book-<?php echo $row['id']; ?>">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?> 封面">
                        <a href="book_details.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
                        <p>作者: <?php echo $row['author']; ?></p>
                        <p>價格: $<?php echo $row['price']; ?></p>
                        <button class="deleteBtn" data-id="<?php echo $row['id']; ?>">刪除</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>沒有找到符合的書籍。</p>
            <?php endif; ?>
        </div>
    </div>

        <!-- 新增書籍表單 -->
    <div class="add-book-form" id="addBookForm">
        <h3>新增書籍</h3>
        <form id="addBookFormAction" enctype="multipart/form-data">
            <input type="text" id="title" placeholder="書名" required>
            <input type="text" id="author" placeholder="作者 (可選)">
            <input type="number" id="year" placeholder="出版年份 (可選)">
            <input type="number" step="0.01" id="price" placeholder="價格" required>
            <input type="number" id="quantity" placeholder="庫存數量" required>
            <input type="file" id="image" accept="image/*">
            <button type="submit">新增書籍</button>
        </form>
    </div>
    <script>
        document.getElementById('addBookBtn').addEventListener('click', function () {
            const addBookForm = document.getElementById('addBookForm');
            addBookForm.style.display = (addBookForm.style.display === 'block') ? 'none' : 'block';
        });
        
        document.getElementById('addBookForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('title', document.getElementById('title').value);
            formData.append('author', document.getElementById('author').value);
            formData.append('year', document.getElementById('year').value);
            formData.append('price', document.getElementById('price').value);
            formData.append('quantity', document.getElementById('quantity').value);
            formData.append('image', document.getElementById('image').files[0]);

            fetch('add_book.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('書籍新增成功！');
                    
                    // 創建新的書籍項目
                    const bookItem = document.createElement('div');
                    bookItem.classList.add('book-item');
                    bookItem.id = 'book-' + data.book.id;

                    const bookImage = document.createElement('img');
                    bookImage.src = data.book.image;
                    bookImage.alt = data.book.title + " 封面";
                    bookItem.appendChild(bookImage);

                    const bookTitle = document.createElement('a');
                    bookTitle.href = 'book_details.php?id=' + data.book.id;
                    bookTitle.textContent = data.book.title;
                    bookItem.appendChild(bookTitle);

                    const bookAuthor = document.createElement('p');
                    bookAuthor.textContent = '作者: ' + data.book.author;
                    bookItem.appendChild(bookAuthor);

                    const bookPrice = document.createElement('p');
                    bookPrice.textContent = '價格: $' + data.book.price;
                    bookItem.appendChild(bookPrice);

                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = '刪除';
                    deleteButton.classList.add('deleteBtn');
                    deleteButton.setAttribute('data-id', data.book.id);
                    deleteButton.addEventListener('click', function () {
                        const bookId = this.getAttribute('data-id');
                        if (confirm("確定要刪除此書籍嗎？")) {
                            fetch('delete_book.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ id: bookId }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert("書籍已成功刪除！");
                                    document.getElementById('book-' + bookId).remove();
                                } else {
                                    alert("錯誤：" + data.error);
                                }
                            })
                            .catch(error => {
                                console.error("刪除書籍時發生錯誤：", error);
                                alert("刪除過程中發生錯誤，請稍後再試。");
                            });
                        }
                    });
                    bookItem.appendChild(deleteButton);

                    // 在書籍列表中插入新書
                    document.querySelector('.book-list').prepend(bookItem);

                    // 清除表單
                } else {
                    alert('錯誤：' + data.error);
                }
            })
            .catch(error => {
                console.error('新增書籍時發生錯誤：', error);
                alert('新增書籍過程中發生錯誤，請稍後再試。');
            });
        });
        document.querySelectorAll('.deleteBtn').forEach(button => {
            button.addEventListener('click', function () {
                const bookId = this.getAttribute('data-id');
                if (confirm("確定要刪除此書籍嗎？")) {
                    fetch('delete_book.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: bookId }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("書籍已成功刪除！");
                            document.getElementById('book-' + bookId).remove();
                        } else {
                            alert("錯誤：" + data.error);
                        }
                    })
                    .catch(error => {
                        console.error("刪除書籍時發生錯誤：", error);
                        alert("刪除過程中發生錯誤，請稍後再試。");
                    });
            }
        });
    });
    </script>

        <div class="book-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-item" id="book-<?php echo $row['id']; ?>">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?> 封面">
                        <a href="book_details.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
                        <p>作者: <?php echo $row['author']; ?></p>
                        <p>價格: $<?php echo $row['price']; ?></p>
                        <button class="deleteBtn" data-id="<?php echo $row['id']; ?>">刪除</button>
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
