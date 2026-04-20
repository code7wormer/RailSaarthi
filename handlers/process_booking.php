<?php
session_start();
require_once '../includes/db_book.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.html?error=login_required");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $train_number = $_POST['train_number'];
    $train_name = $_POST['train_name'];
    $from_station = $_POST['from_station'];
    $to_station = $_POST['to_station'];
    $class = $_POST['class'];
    $travel_date = $_POST['travel_date'];
    $seat_number = $_POST['seat_number'];

    $conn_book->begin_transaction();

    try {
        $stmt = $conn_book->prepare("SELECT available_seats FROM seat_inventory WHERE train_number = ? AND class = ? FOR UPDATE");
        $stmt->bind_param("ss", $train_number, $class);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Inventory not found for this train/class.");
        }

        $row = $result->fetch_assoc();
        if ($row['available_seats'] <= 0) {
            throw new Exception("No seats available in this class.");
        }

        $stmt = $conn_book->prepare("INSERT INTO bookings (user_id, train_number, train_name, from_station_code, to_station_code, class, seat_number, travel_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $user_id, $train_number, $train_name, $from_station, $to_station, $class, $seat_number, $travel_date);
        $stmt->execute();

        $stmt = $conn_book->prepare("UPDATE seat_inventory SET available_seats = available_seats - 1 WHERE train_number = ? AND class = ?");
        $stmt->bind_param("ss", $train_number, $class);
        $stmt->execute();

        $conn_book->commit();
        header("Location: ../booking.php?view=history&status=success");
        exit();

    } catch (Exception $e) {
        $conn_book->rollback();
        header("Location: ../booking.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
