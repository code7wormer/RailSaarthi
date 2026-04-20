-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2026 at 10:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rail_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `station_code` varchar(10) NOT NULL,
  `station_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`station_code`, `station_name`) VALUES
('ADI', 'Ahmedabad Jn'),
('BCT', 'Mumbai Central'),
('BRC', 'Vadodara'),
('BVI', 'Borivali'),
('GKP', 'Gorakhpur'),
('HWH', 'Howrah Jn'),
('JP', 'Jaipur'),
('KOTA', 'Kota Jn'),
('LKO', 'Lucknow NR'),
('MAS', 'Chennai Central'),
('MMCT', 'Mumbai Central'),
('MTJ', 'Mathura Jn'),
('NDLS', 'New Delhi'),
('NZM', 'Hazrat Nizamuddin'),
('PNBE', 'Patna Jn'),
('RTM', 'Ratlam Jn'),
('SBC', 'KSR Bengaluru'),
('SC', 'Secunderabad Jn'),
('ST', 'Surat'),
('SWM', 'Sawai Madhopur');

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE `trains` (
  `train_number` varchar(10) NOT NULL,
  `train_name` varchar(100) NOT NULL,
  `train_type` varchar(50) DEFAULT NULL,
  `run_days` varchar(100) DEFAULT NULL,
  `from_station_code` varchar(10) DEFAULT NULL,
  `to_station_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trains`
--

INSERT INTO `trains` (`train_number`, `train_name`, `train_type`, `run_days`, `from_station_code`, `to_station_code`) VALUES
('11002', 'Deccan Queen', 'Express', 'Daily', 'MMCT', 'BCT'),
('12001', 'Shatabdi Express', 'Shatabdi', 'Daily', 'NZM', 'NDLS'),
('12002', 'Bhopal Shatabdi', 'Shatabdi', 'Daily', 'NDLS', 'NZM'),
('12005', 'Kalka Shatabdi', 'Shatabdi', 'Daily', 'NDLS', 'NZM'),
('12006', 'Kalka Shatabdi', 'Shatabdi', 'Daily', 'NZM', 'NDLS'),
('12009', 'Shatabdi Express', 'Shatabdi', 'Except Sun', 'BCT', 'ADI'),
('12010', 'Shatabdi Express', 'Shatabdi', 'Except Sun', 'ADI', 'BCT'),
('12051', 'Jan Shatabdi', 'Jan Shatabdi', 'Daily', 'BCT', 'ADI'),
('12124', 'Deccan Queen', 'Express', 'Daily', 'ADI', 'BCT'),
('12224', 'Duronto Express', 'Duronto', 'Tue, Sat', 'ADI', 'BCT'),
('12267', 'Duronto Express', 'Duronto', 'Daily', 'BCT', 'ADI'),
('12301', 'Kolkata Rajdhani', 'Rajdhani', 'Mon, Tue, Wed, Thu, Fri, Sat', 'HWH', 'NDLS'),
('12431', 'Rajdhani Express', 'Rajdhani', 'Tue, Thu, Fri', 'MAS', 'NZM'),
('12432', 'Trivandrum Rajdhani', 'Rajdhani', 'Tue, Wed, Sun', 'NZM', 'MAS'),
('12901', 'Gujarat Mail', 'Express', 'Daily', 'BCT', 'ADI'),
('12903', 'Golden Temple Mail', 'Express', 'Daily', 'BCT', 'NZM'),
('12904', 'Golden Temple Mail', 'Express', 'Daily', 'NZM', 'BCT'),
('12907', 'Maharashtra Sampark Kranti', 'S. Kranti', 'Mon, Thu', 'BCT', 'NZM'),
('12909', 'Garib Rath', 'Garib Rath', 'Tue, Thu, Sat', 'BCT', 'NZM'),
('12910', 'Garib Rath', 'Garib Rath', 'Wed, Fri, Sun', 'NZM', 'BCT'),
('12925', 'Paschim Express', 'Express', 'Daily', 'BCT', 'NDLS'),
('12936', 'Surat Intercity', 'Express', 'Daily', 'ST', 'BCT'),
('12951', 'Mumbai Rajdhani Express', 'Rajdhani', 'Daily', 'BCT', 'NDLS'),
('12952', 'New Delhi Rajdhani Express', 'Rajdhani', 'Daily', 'NDLS', 'BCT'),
('12953', 'August Kranti Rajdhani', 'Rajdhani', 'Daily', 'BCT', 'NZM'),
('12954', 'August Kranti Rajdhani', 'Rajdhani', 'Daily', 'NZM', 'BCT'),
('12961', 'Avantika Express', 'Express', 'Daily', 'BCT', 'ADI'),
('20901', 'Vande Bharat Express', 'Vande Bharat', 'Except Sun', 'BCT', 'ADI'),
('20902', 'Vande Bharat Express', 'Vande Bharat', 'Except Sun', 'ADI', 'BCT'),
('22222', 'Mumbai Rajdhani', 'Rajdhani', 'Daily', 'NZM', 'BCT');

-- --------------------------------------------------------

--
-- Table structure for table `train_schedule`
--

CREATE TABLE `train_schedule` (
  `id` int(11) NOT NULL,
  `train_number` varchar(10) DEFAULT NULL,
  `station_code` varchar(10) DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `stop_number` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `train_schedule`
--

INSERT INTO `train_schedule` (`id`, `train_number`, `station_code`, `arrival_time`, `departure_time`, `stop_number`, `distance`) VALUES
(1, '12951', 'BCT', NULL, '17:00:00', 1, 0),
(2, '12951', 'BVI', '17:22:00', '17:24:00', 2, 30),
(3, '12951', 'ST', '19:43:00', '19:48:00', 3, 263),
(4, '12951', 'BRC', '21:03:00', '21:13:00', 4, 392),
(5, '12951', 'RTM', '00:25:00', '00:28:00', 5, 653),
(6, '12951', 'KOTA', '03:15:00', '03:20:00', 6, 920),
(7, '12951', 'NDLS', '08:32:00', NULL, 7, 1384),
(8, '12952', 'NDLS', NULL, '16:55:00', 1, 0),
(9, '12952', 'KOTA', '21:30:00', '21:40:00', 2, 465),
(10, '12952', 'RTM', '00:30:00', '00:33:00', 3, 730),
(11, '12952', 'BRC', '03:30:00', '03:40:00', 4, 992),
(12, '12952', 'ST', '05:13:00', '05:18:00', 5, 1121),
(13, '12952', 'BVI', '07:40:00', '07:42:00', 6, 1354),
(14, '12952', 'BCT', '08:35:00', NULL, 7, 1384),
(15, '12002', 'NDLS', NULL, '06:00:00', 1, 0),
(16, '12002', 'MTJ', '07:19:00', '07:20:00', 2, 141),
(17, '12002', 'KOTA', '10:30:00', '10:35:00', 3, 465),
(18, '12002', 'NZM', '14:00:00', NULL, 4, 700),
(19, '20901', 'BCT', NULL, '06:10:00', 1, 0),
(20, '20901', 'BVI', '06:33:00', '06:35:00', 2, 30),
(21, '20901', 'ST', '08:55:00', '08:58:00', 4, 263),
(22, '20901', 'BRC', '10:13:00', '10:16:00', 5, 392),
(23, '20901', 'ADI', '11:25:00', NULL, 6, 492),
(25, '12936', 'ST', NULL, '16:35:00', 1, 0),
(26, '12936', 'BVI', '19:49:00', '19:51:00', 2, 233),
(27, '12936', 'BCT', '20:40:00', NULL, 3, 263),
(28, '12010', 'BCT', '08:00:00', '08:15:00', 1, 0),
(29, '12124', 'BCT', '08:00:00', '08:15:00', 1, 0),
(30, '12224', 'BCT', '08:00:00', '08:15:00', 1, 0),
(31, '20902', 'BCT', '08:00:00', '08:15:00', 1, 0),
(32, '12009', 'BCT', '08:00:00', '08:15:00', 1, 0),
(33, '12051', 'BCT', '08:00:00', '08:15:00', 1, 0),
(34, '12267', 'BCT', '08:00:00', '08:15:00', 1, 0),
(35, '12901', 'BCT', '08:00:00', '08:15:00', 1, 0),
(36, '12903', 'BCT', '08:00:00', '08:15:00', 1, 0),
(37, '12907', 'BCT', '08:00:00', '08:15:00', 1, 0),
(43, '12010', 'ADI', '14:00:00', NULL, 2, 500),
(44, '12124', 'ADI', '14:00:00', NULL, 2, 500),
(45, '12224', 'ADI', '14:00:00', NULL, 2, 500),
(46, '20902', 'ADI', '14:00:00', NULL, 2, 500),
(47, '12009', 'ADI', '14:00:00', NULL, 2, 500),
(48, '12051', 'ADI', '14:00:00', NULL, 2, 500),
(49, '12267', 'ADI', '14:00:00', NULL, 2, 500),
(50, '12901', 'ADI', '14:00:00', NULL, 2, 500),
(51, '12903', 'ADI', '14:00:00', NULL, 2, 500),
(52, '12907', 'ADI', '14:00:00', NULL, 2, 500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`station_code`);

--
-- Indexes for table `trains`
--
ALTER TABLE `trains`
  ADD PRIMARY KEY (`train_number`),
  ADD KEY `from_station_code` (`from_station_code`),
  ADD KEY `to_station_code` (`to_station_code`);

--
-- Indexes for table `train_schedule`
--
ALTER TABLE `train_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `train_number` (`train_number`),
  ADD KEY `station_code` (`station_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `train_schedule`
--
ALTER TABLE `train_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `trains`
--
ALTER TABLE `trains`
  ADD CONSTRAINT `trains_ibfk_1` FOREIGN KEY (`from_station_code`) REFERENCES `stations` (`station_code`),
  ADD CONSTRAINT `trains_ibfk_2` FOREIGN KEY (`to_station_code`) REFERENCES `stations` (`station_code`);

--
-- Constraints for table `train_schedule`
--
ALTER TABLE `train_schedule`
  ADD CONSTRAINT `train_schedule_ibfk_1` FOREIGN KEY (`train_number`) REFERENCES `trains` (`train_number`),
  ADD CONSTRAINT `train_schedule_ibfk_2` FOREIGN KEY (`station_code`) REFERENCES `stations` (`station_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
