<?php
$servername = "localhost";
$username = "root";
$password = "881215";
$dbname = "database_work";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
