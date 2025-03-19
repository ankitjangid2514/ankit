-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 25, 2025 at 02:46 PM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u764621863_a2zxyz`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(1) NOT NULL,
  `mobile_number` bigint(10) NOT NULL,
  `telegram_link` varchar(50) NOT NULL,
  `whatsapp_number` bigint(10) NOT NULL,
  `qr_code` varchar(100) NOT NULL,
  `google_pay` varchar(255) DEFAULT NULL,
  `marchant_Id` varchar(255) NOT NULL,
  `home_text` varchar(255) NOT NULL,
  `privacyPolicy` varchar(255) NOT NULL,
  `upi_payment` varchar(255) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `mobile_number`, `telegram_link`, `whatsapp_number`, `qr_code`, `google_pay`, `marchant_Id`, `home_text`, `privacyPolicy`, `upi_payment`, `created_at`, `updated_at`) VALUES
(1, 919784357126, 'https://ka', 919784357126, 'uploads/qr_code/1735541530.png', '8764075504@ibl', '249839700035273@cnrb', 'Welcome to Balaji 567 App. This app show all results', 'https://www.freeprivacypolicy.com/live/af623d35-2d48-4d5c-979a-fea2bffb8d5a', '8764075504@upi', '2024-10-16', '2025-02-01');

-- --------------------------------------------------------

--
-- Table structure for table `bid_table`
--

CREATE TABLE `bid_table` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(50) NOT NULL,
  `market_id` int(11) NOT NULL,
  `gtype_id` int(11) NOT NULL,
  `bid_date` date NOT NULL,
  `session` varchar(255) DEFAULT NULL,
  `open_digit` varchar(11) DEFAULT 'N/A',
  `close_digit` varchar(11) DEFAULT 'N/A',
  `jodi` varchar(11) DEFAULT 'N/A',
  `open_panna` varchar(11) DEFAULT 'N/A',
  `close_panna` varchar(11) DEFAULT 'N/A',
  `half_sangam_a` varchar(50) DEFAULT 'N/A',
  `half_sangam_b` varchar(50) DEFAULT 'N/A',
  `full_sangam` varchar(50) DEFAULT 'N/A',
  `amount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bid_table`
--

INSERT INTO `bid_table` (`id`, `user_id`, `market_id`, `gtype_id`, `bid_date`, `session`, `open_digit`, `close_digit`, `jodi`, `open_panna`, `close_panna`, `half_sangam_a`, `half_sangam_b`, `full_sangam`, `amount`, `created_at`, `updated_at`) VALUES
(1, 46, 26, 7, '2025-01-20', NULL, 'N/A', 'N/A', '59', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 41, '2025-01-20 18:15:55', '2025-01-20 18:15:55'),
(2, 46, 26, 2, '2025-01-20', 'open', 'N/A', 'N/A', 'N/A', '230', 'N/A', 'N/A', 'N/A', 'N/A', 10, '2025-01-20 18:16:38', '2025-01-20 18:16:38'),
(3, 46, 26, 2, '2025-01-20', 'close', 'N/A', 'N/A', 'N/A', 'N/A', '450', 'N/A', 'N/A', 'N/A', 10, '2025-01-20 18:16:56', '2025-01-20 18:16:56'),
(4, 46, 26, 6, '2025-01-20', NULL, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '230-450', 10, '2025-01-20 18:17:15', '2025-01-20 18:17:15'),
(5, 46, 26, 5, '2025-01-20', 'open', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '5 - 450', 'N/A', 'N/A', 11, '2025-01-20 18:20:52', '2025-01-20 18:20:52'),
(6, 46, 26, 5, '2025-01-20', 'close', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '9 - 230', 'N/A', 22, '2025-01-20 18:21:11', '2025-01-20 18:21:11'),
(7, 46, 2, 1, '2025-01-23', 'close', 'N/A', '1', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 20, '2025-01-23 22:48:45', '2025-01-23 22:48:45'),
(8, 46, 6, 7, '2025-01-23', NULL, 'N/A', 'N/A', '12', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 122, '2025-01-23 23:24:21', '2025-01-23 23:24:21'),
(9, 46, 26, 1, '2025-01-28', 'open', '2', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 11, '2025-01-28 10:58:07', '2025-01-28 10:58:07'),
(10, 46, 26, 1, '2025-01-28', 'close', 'N/A', '3', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 12, '2025-01-28 10:58:29', '2025-01-28 10:58:29'),
(11, 46, 26, 2, '2025-01-28', 'open', 'N/A', 'N/A', 'N/A', '125', 'N/A', 'N/A', 'N/A', 'N/A', 13, '2025-01-28 10:58:59', '2025-01-28 10:58:59'),
(12, 46, 26, 2, '2025-01-28', 'close', 'N/A', 'N/A', 'N/A', 'N/A', '123', 'N/A', 'N/A', 'N/A', 14, '2025-01-28 10:59:20', '2025-01-28 10:59:20'),
(13, 46, 26, 7, '2025-01-28', NULL, 'N/A', 'N/A', '89', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 15, '2025-01-28 10:59:40', '2025-01-28 10:59:40'),
(14, 46, 26, 5, '2025-01-28', 'open', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '8 - 126', 'N/A', 'N/A', 16, '2025-01-28 11:00:03', '2025-01-28 11:00:03'),
(15, 46, 26, 5, '2025-01-28', 'close', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '9 - 125', 'N/A', 17, '2025-01-28 11:00:42', '2025-01-28 11:00:42'),
(16, 46, 26, 6, '2025-01-28', NULL, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '125-126', 19, '2025-01-28 11:01:03', '2025-01-28 11:01:03'),
(17, 66, 26, 1, '2025-01-30', 'open', '1', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 10, '2025-01-30 23:44:19', '2025-01-30 23:44:19'),
(18, 66, 26, 1, '2025-01-30', 'close', 'N/A', '2', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 10, '2025-01-30 23:44:27', '2025-01-30 23:44:27'),
(19, 66, 26, 7, '2025-01-30', NULL, 'N/A', 'N/A', '12', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 10, '2025-01-30 23:44:52', '2025-01-30 23:44:52'),
(20, 66, 26, 2, '2025-01-30', 'open', 'N/A', 'N/A', 'N/A', '128', 'N/A', 'N/A', 'N/A', 'N/A', 10, '2025-01-30 23:45:42', '2025-01-30 23:45:42'),
(21, 66, 26, 2, '2025-01-30', 'close', 'N/A', 'N/A', 'N/A', 'N/A', '129', 'N/A', 'N/A', 'N/A', 10, '2025-01-30 23:45:51', '2025-01-30 23:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposite_table`
--

