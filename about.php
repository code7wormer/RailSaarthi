<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Rail Saarthi</title>
    <meta name="description"
        content="Meet the team behind Rail Saarthi — the smart rail assistant built by students of PDPM IIITDM Jabalpur.">
    <link rel="stylesheet" href="assets/css/about.css">
</head>

<body>

    <nav>
        <a href="index.php" class="nav-brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path
                    d="M2 20h20M7 20v-5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v5M8 13V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6M9 5V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
            </svg>
            RailSaarthi
        </a>
        <ul class="nav-links">
            <li><a href="TrackMyTrain.php">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 22s8-4.5 8-11.8A8 8 0 0 0 4 10.2c0 7.3 8 11.8 8 11.8z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span>Track</span>
                </a></li>
            <li><a href="TrainKundli.php">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                    <span>Kundli</span>
                </a></li>
            <li><a href="booking.php" class="highlight">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M2 9V5.25A2.25 2.25 0 0 1 4.25 3h15.5A2.25 2.25 0 0 1 22 5.25V9" />
                        <path d="M22 15v3.75A2.25 2.25 0 0 1 19.75 21H4.25A2.25 2.25 0 0 1 2 18.75V15" />
                        <path d="M12 3v18" />
                        <path d="M2 12h5" />
                        <path d="M17 12h5" />
                        <path d="M7 12a5 5 0 0 1 10 0" />
                    </svg>
                    <span>Booking</span>
                </a></li>
            <li><a href="TripPlanner.php">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" />
                    </svg>
                    <span>Planner</span>
                </a></li>
            <li><a href="about.php">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4" />
                        <path d="M12 8h.01" />
                    </svg>
                    <span>About</span>
                </a></li>
        </ul>
        <div class="nav-auth">
            <?php if (isset($_SESSION['user_id'])): ?>
        <div class="profile-section">
            <svg width="30px" height="30px" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier">
                <path fill="#494c4e" d="M9 0a9 9 0 0 0-9 9 8.654 8.654 0 0 0 .05.92 9 9 0 0 0 17.9 0A8.654 8.654 0 0 0 18 9a9 9 0 0 0-9-9zm5.42 13.42c-.01 0-.06.08-.07.08a6.975 6.975 0 0 1-10.7 0c-.01 0-.06-.08-.07-.08a.512.512 0 0 1-.09-.27.522.522 0 0 1 .34-.48c.74-.25 1.45-.49 1.65-.54a.16.16 0 0 1 .03-.13.49.49 0 0 1 .43-.36l1.27-.1a2.077 2.077 0 0 0-.19-.79v-.01a2.814 2.814 0 0 0-.45-.78 3.83 3.83 0 0 1-.79-2.38A3.38 3.38 0 0 1 8.88 4h.24a3.38 3.38 0 0 1 3.1 3.58 3.83 3.83 0 0 1-.79 2.38 2.814 2.814 0 0 0-.45.78v.01a2.077 2.077 0 0 0-.19.79l1.27.1a.49.49 0 0 1 .43.36.16.16 0 0 1 .03.13c.2.05.91.29 1.65.54a.49.49 0 0 1 .25.75z"></path> </g></svg>
            <a href="#" class="name" ><?php echo $_SESSION['name']; ?></a>
            <a href="handlers/logout.php" class="auth-btn register">Sign Out</a>
        </div>

    <?php else: ?>

        <a href="auth.html" class="auth-btn login">Login</a>
        <a href="auth.html#register" class="auth-btn register">Register</a>

    <?php endif; ?>
        </div>
    </nav>

    <section class="about-hero">
        <div class="hero-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 2L2 7l10 5 10-5-10-5z" />
                <path d="M2 17l10 5 10-5" />
                <path d="M2 12l10 5 10-5" />
            </svg>
            Application Programming Project 2nd Semester
        </div>

        <div class="team-section">
            <div class="team-grid">
                <div class="team-card">
                    <div class="avatar-ring">
                        <div class="avatar-icon">SS</div>
                    </div>
                    <h3 class="member-name">Shardul Singh</h3>
                    <div class="member-id">BT25CSH050</div>
                    <p class="member-role">Frontend</p>
                </div>
                <div class="team-card">
                    <div class="avatar-ring">
                        <div class="avatar-icon">SJ</div>
                    </div>
                    <h3 class="member-name">Samyaak Jain</h3>
                    <div class="member-id">BT25CSH048</div>
                    <p class="member-role">Architecture & Design</p>
                </div>
                <div class="team-card">
                    <div class="avatar-ring">
                        <div class="avatar-icon">SK</div>
                    </div>
                    <h3 class="member-name">Samarth Kodape</h3>
                    <div class="member-id">BT25CSH049</div>
                    <p class="member-role">Backend & Database</p>
                </div>
            </div>
        </div>

        <p class="about-subtitle">Rail Saarthi is a project crafted by us three IIITN students, bringing smart and
            seamless railway navigation to life.</p>
    </section>

    <div class="divider"></div>

    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Pages</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">3</div>
                <div class="stat-label">Features</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">3</div>
                <div class="stat-label">Developers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1</div>
                <div class="stat-label">Semester</div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <section class="tech-section">
        <p class="section-label">Powered By</p>
        <h2 class="section-title">Our Tech Stack</h2>
        <div class="tech-grid">
            <div class="tech-pill">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                </svg>
                HTML5
            </div>
            <div class="tech-pill">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6l8 4 8-4" />
                    <path d="M4 10l8 4 8-4" />
                    <path d="M4 14l8 4 8-4" />
                </svg>
                CSS3
            </div>
            <div class="tech-pill">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="16 18 22 12 16 6" />
                    <polyline points="8 6 2 12 8 18" />
                </svg>
                JavaScript
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Rail Saarthi &mdash; Application Programming Project 2nd Semester. <a href="index.html">Back to
                Home</a></p>
    </footer>

</body>

</html>