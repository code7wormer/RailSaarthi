<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Tracking | Rail Saarthi</title>
    <link rel="stylesheet" href="track.css">
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
            <li><a href="TrackMyTrain.php" class="active">
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
            <svg width="30px" height="30px" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier">
                <path fill="#494c4e" d="M9 0a9 9 0 0 0-9 9 8.654 8.654 0 0 0 .05.92 9 9 0 0 0 17.9 0A8.654 8.654 0 0 0 18 9a9 9 0 0 0-9-9zm5.42 13.42c-.01 0-.06.08-.07.08a6.975 6.975 0 0 1-10.7 0c-.01 0-.06-.08-.07-.08a.512.512 0 0 1-.09-.27.522.522 0 0 1 .34-.48c.74-.25 1.45-.49 1.65-.54a.16.16 0 0 1 .03-.13.49.49 0 0 1 .43-.36l1.27-.1a2.077 2.077 0 0 0-.19-.79v-.01a2.814 2.814 0 0 0-.45-.78 3.83 3.83 0 0 1-.79-2.38A3.38 3.38 0 0 1 8.88 4h.24a3.38 3.38 0 0 1 3.1 3.58 3.83 3.83 0 0 1-.79 2.38 2.814 2.814 0 0 0-.45.78v.01a2.077 2.077 0 0 0-.19.79l1.27.1a.49.49 0 0 1 .43.36.16.16 0 0 1 .03.13c.2.05.91.29 1.65.54a.49.49 0 0 1 .25.75z"></path> </g></svg>
            <a href="#" class="name" ><?php echo $_SESSION['name']; ?></a>
            <a href="logout.php" class="auth-btn register">Sign Out</a>
        </div>

    <?php else: ?>

        <a href="auth.html" class="auth-btn login">Login</a>
        <a href="auth.html#register" class="auth-btn register">Register</a>

    <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <h1>Track Your Train</h1>
        
        <div class="status-box">
            <div class="train-info">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><line x1="4" y1="13" x2="20" y2="13"/><line x1="8" y1="17" x2="8" y2="17.01"/><line x1="16" y1="17" x2="16" y2="17.01"/></svg>
                Shatabdi Express (12002) - NDLS to AGC
            </div>
            
            <div class="status-summary">
                <p style="margin-bottom: 0.5rem; color: #475569; font-weight: 700; font-size: 0.75rem; letter-spacing: 1px;">CURRENT LOCATION</p>
                <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">Mathura Junction (MTJ)</div>
                <p style="margin-top: 1.5rem; font-weight: 700; color: #ef4444; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Delayed by 15 mins
                </p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Station</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>New Delhi (NDLS)</td>
                        <td>--</td>
                        <td>06:00 AM</td>
                        <td><span class="badge on-time">On Time</span></td>
                    </tr>
                    <tr>
                        <td>Mathura Jn (MTJ)</td>
                        <td>07:15 AM</td>
                        <td>07:17 AM</td>
                        <td><span class="badge delayed">Delayed</span></td>
                    </tr>
                    <tr>
                        <td>Agra Cantt (AGC)</td>
                        <td>08:12 AM</td>
                        <td>--</td>
                        <td><span class="badge delayed">Expt. 08:27 AM</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Rail Saarthi - Dynamic Tracking System</p>
    </footer>

</body>
</html>
