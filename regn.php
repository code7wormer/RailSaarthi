<?php
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, password, name) VALUES ('$email', '$hashed_password', '$name')";

if($conn->query($sql) === TRUE) {
    header("Location: auth.html?register=success");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>