-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 23, 2026 at 09:34 AM
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
  `personnel_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `approved_by` bigint(20) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `personnel_id`, `reason`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `user_id`, `approved_by`, `approved_at`) VALUES
(1, NULL, 'vacay', '2026-04-22', '2026-04-24', 'Approved', '2026-04-22 23:06:14', '2026-04-22 23:25:44', 2, 1, '2026-04-22 23:25:44');

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
(6, '2026_04_23_051005_create_personnel_table', 2);

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
(3, 1, 'user', 'New User Registration', 'test9 registered and needs approval', 1, '2026-04-22 22:08:25', '2026-04-22 23:32:58'),
(4, 1, 'user', 'New User Registration', 'test10 registered and needs approval', 1, '2026-04-22 22:15:26', '2026-04-22 23:32:54'),
(5, 1, 'leave', 'New Leave Request', 'test2 submitted a leave request.', 1, '2026-04-22 23:06:14', '2026-04-22 23:32:52'),
(6, 2, 'leave', 'Leave Submitted', 'Your leave request has been submitted.', 0, '2026-04-22 23:06:15', '2026-04-22 23:06:15'),
(7, 2, 'leave', 'Leave Approved', 'Your leave request has been approved.', 0, '2026-04-22 23:25:44', '2026-04-22 23:25:44');

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
(1, 'EMP2450', 'test6', 'Staff', 'Maintenance', 6, 'Active', NULL, NULL),
(2, 'EMP1191', 'test7', 'Staff', 'Maintenance', 7, 'Active', NULL, NULL),
(3, 'EMP8420', 'test8', 'Staff', 'Maintenance', 8, 'Active', NULL, NULL),
(4, 'EMP5149', 'test9', 'Staff', 'Maintenance', 11, 'Active', NULL, NULL),
(5, 'EMP5572', 'test10', 'Staff', 'Maintenance', 12, 'Active', NULL, NULL);

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
(1, 'Mark Abril', 'admin@mail.com', 'supervisor', '$2y$12$lvHvsc5OFswzD/CteERdaOzgyKL2GFVZIEZ6g6mCEGFm7fasvlS1i', '2000-01-01', 'September', 25, 'supervisor', 'approved', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'test2', 'test2@mail.com', 'test2', '$2y$12$ld6YJyKGm3gC.Ts16FMCVu0ef1cZDk.N2bZrNlzQuRPNXngpGDKg2', '2013-09-19', 'September', 12, 'personnel', 'approved', '2026-04-22 21:11:34', '2026-04-22 21:41:20'),
(3, 'test3', 'test3@mail.com', 'test3', '$2y$12$CGWylmTfoqDl6jOXqDzYHeOvdx/pu9k3cxOoR4jXLINyAIvW2cine', '2010-01-16', 'January', 16, 'personnel', 'pending', '2026-04-22 21:15:05', '2026-04-22 21:15:05'),
(4, 'test4', 'test4@mail.com', 'test4', '$2y$12$hhKEFMvGFNAL8AODNwGUeeUtQErdLNXcvOgXOEzGQAjPvuP37aipO', '2009-03-18', 'March', 17, 'personnel', 'pending', '2026-04-22 21:17:35', '2026-04-22 21:17:35'),
(5, 'test5', 'test5@mail.com', 'test5', '$2y$12$sKSgOkxmOHMhkNliQTfgx.3hL3ptrm6kUxcXA7JBW8lKW6WBYl4Ce', '2010-05-16', 'May', 15, 'personnel', 'pending', '2026-04-22 21:19:19', '2026-04-22 21:19:19'),
(6, 'test6', 'test6@mail.com', 'test6', '$2y$12$gemw.J6Jwb6XA8RMQaKB1eBI4FwY4NcnUusLMTbhPF6EZn9/omZG6', '2011-12-18', 'December', 14, 'personnel', 'pending', '2026-04-22 21:21:18', '2026-04-22 21:21:18'),
(7, 'test7', 'test7@mail.com', 'test7', '$2y$12$UuSO6u.Dz2qkSzYxHM1oxeJ6kZVf0FPIXgZsyH.JQMWGGMyyjNSUe', '2008-01-17', 'January', 18, 'personnel', 'pending', '2026-04-22 21:29:01', '2026-04-22 21:29:01'),
(8, 'test8', 'test8@mail.com', 'test8', '$2y$12$lvHvsc5OFswzD/CteERdaOzgyKL2GFVZIEZ6g6mCEGFm7fasvlS1i', '2008-04-19', 'April', 18, 'personnel', 'pending', '2026-04-22 21:35:21', '2026-04-22 21:35:21'),
(11, 'test9', 'test9@mail.com', 'test9', '$2y$12$dv.AEow0nHREJ5UEptpDueJHXDcNMZ/EZh0trVkSiFqmRr8FHuy5i', '2008-01-19', 'January', 18, 'personnel', 'pending', '2026-04-22 22:08:25', '2026-04-22 22:08:25'),
(12, 'test10', 'test10@mail.com', 'test10', '$2y$12$cdtiUl08BNCfoKLYUFUhuOmw7IA/hVBEWD8s6b9Q0PGHZH6vwTR7e', '2009-02-19', 'February', 17, 'personnel', 'approved', '2026-04-22 22:15:26', '2026-04-22 23:25:52');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

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