CREATE TABLE `deposite_table` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `deposit_amount` int(10) NOT NULL,
  `deposit_by` varchar(255) NOT NULL DEFAULT 'user',
  `trnxnId` varchar(255) DEFAULT NULL,
  `marchantId` varchar(255) DEFAULT NULL,
  `deposite_status` varchar(20) NOT NULL,
  `upload_qr` varchar(255) DEFAULT NULL,
  `deposite_date` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deposite_table`
--

INSERT INTO `deposite_table` (`id`, `user_id`, `deposit_amount`, `deposit_by`, `trnxnId`, `marchantId`, `deposite_status`, `upload_qr`, `deposite_date`, `updated_at`) VALUES
(1, 46, 7891, '', '', '', 'approved', 'uploads/qr_code/1732953134.png', '2024-11-30', '2024-11-30'),
(15, 45, 1, '', '', '', 'approved', NULL, '2025-01-12', '2025-01-12'),
(24, 46, 1, '', '', '', 'approved', NULL, '2025-01-16', '2025-01-16'),
(25, 46, 1, '', '876tgbni876t', 'ngt678ikjhg6', 'approved', NULL, '2025-01-16', '2025-01-16'),
(26, 45, 1, '', 'YBLb408a702673643d995d896cff68973cd', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-16', '2025-01-16'),
(27, 45, 1, '', 'YBL7a3e91c7b0f64c74bddd3e0148b58a9e', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-16', '2025-01-16'),
(28, 45, 1, '', 'YBL3bb2faf9d66d4980a5dae8d2a4be64ca', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-16', '2025-01-16'),
(29, 45, 1, '', 'YBL6951b129976a4916a05f63cf630d3e9a', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-16', '2025-01-16'),
(30, 45, 10, '', 'YBL6951b129976a4916a05f63cf630d3e9a', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-16', '2025-01-16'),
(31, 45, 1, '', 'YBLa4dcd2e4d3bc44ea9396b175ec145efd', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-16', '2025-01-16'),
(32, 45, 1, '', 'YBL1aa28d29d3fc4410b0f68e0f67d9334c', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-16', '2025-01-16'),
(33, 45, 1, '', 'YBL10e9f9959ebe405eb08c648efc235d2d', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-17', '2025-01-17'),
(34, 45, 1, '', 'YBL8fb112c2565e489a8ad4e8c6ec21791e', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-17', '2025-01-17'),
(35, 45, 1, '', 'YBLfeb99887904c4a50ab8c5b7a59bfda53', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-17', '2025-01-17'),
(36, 46, 1, '', 'PTM0125db298f2f4fff9cf803395531135f', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-17', '2025-01-17'),
(37, 46, 1, '', 'PTM6fd9218083174898a2eb60e2eaf3ad92', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-18', '2025-01-18'),
(38, 45, 1, '', 'YBL8de222e6c67a47af861af187e8cc0c5f', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-18', '2025-01-18'),
(39, 45, 1, '', 'YBL23749523acf54ce1827f1907912e599d', 'HSBIMOPAD.YC025294-02EZ01000050166@SBI', 'approved', NULL, '2025-01-18', '2025-01-18'),
(40, 45, 445, '', NULL, NULL, 'pending', 'uploads/qr_code/1737306523.jpg', '2025-01-19', '2025-01-19'),
(41, 46, 88, '', NULL, NULL, 'pending', 'uploads/qr_code/1737306528.png', '2025-01-19', '2025-01-19'),
(42, 45, 445, '', NULL, NULL, 'approved', 'uploads/qr_code/1737306551.jpg', '2025-01-19', '2025-01-19'),
(43, 45, 1, '', 'PTM2693e383d732406493dada496575f759', '7424995796@kbl', 'approved', NULL, '2025-01-20', '2025-01-20'),
(44, 46, 50, 'admin', NULL, NULL, 'approved', NULL, '2025-01-20', '2025-01-20'),
(45, 66, 495, 'admin', NULL, NULL, 'approved', NULL, '2025-01-30', '2025-01-30'),
(46, 46, 1, 'user', 'YBL505ea1622b3c470c8a4c889137be4d33', '7424995796@kbl', 'approved', NULL, '2025-02-05', '2025-02-05');

-- --------------------------------------------------------

--
-- Table structure for table `Desawar_text`
--

CREATE TABLE `Desawar_text` (
  `id` int(11) NOT NULL,
  `first` varchar(255) NOT NULL,
  `second` varchar(255) NOT NULL,
  `third` varchar(255) NOT NULL,
  `video_link` varchar(255) NOT NULL,
  `created_at` timestamp(6) NOT NULL,
  `update_at` timestamp(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Desawar_text`
--

INSERT INTO `Desawar_text` (`id`, `first`, `second`, `third`, `video_link`, `created_at`, `update_at`) VALUES
(1, 'jbhfgjbguhbfhsdbv', 'bgjfjgbfdj  ajay', 'gbjfjgbdfjg  ram', 'https://www.youtube.com/watch?v=ZQkUYmaVwgs&list=RDZQkUYmaVwgs&start_radio=1', '2025-01-29 05:21:19.000000', '2025-01-29 05:21:19.000000');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `full_sangam`
--

CREATE TABLE `full_sangam` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galidesawar_bid_table`
--

CREATE TABLE `galidesawar_bid_table` (
  `id` int(100) NOT NULL,
  `user_id` int(50) NOT NULL,
  `desawar_id` int(20) NOT NULL,
  `desawar_gtype_id` int(11) NOT NULL,
  `desawar_bid_date` date NOT NULL,
  `digit` int(5) NOT NULL,
  `amount` int(10) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT NULL,
  `updated_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galidesawar_bid_table`
--

INSERT INTO `galidesawar_bid_table` (`id`, `user_id`, `desawar_id`, `desawar_gtype_id`, `desawar_bid_date`, `digit`, `amount`, `created_at`, `updated_at`) VALUES
(1, 46, 2, 3, '2025-01-21', 55, 10, '2025-01-21 16:46:30.000000', '2025-01-21 16:46:30.000000'),
(2, 46, 2, 3, '2025-01-20', 55, 13, '2025-01-21 16:31:30.000000', '2025-01-21 16:31:30.000000'),
(3, 46, 2, 3, '2025-01-20', 55, 13, '2025-01-21 16:46:30.000000', '2025-01-21 16:46:30.000000'),
(4, 46, 2, 3, '2025-01-22', 55, 10, '2025-01-22 04:52:59.000000', '2025-01-22 04:52:59.000000'),
(5, 46, 1, 3, '2025-01-22', 35, 11, '2025-01-22 05:52:31.000000', '2025-01-22 05:52:31.000000'),
(6, 46, 3, 3, '2025-01-22', 44, 10, '2025-01-22 05:58:29.000000', '2025-01-22 05:58:29.000000'),
(7, 46, 4, 3, '2025-01-22', 66, 10, '2025-01-22 06:29:19.000000', '2025-01-22 06:29:19.000000'),
(8, 46, 1, 3, '2025-01-22', 87, 11, '2025-01-22 06:47:40.000000', '2025-01-21 19:47:40.000000'),
(9, 47, 1, 3, '2025-01-22', 87, 10, '2025-01-22 06:51:03.000000', '2025-01-21 06:51:03.000000'),
(10, 46, 2, 2, '2025-01-22', 8, 16, '2025-01-22 08:32:41.000000', '2025-01-22 08:32:41.000000'),
(11, 46, 1, 1, '2025-01-23', 5, 15, '2025-01-23 23:16:24.000000', '2025-01-23 23:16:24.000000'),
(12, 46, 4, 3, '2025-01-24', 88, 10, '2025-01-24 11:19:42.000000', '2025-01-24 11:19:42.000000'),
(13, 46, 1, 1, '2025-01-25', 1, 100, '2025-01-25 00:31:45.000000', '2025-01-25 00:31:45.000000'),
(14, 46, 6, 3, '2025-01-28', 92, 29, '2025-01-28 11:32:49.000000', '2025-01-28 11:32:49.000000'),
(15, 46, 6, 1, '2025-01-28', 9, 10, '2025-01-28 11:33:03.000000', '2025-01-28 11:33:03.000000'),
(16, 46, 6, 2, '2025-01-28', 9, 11, '2025-01-28 11:33:21.000000', '2025-01-28 11:33:21.000000'),
(17, 46, 1, 1, '2025-01-30', 1, 10, '2025-01-30 23:51:09.000000', '2025-01-30 23:51:09.000000'),
(18, 46, 2, 2, '2025-01-30', 1, 10, '2025-01-30 23:51:21.000000', '2025-01-30 23:51:21.000000');

-- --------------------------------------------------------

--
-- Table structure for table `galidesawar_game_rates`
--

CREATE TABLE `galidesawar_game_rates` (
  `id` int(1) NOT NULL,
  `left_digit_bid` int(10) NOT NULL,
  `left_digit_win` int(10) NOT NULL,
  `right_digit_bid` int(10) NOT NULL,
  `right_digit_win` int(10) NOT NULL,
  `jodi_digit_bid` int(10) NOT NULL,
  `jodi_digit_win` int(10) NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galidesawar_game_rates`
