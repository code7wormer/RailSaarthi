<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.html?error=login_required");
    exit();
}

require_once 'includes/db_rail.php';
require_once 'includes/db_book.php';
require_once 'includes/local_service.php';

$train_number = $_GET['train_number'] ?? '';
$from_name = $_GET['from'] ?? '';
$to_name = $_GET['to'] ?? '';

if (empty($train_number)) {
    header("Location: booking.php");
    exit();
}

$info = getTrainInfo($train_number);
if (!$info) {
    die("Train not found.");
}

// Fetch availability
$stmt = $conn_book->prepare("SELECT class, available_seats FROM seat_inventory WHERE train_number = ?");
$stmt->bind_param("s", $train_number);
$stmt->execute();
$inventory = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Selected state
$selected_class = $_GET['class'] ?? ($inventory[0]['class'] ?? 'SL');
$selected_date = $_GET['date'] ?? date('Y-m-d');

// Fetch already booked seats for this SPECIFIC trip
$booked_seats = [];
$stmt = $conn_book->prepare("SELECT seat_number FROM bookings WHERE train_number = ? AND class = ? AND travel_date = ? AND status = 'CONFIRMED'");
$stmt->bind_param("sss", $train_number, $selected_class, $selected_date);
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_assoc()) {
    $booked_seats[] = $row['seat_number'];
}

