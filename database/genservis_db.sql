-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 29, 2026 at 07:30 AM
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
(6, 'General Inventory', 'GEN', 'Auto-created by inventory import', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(7, 'Department of Art and Sciences', 'DAS', 'Art and Sciences', '2026-06-03 23:40:49', '2026-06-04 00:13:58'),
(8, 'Department of Teacher Education', 'DTE', 'English, Math, and Science', '2026-06-03 23:42:32', '2026-06-03 23:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `department_materials`
--

CREATE TABLE `department_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `released_by` bigint(20) UNSIGNED NOT NULL,
  `released_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department_materials`
--

INSERT INTO `department_materials` (`id`, `department_id`, `material_id`, `quantity`, `request_id`, `released_by`, `released_at`, `created_at`, `updated_at`) VALUES
(2, 1, 373, 20, 37, 3, '2026-06-08 23:46:41', '2026-06-08 23:46:41', '2026-06-08 23:46:41'),
(3, 1, 328, 1, 37, 3, '2026-06-08 23:46:41', '2026-06-08 23:46:41', '2026-06-08 23:46:41'),
(4, 2, 389, 1, NULL, 3, '2026-06-23 21:05:43', '2026-06-23 21:05:43', '2026-06-23 21:05:43'),
(5, 7, 389, 1, 14, 3, '2026-06-23 21:32:41', '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(6, 7, 358, 1, 14, 3, '2026-06-23 21:32:41', '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(7, 7, 405, 1, 14, 3, '2026-06-23 21:32:41', '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(8, 7, 447, 2, 15, 3, '2026-06-24 00:06:07', '2026-06-24 00:06:07', '2026-06-24 00:06:07'),
(9, 8, 326, 1, 16, 3, '2026-06-24 00:09:19', '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(10, 8, 373, 1, 16, 3, '2026-06-24 00:09:19', '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(11, 8, 326, 1, 16, 3, '2026-06-24 00:09:19', '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(12, 1, 326, 2, 17, 3, '2026-06-24 19:05:26', '2026-06-24 19:05:26', '2026-06-24 19:05:26'),
(13, 1, 358, 1, 17, 3, '2026-06-24 19:05:26', '2026-06-24 19:05:26', '2026-06-24 19:05:26');

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
(231, 230, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(232, 231, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(233, 232, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(240, 239, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(241, 240, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(242, 241, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(244, 243, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(250, 249, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(253, 252, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(255, 254, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(261, 260, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(262, 261, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(263, 262, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(264, 263, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(273, 272, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(281, 280, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(282, 281, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(283, 282, 'restock', 73, 0, 73, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(284, 283, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(285, 284, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(286, 285, 'restock', 12, 0, 12, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(287, 286, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(288, 287, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(289, 288, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(290, 289, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(291, 290, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(292, 291, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(293, 292, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(294, 293, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(295, 294, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(296, 295, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(297, 296, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(298, 297, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(299, 298, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(300, 299, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(301, 300, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(302, 301, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(303, 302, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(304, 303, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(305, 304, 'restock', 11, 0, 11, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(306, 305, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(307, 306, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(308, 307, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(309, 308, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(310, 309, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(311, 310, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(312, 311, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(313, 312, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(314, 313, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(315, 314, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(316, 315, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(317, 316, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(318, 317, 'restock', 20, 0, 20, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(319, 318, 'restock', 19, 0, 19, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(320, 319, 'restock', 86, 0, 86, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(321, 320, 'restock', 39, 0, 39, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(322, 321, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(323, 322, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(324, 323, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(325, 324, 'restock', 18, 0, 18, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(326, 325, 'restock', 61, 0, 61, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(327, 326, 'restock', 173, 0, 173, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(328, 327, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(329, 328, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(330, 329, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(331, 330, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(332, 331, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(333, 332, 'restock', 31, 0, 31, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(334, 333, 'restock', 25, 0, 25, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(335, 334, 'restock', 40, 0, 40, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(336, 335, 'restock', 34, 0, 34, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(337, 336, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(338, 337, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(339, 338, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(340, 339, 'restock', 1000, 0, 1000, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(341, 340, 'restock', 98, 0, 98, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(342, 341, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(343, 342, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(344, 343, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(345, 344, 'restock', 12, 0, 12, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(346, 345, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(347, 346, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(348, 347, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(349, 348, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(350, 349, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(351, 350, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(352, 351, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(353, 352, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(354, 353, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(355, 354, 'restock', 42, 0, 42, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(356, 355, 'restock', 49, 0, 49, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(357, 356, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(358, 357, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(359, 358, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(360, 359, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(361, 360, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(362, 361, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(363, 362, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(364, 363, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(365, 364, 'restock', 174, 0, 174, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(366, 365, 'restock', 196, 0, 196, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(367, 366, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(368, 367, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(369, 368, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(370, 369, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(371, 370, 'restock', 14, 0, 14, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(372, 371, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(373, 372, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(374, 373, 'restock', 106, 0, 106, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(375, 374, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(376, 375, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(377, 376, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(378, 377, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(379, 378, 'restock', 10, 0, 10, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(380, 379, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(381, 380, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(382, 381, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(383, 382, 'restock', 20, 0, 20, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(384, 383, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(385, 384, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(386, 385, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(387, 386, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(388, 387, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(389, 388, 'restock', 99, 0, 99, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(390, 389, 'restock', 15, 0, 15, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(391, 390, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(392, 391, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(393, 392, 'restock', 15, 0, 15, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(394, 393, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(395, 394, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(396, 395, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(397, 396, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(398, 397, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(399, 398, 'restock', 7, 0, 7, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(400, 399, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(401, 400, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(402, 401, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(403, 402, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(404, 403, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(405, 404, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(406, 405, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(407, 406, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(408, 407, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(409, 408, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(410, 409, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(411, 410, 'restock', 6, 0, 6, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(412, 411, 'restock', 8, 0, 8, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(413, 412, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(414, 413, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(415, 414, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(416, 415, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(417, 416, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(418, 417, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(419, 418, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(420, 419, 'restock', 26, 0, 26, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(421, 420, 'restock', 9, 0, 9, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(422, 421, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(423, 422, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(424, 423, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(425, 424, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(426, 425, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(427, 426, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(428, 427, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(429, 428, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(430, 429, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(431, 430, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(432, 431, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(433, 432, 'restock', 4, 0, 4, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(434, 433, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(435, 434, 'restock', 5, 0, 5, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(436, 435, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(437, 436, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(438, 437, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(439, 438, 'restock', 2, 0, 2, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(440, 439, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(441, 440, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(442, 441, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(443, 442, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(444, 443, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(445, 444, 'restock', 3, 0, 3, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(446, 445, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(447, 446, 'restock', 1, 0, 1, 'Imported from Excel', 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(449, 330, 'request', 1, 3, 2, 'Request #: MR-2026-0036 | Requested by: aldrin', 3, '2026-06-08 00:41:29', '2026-06-08 00:41:29'),
(450, 328, 'request', 2, 5, 3, 'Request #: MR-2026-0036 | Requested by: aldrin', 3, '2026-06-08 00:41:30', '2026-06-08 00:41:30'),
(451, 337, 'request', 1, 5, 4, 'Request #: MR-2026-0036 | Requested by: aldrin', 3, '2026-06-08 00:41:31', '2026-06-08 00:41:31'),
(452, 373, 'request', 20, 106, 86, 'Request #: MR-2026-0037 | Requested by: aldrin', 3, '2026-06-08 23:46:28', '2026-06-08 23:46:28'),
(453, 328, 'request', 1, 3, 2, 'Request #: MR-2026-0037 | Requested by: aldrin', 3, '2026-06-08 23:46:28', '2026-06-08 23:46:28');

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
(230, 'Biglite Fire Emergency Evacuation Sign', 2, 14, 3, 2, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(231, 'Royu Electrical Devices', 3, 8, 3, 2, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(232, 'Firefly Bulb', 4, 8, 3, 2, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(239, 'ENERGIZER Battery AAA (pack)', 5, 8, 3, 5, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(240, 'ENERGIZER Battery AA', 1, 8, 3, 2, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(241, 'EVEREADY Battery 9B', 1, 8, 3, 2, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(243, 'ROYU Safety Breaker 9', 1, 8, 3, 2, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(249, 'MGK Binder Clips', 1, 6, 3, 2, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(252, 'NEC Type 32 Telephone Set', 4, 7, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(254, 'Stanley Door Stopper', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(260, 'Joy Binder Clips', 3, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(261, 'Excel Binder Clips', 1, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(262, 'JDWJ Round Clips 75mm', 1, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(263, 'Joy Sharpener', 1, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(272, 'Suntech USB Type B Cable', 9, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(280, 'WIN Amplified Multimedia Hi-Fi Speaker', 1, 7, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(281, 'RJ45', 7, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(282, 'CD Case', 73, 7, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(283, 'HP-RW (pack)', 3, 7, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(284, 'WD Tool Pouch', 3, 15, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(285, 'AMASCO LED Lamp', 8, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-23 20:47:23', 5),
(286, 'Proteger LED Water Proof Search Light', 2, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(287, 'KOTEN Safety Breaker', 2, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(288, 'X-BALOG Head Lamp', 4, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(289, 'Portable Flashlight', 1, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(290, 'HBW Permanent Marker Ink Refill', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(291, 'HBW Red Marker', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(292, 'Pixon Highlighter (pack)', 5, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(293, 'FOXCEL Heavy Duty Staples (pack)', 5, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(294, 'Printo Stapler Remover', 7, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(295, 'LOVEIN Gel Ink Pen (pack)', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(296, 'PRIMAX Liquid Ink Freeliner', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(297, 'DELI Permanent Marker', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(298, 'PILOT Permanent Marker', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(299, 'HB Pencil (pack)', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(300, 'Glue Stick (pack)', 2, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(301, 'MONGOL Pencil', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(302, 'HBW Ballpen', 5, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(303, 'PILOT Whiteboard Marker Refill Ink', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(304, 'EXCEL Highlighter', 11, 8, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(305, 'Dustless Chalk', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(306, 'Post It Notepads (pack)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(307, 'JOY Correction Tape', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(308, 'JOY Push Pin (pack)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(309, 'SCOTCH Magic Tape', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(310, 'POINTER Index Tabs', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(311, 'DO IT Luggage Tags', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(312, 'PRINTO Ruler', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(313, 'EXCELLENT Board Eraser', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(314, 'JDWJ 50mm Round Clips', 2, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(315, 'LCT Bulldog Clip', 7, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(316, 'JDWJ 22mm Round Clips', 1, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(317, 'BOSTON Staples (pack)', 20, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(318, 'ETONA Staples (pack)', 19, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(319, 'JOY Staples (pack)', 86, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(320, 'WORX Long Board Paper (pack)', 39, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(321, 'AGPAPHOTO A4 Laminating Film (pack)', 3, 16, 3, 5, 3, '2026-06-03 00:12:35', '2026-06-23 22:58:35', 5),
(322, 'KING Legal Size L-Type Folder (pack)', 9, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(323, 'LUCKY File Bag', 9, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(324, 'WORX 8.5x13 Specialty Paper (pack)', 18, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(325, 'WORX A4 90gsm Specialty Paper (pack)', 61, 16, 3, 5, 3, '2026-06-03 00:12:35', '2026-06-23 23:00:46', 5),
(326, 'A4 200gsm Specialty Paper WORX', 169, 6, 3, 5, 3, '2026-06-03 00:12:35', '2026-06-24 19:05:26', 5),
(327, 'JOJO Photopaper 180g/m2 (pack)', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(328, 'A4 Document Frame', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-08 23:46:28', 5),
(329, 'Brown Envelope Short', 10, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(330, '8.50x11 Certificate Holder', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-23 00:31:22', 5),
(331, 'Binder Envelope', 6, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(332, 'SUPERFAX A4 Sensitized Film (pack)', 31, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(333, 'QUAFF A4 Laminating Film (pack)', 25, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(334, 'SUKI/GENTLE PRINCE DTR Card (pack)', 40, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(335, 'KING FILES Clear Book', 34, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(336, 'File Folder w/index tabs (pack)', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(337, 'ADVENTURER BlueCard Case', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-08 00:41:31', 5),
(338, 'EXCEL Glossy Sticker Paper(bundle)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(339, 'DTR Card', 1000, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(340, 'IMARI 8.5x11 Glossy Paper (pack)', 98, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(341, 'QUAFF A4 Calling Card Paper (pack)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(342, 'QUAFF A4 Glossy Photo Sticker (pack)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(343, 'Sliding Folder (pack)', 10, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(344, 'Stapler w/remover', 12, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(345, 'PILOT Broad Super Color Marker (pack)', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(346, 'DONG-A Gel Ink (pack)', 9, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(347, 'PRINTO Stamp Pad Ink', 8, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(348, 'EXCEL Chisel Point #10 Staples (pack)', 9, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(349, 'ASIAN 5x8 Index Card (pack)', 7, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(350, 'ASIAN 3x5 Index Card (pack)', 5, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(351, 'ZHIJIN Enice Ballpen (pack)', 8, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(352, 'FLEX OFFICE Ballpen (pack)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(353, 'HBW Whiteboard Marker Ink Refill', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(354, 'NINGTAI Card  Case (pack)', 42, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(355, 'QUAFF Calling Card (pack)', 49, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(356, 'TOLSEN Air Tools Kit (set)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(357, 'MAKITA Jigsaw (set)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(358, 'AVR', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-24 19:05:26', 5),
(359, 'Mega Phone', 5, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(360, 'STANDARD Duct Fan', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(361, 'IMARI Glossy Paper A3 (bundle)', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(362, 'KODAK Photo Glossy Paper for Inkjet Prints', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(363, 'OFFICEMAX Coin Envelope 8 ½ size', 10, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(364, 'P/X Expanding Folder Green', 174, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(365, 'P/X Expanding Folder Red', 196, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(366, 'P/X Expanding Folder Yellow', 10, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(367, 'P/X Expanding Folder Orange', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(368, 'P/X Expanding Folder Blue', 10, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(369, 'P/X Expanding Folder Violet', 10, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(370, 'ADVENTURER Certificate Holder Red', 14, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(371, 'Long Envelope', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(372, 'QUAFF Comb Binding Machine', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(373, 'ADVENTURER Certificate Holder Blue', 84, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-24 00:09:19', 5),
(374, 'UPRIGHT Laminator Mini', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(375, 'MIYAMI Laminator Big', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(376, 'STAR Money Detector', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(377, 'AOC Monitor', 9, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(378, 'D-Ring Binder White', 10, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(379, 'D-Ring Binder Red', 2, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(380, 'D-Ring Binder Blue', 6, 6, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(381, 'EPSON Ink (For Printer) Black', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(382, 'Diploma Holder', 20, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(383, 'KRAFT Document Envelopes A4', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(384, 'EPSON Ink (For Printer) Cyan', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(385, 'CLASSIC White Envelope size 10', 7, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(386, 'PHOENIX Expanding Envelope', 8, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(387, 'Document File (bundle)', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(388, 'Multi Copy Paper A4 (reams)', 99, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(389, 'Avant Garde Quick Ladder AG53009 8m', 13, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-23 21:32:41', 5),
(390, 'Leap PQ9907S Digital Chess Timer', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(391, 'Verza Chess Clock', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(392, 'Competition Ball (sepak takraw)', 15, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(393, 'Table Tennis Net', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(394, 'Double Happiness Table Tennis Net', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(395, 'Table Tennis Racket', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(396, 'Aristo Stopwatch Series', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(397, 'Super K SK6128 Professional Quartz Timer', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(398, 'Orange Traffic Cone', 7, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(399, 'Chessboard Set', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(400, 'Cima Manual Scoreboard', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(401, 'Digital Scoreboard', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(402, 'Stix Headgear (blue)', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(403, 'Stix Headgear (red)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(404, 'Swimfit Swimming Goggles', 8, 14, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(405, 'Boxing Gloves Pair (blue)', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-23 21:32:41', 5),
(406, 'Boxing Gloves Pair (red)', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(407, 'Kix Taekwondo Headgear (red)', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(408, 'Kix Taekwondo Headgear (blue)', 5, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(409, 'Guide Overgrip', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(410, 'Kix Taekwondo Shin Guard Pair (red)', 6, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(411, 'Kix Taekwondo Shin Guard Pair (blue)', 8, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(412, 'Clever Shin and Instep Guard Pair (red)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(413, 'Taekwondo Gloves Pair (white)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(414, 'Excalibur Boxing Helmet (black)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(415, 'Boxing Headgear (black)', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(416, 'Boxing Headgear (red)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(417, 'Chess Mat Set', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(418, 'Srabble Wooden Board Set', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(419, 'Kix Taekwondo Body Armor', 26, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(420, 'Kix Taekwondo Kick Pad', 9, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(421, 'Stix Groin Guard (male)', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(422, 'Kix Groin Guard (male)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(423, 'Stix Groin Guard (female)', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(424, 'Kix Groin Guard (female)', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(425, 'Kang O Fitness Boxing Hand Wraps 3m (black)', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(426, 'Kang O Fitness Boxing Hand Wraps 3m (red)', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(427, 'Ciever Master Boxing Hand Wraps', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(428, 'Super K Coaching Board', 5, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(429, 'Super K Frisbee (orange)', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(430, 'Super K Frisbee (dark green)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(431, 'Super K Frisbee (green)', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(432, 'Classic Fox40 Referee Whistle (dark blue)', 4, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(433, 'Classic Fox40 RefereeWhistle (black)', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(434, 'Elite Fitness Yoga Mat', 5, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(435, 'Pogo Stick', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(436, 'Mizuno Tennis Racket', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(437, 'Speedrino Tennis Racket', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(438, 'Yonex Badminton Set', 2, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(439, 'GTO Tennis Net', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(440, 'Table Tennis Net and Post Set', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(441, 'Stix Arnis Hand Gloves Pair', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(442, 'Super K Table Tennis Net and Post Set', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(443, 'Ys-902 Digital Chess timer', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(444, 'Kix Taekwondo Kick Shield', 3, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(445, 'Molten B64500 Basketball', 1, 18, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-23 22:57:24', 5),
(446, 'Accel 7500 Voleyballl', 1, 16, 3, 6, 3, '2026-06-03 00:12:35', '2026-06-03 00:12:35', 5),
(447, 'A4 COPY ONE', 13, 6, 3, 3, 3, '2026-06-23 23:02:54', '2026-06-24 00:06:07', 5);

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
(247, 230, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(248, 231, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(249, 232, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(256, 239, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(257, 240, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(258, 241, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(260, 243, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(266, 249, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(269, 252, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(271, 254, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(277, 260, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(278, 261, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(279, 262, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(280, 263, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(289, 272, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(297, 280, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(298, 281, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(299, 282, 3, 'stock_in', 73, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(300, 283, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(301, 284, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(302, 285, 3, 'stock_in', 12, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(303, 286, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(304, 287, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(305, 288, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(306, 289, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(307, 290, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(308, 291, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(309, 292, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(310, 293, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(311, 294, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(312, 295, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(313, 296, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(314, 297, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(315, 298, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(316, 299, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(317, 300, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(318, 301, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(319, 302, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(320, 303, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(321, 304, 3, 'stock_in', 11, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(322, 305, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(323, 306, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(324, 307, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(325, 308, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(326, 309, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(327, 310, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(328, 311, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(329, 312, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(330, 313, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(331, 314, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(332, 315, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(333, 316, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(334, 317, 3, 'stock_in', 20, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(335, 318, 3, 'stock_in', 19, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(336, 319, 3, 'stock_in', 86, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(337, 320, 3, 'stock_in', 39, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(338, 321, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(339, 322, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(340, 323, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(341, 324, 3, 'stock_in', 18, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(342, 325, 3, 'stock_in', 61, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(343, 326, 3, 'stock_in', 173, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(344, 327, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(345, 328, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(346, 329, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(347, 330, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(348, 331, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(349, 332, 3, 'stock_in', 31, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(350, 333, 3, 'stock_in', 25, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(351, 334, 3, 'stock_in', 40, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(352, 335, 3, 'stock_in', 34, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(353, 336, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(354, 337, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(355, 338, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(356, 339, 3, 'stock_in', 1000, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(357, 340, 3, 'stock_in', 98, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(358, 341, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(359, 342, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(360, 343, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(361, 344, 3, 'stock_in', 12, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(362, 345, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(363, 346, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(364, 347, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(365, 348, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(366, 349, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(367, 350, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(368, 351, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(369, 352, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(370, 353, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(371, 354, 3, 'stock_in', 42, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(372, 355, 3, 'stock_in', 49, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(373, 356, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(374, 357, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(375, 358, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(376, 359, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(377, 360, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(378, 361, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(379, 362, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(380, 363, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(381, 364, 3, 'stock_in', 174, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(382, 365, 3, 'stock_in', 196, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(383, 366, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(384, 367, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(385, 368, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(386, 369, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(387, 370, 3, 'stock_in', 14, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(388, 371, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(389, 372, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(390, 373, 3, 'stock_in', 106, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(391, 374, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(392, 375, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(393, 376, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(394, 377, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(395, 378, 3, 'stock_in', 10, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(396, 379, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(397, 380, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(398, 381, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(399, 382, 3, 'stock_in', 20, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(400, 383, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(401, 384, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(402, 385, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(403, 386, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(404, 387, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(405, 388, 3, 'stock_in', 99, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(406, 389, 3, 'stock_in', 15, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(407, 390, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(408, 391, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(409, 392, 3, 'stock_in', 15, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(410, 393, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(411, 394, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(412, 395, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(413, 396, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(414, 397, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(415, 398, 3, 'stock_in', 7, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(416, 399, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(417, 400, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(418, 401, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(419, 402, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(420, 403, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(421, 404, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(422, 405, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(423, 406, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(424, 407, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(425, 408, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(426, 409, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(427, 410, 3, 'stock_in', 6, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(428, 411, 3, 'stock_in', 8, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(429, 412, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(430, 413, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(431, 414, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(432, 415, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(433, 416, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(434, 417, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(435, 418, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(436, 419, 3, 'stock_in', 26, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(437, 420, 3, 'stock_in', 9, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(438, 421, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(439, 422, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(440, 423, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(441, 424, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(442, 425, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(443, 426, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(444, 427, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(445, 428, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(446, 429, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(447, 430, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(448, 431, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(449, 432, 3, 'stock_in', 4, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(450, 433, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(451, 434, 3, 'stock_in', 5, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(452, 435, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(453, 436, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(454, 437, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(455, 438, 3, 'stock_in', 2, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(456, 439, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(457, 440, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(458, 441, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(459, 442, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(460, 443, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(461, 444, 3, 'stock_in', 3, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(462, 445, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(463, 446, 3, 'stock_in', 1, 'Imported from Excel', '2026-06-03 00:12:35', '2026-06-03 00:12:35'),
(465, 330, 3, 'deducted', 1, 'Request #: MR-2026-0036 | Requested by: aldrin | Approved by: supervisor', '2026-06-08 00:41:29', '2026-06-08 00:41:29'),
(466, 328, 3, 'deducted', 2, 'Request #: MR-2026-0036 | Requested by: aldrin | Approved by: supervisor', '2026-06-08 00:41:30', '2026-06-08 00:41:30'),
(467, 337, 3, 'deducted', 1, 'Request #: MR-2026-0036 | Requested by: aldrin | Approved by: supervisor', '2026-06-08 00:41:31', '2026-06-08 00:41:31'),
(468, 373, 3, 'deducted', 20, 'Request #: MR-2026-0037 | Requested by: aldrin | Approved by: supervisor', '2026-06-08 23:46:28', '2026-06-08 23:46:28'),
(469, 328, 3, 'deducted', 1, 'Request #: MR-2026-0037 | Requested by: aldrin | Approved by: supervisor', '2026-06-08 23:46:28', '2026-06-08 23:46:28'),
(470, 373, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260623084042', '2026-06-23 00:40:42', '2026-06-23 00:40:42'),
(471, 285, 3, 'DEDUCTED', 2, 'Walk-In Issue #WI-20260624044545', '2026-06-23 20:45:45', '2026-06-23 20:45:45'),
(472, 285, 3, 'DEDUCTED', 2, 'Walk-In Issue #WI-20260624044723', '2026-06-23 20:47:23', '2026-06-23 20:47:23'),
(473, 389, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260624050543', '2026-06-23 21:05:43', '2026-06-23 21:05:43'),
(474, 389, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260624053241', '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(475, 358, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260624053241', '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(476, 405, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260624053241', '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(477, 447, 3, 'DEDUCTED', 2, 'Walk-In Issue #WI-20260624080607', '2026-06-24 00:06:07', '2026-06-24 00:06:07'),
(478, 326, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260624080919', '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(479, 373, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260624080919', '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(480, 326, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260624080919', '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(481, 326, 3, 'DEDUCTED', 2, 'Walk-In Issue #WI-20260625030526', '2026-06-24 19:05:26', '2026-06-24 19:05:26'),
(482, 358, 3, 'DEDUCTED', 1, 'Walk-In Issue #WI-20260625030526', '2026-06-24 19:05:26', '2026-06-24 19:05:26');

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','released','rejected') NOT NULL DEFAULT 'pending',
  `purpose` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `released_by` bigint(20) UNSIGNED DEFAULT NULL,
  `released_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requests`
--

INSERT INTO `material_requests` (`id`, `request_number`, `user_id`, `department_id`, `status`, `purpose`, `remarks`, `released_by`, `released_at`, `created_at`, `updated_at`) VALUES
(1, 'MR-2026-0001', 4, NULL, 'approved', 'Dean', NULL, NULL, NULL, '2026-05-18 22:50:30', '2026-05-18 22:50:52'),
(2, 'MR-2026-0002', 4, NULL, 'rejected', 'Printing ID', NULL, NULL, NULL, '2026-05-24 19:02:17', '2026-05-24 19:21:02'),
(3, 'MR-2026-0003', 4, NULL, 'rejected', 'For exam', NULL, NULL, NULL, '2026-05-24 19:21:29', '2026-05-24 19:36:14'),
(4, 'MR-2026-0004', 4, NULL, 'rejected', 'DM', NULL, NULL, NULL, '2026-05-24 19:22:49', '2026-05-24 19:36:12'),
(5, 'MR-2026-0005', 4, 1, 'approved', 'Exam 2', NULL, NULL, NULL, '2026-05-24 19:29:50', '2026-05-24 19:36:10'),
(6, 'MR-2026-0006', 4, 2, 'rejected', 'wee', NULL, NULL, NULL, '2026-05-24 19:45:36', '2026-05-24 19:55:09'),
(7, 'MR-2026-0007', 4, 1, 'rejected', 'wee', NULL, NULL, NULL, '2026-05-24 19:56:28', '2026-05-24 22:16:39'),
(8, 'MR-2026-0008', 4, 1, 'rejected', 'wee', NULL, NULL, NULL, '2026-05-24 19:59:23', '2026-05-24 22:16:38'),
(9, 'MR-2026-0009', 4, 2, 'rejected', 'weee', NULL, NULL, NULL, '2026-05-24 21:48:52', '2026-05-24 22:16:38'),
(10, 'MR-2026-0010', 4, 2, 'rejected', 'wee', NULL, NULL, NULL, '2026-05-24 21:52:17', '2026-05-24 22:16:36'),
(11, 'MR-2026-0011', 7, 1, 'rejected', '1', NULL, NULL, NULL, '2026-05-24 21:57:32', '2026-05-24 22:16:35'),
(12, 'MR-2026-0012', 7, 2, 'rejected', 'weee', NULL, NULL, NULL, '2026-05-24 22:01:21', '2026-05-24 22:16:34'),
(13, 'MR-2026-0013', 4, 1, 'rejected', 'weee', NULL, NULL, NULL, '2026-05-24 22:03:29', '2026-05-24 22:16:33'),
(14, 'MR-2026-0014', 4, 1, 'rejected', 'weee', NULL, NULL, NULL, '2026-05-24 22:16:11', '2026-05-24 22:16:32'),
(15, 'MR-2026-0015', 4, 1, 'approved', 'wee', NULL, NULL, NULL, '2026-05-24 22:41:03', '2026-05-24 23:20:15'),
(16, 'MR-2026-0016', 4, 2, 'rejected', 'wee DM', NULL, NULL, NULL, '2026-05-24 22:43:26', '2026-05-24 23:20:11'),
(17, 'MR-2026-0017', 4, 1, 'rejected', 'weee na', NULL, NULL, NULL, '2026-05-24 22:53:09', '2026-05-24 23:20:10'),
(18, 'MR-2026-0018', 4, 1, 'rejected', 'weee', NULL, NULL, NULL, '2026-05-24 23:09:31', '2026-05-24 23:20:09'),
(19, 'MR-2026-0019', 4, 1, 'rejected', 'wqeee', NULL, NULL, NULL, '2026-05-24 23:19:38', '2026-05-24 23:20:06'),
(20, 'MR-2026-0020', 4, 1, 'approved', 'weee', NULL, NULL, NULL, '2026-05-27 17:53:52', '2026-05-27 17:54:12'),
(21, 'MR-2026-0021', 4, 1, 'approved', 'for ID', NULL, NULL, NULL, '2026-05-27 19:09:33', '2026-05-27 19:09:58'),
(22, 'MR-2026-0022', 4, 1, 'rejected', 'for event', NULL, NULL, NULL, '2026-05-28 00:01:40', '2026-05-28 00:03:04'),
(23, 'MR-2026-0023', 4, 1, 'rejected', 'for classroom', NULL, NULL, NULL, '2026-05-28 00:03:51', '2026-05-28 00:13:22'),
(24, 'MR-2026-0024', 4, 1, 'rejected', 'for DIIT', NULL, NULL, NULL, '2026-05-28 00:13:01', '2026-05-28 00:22:01'),
(25, 'MR-2026-0025', 4, 1, 'approved', 'wee', NULL, NULL, NULL, '2026-05-28 00:21:40', '2026-05-28 00:22:03'),
(26, 'MR-2026-0026', 4, 1, 'rejected', 'wee', NULL, NULL, NULL, '2026-05-28 00:35:37', '2026-05-28 00:57:53'),
(27, 'MR-2026-0027', 4, 2, 'rejected', 'wee', NULL, NULL, NULL, '2026-05-28 00:48:43', '2026-05-28 00:57:51'),
(28, 'MR-2026-0028', 4, 2, 'pending', 'weee', NULL, NULL, NULL, '2026-05-28 00:58:23', '2026-05-28 00:58:23'),
(29, 'MR-2026-0029', 4, 1, 'pending', 'weee', NULL, NULL, NULL, '2026-05-28 01:33:35', '2026-05-28 01:33:35'),
(30, 'MR-2026-0030', 4, 1, 'pending', 'weee', NULL, NULL, NULL, '2026-05-28 01:39:49', '2026-05-28 01:39:49'),
(31, 'MR-2026-0031', 4, 1, 'pending', 'wee', NULL, NULL, NULL, '2026-05-31 21:16:47', '2026-05-31 21:16:47'),
(32, 'MR-2026-0032', 4, 1, 'approved', 'qwwe', NULL, NULL, NULL, '2026-05-31 23:52:12', '2026-05-31 23:52:26'),
(33, 'MR-2026-0033', 4, 1, 'approved', 'wee testing for batch', NULL, NULL, NULL, '2026-06-02 01:32:39', '2026-06-02 01:32:56'),
(34, 'MR-2026-0034', 4, 1, 'released', 'weee', NULL, NULL, NULL, '2026-06-02 18:28:48', '2026-06-03 20:12:26'),
(35, 'MR-2026-0035', 4, 2, 'released', 'for computer PC', NULL, NULL, NULL, '2026-06-03 21:00:10', '2026-06-03 21:00:39'),
(36, 'MR-2026-0036', 4, 7, 'approved', 'weee', NULL, NULL, NULL, '2026-06-08 00:40:56', '2026-06-08 00:41:31'),
(37, 'MR-2026-0037', 4, 1, 'released', 'weeee', NULL, NULL, NULL, '2026-06-08 23:45:55', '2026-06-08 23:46:41');

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
(40, 36, 330, 1, NULL, '2026-06-08 00:40:56', '2026-06-08 00:40:56'),
(41, 36, 328, 2, NULL, '2026-06-08 00:40:56', '2026-06-08 00:40:56'),
(42, 36, 337, 1, NULL, '2026-06-08 00:40:56', '2026-06-08 00:40:56'),
(43, 37, 373, 20, NULL, '2026-06-08 23:45:55', '2026-06-08 23:45:55'),
(44, 37, 328, 1, NULL, '2026-06-08 23:45:55', '2026-06-08 23:45:55');

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
(24, '2026_06_02_083744_add_batch_fields_to_material_restock_logs_table', 10),
(25, '2026_06_04_035514_add_release_fields_to_material_requests_table', 11),
(26, '2026_06_04_043528_create_department_materials_table', 12),
(27, '2026_06_13_032753_create_walkin_requests_table', 13),
(28, '2026_06_13_032758_create_walkin_request_items_table', 13);

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
(49, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-06-02 18:29:14', '2026-06-02 18:29:14', '/material-request/history'),
(50, 4, 'material', 'Materials Released', 'Your requested materials are ready for pickup.', 0, '2026-06-03 20:12:26', '2026-06-03 20:12:26', '/material-request/history'),
(51, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-03 21:00:10', '2026-06-03 21:00:21', '/supervisor/material-requests'),
(52, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-06-03 21:00:26', '2026-06-03 21:00:26', '/material-request/history'),
(53, 4, 'material', 'Materials Released', 'Your requested materials are ready for pickup.', 1, '2026-06-03 21:00:39', '2026-06-08 01:16:11', '/material-request/history'),
(54, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-08 00:40:56', '2026-06-08 00:41:21', '/supervisor/material-requests'),
(55, 3, 'inventory', 'Critical Stock Alert', '8.50x11 Certificate Holder stock is critically low (2 remaining).', 0, '2026-06-08 00:41:29', '2026-06-08 00:41:29', 'http://127.0.0.1:8000/materials'),
(56, 3, 'inventory', 'Critical Stock Alert', 'A4 Document Frame stock is critically low (3 remaining).', 0, '2026-06-08 00:41:30', '2026-06-08 00:41:30', 'http://127.0.0.1:8000/materials'),
(57, 3, 'inventory', 'Critical Stock Alert', 'ADVENTURER BlueCard Case stock is critically low (4 remaining).', 1, '2026-06-08 00:41:31', '2026-06-08 01:15:53', 'http://127.0.0.1:8000/materials'),
(58, 4, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-06-08 00:41:31', '2026-06-08 00:41:46', '/material-request/history'),
(59, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-08 23:45:55', '2026-06-08 23:46:12', '/supervisor/material-requests'),
(60, 3, 'inventory', 'Critical Stock Alert', 'A4 Document Frame stock is critically low (2 remaining).', 0, '2026-06-08 23:46:28', '2026-06-08 23:46:28', 'http://127.0.0.1:8000/materials'),
(61, 4, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-06-08 23:46:29', '2026-06-08 23:46:29', '/material-request/history'),
(62, 4, 'material', 'Materials Released', 'Your requested materials are ready for pickup.', 1, '2026-06-08 23:46:41', '2026-06-08 23:47:06', '/material-request/history');

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
(1, 'Piece', NULL, NULL),
(2, 'Box', NULL, NULL),
(3, 'Rim', '2026-05-18 17:10:39', '2026-05-18 17:10:39'),
(4, 'Pieces', '2026-06-02 22:33:01', '2026-06-02 23:41:24'),
(5, 'Pack', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(6, 'Bottle', '2026-06-02 22:33:01', '2026-06-03 23:45:57'),
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

-- --------------------------------------------------------

--
-- Table structure for table `walkin_requests`
--

CREATE TABLE `walkin_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `requestor_name` varchar(255) NOT NULL,
  `personnel_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `room` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `priority` enum('Normal','Urgent') NOT NULL DEFAULT 'Normal',
  `remarks` text DEFAULT NULL,
  `issued_by` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Issued',
  `issued_at` timestamp NULL DEFAULT NULL,
  `printed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transaction_type` varchar(255) NOT NULL DEFAULT 'WALK-IN ISSUE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `walkin_requests`
--

INSERT INTO `walkin_requests` (`id`, `reference_no`, `requestor_name`, `personnel_id`, `department_id`, `room`, `purpose`, `priority`, `remarks`, `issued_by`, `status`, `issued_at`, `printed_at`, `completed_at`, `created_at`, `updated_at`, `transaction_type`) VALUES
(1, 'WI-20260623074328', 'Mark Anthony Abril', NULL, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-22 23:43:28', NULL, NULL, '2026-06-22 23:43:28', '2026-06-22 23:43:28', 'WALK-IN ISSUE'),
(2, 'WI-20260623080457', 'Mark Anthony Abril', NULL, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:04:57', NULL, NULL, '2026-06-23 00:04:57', '2026-06-23 00:04:57', 'WALK-IN ISSUE'),
(3, 'WI-20260623082113', 'Mark Anthony Abril', NULL, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:21:13', NULL, NULL, '2026-06-23 00:21:13', '2026-06-23 00:21:13', 'WALK-IN ISSUE'),
(4, 'WI-20260623082938', 'Mark Anthony Abril', NULL, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:29:38', NULL, NULL, '2026-06-23 00:29:38', '2026-06-23 00:29:38', 'WALK-IN ISSUE'),
(5, 'WI-20260623083122', 'Mark Anthony Abril', NULL, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:31:22', NULL, NULL, '2026-06-23 00:31:22', '2026-06-23 00:31:22', 'WALK-IN ISSUE'),
(6, 'WI-20260623084042', 'Mark Anthony Abril', NULL, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:40:42', NULL, NULL, '2026-06-23 00:40:42', '2026-06-23 00:40:42', 'WALK-IN ISSUE'),
(7, 'WI-20260624044105', 'Mark Anthony Abril', NULL, 8, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:41:05', NULL, NULL, '2026-06-23 20:41:05', '2026-06-23 20:41:05', 'WALK-IN ISSUE'),
(8, 'WI-20260624044243', 'Mark Anthony Abril', NULL, 2, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:42:43', NULL, NULL, '2026-06-23 20:42:43', '2026-06-23 20:42:43', 'WALK-IN ISSUE'),
(9, 'WI-20260624044545', 'Mark Anthony Abril', NULL, 2, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:45:45', NULL, NULL, '2026-06-23 20:45:45', '2026-06-23 20:45:45', 'WALK-IN ISSUE'),
(10, 'WI-20260624044723', 'Mark Anthony Abril', NULL, 8, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:47:23', NULL, NULL, '2026-06-23 20:47:23', '2026-06-23 20:47:23', 'WALK-IN ISSUE'),
(11, 'WI-20260624045534', 'Mark Anthony Abril', NULL, 8, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:55:34', NULL, NULL, '2026-06-23 20:55:34', '2026-06-23 20:55:34', 'WALK-IN ISSUE'),
(12, 'WI-20260624050143', 'Mark Anthony Abril', NULL, 2, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 21:01:43', NULL, NULL, '2026-06-23 21:01:43', '2026-06-23 21:01:43', 'WALK-IN ISSUE'),
(13, 'WI-20260624050543', 'Mark Anthony Abril', NULL, 2, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 21:05:43', NULL, NULL, '2026-06-23 21:05:43', '2026-06-23 21:05:43', 'WALK-IN ISSUE'),
(14, 'WI-20260624053241', 'Mark Anthony Abril', NULL, 7, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 21:32:41', NULL, NULL, '2026-06-23 21:32:41', '2026-06-23 21:32:41', 'WALK-IN ISSUE'),
(15, 'WI-20260624080607', 'Mark Anthony Abril', NULL, 7, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-24 00:06:07', NULL, NULL, '2026-06-24 00:06:07', '2026-06-24 00:06:07', 'WALK-IN ISSUE'),
(16, 'WI-20260624080919', 'Mark Anthony Abril', NULL, 8, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-24 00:09:19', NULL, NULL, '2026-06-24 00:09:19', '2026-06-24 00:09:19', 'WALK-IN ISSUE'),
(17, 'WI-20260625030526', 'Cua', NULL, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-24 19:05:26', NULL, NULL, '2026-06-24 19:05:26', '2026-06-24 19:05:26', 'WALK-IN ISSUE');

-- --------------------------------------------------------

--
-- Table structure for table `walkin_request_items`
--

CREATE TABLE `walkin_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `walkin_request_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `stock_before` decimal(10,2) NOT NULL,
  `stock_after` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `walkin_request_items`
--

INSERT INTO `walkin_request_items` (`id`, `walkin_request_id`, `material_id`, `quantity`, `unit`, `remarks`, `stock_before`, `stock_after`, `created_at`, `updated_at`) VALUES
(1, 3, 330, 1.00, 'Bottle', NULL, 2.00, 1.00, '2026-06-23 00:21:13', '2026-06-23 00:21:13'),
(2, 4, 330, 1.00, 'Bottle', NULL, 2.00, 1.00, '2026-06-23 00:29:38', '2026-06-23 00:29:38'),
(3, 5, 330, 1.00, 'Bottle', NULL, 2.00, 1.00, '2026-06-23 00:31:22', '2026-06-23 00:31:22'),
(4, 6, 373, 1.00, 'Bottle', NULL, 86.00, 85.00, '2026-06-23 00:40:42', '2026-06-23 00:40:42'),
(5, 9, 285, 2.00, 'Bottle', NULL, 12.00, 10.00, '2026-06-23 20:45:45', '2026-06-23 20:45:45'),
(6, 10, 285, 2.00, 'Bottle', NULL, 10.00, 8.00, '2026-06-23 20:47:23', '2026-06-23 20:47:23'),
(7, 11, 377, 1.00, 'Bottle', NULL, 9.00, 8.00, '2026-06-23 20:55:34', '2026-06-23 20:55:34'),
(8, 12, 389, 1.00, 'Bottle', NULL, 15.00, 14.00, '2026-06-23 21:01:43', '2026-06-23 21:01:43'),
(9, 13, 389, 1.00, 'Bottle', NULL, 15.00, 14.00, '2026-06-23 21:05:43', '2026-06-23 21:05:43'),
(10, 14, 389, 1.00, 'Bottle', NULL, 14.00, 13.00, '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(11, 14, 358, 1.00, 'Bottle', NULL, 3.00, 2.00, '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(12, 14, 405, 1.00, 'Bottle', NULL, 3.00, 2.00, '2026-06-23 21:32:41', '2026-06-23 21:32:41'),
(13, 15, 447, 2.00, 'Rim', NULL, 15.00, 13.00, '2026-06-24 00:06:07', '2026-06-24 00:06:07'),
(14, 16, 326, 1.00, 'Pack', NULL, 173.00, 172.00, '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(15, 16, 373, 1.00, 'Bottle', NULL, 85.00, 84.00, '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(16, 16, 326, 1.00, 'Pack', NULL, 172.00, 171.00, '2026-06-24 00:09:19', '2026-06-24 00:09:19'),
(17, 17, 326, 2.00, 'Pack', NULL, 171.00, 169.00, '2026-06-24 19:05:26', '2026-06-24 19:05:26'),
(18, 17, 358, 1.00, 'Bottle', NULL, 2.00, 1.00, '2026-06-24 19:05:26', '2026-06-24 19:05:26');

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
-- Indexes for table `department_materials`
--
ALTER TABLE `department_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_department_materials_material` (`material_id`);

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
-- Indexes for table `walkin_requests`
--
ALTER TABLE `walkin_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `walkin_requests_reference_no_unique` (`reference_no`),
  ADD KEY `walkin_requests_personnel_id_foreign` (`personnel_id`),
  ADD KEY `walkin_requests_department_id_foreign` (`department_id`),
  ADD KEY `walkin_requests_issued_by_foreign` (`issued_by`);

--
-- Indexes for table `walkin_request_items`
--
ALTER TABLE `walkin_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `walkin_request_items_walkin_request_id_foreign` (`walkin_request_id`),
  ADD KEY `walkin_request_items_material_id_foreign` (`material_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `department_materials`
--
ALTER TABLE `department_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=454;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=448;

--
-- AUTO_INCREMENT for table `material_logs`
--
ALTER TABLE `material_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=483;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `material_request_items`
--
ALTER TABLE `material_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `material_restock_logs`
--
ALTER TABLE `material_restock_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `walkin_requests`
--
ALTER TABLE `walkin_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `walkin_request_items`
--
ALTER TABLE `walkin_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `department_materials`
--
ALTER TABLE `department_materials`
  ADD CONSTRAINT `fk_department_materials_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`);

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

--
-- Constraints for table `walkin_requests`
--
ALTER TABLE `walkin_requests`
  ADD CONSTRAINT `walkin_requests_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `walkin_requests_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `walkin_requests_personnel_id_foreign` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `walkin_request_items`
--
ALTER TABLE `walkin_request_items`
  ADD CONSTRAINT `walkin_request_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `walkin_request_items_walkin_request_id_foreign` FOREIGN KEY (`walkin_request_id`) REFERENCES `walkin_requests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
