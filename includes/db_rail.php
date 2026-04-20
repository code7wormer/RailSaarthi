<?php
$conn_rail = new mysqli("localhost", "root", "", "rail_db");

if($conn_rail->connect_error) {
    die("Rail DB Connection failed: " . $conn_rail->connect_error);
}
?>
