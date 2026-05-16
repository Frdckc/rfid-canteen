-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 11, 2025 at 03:20 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u410635593_rfidcanteen`
--

-- --------------------------------------------------------

--
-- Table structure for table `system_qr`
--

DROP TABLE IF EXISTS `system_qr`;
CREATE TABLE IF NOT EXISTS `system_qr` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_qr`
--

INSERT INTO `system_qr` (`id`, `filename`, `uploaded_at`) VALUES
(1, '1758103191_oreo.jpg', '2025-09-17 09:59:51'),
(2, '1758103244_gfaLogo.jpg', '2025-09-17 10:00:44'),
(3, '1758103614_oreo.jpg', '2025-09-17 10:06:54'),
(4, '1758104130_63a771c0-80b7-4795-aee4-73dbec1153b1.jfif', '2025-09-17 10:15:30'),
(5, '1758195888_mary.jpg', '2025-09-18 11:44:48'),
(6, '1758195988_63a771c0-80b7-4795-aee4-73dbec1153b1.jfif', '2025-09-18 11:46:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE IF NOT EXISTS `tbl_category` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `category` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`ID`, `category`) VALUES
(10, 'Drinks'),
(11, 'Desserts'),
(13, 'Burger');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

DROP TABLE IF EXISTS `tbl_menu`;
CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` text NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `price` double(20,2) NOT NULL,
  `qty` int NOT NULL,
  `category` text NOT NULL,
  `imgUrl` text NOT NULL,
  `expDate` date NOT NULL,
  `dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`id`, `code`, `name`, `description`, `price`, `qty`, `category`, `imgUrl`, `expDate`, `dt`) VALUES
