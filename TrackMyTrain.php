<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/local_service.php';

$train_number = $_GET['train_number'] ?? '';
$status_data = null;
$error_message = null;

if (!empty($train_number)) {
    $status_data = simulateLiveStatus($train_number);
    if (!$status_data) {
        $error_message = "Train data not found in our local database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Train | Rail Saarthi</title>
    <link rel="stylesheet" href="assets/css/track.css">
    <link rel="stylesheet" href="assets/css/live_timeline.css">
</head>
<body>

    <!-- Loading Overlay -->
    <?php if ($status_data): ?>
    <div id="loadingOverlay" class="loading-overlay">
        <div class="satellite-track"></div>
        <p class="loading-text">CONNECTING TO SATELLITE...</p>
        <p style="margin-top: 1rem; color: #64748b; font-weight: 600;">Authenticating with Station Masters</p>
    </div>
    <?php endif; ?>

    <nav>
        <a href="index.php" class="nav-brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 20h20M7 20v-5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v5M8 13V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6M9 5V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
            RailSaarthi
        </a>
        <ul class="nav-links">
            <li><a href="TrackMyTrain.php" class="active">Track</a></li>
            <li><a href="TrainKundli.php">Kundli</a></li>
            <li><a href="booking.php">Booking</a></li>
            <li><a href="TripPlanner.php">Planner</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <div class="nav-auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="handlers/logout.php" class="auth-btn register">Sign Out</a>
            <?php else: ?>
                <a href="auth.html" class="auth-btn login">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container" id="mainContent" style="opacity: 0; transition: opacity 0.5s ease;">
        <h1>Live Train Status</h1>
        <p class="subtitle">Real-time GPS tracking for your journey.</p>

        <div class="search-box">
            <form method="GET" action="TrackMyTrain.php" class="track-form">
                <div class="input-group">
                    <label>Train Number</label>
                    <input type="text" name="train_number" placeholder="Enter 5-digit number (e.g. 12951)" value="<?php echo htmlspecialchars($train_number); ?>" required maxlength="5">
                </div>
                <button type="submit" class="search-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Track Status
                </button>
            </form>
        </div>

        <?php if ($error_message): ?>
            <div class="error-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($status_data): ?>
            <div class="timeline-container">
                <div class="timeline-header">
                    <div class="train-title">
                        <h2><?php echo htmlspecialchars($status_data['data']['train_number']); ?> - <?php echo htmlspecialchars($status_data['data']['train_name']); ?></h2>
                        <p>Currently at: <span style="color: #2563eb;"><?php echo htmlspecialchars($status_data['data']['current_station']); ?></span></p>
                    </div>
                    <div class="status-chip <?php echo ($status_data['data']['delay'] > 0) ? 'departed' : 'upcoming'; ?>">
                        <?php echo ($status_data['data']['delay'] > 0) ? $status_data['data']['delay'] . 'm LATE' : 'ON TIME'; ?>
                    </div>
                </div>

                <div class="timeline-track">
                    <?php foreach ($status_data['data']['stoppages'] as $stop): ?>
                        <div class="timeline-node <?php echo $stop['status']; ?>">
                            <div class="node-dot"></div>
                            
                            <?php if ($stop['status'] == 'arrived'): ?>
                                <div class="train-icon-live">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><line x1="4" y1="8" x2="20" y2="8"/><line x1="4" y1="13" x2="20" y2="13"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                                </div>
                            <?php endif; ?>

                            <div class="time-column">
                                <span class="sched"><?php echo $stop['arrival']; ?></span>
                                <span class="actual"><?php echo $stop['actual_arrival']; ?></span>
                            </div>

                            <div class="station-info">
                                <h4><?php echo htmlspecialchars($stop['station_name']); ?></h4>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <p><?php echo $stop['station_code']; ?> • Halt: <?php echo rand(2, 10); ?>m</p>
                                    <span class="badge <?php echo ($status_data['data']['delay'] > 0) ? 'late' : 'on-time'; ?>">
                                        <?php echo $stop['delay_text']; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="pf-info">
                                <?php echo $stop['platform']; ?>
                                <div style="font-size: 0.6rem; color: #94a3b8; margin-top: 2px;"><?php echo $stop['distance']; ?> km</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="next-station" style="background: #f8fafc; padding: 1.25rem; border-top: 1px solid #f1f5f9; margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 700;">NEXT STOP</span>
                        <div style="font-size: 1rem; font-weight: 800; color: #0f172a;">
                            <?php echo $status_data['data']['next_station'] ? htmlspecialchars($status_data['data']['next_station']['station_name']) : 'Arrived at Destination'; ?>
                        </div>
                    </div>
                    <?php if ($status_data['data']['next_station']): ?>
                        <div style="text-align: right;">
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 700;">ARRIVING AT</span>
                            <div style="font-size: 1rem; font-weight: 800; color: #2563eb;"><?php echo $status_data['data']['next_station']['actual_arrival']; ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <style> #mainContent { opacity: 1 !important; } </style>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2026 Rail Saarthi - Your Intelligent Rail Companion</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('loadingOverlay');
            const content = document.getElementById('mainContent');
            
            if (overlay) {
                // Generate random delay between 3000ms and 5000ms
                const randomDelay = Math.floor(Math.random() * (5000 - 3000 + 1) + 3000);
                
                setTimeout(() => {
                    overlay.style.opacity = '0';
                    content.style.opacity = '1';
                    setTimeout(() => {
                        overlay.style.display = 'none';
                    }, 500);
                }, randomDelay);
            } else {
                content.style.opacity = '1';
            }
        });
    </script>
</body>
</html>
