document.addEventListener('DOMContentLoaded', function () {
    // Load the list of books when the page is ready
    const bookList = document.getElementById("bookList");
    if (bookList) {
        loadBooks();
    } else {
        console.error("Element with ID 'bookList' not found.");
    }

    // Handle adding a new book
    const addBookForm = document.getElementById("addBookForm");
    if (addBookForm) {
        addBookForm.addEventListener("submit", function (event) {
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
                        addBookForm.reset(); // Clear form fields
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
    } else {
        console.error("Element with ID 'addBookForm' not found.");
    }

    // Handle search input changes
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            searchBooks();
        });
    } else {
        console.error("Element with ID 'searchInput' not found.");
    }

    // Handle login form submission
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
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
                .then(data => {
                    console.log(data);  // 查看 PHP 回傳的資料
                    const messageDiv = document.getElementById('message');
                    if (data.success) {
                        messageDiv.textContent = data.success;
                        messageDiv.className = 'message success';

                        // 根據角色跳轉到對應頁面
                        if (data.role === 'admin') {
                            console.log('Redirecting to admin page');
                            setTimeout(() => {
                                window.location.href = 'admin_page.php'; // 登入成功後跳轉到 admin 頁面
                            }, 1000);
                        } else if (data.role === 'user') {
                            console.log('Redirecting to user page');
                            setTimeout(() => {
                                window.location.href = 'user_page.php'; // 登入成功後跳轉到 user 頁面
                            }, 1000);
                        }
                    } else {
                        messageDiv.textContent = data.error;
                        messageDiv.className = 'message error';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const messageDiv = document.getElementById('message');
                    if (messageDiv) {
                        messageDiv.textContent = '網路錯誤，請稍後再試。';
                        messageDiv.className = 'message error';
                    }
                });
        });
    } else {
        console.error("Element with ID 'loginForm' not found.");
    }
});

// Function to load books from the server and display them
function loadBooks(searchQuery = "") {
    const bookList = document.getElementById("bookList");
    if (!bookList) {
        console.error("Element with ID 'bookList' not found.");
        return;
    }

    const url = searchQuery ? `get_book.php?search=${encodeURIComponent(searchQuery)}` : 'get_book.php';

    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
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
            //console.error('Error loading books:', error);
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
    const searchInput = document.getElementById("searchInput");
    if (!searchInput) {
        console.error("Element with ID 'searchInput' not found.");
        return;
    }

    const searchQuery = searchInput.value.trim();
    loadBooks(searchQuery); // Call loadBooks with the search query
}