// Function to get berth type color class
function getBerthClass($num) {
    $rem = $num % 8;
    if ($rem == 1 || $rem == 4) return 'lower';
    if ($rem == 2 || $rem == 5) return 'middle';
    if ($rem == 3 || $rem == 6) return 'upper';
    if ($rem == 7) return 'side-lower';
    if ($rem == 0) return 'side-upper';
    return '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Booking | Rail Saarthi</title>
    <link rel="stylesheet" href="assets/css/track.css">
    <link rel="stylesheet" href="assets/css/booking.css">
    <link rel="stylesheet" href="assets/css/seat_map.css">
</head>
<body>
    <nav>
        <a href="index.php" class="nav-brand">RailSaarthi</a>
        <ul class="nav-links">
            <li><a href="booking.php">Back to Search</a></li>
        </ul>
    </nav>

    <div class="container" style="max-width: 600px; padding-top: 8rem;">
        <div class="search-card">
            <h2 style="margin-bottom: 2rem; border-bottom: 2px solid #3b82f6; display: inline-block; padding-bottom: 0.5rem;">Confirm Your Booking</h2>
            
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                <p style="font-size: 0.8rem; color: #64748b; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem;">Journey Details</p>
                <h3 style="font-size: 1.25rem; font-weight: 800;"><?php echo htmlspecialchars($info['train_name']); ?> (<?php echo $train_number; ?>)</h3>
                <p style="margin-top: 0.5rem; color: #1e293b; font-weight: 600;"><?php echo htmlspecialchars($from_name); ?> → <?php echo htmlspecialchars($to_name); ?></p>
            </div>

            <form action="handlers/process_booking.php" method="POST">
                <input type="hidden" name="train_number" value="<?php echo htmlspecialchars($train_number); ?>">
                <input type="hidden" name="train_name" value="<?php echo htmlspecialchars($info['train_name']); ?>">
                <input type="hidden" name="from_station" value="<?php echo htmlspecialchars($from_name); ?>">
                <input type="hidden" name="to_station" value="<?php echo htmlspecialchars($to_name); ?>">
                
                <div class="input-group" style="margin-bottom: 1.5rem;">
                    <label>Travel Date</label>
                    <input type="date" id="travel_date" name="travel_date" required min="<?php echo date('Y-m-d'); ?>" 
                           value="<?php echo htmlspecialchars($selected_date); ?>" 
                           onchange="updateLayout()">
                </div>

                <div class="input-group" style="margin-bottom: 1.5rem;">
                    <label>Select Class</label>
                    <select name="class" id="class_selector" required style="padding: 1rem; border-radius: 12px; border: 1px solid #e2e8f0; font-family: inherit; font-size: 1rem; width: 100%;" onchange="updateLayout()">
                        <?php foreach($inventory as $item): ?>
                            <option value="<?php echo $item['class']; ?>" <?php echo $selected_class == $item['class'] ? 'selected' : ''; ?>>
                                <?php echo $item['class']; ?> (<?php echo $item['available_seats']; ?> left)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Seat Map Section -->
                <div class="coach-container">
                    <div class="coach-header">
                        <span style="font-weight: 800; color: #1e293b;">Coach Layout - <?php echo $selected_class; ?></span>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #e2e8f0; border: 1px solid #cbd5e1;"></div>
                            <span>Booked</span>
                        </div>
                    </div>

                    <div class="toilet-section">
                        <div class="toilet">Toilet</div>
                        <div class="toilet">Toilet</div>
                    </div>

                    <div class="seat-grid">
                        <?php for ($bay = 0; $bay < 9; $bay++): ?>
                            <div class="bay-row">
                                <!-- Triple Set row 1 -->
                                <?php for ($s = 1; $s <= 3; $s++): 
                                    $num = $bay * 8 + $s;
                                    $is_booked = in_array((string)$num, $booked_seats);
                                ?>
                                    <div class="seat <?php echo getBerthClass($num); ?> <?php echo $is_booked ? 'booked' : ''; ?>" 
                                         onclick="selectSeat('<?php echo $num; ?>', this)">
                                        <?php echo $num; ?>
                                    </div>
                                <?php endfor; ?>
                                <div class="aisle"></div>
                                <!-- Side SU -->
                                <?php 
                                    $num = $bay * 8 + 8; 
                                    $is_booked = in_array((string)$num, $booked_seats);
                                ?>
                                <div class="seat <?php echo getBerthClass($num); ?> <?php echo $is_booked ? 'booked' : ''; ?>"
                                     onclick="selectSeat('<?php echo $num; ?>', this)">
                                    <?php echo $num; ?>
                                </div>
                            </div>

                            <div class="bay-row" style="margin-bottom: 1rem;">
                                <!-- Triple Set row 2 -->
                                <?php for ($s = 4; $s <= 6; $s++): 
                                    $num = $bay * 8 + $s;
                                    $is_booked = in_array((string)$num, $booked_seats);
                                ?>
                                    <div class="seat <?php echo getBerthClass($num); ?> <?php echo $is_booked ? 'booked' : ''; ?>"
                                         onclick="selectSeat('<?php echo $num; ?>', this)">
                                        <?php echo $num; ?>
                                    </div>
                                <?php endfor; ?>
                                <div class="aisle"></div>
                                <!-- Side SL -->
                                <?php 
                                    $num = $bay * 8 + 7; 
                                    $is_booked = in_array((string)$num, $booked_seats);
                                ?>
                                <div class="seat <?php echo getBerthClass($num); ?> <?php echo $is_booked ? 'booked' : ''; ?>"
                                     onclick="selectSeat('<?php echo $num; ?>', this)">
                                    <?php echo $num; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="toilet-section" style="margin-top: 1rem;">
                        <div class="toilet">Toilet</div>
                        <div class="toilet">Toilet</div>
                    </div>

                    <div class="legend">
                        <div class="legend-item"><div class="legend-color lower"></div> Lower</div>
                        <div class="legend-item"><div class="legend-color middle"></div> Middle</div>
                        <div class="legend-item"><div class="legend-color upper"></div> Upper</div>
                        <div class="legend-item"><div class="legend-color side-lower"></div> Side Lower</div>
                        <div class="legend-item"><div class="legend-color side-upper"></div> Side Upper</div>
                    </div>
                </div>

                <div class="input-group" style="margin-bottom: 2rem;">
                    <label>Selected Seat</label>
                    <input type="text" name="seat_number" id="selected_seat_display" placeholder="Click on a seat above..." readonly required>
                </div>

                <button type="submit" class="search-btn" style="width: 100%; justify-content: center; font-size: 1.1rem; padding: 1.25rem;">
                    Confirm & Reserve Ticket
                </button>
            </form>
        </div>
    </div>
    <script>
        function selectSeat(num, element) {
            if (element.classList.contains('booked')) return;

            // Remove previous selection
            document.querySelectorAll('.seat.selected').forEach(s => s.classList.remove('selected'));
            
            // Add new selection
            element.classList.add('selected');
            document.getElementById('selected_seat_display').value = num;
        }

        function updateLayout() {
            const classVal = document.getElementById('class_selector').value;
            const dateVal = document.getElementById('travel_date').value;
            const trainNum = '<?php echo $train_number; ?>';
            const from = '<?php echo urlencode($from_name); ?>';
            const to = '<?php echo urlencode($to_name); ?>';
            
            window.location.href = `confirm_booking.php?train_number=${trainNum}&from=${from}&to=${to}&class=${classVal}&date=${dateVal}`;
        }
    </script>
</body>
</html>
