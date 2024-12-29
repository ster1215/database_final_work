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
<html lang="en">
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
            grid-template-columns: repeat(5, 1fr); /* 每行顯示 5 本書 */
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
    </style>
</head>
<body>
    <header>
        <h1>書香書店</h1>
        <div class="header-buttons">
            <button onclick="location.href='index.php'">回首頁</button>
            <button onclick="location.href='logout.php'">登出</button>
        </div>
    </header>
    <div class="container">
        <h2>新增書籍</h2>
        <form id="addBookForm" enctype="multipart/form-data">
            <input type="text" id="title" placeholder="書名" required>
            <input type="text" id="author" placeholder="作者" required>
            <input type="number" id="year" placeholder="出版年份" required>
            <input type="number" step="0.01" id="price" placeholder="價格" required>
            <input type="number" id="quantity" placeholder="庫存數量" required>
            <input type="file" id="image" accept="image/*">
            <button type="submit">新增書籍</button>
        </form>
        <div class="search-bar">
            <input type="text" id="searchQuery" placeholder="搜尋書名或作者">
            <button id="searchBtn">搜尋</button>
        <div class="book-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-item">
                        <!-- 顯示書籍封面 -->
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?> 封面" width="100">
                        
                        <!-- 書名，點擊後可進入書籍詳細頁 -->
                        <a href="book_details.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
                        
                        <!-- 顯示作者 -->
                        <p>作者: <?php echo $row['author']; ?></p>
                        
                        <!-- 顯示價格 -->
                        <p>價格: $<?php echo $row['price']; ?></p>
                        
                        <!-- 刪除按鈕 -->
                        <button class="deleteBtn" data-id="<?php echo $row['id']; ?>">刪除</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>目前沒有書籍。</p>
            <?php endif; ?>
        </div>
    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Handle delete book functionality
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

            // Handle search functionality
            document.getElementById('searchBtn').addEventListener('click', function () {
                const searchQuery = document.getElementById('searchQuery').value.trim();
                if (searchQuery !== "") {
                    fetch(`get_book.php?search=${encodeURIComponent(searchQuery)}`)
                        .then(response => response.json())
                        .then(data => {
                            const bookList = document.getElementById('bookList');
                            bookList.innerHTML = ''; // Clear current list

                            if (data.books && data.books.length > 0) {
                                data.books.forEach(book => {
                                    const listItem = document.createElement('li');
                                    listItem.id = `book-${book.id}`;
                                    listItem.innerHTML = `
                                        <img src="${book.image}" alt="${book.title} 書封" width="100">
                                        <strong>${book.title}</strong> 作者：${book.author} (${book.year}) - $ ${book.price} - ${book.quantity} 本
                                        <button class="deleteBtn" data-id="${book.id}">刪除</button>
                                    `;
                                    // Add event listener to new delete button
                                    listItem.querySelector(".deleteBtn").addEventListener('click', function () {
                                        deleteBook(book.id);
                                    });

                                    bookList.appendChild(listItem);
                                });
                            } else {
                                bookList.innerHTML = `<li>找不到相關書籍。</li>`;
                            }
                        })
                        .catch(error => {
                            console.error("搜尋書籍時發生錯誤：", error);
                            alert("搜尋過程中發生錯誤，請稍後再試。");
                        });
                } else {
                    // Reload all books if search query is empty
                    loadBooks();
                }
            });

            // Function to load all books
            function loadBooks() {
                fetch('get_book.php')
                    .then(response => response.json())
                    .then(data => {
                        const bookList = document.getElementById('bookList');
                        bookList.innerHTML = ''; // Clear current list

                        if (data.books && data.books.length > 0) {
                            data.books.forEach(book => {
                                const listItem = document.createElement('li');
                                listItem.id = `book-${book.id}`;
                                listItem.innerHTML = `
                                    <img src="${book.image}" alt="${book.title} 書封" width="100">
                                    <strong>${book.title}</strong> 作者：${book.author} (${book.year}) - $ ${book.price} - ${book.quantity} 本
                                    <button class="deleteBtn" data-id="${book.id}">刪除</button>
                                `;
                                // Add event listener to new delete button
                                listItem.querySelector(".deleteBtn").addEventListener('click', function () {
                                    deleteBook(book.id);
                                });

                                bookList.appendChild(listItem);
                            });
                        } else {
                            bookList.innerHTML = `<li>目前沒有書籍。</li>`;
                        }
                    })
                    .catch(error => {
                        console.error("載入書籍時發生錯誤：", error);
                        //alert("載入書籍過程中發生錯誤，請稍後再試。");
                    });
            }

            // Initial load of books
            loadBooks();
        });
    </script>
</body>
</html>