document.addEventListener('DOMContentLoaded', function () {
    // Load the list of books when the page is ready
    loadBooks();

    // Handle adding a new book
    document.getElementById("addBookForm").addEventListener("submit", function (event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData();
        formData.append("title", document.getElementById("title").value);
        formData.append("author", document.getElementById("author").value);
        formData.append("year", document.getElementById("year").value);
        formData.append("price", document.getElementById("price").value);
        formData.append("quantity", document.getElementById("quantity").value);
        formData.append("image", document.getElementById("image").files[0]);

        // Send data to the server using fetch
        fetch('add_book.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Book added successfully!");
                    document.getElementById("addBookForm").reset(); // Clear form fields
                    loadBooks(); // Reload the list of books
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert("An error occurred while adding the book.");
            });
    });

    // Handle search input changes
    document.getElementById("searchInput").addEventListener("input", function () {
        searchBooks();
    });
});

// Function to load books from the server and display them
function loadBooks(searchQuery = "") {
    // Fix URL query parameter spacing issue
    const url = searchQuery ? `get_book.php?search=${encodeURIComponent(searchQuery)}` : 'get_book.php';

    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            const bookList = document.getElementById("bookList");
            bookList.innerHTML = ""; // Clear the current list

            if (data.books && data.books.length > 0) {
                data.books.forEach((book) => {
                    const listItem = document.createElement("li");
                    listItem.id = "book-" + book.id;

                    listItem.innerHTML = `
                        <img src="${book.image}" alt="${book.title} Cover" width="100">
                        <strong>${book.title}</strong> by ${book.author} (${book.year}) - $${book.price} - ${book.quantity} in stock
                        <button class="deleteBtn" data-id="${book.id}">Delete</button>
                    `;

                    // Add event listener to delete button
                    listItem.querySelector(".deleteBtn").addEventListener("click", function () {
                        deleteBook(book.id);
                    });

                    bookList.appendChild(listItem);
                });
            } else {
                bookList.innerHTML = `<li>No books found.</li>`;
            }
        })
        .catch((error) => {
            console.error('Error loading books:', error);
            alert("An error occurred while loading books.");
        });
}

// Function to handle deleting a book
function deleteBook(bookId) {
    if (confirm("Are you sure you want to delete this book?")) {
        fetch('delete_book.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: bookId }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    alert("Book deleted successfully!");
                    loadBooks(); // Reload the list of books
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch((error) => {
                console.error('Error deleting book:', error);
                alert("An error occurred while deleting the book.");
            });
    }
}

// Function to search books based on the query entered by the user
function searchBooks() {
    const searchQuery = document.getElementById("searchInput").value.trim();
    loadBooks(searchQuery); // Call loadBooks with the search query
}
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const loginData = {
        username: document.getElementById('username').value,
        password: document.getElementById('password').value
    };

    fetch('login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(loginData)
    })
    .then(response => response.json())
    .then(data => 
        {
        console.log(data);  // 查看 PHP 回傳的資料
        const messageDiv = document.getElementById('message');
        if (data.success) {
            messageDiv.textContent = data.success;
            messageDiv.className = 'message success';
            setTimeout(() => {
                window.location.href = 'admin_page.php'; // 登入成功後跳轉到管理頁面
            }, 1000);
        } else {
            messageDiv.textContent = data.error;
            messageDiv.className = 'message error';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = '網路錯誤，請稍後再試。';
        messageDiv.className = 'message error';
    });
});