-- Create the database for bookings
CREATE DATABASE IF NOT EXISTS book_db;
USE book_db;

-- Seat Inventory Table
CREATE TABLE IF NOT EXISTS seat_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    train_number VARCHAR(10) NOT NULL,
    class VARCHAR(10) NOT NULL,
    total_seats INT NOT NULL DEFAULT 60,
    available_seats INT NOT NULL DEFAULT 60,
    UNIQUE KEY (train_number, class)
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    train_number VARCHAR(10) NOT NULL,
    train_name VARCHAR(100) NOT NULL,
    from_station_code VARCHAR(10) NOT NULL,
    to_station_code VARCHAR(10) NOT NULL,
    class VARCHAR(10) NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    travel_date DATE NOT NULL,
    status ENUM('CONFIRMED', 'CANCELLED') DEFAULT 'CONFIRMED',
    booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed initial seat inventory for the 5 working trains
INSERT IGNORE INTO seat_inventory (train_number, class, total_seats, available_seats) VALUES
('12951', '1A', 20, 20), ('12951', '2A', 40, 40), ('12951', '3A', 60, 60), ('12951', 'SL', 80, 80),
('12952', '1A', 20, 20), ('12952', '2A', 40, 40), ('12952', '3A', 60, 60), ('12952', 'SL', 80, 80),
('12002', 'CC', 60, 60), ('12002', 'EC', 20, 20),
('20901', 'CC', 60, 60), ('20901', 'EC', 20, 20),
('12936', '2S', 80, 80), ('12936', 'CC', 40, 40);
