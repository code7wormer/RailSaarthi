<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Add New Train</title>
    <link rel="stylesheet" href="assets/css/track.css">
    <style>
        * { box-sizing: border-box; }
        .admin-container {
            padding: 8rem 10% 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .admin-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        .admin-header h1 {
            font-size: 3rem;
            font-weight: 900;
            letter-spacing: -2px;
            color: var(--navy);
            margin-bottom: 1rem;
        }
        .admin-card {
            background: #ffffff;
            padding: 3rem;
            border-radius: 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
        }
        .form-section {
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .form-section h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .admin-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        .schedule-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1.25rem;
            background: #f8fafc;
            border-radius: 16px;
        }
        .schedule-header {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--slate);
            text-transform: uppercase;
            padding: 0 1.25rem;
            margin-bottom: 1rem;
        }
        
        /* Consistent Widths */
        .col-stop { width: 70px; flex-shrink: 0; }
        .col-code { width: 140px; flex-shrink: 0; }
        .col-time { width: 160px; flex-shrink: 0; }
        .col-dist { width: 100px; flex-shrink: 0; }
        .col-notes { flex-grow: 1; min-width: 100px; color: #94a3b8; font-weight: 700; font-size: 0.7rem; }

        .btn-add-row {
            background: #f1f5f9;
            color: var(--navy);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 1rem;
            transition: all 0.2s;
        }
        .btn-add-row:hover { background: #e2e8f0; }
        .submit-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 1.25rem 3rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 1.1rem;
            cursor: pointer;
            display: block;
            margin: 3rem auto 0;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
            transition: all 0.2s;
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3); }
        
        input, select {
            width: 100%;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="nav-brand">RailSaarthi Admin</a>
        <div class="nav-auth">
            <span style="font-weight: 600; color: var(--slate); margin-right: 1.5rem;">Hello, Admin</span>
            <a href="handlers/logout.php" class="auth-btn register">Logout</a>
        </div>
    </nav>

    <div class="admin-container">
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 1.5rem; border-radius: 20px; margin-bottom: 2rem; font-weight: 700; text-align: center; border: 1px solid #bbfcce;">
            TRP INITIALIZED: Train Published Successfully!
        </div>
        <?php endif; ?>

        <div class="admin-header">
            <h1>Initialize New Train</h1>
            <p style="color: var(--slate); font-weight: 500;">Fill in the master data and operational schedule below.</p>
        </div>

        <div class="admin-card">
            <form action="handlers/process_admin_train.php" method="POST">
                <div class="form-section">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        Train Identity
                    </h3>
                    <div class="admin-form">
                        <div class="input-group">
                            <label>Train Number</label>
                            <input type="text" name="train_number" placeholder="e.g. 12952" required>
                        </div>
                        <div class="input-group">
                            <label>Train Name</label>
                            <input type="text" name="train_name" placeholder="e.g. Mumbai Rajdhani Express" required>
                        </div>
                        <div class="input-group">
                            <label>Train Type</label>
                            <select name="train_type">
                                <option value="Rajdhani">Rajdhani</option>
                                <option value="Shatabdi">Shatabdi</option>
                                <option value="Vande Bharat">Vande Bharat</option>
                                <option value="Express" selected>Express</option>
                                <option value="Superfast">Superfast</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label>From Station Code</label>
                            <input type="text" name="from_code" placeholder="e.g. MMCT" required>
                        </div>
                        <div class="input-group">
                            <label>To Station Code</label>
                            <input type="text" name="to_code" placeholder="e.g. NDLS" required>
                        </div>
                        <div class="input-group">
                            <label>Running Days</label>
                            <input type="text" name="run_days" value="Daily" placeholder="e.g. MON, WED, FRI">
                        </div>
                    </div>
                </div>

                <div class="form-section" style="border: none;">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Operational Schedule
                    </h3>
                    
                    <div class="schedule-header">
                        <span># Stop</span>
                        <span>Station Code</span>
                        <span>Arrival</span>
                        <span>Departure</span>
                        <span>Dist (km)</span>
                        <span>Notes</span>
                    </div>

                    <div id="scheduleContainer">
                        <!-- Origin -->
                        <div class="schedule-row">
                            <input type="number" name="stop_no[]" value="1" readonly>
                            <input type="text" name="s_code[]" placeholder="Code" required>
                            <input type="time" name="s_arr[]" value="00:00" readonly>
                            <input type="time" name="s_dep[]" required>
                            <input type="number" name="s_dist[]" value="0" readonly>
                            <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 700;">ORIGIN</span>
                        </div>
                        
                        <!-- Middle -->
                        <div class="schedule-row">
                            <input type="number" name="stop_no[]" value="2">
                            <input type="text" name="s_code[]" placeholder="Code" required>
                            <input type="time" name="s_arr[]" required>
                            <input type="time" name="s_dep[]" required>
                            <input type="number" name="s_dist[]" placeholder="Km" required>
                            <span>--</span>
                        </div>

                        <!-- Destination -->
                        <div class="schedule-row">
                            <input type="number" name="stop_no[]" value="3">
                            <input type="text" name="s_code[]" placeholder="Code" required>
                            <input type="time" name="s_arr[]" required>
                            <input type="time" name="s_dep[]" value="00:00" readonly>
                            <input type="number" name="s_dist[]" placeholder="Km" required>
                            <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 700;">DESTINATION</span>
                        </div>
                    </div>

                    <button type="button" class="btn-add-row" onclick="addRow()">+ Insert Station</button>
                </div>

                <button type="submit" class="submit-btn">Publish Train Data</button>
            </form>
        </div>
    </div>

    <script>
        function addRow() {
            const container = document.getElementById('scheduleContainer');
            const rows = container.getElementsByClassName('schedule-row');
            const newIndex = rows.length + 1;
            
            // For simplicity in this demo, we'll just insert a middle row before the last one
            const newRow = document.createElement('div');
            newRow.className = 'schedule-row';
            newRow.innerHTML = `
                <input type="number" name="stop_no[]" value="${newIndex}">
                <input type="text" name="s_code[]" placeholder="Code" required>
                <input type="time" name="s_arr[]" required>
                <input type="time" name="s_dep[]" required>
                <input type="number" name="s_dist[]" placeholder="Km" required>
                <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color:#ef4444; font-weight:800; cursor:pointer;">&times;</button>
            `;
            
            // Insert before the last child (destination)
            container.insertBefore(newRow, rows[rows.length - 1]);
            
            // Re-index all rows
            updateIndices();
        }

        function updateIndices() {
            const container = document.getElementById('scheduleContainer');
            const rows = container.getElementsByClassName('schedule-row');
            for(let i=0; i<rows.length; i++) {
                rows[i].getElementsByTagName('input')[0].value = i + 1;
            }
        }
    </script>
</body>
</html>