--

INSERT INTO `galidesawar_game_rates` (`id`, `left_digit_bid`, `left_digit_win`, `right_digit_bid`, `right_digit_win`, `jodi_digit_bid`, `jodi_digit_win`, `created_at`, `updated_at`) VALUES
(1, 10, 100, 10, 100, 10, 1000, NULL, '2024-12-02 16:25:54.000000');

-- --------------------------------------------------------

--
-- Table structure for table `galidesawar_gtype`
--

CREATE TABLE `galidesawar_gtype` (
  `id` int(10) NOT NULL,
  `gtype` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `created_at` datetime(6) NOT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galidesawar_gtype`
--

INSERT INTO `galidesawar_gtype` (`id`, `gtype`, `img`, `created_at`, `updated_at`) VALUES
(1, 'Left Digit', 'images/games/banner1.jpeg', '0000-00-00 00:00:00.000000', NULL),
(2, 'Right Digit', 'images/games/banner2.jpeg', '0000-00-00 00:00:00.000000', NULL),
(3, 'Jodi Digit', 'images/games/jodiDigit.png', '0000-00-00 00:00:00.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `galidesawar_market`
--

CREATE TABLE `galidesawar_market` (
  `id` int(20) NOT NULL,
  `desawar_name` varchar(255) NOT NULL,
  `desawar_name_hindi` varchar(255) DEFAULT NULL,
  `open_time` time NOT NULL,
  `close_time` time(6) NOT NULL,
  `market_status` varchar(10) DEFAULT NULL,
  `active` varchar(10) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galidesawar_market`
--

INSERT INTO `galidesawar_market` (`id`, `desawar_name`, `desawar_name_hindi`, `open_time`, `close_time`, `market_status`, `active`, `created_at`, `updated_at`) VALUES
(1, 'disawr', NULL, '18:04:00', '19:04:00.000000', 'active', NULL, '2024-12-02 16:04:31.000000', NULL),
(2, 'hero', NULL, '14:51:00', '22:50:00.000000', 'active', NULL, '2025-01-21 12:50:27.000000', NULL),
(3, 'mahadev', NULL, '23:50:00', '22:51:00.000000', 'active', NULL, '2025-01-21 12:51:16.000000', NULL),
(4, 'rajdhani', NULL, '22:50:00', '22:49:00.000000', 'active', NULL, '2025-01-21 12:51:43.000000', NULL),
(6, 'Gali', NULL, '23:37:00', '23:58:00.000000', 'active', NULL, '2025-01-22 23:37:34.000000', NULL),
(7, 'ajay', NULL, '00:04:00', '02:04:00.000000', 'active', NULL, '2025-02-06 00:05:06.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `galidesawar_result`
--

CREATE TABLE `galidesawar_result` (
  `id` int(10) NOT NULL,
  `desawar_id` int(10) NOT NULL,
  `digit` varchar(5) NOT NULL,
  `result_date` date NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galidesawar_result`
--

INSERT INTO `galidesawar_result` (`id`, `desawar_id`, `digit`, `result_date`, `created_at`, `updated_at`) VALUES
(2, 1, '22', '2025-01-30', '2025-01-30', NULL),
(3, 4, '33', '2025-01-30', '2025-01-30', NULL),
(4, 6, '30', '2025-01-29', '2025-01-29', NULL),
(5, 6, '11', '2025-01-30', '2025-01-30', NULL),
(6, 1, '12', '2025-01-31', '2025-01-31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `galidesawar_winners`
--

CREATE TABLE `galidesawar_winners` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `bid_id` int(10) NOT NULL,
  `desawar_id` int(10) NOT NULL,
  `bid_type` varchar(20) NOT NULL,
  `bid_point` int(10) NOT NULL,
  `winning_amount` int(10) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galidesawar_winners`
--

INSERT INTO `galidesawar_winners` (`id`, `user_id`, `bid_id`, `desawar_id`, `bid_type`, `bid_point`, `winning_amount`, `created_at`, `updated_at`) VALUES
(1, 46, 15, 6, 'left digit', 9, 100, '2025-01-28', '2025-01-28'),
(2, 46, 16, 6, 'right digit', 9, 110, '2025-01-28', '2025-01-28');

-- --------------------------------------------------------

--
-- Table structure for table `galidesawar_winners_guess`
--

CREATE TABLE `galidesawar_winners_guess` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `desawar_id` int(11) NOT NULL,
  `bid_type` varchar(255) NOT NULL,
  `bid_point` int(11) NOT NULL,
  `winning_amount` int(11) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(255) NOT NULL,
  `open` tinyint(1) NOT NULL,
  `market_id` bigint(20) UNSIGNED NOT NULL,
  `single_ank_number` int(11) DEFAULT NULL,
  `single_ank_points` int(11) DEFAULT NULL,
  `jodi_number` int(11) DEFAULT NULL,
  `jodi_points` int(11) DEFAULT NULL,
  `single_panna_number` int(11) DEFAULT NULL,
  `single_panna_points` int(11) DEFAULT NULL,
  `double_panna_number` int(11) DEFAULT NULL,
  `double_panna_points` int(11) DEFAULT NULL,
  `triple_panna_number` int(11) DEFAULT NULL,
  `triple_panna_points` int(11) DEFAULT NULL,
  `half_sangam_a_open` int(11) DEFAULT NULL,
  `half_sangam_a_close_digit` int(11) DEFAULT NULL,
  `half_sangam_a_points` int(11) DEFAULT NULL,
  `half_sangam_b_close` int(11) DEFAULT NULL,
  `half_sangam_b_open_digit` int(11) DEFAULT NULL,
  `half_sangam_b_points` int(11) DEFAULT NULL,
  `full_sangam_open` int(11) DEFAULT NULL,
  `full_sangam_close` int(11) DEFAULT NULL,
  `full_sangam_points` int(11) DEFAULT NULL,
  `total` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_rates`
--

CREATE TABLE `game_rates` (
  `id` int(20) UNSIGNED NOT NULL,
  `single_digit_bid` decimal(8,2) DEFAULT NULL,
  `single_digit_win` decimal(8,2) DEFAULT NULL,
  `jodi_digit_bid` decimal(8,2) DEFAULT NULL,
  `jodi_digit_win` decimal(8,2) DEFAULT NULL,
  `single_match_bid` decimal(8,2) DEFAULT NULL,
  `single_match_win` decimal(8,2) DEFAULT NULL,
  `double_match_bid` decimal(8,2) DEFAULT NULL,
  `double_match_win` decimal(8,2) DEFAULT NULL,
  `triple_match_bid` decimal(8,2) DEFAULT NULL,
  `triple_match_win` decimal(8,2) DEFAULT NULL,
  `half_sangam_bid` decimal(8,2) DEFAULT NULL,
  `half_sangam_win` decimal(8,2) DEFAULT NULL,
  `full_sangam_bid` decimal(8,2) DEFAULT NULL,
  `full_sangam_win` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `game_rates`
--

INSERT INTO `game_rates` (`id`, `single_digit_bid`, `single_digit_win`, `jodi_digit_bid`, `jodi_digit_win`, `single_match_bid`, `single_match_win`, `double_match_bid`, `double_match_win`, `triple_match_bid`, `triple_match_win`, `half_sangam_bid`, `half_sangam_win`, `full_sangam_bid`, `full_sangam_win`, `created_at`, `updated_at`) VALUES
(1, 1.00, 10.00, 1.00, 100.00, 1.00, 140.00, 1.00, 280.00, 1.00, 1000.00, 1.00, 1500.00, 1.00, 12000.00, NULL, '2025-01-18 21:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `gtype`
--

CREATE TABLE `gtype` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gtype` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gtype`
--

INSERT INTO `gtype` (`id`, `gtype`, `img`, `created_at`, `updated_at`) VALUES
(1, 'Single Digit', 'images/games/single.png', NULL, NULL),
(2, 'Single Panna', 'images/games/single_patti.png', NULL, NULL),
(3, 'Double Panna', 'images/games/double_patti.png', NULL, NULL),
(4, 'Triple Panna', 'images/games/triple_patti.png', NULL, NULL),
(5, 'Half Sangam', 'images/games/half_sangam.png', NULL, NULL),
(6, 'Full Sangam', 'images/games/full_sangam.png', NULL, NULL),
(7, 'Jodi Digit', 'images/games/jodi.png', NULL, NULL),
(8, 'sp moter', 'images/games/sp-moter.jpg', NULL, NULL),
(9, 'dp moter', 'images/games/sp-moter.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market`
--

CREATE TABLE `market` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `market_name` varchar(255) NOT NULL,
  `market_name_hindi` varchar(255) DEFAULT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `market_status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `market`
--

INSERT INTO `market` (`id`, `market_name`, `market_name_hindi`, `open_time`, `close_time`, `market_status`, `created_at`, `updated_at`) VALUES
(21, 'Sridevi', NULL, '11:30:00', '12:31:00', 'active', '2025-01-17 12:31:10', NULL),
(27, 'Ajay', NULL, '10:58:00', '23:59:00', 'active', '2025-02-11 15:59:17', NULL),
(28, 'Shivani', NULL, '13:59:00', '15:59:00', 'active', '2025-02-11 15:59:52', NULL),
(29, 'Divan', NULL, '14:00:00', '16:00:00', 'active', '2025-02-11 16:00:35', NULL),
(30, 'Amit', NULL, '16:00:00', '17:00:00', 'active', '2025-02-11 16:00:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `market_results`
--

CREATE TABLE `market_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `market_id` bigint(20) UNSIGNED NOT NULL,
  `open_panna` varchar(255) DEFAULT NULL,
  `close_panna` varchar(3) DEFAULT NULL,
  `open_digit` int(11) DEFAULT NULL,
  `close_digit` int(11) DEFAULT NULL,
  `jodi` int(11) DEFAULT NULL,
  `half_sangam_a` varchar(11) DEFAULT NULL,
  `half_sangam_b` varchar(11) DEFAULT NULL,
  `full_sangam` varchar(11) DEFAULT NULL,
  `result_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `market_results`
--

INSERT INTO `market_results` (`id`, `market_id`, `open_panna`, `close_panna`, `open_digit`, `close_digit`, `jodi`, `half_sangam_a`, `half_sangam_b`, `full_sangam`, `result_date`, `created_at`, `updated_at`) VALUES
(1, 26, '230', NULL, 5, NULL, 5, '5', '230', '230', '2025-01-20', '2025-01-20 18:21:54', NULL),
(2, 26, '125', NULL, 8, NULL, 8, '8', '125', '125', '2025-01-28', '2025-01-28 11:05:11', NULL),
(3, 26, '128', '129', 1, 2, 12, '1 - 129', '2 - 128', '128-129', '2025-01-30', '2025-01-30 23:48:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(6, '2024_09_05_055858_create_games_table', 2),
(7, '2024_09_05_060030_create_game_types_table', 3),
(8, '2024_09_05_060515_create_half_sangam_a_table', 4),
(9, '2024_09_05_060654_create_half_sangam_b_table', 5),
(10, '2024_09_05_061037_create_full_sangam_table', 6),
(12, '2024_09_06_064913_create_market_table', 7),
(13, '2024_09_06_110238_create_game_rate_table', 8),
(14, '2024_09_07_000534_create_game_rates_table', 9),
(15, '2024_09_08_085343_create_wallets_table', 10),
(16, '2024_09_08_124838_create_withdrawal_table', 11),
(17, '2024_09_11_061304_create_gtype_table', 12),
(19, '2024_09_13_084914_create_bid_table_table', 13),
(20, '2025_01_21_221000_add_result_declared_to_galidesawar_result_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `notice_management`
--

CREATE TABLE `notice_management` (
  `id` int(10) NOT NULL,
  `notice_title` varchar(255) NOT NULL,
  `notice_date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice_management`
--

INSERT INTO `notice_management` (`id`, `notice_title`, `notice_date`, `description`, `status`, `created_at`, `updated_at`) VALUES
(2, 'ufhgiukdsfjk', '2024-10-08', 'bdhsfhjgdsjkhbfvsnmdfhj', 'pending', '2024-10-08', '2024-10-08');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4aXThnWtfArriv9iVJ7QBbwUmJsXlvw0pWfOlvcz', NULL, '2409:40d4:310e:4508:8000::', 'WhatsApp/2.23.20.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZTZRM2dPaE0wM21HT2ROTzdQRU1teHNJeGd5VGNCQVVLUktndkt0RCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei9hZG1pbl9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1740494488),
('5rS7UjkKsU3ObR3g7hD4jrjIXTcyUb6Z2GrIncDB', NULL, '2409:40d4:310e:4508:8000::', 'WhatsApp/2.23.20.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOU9UdFV6VzRKNG5QYVVUZktyOWFoSVVXZVdXSkExYXdHSkhKUk1IaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei9hZG1pbl9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740494488),
('belXaR5hhyW2EdUw83n6MePASJgNxyS1lZSNe56d', 70, '2409:40e0:49:5896:b9c4:938b:a47a:83eb', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibW1FWUNlN05BNG1RYTVhenNqTDhSczk2NzlsbEpQNHFrdzNvWUNTeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei93YWxsZXRfaGlzdG9yeSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjcwO3M6MTI6Imxhc3RBY3Rpdml0eSI7aToxNzQwNDk0MjkzO30=', 1740494293),
('DfQwphzQ5A70YhkvNjXW6opDlN6r55t6BJN41emr', NULL, '2409:40d4:310e:4508:8000::', 'WhatsApp/2.23.20.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibXlGOWIxa3FNMVlNaVRhWGlocjJKTmV2c1BqUUFhblI4RWhUSE9ORSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740493537),
('kRSs10BVVg55CIwOsw9WBvASjnq06pQ7ClPbQSHX', NULL, '2409:40d4:310e:4508:8000::', 'WhatsApp/2.23.20.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOW9oOHRSa09jMDh5cWFtdlJlTDVXNlNDdlRQeUJaNHkwRERHTjFnSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vYTJ6LW1hdGthLnh5eiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740494470),
('nt7lSsYkMgN6NFxwyto1eUKkyH2JlFklOQa3bauA', NULL, '2409:40d4:310e:4508:8000::', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia1BLYnRxZXpvMjd6RGd3c0VXdEcxMGZUclA0UTFQalhrd2VqdU5rWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei9hZG1pbl9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740494503),
('nWBiwioiWDXZwnPycTmQk0ThXzsTRDB9l7YjNieP', NULL, '2409:40e0:49:5896:b9c4:938b:a47a:83eb', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieGd4eXlISFIwcmh3REhuQTF6UU9TUDZ5TmJNeUhHanh1UlZWN3BkeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei9yZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740493675),
('utzk6seeA5e0Nt5Vu0FF3F89fproDHeoUxqC7Fki', NULL, '66.249.83.11', 'Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1RoemZpcXZkMFVXeU5qYnpJcVVuMzF1bDEwU2d1aWhacU4xR1ZMSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei9hZG1pbl9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740493603),
('WfiOi7Xyfw6eOjcVJBUW2KBwLJ8wJn4NGlCKbRRn', 2, '2409:40e0:49:5896:b9c4:938b:a47a:83eb', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUVh4QjBIdHV6NWI2anhUTEdzYlVOQU85RkRqYTc5VzIzSjNVaWRoOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei9hZG1pbl9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1740494633),
('yaJyLScbezH7QoYSPvruYJ9x1DH8qg4LqZnreqwY', 2, '2409:40d4:310e:4508:8000::', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZVlMT1BVdENBcXNmNnVmbTFPUUpwTFkyUTE5WmNibVhMZjA5MGZTbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei9hZG1pbl9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1740493662),
('zKTh2OSQSf0n1THVlKr6pNeu2k1hrz8AhfUMyyoe', NULL, '66.249.83.11', 'Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSUlqNWZQQ3ZUR1pabk84Y2tYcjZNWE5lYWppSURhaEZKT1FsOE42SyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYTJ6LW1hdGthLnh5ei91c2VyX21hbmFnZW1lbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1740493602);

-- --------------------------------------------------------

--
-- Table structure for table `set_amount`
--

CREATE TABLE `set_amount` (
  `id` int(11) NOT NULL,
  `min_deposite` int(11) NOT NULL,
  `max_deposite` int(11) NOT NULL,
  `min_withdrawal` int(11) NOT NULL,
  `max_withdrawal` int(11) NOT NULL,
  `min_bid_amount` int(11) NOT NULL,
  `max_bid_amount` int(11) NOT NULL,
  `welcome_bonus` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `set_amount`
--

INSERT INTO `set_amount` (`id`, `min_deposite`, `max_deposite`, `min_withdrawal`, `max_withdrawal`, `min_bid_amount`, `max_bid_amount`, `welcome_bonus`, `created_at`, `update_at`) VALUES
(1, 1, 100000, 11, 1000000, 10, 10000, 5, '2024-10-19 13:58:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `slider_images`
--

CREATE TABLE `slider_images` (
  `id` int(10) NOT NULL,
  `image` varchar(255) NOT NULL,
  `display_order` int(10) NOT NULL,
  `status` varchar(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider_images`
--

INSERT INTO `slider_images` (`id`, `image`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(10, 'slider/img/1735279927.jpeg', 1, 'active', '2024-12-27', '2024-12-27'),
(11, 'slider/img/1735279947.jpg', 2, 'active', '2024-12-27', '2024-12-27'),
(12, 'slider/img/1735279970.png', 3, 'active', '2024-12-27', '2024-12-27');

-- --------------------------------------------------------

--
-- Table structure for table `starline_bid_table`
--

CREATE TABLE `starline_bid_table` (
  `id` int(100) NOT NULL,
  `user_id` int(50) NOT NULL,
  `starline_id` int(11) NOT NULL,
  `starline_gtype_id` int(11) NOT NULL,
  `starline_bid_date` date NOT NULL,
  `digit` varchar(5) DEFAULT 'N/A',
  `panna` varchar(3) DEFAULT 'N/A',
  `amount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `starline_bid_table`
--

INSERT INTO `starline_bid_table` (`id`, `user_id`, `starline_id`, `starline_gtype_id`, `starline_bid_date`, `digit`, `panna`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2024-09-18', '2', 'N/A', 100, NULL, NULL),
(2, 1, 2, 4, '2024-09-19', 'N/A', '333', 200, NULL, NULL),
(3, 1, 3, 3, '2024-09-24', 'N/A', '344', 500, NULL, NULL),
(4, 1, 3, 1, '2024-09-24', '6', 'N/A', 200, NULL, NULL),
(5, 3, 3, 3, '2024-09-24', 'N/A', '344', 100, NULL, NULL),
(6, 1, 1, 1, '2024-09-30', '3', 'N/A', 500, NULL, NULL),
(7, 1, 1, 2, '2024-09-30', 'N/A', '120', 100, NULL, NULL),
(8, 3, 1, 2, '2024-09-30', 'N/A', '120', 500, NULL, NULL),
(9, 3, 1, 1, '2024-09-30', '3', 'N/A', 200, NULL, NULL),
(10, 1, 1, 4, '2024-10-17', 'N/A', '111', 123, NULL, NULL),
(11, 1, 1, 1, '2024-10-18', '2', 'N/A', 200, NULL, NULL),
(12, 1, 4, 3, '2024-10-21', 'N/A', '101', 900, NULL, NULL),
(13, 1, 4, 4, '2024-10-21', 'N/A', '111', 800, NULL, NULL),
(14, 46, 9, 2, '2025-01-09', 'N/A', '123', 10, NULL, NULL),
(15, 46, 8, 2, '2025-01-28', 'N/A', '190', 11, NULL, NULL),
(16, 46, 8, 3, '2025-01-28', 'N/A', '110', 10, NULL, NULL),
(17, 46, 8, 4, '2025-01-28', 'N/A', '111', 10, NULL, NULL),
(18, 46, 9, 1, '2025-01-28', '4', 'N/A', 10, NULL, NULL),
(19, 46, 8, 1, '2025-01-29', '7', 'N/A', 99, NULL, NULL),
(20, 46, 8, 2, '2025-01-29', 'N/A', '123', 100, NULL, NULL),
(21, 46, 8, 3, '2025-01-29', 'N/A', '122', 200, NULL, NULL),
(22, 46, 8, 4, '2025-01-29', 'N/A', '888', 11, NULL, NULL),
(23, 46, 2, 1, '2025-02-11', 'N/A', 'N/A', 10, NULL, NULL),
(24, 46, 2, 1, '2025-02-11', '4', 'N/A', 10, NULL, NULL),
(25, 46, 2, 1, '2025-02-11', 'N/A', '444', 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `starline_game_rates`
--

CREATE TABLE `starline_game_rates` (
  `id` int(20) NOT NULL,
  `single_digit_bid` int(10) DEFAULT NULL,
  `single_digit_win` int(10) DEFAULT NULL,
  `single_match_bid` int(10) DEFAULT NULL,
  `single_match_win` int(10) DEFAULT NULL,
  `double_match_bid` int(10) DEFAULT NULL,
  `double_match_win` int(10) DEFAULT NULL,
  `triple_match_bid` int(10) DEFAULT NULL,
  `triple_match_win` int(10) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `starline_game_rates`
--

INSERT INTO `starline_game_rates` (`id`, `single_digit_bid`, `single_digit_win`, `single_match_bid`, `single_match_win`, `double_match_bid`, `double_match_win`, `triple_match_bid`, `triple_match_win`, `created_at`, `updated_at`) VALUES
(1, 10, 90, 10, 1400, 10, 2800, 10, 8800, '2024-09-17', '2024-11-06');

-- --------------------------------------------------------

--
-- Table structure for table `starline_gtype`
--

CREATE TABLE `starline_gtype` (
  `id` int(6) NOT NULL,
  `gtype` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `starline_gtype`
--

INSERT INTO `starline_gtype` (`id`, `gtype`, `img`, `created_at`, `updated_at`) VALUES
(1, 'Single Digit', 'images/games/single.png', '2024-10-21 06:56:38', NULL),
(2, 'Single Panna', 'images/games/single_patti.png', '2024-10-21 06:58:00', NULL),
(3, 'Double Panna', 'images/games/double_patti.png', '2024-10-21 06:59:06', NULL),
(4, 'Tripple Panna', 'images/games/triple_patti.png', '2024-10-21 06:59:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `starline_market`
--

CREATE TABLE `starline_market` (
  `id` bigint(20) NOT NULL,
  `starline_name` varchar(255) NOT NULL,
  `starline_name_hindi` varchar(255) DEFAULT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `market_status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `starline_market`
--

INSERT INTO `starline_market` (`id`, `starline_name`, `starline_name_hindi`, `open_time`, `close_time`, `market_status`, `created_at`, `updated_at`) VALUES
(2, 'hfdkf', NULL, '00:00:00', '11:00:00', 'active', '2024-09-18 04:53:57', '2024-12-30 13:41:38'),
(8, 'rajdhani', NULL, '23:41:00', '23:54:00', 'active', '2024-12-30 13:39:57', '2025-01-09 17:09:11'),
(9, 'ram', NULL, '23:06:00', '23:22:00', 'active', '2025-01-09 17:07:03', '2025-01-09 17:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `starline_result`
--

CREATE TABLE `starline_result` (
  `id` int(10) NOT NULL,
  `starline_id` int(10) NOT NULL,
  `panna` int(3) NOT NULL,
  `digit` int(2) NOT NULL,
  `result_date` date NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `starline_result`
--

INSERT INTO `starline_result` (`id`, `starline_id`, `panna`, `digit`, `result_date`, `created_at`, `updated_at`) VALUES
(13, 2, 128, 1, '2025-01-30', '2025-01-30', NULL),
(14, 8, 0, 0, '2025-01-30', '2025-01-30', NULL),
(15, 9, 145, 0, '2025-01-30', '2025-01-30', NULL),
(16, 2, 444, 2, '2025-02-11', '2025-02-11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `starline_winners`
--

CREATE TABLE `starline_winners` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `bid_id` int(10) NOT NULL,
  `starline_id` int(10) NOT NULL,
  `bid_type` varchar(20) NOT NULL,
  `bid_point` int(10) NOT NULL,
  `winning_amount` int(10) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `starline_winners`
--

INSERT INTO `starline_winners` (`id`, `user_id`, `bid_id`, `starline_id`, `bid_type`, `bid_point`, `winning_amount`, `created_at`, `updated_at`) VALUES
(1, 46, 22, 8, 'panna', 111, 1540, '2025-01-29', '2025-01-29'),
(2, 46, 19, 8, 'digit', 7, 891, '2025-01-29', '2025-01-29'),
(3, 46, 19, 8, 'digit', 7, 891, '2025-01-29', '2025-01-29'),
(4, 46, 25, 2, 'panna', 444, 1400, '2025-02-11', '2025-02-11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `duplicate_password` varchar(255) DEFAULT 'some_default_value',
  `type` varchar(10) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `number`, `password`, `duplicate_password`, `type`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Admin', '7878488304', '$2y$12$EZD0WBDmTVtgCPim0V.cC./n76lUkyaAtroHxgPI2vQpIibmkEVGW', '1234', 'admin', 'IWeVuUr6DbGaNl7Mr60DxInm1LLcaz5hq9EX3clNl8e8wELqPdJH92jxsPTu', 'approved', '2024-09-06', '2024-10-15 07:00:12'),
(45, 'jay', '1234567890', '$2y$12$WfalsXVVhuFM.In1hT2E5.Uf75K02yJ/Pg.1C8yaHpfO.gjVKWg7i', '123456', 'user', NULL, 'unapproved', '2024-11-26', '2025-01-20 18:32:16'),
(46, 'ajay', '8764075504', '$2y$12$SE0rk6ubzSAJhPdFNA5ykOE0vZI9YMHY.taceFaRrjWYj42mvC4Bi', '123456', 'user', NULL, 'approved', '2024-11-27', '2025-02-01 15:35:33'),
(70, 'Surojit', '8910887617', '$2y$12$ISxF3gnEJMTidldPLFqSxuusEgjSqHE0dFH76yO3hTqcMPfY5GgAy', 'some_default_value', 'user', NULL, 'approved', '2025-02-25', '2025-02-25 19:57:14');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` int(15) NOT NULL DEFAULT 0,
  `totalDeposit` int(10) NOT NULL DEFAULT 0,
  `totalWithdrawal` int(10) NOT NULL DEFAULT 0,
  `totalWinning` int(10) NOT NULL DEFAULT 0,
  `deposite` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `totalDeposit`, `totalWithdrawal`, `totalWinning`, `deposite`, `created_at`, `updated_at`) VALUES
(34, 45, 855, 0, 0, 0, NULL, '2024-11-26 18:27:41', '2024-11-26 18:27:41'),
(35, 46, 2370, 0, 0, 0, NULL, '2024-11-27 17:33:44', '2024-11-27 17:33:44'),
(56, 70, 5, 0, 0, 0, NULL, '2025-02-25 19:56:35', '2025-02-25 19:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `winners`
--

CREATE TABLE `winners` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `bid_id` int(10) NOT NULL,
  `resultDate` date NOT NULL,
  `market_id` int(10) NOT NULL,
  `bid_type` varchar(20) NOT NULL,
  `session` varchar(10) DEFAULT NULL,
  `bid_point` varchar(20) NOT NULL,
  `winning_amount` int(10) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `winners`
--

INSERT INTO `winners` (`id`, `user_id`, `bid_id`, `resultDate`, `market_id`, `bid_type`, `session`, `bid_point`, `winning_amount`, `created_at`, `updated_at`) VALUES
(1, 46, 1, '0000-00-00', 15, 'single panna', 'open', '123', 1400, '2025-01-09', '2025-01-09'),
(2, 46, 3, '0000-00-00', 15, 'single panna', 'open', '123', 1400, '2025-01-09', '2025-01-09'),
(3, 46, 2, '0000-00-00', 15, 'single panna', 'close', '123', 1400, '2025-01-09', '2025-01-09'),
(4, 46, 7, '0000-00-00', 19, 'single panna', 'open', '123', 1400, '2025-01-09', '2025-01-09'),
(5, 46, 10, '0000-00-00', 19, 'single panna', 'open', '123', 1400, '2025-01-09', '2025-01-09'),
(6, 46, 8, '0000-00-00', 19, 'single panna', 'close', '123', 1400, '2025-01-09', '2025-01-09'),
(7, 46, 32, '0000-00-00', 17, 'full_sangam', NULL, '222-222', 120000, '2025-01-10', '2025-01-10'),
(8, 46, 32, '2025-01-10', 17, 'full_sangam', NULL, '222-222', 120000, '2025-01-10', '2025-01-10'),
(9, 45, 64, '2025-01-11', 15, 'single panna', 'open', '123', 1400, '2025-01-11', '2025-01-11'),
(10, 45, 65, '2025-01-11', 15, 'single panna', 'close', '125', 1400, '2025-01-11', '2025-01-11'),
(14, 45, 91, '2025-01-13', 19, 'full_sangam', NULL, '123-125', 120000, '2025-01-13', '2025-01-13'),
(15, 45, 99, '2025-01-13', 19, 'jodi', NULL, '68', 1000, '2025-01-13', '2025-01-13'),
(16, 45, 96, '2025-01-13', 15, 'digit', 'open', '3', 120, '2025-01-13', '2025-01-13'),
(17, 46, 101, '2025-01-16', 15, 'digit', 'open', '3', 100, '2025-01-16', '2025-01-16'),
(18, 46, 101, '2025-01-16', 15, 'digit', 'open', '3', 100, '2025-01-16', '2025-01-16'),
(19, 45, 110, '2025-01-16', 16, 'digit', 'open', '3', 100, '2025-01-16', '2025-01-16'),
(20, 45, 1, '2025-01-16', 15, 'digit', 'open', '3', 100, '2025-01-16', '2025-01-16'),
(21, 45, 9, '2025-01-16', 17, 'digit', 'open', '1', 100, '2025-01-16', '2025-01-16'),
(22, 45, 10, '2025-01-16', 17, 'digit', 'close', '2', 100, '2025-01-16', '2025-01-16'),
(23, 45, 12, '2025-01-16', 17, 'single panna', 'close', '129', 1400, '2025-01-16', '2025-01-16'),
(24, 46, 16, '2025-01-17', 19, 'digit', 'open', '3', 550, '2025-01-17', '2025-01-17'),
(25, 46, 33, '2025-01-17', 25, 'digit', 'open', '3', 100, '2025-01-17', '2025-01-17'),
(26, 46, 33, '2025-01-17', 25, 'digit', 'open', '3', 100, '2025-01-17', '2025-01-17'),
(27, 46, 42, '2025-01-17', 26, 'digit', 'open', '3', 120, '2025-01-17', '2025-01-17'),
(28, 45, 45, '2025-01-17', 26, 'digit', 'open', '3', 330, '2025-01-17', '2025-01-17'),
(29, 46, 43, '2025-01-17', 26, 'digit', 'close', '7', 100, '2025-01-17', '2025-01-17'),
(30, 46, 44, '2025-01-17', 26, 'jodi', NULL, '37', 1300, '2025-01-17', '2025-01-17'),
(31, 45, 46, '2025-01-17', 26, 'digit', 'close', '7', 120, '2025-01-17', '2025-01-17'),
(32, 45, 47, '2025-01-17', 26, 'jodi', NULL, '37', 2100, '2025-01-17', '2025-01-17'),
(33, 45, 53, '2025-01-18', 26, 'jodi', NULL, '57', 1000, '2025-01-18', '2025-01-18'),
(34, 45, 17, '2025-01-18', 26, 'jodi', NULL, '12', 1000, '2025-01-18', '2025-01-18'),
(35, 45, 30, '2025-01-19', 21, 'digit', 'open', '1', 100, '2025-01-19', '2025-01-19'),
(36, 45, 30, '2025-01-19', 21, 'digit', 'open', '1', 100, '2025-01-19', '2025-01-19'),
(37, 46, 1, '2025-01-20', 23, 'digit', 'open', '8', 100, '2025-01-20', '2025-01-20'),
(38, 46, 3, '2025-01-20', 23, 'single panna', 'open', '125', 1400, '2025-01-20', '2025-01-20'),
(39, 46, 4, '2025-01-20', 23, 'single panna', 'close', '130', 1400, '2025-01-20', '2025-01-20'),
(40, 46, 5, '2025-01-20', 23, 'jodi', NULL, '84', 1000, '2025-01-20', '2025-01-20'),
(41, 46, 1, '2025-01-20', 23, 'digit', 'open', '8', 100, '2025-01-20', '2025-01-20'),
(42, 46, 3, '2025-01-20', 23, 'single panna', 'open', '125', 1400, '2025-01-20', '2025-01-20'),
(43, 46, 1, '2025-01-20', 23, 'digit', 'open', '8', 100, '2025-01-20', '2025-01-20'),
(44, 46, 3, '2025-01-20', 23, 'single panna', 'open', '125', 1400, '2025-01-20', '2025-01-20'),
(45, 46, 15, '2025-01-20', 26, 'single panna', 'open', '134', 2380, '2025-01-20', '2025-01-20'),
(46, 46, 2, '2025-01-20', 26, 'single panna', 'open', '230', 1400, '2025-01-20', '2025-01-20'),
(47, 46, 2, '2025-01-20', 26, 'single panna', 'open', '230', 1400, '2025-01-20', '2025-01-20'),
(48, 46, 11, '2025-01-28', 26, 'single panna', 'open', '125', 1820, '2025-01-28', '2025-01-28'),
(49, 66, 17, '2025-01-30', 26, 'digit', 'open', '1', 100, '2025-01-30', '2025-01-30'),
(50, 66, 20, '2025-01-30', 26, 'single panna', 'open', '128', 1400, '2025-01-30', '2025-01-30'),
(51, 66, 18, '2025-01-30', 26, 'digit', 'close', '2', 100, '2025-01-30', '2025-01-30'),
(52, 66, 19, '2025-01-30', 26, 'jodi', NULL, '12', 1000, '2025-01-30', '2025-01-30'),
(53, 66, 21, '2025-01-30', 26, 'single panna', 'close', '129', 1400, '2025-01-30', '2025-01-30');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal`
--

CREATE TABLE `withdrawal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payout` varchar(255) NOT NULL,
  `number` varchar(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `withdrawal_date` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawal`
--

INSERT INTO `withdrawal` (`id`, `user_id`, `amount`, `payout`, `number`, `status`, `withdrawal_date`, `updated_at`) VALUES
(16, 45, 14830.00, 'Paytm', '9876543222', 'pending', '2024-11-30 08:25:09', NULL),
(27, 45, 377251.00, 'Paytm', '7220881980', 'pending', '2025-01-17 12:14:43', NULL),
(28, 45, 1000.00, 'PhonePe', '7732914129', 'pending', '2025-01-17 22:43:19', NULL),
(30, 45, 1000.00, 'GooglePay', '1234567890', 'approved', '2025-01-18 15:29:03', NULL),
(31, 45, 51.00, 'Paytm', '8764075504', 'pending', '2025-01-18 21:18:48', NULL),
(32, 45, 53.00, 'Paytm', '8764075504', 'pending', '2025-01-18 21:26:51', NULL),
(33, 45, 500.00, 'PhonePe', '6375412552', 'rejected', '2025-01-19 22:58:45', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bid_table`
--
ALTER TABLE `bid_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `deposite_table`
--
ALTER TABLE `deposite_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Desawar_text`
--
ALTER TABLE `Desawar_text`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `full_sangam`
--
ALTER TABLE `full_sangam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galidesawar_bid_table`
--
ALTER TABLE `galidesawar_bid_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galidesawar_game_rates`
--
ALTER TABLE `galidesawar_game_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galidesawar_gtype`
--
ALTER TABLE `galidesawar_gtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galidesawar_market`
--
ALTER TABLE `galidesawar_market`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galidesawar_result`
--
ALTER TABLE `galidesawar_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galidesawar_winners`
--
ALTER TABLE `galidesawar_winners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galidesawar_winners_guess`
--
ALTER TABLE `galidesawar_winners_guess`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `games_user_id_foreign` (`user_id`);

--
-- Indexes for table `game_rates`
--
ALTER TABLE `game_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gtype`
--
ALTER TABLE `gtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market`
--
ALTER TABLE `market`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_results`
--
ALTER TABLE `market_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_management`
--
ALTER TABLE `notice_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `set_amount`
--
ALTER TABLE `set_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider_images`
--
ALTER TABLE `slider_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `starline_bid_table`
--
ALTER TABLE `starline_bid_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `starline_game_rates`
--
ALTER TABLE `starline_game_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `starline_gtype`
--
ALTER TABLE `starline_gtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `starline_market`
--
ALTER TABLE `starline_market`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `starline_result`
--
ALTER TABLE `starline_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `starline_winners`
--
ALTER TABLE `starline_winners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_number_unique` (`number`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_foreign` (`user_id`);

--
-- Indexes for table `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawal_user_id_index` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bid_table`
--
ALTER TABLE `bid_table`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `deposite_table`
--
ALTER TABLE `deposite_table`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `Desawar_text`
--
ALTER TABLE `Desawar_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `full_sangam`
--
ALTER TABLE `full_sangam`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galidesawar_bid_table`
--
ALTER TABLE `galidesawar_bid_table`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `galidesawar_game_rates`
--
ALTER TABLE `galidesawar_game_rates`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `galidesawar_gtype`
--
ALTER TABLE `galidesawar_gtype`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `galidesawar_market`
--
ALTER TABLE `galidesawar_market`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `galidesawar_result`
--
ALTER TABLE `galidesawar_result`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `galidesawar_winners`
--
ALTER TABLE `galidesawar_winners`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `galidesawar_winners_guess`
--
ALTER TABLE `galidesawar_winners_guess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game_rates`
--
ALTER TABLE `game_rates`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gtype`
--
ALTER TABLE `gtype`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market`
--
ALTER TABLE `market`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `market_results`
--
ALTER TABLE `market_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notice_management`
--
ALTER TABLE `notice_management`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `set_amount`
--
ALTER TABLE `set_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `slider_images`
--
ALTER TABLE `slider_images`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `starline_bid_table`
--
ALTER TABLE `starline_bid_table`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `starline_game_rates`
--
ALTER TABLE `starline_game_rates`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `starline_gtype`
--
ALTER TABLE `starline_gtype`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `starline_market`
--
ALTER TABLE `starline_market`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `starline_result`
--
ALTER TABLE `starline_result`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `starline_winners`
--
ALTER TABLE `starline_winners`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `winners`
--
ALTER TABLE `winners`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `withdrawal`
--
ALTER TABLE `withdrawal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD CONSTRAINT `withdrawal_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
