<?php
require_once("connMysql.php");
session_start(); // 啟動會話，檢查用戶是否登入

// Get the book ID from the URL parameter
$bookId = isset($_GET['id']) ? $_GET['id'] : 0;

if ($bookId) {
    // Fetch the book details from the database
    $query = "SELECT * FROM books WHERE id = {$bookId}";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "Book not found.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
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
        .bookDetails {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        .bookDetails img {
            max-width: 100%;
            border-radius: 5px;
        }
        .bookDetails h2 {
            margin-top: 20px;
        }
        .bookDetails p {
            margin: 10px 0;
        }
        .bookDetails a {
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .bookDetails a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>書香書店</h1>
        <div class="header-buttons">
            <button onclick="location.href='<?php echo isset($_SESSION['user_id']) ? 'admin_page.php' : 'index.php'; ?>'">
                回書籍列表
            </button>
        </div>
    </header>

    <div class="container">
        <h1>Book Details</h1>
        
        <div class="bookDetails">
            <img src="<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?> Cover" width="200">
            <h2><?php echo $book['title']; ?></h2>
            <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
            <p><strong>Year:</strong> <?php echo $book['year']; ?></p>
            <p><strong>Price:</strong> $<?php echo $book['price']; ?></p>
            <p><strong>Quantity:</strong> <?php echo $book['quantity']; ?> in stock</p>
            <a href="javascript:void(0);" onclick="window.history.back();">Back</a> <!-- 回到上一頁 -->
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
