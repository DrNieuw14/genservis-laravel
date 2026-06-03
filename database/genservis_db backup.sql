-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 03, 2026 at 10:11 AM
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
-- Database: `genservis_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `action`, `description`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 3, 'Users', 'Approved User', 'Approved user: Marnel KuyaBunso', '127.0.0.1', '2026-06-01 00:22:15', '2026-06-01 00:22:15'),
(2, 4, 'Leave', 'Submitted Leave', 'Submitted leave request from aldrin', '127.0.0.1', '2026-06-01 01:31:46', '2026-06-01 01:31:46'),
(3, 3, 'Leave', 'Approved Leave', 'Approved leave request of aldrin', '127.0.0.1', '2026-06-01 01:37:06', '2026-06-01 01:37:06'),
(4, 4, 'Leave', 'Submitted Leave', 'Submitted leave request from aldrin', '127.0.0.1', '2026-06-01 01:42:41', '2026-06-01 01:42:41'),
(5, 3, 'Leave', 'Rejected Leave', 'Rejected leave request of aldrin', '127.0.0.1', '2026-06-01 01:49:43', '2026-06-01 01:49:43'),
(6, 4, 'Leave', 'Submitted Leave', 'Submitted leave request from aldrin', '127.0.0.1', '2026-06-01 17:25:26', '2026-06-01 17:25:26'),
(7, 3, 'Leave', 'Approved Leave', 'Approved leave request of aldrin', '127.0.0.1', '2026-06-01 17:26:19', '2026-06-01 17:26:19');

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Maintenance Materials', NULL, '2026-06-02 23:52:41'),
(6, 'Office Supplies', '2026-05-18 16:43:39', '2026-05-18 16:46:31'),
(7, 'ICT Equipment', '2026-05-18 22:40:38', '2026-05-18 22:40:38'),
(8, 'Electrical Materials', '2026-05-25 00:17:15', '2026-06-02 23:51:22'),
(13, 'Imported Inventory', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(14, 'Safety Equipment', '2026-06-02 23:51:39', '2026-06-02 23:51:39'),
(15, 'Tools & Instruments', '2026-06-02 23:52:02', '2026-06-02 23:52:02'),
(16, 'Laboratory Equipment', '2026-06-02 23:52:19', '2026-06-02 23:52:19'),
(17, 'Consumables', '2026-06-02 23:52:27', '2026-06-02 23:52:27'),
(18, 'Sport Equipment', '2026-06-02 23:52:51', '2026-06-02 23:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `department_code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Department of Industrial and Information Technology', 'DIIT', 'Information and Computer Department', '2026-05-24 17:32:52', '2026-05-24 17:32:52'),
(2, 'Department of Management', 'DM', 'Business and Management', '2026-05-24 17:33:17', '2026-05-24 17:33:17'),
(3, 'Central Stockroom', 'SR-Main', 'Stock Room in the main building', '2026-05-24 23:22:47', '2026-05-24 23:22:47'),
(6, 'General Inventory', 'GEN', 'Auto-created by inventory import', '2026-06-02 22:33:01', '2026-06-02 22:33:01');

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
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `movement_type` enum('restock','request','damage','transfer','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_movements`
--

INSERT INTO `inventory_movements` (`id`, `material_id`, `movement_type`, `quantity`, `previous_stock`, `new_stock`, `remarks`, `performed_by`, `created_at`, `updated_at`) VALUES
(1, 8, 'restock', 3, 22, 25, 'by LGU', 3, '2026-05-27 17:00:02', '2026-05-27 17:00:02'),
(2, 4, 'request', 5, 5, 0, 'Request #: MR-2026-0024 | Requested by: aldrin', 3, '2026-05-28 00:16:55', '2026-05-28 00:16:55'),
(3, 4, 'restock', 10, 0, 10, '5 watts', 3, '2026-05-28 00:19:53', '2026-05-28 00:19:53'),
(4, 4, 'request', 5, 10, 5, 'Request #: MR-2026-0025 | Requested by: aldrin', 3, '2026-05-28 00:22:03', '2026-05-28 00:22:03'),
(5, 7, 'request', 1, 4, 3, 'Request #: MR-2026-0032 | Requested by: aldrin', 3, '2026-05-31 23:52:25', '2026-05-31 23:52:25'),
(6, 8, 'restock', 5, 4, 9, 'Red Marker', 3, '2026-06-02 01:04:35', '2026-06-02 01:04:35'),
(7, 7, 'restock', 10, 3, 13, '5 meters', 3, '2026-06-02 01:20:20', '2026-06-02 01:20:20'),
(8, 8, 'restock', 3, 9, 12, 'Remarks Supplier', 3, '2026-06-02 01:31:43', '2026-06-02 01:31:43'),
(9, 8, 'request', 3, 12, 9, 'Request #: MR-2026-0033 | Requested by: aldrin', 3, '2026-06-02 01:32:56', '2026-06-02 01:32:56'),
(10, 8, 'restock', 6, 9, 15, 'Markers again', 3, '2026-06-02 17:49:34', '2026-06-02 17:49:34'),
(11, 8, 'request', 3, 15, 12, 'Request #: MR-2026-0034 | Requested by: aldrin', 3, '2026-06-02 18:29:14', '2026-06-02 18:29:14'),
(12, 11, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(13, 12, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(14, 13, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(15, 14, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(16, 15, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(17, 16, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(18, 17, 'restock', 78, 0, 78, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(19, 18, 'restock', 46, 0, 46, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(20, 19, 'restock', 29, 0, 29, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(21, 20, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(22, 21, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(23, 22, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(24, 23, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(25, 24, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(26, 25, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(27, 26, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(28, 27, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(29, 28, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(30, 29, 'restock', 17, 0, 17, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(31, 30, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(32, 31, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(33, 32, 'restock', 16, 0, 16, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(34, 33, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(35, 34, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(36, 35, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(37, 36, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(38, 37, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(39, 38, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(40, 39, 'restock', 11, 0, 11, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(41, 40, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(42, 41, 'restock', 32, 0, 32, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(43, 42, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(44, 43, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(45, 44, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(46, 45, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(47, 46, 'restock', 24, 0, 24, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(48, 47, 'restock', 17, 0, 17, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(49, 48, 'restock', 19, 0, 19, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(50, 49, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(51, 50, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(52, 51, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(53, 52, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(54, 53, 'restock', 13, 0, 13, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(55, 54, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(56, 55, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(57, 56, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(58, 57, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(59, 58, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(60, 59, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(61, 60, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(62, 61, 'restock', 23, 0, 23, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(63, 62, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(64, 63, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(65, 64, 'restock', 73, 0, 73, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(66, 65, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(67, 66, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(68, 67, 'restock', 12, 0, 12, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(69, 68, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(70, 69, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(71, 70, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(72, 71, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(73, 72, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(74, 73, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(75, 74, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(76, 75, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(77, 76, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(78, 77, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(79, 78, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(80, 79, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(81, 80, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(82, 81, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(83, 82, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(84, 83, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(85, 84, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(86, 85, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(87, 86, 'restock', 11, 0, 11, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(88, 87, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(89, 88, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(90, 89, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(91, 90, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(92, 91, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(93, 92, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(94, 93, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(95, 94, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(96, 95, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(97, 96, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(98, 97, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(99, 98, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(100, 99, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(101, 100, 'restock', 20, 0, 20, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(102, 101, 'restock', 19, 0, 19, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(103, 102, 'restock', 86, 0, 86, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(104, 103, 'restock', 39, 0, 39, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(105, 104, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(106, 105, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(107, 106, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(108, 107, 'restock', 18, 0, 18, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(109, 108, 'restock', 61, 0, 61, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(110, 109, 'restock', 173, 0, 173, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(111, 110, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(112, 111, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(113, 112, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(114, 113, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(115, 114, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(116, 115, 'restock', 31, 0, 31, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(117, 116, 'restock', 25, 0, 25, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(118, 117, 'restock', 40, 0, 40, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(119, 118, 'restock', 34, 0, 34, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(120, 119, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(121, 120, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(122, 121, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(123, 122, 'restock', 1000, 0, 1000, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(124, 123, 'restock', 98, 0, 98, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(125, 124, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(126, 125, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(127, 126, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(128, 127, 'restock', 12, 0, 12, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(129, 128, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(130, 129, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(131, 130, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(132, 131, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(133, 132, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(134, 133, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(135, 134, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(136, 135, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(137, 136, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(138, 137, 'restock', 42, 0, 42, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(139, 138, 'restock', 49, 0, 49, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(140, 139, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(141, 140, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(142, 141, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(143, 142, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(144, 143, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(145, 144, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(146, 145, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(147, 146, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(148, 147, 'restock', 174, 0, 174, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(149, 148, 'restock', 196, 0, 196, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(150, 149, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(151, 150, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(152, 151, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(153, 152, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(154, 153, 'restock', 14, 0, 14, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(155, 154, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(156, 155, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(157, 156, 'restock', 106, 0, 106, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(158, 157, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(159, 158, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(160, 159, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(161, 160, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(162, 161, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(163, 162, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(164, 163, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(165, 164, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(166, 165, 'restock', 20, 0, 20, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(167, 166, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(168, 167, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(169, 168, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(170, 169, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(171, 170, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(172, 171, 'restock', 99, 0, 99, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(173, 172, 'restock', 15, 0, 15, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(174, 173, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(175, 174, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(176, 175, 'restock', 15, 0, 15, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(177, 176, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(178, 177, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(179, 178, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(180, 179, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(181, 180, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(182, 181, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(183, 182, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(184, 183, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(185, 184, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(186, 185, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(187, 186, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(188, 187, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(189, 188, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(190, 189, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(191, 190, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(192, 191, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(193, 192, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(194, 193, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(195, 194, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(196, 195, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(197, 196, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(198, 197, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(199, 198, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(200, 199, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(201, 200, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(202, 201, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(203, 202, 'restock', 26, 0, 26, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(204, 203, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(205, 204, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(206, 205, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(207, 206, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(208, 207, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(209, 208, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(210, 209, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(211, 210, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(212, 211, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(213, 212, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(214, 213, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(215, 214, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(216, 215, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(217, 216, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(218, 217, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(219, 218, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(220, 219, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(221, 220, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(222, 221, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(223, 222, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(224, 223, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(225, 224, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(226, 225, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(227, 226, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(228, 227, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(229, 228, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(230, 229, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02');

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
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personnel_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `personnel_id`, `reason`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'test', '2026-06-03', '2026-06-03', 'Approved', '2026-06-01 01:31:46', '2026-06-01 01:37:06'),
(2, 1, 'testing again', '2026-06-07', '2026-06-09', 'Rejected', '2026-06-01 01:42:41', '2026-06-01 01:49:43'),
(3, 1, 'weee', '2026-06-02', '2026-06-02', 'Approved', '2026-06-01 17:25:26', '2026-06-01 17:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `threshold` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `quantity`, `category_id`, `department_id`, `unit_id`, `created_by`, `created_at`, `updated_at`, `threshold`) VALUES
(1, 'A4', 3, 6, NULL, 3, 3, '2026-05-18 22:35:00', '2026-05-24 19:36:08', 5),
(2, 'Photo Paper', 8, 6, NULL, 1, 3, '2026-05-18 22:42:01', '2026-05-27 19:09:58', 5),
(3, 'Short Bond Paper v2', 10, 6, 1, 3, 3, '2026-05-24 23:23:21', '2026-05-24 23:53:21', 5),
(4, 'LED Bulbs', 5, 8, 3, 1, 3, '2026-05-25 00:17:54', '2026-05-28 00:22:03', 5),
(5, 'Safety Breaker', 4, 8, 3, 1, 3, '2026-05-25 00:18:27', '2026-05-25 00:18:27', 5),
(6, 'Batteries AAA', 1, 8, 3, 1, 3, '2026-05-25 00:19:08', '2026-05-28 00:01:59', 5),
(7, 'HDMI Cable', 13, 7, 3, 1, 3, '2026-05-25 00:19:51', '2026-06-02 01:20:20', 5),
(8, 'Markers', 12, 6, 1, 1, 3, '2026-05-25 00:20:24', '2026-06-02 18:29:13', 5),
(11, 'Biglite Fire Emergency Evacuation Sign (Box)', 2, 13, 6, 2, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(12, 'Royu Electrical Devices (Box)', 3, 13, 6, 2, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(13, 'Firefly Bulb (Box)', 4, 13, 6, 2, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(14, 'Akari LED Solar (pcs)', 3, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(15, 'Delta Pre-heat Start Ballast (pcs)', 6, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(16, 'ETS Smoke Alarm (pcs)', 2, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(17, 'GES LED Bulb MECQ (pcs)', 78, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(18, 'CONCATA LED Bulb (pcs)', 46, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(19, 'GES LED Bulb (pcs)', 29, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(20, 'ENERGIZER Battery AAA (pack)', 5, 13, 6, 5, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(21, 'ENERGIZER Battery AA (box)', 1, 13, 6, 2, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(22, 'EVEREADY Battery 9B (box)', 1, 13, 6, 2, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(23, 'Alarm Buttons (pcs)', 3, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(24, 'ROYU Safety Breaker 9 (box)', 1, 13, 6, 2, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(25, 'ROYU Outlet (pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(26, 'FIREFLY Bulb 15w (pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(27, 'HIGH SPEED 3.0 USB Cable (pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(28, 'DELTA Pre-heat Start Ballast (box)', 4, 13, 6, 2, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(29, '2A Quartz Clock (pcs)', 17, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(30, 'Best Guard Riveter (pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(31, 'MGK Binder Clips (box)', 1, 13, 6, 2, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(32, 'Firefly Security Light (pcs)', 16, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(33, 'Veco Record Book (pcs)', 3, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(34, 'NEC Type 32 Telephone Set (box)', 4, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(35, 'Iron Grip Wall Mount (pcs)', 9, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(36, 'Stanley Door Stopper (box)', 4, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(37, 'Power House Door knob (pcs)', 2, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(38, 'KJJ Vacuum (pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(39, 'Elmer’s Glue (pcs)', 11, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(40, 'Book End (pcs)', 3, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(41, 'Reston Safety Googles (pcs)', 32, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(42, 'Joy Binder Clips (box)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(43, 'Excel Binder Clips (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(44, 'JDWJ Round Clips 75mm (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(45, 'Joy Sharpener (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(46, 'A4Tech Keyboard KRS5 (pcs)', 24, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(47, 'A4Tech Keyboard KRS85 (pcs)', 17, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(48, 'A4Tech Keyboard KRS83 (pcs)', 19, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(49, 'ACER Keyboard (pcs)', 3, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(50, 'HDMI Cable (pcs)', 6, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(51, 'Adaptor (pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(52, 'JYC Crimping Tool (pcs)', 4, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(53, 'Germany Crimping Tool (pcs)', 13, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(54, 'Suntech USB Type B Cable (box)', 9, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(55, 'Genius Keyboard KB-110 (pcs)', 8, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(56, 'Intex Keyboard IT-2014B (pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(57, 'TV Wall Mount (pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(58, 'DELL Keyboard KM816(pcs)', 1, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(59, 'HP Toner Cartridge W2091A (pcs)', 6, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(60, 'AWP UPS AIDE 400 -1000 VA (pcs)', 6, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(61, 'RAPOO USB Stereo Headset (pcs)', 23, 13, 6, 4, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(62, 'WIN Amplified Multimedia Hi-Fi Speaker (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(63, 'RJ45 (pcs)', 7, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(64, 'CD Case (pcs)', 73, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(65, 'HP-RW (pack)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(66, 'WD Tool Pouch (pcs)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(67, 'AMASCO LED Lamp (pcs)', 12, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(68, 'Proteger LED Water Proof Search Light (pcs)', 2, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(69, 'KOTEN Safety Breaker (box)', 2, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(70, 'X-BALOG Head Lamp (pcs)', 4, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(71, 'Portable Flashlight (pcs)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(72, 'HBW Permanent Marker Ink Refill (pcs)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(73, 'HBW Red Marker (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(74, 'Pixon Highlighter (pack)', 5, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(75, 'FOXCEL Heavy Duty Staples (pack)', 5, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(76, 'Printo Stapler Remover (pcs)', 7, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(77, 'LOVEIN Gel Ink Pen (pack)', 2, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(78, 'PRIMAX Liquid Ink Freeliner (pcs)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(79, 'DELI Permanent Marker (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(80, 'PILOT Permanent Marker (pcs)', 6, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(81, 'HB Pencil (pack)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(82, 'Glue Stick (pack)', 2, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(83, 'MONGOL Pencil (box)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(84, 'HBW Ballpen (box)', 5, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(85, 'PILOT Whiteboard Marker Refill Ink (pcs)', 4, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(86, 'EXCEL Highlighter (box)', 11, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(87, 'Dustless Chalk (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(88, 'Post It Notepads (pack)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(89, 'JOY Correction Tape (pcs)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(90, 'JOY Push Pin (pack)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(91, 'SCOTCH Magic Tape (pcs)', 1, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(92, 'POINTER Index Tabs (box)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(93, 'DO IT Luggage Tags (pcs)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(94, 'PRINTO Ruler (pcs)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(95, 'EXCELLENT Board Eraser (pcs)', 6, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(96, 'JDWJ 50mm Round Clips (box)', 2, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(97, 'EXCELLENT Board Eraser (box)', 3, 13, 6, 6, 3, '2026-06-02 22:33:01', '2026-06-02 22:33:01', 5),
(98, 'LCT Bulldog Clip (box)', 7, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(99, 'JDWJ 22mm Round Clips (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(100, 'BOSTON Staples (pack)', 20, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(101, 'ETONA Staples (pack)', 19, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(102, 'JOY Staples (pack)', 86, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(103, 'WORX Long Board Paper (pack)', 39, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(104, 'AGPAPHOTO A4 Laminating Film (pack)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(105, 'KING Legal Size L-Type Folder (pack)', 9, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(106, 'LUCKY File Bag (pcs)', 9, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(107, 'WORX 8.5x13 Specialty Paper (pack)', 18, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(108, 'WORX A4,90gsm Specialty Paper (pack)', 61, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(109, 'WORX A4,200gsm Specialty Paper (pack)', 173, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(110, 'JOJO Photopaper 180g/m2 (pack)', 4, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(111, 'A4 Document Frame (pcs)', 5, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(112, 'Brown Envelope Short (pcs)', 10, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(113, '8.50x11 Certificate Holder (pcs)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(114, 'Binder Envelope (pcs)', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(115, 'SUPERFAX A4 Sensitized Film (pack)', 31, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(116, 'QUAFF A4 Laminating Film (pack)', 25, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(117, 'SUKI/GENTLE PRINCE DTR Card (pack)', 40, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(118, 'KING FILES Clear Book (pcs)', 34, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(119, 'File Folder w/index tabs (pack)', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(120, 'ADVENTURER BlueCard Case (pcs)', 5, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(121, 'EXCEL Glossy Sticker Paper(bundle)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(122, 'DTR Card (pcs)', 1000, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(123, 'IMARI 8.5x11 Glossy Paper (pack)', 98, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(124, 'QUAFF A4 Calling Card Paper (pack)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(125, 'QUAFF A4 Glossy Photo Sticker (pack)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(126, 'Sliding Folder (pack)', 10, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(127, 'Stapler w/remover (pcs)', 12, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(128, 'PILOT Broad Super Color Marker (pack)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(129, 'DONG-A Gel Ink (pack)', 9, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(130, 'PRINTO Stamp Pad Ink (pcs)', 8, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(131, 'EXCEL Chisel Point #10 Staples (pack)', 9, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(132, 'ASIAN 5x8 Index Card (pack)', 7, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(133, 'ASIAN 3x5 Index Card (pack)', 5, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(134, 'ZHIJIN Enice Ballpen (pack)', 8, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(135, 'FLEX OFFICE Ballpen (pack)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(136, 'HBW Whiteboard Marker Ink Refill (pcs)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(137, 'NINGTAI Card  Case (pack)', 42, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(138, 'QUAFF Calling Card (pack)', 49, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(139, 'TOLSEN Air Tools Kit (set)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(140, 'MAKITA Jigsaw (set)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(141, 'AVR (pcs)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(142, 'Mega Phone (pcs)', 5, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(143, 'STANDARD Duct Fan (box)', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(144, 'IMARI Glossy Paper A3 (bundle)', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(145, 'KODAK Photo Glossy Paper for Inkjet Prints (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(146, 'OFFICEMAX Coin Envelope 8 ½ size (box)', 10, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(147, 'P/X Expanding Folder Green (pcs)', 174, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(148, 'P/X Expanding Folder Red (pcs)', 196, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(149, 'P/X Expanding Folder Yellow (pcs)', 10, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(150, 'P/X Expanding Folder Orange(pcs)', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(151, 'P/X Expanding Folder Blue (pcs)', 10, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(152, 'P/X Expanding Folder Violet (pcs)', 10, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(153, 'ADVENTURER Certificate Holder Red (pcs)', 14, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(154, 'Long Envelope (Box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(155, 'QUAFF Comb Binding Machine (pcs)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(156, 'ADVENTURER Certificate Holder Blue (pcs)', 106, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(157, 'UPRIGHT Laminator Mini (pcs)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(158, 'MIYAMI Laminator Big (pcs)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(159, 'STAR Money Detector (pcs)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(160, 'AOC Monitor (pcs)', 9, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(161, 'D-Ring Binder White (pcs)', 10, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(162, 'D-Ring Binder Red (pcs)', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(163, 'D-Ring Binder Blue (pcs)', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(164, 'EPSON Ink (For Printer) Black (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(165, 'Diploma Holder (pcs)', 20, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(166, 'KRAFT Document Envelopes A4 (box)', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(167, 'EPSON Ink (For Printer) Cyan (box)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(168, 'CLASSIC White Envelope size 10 (box)', 7, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(169, 'PHOENIX Expanding Envelope (box)', 8, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(170, 'Document File (bundle)', 4, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(171, 'Multi Copy Paper A4 (reams)', 99, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(172, 'Avant Garde Quick Ladder AG53009 8m', 15, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(173, 'Leap PQ9907S Digital Chess Timer', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(174, 'Verza Chess Clock', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(175, 'Competition Ball (sepak takraw)', 15, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(176, 'Table Tennis Net', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(177, 'Double Happiness Table Tennis Net', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(178, 'Table Tennis Racket', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(179, 'Aristo Stopwatch Series', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(180, 'Super K SK6128 Professional Quartz Timer', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(181, 'Orange Traffic Cone', 7, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(182, 'Chessboard Set', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(183, 'Cima Manual Scoreboard', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(184, 'Digital Scoreboard', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(185, 'Stix Headgear (blue)', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(186, 'Stix Headgear (red)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(187, 'Swimfit Swimming Goggles', 8, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(188, 'Boxing Gloves Pair (blue)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(189, 'Boxing Gloves Pair (red)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(190, 'Kix Taekwondo Headgear (red)', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(191, 'Kix Taekwondo Headgear (blue)', 5, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(192, 'Guide Overgrip', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(193, 'Kix Taekwondo Shin Guard Pair (red)', 6, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(194, 'Kix Taekwondo Shin Guard Pair (blue)', 8, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(195, 'Clever Shin and Instep Guard Pair (red)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(196, 'Taekwondo Gloves Pair (white)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(197, 'Excalibur Boxing Helmet (black)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(198, 'Boxing Headgear (black)', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(199, 'Boxing Headgear (red)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(200, 'Chess Mat Set', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(201, 'Srabble Wooden Board Set', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(202, 'Kix Taekwondo Body Armor', 26, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(203, 'Kix Taekwondo Kick Pad', 9, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(204, 'Stix Groin Guard (male)', 4, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(205, 'Kix Groin Guard (male)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(206, 'Stix Groin Guard (female)', 4, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(207, 'Kix Groin Guard (female)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(208, 'Kang O Fitness Boxing Hand Wraps 3m (black)', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(209, 'Kang O Fitness Boxing Hand Wraps 3m (red)', 4, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(210, 'Ciever Master Boxing Hand Wraps', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(211, 'Super K Coaching Board', 5, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(212, 'Super K Frisbee (orange)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(213, 'Super K Frisbee (dark green)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(214, 'Super K Frisbee (green)', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(215, 'Classic Fox40 Referee Whistle (dark blue)', 4, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(216, 'Classic Fox40 RefereeWhistle (black)', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(217, 'Elite Fitness Yoga Mat', 5, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(218, 'Pogo Stick', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(219, 'Mizuno Tennis Racket', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(220, 'Speedrino Tennis Racket', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(221, 'Yonex Badminton Set', 2, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(222, 'GTO Tennis Net', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(223, 'Table Tennis Net and Post Set', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(224, 'Stix Arnis Hand Gloves Pair', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(225, 'Super K Table Tennis Net and Post Set', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(226, 'Ys-902 Digital Chess timer', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(227, 'Kix Taekwondo Kick Shield', 3, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(228, 'Molten B64500 Basketball', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5),
(229, 'Accel 7500 Voleyballl', 1, 13, 6, 6, 3, '2026-06-02 22:33:02', '2026-06-02 22:33:02', 5);

-- --------------------------------------------------------

--
-- Table structure for table `material_logs`
--

CREATE TABLE `material_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_logs`
--

INSERT INTO `material_logs` (`id`, `material_id`, `user_id`, `action`, `quantity`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'stock_in', 5, 'Initial stock added', '2026-05-18 22:35:00', '2026-05-18 22:35:00'),
(2, 2, 3, 'stock_in', 5, 'Initial stock added', '2026-05-18 22:42:01', '2026-05-18 22:42:01'),
(3, 1, 3, 'deducted', 1, 'Request #: MR-2026-0001 | Requested by: aldrin | Approved by: supervisor', '2026-05-18 22:50:51', '2026-05-18 22:50:51'),
(4, 2, 3, 'restock', 5, 'for 1 week', '2026-05-18 23:19:04', '2026-05-18 23:19:04'),
(5, 1, 3, 'deducted', 1, 'Request #: MR-2026-0005 | Requested by: aldrin | Approved by: supervisor', '2026-05-24 19:36:08', '2026-05-24 19:36:08'),
(6, 2, 3, 'deducted', 1, 'Request #: MR-2026-0015 | Requested by: aldrin | Approved by: supervisor', '2026-05-24 23:20:15', '2026-05-24 23:20:15'),
(7, 3, 3, 'stock_in', 5, 'Initial stock added', '2026-05-24 23:23:21', '2026-05-24 23:23:21'),
(8, 4, 3, 'stock_in', 10, 'Initial stock added', '2026-05-25 00:17:54', '2026-05-25 00:17:54'),
(9, 5, 3, 'stock_in', 4, 'Initial stock added', '2026-05-25 00:18:27', '2026-05-25 00:18:27'),
(10, 6, 3, 'stock_in', 15, 'Initial stock added', '2026-05-25 00:19:08', '2026-05-25 00:19:08'),
(11, 7, 3, 'stock_in', 4, 'Initial stock added', '2026-05-25 00:19:51', '2026-05-25 00:19:51'),
(12, 8, 3, 'stock_in', 20, 'Initial stock added', '2026-05-25 00:20:24', '2026-05-25 00:20:24'),
(13, 8, 3, 'restock', 2, 'Material restocked', '2026-05-25 01:15:15', '2026-05-25 01:15:15'),
(14, 8, 3, 'restock', 3, 'Material restocked', '2026-05-27 17:00:02', '2026-05-27 17:00:02'),
(15, 6, 3, 'deducted', 2, 'Request #: MR-2026-0020 | Requested by: aldrin | Approved by: supervisor', '2026-05-27 17:54:12', '2026-05-27 17:54:12'),
(16, 6, 3, 'deducted', 2, 'Request #: MR-2026-0021 | Requested by: aldrin | Approved by: supervisor', '2026-05-27 19:09:58', '2026-05-27 19:09:58'),
(17, 2, 3, 'deducted', 1, 'Request #: MR-2026-0021 | Requested by: aldrin | Approved by: supervisor', '2026-05-27 19:09:58', '2026-05-27 19:09:58'),
(18, 4, 3, 'deducted', 5, 'Request #: MR-2026-0024 | Requested by: aldrin | Approved by: supervisor', '2026-05-28 00:16:55', '2026-05-28 00:16:55'),
(19, 4, 3, 'restock', 10, 'Material restocked', '2026-05-28 00:19:53', '2026-05-28 00:19:53'),
(20, 4, 3, 'deducted', 5, 'Request #: MR-2026-0025 | Requested by: aldrin | Approved by: supervisor', '2026-05-28 00:22:03', '2026-05-28 00:22:03'),
(21, 7, 3, 'deducted', 1, 'Request #: MR-2026-0032 | Requested by: aldrin | Approved by: supervisor', '2026-05-31 23:52:25', '2026-05-31 23:52:25'),
(22, 8, 3, 'restock', 5, 'Material restocked', '2026-06-02 01:04:35', '2026-06-02 01:04:35'),
(23, 7, 3, 'restock', 10, 'Material restocked', '2026-06-02 01:20:20', '2026-06-02 01:20:20'),
(24, 8, 3, 'restock', 3, 'Material restocked', '2026-06-02 01:31:43', '2026-06-02 01:31:43'),
(25, 8, 3, 'deducted', 3, 'Request #: MR-2026-0033 | Requested by: aldrin | Approved by: supervisor', '2026-06-02 01:32:56', '2026-06-02 01:32:56'),
(26, 8, 3, 'restock', 6, 'Material restocked', '2026-06-02 17:49:34', '2026-06-02 17:49:34'),
(27, 8, 3, 'deducted', 3, 'Request #: MR-2026-0034 | Requested by: aldrin | Approved by: supervisor', '2026-06-02 18:29:14', '2026-06-02 18:29:14'),
(28, 11, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(29, 12, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(30, 13, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(31, 14, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(32, 15, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(33, 16, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(34, 17, 3, 'stock_in', 78, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(35, 18, 3, 'stock_in', 46, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(36, 19, 3, 'stock_in', 29, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(37, 20, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(38, 21, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(39, 22, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(40, 23, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(41, 24, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(42, 25, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(43, 26, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(44, 27, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(45, 28, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(46, 29, 3, 'stock_in', 17, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(47, 30, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(48, 31, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(49, 32, 3, 'stock_in', 16, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(50, 33, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(51, 34, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(52, 35, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(53, 36, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(54, 37, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(55, 38, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(56, 39, 3, 'stock_in', 11, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(57, 40, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(58, 41, 3, 'stock_in', 32, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(59, 42, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(60, 43, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(61, 44, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(62, 45, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(63, 46, 3, 'stock_in', 24, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(64, 47, 3, 'stock_in', 17, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(65, 48, 3, 'stock_in', 19, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(66, 49, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(67, 50, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(68, 51, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(69, 52, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(70, 53, 3, 'stock_in', 13, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(71, 54, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(72, 55, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(73, 56, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(74, 57, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(75, 58, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(76, 59, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(77, 60, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(78, 61, 3, 'stock_in', 23, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(79, 62, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(80, 63, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(81, 64, 3, 'stock_in', 73, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(82, 65, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(83, 66, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(84, 67, 3, 'stock_in', 12, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(85, 68, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(86, 69, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(87, 70, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(88, 71, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(89, 72, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(90, 73, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(91, 74, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(92, 75, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(93, 76, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(94, 77, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(95, 78, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(96, 79, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(97, 80, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(98, 81, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(99, 82, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(100, 83, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(101, 84, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(102, 85, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(103, 86, 3, 'stock_in', 11, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(104, 87, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(105, 88, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(106, 89, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(107, 90, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(108, 91, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(109, 92, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(110, 93, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(111, 94, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(112, 95, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(113, 96, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(114, 97, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(115, 98, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(116, 99, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(117, 100, 3, 'stock_in', 20, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(118, 101, 3, 'stock_in', 19, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(119, 102, 3, 'stock_in', 86, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(120, 103, 3, 'stock_in', 39, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(121, 104, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(122, 105, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(123, 106, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(124, 107, 3, 'stock_in', 18, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(125, 108, 3, 'stock_in', 61, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(126, 109, 3, 'stock_in', 173, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(127, 110, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(128, 111, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(129, 112, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(130, 113, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(131, 114, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(132, 115, 3, 'stock_in', 31, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(133, 116, 3, 'stock_in', 25, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(134, 117, 3, 'stock_in', 40, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(135, 118, 3, 'stock_in', 34, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(136, 119, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(137, 120, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(138, 121, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(139, 122, 3, 'stock_in', 1000, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(140, 123, 3, 'stock_in', 98, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(141, 124, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(142, 125, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(143, 126, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(144, 127, 3, 'stock_in', 12, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(145, 128, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(146, 129, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(147, 130, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(148, 131, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(149, 132, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(150, 133, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(151, 134, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(152, 135, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(153, 136, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(154, 137, 3, 'stock_in', 42, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(155, 138, 3, 'stock_in', 49, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(156, 139, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(157, 140, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(158, 141, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(159, 142, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(160, 143, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(161, 144, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(162, 145, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(163, 146, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(164, 147, 3, 'stock_in', 174, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(165, 148, 3, 'stock_in', 196, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(166, 149, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(167, 150, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(168, 151, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(169, 152, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(170, 153, 3, 'stock_in', 14, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(171, 154, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(172, 155, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(173, 156, 3, 'stock_in', 106, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(174, 157, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(175, 158, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(176, 159, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(177, 160, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(178, 161, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(179, 162, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(180, 163, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(181, 164, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(182, 165, 3, 'stock_in', 20, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(183, 166, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(184, 167, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(185, 168, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(186, 169, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(187, 170, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(188, 171, 3, 'stock_in', 99, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(189, 172, 3, 'stock_in', 15, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(190, 173, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(191, 174, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(192, 175, 3, 'stock_in', 15, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(193, 176, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(194, 177, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(195, 178, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(196, 179, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(197, 180, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(198, 181, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(199, 182, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(200, 183, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(201, 184, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(202, 185, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(203, 186, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(204, 187, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(205, 188, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(206, 189, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(207, 190, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(208, 191, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(209, 192, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(210, 193, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(211, 194, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(212, 195, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(213, 196, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(214, 197, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(215, 198, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(216, 199, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(217, 200, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(218, 201, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(219, 202, 3, 'stock_in', 26, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(220, 203, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(221, 204, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(222, 205, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(223, 206, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(224, 207, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(225, 208, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(226, 209, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(227, 210, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(228, 211, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(229, 212, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(230, 213, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(231, 214, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(232, 215, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(233, 216, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(234, 217, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(235, 218, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(236, 219, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(237, 220, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(238, 221, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(239, 222, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(240, 223, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(241, 224, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(242, 225, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(243, 226, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(244, 227, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(245, 228, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02'),
(246, 229, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-02 22:33:02', '2026-06-02 22:33:02');

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `purpose` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requests`
--

INSERT INTO `material_requests` (`id`, `request_number`, `user_id`, `department_id`, `status`, `purpose`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'MR-2026-0001', 4, NULL, 'approved', 'Dean', NULL, '2026-05-18 22:50:30', '2026-05-18 22:50:52'),
(2, 'MR-2026-0002', 4, NULL, 'rejected', 'Printing ID', NULL, '2026-05-24 19:02:17', '2026-05-24 19:21:02'),
(3, 'MR-2026-0003', 4, NULL, 'rejected', 'For exam', NULL, '2026-05-24 19:21:29', '2026-05-24 19:36:14'),
(4, 'MR-2026-0004', 4, NULL, 'rejected', 'DM', NULL, '2026-05-24 19:22:49', '2026-05-24 19:36:12'),
(5, 'MR-2026-0005', 4, 1, 'approved', 'Exam 2', NULL, '2026-05-24 19:29:50', '2026-05-24 19:36:10'),
(6, 'MR-2026-0006', 4, 2, 'rejected', 'wee', NULL, '2026-05-24 19:45:36', '2026-05-24 19:55:09'),
(7, 'MR-2026-0007', 4, 1, 'rejected', 'wee', NULL, '2026-05-24 19:56:28', '2026-05-24 22:16:39'),
(8, 'MR-2026-0008', 4, 1, 'rejected', 'wee', NULL, '2026-05-24 19:59:23', '2026-05-24 22:16:38'),
(9, 'MR-2026-0009', 4, 2, 'rejected', 'weee', NULL, '2026-05-24 21:48:52', '2026-05-24 22:16:38'),
(10, 'MR-2026-0010', 4, 2, 'rejected', 'wee', NULL, '2026-05-24 21:52:17', '2026-05-24 22:16:36'),
(11, 'MR-2026-0011', 7, 1, 'rejected', '1', NULL, '2026-05-24 21:57:32', '2026-05-24 22:16:35'),
(12, 'MR-2026-0012', 7, 2, 'rejected', 'weee', NULL, '2026-05-24 22:01:21', '2026-05-24 22:16:34'),
(13, 'MR-2026-0013', 4, 1, 'rejected', 'weee', NULL, '2026-05-24 22:03:29', '2026-05-24 22:16:33'),
(14, 'MR-2026-0014', 4, 1, 'rejected', 'weee', NULL, '2026-05-24 22:16:11', '2026-05-24 22:16:32'),
(15, 'MR-2026-0015', 4, 1, 'approved', 'wee', NULL, '2026-05-24 22:41:03', '2026-05-24 23:20:15'),
(16, 'MR-2026-0016', 4, 2, 'rejected', 'wee DM', NULL, '2026-05-24 22:43:26', '2026-05-24 23:20:11'),
(17, 'MR-2026-0017', 4, 1, 'rejected', 'weee na', NULL, '2026-05-24 22:53:09', '2026-05-24 23:20:10'),
(18, 'MR-2026-0018', 4, 1, 'rejected', 'weee', NULL, '2026-05-24 23:09:31', '2026-05-24 23:20:09'),
(19, 'MR-2026-0019', 4, 1, 'rejected', 'wqeee', NULL, '2026-05-24 23:19:38', '2026-05-24 23:20:06'),
(20, 'MR-2026-0020', 4, 1, 'approved', 'weee', NULL, '2026-05-27 17:53:52', '2026-05-27 17:54:12'),
(21, 'MR-2026-0021', 4, 1, 'approved', 'for ID', NULL, '2026-05-27 19:09:33', '2026-05-27 19:09:58'),
(22, 'MR-2026-0022', 4, 1, 'rejected', 'for event', NULL, '2026-05-28 00:01:40', '2026-05-28 00:03:04'),
(23, 'MR-2026-0023', 4, 1, 'rejected', 'for classroom', NULL, '2026-05-28 00:03:51', '2026-05-28 00:13:22'),
(24, 'MR-2026-0024', 4, 1, 'rejected', 'for DIIT', NULL, '2026-05-28 00:13:01', '2026-05-28 00:22:01'),
(25, 'MR-2026-0025', 4, 1, 'approved', 'wee', NULL, '2026-05-28 00:21:40', '2026-05-28 00:22:03'),
(26, 'MR-2026-0026', 4, 1, 'rejected', 'wee', NULL, '2026-05-28 00:35:37', '2026-05-28 00:57:53'),
(27, 'MR-2026-0027', 4, 2, 'rejected', 'wee', NULL, '2026-05-28 00:48:43', '2026-05-28 00:57:51'),
(28, 'MR-2026-0028', 4, 2, 'pending', 'weee', NULL, '2026-05-28 00:58:23', '2026-05-28 00:58:23'),
(29, 'MR-2026-0029', 4, 1, 'pending', 'weee', NULL, '2026-05-28 01:33:35', '2026-05-28 01:33:35'),
(30, 'MR-2026-0030', 4, 1, 'pending', 'weee', NULL, '2026-05-28 01:39:49', '2026-05-28 01:39:49'),
(31, 'MR-2026-0031', 4, 1, 'pending', 'wee', NULL, '2026-05-31 21:16:47', '2026-05-31 21:16:47'),
(32, 'MR-2026-0032', 4, 1, 'approved', 'qwwe', NULL, '2026-05-31 23:52:12', '2026-05-31 23:52:26'),
(33, 'MR-2026-0033', 4, 1, 'approved', 'wee testing for batch', NULL, '2026-06-02 01:32:39', '2026-06-02 01:32:56'),
(34, 'MR-2026-0034', 4, 1, 'approved', 'weee', NULL, '2026-06-02 18:28:48', '2026-06-02 18:29:14');

-- --------------------------------------------------------

--
-- Table structure for table `material_request_items`
--

CREATE TABLE `material_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `purpose` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_request_items`
--

INSERT INTO `material_request_items` (`id`, `request_id`, `material_id`, `quantity`, `purpose`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, '2026-05-18 22:50:30', '2026-05-18 22:50:30'),
(2, 2, 2, 1, NULL, '2026-05-24 19:02:17', '2026-05-24 19:02:17'),
(3, 2, 1, 1, NULL, '2026-05-24 19:02:17', '2026-05-24 19:02:17'),
(4, 3, 1, 1, NULL, '2026-05-24 19:21:29', '2026-05-24 19:21:29'),
(5, 4, 2, 1, NULL, '2026-05-24 19:22:49', '2026-05-24 19:22:49'),
(6, 5, 1, 1, NULL, '2026-05-24 19:29:50', '2026-05-24 19:29:50'),
(7, 6, 2, 1, NULL, '2026-05-24 19:45:36', '2026-05-24 19:45:36'),
(8, 7, 2, 1, NULL, '2026-05-24 19:56:28', '2026-05-24 19:56:28'),
(9, 8, 2, 1, NULL, '2026-05-24 19:59:23', '2026-05-24 19:59:23'),
(10, 9, 2, 1, NULL, '2026-05-24 21:48:52', '2026-05-24 21:48:52'),
(11, 10, 2, 1, NULL, '2026-05-24 21:52:17', '2026-05-24 21:52:17'),
(12, 11, 2, 1, NULL, '2026-05-24 21:57:32', '2026-05-24 21:57:32'),
(13, 12, 2, 1, NULL, '2026-05-24 22:01:21', '2026-05-24 22:01:21'),
(14, 13, 2, 1, NULL, '2026-05-24 22:03:29', '2026-05-24 22:03:29'),
(15, 14, 2, 1, NULL, '2026-05-24 22:16:11', '2026-05-24 22:16:11'),
(16, 15, 2, 1, NULL, '2026-05-24 22:41:03', '2026-05-24 22:41:03'),
(17, 16, 2, 2, NULL, '2026-05-24 22:43:26', '2026-05-24 22:43:26'),
(18, 16, 1, 1, NULL, '2026-05-24 22:43:26', '2026-05-24 22:43:26'),
(19, 17, 2, 1, NULL, '2026-05-24 22:53:09', '2026-05-24 22:53:09'),
(20, 18, 2, 1, NULL, '2026-05-24 23:09:31', '2026-05-24 23:09:31'),
(21, 18, 1, 1, NULL, '2026-05-24 23:09:31', '2026-05-24 23:09:31'),
(22, 19, 2, 1, NULL, '2026-05-24 23:19:38', '2026-05-24 23:19:38'),
(23, 20, 6, 2, NULL, '2026-05-27 17:53:52', '2026-05-27 17:53:52'),
(24, 21, 6, 2, NULL, '2026-05-27 19:09:33', '2026-05-27 19:09:33'),
(25, 21, 2, 1, NULL, '2026-05-27 19:09:33', '2026-05-27 19:09:33'),
(26, 22, 6, 10, NULL, '2026-05-28 00:01:40', '2026-05-28 00:01:40'),
(27, 23, 8, 21, NULL, '2026-05-28 00:03:51', '2026-05-28 00:03:51'),
(28, 24, 4, 5, NULL, '2026-05-28 00:13:01', '2026-05-28 00:13:01'),
(29, 25, 4, 5, NULL, '2026-05-28 00:21:41', '2026-05-28 00:21:41'),
(30, 26, 2, 2, NULL, '2026-05-28 00:35:37', '2026-05-28 00:35:37'),
(31, 27, 5, 1, NULL, '2026-05-28 00:48:43', '2026-05-28 00:48:43'),
(32, 28, 2, 1, NULL, '2026-05-28 00:58:23', '2026-05-28 00:58:23'),
(33, 29, 7, 1, NULL, '2026-05-28 01:33:35', '2026-05-28 01:33:35'),
(34, 30, 7, 1, NULL, '2026-05-28 01:39:49', '2026-05-28 01:39:49'),
(35, 31, 2, 1, NULL, '2026-05-31 21:16:47', '2026-05-31 21:16:47'),
(36, 32, 7, 1, NULL, '2026-05-31 23:52:12', '2026-05-31 23:52:12'),
(37, 33, 8, 3, NULL, '2026-06-02 01:32:39', '2026-06-02 01:32:39'),
(38, 34, 8, 3, NULL, '2026-06-02 18:28:48', '2026-06-02 18:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `material_restock_logs`
--

CREATE TABLE `material_restock_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `batch_no` varchar(255) DEFAULT NULL,
  `previous_stock` int(11) NOT NULL,
  `added_stock` int(11) NOT NULL,
  `quantity_remaining` int(11) NOT NULL DEFAULT 0,
  `new_stock` int(11) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `has_expiration` tinyint(1) DEFAULT 0,
  `expiration_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `restocked_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_restock_logs`
--

INSERT INTO `material_restock_logs` (`id`, `material_id`, `batch_no`, `previous_stock`, `added_stock`, `quantity_remaining`, `new_stock`, `supplier`, `invoice_no`, `has_expiration`, `expiration_date`, `remarks`, `restocked_by`, `created_at`, `updated_at`) VALUES
(1, 8, 'RST-2026-0001\r\n', 20, 2, 0, 22, 'National bookstore', NULL, 0, NULL, 'May 25, 2026', 3, '2026-05-25 01:15:15', '2026-05-25 01:15:15'),
(2, 8, 'RST-2026-0002\r\n', 22, 3, 0, 25, 'Donation', NULL, 0, NULL, 'by LGU', 3, '2026-05-27 17:00:02', '2026-05-27 17:00:02'),
(3, 4, 'RST-2026-0003\r\n', 0, 10, 0, 10, 'Ace Hardware', NULL, 0, NULL, '5 watts', 3, '2026-05-28 00:19:53', '2026-05-28 00:19:53'),
(4, 8, 'RST-2026-0004', 4, 5, 0, 9, 'Wilcon', NULL, 0, NULL, 'Red Marker', 3, '2026-06-02 01:04:35', '2026-06-02 18:29:13'),
(5, 7, 'RST-2026-0005', 3, 10, 10, 13, 'National bookstore Carmona', NULL, 0, NULL, '5 meters', 3, '2026-06-02 01:20:20', '2026-06-02 01:20:20'),
(6, 8, 'RST-2026-0006', 9, 3, 2, 12, 'Supplier A', NULL, 0, NULL, 'Remarks Supplier', 3, '2026-06-02 01:31:43', '2026-06-02 18:29:13'),
(7, 8, 'RST-2026-0007', 9, 6, 6, 15, 'Palengke', 'Delivery Receipt No 1', 1, '2027-06-01', 'Markers again', 3, '2026-06-02 17:49:34', '2026-06-02 17:49:34');

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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_04_12_064412_create_leave_requests_table', 1),
(4, '2026_04_12_071224_create_users_table', 1),
(5, '2026_04_13_000000_create_notifications_table', 1),
(6, '2026_04_23_051005_create_personnel_table', 1),
(7, '2026_04_28_054000_create_categories_table', 1),
(8, '2026_04_28_054001_create_units_table', 1),
(9, '2026_04_28_054002_create_materials_table', 1),
(10, '2026_04_28_054003_create_material_requests_table', 1),
(11, '2026_04_28_054004_create_material_request_items_table', 1),
(12, '2026_05_02_121119_add_threshold_to_materials_table', 1),
(13, '2026_05_18_053811_create_material_logs_table', 1),
(14, '2026_05_18_071809_add_purpose_to_material_requests_table', 1),
(15, '2026_05_19_045244_add_request_number_to_material_requests_table', 1),
(16, '2026_05_19_062206_add_supplier_and_remarks_to_material_logs_table', 2),
(17, '2026_05_25_004929_create_departments_table', 3),
(18, '2026_05_25_010229_add_department_id_to_materials_table', 4),
(19, '2026_05_25_021639_add_department_id_to_material_requests_table', 5),
(20, '2026_05_25_062454_add_url_to_notifications_table', 6),
(21, '2026_05_25_085315_create_material_restock_logs_table', 7),
(22, '2026_05_28_005032_create_inventory_movements_table', 8),
(23, '2026_06_01_080546_create_activity_logs_table', 9),
(24, '2026_06_02_083744_add_batch_fields_to_material_restock_logs_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `created_at`, `updated_at`, `url`) VALUES
(1, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-24 22:53:09', '2026-05-24 22:53:20', '/supervisor/material-requests'),
(2, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-24 23:09:31', '2026-05-24 23:10:23', '/supervisor/material-requests'),
(3, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-24 23:19:38', '2026-05-24 23:19:49', '/supervisor/material-requests'),
(4, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-24 23:20:06', '2026-05-28 01:56:25', '/material-request/history'),
(5, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-24 23:20:09', '2026-05-28 01:52:14', '/material-request/history'),
(6, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-24 23:20:10', '2026-05-28 01:48:42', '/material-request/history'),
(7, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-24 23:20:11', '2026-05-24 23:21:35', '/material-request/history'),
(8, 4, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-05-24 23:20:15', '2026-05-24 23:20:26', '/material-request/history'),
(9, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-27 17:53:52', '2026-05-28 00:35:14', '/supervisor/material-requests'),
(10, 4, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-05-27 17:54:12', '2026-05-28 01:45:30', '/material-request/history'),
(11, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-27 19:09:33', '2026-05-28 00:35:10', '/supervisor/material-requests'),
(12, 4, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-05-27 19:09:58', '2026-05-28 01:39:25', '/material-request/history'),
(13, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:01:40', '2026-05-28 00:02:53', '/supervisor/material-requests'),
(14, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-28 00:03:04', '2026-05-28 00:59:33', '/material-request/history'),
(15, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:03:51', '2026-05-28 00:11:38', '/supervisor/material-requests'),
(16, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:13:01', '2026-05-28 00:13:14', '/supervisor/material-requests'),
(17, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-28 00:13:22', '2026-05-28 01:30:49', '/material-request/history'),
(18, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:21:41', '2026-05-28 00:25:58', '/supervisor/material-requests'),
(19, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-28 00:22:01', '2026-05-28 01:02:16', '/material-request/history'),
(20, 3, 'inventory', 'Critical Stock Alert', 'LED Bulbs stock is critically low (5 remaining).', 1, '2026-05-28 00:22:03', '2026-05-28 00:22:11', 'http://127.0.0.1:8000/materials'),
(21, 4, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-05-28 00:22:03', '2026-05-28 01:02:12', '/material-request/history'),
(22, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:35:37', '2026-05-28 00:35:56', '/supervisor/material-requests'),
(23, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:48:43', '2026-05-28 00:48:55', '/supervisor/material-requests'),
(24, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-28 00:57:51', '2026-05-28 00:59:18', '/material-request/history'),
(25, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-28 00:57:53', '2026-05-28 00:59:12', '/material-request/history'),
(26, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:58:23', '2026-05-28 00:58:35', '/supervisor/material-requests'),
(27, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 01:33:35', '2026-05-28 01:33:48', '/supervisor/material-requests'),
(28, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 01:39:49', '2026-05-28 01:40:00', '/supervisor/material-requests'),
(29, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-31 21:16:47', '2026-05-31 21:17:01', '/supervisor/material-requests'),
(30, 3, 'user', 'New User Registration', 'Nerrisa Cator registered and needs approval', 1, '2026-05-31 21:18:05', '2026-05-31 21:18:22', NULL),
(31, 3, 'user_registration', 'New User Registration', 'test registered and needs approval', 1, '2026-05-31 21:38:37', '2026-05-31 21:41:09', 'http://127.0.0.1:8000/admin/users/pending'),
(32, 3, 'user_registration', 'New User Registration', 'Neri Cator registered and needs approval', 1, '2026-05-31 21:57:05', '2026-05-31 21:57:20', '/admin/users/pending'),
(33, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-31 23:52:12', '2026-05-31 23:52:23', '/supervisor/material-requests'),
(34, 3, 'inventory', 'Critical Stock Alert', 'HDMI Cable stock is critically low (3 remaining).', 1, '2026-05-31 23:52:25', '2026-06-01 17:26:03', 'http://127.0.0.1:8000/materials'),
(35, 4, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-05-31 23:52:26', '2026-05-31 23:52:39', '/material-request/history'),
(36, 3, 'user_registration', 'New User Registration', 'Marnel KuyaBunso registered and needs approval', 1, '2026-06-01 00:22:02', '2026-06-01 17:26:01', '/admin/users/pending'),
(37, 3, 'leave', 'New Leave Request', 'Aldrin Justimbaste submitted a leave request.', 1, '2026-06-01 01:31:46', '2026-06-01 01:32:00', '/leave-requests'),
(38, 4, 'leave', 'Leave Submitted', 'Your leave request has been submitted.', 1, '2026-06-01 01:31:47', '2026-06-01 20:47:40', '/leave-requests'),
(39, 4, 'leave', 'Leave Approved', 'Your leave request has been approved.', 1, '2026-06-01 01:37:06', '2026-06-01 01:37:57', '/leave/history'),
(40, 3, 'leave', 'New Leave Request', 'Aldrin Justimbaste submitted a leave request.', 1, '2026-06-01 01:42:41', '2026-06-01 01:42:54', '/leave-requests'),
(41, 4, 'leave', 'Leave Submitted', 'Your leave request has been submitted.', 1, '2026-06-01 01:42:41', '2026-06-01 17:13:43', '/leave-requests'),
(42, 4, 'leave', 'Leave Rejected', 'Your leave request has been rejected.', 1, '2026-06-01 01:49:43', '2026-06-01 17:06:27', '/leave/history'),
(43, 3, 'leave', 'New Leave Request', 'Aldrin Justimbaste submitted a leave request.', 1, '2026-06-01 17:25:26', '2026-06-01 17:25:40', '/leave-requests'),
(44, 4, 'leave', 'Leave Submitted', 'Your leave request has been submitted.', 1, '2026-06-01 17:25:27', '2026-06-01 17:44:03', '/leave/history'),
(45, 4, 'leave', 'Leave Approved', 'Your leave request has been approved.', 1, '2026-06-01 17:26:19', '2026-06-01 17:26:49', '/leave/history'),
(46, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-02 01:32:39', '2026-06-02 01:32:52', '/supervisor/material-requests'),
(47, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-06-02 01:32:56', '2026-06-02 01:32:56', '/material-request/history'),
(48, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-02 18:28:48', '2026-06-02 18:29:06', '/supervisor/material-requests'),
(49, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-06-02 18:29:14', '2026-06-02 18:29:14', '/material-request/history');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`id`, `employee_id`, `fullname`, `position`, `department`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'EMP-001', 'aldrin', 'Personnel', 'General Services', 4, 'Active', NULL, NULL),
(3, 'EMP4650', 'Marilou Corrales', 'Staff', 'Maintenance', 7, 'Active', NULL, NULL),
(4, 'EMP9059', 'Nerrisa Cator', 'Staff', 'Maintenance', 8, 'Active', NULL, NULL),
(5, 'EMP9973', 'test', 'Staff', 'Maintenance', 9, 'Active', NULL, NULL),
(6, 'EMP00005', 'Neri Cator', 'Staff', 'Maintenance', 10, 'Active', NULL, NULL),
(7, 'EMP00006', 'Marnel KuyaBunso', 'Staff', 'Maintenance', 11, 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'piece', NULL, NULL),
(2, 'box', NULL, NULL),
(3, 'Rim', '2026-05-18 17:10:39', '2026-05-18 17:10:39'),
(4, 'Pieces', '2026-06-02 22:33:01', '2026-06-02 23:41:24'),
(5, 'PACK', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(6, '', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(7, 'Set', '2026-06-02 23:41:02', '2026-06-02 23:41:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `birth_month` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'personnel',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `birthdate`, `birth_month`, `age`, `role`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Mark Anthony R. Abril', 'mark@mail.com', 'supervisor', '$2y$12$ZbYz9wJOW1UYVUij8n8G2OVvZ0mBpyrXn/5wy.M6F8VV9Zl29Kgji', '2011-02-16', 'February', 15, 'supervisor', 'approved', '2026-04-27 22:35:31', '2026-04-27 22:35:31'),
(4, 'Aldrin Justimbaste', 'test@mail.com', 'aldrin', '$2y$12$tqU03vlacqclCWYmdJoDI.X9X78gboHV2.VNL6.n7UVqsB/e2voy2', '2009-04-17', 'April', 17, 'personnel', 'approved', '2026-04-27 22:38:13', '2026-04-27 22:38:36'),
(5, 'Arnold Balingit', 'test1@mail.com', 'arnold', '$2y$12$.gtHwE1krpIvOsyQmzDep.dj/r36KFSBUg.zxOv9r1ePPg.n0/L5W', '2009-01-18', 'January', 17, 'personnel', 'approved', '2026-04-27 23:43:36', '2026-04-27 23:47:05'),
(6, 'Aileen Estrada', 'test2@mail.com', 'aileen', '$2y$12$1mRP6RuoO6w.N7n0sABULe1BOHURY9iAk/uWct8IGeR/Nio5Gigve', '2008-02-16', 'February', 18, 'personnel', 'approved', '2026-04-27 23:59:49', '2026-05-24 21:50:03'),
(7, 'Marilou Corrales', 'marilou@mail.com', 'marilou', '$2y$12$h8Senq0DO82Bnqr9Cl6WduF5LFY0EoFbVtRQhYX6dOX5w9KdFKOwq', '2009-02-18', 'February', 17, 'personnel', 'approved', '2026-05-24 21:56:49', '2026-05-24 21:57:08'),
(8, 'Nerrisa Cator', 'Nerrisa@mail.com', 'nerri', '$2y$12$g26jUVEPt5hcu20nkwLbE.HwDFYe2oU5YT4IllJCsEYn39movt8F6', '2012-01-14', 'January', 14, 'personnel', 'rejected', '2026-05-31 21:18:05', '2026-05-31 21:56:35'),
(9, 'test', 'testtest@mail.com', 'test', '$2y$12$YeAa4aTAcDKGbcNHzdl2iORpo153f4iy03pR74yXpXb1cR2Uz8FVW', '2010-01-19', 'January', 16, 'personnel', 'rejected', '2026-05-31 21:38:37', '2026-05-31 21:56:33'),
(10, 'Neri Cator', 'Neri@mail.com', 'neri', '$2y$12$GShTppSWf8ad3OEgA65sWuDvopUiD27ytP0EkC26DosIMZYIvYXAK', '2010-02-18', 'February', 16, 'personnel', 'approved', '2026-05-31 21:57:05', '2026-05-31 21:57:23'),
(11, 'Marnel KuyaBunso', 'marke@mail.com', 'marnell', '$2y$12$FrL.y64PZxkLxUX063AKGuYvy/htjisFsUBUYF/yXPlfkZHZJz82K', '2009-04-18', 'April', 17, 'personnel', 'approved', '2026-06-01 00:22:02', '2026-06-01 00:22:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_material_id_foreign` (`material_id`),
  ADD KEY `inventory_movements_performed_by_foreign` (`performed_by`);

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
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materials_category_id_foreign` (`category_id`),
  ADD KEY `materials_unit_id_foreign` (`unit_id`),
  ADD KEY `materials_created_by_foreign` (`created_by`),
  ADD KEY `materials_department_id_foreign` (`department_id`);

--
-- Indexes for table `material_logs`
--
ALTER TABLE `material_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_logs_material_id_foreign` (`material_id`),
  ADD KEY `material_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_requests_user_id_foreign` (`user_id`),
  ADD KEY `material_requests_department_id_foreign` (`department_id`);

--
-- Indexes for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_request_items_request_id_foreign` (`request_id`),
  ADD KEY `material_request_items_material_id_foreign` (`material_id`);

--
-- Indexes for table `material_restock_logs`
--
ALTER TABLE `material_restock_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_restock_logs_material_id_foreign` (`material_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personnel_user_id_foreign` (`user_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `material_logs`
--
ALTER TABLE `material_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `material_request_items`
--
ALTER TABLE `material_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `material_restock_logs`
--
ALTER TABLE `material_restock_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materials_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materials_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `materials_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_logs`
--
ALTER TABLE `material_logs`
  ADD CONSTRAINT `material_logs_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD CONSTRAINT `material_requests_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD CONSTRAINT `material_request_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_request_items_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `material_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_restock_logs`
--
ALTER TABLE `material_restock_logs`
  ADD CONSTRAINT `material_restock_logs_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `personnel_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
