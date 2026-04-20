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
            <li><a href="TrackMyTrain.php" class="active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4.5 8-11.8A8 8 0 0 0 4 10.2c0 7.3 8 11.8 8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                Track
            </a></li>
            <li><a href="TrainKundli.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Kundli
            </a></li>
            <li><a href="booking.php">
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
                    <svg width="30px" height="30px" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="#000000"><path fill="#494c4e" d="M9 0a9 9 0 0 0-9 9 8.654 8.654 0 0 0 .05.92 9 9 0 0 0 17.9 0A8.654 8.654 0 0 0 18 9a9 9 0 0 0-9-9zm5.42 13.42c-.01 0-.06.08-.07.08a6.975 6.975 0 0 1-10.7 0c-.01 0-.06-.08-.07-.08a.512.512 0 0 1-.09-.27.522.522 0 0 1 .34-.48c.74-.25 1.45-.49 1.65-.54a.16.16 0 0 1 .03-.13.49.49 0 0 1 .43-.36l1.27-.1a2.077 2.077 0 0 0-.19-.79v-.01a2.814 2.814 0 0 0-.45-.78 3.83 3.83 0 0 1-.79-2.38A3.38 3.38 0 0 1 8.88 4h.24a3.38 3.38 0 0 1 3.1 3.58 3.83 3.83 0 0 1-.79 2.38 2.814 2.814 0 0 0-.45.78v.01a2.077 2.077 0 0 0-.19.79l1.27.1a.49.49 0 0 1 .43.36.16.16 0 0 1 .03.13c.2.05.91.29 1.65.54a.49.49 0 0 1 .25.75z"></path></svg>
                    <a href="#" class="name"><?php echo $_SESSION['name']; ?></a>
                    <a href="handlers/logout.php" class="auth-btn register">Sign Out</a>
                </div>
            <?php else: ?>
                <a href="auth.html" class="auth-btn login">Login</a>
                <a href="auth.html#register" class="auth-btn register">Register</a>
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
