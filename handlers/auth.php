<?php
session_start();
include '../includes/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if(password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['name'] = $row['name'];
        header("Location: ../index.php?login=success");
        exit();

    } else {
        header("Location: ../auth.html?error=invalidpassword");
        exit();
    }
} else {
    header("Location: ../auth.html?error=User_not_found");
    exit();
}
?>
