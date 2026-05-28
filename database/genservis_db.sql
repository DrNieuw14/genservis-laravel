-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 28, 2026 at 08:34 AM
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
(1, 'Cleaning Materials', NULL, NULL),
(2, 'Soap', NULL, NULL),
(3, 'Detergent', NULL, NULL),
(4, 'Office Equipment', NULL, '2026-05-18 22:40:54'),
(6, 'Office Supplies', '2026-05-18 16:43:39', '2026-05-18 16:46:31'),
(7, 'ICT Equipment', '2026-05-18 22:40:38', '2026-05-18 22:40:38'),
(8, 'Electrical Supplies', '2026-05-25 00:17:15', '2026-05-25 00:17:15');

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
(3, 'Stock Room', 'SR-Main', 'Stock Room in the main building', '2026-05-24 23:22:47', '2026-05-24 23:22:47');

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
(1, 8, 'restock', 3, 22, 25, 'by LGU', 3, '2026-05-27 17:00:02', '2026-05-27 17:00:02');

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
(4, 'LED Bulbs', 10, 8, 3, 1, 3, '2026-05-25 00:17:54', '2026-05-25 00:17:54', 5),
(5, 'Safety Breaker', 4, 8, 3, 1, 3, '2026-05-25 00:18:27', '2026-05-25 00:18:27', 5),
(6, 'Batteries AAA', 11, 8, 3, 1, 3, '2026-05-25 00:19:08', '2026-05-27 19:09:58', 5),
(7, 'HDMI Cable', 4, 7, 3, 1, 3, '2026-05-25 00:19:51', '2026-05-25 00:19:51', 5),
(8, 'Markers', 25, 6, 1, 1, 3, '2026-05-25 00:20:24', '2026-05-27 21:16:00', 5);

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
(17, 2, 3, 'deducted', 1, 'Request #: MR-2026-0021 | Requested by: aldrin | Approved by: supervisor', '2026-05-27 19:09:58', '2026-05-27 19:09:58');

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
(21, 'MR-2026-0021', 4, 1, 'approved', 'for ID', NULL, '2026-05-27 19:09:33', '2026-05-27 19:09:58');

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
(25, 21, 2, 1, NULL, '2026-05-27 19:09:33', '2026-05-27 19:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `material_restock_logs`
--

CREATE TABLE `material_restock_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `added_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `restocked_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_restock_logs`
--

INSERT INTO `material_restock_logs` (`id`, `material_id`, `previous_stock`, `added_stock`, `new_stock`, `supplier`, `remarks`, `restocked_by`, `created_at`, `updated_at`) VALUES
(1, 8, 20, 2, 22, 'National bookstore', 'May 25, 2026', 3, '2026-05-25 01:15:15', '2026-05-25 01:15:15'),
(2, 8, 22, 3, 25, 'Donation', 'by LGU', 3, '2026-05-27 17:00:02', '2026-05-27 17:00:02');

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
(22, '2026_05_28_005032_create_inventory_movements_table', 8);

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
(4, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 0, '2026-05-24 23:20:06', '2026-05-24 23:20:06', '/material-request/history'),
(5, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 0, '2026-05-24 23:20:09', '2026-05-24 23:20:09', '/material-request/history'),
(6, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 0, '2026-05-24 23:20:10', '2026-05-24 23:20:10', '/material-request/history'),
(7, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 1, '2026-05-24 23:20:11', '2026-05-24 23:21:35', '/material-request/history'),
(8, 4, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-05-24 23:20:15', '2026-05-24 23:20:26', '/material-request/history'),
(9, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 0, '2026-05-27 17:53:52', '2026-05-27 17:53:52', '/supervisor/material-requests'),
(10, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-05-27 17:54:12', '2026-05-27 17:54:12', '/material-request/history'),
(11, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 0, '2026-05-27 19:09:33', '2026-05-27 19:09:33', '/supervisor/material-requests'),
(12, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-05-27 19:09:58', '2026-05-27 19:09:58', '/material-request/history');

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
(3, 'EMP4650', 'Marilou Corrales', 'Staff', 'Maintenance', 7, 'Active', NULL, NULL);

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
(3, 'Rim', '2026-05-18 17:10:39', '2026-05-18 17:10:39');

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
(7, 'Marilou Corrales', 'marilou@mail.com', 'marilou', '$2y$12$h8Senq0DO82Bnqr9Cl6WduF5LFY0EoFbVtRQhYX6dOX5w9KdFKOwq', '2009-02-18', 'February', 17, 'personnel', 'approved', '2026-05-24 21:56:49', '2026-05-24 21:57:08');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `material_logs`
--
ALTER TABLE `material_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `material_request_items`
--
ALTER TABLE `material_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `material_restock_logs`
--
ALTER TABLE `material_restock_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

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
