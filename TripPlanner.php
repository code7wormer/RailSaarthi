<?php
session_start();
require_once 'includes/api_helper.php';

$station_code = $_GET['station_code'] ?? '';
$trains = [];
$error_message = null;

if (!empty($station_code)) {
    require_once 'includes/local_service.php';
    $trains = getTrainsAtStation($station_code);
    
    if (empty($trains)) {
        $error_message = "No trains found for this station in our database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trip Planner | Rail Saarthi</title>
    <link rel="stylesheet" href="assets/css/planner.css">
    <link rel="stylesheet" href="assets/css/track.css"> <!-- Reuse for search box styles -->
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
            <li><a href="booking.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9V5.25A2.25 2.25 0 0 1 4.25 3h15.5A2.25 2.25 0 0 1 22 5.25V9"/><path d="M22 15v3.75A2.25 2.25 0 0 1 19.75 21H4.25A2.25 2.25 0 0 1 2 18.75V15"/><path d="M12 3v18"/><path d="M2 12h5"/><path d="M17 12h5"/><path d="M7 12a5 5 0 0 1 10 0"/></svg>
                Booking
            </a></li>
            <li><a href="TripPlanner.php" class="active">
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

    <div class="container">
        <h1>Smart Trip Planner [DEMO MODE]</h1>
        <p class="subtitle">Explore trains arriving or departing from your favorite stations. (Simulated Data)</p>

        <div class="search-box" style="margin: 2rem 0;">
            <form method="GET" action="TripPlanner.php" class="track-form" style="grid-template-columns: 1fr auto;">
                <div class="input-group">
                    <label>Station Code</label>
                    <input type="text" name="station_code" placeholder="Enter Station Code (e.g., ADI, BCT)" value="<?php echo htmlspecialchars($station_code); ?>" required>
                </div>
                <button type="submit" class="search-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    Explore Trains (Simulated)
                </button>
            </form>
        </div>

        <?php if (!empty($trains)): ?>
            <div class="trains-list" style="display: grid; gap: 1.5rem;">
                <h3 style="margin-bottom: 0.5rem; color: #475569;">Trains found at <?php echo htmlspecialchars($station_name); ?>:</h3>
                <?php foreach ($trains as $train): ?>
                    <div class="trip-card" style="display: flex; flex-direction: column; height: auto; border: 1px dashed #cbd5e1; background: #f8fafc;">
                        <div class="trip-details">
                            <span class="tagline">TRAIN #<?php echo htmlspecialchars($train['train_number'] ?? ''); ?></span>
                            <h3><?php echo htmlspecialchars($train['train_name'] ?? 'N/A'); ?></h3>
                            <div style="display: flex; gap: 2rem; margin-top: 1rem;">
                                <div>
                                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 700;">DEPARTURE</span>
                                    <div style="font-size: 1.25rem; font-weight: 800;"><?php echo htmlspecialchars($train['departure_time'] ?? '--:--'); ?></div>
                                </div>
                                <div>
                                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 700;">PLATFORM</span>
                                    <div style="font-size: 1.25rem; font-weight: 800; color: #2563eb;"><?php echo htmlspecialchars($train['extra_info'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="trip-grid">
                <div class="trip-card">
                    <div class="trip-details">
                        <span class="tagline">Heritage • Culture • North India</span>
                        <h3>The Golden Triangle</h3>
                        <p>Experience the majestic circuit of Delhi, Agra, and Jaipur. Optimized travel via high-speed Shatabdi.</p>
                        <div class="cost-info">
                            <div>
                                <span class="price-label">Estimated Cost</span>
                                <div class="price">₹2,500 - ₹4,500</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="trip-card">
                    <div class="trip-details">
                        <span class="tagline">Western Ghats • Vande Bharat</span>
                        <h3>Coastal Retreat (Goa)</h3>
                        <p>Wind through 103 tunnels and breathtaking valleys via the Vande Bharat Express.</p>
                        <div class="cost-info">
                            <div>
                                <span class="price-label">Estimated Cost</span>
                                <div class="price">₹1,200 - ₹3,000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2026 Rail Saarthi - Your Intelligent Rail Companion</p>
    </footer>

</body>
</html>
