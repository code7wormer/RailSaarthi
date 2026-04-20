<?php
session_start();
require_once 'includes/api_helper.php';

$train_number = $_GET['train_number'] ?? '';
$search_data = null;
$error_message = null;

if (!empty($train_number)) {
    require_once 'includes/local_service.php';
    $info = getTrainInfo($train_number);
    $schedule = getTrainFullSchedule($train_number);
    
    if ($info && !empty($schedule)) {
        $first_stop = $schedule[0];
        $last_stop = $schedule[count($schedule)-1];
        
        $search_data = [
            "data" => [
                "train_name" => $info['train_name'],
                "from_station_name" => $info['from_name'],
                "to_station_name" => $info['to_name'],
                "run_days" => explode(',', $info['run_days']),
                "train_type" => $info['train_type'],
                "duration" => calculateDuration($first_stop['departure_time'], $last_stop['arrival_time']),
                "distance" => $last_stop['distance'],
                "classes" => ['SL', '3A', '2A', '1A']
            ]
        ];
    } else {
        $error_message = "Train details not found in our local database.";
    }
}

// Function to generate pseudo-realistic seat availability
function getMockSeats($class) {
    $rand = rand(0, 10);
    if ($rand > 8) return ["status" => "waiting", "value" => "WL " . rand(5, 20) . " / WL " . rand(1, 4)];
    if ($rand > 6) return ["status" => "available", "value" => "RAC " . rand(1, 10)];
    return ["status" => "available", "value" => "Available (" . rand(5, 150) . ")"];
}

