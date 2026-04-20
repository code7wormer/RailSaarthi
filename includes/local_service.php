<?php
require_once 'db_rail.php';

function getTrainInfo($train_number) {
    global $conn_rail;
    $stmt = $conn_rail->prepare("SELECT t.*, s1.station_name as from_name, s2.station_name as to_name 
                                FROM trains t 
                                JOIN stations s1 ON t.from_station_code = s1.station_code
                                JOIN stations s2 ON t.to_station_code = s2.station_code
                                WHERE t.train_number = ?");
    $stmt->bind_param("s", $train_number);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getTrainFullSchedule($train_number) {
    global $conn_rail;
    $stmt = $conn_rail->prepare("SELECT ts.*, s.station_name 
                                FROM train_schedule ts 
                                JOIN stations s ON ts.station_code = s.station_code 
                                WHERE ts.train_number = ? 
                                ORDER BY ts.stop_number ASC");
    $stmt->bind_param("s", $train_number);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function simulateLiveStatus($train_number) {
    $schedule = getTrainFullSchedule($train_number);
    $info = getTrainInfo($train_number);
    
    if (empty($schedule)) return null;

    $current_time = date('H:i:s');
    $current_timestamp = strtotime($current_time);
    
    $last_reached_index = 0; 
    $is_running = false;
    $delay = rand(0, 15);

    foreach ($schedule as $index => $stop) {
        $dep_time = $stop['departure_time'] ? strtotime($stop['departure_time']) : null;
        $arr_time = $stop['arrival_time'] ? strtotime($stop['arrival_time']) : null;

        if ($dep_time && $current_timestamp > $dep_time) {
            $last_reached_index = $index;
            $is_running = true;
        } elseif ($arr_time && $current_timestamp > $arr_time) {
            $last_reached_index = $index;
            $is_running = true;
            break; 
        }
    }

    $first_dep = strtotime($schedule[0]['departure_time']);
    if ($current_timestamp < $first_dep) {
        $last_reached_index = 0;
        $is_running = false;
    }

    $stoppages = [];
    foreach ($schedule as $index => $stop) {
        $status = 'upcoming';
        if ($index < $last_reached_index) {
            $status = 'departed';
        } elseif ($index == $last_reached_index) {
            $status = 'arrived';
        }

        $stoppages[] = [
            "station_name" => $stop['station_name'],
            "station_code" => $stop['station_code'],
            "arrival" => $stop['arrival_time'] ? date('H:i', strtotime($stop['arrival_time'])) : '--',
            "departure" => $stop['departure_time'] ? date('H:i', strtotime($stop['departure_time'])) : '--',
            "actual_arrival" => $stop['arrival_time'] ? date('H:i', strtotime($stop['arrival_time']) + ($delay * 60)) : '--',
            "actual_departure" => $stop['departure_time'] ? date('H:i', strtotime($stop['departure_time']) + ($delay * 60)) : '--',
            "platform" => "PF " . rand(1, 5),
            "distance" => $stop['distance'],
            "status" => $status,
            "delay_text" => ($delay > 0) ? "$delay min late" : "On Time"
        ];
    }

    $next_station = null;
    if ($is_running && $last_reached_index < count($stoppages) - 1) {
        $next_station = $stoppages[$last_reached_index + 1];
    } elseif (!$is_running) {
        $next_station = $stoppages[0];
    }

    return [
        "data" => [
            "train_number" => $train_number,
            "train_name" => $info['train_name'],
            "current_station" => ($last_reached_index >= 0) ? $stoppages[$last_reached_index]['station_name'] : $schedule[0]['station_name'],
            "next_station" => $next_station,
            "status_label" => ($last_reached_index == count($stoppages) - 1 && $is_running) ? "ARRIVED" : ($is_running ? "RUNNING" : "NOT STARTED"),
            "delay" => $delay,
            "stoppages" => $stoppages
        ]
    ];
}

function searchTrainsBetween($from_code, $to_code) {
    global $conn_rail;
    $from_code = strtoupper($from_code);
    $to_code = strtoupper($to_code);
    
    $sql = "SELECT t.*, 
                   s1.arrival_time as from_sta, s1.departure_time as from_std, s1.stop_number as from_stop,
                   s2.arrival_time as to_sta, s2.departure_time as to_std, s2.stop_number as to_stop,
                   st1.station_name as from_station_name, st2.station_name as to_station_name
            FROM trains t
            JOIN train_schedule s1 ON t.train_number = s1.train_number AND s1.station_code = ?
            JOIN train_schedule s2 ON t.train_number = s2.train_number AND s2.station_code = ?
            JOIN stations st1 ON s1.station_code = st1.station_code
            JOIN stations st2 ON s2.station_code = st2.station_code
            WHERE s1.stop_number < s2.stop_number";
            
    $stmt = $conn_rail->prepare($sql);
    $stmt->bind_param("ss", $from_code, $to_code);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    $trains = [];
    foreach ($result as $row) {
        $trains[] = [
            "train_number" => $row['train_number'],
            "train_name" => $row['train_name'],
            "run_days" => $row['run_days'],
            "from_std" => date('H:i', strtotime($row['from_std'])),
            "from_station_name" => $row['from_station_name'],
            "to_sta" => date('H:i', strtotime($row['to_sta'])),
            "to_station_name" => $row['to_station_name'],
            "duration" => calculateDuration($row['from_std'], $row['to_sta']),
            "classes" => "1A,2A,3A,SL"
        ];
    }
    return $trains;
}

function calculateDuration($from_time, $to_time) {
    if (!$from_time || !$to_time) return "N/A";
    $start = strtotime($from_time);
    $end = strtotime($to_time);
    
    if ($end <= $start) {
        $end += 86400; 
    }
    
    $diff = $end - $start;
    $hours = floor($diff / 3600);
    $mins = floor(($diff % 3600) / 60);
    
    return $hours . "h " . $mins . "m";
}

function getTrainsAtStation($station_code) {
    global $conn_rail;
    $station_code = strtoupper($station_code);
    
    $sql = "SELECT t.train_number, t.train_name, ts.departure_time, s.station_name
            FROM train_schedule ts
            JOIN trains t ON ts.train_number = t.train_number
            JOIN stations s ON ts.station_code = s.station_code
            WHERE ts.station_code = ?
            ORDER BY ts.departure_time ASC";
            
    $stmt = $conn_rail->prepare($sql);
    $stmt->bind_param("s", $station_code);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    $trains = [];
    foreach ($result as $row) {
        $trains[] = [
            "train_number" => $row['train_number'],
            "train_name" => $row['train_name'],
            "departure_time" => date('H:i', strtotime($row['departure_time'])),
            "extra_info" => "Platform " . rand(1, 10),
            "station_name" => $row['station_name']
        ];
    }
    return $trains;
}

function getCoachSequence($train_number) {
    $info = getTrainInfo($train_number);
    if (!$info) return [];

    $sequence = ["EN"]; 
    
    $type = strtolower($info['train_type'] ?? '');
    if (strpos($type, 'rajdhani') !== false || strpos($type, 'vande') !== false) {
        $sequence = array_merge($sequence, ["H1", "A1", "A2", "B1", "B2", "B3", "B4", "B5", "B6", "PC", "B7", "B8", "B9", "B10", "EOR"]);
    } elseif (strpos($type, 'shatabdi') !== false) {
        $sequence = array_merge($sequence, ["E1", "C1", "C2", "C3", "C4", "C5", "C6", "C7", "PC", "C8", "C9", "C10", "EOR"]);
    } else {
        $sequence = array_merge($sequence, ["GS", "GS", "S1", "S2", "S3", "S4", "S5", "B1", "B2", "A1", "GS", "SLR"]);
    }
    
    return $sequence;
}
?>
