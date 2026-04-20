<?php
session_start();
include '../includes/db_rail.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die("Unauthorized access.");
}

$train_number = $_POST['train_number'];
$train_name = $_POST['train_name'];
$train_type = $_POST['train_type'];
$from_code = $_POST['from_code'];
$to_code = $_POST['to_code'];
$run_days = $_POST['run_days'];

$stop_nos = $_POST['stop_no'];
$s_codes = $_POST['s_code'];
$s_arrs = $_POST['s_arr'];
$s_deps = $_POST['s_dep'];
$s_dists = $_POST['s_dist'];

$conn_rail->begin_transaction();

try {
    $stmt_stn = $conn_rail->prepare("INSERT IGNORE INTO stations (station_code, station_name) VALUES (?, ?)");
    foreach ($s_codes as $code) {
        $mock_name = $code . " Station"; 
        $stmt_stn->bind_param("ss", $code, $mock_name);
        $stmt_stn->execute();
    }

    $stmt_train = $conn_rail->prepare("INSERT INTO trains (train_number, train_name, from_station_code, to_station_code, train_type, run_days) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_train->bind_param("ssssss", $train_number, $train_name, $from_code, $to_code, $train_type, $run_days);
    $stmt_train->execute();

    $stmt_sched = $conn_rail->prepare("INSERT INTO train_schedule (train_number, station_code, stop_number, arrival_time, departure_time, distance) VALUES (?, ?, ?, ?, ?, ?)");
    
    for ($i = 0; $i < count($stop_nos); $i++) {
        $stop_no = $stop_nos[$i];
        $code = $s_codes[$i];
        $arr = ($s_arrs[$i] == '00:00' && $stop_no == 1) ? null : $s_arrs[$i];
        $dep = ($s_deps[$i] == '00:00' && $i == count($stop_nos)-1) ? null : $s_deps[$i];
        $dist = $s_dists[$i];

        $stmt_sched->bind_param("ssissi", $train_number, $code, $stop_no, $arr, $dep, $dist);
        $stmt_sched->execute();
    }

    $conn_rail->commit();
    header("Location: ../admin_add_train.php?status=success");
} catch (Exception $e) {
    $conn_rail->rollback();
    die("Error adding train: " . $e->getMessage());
}
?>
