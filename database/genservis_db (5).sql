-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 19, 2026 at 07:46 AM
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
(4, 'Equipment', NULL, NULL),
(6, 'Office Supplies', '2026-05-18 16:43:39', '2026-05-18 16:46:31');

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
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `threshold` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `quantity`, `category_id`, `unit_id`, `created_by`, `created_at`, `updated_at`, `threshold`) VALUES
(1, 'brush', 5, 1, 1, 3, '2026-04-27 22:53:53', '2026-05-18 21:33:45', 5),
(2, 'soup', 7, 1, 1, 3, '2026-05-17 21:53:28', '2026-05-18 18:56:51', 5),
(3, 'Liquid Detergent', 5, 3, 1, 3, '2026-05-17 22:08:07', '2026-05-17 22:08:07', 5),
(4, 'A4 Bond paper', 4, 6, 3, 3, '2026-05-18 17:11:22', '2026-05-18 20:55:43', 5);

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
(1, 2, 3, 'stock_in', 10, 'Initial stock added', '2026-05-17 21:53:28', '2026-05-17 21:53:28'),
(2, 3, 3, 'stock_in', 5, 'Initial stock added', '2026-05-17 22:08:07', '2026-05-17 22:08:07'),
(3, 4, 3, 'stock_in', 10, 'Initial stock added', '2026-05-18 17:11:22', '2026-05-18 17:11:22'),
(4, 4, 3, 'deducted', 1, 'Request #: N/A | Requested by: aldrin | Approved by: supervisor', '2026-05-18 20:49:39', '2026-05-18 20:49:39'),
(5, 1, 3, 'deducted', 1, 'Request #: N/A | Requested by: aldrin | Approved by: supervisor', '2026-05-18 20:49:40', '2026-05-18 20:49:40'),
(6, 4, 3, 'deducted', 1, 'Request #: MR-2026-0009 | Requested by: aldrin | Approved by: supervisor', '2026-05-18 20:55:43', '2026-05-18 20:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `purpose` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requests`
--

INSERT INTO `material_requests` (`id`, `request_number`, `user_id`, `status`, `purpose`, `remarks`, `created_at`, `updated_at`) VALUES
(1, NULL, 4, 'rejected', NULL, NULL, '2026-04-28 21:20:41', '2026-05-17 23:26:03'),
(2, NULL, 4, 'approved', NULL, NULL, '2026-05-17 23:11:04', '2026-05-17 23:26:01'),
(3, NULL, 4, 'rejected', NULL, NULL, '2026-05-17 23:40:12', '2026-05-17 23:41:03'),
(4, NULL, 4, 'approved', NULL, NULL, '2026-05-18 17:11:46', '2026-05-18 17:12:10'),
(5, NULL, 5, 'pending', NULL, NULL, '2026-05-18 17:36:58', '2026-05-18 17:36:58'),
(6, NULL, 4, 'pending', NULL, NULL, '2026-05-18 18:08:21', '2026-05-18 18:08:21'),
(7, NULL, 4, 'approved', NULL, NULL, '2026-05-18 18:56:10', '2026-05-18 18:56:51'),
(8, NULL, 4, 'approved', 'para sa Dean office', NULL, '2026-05-18 19:17:43', '2026-05-18 20:49:41'),
(9, 'MR-2026-0009', 4, 'approved', 'for DTR', NULL, '2026-05-18 20:55:25', '2026-05-18 20:55:43');

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
(1, 1, 1, 1, NULL, '2026-04-28 21:20:41', '2026-04-28 21:20:41'),
(2, 2, 2, 2, NULL, '2026-05-17 23:11:04', '2026-05-17 23:11:04'),
(3, 3, 1, 2, NULL, '2026-05-17 23:40:12', '2026-05-17 23:40:12'),
(4, 4, 4, 1, NULL, '2026-05-18 17:11:46', '2026-05-18 17:11:46'),
(5, 5, 4, 1, NULL, '2026-05-18 17:36:58', '2026-05-18 17:36:58'),
(6, 6, 1, 1, NULL, '2026-05-18 18:08:21', '2026-05-18 18:08:21'),
(7, 6, 2, 4, NULL, '2026-05-18 18:08:21', '2026-05-18 18:08:21'),
(8, 6, 4, 1, NULL, '2026-05-18 18:08:21', '2026-05-18 18:08:21'),
(9, 7, 4, 3, NULL, '2026-05-18 18:56:10', '2026-05-18 18:56:10'),
(10, 7, 2, 1, NULL, '2026-05-18 18:56:10', '2026-05-18 18:56:10'),
(11, 8, 4, 1, NULL, '2026-05-18 19:17:43', '2026-05-18 19:17:43'),
(12, 8, 1, 1, NULL, '2026-05-18 19:17:43', '2026-05-18 19:17:43'),
(13, 9, 4, 1, NULL, '2026-05-18 20:55:25', '2026-05-18 20:55:25');

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
(12, '2026_05_02_121119_add_threshold_to_materials_table', 2),
(13, '2026_05_18_053811_create_material_logs_table', 3),
(14, '2026_05_18_071809_add_purpose_to_material_requests_table', 4),
(15, '2026_05_19_045244_add_request_number_to_material_requests_table', 5);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(2, 3, 'user', 'New User Registration', 'test registered and needs approval', 1, '2026-04-27 22:38:13', '2026-04-28 16:26:20'),
(3, 3, 'user', 'New User Registration', 'test1 registered and needs approval', 1, '2026-04-27 23:43:36', '2026-04-28 16:26:18'),
(4, 3, 'user', 'New User Registration', 'test2 Ltest2 registered and needs approval', 1, '2026-04-27 23:59:49', '2026-04-28 16:26:13'),
(5, 3, 'material', 'New Material Request', ' requested materials.', 0, '2026-04-28 21:20:41', '2026-04-28 21:20:41'),
(6, 3, 'material', 'New Material Request', 'test requested materials.', 0, '2026-05-17 23:11:04', '2026-05-17 23:11:04'),
(7, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-05-17 23:26:01', '2026-05-17 23:26:01'),
(8, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 0, '2026-05-17 23:26:03', '2026-05-17 23:26:03'),
(9, 3, 'material', 'New Material Request', 'test requested materials.', 1, '2026-05-17 23:40:12', '2026-05-17 23:40:39'),
(10, 4, 'material', 'Request Rejected', 'Your material request has been rejected.', 0, '2026-05-17 23:41:03', '2026-05-17 23:41:03'),
(11, 3, 'material', 'New Material Request', 'test requested materials.', 0, '2026-05-18 17:11:46', '2026-05-18 17:11:46'),
(12, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-05-18 17:12:10', '2026-05-18 17:12:10'),
(13, 3, 'material', 'New Material Request', 'test1 requested materials.', 0, '2026-05-18 17:36:58', '2026-05-18 17:36:58'),
(14, 3, 'material', 'New Material Request', 'test submitted a material request.', 0, '2026-05-18 18:08:21', '2026-05-18 18:08:21'),
(15, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 0, '2026-05-18 18:56:10', '2026-05-18 18:56:10'),
(16, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-05-18 18:56:51', '2026-05-18 18:56:51'),
(17, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 0, '2026-05-18 19:17:43', '2026-05-18 19:17:43'),
(18, 3, 'material', 'Low Stock Alert', 'A4 Bond paper is running low on stock!', 0, '2026-05-18 20:49:39', '2026-05-18 20:49:39'),
(19, 3, 'material', 'Low Stock Alert', 'brush is running low on stock!', 0, '2026-05-18 20:49:40', '2026-05-18 20:49:40'),
(20, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-05-18 20:49:41', '2026-05-18 20:49:41'),
(21, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 0, '2026-05-18 20:55:25', '2026-05-18 20:55:25'),
(22, 3, 'material', 'Low Stock Alert', 'A4 Bond paper is running low on stock!', 1, '2026-05-18 20:55:43', '2026-05-18 21:24:17'),
(23, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-05-18 20:55:43', '2026-05-18 20:55:43');

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
(3, 'EMP3654', 'Mark Anthony R. Abril', 'Staff', 'Maintenance', 3, 'Active', NULL, NULL),
(4, 'EMP2451', 'test', 'Staff', 'Maintenance', 4, 'Active', NULL, NULL),
(5, 'EMP5571', 'test1', 'Staff', 'Maintenance', 5, 'Active', NULL, NULL),
(6, 'EMP2703', 'test2 Ltest2', 'Staff', 'Maintenance', 6, 'Active', NULL, NULL);

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
(6, 'Aileen Estrada', 'test2@mail.com', 'aileen', '$2y$12$1mRP6RuoO6w.N7n0sABULe1BOHURY9iAk/uWct8IGeR/Nio5Gigve', '2008-02-16', 'February', 18, 'personnel', 'pending', '2026-04-27 23:59:49', '2026-04-27 23:59:49');

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
  ADD KEY `materials_created_by_foreign` (`created_by`);

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
  ADD KEY `material_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_request_items_request_id_foreign` (`request_id`),
  ADD KEY `material_request_items_material_id_foreign` (`material_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `material_logs`
--
ALTER TABLE `material_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `material_request_items`
--
ALTER TABLE `material_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materials_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `material_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD CONSTRAINT `material_request_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_request_items_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `material_requests` (`id`) ON DELETE CASCADE;

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