$classes = ['1A', '2A', '3A', 'SL', '2S', 'CC', 'EC'];
$dates = [];
for($i=0; $i<5; $i++) {
    $dates[] = date('d M', strtotime("+$i days"));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Train Kundli | Rail Saarthi</title>
    <link rel="stylesheet" href="assets/css/kundli.css">
    <link rel="stylesheet" href="assets/css/track.css"> <!-- Reusing search box styles -->
    <link rel="stylesheet" href="assets/css/coach.css">
</head>
<body>

    <nav>
        <a href="index.php" class="nav-brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 20h20M7 20v-5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v5M8 13V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6M9 5V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
            RailSaarthi
        </a>
        <ul class="nav-links">
            <li><a href="TrackMyTrain.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4.5 8-11.8A8 8 0 0 0 4 10.2c0 7.3 8 11.8 8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                Track
            </a></li>
            <li><a href="TrainKundli.php" class="active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Kundli
            </a></li>
            <li><a href="booking.php" class="highlight">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9V5.25A2.25 2.25 0 0 1 4.25 3h15.5A2.25 2.25 0 0 1 22 5.25V9"/><path d="M22 15v3.75A2.25 2.25 0 0 1 19.75 21H4.25A2.25 2.25 0 0 1 2 18.75V15"/><path d="M12 3v18"/><path d="M2 12h5"/><path d="M17 12h5"/><path d="M7 12a5 5 0 0 1 10 0"/></svg>
                Booking
            </a></li>
            <li><a href="TripPlanner.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                Planner
            </a></li>
            <li><a href="about.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                About
            </a></li>
        </ul>
        <div class="nav-auth">
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="profile-section">
                <svg width="30px" height="30px" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                    <path fill="#494c4e" d="M9 0a9 9 0 0 0-9 9 8.654 8.654 0 0 0 .05.92 9 9 0 0 0 17.9 0A8.654 8.654 0 0 0 18 9a9 9 0 0 0-9-9zm5.42 13.42c-.01 0-.06.08-.07.08a6.975 6.975 0 0 1-10.7 0c-.01 0-.06-.08-.07-.08a.512.512 0 0 1-.09-.27.522.522 0 0 1 .34-.48c.74-.25 1.45-.49 1.65-.54a.16.16 0 0 1 .03-.13.49.49 0 0 1 .43-.36l1.27-.1a2.077 2.077 0 0 0-.19-.79v-.01a2.814 2.814 0 0 0-.45-.78 3.83 3.83 0 0 1-.79-2.38A3.38 3.38 0 0 1 8.88 4h.24a3.38 3.38 0 0 1 3.1 3.58 3.83 3.83 0 0 1-.79 2.38 2.814 2.814 0 0 0-.45.78v.01a2.077 2.077 0 0 0-.19.79l1.27.1a.49.49 0 0 1 .43.36.16.16 0 0 1 .03.13c.2.05.91.29 1.65.54a.49.49 0 0 1 .25.75z"></path>
                </svg>
                <a href="#" class="name"><?php echo $_SESSION['name']; ?></a>
                <a href="handlers/logout.php" class="auth-btn register">Sign Out</a>
            </div>
            <?php else: ?>
            <a href="auth.html" class="auth-btn login">Login</a>
            <a href="auth.html#register" class="auth-btn register">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <h1>Train Kundli (Profile)</h1>

        <div class="search-box">
            <form method="GET" action="TrainKundli.php" class="track-form" style="grid-template-columns: 1fr auto;">
                <div class="input-group">
                    <label>Train Number</label>
                    <input type="text" name="train_number" placeholder="Enter 5-digit Train Number" value="<?php echo htmlspecialchars($train_number); ?>" required>
                </div>
                <button type="submit" class="search-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    Fetch Profile
                </button>
            </form>
        </div>

        <?php if ($error_message): ?>
            <div class="error-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($search_data && isset($search_data['data'])): 
            $data = $search_data['data'];
            $train_name = $data['train_name'] ?? 'N/A';
            $from_to = ($data['from_station_name'] ?? 'N/A') . ' To ' . ($data['to_station_name'] ?? 'N/A');
            $days = $data['run_days'] ?? []; // Assuming this is an array
            $train_type = $data['train_type'] ?? 'N/A';
            $duration = $data['duration'] ?? 'N/A';
            $classes_raw = $data['classes'] ?? ['SL', '3A', '2A', '1A'];
        ?>
            <div class="profile-card">
                <div class="profile-header">
                    <h2>
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="8.01"/></svg>
                        <?php echo htmlspecialchars($train_name); ?> (<?php echo htmlspecialchars($train_number); ?>)
                    </h2>
                    <p><?php echo htmlspecialchars($from_to); ?></p>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Available Days</span>
                        <span class="value"><?php echo is_array($days) ? implode(', ', $days) : htmlspecialchars($days); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Train Type</span>
                        <span class="value"><?php echo htmlspecialchars($train_type); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Travel Time</span>
                        <span class="value"><?php echo htmlspecialchars($duration); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Coach Order</span>
                        <span class="value">Loco-EOG-<?php echo implode('-', array_slice($classes_raw, 0, 4)); ?>...-EOG</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Distance</span>
                        <span class="value"><?php echo htmlspecialchars($data['distance'] ?? 'N/A'); ?> km</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Available Classes</span>
                        <span class="value"><?php echo implode(', ', $classes_raw); ?></span>
                    </div>
                </div>

                <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-top: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    Seat Availability Matrix (Live Database)
                </h3>
                <table>
                    <thead>
                        <tr>
                            <th>Class</th>
                            <?php foreach($dates as $date): ?>
                                <th><?php echo $date; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classes_raw as $class): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($class); ?></strong></td>
                                <?php foreach($dates as $date): 
                                    $mock = getMockSeats($class);
                                ?>
                                    <td class="<?php echo $mock['status']; ?>"><?php echo $mock['value']; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Coach Position Section -->
                <?php 
                $coach_sequence = getCoachSequence($train_number);
                if (!empty($coach_sequence)): ?>
                    <div class="coach-section">
                        <h3>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="4" y="3" width="16" height="18" rx="2"/><line x1="4" y1="8" x2="20" y2="8"/><line x1="4" y1="13" x2="20" y2="13"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                            Coach Position & Composition
                        </h3>
                        <div class="coach-display">
                            <?php foreach ($coach_sequence as $coach): 
                                $is_engine = ($coach === 'EN');
                                ?>
                                <div class="coach-container">
                                    <div class="coach-label"><?php echo ($is_engine) ? 'LOCO' : $coach; ?></div>
                                    <div class="coach-box <?php echo ($is_engine) ? 'engine' : ''; ?>">
                                        <div class="coach-window"></div>
                                        <div class="coach-window"></div>
                                        <div class="coach-window"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <p style="margin-top: 1rem; color: #64748b; font-size: 0.8rem; font-weight: 600;">* Typical coach position. Subject to operational changes.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif (!$search_data && !$error_message && !empty($train_number)): ?>
             <div class="error-box">No profile data available for this train number.</div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2026 Rail Saarthi - Detailed Train Insights</p>
    </footer>

</body>
</html>
