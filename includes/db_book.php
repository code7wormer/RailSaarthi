<?php
$conn_book = new mysqli("localhost", "root", "", "book_db");

if($conn_book->connect_error) {
    die("Booking DB Connection failed: " . $conn_book->connect_error);
}
?>
