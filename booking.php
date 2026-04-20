<?php
session_start();
require_once 'includes/api_helper.php';

$from_code = $_GET['from_code'] ?? '';
$to_code = $_GET['to_code'] ?? '';
$date = $_GET['date'] ?? date('Y-m-d');
$trains = [];
$error_message = null;

if (!empty($from_code) && !empty($to_code)) {
    require_once 'includes/local_service.php';
    $trains = searchTrainsBetween($from_code, $to_code);
    
    if (empty($trains)) {
        $error_message = "No trains found for the selected route in our database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Journey | Rail Saarthi</title>
    <link rel="stylesheet" href="assets/css/booking.css">
</head>
<body>

    <nav>
        <a href="index.php" class="nav-brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 20h20M7 20v-5a2 2 0 0 1 2-2h6a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v5M8 13V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6M9 5V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
            RailSaarthi
        </a>
        <ul class="nav-links">
            <li><a href="TrackMyTrain.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4.5 8-11.8A8 8 0 0 0 4 10.2c0 7.3 8 11.8 8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                Track
            </a></li>
            <li><a href="TrainKundli.php">
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

    <div class="booking-container">
        <header class="booking-header">
            <h1>Book Your Journey</h1>
            <p>Experience seamless rail travel with India's smartest booking assistant.</p>
        </header>

        <div class="search-card">
            <form class="search-form" method="GET" action="booking.php">
                <div class="form-group">
                    <label>From (Station Code)</label>
                    <input type="text" name="from_code" placeholder="e.g. NDLS" value="<?php echo htmlspecialchars($from_code); ?>" required>
                </div>
                <div class="form-group">
                    <label>To (Station Code)</label>
                    <input type="text" name="to_code" placeholder="e.g. MMCT" value="<?php echo htmlspecialchars($to_code); ?>" required>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
                </div>
                <button type="submit" class="search-btn">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Search Trains
                </button>
            </form>
        </div>

        <?php if ($error_message): ?>
            <div class="error-box" style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid #fecaca;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="results-section">
            <?php if (!empty($trains)): ?>
                <?php foreach ($trains as $train): ?>
                    <div class="train-card">
                        <div class="train-main-info">
                            <h3><?php echo htmlspecialchars($train['train_name'] ?? 'N/A'); ?></h3>
                            <p>Train #<?php echo htmlspecialchars($train['train_number'] ?? 'N/A'); ?> • <?php echo htmlspecialchars($train['run_days'] ?? 'Daily'); ?></p>
                        </div>
                        <div class="journey-details" style="display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 2rem; margin: 1.5rem 0;">
                            <div class="time-box">
                                <h4 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($train['from_std'] ?? '--:--'); ?></h4>
                                <p style="color: #64748b; font-weight: 600; font-size: 0.875rem;"><?php echo htmlspecialchars($train['from_station_name'] ?? ''); ?></p>
                            </div>
                            <div class="journey-path" style="height: 2px; background: #e2e8f0; position: relative;">
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 0 0.5rem; color: #94a3b8; font-size: 0.75rem; font-weight: 700;"><?php echo htmlspecialchars($train['duration'] ?? ''); ?></div>
                            </div>
                            <div class="time-box" style="text-align: right;">
                                <h4 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($train['to_sta'] ?? '--:--'); ?></h4>
                                <p style="color: #64748b; font-weight: 600; font-size: 0.875rem;"><?php echo htmlspecialchars($train['to_station_name'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="price-booking" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                            <div class="classes-chips" style="display: flex; gap: 0.5rem;">
                                <?php 
                                $classes = explode(',', $train['classes'] ?? '');
                                foreach($classes as $class): if(!empty($class)): ?>
                                    <span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; color: #475569;"><?php echo trim($class); ?></span>
                                <?php endif; endforeach; ?>
                            </div>
                            <a href="#" class="book-btn" style="background: #000; color: #fff; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 700; text-decoration: none; transition: transform 0.2s;">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif (!empty($from_code)): ?>
                <div class="no-results" style="text-align: center; padding: 4rem 2rem; color: #64748b;">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 1rem; opacity: 0.5;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p>No trains available for the selected route.</p>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 4rem 2rem; color: #64748b;">
                     <p>Enter station codes above to find trains.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Rail Saarthi - The Smart Rail Assistant. All Rights Reserved.</p>
    </footer>

</body>
</html>