(18, 'BRG1', 'CHEESE BURGER', 'Cheese Burger', 45.00, 39, 'Burger', 'burger.jpg', '2026-01-01', '2025-09-14 12:02:42'),
(20, 'WTR1', 'BOTTLED WATER', 'Bottled Water', 15.00, 82, 'Drinks', 'water.jpeg', '2026-01-01', '2025-09-14 12:18:51'),
(21, 'FDB1', 'FUDGEE BARR', 'Fudgee Barr', 15.00, 40, 'Desserts', 'fudgeebarr.jpg', '2026-01-01', '2025-09-14 12:18:31'),
(22, 'PTS1', 'CHEESE PIATTOS', 'Cheese Piattos', 20.00, 1, 'Desserts', 'piatos cheese.jpg', '2026-01-01', '2025-09-22 07:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

DROP TABLE IF EXISTS `tbl_notifications`;
CREATE TABLE IF NOT EXISTS `tbl_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `studentID` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `studentID` (`studentID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`id`, `studentID`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(8, 28, 'Payment Approved', 'Your payment of ₱2,990.00 has been approved. New balance: ₱3,700.00', 'success', 0, '2025-11-07 14:09:10'),
(7, 28, 'Payment Declined', 'Your payment of ₱2,000.00 has been declined. Reason: testing decline message reason here...', 'danger', 0, '2025-11-07 14:08:37'),
(6, 31, 'Payment Declined', 'Your payment of ₱100.00 has been declined. Reason: deds', 'danger', 0, '2025-11-07 13:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_parent_student_links`
--

DROP TABLE IF EXISTS `tbl_parent_student_links`;
CREATE TABLE IF NOT EXISTS `tbl_parent_student_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL,
  `student_id` int NOT NULL,
  `linked_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_parent_student_links`
--

INSERT INTO `tbl_parent_student_links` (`id`, `parent_id`, `student_id`, `linked_at`) VALUES
(10, 28, 33, '2025-11-07 14:03:59'),
(9, 28, 31, '2025-11-07 14:03:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

DROP TABLE IF EXISTS `tbl_payment`;
CREATE TABLE IF NOT EXISTS `tbl_payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoiceNo` text NOT NULL,
  `sumTotal` double(20,2) NOT NULL,
  `sumQTY` int NOT NULL,
  `sumDiscount` double(20,2) NOT NULL,
  `xtotal` double(20,2) NOT NULL,
  `dt` datetime NOT NULL,
  `cash` double(20,2) NOT NULL,
  `subtotal` double(20,2) NOT NULL,
  `salestax` double(20,2) NOT NULL,
  `disNo` text NOT NULL,
  `disName` text NOT NULL,
  `cusType` text NOT NULL,
  `dtNow` date NOT NULL,
  `received` int NOT NULL DEFAULT '1',
  `staffid` int NOT NULL,
  `processed_by` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payment`
--

INSERT INTO `tbl_payment` (`id`, `invoiceNo`, `sumTotal`, `sumQTY`, `sumDiscount`, `xtotal`, `dt`, `cash`, `subtotal`, `salestax`, `disNo`, `disName`, `cusType`, `dtNow`, `received`, `staffid`, `processed_by`) VALUES
(3, '202506120151', 44.64, 0, 0.00, 50.00, '2025-06-12 02:01:09', 50.00, 44.64, 5.36, '', '', 'Walk-in', '2025-06-12', 1, 37, NULL),
(4, '20250612020113', 22.32, 0, 0.00, 25.00, '2025-06-12 02:41:31', 25.00, 22.32, 2.68, '', '', 'Walk-in', '2025-06-12', 1, 37, NULL),
(5, '20250612024140', 94.64, 0, 0.00, 106.00, '2025-06-12 03:41:05', 106.00, 94.64, 11.36, '', '', 'Walk-in', '2025-06-12', 1, 37, NULL),
(6, '20250612103539', 25.00, 0, 0.00, 28.00, '2025-06-12 10:37:24', 28.00, 25.00, 3.00, '', '', 'Walk-in', '2025-06-12', 1, 37, NULL),
(7, '20250720202351', 46.43, 0, 0.00, 52.00, '2025-07-20 20:32:16', 52.00, 46.43, 5.57, '', '', 'Walk-in', '2025-07-20', 1, 16, NULL),
(8, '20250723013918', 3.57, 0, 0.00, 4.00, '2025-07-23 01:44:38', 4.00, 3.57, 0.43, '', '', 'Walk-in', '2025-07-23', 1, 37, NULL),
(9, '20250723014458', 44.64, 0, 0.00, 50.00, '2025-07-23 01:47:11', 50.00, 44.64, 5.36, '', '', 'Walk-in', '2025-07-23', 1, 37, NULL),
(10, '20250826171335', 89.29, 0, 0.00, 100.00, '2025-08-26 17:18:36', 100.00, 89.29, 10.71, '', '', 'Walk-in', '2025-08-26', 1, 37, NULL),
(11, '20250826171911', 111.61, 0, 0.00, 125.00, '2025-08-26 17:19:42', 125.00, 111.61, 13.39, '', '', 'Walk-in', '2025-08-26', 1, 37, NULL),
(28, '20250913161627', 24.11, 0, 0.00, 27.00, '2025-09-13 16:22:15', 27.00, 24.11, 2.89, '', '', 'Walk-in', '2025-09-13', 1, 1, NULL),
(29, '20250913161627', 24.11, 0, 0.00, 27.00, '2025-09-13 16:22:17', 27.00, 24.11, 2.89, '', '', 'Walk-in', '2025-09-13', 1, 1, NULL),
(30, '20250913162204', 2.68, 0, 0.00, 3.00, '2025-09-13 16:22:25', 3.00, 2.68, 0.32, '', '', 'Walk-in', '2025-09-13', 1, 37, NULL),
(31, '20250913161627', 24.11, 0, 0.00, 27.00, '2025-09-13 16:22:38', 27.00, 24.11, 2.89, '', '', 'Walk-in', '2025-09-13', 1, 1, NULL),
(32, '20250913161627', 24.11, 0, 0.00, 27.00, '2025-09-13 16:22:44', 27.00, 24.11, 2.89, '', '', 'Walk-in', '2025-09-13', 1, 1, NULL),
(33, '20250913162204', 44.64, 0, 0.00, 50.00, '2025-09-13 16:23:03', 50.00, 44.64, 5.36, '', '', 'Walk-in', '2025-09-13', 1, 37, NULL),
(34, '20250913162204', 24.11, 0, 0.00, 27.00, '2025-09-13 16:23:57', 27.00, 24.11, 2.89, '', '', 'Walk-in', '2025-09-13', 1, 37, NULL),
(35, '20250913161627', 24.11, 0, 0.00, 27.00, '2025-09-13 16:23:57', 27.00, 24.11, 2.89, '', '', 'Walk-in', '2025-09-13', 1, 1, NULL),
(36, '20250913161627', 24.11, 0, 0.00, 27.00, '2025-09-13 16:24:00', 27.00, 24.11, 2.89, '', '', 'Walk-in', '2025-09-13', 1, 1, NULL),
(45, '20250913163241', 0.89, 0, 0.00, 1.00, '2025-09-13 16:33:34', 1.00, 0.89, 0.11, '', '', 'Walk-in', '2025-09-13', 1, 37, NULL),
(46, '20250913191342', 22.32, 0, 0.00, 25.00, '2025-09-13 19:17:50', 25.00, 22.32, 2.68, '', '', 'Walk-in', '2025-09-13', 1, 37, NULL),
(70, '2025-11-061', 267.86, 0, 0.00, 300.00, '2025-11-06 00:00:00', 300.00, 267.86, 32.14, '', '', 'Walk-in', '2025-11-06', 1, 47, NULL),
(71, '20251106223321', 187.50, 0, 0.00, 210.00, '2025-11-06 00:00:00', 210.00, 187.50, 22.50, '', '', 'Walk-in', '2025-11-06', 1, 47, NULL),
(72, '2025-11-063', 40.18, 0, 0.00, 45.00, '2025-11-06 00:00:00', 45.00, 40.18, 4.82, '', '', 'Walk-in', '2025-11-06', 1, 47, NULL),
(73, '20251106223656', 178.57, 0, 0.00, 200.00, '2025-11-06 00:00:00', 200.00, 178.57, 21.43, '', '', 'Walk-in', '2025-11-06', 1, 47, 47),
(74, '20251106224956', 13.39, 0, 0.00, 15.00, '2025-11-06 00:00:00', 15.00, 13.39, 1.61, '', '', 'Walk-in', '2025-11-06', 1, 37, 37),
(75, '20251106225219', 35.71, 0, 0.00, 40.00, '2025-11-06 00:00:00', 40.00, 35.71, 4.29, '', '', 'Walk-in', '2025-11-06', 1, 37, 37),
(76, '20251106225425', 133.93, 0, 0.00, 150.00, '2025-11-07 00:00:00', 150.00, 133.93, 16.07, '', '', 'Walk-in', '2025-11-07', 1, 37, 37),
(77, '20251107221207', 35.71, 0, 0.00, 40.00, '2025-11-07 00:00:00', 40.00, 35.71, 4.29, '', '', 'Walk-in', '2025-11-07', 1, 47, 47);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchases`
--

DROP TABLE IF EXISTS `tbl_purchases`;
CREATE TABLE IF NOT EXISTS `tbl_purchases` (
  `id` int NOT NULL AUTO_INCREMENT,
  `studentID` int NOT NULL,
  `menuID` int NOT NULL,
  `invoiceNo` text NOT NULL,
  `xprice` double(20,2) NOT NULL,
  `xqty` int NOT NULL,
  `xdiscount` double NOT NULL,
  `xtotal` double NOT NULL,
  `xfinished` int NOT NULL,
  `dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_purchases`
--

INSERT INTO `tbl_purchases` (`id`, `studentID`, `menuID`, `invoiceNo`, `xprice`, `xqty`, `xdiscount`, `xtotal`, `xfinished`, `dt`) VALUES
(1, 16, 15, '202506120151', 25.00, 1, 0, 25, 1, '2025-06-11 17:52:17'),
(3, 16, 15, '20250612020113', 25.00, 1, 0, 25, 1, '2025-06-11 18:41:31'),
(4, 16, 15, '20250612024140', 25.00, 4, 0, 100, 1, '2025-06-11 19:41:05'),
(5, 16, 16, '20250612024140', 1.00, 6, 0, 6, 1, '2025-06-11 19:41:05'),
(6, 17, 16, '20250612103539', 1.00, 3, 0, 3, 1, '2025-06-12 02:37:24'),
(7, 17, 15, '20250612103539', 25.00, 1, 0, 25, 1, '2025-06-12 02:37:24'),
(8, 16, 16, '20250720202351', 1.00, 2, 0, 2, 1, '2025-07-20 12:32:16'),
(9, 16, 15, '20250720202351', 25.00, 2, 0, 50, 1, '2025-07-20 12:32:16'),
(10, 16, 16, '20250723013918', 1.00, 4, 0, 4, 1, '2025-07-22 17:44:38'),
(11, 16, 15, '20250723014458', 25.00, 2, 0, 50, 1, '2025-07-22 17:47:11'),
(12, 16, 15, '20250826171335', 25.00, 4, 0, 100, 1, '2025-08-26 09:18:36'),
(13, 16, 15, '20250826171911', 25.00, 5, 0, 125, 1, '2025-08-26 09:19:42'),
(14, 16, 16, '20250913085045', 1.00, 3, 0, 3, 1, '2025-09-13 00:56:06'),
(15, 16, 15, '20250913085045', 25.00, 2, 0, 50, 1, '2025-09-13 00:56:06'),
(16, 16, 16, '20250913085624', 1.00, 2, 0, 2, 1, '2025-09-13 01:05:57'),
(17, 16, 16, '20250913090558', 1.00, 1, 0, 1, 1, '2025-09-13 01:10:06'),
(18, 16, 16, '20250913091007', 1.00, 1, 0, 1, 1, '2025-09-13 01:10:29'),
(19, 16, 16, '20250913091033', 1.00, 1, 0, 1, 1, '2025-09-13 01:10:54'),
(20, 16, 16, '20250913091055', 1.00, 1, 0, 1, 1, '2025-09-13 01:11:05'),
(53, 25, 16, '20250913162204', 1.00, 2, 0, 2, 1, '2025-09-13 08:30:32'),
(54, 25, 15, '20250913162204', 25.00, 2, 0, 50, 1, '2025-09-13 08:30:32'),
(55, 25, 16, '20250913163241', 1.00, 1, 0, 1, 1, '2025-09-13 08:33:34'),
(56, 25, 15, '20250913191342', 25.00, 1, 0, 25, 1, '2025-09-13 11:17:50'),
(57, 25, 23, '2025-09-141', 20.00, 1, 0, 20, 1, '2025-09-14 05:08:13'),
(58, 25, 23, '20250914130815', 20.00, 4, 0, 80, 1, '2025-09-14 05:11:12'),
(59, 25, 20, '20250914130815', 15.00, 1, 0, 15, 1, '2025-09-14 05:11:12'),
(60, 18, 15, '2025-09-161', 25.00, 1, 0, 25, 1, '2025-09-16 16:46:46'),
(61, 21, 23, '20250916164835', 20.00, 1, 0, 20, 1, '2025-09-16 16:53:26'),
(62, 19, 23, '20250916165327', 20.00, 2, 0, 40, 1, '2025-09-16 17:15:54'),
(63, 17, 23, '20250916171556', 20.00, 2, 0, 40, 1, '2025-09-16 17:24:10'),
(64, 18, 16, '20250916172411', 1.00, 4, 0, 4, 1, '2025-09-16 17:25:39'),
(65, 18, 16, '20250916172543', 1.00, 1, 0, 1, 1, '2025-09-16 17:26:28'),
(66, 18, 16, '20250916172746', 1.00, 1, 0, 1, 1, '2025-09-16 17:28:34'),
(67, 18, 16, '20250916172837', 1.00, 1, 0, 1, 1, '2025-09-16 17:32:16'),
(68, 18, 16, '20250916173217', 1.00, 1, 0, 1, 1, '2025-09-16 17:33:05'),
(69, 18, 16, '20250916173307', 1.00, 1, 0, 1, 1, '2025-09-16 17:36:41'),
(70, 18, 15, '20250916173643', 25.00, 1, 0, 25, 1, '2025-09-16 17:37:30'),
(71, 18, 16, '20250916173734', 1.00, 1, 0, 1, 1, '2025-09-16 17:38:56'),
(72, 27, 16, '20250916192413', 1.00, 4, 0, 4, 1, '2025-09-16 19:24:46'),
(73, 30, 15, '2025-09-1615', 25.00, 2, 0, 50, 1, '2025-09-16 21:36:29'),
(74, 30, 24, '20250916213630', 15.00, 2, 0, 30, 1, '2025-09-16 21:38:16'),
(75, 30, 25, '20250916213819', 10.00, 2, 0, 20, 1, '2025-09-16 21:48:17'),
(76, 30, 25, '20250916214819', 10.00, 2, 0, 20, 1, '2025-09-16 21:53:14'),
(77, 30, 25, '20250916215316', 10.00, 1, 0, 10, 1, '2025-09-16 21:57:01'),
(78, 30, 25, '20250916215704', 10.00, 2, 0, 20, 1, '2025-09-16 22:05:23'),
(79, 30, 15, '20250916215704', 25.00, 4, 0, 100, 1, '2025-09-16 22:05:23'),
(80, 29, 25, '20250917125522', 10.00, 1, 0, 10, 1, '2025-09-17 13:01:25'),
(81, 31, 22, '2025-11-061', 20.00, 15, 0, 300, 1, '2025-11-06 22:26:41'),
(82, 31, 20, '20251106223321', 15.00, 14, 0, 210, 1, '2025-11-06 22:34:42'),
(83, 31, 18, '2025-11-063', 45.00, 1, 0, 45, 1, '2025-11-06 22:36:54'),
(84, 31, 22, '20251106223656', 20.00, 10, 0, 200, 1, '2025-11-06 22:49:54'),
(85, 31, 20, '20251106224956', 15.00, 1, 0, 15, 1, '2025-11-06 22:52:18'),
(86, 28, 22, '20251106225219', 20.00, 2, 0, 40, 1, '2025-11-06 22:54:23'),
(87, 28, 21, '20251106225425', 15.00, 10, 0, 150, 1, '2025-11-07 22:12:05'),
(88, 28, 22, '20251107221207', 20.00, 2, 0, 40, 1, '2025-11-07 22:13:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qrcode`
--

DROP TABLE IF EXISTS `tbl_qrcode`;
CREATE TABLE IF NOT EXISTS `tbl_qrcode` (
  `id` int NOT NULL AUTO_INCREMENT,
  `qr_image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_qrcode`
--

INSERT INTO `tbl_qrcode` (`id`, `qr_image`, `uploaded_at`) VALUES
(1, 'qr_1758088865.jpg', '2025-09-17 05:41:37');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings_constants`
--

DROP TABLE IF EXISTS `tbl_settings_constants`;
CREATE TABLE IF NOT EXISTS `tbl_settings_constants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `value` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sub_value` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_settings_constants`
--

INSERT INTO `tbl_settings_constants` (`id`, `category`, `value`, `sub_value`, `notes`, `description`) VALUES
(6, 'App Status', 'Pending', NULL, NULL, ''),
(7, 'App Status', 'Approved', NULL, NULL, ''),
(8, 'App Status', 'Completed', NULL, NULL, ''),
(9, 'App Status', 'Cancelled', NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

DROP TABLE IF EXISTS `tbl_students`;
CREATE TABLE IF NOT EXISTS `tbl_students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `studentNo` text NOT NULL,
  `rfidno` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `bday` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `xagree` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `pic` text NOT NULL,
  `balance_amount` double NOT NULL,
  `per_day_balance` double(15,2) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`id`, `studentNo`, `rfidno`, `fname`, `lname`, `mname`, `bday`, `age`, `gender`, `address`, `email`, `contact`, `xagree`, `username`, `password`, `pic`, `balance_amount`, `per_day_balance`, `status`, `created_at`) VALUES
(28, '54321', '0042915249', 'Mary ', 'Cabrera', NULL, '2004-04-19', '21', 'Female', 'Taytay', 'marygiselle@gmail.com', '09684524511', 0, 'mary', 'Mary@1', 'mary.jpg', 3510, 500.00, 'Active', '2025-09-16 12:11:00'),
(31, '11111', '121212', 'Test', 'Test', NULL, '2003-07-09', '21', 'Male', 'test', 'test@gmail.com', '23232323', 0, '11111', 'Test@123', 'Screenshot 2025-11-03 210354.png', 785, 1000.00, 'Active', '2025-11-03 14:18:18'),
(33, '232323', '232323', 'Test', 'Test', NULL, '2003-07-09', '21', 'Male', 'test', 'test@gmail.com', '23232323', 0, '232323', 'Test@123', 'Screenshot 2025-10-13 200542.png', 100, 100.00, 'Active', '2025-11-06 13:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

DROP TABLE IF EXISTS `tbl_transactions`;
CREATE TABLE IF NOT EXISTS `tbl_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transNo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `studentID` int NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `trans_amount` double NOT NULL,
  `new_balance` double(15,2) NOT NULL,
  `processed_by` int DEFAULT NULL,
  `trans_img` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `trans_stat` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Done',
  `decline_reason` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_transactions`
--

INSERT INTO `tbl_transactions` (`id`, `transNo`, `studentID`, `category`, `trans_amount`, `new_balance`, `processed_by`, `trans_img`, `trans_stat`, `decline_reason`, `created_at`) VALUES
(11, '20250612173049', 16, 'IN', 1, 820.00, NULL, '', 'Done', NULL, '2025-06-12 09:30:49'),
(17, '20250716191302', 21, 'IN', 1500, 1500.00, NULL, 'IMG_0452.jpeg', 'Approved', NULL, '2025-07-16 11:13:02'),
(18, '20250717131432', 22, 'IN', 100, 100.00, NULL, 'Copilot_20250630_180443.png', 'Approved', NULL, '2025-07-17 05:14:32'),
(65, '20250916192334', 27, 'IN', 200, 700.00, NULL, 'samplepic.jpg', 'Approved', NULL, '2025-09-16 19:23:34'),
(67, '20250916192724', 27, 'IN', 4, 0.00, NULL, 'samplepic.jpg', 'Declined', NULL, '2025-09-16 19:27:24'),
(68, '20250916203614', 27, 'IN', 100, 796.00, NULL, '', 'Done', NULL, '2025-09-16 20:36:14'),
(73, '2025-09-1615', 30, 'OUT', 50, 200.00, NULL, '', 'Done', NULL, '2025-09-16 21:36:29'),
(74, '20250916213630', 30, 'OUT', 30, 170.00, NULL, '', 'Done', NULL, '2025-09-16 21:38:16'),
(83, '20250918194848', 30, 'IN', 1, 101.00, NULL, '', 'Done', NULL, '2025-09-18 19:48:48'),
(84, '20250918202536', 30, 'IN', 1, 0.00, NULL, '63a771c0-80b7-4795-aee4-73dbec1153b1.jfif', 'Declined', NULL, '2025-09-18 20:25:36'),
(85, '20250930111905', 28, 'IN', 150, 250.00, NULL, '2826800f-f75e-4b7f-8f69-a9f38deb081a.jfif', 'Approved', NULL, '2025-09-30 03:19:05'),
(86, '20251106194413', 28, 'IN', 100, 0.00, NULL, 'Screenshot 2025-10-13 211744.png', 'Declined', 'sample decline\r\n', '2025-11-06 19:44:13'),
(87, '20251106213332', 28, 'IN', 500, 750.00, NULL, 'Screenshot 2025-10-11 181627.png', 'Approved', NULL, '2025-11-06 21:33:32'),
(88, '20251106213457', 28, 'IN', 233, 0.00, NULL, 'Screenshot 2025-10-13 203741.png', 'Declined', 'testing reason\r\n', '2025-11-06 21:34:57'),
(89, '20251106221548', 33, 'IN', 200, 0.00, NULL, 'Screenshot 2025-10-13 214302.png', 'Declined', 'test', '2025-11-06 22:15:48'),
(90, '20251106221635', 33, 'IN', 100, 100.00, NULL, '', 'Done', NULL, '2025-11-06 22:16:35'),
(91, '20251106221641', 31, 'IN', 300, 300.00, NULL, '', 'Done', NULL, '2025-11-06 22:16:41'),
(92, '2025-11-061', 31, 'OUT', 300, 0.00, NULL, '', 'Done', NULL, '2025-11-06 22:26:41'),
(93, '20251106223321', 31, 'OUT', 210, 790.00, NULL, '', 'Done', NULL, '2025-11-06 22:34:42'),
(94, '2025-11-063', 31, 'OUT', 45, 745.00, NULL, '', 'Done', NULL, '2025-11-06 22:36:54'),
(95, '20251106223656', 31, 'OUT', 200, 800.00, 47, '', 'Done', NULL, '2025-11-06 22:49:54'),
(96, '20251106224956', 31, 'OUT', 15, 785.00, 37, '', 'Done', NULL, '2025-11-06 22:52:18'),
(97, '20251106225219', 28, 'OUT', 40, 710.00, 37, '', 'Done', NULL, '2025-11-06 22:54:23'),
(98, '20251107215353', 31, 'IN', 100, 0.00, NULL, 'Screenshot 2025-11-06 221135.png', 'Declined', 'deds', '2025-11-07 21:53:53'),
(99, '20251107220807', 28, 'IN', 2000, 0.00, NULL, 'Screenshot 2025-11-06 221135.png', 'Declined', 'testing decline message reason here...', '2025-11-07 22:08:07'),
(100, '20251107220901', 28, 'IN', 2990, 3700.00, NULL, 'Screenshot 2025-11-06 221135.png', 'Approved', NULL, '2025-11-07 22:09:01'),
(101, '20251106225425', 28, 'OUT', 150, 3550.00, 37, '', 'Done', NULL, '2025-11-07 22:12:05'),
(102, '20251107221207', 28, 'OUT', 40, 3510.00, 47, '', 'Done', NULL, '2025-11-07 22:13:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ualt`
--

DROP TABLE IF EXISTS `tbl_ualt`;
CREATE TABLE IF NOT EXISTS `tbl_ualt` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'User',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=658 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_ualt`
--

INSERT INTO `tbl_ualt` (`id`, `userID`, `type`, `dt`, `action`) VALUES
(1, 14, 'Admin', '2025-04-01 12:03:34', 'Logged-out.'),
(2, 14, 'Admin', '2025-04-01 12:03:36', 'Logged-in.'),
(3, 14, 'Admin', '2025-04-01 12:12:48', 'Logged-out.'),
(4, 14, 'Admin', '2025-04-01 12:21:51', 'Logged-in.'),
(5, 14, 'Admin', '2025-04-01 12:47:02', 'Logged-in.'),
(6, 14, 'Admin', '2025-04-02 16:05:50', 'Logged-out.'),
(7, 14, 'Admin', '2025-04-02 16:05:53', 'Logged-in.'),
(8, 14, 'Admin', '2025-04-02 16:17:21', 'Logged-in.'),
(9, 14, 'Admin', '2025-04-01 10:25:59', 'Logged-in.'),
(10, 14, 'Admin', '2025-04-01 10:26:23', 'Logged-in.'),
(11, 14, 'Admin', '2025-04-01 10:28:46', 'Logged-out.'),
(12, 14, 'Admin', '2025-04-01 10:28:49', 'Logged-in.'),
(13, 14, 'Admin', '2025-04-01 10:29:12', 'Logged-out.'),
(14, 14, 'Admin', '2025-04-01 10:29:30', 'Logged-in.'),
(15, 14, 'Admin', '2025-04-01 10:29:41', 'Logged-out.'),
(16, 14, 'Admin', '2025-04-01 10:29:44', 'Logged-in.'),
(17, 14, 'Admin', '2025-04-01 22:30:22', 'Logged-out.'),
(18, 14, 'Admin', '2025-04-01 22:34:35', 'Logged-out.'),
(19, 14, 'Admin', '2025-04-01 22:34:40', 'Logged-in.'),
(20, 14, 'Admin', '2025-04-01 22:38:01', 'Logged-out.'),
(21, 14, 'Admin', '2025-04-01 22:38:08', 'Logged-in.'),
(22, 14, 'Admin', '2025-04-01 22:38:25', 'Logged-out.'),
(23, 14, 'Admin', '2025-04-01 22:38:53', 'Logged-in.'),
(24, 14, 'Admin', '2025-04-01 22:42:47', 'Logged-out.'),
(25, 14, 'Admin', '2025-04-01 22:43:16', 'Logged-in.'),
(26, 14, 'Admin', '2025-04-01 16:14:37', 'Logged-in.'),
(27, 14, 'Admin', '2025-04-01 16:22:51', 'Logged-out.'),
(28, 14, 'Admin', '2025-04-01 16:23:11', 'Logged-in.'),
(29, 14, 'Admin', '2025-04-01 16:34:54', 'Logged-out.'),
(30, 14, 'Admin', '2025-04-01 16:35:42', 'Logged-in.'),
(31, 14, 'Admin', '2025-04-01 16:36:47', 'Logged-out.'),
(32, 14, 'Admin', '2025-04-01 16:39:49', 'Logged-in.'),
(33, 14, 'Admin', '2025-04-02 09:08:42', 'Logged-in.'),
(34, 14, 'Admin', '2025-04-02 09:52:20', 'Logged-out.'),
(35, 14, 'Admin', '2025-04-02 09:52:25', 'Logged-in.'),
(36, 14, 'Admin', '2025-04-02 11:29:17', 'Logged-in.'),
(37, 14, 'Admin', '2025-04-02 11:48:48', 'Logged-out.'),
(38, 14, 'Admin', '2025-04-02 11:48:58', 'Logged-in.'),
(39, 14, 'Admin', '2025-04-02 11:50:26', 'Logged-out.'),
(40, 14, 'Admin', '2025-04-02 11:50:36', 'Logged-in.'),
(41, 14, 'Admin', '2025-04-02 11:51:09', 'Logged-out.'),
(42, 14, 'Admin', '2025-04-02 11:53:49', 'Logged-in.'),
(43, 14, 'Admin', '2025-04-02 11:55:06', 'Logged-out.'),
(44, 14, 'Admin', '2025-04-02 11:58:09', 'Logged-in.'),
(45, 14, 'Admin', '2025-04-02 13:48:49', 'Logged-in.'),
(46, 14, 'Admin', '2025-04-04 10:29:31', 'Logged-in.'),
(47, 14, 'Admin', '2025-04-04 10:30:25', 'Logged-out.'),
(48, 14, 'Admin', '2025-04-05 14:07:06', 'Logged-in.'),
(49, 14, 'Admin', '2025-04-05 14:08:44', 'Logged-out.'),
(50, 34, 'Admin', '2025-04-05 14:08:52', 'Logged-in.'),
(51, 34, 'Admin', '2025-04-05 14:10:14', 'Logged-out.'),
(52, 14, 'Admin', '2025-04-05 14:10:36', 'Logged-in.'),
(53, 14, 'Admin', '2025-04-05 14:11:56', 'Logged-out.'),
(54, 34, 'Admin', '2025-04-05 14:12:10', 'Logged-in.'),
(55, 34, 'Admin', '2025-04-05 14:13:08', 'Logged-out.'),
(56, 34, 'Admin', '2025-04-05 14:13:37', 'Logged-in.'),
(57, 34, 'Admin', '2025-04-05 14:15:03', 'Logged-out.'),
(58, 35, 'Admin', '2025-04-05 14:22:30', 'Logged-in.'),
(59, 35, 'Admin', '2025-04-05 14:23:09', 'Logged-in.'),
(60, 0, 'Admin', '2025-04-05 14:39:06', 'Logged-out.'),
(61, 35, 'Admin', '2025-04-05 14:39:18', 'Logged-in.'),
(62, 35, 'Admin', '2025-04-07 07:44:39', 'Logged-in.'),
(63, 35, 'Admin', '2025-04-07 14:51:57', 'Logged-in.'),
(64, 35, 'Admin', '2025-04-08 06:14:53', 'Logged-in.'),
(65, 35, 'Admin', '2025-04-08 06:21:53', 'Logged-out.'),
(66, 34, 'Admin', '2025-05-08 12:13:45', 'Logged-in.'),
(67, 34, 'Admin', '2025-05-08 12:16:33', 'Logged-out.'),
(68, 3, 'Admin', '2025-05-08 14:44:03', 'Logged-in.'),
(69, 36, 'Admin', '2025-05-08 14:48:15', 'Logged-in.'),
(70, 36, 'Admin', '2025-05-08 14:52:29', 'Logged-out.'),
(71, 37, 'Admin', '2025-05-08 14:54:09', 'Logged-in.'),
(72, 37, 'Admin', '2025-05-08 14:56:34', 'Logged-out.'),
(73, 38, 'Admin', '2025-05-08 14:56:47', 'Logged-in.'),
(74, 38, 'Admin', '2025-05-08 14:56:53', 'Logged-out.'),
(75, 37, 'Admin', '2025-05-08 14:57:04', 'Logged-in.'),
(76, 37, 'Admin', '2025-05-08 14:57:36', 'Logged-out.'),
(77, 38, 'Admin', '2025-05-08 14:57:44', 'Logged-in.'),
(78, 38, 'Admin', '2025-05-08 15:09:28', 'Logged-out.'),
(79, 37, 'Admin', '2025-05-08 15:09:42', 'Logged-in.'),
(80, 37, 'Admin', '2025-05-08 15:11:11', 'Logged-out.'),
(81, 39, 'Admin', '2025-05-08 15:11:20', 'Logged-in.'),
(82, 39, 'Admin', '2025-05-08 15:11:38', 'Logged-out.'),
(83, 34, 'Admin', '2025-05-08 15:12:04', 'Logged-in.'),
(84, 34, 'Admin', '2025-05-08 15:12:51', 'Logged-out.'),
(85, 34, 'Admin', '2025-05-08 15:13:06', 'Logged-in.'),
(86, 34, 'Admin', '2025-05-08 15:13:13', 'Logged-out.'),
(87, 37, 'Admin', '2025-05-08 15:13:25', 'Logged-in.'),
(88, 37, 'Admin', '2025-05-08 15:22:49', 'Logged-out.'),
(89, 40, 'Admin', '2025-05-08 15:22:57', 'Logged-in.'),
(90, 40, 'Admin', '2025-05-08 15:23:02', 'Logged-out.'),
(91, 37, 'Admin', '2025-05-08 15:23:11', 'Logged-in.'),
(92, 37, 'Admin', '2025-05-08 15:34:44', 'Logged-out.'),
(93, 42, 'Admin', '2025-05-08 15:35:17', 'Logged-in.'),
(94, 42, 'Admin', '2025-05-08 15:35:24', 'Logged-out.'),
(95, 37, 'Admin', '2025-05-08 15:35:40', 'Logged-in.'),
(96, 37, 'Admin', '2025-05-08 15:45:41', 'Logged-out.'),
(97, 42, 'Admin', '2025-05-08 15:45:53', 'Logged-in.'),
(98, 42, 'Admin', '2025-05-08 15:45:57', 'Logged-out.'),
(99, 37, 'Admin', '2025-05-08 15:46:09', 'Logged-in.'),
(100, 37, 'Admin', '2025-05-08 16:01:24', 'Logged-out.'),
(101, 46, 'Admin', '2025-05-08 16:01:32', 'Logged-in.'),
(102, 46, 'Admin', '2025-05-08 16:01:41', 'Logged-out.'),
(103, 37, 'Admin', '2025-05-08 16:01:48', 'Logged-in.'),
(104, 37, 'Admin', '2025-05-15 11:42:16', 'Logged-in.'),
(105, 37, 'Admin', '2025-05-15 11:49:16', 'Logged-out.'),
(106, 41, 'Admin', '2025-05-15 11:49:28', 'Logged-in.'),
(107, 41, 'Admin', '2025-05-15 11:51:12', 'Logged-out.'),
(108, 37, 'Admin', '2025-05-15 11:51:25', 'Logged-in.'),
(109, 37, 'Admin', '2025-05-15 11:57:39', 'Logged-out.'),
(110, 41, 'Admin', '2025-05-15 11:57:54', 'Logged-in.'),
(111, 41, 'Admin', '2025-05-15 11:58:34', 'Logged-out.'),
(112, 16, 'Parent', '2025-06-11 12:38:33', 'Logged-in.'),
(113, 16, '', '2025-06-11 13:18:49', 'Logged-out.'),
(114, 16, 'Parent', '2025-06-11 13:19:25', 'Logged-in.'),
(115, 16, '', '2025-06-11 13:20:04', 'Logged-out.'),
(116, 16, 'Parent', '2025-06-11 13:20:36', 'Logged-in.'),
(117, 37, 'Admin', '2025-06-11 23:44:02', 'Logged-in.'),
(118, 16, 'Parent', '2025-06-11 23:47:03', 'Logged-in.'),
(119, 14, '', '2025-06-12 01:41:46', 'Logged-out.'),
(120, 37, 'Admin', '2025-06-12 01:42:01', 'Logged-in.'),
(121, 37, '', '2025-06-12 04:02:05', 'Logged-out.'),
(122, 37, 'Admin', '2025-06-12 04:02:13', 'Logged-in.'),
(123, 0, '', '2025-06-12 10:05:32', 'Logged-out.'),
(124, 37, 'Admin', '2025-06-12 10:05:45', 'Logged-in.'),
(125, 37, 'Admin', '2025-06-23 10:02:28', 'Logged-in.'),
(126, 0, '', '2025-06-23 10:06:42', 'Logged-out.'),
(127, 19, 'Parent', '2025-06-23 10:08:44', 'Logged-in.'),
(128, 19, '', '2025-06-23 10:09:50', 'Logged-out.'),
(129, 37, 'Admin', '2025-06-23 10:11:32', 'Logged-in.'),
(130, 0, '', '2025-06-23 15:23:58', 'Logged-out.'),
(131, 37, 'Admin', '2025-06-23 15:24:28', 'Logged-in.'),
(132, 37, '', '2025-06-23 15:24:54', 'Logged-out.'),
(133, 19, 'Parent', '2025-06-23 15:25:40', 'Logged-in.'),
(134, 19, '', '2025-06-23 15:26:48', 'Logged-out.'),
(135, 37, 'Admin', '2025-06-23 15:26:57', 'Logged-in.'),
(136, 37, 'Admin', '2025-06-24 15:26:49', 'Logged-in.'),
(137, 37, '', '2025-06-24 15:31:02', 'Logged-out.'),
(138, 37, 'Admin', '2025-06-24 15:31:51', 'Logged-in.'),
(139, 19, 'Parent', '2025-06-24 15:32:14', 'Logged-in.'),
(140, 19, '', '2025-06-24 15:35:29', 'Logged-out.'),
(141, 37, 'Admin', '2025-06-24 15:35:41', 'Logged-in.'),
(142, 37, '', '2025-06-24 15:44:54', 'Logged-out.'),
(143, 41, 'Staff', '2025-06-24 15:45:05', 'Logged-in.'),
(144, 41, '', '2025-06-24 15:45:52', 'Logged-out.'),
(145, 37, 'Admin', '2025-06-24 15:46:00', 'Logged-in.'),
(146, 19, 'Parent', '2025-06-24 15:55:33', 'Logged-in.'),
(147, 37, '', '2025-06-24 16:09:33', 'Logged-out.'),
(148, 37, 'Admin', '2025-06-26 13:01:27', 'Logged-in.'),
(149, 37, '', '2025-06-26 13:03:45', 'Logged-out.'),
(150, 37, 'Admin', '2025-06-26 13:05:21', 'Logged-in.'),
(151, 0, '', '2025-06-27 13:26:10', 'Logged-out.'),
(152, 37, 'Admin', '2025-06-27 13:26:26', 'Logged-in.'),
(153, 37, '', '2025-06-27 13:26:48', 'Logged-out.'),
(154, 0, '', '2025-06-27 17:02:56', 'Logged-out.'),
(155, 0, '', '2025-06-29 19:21:33', 'Logged-out.'),
(156, 37, 'Admin', '2025-06-29 19:21:45', 'Logged-in.'),
(157, 37, '', '2025-06-29 19:22:10', 'Logged-out.'),
(158, 37, 'Admin', '2025-07-14 09:27:31', 'Logged-in.'),
(159, 37, '', '2025-07-14 09:32:46', 'Logged-out.'),
(160, 16, 'Parent', '2025-07-14 09:33:49', 'Logged-in.'),
(161, 37, 'Admin', '2025-07-14 19:32:01', 'Logged-in.'),
(162, 37, 'Admin', '2025-07-16 19:04:44', 'Logged-in.'),
(163, 21, 'Parent', '2025-07-16 19:12:04', 'Logged-in.'),
(164, 37, '', '2025-07-16 19:19:15', 'Logged-out.'),
(165, 0, '', '2025-07-17 12:33:44', 'Logged-out.'),
(166, 37, 'Admin', '2025-07-17 12:35:15', 'Logged-in.'),
(167, 16, 'Parent', '2025-07-17 12:38:58', 'Logged-in.'),
(168, 16, '', '2025-07-17 12:39:35', 'Logged-out.'),
(169, 37, '', '2025-07-17 12:43:28', 'Logged-out.'),
(170, 47, 'Admin', '2025-07-17 12:43:47', 'Logged-in.'),
(171, 47, '', '2025-07-17 12:44:37', 'Logged-out.'),
(172, 47, 'Staff', '2025-07-17 12:44:47', 'Logged-in.'),
(173, 47, '', '2025-07-17 12:45:41', 'Logged-out.'),
(174, 37, 'Admin', '2025-07-17 12:48:20', 'Logged-in.'),
(175, 37, 'Admin', '2025-07-17 12:48:20', 'Logged-in.'),
(176, 37, '', '2025-07-17 12:49:11', 'Logged-out.'),
(177, 37, 'Admin', '2025-07-17 13:10:38', 'Logged-in.'),
(178, 22, 'Parent', '2025-07-17 13:13:44', 'Logged-in.'),
(179, 37, 'Admin', '2025-07-20 20:23:14', 'Logged-in.'),
(180, 22, 'Parent', '2025-07-20 20:24:52', 'Logged-in.'),
(181, 22, '', '2025-07-20 20:26:08', 'Logged-out.'),
(182, 37, 'Admin', '2025-07-20 20:26:30', 'Logged-in.'),
(183, 37, '', '2025-07-20 20:26:54', 'Logged-out.'),
(184, 47, 'Staff', '2025-07-20 20:27:18', 'Logged-in.'),
(185, 47, '', '2025-07-20 20:28:16', 'Logged-out.'),
(186, 37, 'Admin', '2025-07-20 20:28:25', 'Logged-in.'),
(187, 37, '', '2025-07-20 20:29:33', 'Logged-out.'),
(188, 16, 'Parent', '2025-07-20 20:30:46', 'Logged-in.'),
(189, 16, '', '2025-07-20 20:33:38', 'Logged-out.'),
(190, 37, 'Admin', '2025-07-20 20:33:49', 'Logged-in.'),
(191, 37, '', '2025-07-20 20:34:18', 'Logged-out.'),
(192, 16, 'Parent', '2025-07-20 20:34:52', 'Logged-in.'),
(193, 16, '', '2025-07-20 20:35:12', 'Logged-out.'),
(194, 47, 'Staff', '2025-07-21 13:47:36', 'Logged-in.'),
(195, 0, '', '2025-07-23 01:39:27', 'Logged-out.'),
(196, 16, 'Parent', '2025-07-23 01:40:30', 'Logged-in.'),
(197, 16, '', '2025-07-23 01:41:05', 'Logged-out.'),
(198, 37, 'Admin', '2025-07-23 01:42:55', 'Logged-in.'),
(199, 16, 'Parent', '2025-07-23 01:44:14', 'Logged-in.'),
(200, 16, '', '2025-07-23 01:48:18', 'Logged-out.'),
(201, 37, '', '2025-07-23 01:48:24', 'Logged-out.'),
(202, 37, 'Admin', '2025-07-23 14:09:48', 'Logged-in.'),
(203, 37, '', '2025-07-23 14:10:18', 'Logged-out.'),
(204, 37, 'Admin', '2025-08-04 19:50:32', 'Logged-in.'),
(205, 37, '', '2025-08-04 19:54:20', 'Logged-out.'),
(206, 37, 'Admin', '2025-08-04 19:54:35', 'Logged-in.'),
(207, 37, 'Admin', '2025-08-04 20:05:12', 'Logged-in.'),
(208, 37, '', '2025-08-04 20:06:44', 'Logged-out.'),
(209, 37, 'Admin', '2025-08-26 14:38:32', 'Logged-in.'),
(210, 37, '', '2025-08-26 14:41:40', 'Logged-out.'),
(211, 16, 'Parent', '2025-08-26 14:42:22', 'Logged-in.'),
(212, 16, '', '2025-08-26 14:43:13', 'Logged-out.'),
(213, 16, 'Parent', '2025-08-26 17:11:29', 'Logged-in.'),
(214, 37, 'Admin', '2025-08-26 17:12:30', 'Logged-in.'),
(215, 37, '', '2025-08-26 17:30:34', 'Logged-out.'),
(216, 16, 'Parent', '2025-08-26 17:31:17', 'Logged-in.'),
(217, 16, 'Parent', '2025-08-26 17:31:17', 'Logged-in.'),
(218, 16, '', '2025-08-26 17:32:33', 'Logged-out.'),
(219, 16, '', '2025-08-26 17:32:49', 'Logged-out.'),
(220, 37, 'Admin', '2025-09-01 17:22:54', 'Logged-in.'),
(221, 37, '', '2025-09-01 17:23:00', 'Logged-out.'),
(222, 16, 'Parent', '2025-09-01 17:26:52', 'Logged-in.'),
(223, 37, 'Admin', '2025-09-01 19:10:54', 'Logged-in.'),
(224, 37, '', '2025-09-01 19:12:01', 'Logged-out.'),
(225, 16, 'Parent', '2025-09-01 19:12:13', 'Logged-in.'),
(226, 37, 'Admin', '2025-09-01 19:13:05', 'Logged-in.'),
(227, 37, '', '2025-09-01 19:16:07', 'Logged-out.'),
(228, 0, '', '2025-09-01 19:16:11', 'Logged-out.'),
(229, 37, 'Admin', '2025-09-01 19:16:55', 'Logged-in.'),
(230, 37, 'Admin', '2025-09-01 19:18:21', 'Logged-in.'),
(231, 37, '', '2025-09-01 19:46:51', 'Logged-out.'),
(232, 47, 'Staff', '2025-09-01 19:47:09', 'Logged-in.'),
(233, 37, '', '2025-09-01 19:52:33', 'Logged-out.'),
(234, 47, 'Staff', '2025-09-01 19:52:47', 'Logged-in.'),
(235, 47, '', '2025-09-01 19:57:48', 'Logged-out.'),
(236, 37, 'Admin', '2025-09-01 19:57:55', 'Logged-in.'),
(237, 37, '', '2025-09-01 20:00:08', 'Logged-out.'),
(238, 37, 'Admin', '2025-09-01 20:12:46', 'Logged-in.'),
(239, 37, '', '2025-09-01 20:36:55', 'Logged-out.'),
(240, 48, 'Staff', '2025-09-01 20:37:00', 'Logged-in.'),
(241, 48, '', '2025-09-01 20:37:22', 'Logged-out.'),
(242, 37, 'Admin', '2025-09-01 20:37:35', 'Logged-in.'),
(243, 37, '', '2025-09-01 20:39:10', 'Logged-out.'),
(244, 37, 'Admin', '2025-09-01 20:40:31', 'Logged-in.'),
(245, 47, '', '2025-09-01 20:44:42', 'Logged-out.'),
(246, 37, '', '2025-09-01 20:46:52', 'Logged-out.'),
(247, 37, 'Admin', '2025-09-01 20:47:24', 'Logged-in.'),
(248, 37, '', '2025-09-01 20:47:36', 'Logged-out.'),
(249, 37, 'Admin', '2025-09-01 20:48:02', 'Logged-in.'),
(250, 37, '', '2025-09-01 20:49:24', 'Logged-out.'),
(251, 37, 'Admin', '2025-09-01 21:01:28', 'Logged-in.'),
(252, 37, '', '2025-09-01 21:02:41', 'Logged-out.'),
(253, 37, 'Admin', '2025-09-01 21:02:52', 'Logged-in.'),
(254, 37, '', '2025-09-01 21:24:48', 'Logged-out.'),
(255, 37, 'Admin', '2025-09-01 21:25:25', 'Logged-in.'),
(256, 37, '', '2025-09-01 21:26:57', 'Logged-out.'),
(257, 23, 'Parent', '2025-09-01 21:27:51', 'Logged-in.'),
(258, 23, '', '2025-09-01 21:28:09', 'Logged-out.'),
(259, 37, 'Admin', '2025-09-01 21:28:11', 'Logged-in.'),
(260, 37, '', '2025-09-01 21:28:55', 'Logged-out.'),
(261, 23, 'Parent', '2025-09-01 21:29:54', 'Logged-in.'),
(262, 37, 'Admin', '2025-09-04 20:51:09', 'Logged-in.'),
(263, 37, 'Admin', '2025-09-04 20:51:09', 'Logged-in.'),
(264, 37, '', '2025-09-04 21:43:52', 'Logged-out.'),
(265, 47, 'Staff', '2025-09-04 21:44:13', 'Logged-in.'),
(266, 47, '', '2025-09-04 21:46:00', 'Logged-out.'),
(267, 16, 'Parent', '2025-09-04 21:46:34', 'Logged-in.'),
(268, 37, 'Admin', '2025-09-04 23:22:53', 'Logged-in.'),
(269, 16, 'Parent', '2025-09-04 23:23:50', 'Logged-in.'),
(270, 16, '', '2025-09-04 23:24:45', 'Logged-out.'),
(271, 37, 'Admin', '2025-09-04 23:24:54', 'Logged-in.'),
(272, 37, '', '2025-09-04 23:26:59', 'Logged-out.'),
(273, 0, '', '2025-09-04 23:27:04', 'Logged-out.'),
(274, 37, 'Admin', '2025-09-06 20:10:10', 'Logged-in.'),
(275, 37, '', '2025-09-06 21:45:40', 'Logged-out.'),
(276, 16, 'Parent', '2025-09-06 21:45:56', 'Logged-in.'),
(277, 16, '', '2025-09-06 22:15:31', 'Logged-out.'),
(278, 37, 'Admin', '2025-09-06 22:15:40', 'Logged-in.'),
(279, 37, 'Admin', '2025-09-06 22:15:41', 'Logged-in.'),
(280, 37, '', '2025-09-06 22:29:18', 'Logged-out.'),
(281, 0, '', '2025-09-12 23:25:56', 'Logged-out.'),
(282, 16, 'Parent', '2025-09-12 23:26:48', 'Logged-in.'),
(283, 1, 'Admin', '2025-09-13 08:45:03', 'Logged-in.'),
(284, 1, '', '2025-09-13 08:47:25', 'Logged-out.'),
(285, 1, 'Admin', '2025-09-13 08:47:49', 'Logged-in.'),
(286, 1, '', '2025-09-13 08:47:56', 'Logged-out.'),
(287, 1, 'Admin', '2025-09-13 08:48:26', 'Logged-in.'),
(288, 1, '', '2025-09-13 08:50:10', 'Logged-out.'),
(289, 1, 'Admin', '2025-09-13 08:50:13', 'Logged-in.'),
(290, 1, '', '2025-09-13 09:12:35', 'Logged-out.'),
(291, 16, 'Parent', '2025-09-13 09:12:51', 'Logged-in.'),
(292, 37, 'Admin', '2025-09-13 12:47:03', 'Logged-in.'),
(293, 37, '', '2025-09-13 12:51:17', 'Logged-out.'),
(294, 0, '', '2025-09-13 12:51:32', 'Logged-out.'),
(295, 37, 'Admin', '2025-09-13 12:52:03', 'Logged-in.'),
(296, 37, '', '2025-09-13 12:52:08', 'Logged-out.'),
(297, 25, 'Parent', '2025-09-13 12:52:24', 'Logged-in.'),
(298, 25, '', '2025-09-13 12:53:07', 'Logged-out.'),
(299, 37, 'Admin', '2025-09-13 12:53:20', 'Logged-in.'),
(300, 37, '', '2025-09-13 12:55:31', 'Logged-out.'),
(301, 25, 'Parent', '2025-09-13 12:56:42', 'Logged-in.'),
(302, 25, '', '2025-09-13 12:57:03', 'Logged-out.'),
(303, 37, 'Admin', '2025-09-13 12:57:12', 'Logged-in.'),
(304, 37, '', '2025-09-13 13:00:09', 'Logged-out.'),
(305, 25, 'Parent', '2025-09-13 13:00:21', 'Logged-in.'),
(306, 25, '', '2025-09-13 13:00:38', 'Logged-out.'),
(307, 37, 'Admin', '2025-09-13 13:02:13', 'Logged-in.'),
(308, 0, '', '2025-09-13 15:55:18', 'Logged-out.'),
(309, 37, 'Admin', '2025-09-13 15:55:56', 'Logged-in.'),
(310, 16, '', '2025-09-13 16:01:11', 'Logged-out.'),
(311, 1, 'Admin', '2025-09-13 16:01:29', 'Logged-in.'),
(312, 37, 'Admin', '2025-09-13 16:06:04', 'Logged-in.'),
(313, 0, '', '2025-09-13 16:07:12', 'Logged-out.'),
(314, 25, 'Parent', '2025-09-13 16:07:50', 'Logged-in.'),
(315, 25, '', '2025-09-13 16:08:12', 'Logged-out.'),
(316, 37, 'Admin', '2025-09-13 16:08:47', 'Logged-in.'),
(317, 37, 'Admin', '2025-09-13 16:21:58', 'Logged-in.'),
(318, 37, '', '2025-09-13 16:27:58', 'Logged-out.'),
(319, 25, 'Parent', '2025-09-13 16:28:41', 'Logged-in.'),
(320, 25, '', '2025-09-13 16:29:00', 'Logged-out.'),
(321, 37, 'Admin', '2025-09-13 16:29:24', 'Logged-in.'),
(322, 37, 'Admin', '2025-09-13 16:33:01', 'Logged-in.'),
(323, 37, 'Admin', '2025-09-13 19:11:46', 'Logged-in.'),
(324, 37, '', '2025-09-13 19:19:27', 'Logged-out.'),
(325, 0, '', '2025-09-14 10:46:54', 'Logged-out.'),
(326, 37, 'Admin', '2025-09-14 10:47:29', 'Logged-in.'),
(327, 37, 'Admin', '2025-09-14 11:53:43', 'Logged-in.'),
(328, 37, '', '2025-09-14 12:20:00', 'Logged-out.'),
(329, 47, 'Staff', '2025-09-14 12:20:15', 'Logged-in.'),
(330, 47, '', '2025-09-14 12:20:41', 'Logged-out.'),
(331, 25, 'Parent', '2025-09-14 12:20:58', 'Logged-in.'),
(332, 25, '', '2025-09-14 12:22:05', 'Logged-out.'),
(333, 37, 'Admin', '2025-09-14 12:24:06', 'Logged-in.'),
(334, 37, '', '2025-09-14 12:27:35', 'Logged-out.'),
(335, 18, 'Parent', '2025-09-14 12:27:55', 'Logged-in.'),
(336, 18, '', '2025-09-14 12:28:27', 'Logged-out.'),
(337, 37, 'Admin', '2025-09-14 12:28:40', 'Logged-in.'),
(338, 37, 'Admin', '2025-09-14 12:28:41', 'Logged-in.'),
(339, 37, '', '2025-09-14 12:30:36', 'Logged-out.'),
(340, 37, 'Admin', '2025-09-14 12:32:16', 'Logged-in.'),
(341, 37, '', '2025-09-14 12:42:50', 'Logged-out.'),
(342, 37, 'Admin', '2025-09-14 12:44:25', 'Logged-in.'),
(343, 37, '', '2025-09-14 13:19:36', 'Logged-out.'),
(344, 37, 'Admin', '2025-09-14 13:20:28', 'Logged-in.'),
(345, 37, '', '2025-09-14 13:20:54', 'Logged-out.'),
(346, 47, 'Staff', '2025-09-14 13:21:46', 'Logged-in.'),
(347, 47, '', '2025-09-14 13:31:24', 'Logged-out.'),
(348, 25, 'Parent', '2025-09-14 13:32:18', 'Logged-in.'),
(349, 25, '', '2025-09-14 13:36:16', 'Logged-out.'),
(350, 37, 'Admin', '2025-09-14 13:36:33', 'Logged-in.'),
(351, 37, '', '2025-09-14 13:38:34', 'Logged-out.'),
(352, 47, 'Staff', '2025-09-14 13:38:51', 'Logged-in.'),
(353, 47, '', '2025-09-14 13:39:32', 'Logged-out.'),
(354, 37, 'Admin', '2025-09-14 15:28:18', 'Logged-in.'),
(355, 37, '', '2025-09-14 16:47:20', 'Logged-out.'),
(356, 37, 'Admin', '2025-09-14 18:52:37', 'Logged-in.'),
(357, 0, '', '2025-09-14 20:10:46', 'Logged-out.'),
(358, 37, 'Admin', '2025-09-14 20:13:20', 'Logged-in.'),
(359, 37, '', '2025-09-14 20:14:26', 'Logged-out.'),
(360, 25, 'Parent', '2025-09-14 20:14:47', 'Logged-in.'),
(361, 37, 'Admin', '2025-09-16 07:46:46', 'Logged-in.'),
(362, 37, '', '2025-09-16 07:47:39', 'Logged-out.'),
(363, 37, 'Admin', '2025-09-16 07:51:15', 'Logged-in.'),
(364, 37, '', '2025-09-16 07:54:19', 'Logged-out.'),
(365, 37, 'Admin', '2025-09-16 07:54:30', 'Logged-in.'),
(366, 37, '', '2025-09-16 08:15:53', 'Logged-out.'),
(367, 37, 'Admin', '2025-09-16 08:18:07', 'Logged-in.'),
(368, 37, '', '2025-09-16 08:38:15', 'Logged-out.'),
(369, 47, 'Staff', '2025-09-16 08:38:38', 'Logged-in.'),
(370, 47, '', '2025-09-16 08:39:10', 'Logged-out.'),
(371, 37, 'Admin', '2025-09-16 08:39:42', 'Logged-in.'),
(372, 37, '', '2025-09-16 08:47:49', 'Logged-out.'),
(373, 37, 'Admin', '2025-09-16 08:48:23', 'Logged-in.'),
(374, 37, '', '2025-09-16 09:58:49', 'Logged-out.'),
(375, 0, '', '2025-09-16 09:58:54', 'Logged-out.'),
(376, 37, 'Admin', '2025-09-16 09:59:25', 'Logged-in.'),
(377, 37, '', '2025-09-16 09:59:34', 'Logged-out.'),
(378, 37, 'Admin', '2025-09-16 10:00:02', 'Logged-in.'),
(379, 37, '', '2025-09-16 10:00:11', 'Logged-out.'),
(380, 37, 'Admin', '2025-09-16 10:01:53', 'Logged-in.'),
(381, 37, '', '2025-09-16 10:02:11', 'Logged-out.'),
(382, 37, 'Admin', '2025-09-16 10:02:21', 'Logged-in.'),
(383, 37, '', '2025-09-16 10:02:27', 'Logged-out.'),
(384, 37, 'Admin', '2025-09-16 10:02:37', 'Logged-in.'),
(385, 37, '', '2025-09-16 10:02:50', 'Logged-out.'),
(386, 47, 'Staff', '2025-09-16 10:03:34', 'Logged-in.'),
(387, 47, '', '2025-09-16 10:03:44', 'Logged-out.'),
(388, 37, 'Admin', '2025-09-16 10:06:35', 'Logged-in.'),
(389, 37, '', '2025-09-16 10:06:43', 'Logged-out.'),
(390, 37, 'Admin', '2025-09-16 10:07:55', 'Logged-in.'),
(391, 37, '', '2025-09-16 10:08:14', 'Logged-out.'),
(392, 47, 'Staff', '2025-09-16 10:11:03', 'Logged-in.'),
(393, 47, '', '2025-09-16 10:11:13', 'Logged-out.'),
(394, 47, 'Staff', '2025-09-16 10:13:51', 'Logged-in.'),
(395, 47, '', '2025-09-16 10:13:59', 'Logged-out.'),
(396, 37, 'Admin', '2025-09-16 10:14:14', 'Logged-in.'),
(397, 37, '', '2025-09-16 10:14:24', 'Logged-out.'),
(398, 37, 'Admin', '2025-09-16 10:14:44', 'Logged-in.'),
(399, 37, '', '2025-09-16 10:14:51', 'Logged-out.'),
(400, 37, 'Admin', '2025-09-16 10:15:26', 'Logged-in.'),
(401, 37, '', '2025-09-16 10:15:36', 'Logged-out.'),
(402, 37, 'Admin', '2025-09-16 10:18:32', 'Logged-in.'),
(403, 37, '', '2025-09-16 10:18:43', 'Logged-out.'),
(404, 37, 'Admin', '2025-09-16 10:23:02', 'Logged-in.'),
(405, 37, 'Admin', '2025-09-16 10:23:08', 'Logged-out.'),
(406, 37, 'Admin', '2025-09-16 10:23:18', 'Logged-in.'),
(407, 37, 'Admin', '2025-09-16 10:23:24', 'Logged-out.'),
(408, 47, 'Staff', '2025-09-16 10:23:49', 'Logged-in.'),
(409, 47, 'Staff', '2025-09-16 10:23:57', 'Logged-out.'),
(410, 47, 'Staff', '2025-09-16 10:25:02', 'Logged-in.'),
(411, 47, 'Staff', '2025-09-16 10:25:08', 'Logged-out.'),
(412, 37, 'Admin', '2025-09-16 10:25:22', 'Logged-in.'),
(413, 37, 'Admin', '2025-09-16 10:25:30', 'Logged-out.'),
(414, 37, 'Admin', '2025-09-16 10:26:07', 'Logged-in.'),
(415, 37, 'Admin', '2025-09-16 10:26:18', 'Logged-out.'),
(416, 25, 'Parent', '2025-09-16 10:26:37', 'Logged-in.'),
(417, 25, 'Parent', '2025-09-16 10:27:02', 'Logged-out.'),
(418, 25, 'Parent', '2025-09-16 10:28:28', 'Logged-in.'),
(419, 25, 'Parent', '2025-09-16 10:35:32', 'Logged-out.'),
(420, 25, 'Parent', '2025-09-16 10:35:48', 'Logged-in.'),
(421, 25, 'Parent', '2025-09-16 10:36:14', 'Logged-out.'),
(422, 25, 'Parent', '2025-09-16 10:36:39', 'Logged-in.'),
(423, 25, 'Parent', '2025-09-16 10:39:02', 'Logged-out.'),
(424, 25, 'Parent', '2025-09-16 10:39:19', 'Logged-in.'),
(425, 25, 'Parent', '2025-09-16 10:39:29', 'Logged-out.'),
(426, 25, 'Parent', '2025-09-16 10:39:44', 'Logged-in.'),
(427, 25, 'Parent', '2025-09-16 10:43:25', 'Logged-out.'),
(428, 25, 'Parent', '2025-09-16 10:47:19', 'Logged-in.'),
(429, 25, 'Parent', '2025-09-16 10:49:10', 'Logged-out.'),
(430, 37, 'Admin', '2025-09-16 10:49:43', 'Logged-in.'),
(431, 37, 'Admin', '2025-09-16 10:49:52', 'Logged-out.'),
(432, 25, 'Parent', '2025-09-16 10:51:15', 'Logged-in.'),
(433, 25, 'Parent', '2025-09-16 11:06:24', 'Logged-in.'),
(434, 25, 'Parent', '2025-09-16 11:16:29', 'Logged-out.'),
(435, 37, 'Admin', '2025-09-16 11:16:39', 'Logged-in.'),
(436, 18, 'Parent', '2025-09-16 11:19:10', 'Logged-in.'),
(437, 18, 'Parent', '2025-09-16 11:19:32', 'Logged-out.'),
(438, 18, 'Parent', '2025-09-16 11:19:46', 'Logged-in.'),
(439, 18, 'Parent', '2025-09-16 11:20:06', 'Logged-out.'),
(440, 37, 'Admin', '2025-09-16 11:20:20', 'Logged-in.'),
(441, 37, 'Admin', '2025-09-16 11:22:59', 'Logged-out.'),
(442, 27, 'Parent', '2025-09-16 11:23:13', 'Logged-in.'),
(443, 27, 'Parent', '2025-09-16 11:23:44', 'Logged-out.'),
(444, 37, 'Admin', '2025-09-16 11:23:54', 'Logged-in.'),
(445, 37, 'Admin', '2025-09-16 11:25:06', 'Logged-out.'),
(446, 27, 'Parent', '2025-09-16 11:25:23', 'Logged-in.'),
(447, 27, 'Parent', '2025-09-16 11:26:02', 'Logged-out.'),
(448, 37, 'Admin', '2025-09-16 11:26:13', 'Logged-in.'),
(449, 37, 'Admin', '2025-09-16 11:26:55', 'Logged-out.'),
(450, 27, 'Parent', '2025-09-16 11:27:08', 'Logged-in.'),
(451, 27, 'Parent', '2025-09-16 11:27:29', 'Logged-out.'),
(452, 37, 'Admin', '2025-09-16 11:27:39', 'Logged-in.'),
(453, 37, 'Admin', '2025-09-16 11:47:29', 'Logged-out.'),
(454, 27, 'Parent', '2025-09-16 11:47:46', 'Logged-in.'),
(455, 27, 'Parent', '2025-09-16 11:48:19', 'Logged-out.'),
(456, 37, 'Admin', '2025-09-16 11:49:13', 'Logged-in.'),
(457, 37, 'Admin', '2025-09-16 11:51:39', 'Logged-out.'),
(458, 27, 'Parent', '2025-09-16 11:51:49', 'Logged-in.'),
(459, 27, 'Parent', '2025-09-16 11:55:37', 'Logged-out.'),
(460, 37, 'Admin', '2025-09-16 12:09:41', 'Logged-in.'),
(461, 37, 'Admin', '2025-09-16 12:17:09', 'Logged-out.'),
(462, 37, 'Admin', '2025-09-16 12:17:26', 'Logged-in.'),
(463, 37, 'Admin', '2025-09-16 12:19:11', 'Logged-out.'),
(464, 37, 'Admin', '2025-09-16 12:19:39', 'Logged-in.'),
(465, 37, 'Admin', '2025-09-16 12:44:39', 'Logged-out.'),
(466, 30, 'Parent', '2025-09-16 12:46:36', 'Logged-in.'),
(467, 30, 'Parent', '2025-09-16 12:51:11', 'Logged-out.'),
(468, 37, 'Admin', '2025-09-16 12:51:21', 'Logged-in.'),
(469, 37, 'Admin', '2025-09-16 13:10:12', 'Logged-out.'),
(470, 30, 'Parent', '2025-09-16 13:10:39', 'Logged-in.'),
(471, 30, 'Parent', '2025-09-16 13:13:04', 'Logged-out.'),
(472, 47, 'Staff', '2025-09-16 13:13:33', 'Logged-in.'),
(473, 47, 'Staff', '2025-09-16 13:18:28', 'Logged-out.'),
(474, 0, '', '2025-09-16 13:18:35', 'Logged-out.'),
(475, 47, 'Staff', '2025-09-16 13:19:00', 'Logged-in.'),
(476, 47, 'Staff', '2025-09-16 13:19:04', 'Logged-out.'),
(477, 47, 'Staff', '2025-09-16 13:19:42', 'Logged-in.'),
(478, 47, 'Staff', '2025-09-16 13:40:35', 'Logged-out.'),
(479, 30, 'Parent', '2025-09-16 13:41:00', 'Logged-in.'),
(480, 30, 'Parent', '2025-09-16 13:45:46', 'Logged-out.'),
(481, 47, 'Staff', '2025-09-16 13:47:22', 'Logged-in.'),
(482, 47, 'Staff', '2025-09-16 13:50:26', 'Logged-out.'),
(483, 30, 'Parent', '2025-09-16 13:50:36', 'Logged-in.'),
(484, 30, 'Parent', '2025-09-16 13:51:36', 'Logged-out.'),
(485, 47, 'Staff', '2025-09-16 13:52:04', 'Logged-in.'),
(486, 47, 'Staff', '2025-09-16 13:54:19', 'Logged-out.'),
(487, 30, 'Parent', '2025-09-16 13:54:34', 'Logged-in.'),
(488, 30, 'Parent', '2025-09-16 13:55:08', 'Logged-out.'),
(489, 47, 'Staff', '2025-09-16 13:56:05', 'Logged-in.'),
(490, 47, 'Staff', '2025-09-16 14:01:29', 'Logged-out.'),
(491, 30, 'Parent', '2025-09-16 14:01:42', 'Logged-in.'),
(492, 30, 'Parent', '2025-09-16 14:04:12', 'Logged-out.'),
(493, 37, 'Admin', '2025-09-16 14:04:37', 'Logged-in.'),
(494, 37, 'Admin', '2025-09-16 14:05:47', 'Logged-out.'),
(495, 37, 'Admin', '2025-09-17 02:34:31', 'Logged-in.'),
(496, 30, 'Parent', '2025-09-17 04:51:55', 'Logged-in.'),
(497, 37, 'Admin', '2025-09-17 04:54:16', 'Logged-in.'),
(498, 37, 'Admin', '2025-09-17 04:59:02', 'Logged-out.'),
(499, 0, '', '2025-09-17 04:59:16', 'Logged-out.'),
(500, 29, 'Parent', '2025-09-17 04:59:45', 'Logged-in.'),
(501, 29, 'Parent', '2025-09-17 05:00:39', 'Logged-out.'),
(502, 37, 'Admin', '2025-09-17 05:01:04', 'Logged-in.'),
(503, 37, 'Admin', '2025-09-17 05:03:52', 'Logged-out.'),
(504, 30, 'Parent', '2025-09-17 05:04:02', 'Logged-in.'),
(505, 37, 'Admin', '2025-09-17 05:07:46', 'Logged-in.'),
(506, 37, 'Admin', '2025-09-17 05:12:04', 'Logged-out.'),
(507, 37, 'Admin', '2025-09-17 05:30:43', 'Logged-in.'),
(508, 37, 'Admin', '2025-09-17 05:31:11', 'Logged-out.'),
(509, 47, 'Staff', '2025-09-17 05:31:23', 'Logged-in.'),
(510, 47, 'Staff', '2025-09-17 05:31:44', 'Logged-out.'),
(511, 30, 'Parent', '2025-09-17 05:31:54', 'Logged-in.'),
(512, 30, 'Parent', '2025-09-17 05:32:01', 'Logged-out.'),
(513, 37, 'Admin', '2025-09-17 05:33:30', 'Logged-in.'),
(514, 37, 'Admin', '2025-09-17 05:33:53', 'Logged-out.'),
(515, 47, 'Staff', '2025-09-17 05:34:04', 'Logged-in.'),
(516, 47, 'Staff', '2025-09-17 05:34:18', 'Logged-out.'),
(517, 30, 'Parent', '2025-09-17 05:34:26', 'Logged-in.'),
(518, 30, 'Parent', '2025-09-17 05:34:44', 'Logged-out.'),
(519, 37, 'Admin', '2025-09-17 05:35:01', 'Logged-in.'),
(520, 30, 'Parent', '2025-09-17 05:42:03', 'Logged-in.'),
(521, 30, 'Parent', '2025-09-17 05:42:53', 'Logged-out.'),
(522, 37, 'Admin', '2025-09-17 05:43:06', 'Logged-in.'),
(523, 37, 'Admin', '2025-09-17 05:59:46', 'Logged-out.'),
(524, 30, 'Parent', '2025-09-17 06:00:00', 'Logged-in.'),
(525, 30, 'Parent', '2025-09-17 06:00:17', 'Logged-out.'),
(526, 47, 'Staff', '2025-09-17 06:00:33', 'Logged-in.'),
(527, 47, 'Staff', '2025-09-17 06:00:41', 'Logged-out.'),
(528, 37, 'Admin', '2025-09-17 06:00:57', 'Logged-in.'),
(529, 37, 'Admin', '2025-09-17 06:01:11', 'Logged-out.'),
(530, 30, 'Parent', '2025-09-17 06:01:21', 'Logged-in.'),
(531, 37, 'Admin', '2025-09-17 06:02:18', 'Logged-in.'),
(532, 37, 'Admin', '2025-09-17 08:58:47', 'Logged-in.'),
(533, 37, 'Admin', '2025-09-17 10:01:59', 'Logged-out.'),
(534, 30, 'Parent', '2025-09-17 10:02:24', 'Logged-in.'),
(535, 30, 'Parent', '2025-09-17 10:06:18', 'Logged-out.'),
(536, 37, 'Admin', '2025-09-17 10:06:40', 'Logged-in.'),
(537, 37, 'Admin', '2025-09-17 10:07:00', 'Logged-out.'),
(538, 30, 'Parent', '2025-09-17 10:07:17', 'Logged-in.'),
(539, 30, 'Parent', '2025-09-17 10:11:42', 'Logged-out.'),
(540, 0, '', '2025-09-17 10:11:53', 'Logged-out.'),
(541, 37, 'Admin', '2025-09-17 10:12:16', 'Logged-in.'),
(542, 37, 'Admin', '2025-09-17 10:15:38', 'Logged-out.'),
(543, 30, 'Parent', '2025-09-17 10:15:53', 'Logged-in.'),
(544, 30, 'Parent', '2025-09-17 10:16:31', 'Logged-out.'),
(545, 37, 'Admin', '2025-09-17 10:17:24', 'Logged-in.'),
(546, 37, 'Admin', '2025-09-17 10:17:37', 'Logged-out.'),
(547, 37, 'Admin', '2025-09-18 10:46:45', 'Logged-in.'),
(548, 37, 'Admin', '2025-09-18 11:36:17', 'Logged-out.'),
(549, 30, 'Parent', '2025-09-18 11:36:35', 'Logged-in.'),
(550, 30, 'Parent', '2025-09-18 11:37:12', 'Logged-out.'),
(551, 37, 'Admin', '2025-09-18 11:37:30', 'Logged-in.'),
(552, 37, 'Admin', '2025-09-18 11:41:46', 'Logged-out.'),
(553, 29, 'Parent', '2025-09-18 11:42:29', 'Logged-in.'),
(554, 29, 'Parent', '2025-09-18 11:42:48', 'Logged-out.'),
(555, 37, 'Admin', '2025-09-18 11:43:03', 'Logged-in.'),
(556, 37, 'Admin', '2025-09-18 11:45:13', 'Logged-out.'),
(557, 29, 'Parent', '2025-09-18 11:45:18', 'Logged-in.'),
(558, 29, 'Parent', '2025-09-18 11:45:47', 'Logged-out.'),
(559, 37, 'Admin', '2025-09-18 11:46:12', 'Logged-in.'),
(560, 37, 'Admin', '2025-09-18 11:46:35', 'Logged-out.'),
(561, 30, 'Parent', '2025-09-18 11:46:42', 'Logged-in.'),
(562, 30, 'Parent', '2025-09-18 11:47:19', 'Logged-out.'),
(563, 37, 'Admin', '2025-09-18 11:47:34', 'Logged-in.'),
(564, 37, 'Admin', '2025-09-18 12:09:28', 'Logged-out.'),
(565, 30, 'Parent', '2025-09-18 12:11:27', 'Logged-in.'),
(566, 30, 'Parent', '2025-09-18 13:38:32', 'Logged-out.'),
(567, 37, 'Admin', '2025-09-18 13:40:12', 'Logged-in.'),
(568, 37, 'Admin', '2025-09-19 13:18:40', 'Logged-in.'),
(569, 37, 'Admin', '2025-09-19 13:19:15', 'Logged-out.'),
(570, 30, 'Parent', '2025-09-19 13:19:36', 'Logged-in.'),
(571, 30, 'Parent', '2025-09-19 13:20:29', 'Logged-out.'),
(572, 37, 'Admin', '2025-09-19 13:20:47', 'Logged-in.'),
(573, 37, 'Admin', '2025-09-19 13:23:30', 'Logged-in.'),
(574, 37, 'Admin', '2025-09-19 13:23:37', 'Logged-out.'),
(575, 30, 'Parent', '2025-09-19 13:24:02', 'Logged-in.'),
(576, 30, 'Parent', '2025-09-19 13:25:27', 'Logged-out.'),
(577, 30, 'Parent', '2025-09-19 13:25:42', 'Logged-in.'),
(578, 30, 'Parent', '2025-09-19 13:28:30', 'Logged-out.'),
(579, 29, 'Parent', '2025-09-19 13:28:40', 'Logged-in.'),
(580, 37, 'Admin', '2025-09-19 13:37:52', 'Logged-in.'),
(581, 37, 'Admin', '2025-09-19 13:38:41', 'Logged-in.'),
(582, 37, 'Admin', '2025-09-19 13:40:09', 'Logged-out.'),
(583, 29, 'Parent', '2025-09-19 13:40:40', 'Logged-out.'),
(584, 28, 'Parent', '2025-09-19 13:40:51', 'Logged-in.'),
(585, 28, 'Parent', '2025-09-19 13:41:47', 'Logged-out.'),
(586, 30, 'Parent', '2025-09-19 13:42:34', 'Logged-in.'),
(587, 30, 'Parent', '2025-09-19 13:43:20', 'Logged-in.'),
(588, 37, 'Admin', '2025-09-19 13:43:21', 'Logged-out.'),
(589, 30, 'Parent', '2025-09-19 13:43:55', 'Logged-out.'),
(590, 37, 'Admin', '2025-09-19 16:48:16', 'Logged-in.'),
(591, 37, 'Admin', '2025-09-19 16:48:36', 'Logged-out.'),
(592, 30, 'Parent', '2025-09-19 16:49:00', 'Logged-in.'),
(593, 37, 'Admin', '2025-09-20 00:19:34', 'Logged-in.'),
(594, 37, 'Admin', '2025-09-20 00:19:39', 'Logged-out.'),
(595, 30, 'Parent', '2025-09-20 00:20:14', 'Logged-in.'),
(596, 30, 'Parent', '2025-09-20 00:20:34', 'Logged-out.'),
(597, 37, 'Admin', '2025-09-20 00:20:50', 'Logged-in.'),
(598, 37, 'Admin', '2025-09-20 00:21:38', 'Logged-out.'),
(599, 28, 'Parent', '2025-09-20 00:22:01', 'Logged-in.'),
(600, 28, 'Parent', '2025-09-20 00:22:26', 'Logged-out.'),
(601, 37, 'Admin', '2025-09-20 00:22:47', 'Logged-in.'),
(602, 37, 'Admin', '2025-09-20 00:22:51', 'Logged-in.'),
(603, 37, 'Admin', '2025-09-20 00:23:12', 'Logged-out.'),
(604, 37, 'Admin', '2025-09-21 16:37:26', 'Logged-in.'),
(605, 37, 'Admin', '2025-09-22 19:12:36', 'Logged-in.'),
(606, 37, 'Admin', '2025-09-22 19:22:27', 'Logged-out.'),
(607, 47, 'Staff', '2025-09-22 19:22:37', 'Logged-in.'),
(608, 47, 'Staff', '2025-09-22 19:23:22', 'Logged-out.'),
(609, 30, 'Parent', '2025-09-22 19:23:30', 'Logged-in.'),
(610, 30, 'Parent', '2025-09-22 19:24:25', 'Logged-out.'),
(611, 30, 'Parent', '2025-09-22 19:24:34', 'Logged-in.'),
(612, 30, 'Parent', '2025-09-22 19:24:43', 'Logged-out.'),
(613, 37, 'Admin', '2025-09-30 08:30:29', 'Logged-in.'),
(614, 37, 'Admin', '2025-09-30 11:08:14', 'Logged-in.'),
(615, 28, 'Parent', '2025-09-30 11:15:20', 'Logged-in.'),
(616, 28, 'Parent', '2025-10-07 20:18:16', 'Logged-in.'),
(617, 28, 'Parent', '2025-10-07 20:18:49', 'Logged-out.'),
(618, 37, 'Admin', '2025-10-07 20:27:15', 'Logged-in.'),
(619, 0, '', '2025-10-07 20:28:26', 'Logged-out.'),
(620, 37, 'Admin', '2025-10-08 20:14:12', 'Logged-in.'),
(621, 37, 'Admin', '2025-10-08 21:20:39', 'Logged-out.'),
(622, 37, 'Admin', '2025-11-03 13:23:52', 'Logged-in.'),
(623, 28, 'Parent', '2025-11-03 13:24:42', 'Logged-in.'),
(624, 47, 'Staff', '2025-11-03 13:47:51', 'Logged-in.'),
(625, 37, 'Admin', '2025-11-06 11:38:05', 'Logged-in.'),
(626, 28, 'Parent', '2025-11-06 11:39:05', 'Logged-in.'),
(627, 28, 'Parent', '2025-11-06 13:10:08', 'Logged-out.'),
(628, 28, 'Parent', '2025-11-06 13:10:16', 'Logged-in.'),
(629, 0, '', '2025-11-06 13:39:32', 'Logged-out.'),
(630, 47, 'Staff', '2025-11-06 13:40:16', 'Logged-in.'),
(631, 28, 'Parent', '2025-11-06 13:57:05', 'Logged-out.'),
(632, 33, 'Parent', '2025-11-06 13:57:29', 'Logged-in.'),
(633, 33, 'Parent', '2025-11-06 13:57:58', 'Logged-out.'),
(634, 28, 'Parent', '2025-11-06 13:58:19', 'Logged-in.'),
(635, 37, 'Admin', '2025-11-06 14:23:33', 'Logged-out.'),
(636, 37, 'Admin', '2025-11-06 14:23:55', 'Logged-in.'),
(637, 47, 'Staff', '2025-11-06 14:24:22', 'Logged-in.'),
(638, 47, 'Staff', '2025-11-06 14:25:15', 'Logged-out.'),
(639, 47, 'Staff', '2025-11-06 14:25:40', 'Logged-in.'),
(640, 47, 'Staff', '2025-11-06 14:26:52', 'Logged-out.'),
(641, 28, 'Parent', '2025-11-06 14:26:59', 'Logged-in.'),
(642, 28, 'Parent', '2025-11-06 14:30:37', 'Logged-out.'),
(643, 31, 'Parent', '2025-11-06 14:30:54', 'Logged-in.'),
(644, 37, 'Admin', '2025-11-06 14:32:17', 'Logged-out.'),
(645, 47, 'Staff', '2025-11-06 14:32:26', 'Logged-in.'),
(646, 47, 'Staff', '2025-11-06 14:50:09', 'Logged-out.'),
(647, 28, 'Parent', '2025-11-06 14:50:20', 'Logged-in.'),
(648, 28, 'Parent', '2025-11-06 14:50:31', 'Logged-out.'),
(649, 37, 'Admin', '2025-11-06 14:52:02', 'Logged-in.'),
(650, 31, 'Parent', '2025-11-06 14:53:28', 'Logged-out.'),
(651, 28, 'Parent', '2025-11-06 14:53:40', 'Logged-in.'),
(652, 28, 'Parent', '2025-11-07 13:57:58', 'Logged-out.'),
(653, 31, 'Parent', '2025-11-07 13:58:14', 'Logged-in.'),
(654, 31, 'Parent', '2025-11-07 14:01:07', 'Logged-out.'),
(655, 28, 'Parent', '2025-11-07 14:02:25', 'Logged-in.'),
(656, 37, 'Admin', '2025-11-07 14:12:49', 'Logged-out.'),
(657, 47, 'Staff', '2025-11-07 14:12:58', 'Logged-in.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `role` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `gender` text NOT NULL,
  `cs` text NOT NULL,
  `email` text NOT NULL,
  `contact` text NOT NULL,
  `address` text NOT NULL,
  `bday` date NOT NULL,
  `age` int NOT NULL,
  `userNo` text NOT NULL,
  `logattempt` int NOT NULL,
  `pic` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `fname`, `lname`, `role`, `active`, `username`, `password`, `gender`, `cs`, `email`, `contact`, `address`, `bday`, `age`, `userNo`, `logattempt`, `pic`) VALUES
(37, 'Frederick Neil', 'Batas', 'Admin', 0, 'Neil', 'Admin@1', 'Male', 'Single', 'fbatas918@gmail.com', '09994613411', 'San Mateo', '2003-04-18', 21, 'K-202505084', 0, 'neil.jpg'),
(47, 'Frederick', 'Caballero', 'Staff', 0, 'Neilstaff', 'Staff@1', 'Male', 'Single', 'Frederickcaballero@gmail.com', '09996825245', 'San Mateo', '2003-04-18', 21, 'K-202507172', 0, 'Copilot_20250630_164406.png');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
