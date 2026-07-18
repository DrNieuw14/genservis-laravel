-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 18, 2026 at 07:15 AM
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
  `target_user_id` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `activity_logs` (`id`, `user_id`, `target_user_id`, `module`, `action`, `description`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 'Users', 'Approved User', 'Approved user: Marnel KuyaBunso', '127.0.0.1', '2026-06-01 00:22:15', '2026-06-01 00:22:15'),
(2, NULL, NULL, 'Leave', 'Submitted Leave', 'Submitted leave request from aldrin', '127.0.0.1', '2026-06-01 01:31:46', '2026-06-01 01:31:46'),
(3, 3, NULL, 'Leave', 'Approved Leave', 'Approved leave request of aldrin', '127.0.0.1', '2026-06-01 01:37:06', '2026-06-01 01:37:06'),
(4, NULL, NULL, 'Leave', 'Submitted Leave', 'Submitted leave request from aldrin', '127.0.0.1', '2026-06-01 01:42:41', '2026-06-01 01:42:41'),
(5, 3, NULL, 'Leave', 'Rejected Leave', 'Rejected leave request of aldrin', '127.0.0.1', '2026-06-01 01:49:43', '2026-06-01 01:49:43'),
(6, NULL, NULL, 'Leave', 'Submitted Leave', 'Submitted leave request from aldrin', '127.0.0.1', '2026-06-01 17:25:26', '2026-06-01 17:25:26'),
(7, 3, NULL, 'Leave', 'Approved Leave', 'Approved leave request of aldrin', '127.0.0.1', '2026-06-01 17:26:19', '2026-06-01 17:26:19'),
(8, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for test3', '127.0.0.1', '2026-07-07 01:25:01', '2026-07-07 01:25:01'),
(9, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Mark Anthony Abril', '127.0.0.1', '2026-07-07 15:22:20', '2026-07-07 15:22:20'),
(10, 3, NULL, 'Users', 'Rejected User', 'Rejected user: test22', '127.0.0.1', '2026-07-07 15:22:24', '2026-07-07 15:22:24'),
(11, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Mary Ann and assigned the default Employee system role.', '127.0.0.1', '2026-07-07 19:50:03', '2026-07-07 19:50:03'),
(12, 3, NULL, 'Employee Master', 'Created Personal Information', 'Added personal information for Mark Anthony Abril', '127.0.0.1', '2026-07-08 01:44:43', '2026-07-08 01:44:43'),
(13, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Raymond T. Uminga and assigned the default Employee system role.', '127.0.0.1', '2026-07-13 22:34:23', '2026-07-13 22:34:23'),
(14, 3, NULL, 'Users', 'Rejected User', 'Rejected user: Arnold Balingit', '127.0.0.1', '2026-07-15 18:47:20', '2026-07-15 18:47:20'),
(15, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Aldrin Justimbaste and assigned the default Employee system role.', '127.0.0.1', '2026-07-15 23:48:22', '2026-07-15 23:48:22'),
(16, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Rochelle C. Malabayabas and assigned the default Employee system role.', '127.0.0.1', '2026-07-16 00:23:12', '2026-07-16 00:23:12'),
(17, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Regene G. Hernandez and assigned the default Employee system role.', '127.0.0.1', '2026-07-16 18:38:48', '2026-07-16 18:38:48'),
(18, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for VERIFY Onboard Test and assigned the default Employee system role.', '127.0.0.1', '2026-07-16 18:42:27', '2026-07-16 18:42:27'),
(19, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Diana H. Cortez and assigned the default Employee system role.', '127.0.0.1', '2026-07-16 18:43:25', '2026-07-16 18:43:25'),
(20, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Joy Siochi and assigned the default Employee system role.', '127.0.0.1', '2026-07-16 21:56:24', '2026-07-16 21:56:24'),
(21, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Jenny Beb F. Espineli and assigned the default Employee system role.', '127.0.0.1', '2026-07-16 22:24:37', '2026-07-16 22:24:37'),
(22, 3, NULL, 'Users', 'Completed Employee Onboarding', 'Completed onboarding for Jenny Beb F. Espineli and assigned the default Employee system role.', '127.0.0.1', '2026-07-16 22:26:26', '2026-07-16 22:26:26'),
(24, 19, NULL, 'Users', 'Quick-Added Employee', 'Created employee record for King Ronmark B. Abril via Walk-In Issuance.', '127.0.0.1', '2026-07-17 19:33:33', '2026-07-17 19:33:33'),
(25, 19, NULL, 'Users', 'Quick-Added Employee', 'Created employee record for Qwncy Amie B. Abril via Walk-In Issuance.', '127.0.0.1', '2026-07-17 19:45:44', '2026-07-17 19:45:44'),
(26, 19, NULL, 'Users', 'Quick-Added Employee', 'Created employee record for Joseph E. Cuarez via Walk-In Issuance.', '127.0.0.1', '2026-07-17 20:34:01', '2026-07-17 20:34:01'),
(27, 19, NULL, 'Users', 'Quick-Added Employee', 'Created employee record for King Ronmark B. Abril via Walk-In Issuance.', '127.0.0.1', '2026-07-17 20:54:22', '2026-07-17 20:54:22'),
(29, 19, NULL, 'Users', 'Reset User Password', 'Reset password for Joy Siochi (joy).', '127.0.0.1', '2026-07-17 21:02:46', '2026-07-17 21:02:46');

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
(18, 'Sport Equipment', '2026-06-02 23:52:51', '2026-06-02 23:52:51'),
(19, 'Others', '2026-07-16 21:07:32', '2026-07-16 21:07:32');

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
(8, 'Department of Teacher Education', 'DTE', 'English, Math, and Science', '2026-06-03 23:42:32', '2026-06-03 23:42:32'),
(9, 'Department of Hotel Management', 'DHM', NULL, '2026-06-29 22:29:04', '2026-06-29 22:29:04'),
(10, 'Library', 'LIB', NULL, '2026-06-29 22:29:32', '2026-06-29 22:29:32'),
(11, 'Registrar', 'Reg', NULL, '2026-06-29 22:29:45', '2026-06-29 22:29:45'),
(12, 'Medical Health', 'CLINIC', NULL, '2026-06-29 22:30:07', '2026-06-29 22:30:07'),
(14, 'Admin Department', 'Admin', NULL, '2026-07-15 21:14:30', '2026-07-15 21:14:30');

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
(19, 1, 1154, 2, 21, 19, '2026-07-15 23:09:52', '2026-07-15 23:09:52', '2026-07-15 23:09:52'),
(20, 1, 1154, 3, 21, 19, '2026-07-15 23:09:52', '2026-07-15 23:09:52', '2026-07-15 23:09:52'),
(21, 8, 1154, 5, 39, 19, '2026-07-16 23:28:55', '2026-07-16 23:28:55', '2026-07-16 23:28:55'),
(22, 1, 1154, 5, 44, 19, '2026-07-17 00:08:00', '2026-07-17 00:08:00', '2026-07-17 00:08:00'),
(24, 1, 1154, 5, 23, 19, '2026-07-17 19:55:55', '2026-07-17 19:55:55', '2026-07-17 19:55:55'),
(28, 1, 1154, 4, 47, 19, '2026-07-17 20:50:50', '2026-07-17 20:50:50', '2026-07-17 20:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `employee_contacts`
--

CREATE TABLE `employee_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personnel_id` bigint(20) UNSIGNED NOT NULL,
  `primary_email` varchar(255) NOT NULL,
  `alternate_email` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `telephone_number` varchar(20) DEFAULT NULL,
  `emergency_contact_person` varchar(255) NOT NULL,
  `emergency_contact_number` varchar(20) NOT NULL,
  `emergency_relationship` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_contacts`
--

INSERT INTO `employee_contacts` (`id`, `personnel_id`, `primary_email`, `alternate_email`, `mobile_number`, `telephone_number`, `emergency_contact_person`, `emergency_contact_number`, `emergency_relationship`, `created_at`, `updated_at`) VALUES
(1, 9, 'markanthony.abril@cvsu.edu.ph', 'markanthonyrabril@gmail.com', '09123456789', '(046)1234567', 'Ronemie', '09987654321', 'Spouse', '2026-07-08 01:48:35', '2026-07-08 01:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `employee_educations`
--

CREATE TABLE `employee_educations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personnel_id` bigint(20) UNSIGNED NOT NULL,
  `education_level` enum('Elementary','Secondary','Vocational','College','Graduate Studies') NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `degree_course` varchar(255) DEFAULT NULL,
  `highest_level` varchar(255) DEFAULT NULL,
  `year_graduated` year(4) DEFAULT NULL,
  `from_year` year(4) DEFAULT NULL,
  `to_year` year(4) DEFAULT NULL,
  `honors` varchar(255) DEFAULT NULL,
  `units_earned` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_profiles`
--

CREATE TABLE `employee_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personnel_id` bigint(20) UNSIGNED NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `civil_status` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `blood_type` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_profiles`
--

INSERT INTO `employee_profiles` (`id`, `personnel_id`, `birthdate`, `gender`, `civil_status`, `nationality`, `religion`, `blood_type`, `photo`, `created_at`, `updated_at`) VALUES
(1, 9, '1987-09-09', 'Male', 'Married', 'Filipino', 'Roman Catholic', 'O+', NULL, '2026-07-08 01:44:43', '2026-07-08 01:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `employment_types`
--

CREATE TABLE `employment_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `employee_prefix` varchar(15) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employment_types`
--

INSERT INTO `employment_types` (`id`, `name`, `employee_prefix`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Regular Faculty', 'REGF', 1, NULL, NULL),
(2, 'COS Faculty', 'COSF', 1, NULL, NULL),
(3, 'Administrative Personnel', 'ADMP', 1, NULL, NULL),
(4, 'Non-Teaching Personnel', 'NTP', 1, NULL, NULL),
(5, 'Utility Personnel', 'UTIL', 1, NULL, NULL),
(6, 'Job Order', 'JO', 0, NULL, '2026-07-17 19:38:34'),
(7, 'Contractual Personnel', 'CONT', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employment_type_position`
--

CREATE TABLE `employment_type_position` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employment_type_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employment_type_position`
--

INSERT INTO `employment_type_position` (`id`, `employment_type_id`, `position_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 5, NULL, NULL),
(6, 1, 6, NULL, NULL),
(7, 1, 7, NULL, NULL),
(8, 1, 8, NULL, NULL),
(9, 1, 9, NULL, NULL),
(10, 1, 10, NULL, NULL),
(11, 1, 11, NULL, NULL),
(12, 1, 12, NULL, NULL),
(13, 2, 1, NULL, NULL),
(14, 2, 2, NULL, NULL),
(15, 2, 3, NULL, NULL),
(16, 2, 4, NULL, NULL),
(17, 2, 5, NULL, NULL),
(18, 2, 6, NULL, NULL),
(19, 2, 7, NULL, NULL),
(20, 2, 8, NULL, NULL),
(21, 2, 9, NULL, NULL),
(22, 2, 10, NULL, NULL),
(23, 2, 11, NULL, NULL),
(24, 2, 12, NULL, NULL),
(25, 2, 13, NULL, NULL),
(26, 2, 14, NULL, NULL),
(27, 2, 15, NULL, NULL),
(28, 3, 16, NULL, NULL),
(29, 3, 17, NULL, NULL),
(30, 3, 18, NULL, NULL),
(31, 3, 19, NULL, NULL),
(32, 3, 20, NULL, NULL),
(33, 3, 21, NULL, NULL),
(34, 3, 22, NULL, NULL),
(35, 3, 23, NULL, NULL),
(36, 3, 24, NULL, NULL),
(37, 4, 25, NULL, NULL),
(38, 4, 26, NULL, NULL),
(39, 4, 27, NULL, NULL),
(40, 4, 28, NULL, NULL),
(41, 4, 29, NULL, NULL),
(42, 5, 30, NULL, NULL),
(43, 5, 31, NULL, NULL),
(44, 5, 32, NULL, NULL),
(45, 6, 33, NULL, NULL),
(46, 6, 34, NULL, NULL),
(47, 6, 35, NULL, NULL),
(48, 6, 36, NULL, NULL),
(49, 7, 37, NULL, NULL),
(50, 7, 38, NULL, NULL),
(51, 7, 39, NULL, NULL),
(52, 7, 33, NULL, NULL),
(53, 1, 40, NULL, NULL),
(54, 1, 41, NULL, NULL),
(55, 1, 42, NULL, NULL),
(56, 1, 43, NULL, NULL),
(57, 1, 44, NULL, NULL),
(58, 1, 45, NULL, NULL),
(59, 1, 46, NULL, NULL),
(60, 2, 40, NULL, NULL),
(61, 2, 41, NULL, NULL),
(62, 2, 42, NULL, NULL),
(63, 2, 43, NULL, NULL),
(64, 2, 44, NULL, NULL),
(65, 2, 45, NULL, NULL),
(66, 4, 47, NULL, NULL);

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
  `movement_type` varchar(50) NOT NULL,
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
(811, 801, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(812, 802, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(813, 803, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(814, 804, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(815, 805, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(816, 806, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(817, 807, 'restock', 75, 0, 75, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(818, 808, 'restock', 45, 0, 45, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(819, 809, 'restock', 29, 0, 29, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(820, 810, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(821, 811, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(822, 812, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(823, 813, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(824, 814, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(825, 815, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(826, 816, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(827, 817, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(828, 818, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(829, 819, 'restock', 17, 0, 17, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(830, 820, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(831, 821, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(832, 822, 'restock', 14, 0, 14, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(833, 823, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(834, 824, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(835, 825, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(836, 826, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(837, 827, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(838, 828, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(839, 829, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(840, 830, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(841, 831, 'restock', 32, 0, 32, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(842, 832, 'restock', 11, 0, 11, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(843, 833, 'restock', 9, 0, 9, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(844, 834, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(845, 835, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(846, 836, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(847, 837, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(848, 838, 'restock', 24, 0, 24, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(849, 839, 'restock', 17, 0, 17, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(850, 840, 'restock', 20, 0, 20, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(851, 841, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(852, 842, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(853, 843, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(854, 844, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(855, 845, 'restock', 12, 0, 12, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(856, 846, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(857, 847, 'restock', 26, 0, 26, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(858, 848, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(859, 849, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(860, 850, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(861, 851, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(862, 852, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(863, 853, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(864, 854, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(865, 855, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(866, 856, 'restock', 87, 0, 87, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(867, 857, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(868, 858, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(869, 859, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(870, 860, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(871, 861, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(872, 862, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(873, 863, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(874, 864, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(875, 865, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(876, 866, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(877, 867, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(878, 868, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(879, 869, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(880, 870, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(881, 871, 'restock', 34, 0, 34, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(882, 872, 'restock', 49, 0, 49, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(883, 873, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(884, 874, 'restock', 57, 0, 57, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(885, 875, 'restock', 50, 0, 50, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(886, 876, 'restock', 9, 0, 9, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(887, 877, 'restock', 11, 0, 11, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(888, 878, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(889, 879, 'restock', 9, 0, 9, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(890, 880, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(891, 881, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(892, 882, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(893, 883, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(894, 884, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(895, 885, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(896, 886, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(897, 887, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(898, 888, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(899, 889, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(900, 890, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(901, 891, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(902, 892, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(903, 893, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(904, 894, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(905, 895, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(906, 896, 'restock', 19, 0, 19, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(907, 897, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(908, 898, 'restock', 35, 0, 35, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(909, 899, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(910, 900, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(911, 901, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(912, 902, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(913, 903, 'restock', 17, 0, 17, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(914, 904, 'restock', 35, 0, 35, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(915, 905, 'restock', 18, 0, 18, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(916, 906, 'restock', 175, 0, 175, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(917, 907, 'restock', 36, 0, 36, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(918, 908, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(919, 909, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(920, 910, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(921, 911, 'restock', 100, 0, 100, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(922, 912, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(923, 913, 'restock', 41, 0, 41, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(924, 914, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(925, 915, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(926, 916, 'restock', 38, 0, 38, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(927, 917, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(928, 918, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(929, 919, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(930, 920, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(931, 921, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(932, 922, 'restock', 2000, 0, 2000, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(933, 923, 'restock', 96, 0, 96, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(934, 924, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(935, 925, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(936, 926, 'restock', 30, 0, 30, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(937, 927, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(938, 928, 'restock', 19, 0, 19, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(939, 929, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(940, 930, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(941, 931, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(942, 932, 'restock', 9, 0, 9, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(943, 933, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(944, 934, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(945, 935, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(946, 936, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(947, 937, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(948, 938, 'restock', 47, 0, 47, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(949, 939, 'restock', 40, 0, 40, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(950, 940, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(951, 941, 'restock', 49, 0, 49, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(952, 942, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(953, 943, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(954, 944, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(955, 945, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(956, 946, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(957, 947, 'restock', 90, 0, 90, 'Imported from Excel', 19, '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(958, 948, 'restock', 40, 0, 40, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(959, 949, 'restock', 9, 0, 9, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(960, 950, 'restock', 173, 0, 173, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(961, 951, 'restock', 185, 0, 185, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(962, 952, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(963, 953, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(964, 954, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(965, 955, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(966, 956, 'restock', 19, 0, 19, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(967, 957, 'restock', 327, 0, 327, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(968, 958, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(969, 959, 'restock', 50, 0, 50, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(970, 960, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(971, 961, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(972, 962, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(973, 963, 'restock', 9, 0, 9, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(974, 964, 'restock', 14, 0, 14, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(975, 965, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(976, 966, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(977, 967, 'restock', 33, 0, 33, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(978, 968, 'restock', 20, 0, 20, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(979, 969, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(980, 970, 'restock', 51, 0, 51, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(981, 971, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(982, 972, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(983, 973, 'restock', 64, 0, 64, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(984, 974, 'restock', 43, 0, 43, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(985, 975, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(986, 976, 'restock', 103, 0, 103, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(987, 977, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(988, 978, 'restock', 9, 0, 9, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(989, 979, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(990, 980, 'restock', 19, 0, 19, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(991, 981, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(992, 982, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(993, 983, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(994, 984, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(995, 985, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(996, 986, 'restock', 20, 0, 20, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(997, 987, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(998, 988, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(999, 989, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1000, 990, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1001, 991, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1002, 992, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1003, 993, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1004, 994, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1005, 995, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1006, 996, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1007, 997, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1008, 998, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1009, 999, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1010, 1000, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1011, 1001, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1012, 1002, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1013, 1003, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1014, 1004, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1015, 1005, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1016, 1006, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1017, 1007, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1018, 1008, 'restock', 23, 0, 23, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1019, 1009, 'restock', 11, 0, 11, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1020, 1010, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1021, 1011, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1022, 1012, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1023, 1013, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1024, 1014, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1025, 1015, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1026, 1016, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1027, 1017, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1028, 1018, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1029, 1019, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1030, 1020, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1031, 1021, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1032, 1022, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1033, 1023, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1034, 1024, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1035, 1025, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1036, 1026, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1037, 1027, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1038, 1028, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1039, 1029, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1040, 1030, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1041, 1031, 'restock', 0, 0, 0, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1042, 1032, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1043, 1033, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1044, 1034, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1045, 1035, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1046, 1036, 'restock', 16, 0, 16, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1047, 1037, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1048, 1038, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1049, 1039, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1050, 1040, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1051, 1041, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1052, 1042, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1053, 1043, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1054, 1044, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1055, 1045, 'restock', 74, 0, 74, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1056, 1046, 'restock', 22, 0, 22, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1057, 1047, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1058, 1048, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1059, 1049, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1060, 1050, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1061, 1051, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1062, 1052, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1063, 1053, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1064, 1054, 'restock', 23, 0, 23, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1065, 1055, 'restock', 13, 0, 13, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1066, 1056, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1067, 1057, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1068, 1058, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1069, 1059, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1070, 1060, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1071, 1061, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1072, 1062, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1073, 1063, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1074, 1064, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1075, 1065, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1076, 1066, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1077, 1067, 'restock', 46, 0, 46, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1078, 1068, 'restock', 79, 0, 79, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1079, 1069, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1080, 1070, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1081, 1071, 'restock', 12, 0, 12, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1082, 1072, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1083, 1073, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1084, 1074, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1085, 1075, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1086, 1076, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1087, 1077, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1088, 1078, 'restock', 22, 0, 22, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1089, 1079, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1090, 1080, 'restock', 23, 0, 23, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1091, 1081, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1092, 1082, 'restock', 23, 0, 23, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1093, 1083, 'restock', 7, 0, 7, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1094, 1084, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1095, 1085, 'restock', 25, 0, 25, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1096, 1086, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1097, 1087, 'restock', 29, 0, 29, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1098, 1088, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1099, 1089, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1100, 1090, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1101, 1091, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1102, 1092, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1103, 1093, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1104, 1094, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1105, 1095, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1106, 1096, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1107, 1097, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1108, 1098, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1109, 1099, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1110, 1100, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1111, 1101, 'restock', 1205, 0, 1205, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1112, 1102, 'restock', 10, 0, 10, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1113, 1103, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1114, 1104, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1115, 1105, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1116, 1106, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1117, 1107, 'restock', 4, 0, 4, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1118, 1108, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1119, 1109, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1120, 1110, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1121, 1111, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1122, 1112, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1123, 1113, 'restock', 3, 0, 3, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1124, 1114, 'restock', 11, 0, 11, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1125, 1115, 'restock', 18, 0, 18, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1126, 1116, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1127, 1117, 'restock', 13, 0, 13, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1128, 1118, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1129, 1119, 'restock', 9, 0, 9, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1130, 1120, 'restock', 5, 0, 5, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1131, 1121, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1132, 1122, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1133, 1123, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1134, 1124, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1135, 1125, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1136, 1126, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1137, 1127, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1138, 1128, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1139, 1129, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1140, 1130, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1141, 1131, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1142, 1132, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1143, 1133, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1144, 1134, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1145, 1135, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1146, 1136, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1147, 1137, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1148, 1138, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1149, 1139, 'restock', 6, 0, 6, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1150, 1140, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1151, 1141, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1152, 1142, 'restock', 8, 0, 8, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1153, 1143, 'restock', 36, 0, 36, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1154, 1144, 'restock', 1, 0, 1, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1155, 1145, 'restock', 2, 0, 2, 'Imported from Excel', 19, '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1156, 1146, 'initial_stock', 7, 0, 7, 'Initial stock added', 19, '2026-07-15 20:49:29', '2026-07-15 20:49:29'),
(1157, 1147, 'initial_stock', 1, 0, 1, 'Initial stock added', 19, '2026-07-15 20:50:11', '2026-07-15 20:50:11'),
(1158, 1147, 'restock', 18, 1, 19, NULL, 19, '2026-07-15 20:51:49', '2026-07-15 20:51:49'),
(1159, 1146, 'restock', 24, 7, 31, NULL, 19, '2026-07-15 20:53:17', '2026-07-15 20:53:17'),
(1160, 1148, 'initial_stock', 50, 0, 50, 'Initial stock added', 19, '2026-07-15 20:54:57', '2026-07-15 20:54:57'),
(1161, 1149, 'initial_stock', 16, 0, 16, 'Initial stock added', 19, '2026-07-15 20:55:20', '2026-07-15 20:55:20'),
(1162, 1150, 'initial_stock', 19, 0, 19, 'Initial stock added', 19, '2026-07-15 20:55:56', '2026-07-15 20:55:56'),
(1163, 1151, 'initial_stock', 96, 0, 96, 'Initial stock added', 19, '2026-07-15 20:58:58', '2026-07-15 20:58:58'),
(1164, 1152, 'initial_stock', 44, 0, 44, 'Initial stock added', 19, '2026-07-15 21:01:40', '2026-07-15 21:01:40'),
(1165, 1153, 'initial_stock', 84, 0, 84, 'Initial stock added', 19, '2026-07-15 21:02:16', '2026-07-15 21:02:16'),
(1166, 1154, 'initial_stock', 100, 0, 100, 'Initial stock added', 19, '2026-07-15 21:08:08', '2026-07-15 21:08:08'),
(1167, 1154, 'request', 2, 100, 98, 'Walk-In Issue #WI-20260716070952', 19, '2026-07-15 23:09:52', '2026-07-15 23:09:52'),
(1168, 1154, 'request', 3, 98, 95, 'Walk-In Issue #WI-20260716070952', 19, '2026-07-15 23:09:52', '2026-07-15 23:09:52'),
(1174, 1166, 'initial_stock', 0, 0, 0, 'Created via PPMP item entry', 21, '2026-07-16 21:11:11', '2026-07-16 21:11:11'),
(1175, 1154, 'request', 5, 95, 90, 'Request #: MR-2026-0001 | Requested by: joy', 19, '2026-07-16 23:28:15', '2026-07-16 23:28:15'),
(1176, 1154, 'request', 5, 90, 85, 'Request #: MR-2026-0044 | Requested by: regene', 19, '2026-07-17 00:07:56', '2026-07-17 00:07:56'),
(1178, 1154, 'request', 5, 85, 80, 'Walk-In Issue #WI-20260718035555', 19, '2026-07-17 19:55:55', '2026-07-17 19:55:55'),
(1182, 1154, 'request', 4, 80, 76, 'Request #: MR-2026-0045 | Requested by: joseph', 19, '2026-07-17 20:45:14', '2026-07-17 20:45:14');

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
  `classification_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `threshold` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `quantity`, `category_id`, `department_id`, `classification_id`, `unit_id`, `created_by`, `created_at`, `updated_at`, `threshold`) VALUES
(801, 'Biglite Fire Emergency Evacuation Sign', 2, 8, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 22:50:07', 10),
(802, 'Royu Electrical Devices', 3, 8, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 22:50:07', 10),
(803, 'Firefly Bulb', 4, 8, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 22:50:07', 10),
(804, 'Akari LED Solar', 3, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(805, 'Delta Pre-heat Start Ballast (PCS)', 6, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(806, 'ETS Smoke Alarm', 2, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(807, 'GES LED Bulb MECQ', 75, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(808, 'CONCATA LED Bulb', 45, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(809, 'GES LED Bulb', 29, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(810, 'ENERGIZER Battery AAA', 0, 8, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(811, 'ENERGIZER Battery AA', 7, 8, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(812, 'EVEREADY Battery 9V', 4, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(813, 'Alarm Buttons', 3, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(814, 'ROYU Safety Breaker 9', 1, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(815, 'ROYU Outlet', 0, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(816, 'FIREFLY Bulb 15w', 1, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(817, 'HIGH SPEED 3.0 USB Cable', 1, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(818, 'DELTA Pre-heat Start Ballast (BOX)', 4, 13, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(819, '2A Quartz Clock', 17, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(820, 'Best Guard Riveter', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(821, 'MGK Binder Clips', 4, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(822, 'Firefly Security Light', 14, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(823, 'Veco Record Book', 0, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(824, 'NEC Type 32 Telephone Set', 3, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(825, 'Iron Grip Wall Mount', 2, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(826, 'Stanley Door Stopper', 4, 13, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(827, 'Power House Door knob', 0, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(828, 'KJJ Vacuum', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(829, 'Elmer’s Glue', 8, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(830, 'Book End', 4, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(831, 'Reston Safety Googles', 32, 14, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(832, 'Joy Binder Clips', 11, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(833, 'Excel Binder Clips', 9, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(834, 'JDWJ Round Clips 75mm (BOX)', 7, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(835, 'JDWJ Round Clips 75mm (PACK)', 3, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(836, 'JDWJ Round Clips 75mm (PCS)', 4, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(837, 'Joy Sharpener', 3, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(838, 'A4Tech Keyboard KRS5', 24, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(839, 'A4Tech Keyboard KRS85', 17, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(840, 'A4Tech Keyboard KRS83', 20, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(841, 'ACER Keyboard', 3, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(842, 'HDMI Cable', 3, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(843, 'Adaptor', 0, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(844, 'JYC Crimping Tool', 4, 15, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(845, 'Germany Crimping Tool', 12, 15, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(846, 'Suntech USB Type B Cable', 8, 7, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(847, 'Genius Keyboard KB-110', 26, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(848, 'Intex Keyboard IT-2014B', 1, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(849, 'TV Wall Mount', 5, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(850, 'DELL Keyboard KM816', 1, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(851, 'HP Toner Cartridge W2091A', 2, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(852, 'AWP UPS AIDE 400 -1000 VA', 4, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(853, 'RAPOO USB Stereo Headset', 2, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(854, 'WIN Amplified Multimedia Hi-Fi Speaker', 1, 7, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(855, 'RJ45', 6, 13, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(856, 'CD Case', 87, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(857, 'HP-RW', 2, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(858, 'WD Tool Pouch', 3, 15, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(859, 'AMASCO LED Lamp', 5, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(860, 'Proteger LED Water Proof Search Light', 2, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(861, 'KOTEN Safety Breaker', 2, 8, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(862, 'X-BALOG Head Lamp', 4, 8, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(863, 'Portable Flashlight', 1, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(864, 'HBW Permanent Marker Ink Refill', 3, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(865, 'HBW Red Marker', 4, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(866, 'Dixon Highlighter', 5, 13, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(867, 'FOXCEL Heavy Duty Staples', 5, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(868, 'Printo Stapler Remover', 6, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(869, 'PRIMAX Liquid Ink Freeliner', 2, 13, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(870, 'DELI Permanent Marker', 4, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(871, 'HB Pencil', 34, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(872, 'Glue Stick (Small)', 49, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(873, 'MONGOL Pencil', 1, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(874, 'HBW Ballpen - Batch 1', 57, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(875, 'HBW Ballpen - Batch 2', 50, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(876, 'PILOT Whiteboard Marker Refill Ink', 9, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(877, 'EXCEL Highlighter', 11, 13, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(878, 'Dustless Chalk', 1, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(879, 'Post It Notepads', 9, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(880, 'JOY Correction Tape', 0, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(881, 'JOY Push Pin', 2, 13, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(882, 'SCOTCH Magic Tap', 1, 13, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(883, 'POINTER Index Tabs', 2, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(884, 'DO IT Luggage Tags', 3, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(885, 'PRINTO Ruler', 2, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(886, 'EXCELLENT PRINTO Board Eraser (BOX)', 4, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(887, 'EXCELLENT PRINTO Board Eraser (PCS)', 2, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(888, 'MGK Binder clips (BOX)', 3, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(889, 'MGK Binder clips (PCS)', 3, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(890, 'JDWJ 50mm Round Clips', 4, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(891, 'EXCELLENT KING Board Eraser (BOX)', 5, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(892, 'EXCELLENT KING Board Eraser (PCS)', 10, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(893, 'JDWJ 22mm Round Clips', 1, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(894, 'BOSTON Staples (BOX)', 1, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(895, 'BOSTON Staples (PACK)', 10, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(896, 'ETONA Staples', 19, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(897, 'JOY Staples (BOX)', 6, 6, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(898, 'JOY Staples (PACK)', 35, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(899, 'WORX Long Board Paper', 0, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(900, 'AGPAPHOTO A4 Laminating Film', 1, 13, 6, NULL, 2, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(901, 'KING Legal Size L-Type Folder', 5, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(902, 'LUCKY File Bag', 7, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(903, 'WORX 8.5x13 Specialty Paper', 17, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(904, 'WORX A4,90gsm Specialty Paper - Batch 1', 35, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(905, 'WORX A4,90gsm Specialty Paper - Batch 2', 18, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(906, 'WORX A4,200gsm Specialty Board - Batch 1', 175, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(907, 'WORX A4,200gsm Specialty Board - Batch 2', 36, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(908, 'JOJO Photopaper 180g/m2', 3, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(909, 'A4 Document Frame', 6, 6, 6, 195, 9, 19, '2026-07-15 20:42:04', '2026-07-16 21:38:03', 15),
(910, 'Brown Envelope Short', 0, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(911, '8.50x11 Certificate Holder', 100, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(912, 'Binder Envelope', 0, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(913, 'SUPERFAX A4 Sensitized Film', 41, 13, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(914, 'QUAFF A4 Laminating Film - Batch 1', 10, 13, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(915, 'QUAFF A4 Laminating Film - Batch 2', 2, 13, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(916, 'SUKI/GENTLE PRINCE DTR Card', 38, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(917, 'KING FILES Clear Book - Batch 1', 10, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(918, 'KING FILES Clear Book - Batch 2', 8, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(919, 'File Folder w/index tabs', 6, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(920, 'ADVENTURER BlueCard Case', 5, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(921, 'EXCEL Glossy Sticker Paper', 1, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(922, 'DTR Card', 2000, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(923, 'IMARI 8.5x11 Glossy Paper', 96, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(924, 'QUAFF A4 Calling Card Paper', 1, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(925, 'QUAFF A4 Glossy Photo Sticker', 1, 13, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(926, 'Sliding Folder - Batch 1', 30, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(927, 'Sliding Folder - Batch 2', 5, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(928, 'Stapler w/remover', 19, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(929, 'PILOT Broad Super Color Marker', 3, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(930, 'DONG-A Gel Ink', 6, 13, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(931, 'PRINTO Stamp Pad Ink', 7, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(932, 'EXCEL Chisel Point #10 Staples', 9, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(933, 'ASIAN 5x8 Index Card', 7, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(934, 'ASIAN 3x5 Index Card', 5, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(935, 'ZHIJIN Enice Ballpen', 0, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(936, 'FLEX OFFICE Ballpen', 0, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(937, 'HBW Whiteboard Marker Ink Refill', 3, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(938, 'Glue Stick (Big)', 47, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(939, 'NINGTAI Card  Case (PACK)', 40, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(940, 'NINGTAI Card  Case (PCS)', 6, 6, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(941, 'QUAFF Calling Card', 49, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(942, 'TOLSEN Air Tools Kit', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(943, 'MAKITA Jigsaw', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(944, 'AVR', 2, 7, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 8),
(945, 'Mega Phone', 5, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(946, 'STANDARD Duct Fan', 4, 13, 6, NULL, 9, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 10),
(947, 'IMARI Glossy Paper A3', 90, 6, 6, NULL, 5, 19, '2026-07-15 20:42:04', '2026-07-15 21:19:51', 15),
(948, 'KODAK Photo Glossy Paper for Inkjet Prints', 40, 6, 6, NULL, 5, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(949, 'OFFICEMAX Coin Envelope 8 ½ size', 9, 6, 6, NULL, 2, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(950, 'P/X Expanding Folder Green', 173, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(951, 'P/X Expanding Folder Red', 185, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(952, 'P/X Expanding Folder Yellow', 10, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(953, 'P/X Expanding Folder Orange', 1, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(954, 'P/X Expanding Folder Blue', 4, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(955, 'P/X Expanding Folder Violet', 3, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(956, 'ADVENTURER Certificate Holder Red', 19, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(957, 'Long Envelope', 327, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(958, 'QUAFF Comb Binding Machine', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(959, 'ADVENTURER Certificate Holder Blue', 50, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(960, 'UPRIGHT Laminator Mini', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(961, 'MIYAMI Laminator Big', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(962, 'STAR Money Detector', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(963, 'AOC Monitor', 9, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(964, 'D-Ring Binder White', 14, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(965, 'D-Ring Binder Red', 4, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(966, 'D-Ring Binder Blue', 0, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(967, 'EPSON Ink (For Printer) Black', 33, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(968, 'Diploma Holder', 20, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(969, 'KRAFT Document Envelopes A4', 6, 6, 6, NULL, 2, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(970, 'EPSON Ink (For Printer) Cyan', 51, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(971, 'CLASSIC White Envelope size 10', 5, 6, 6, NULL, 2, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(972, 'PHOENIX Expanding Envelope (BOX)', 6, 6, 6, NULL, 2, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(973, 'PHOENIX Expanding Envelope (PCS)', 64, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(974, 'Document File - Batch 1', 43, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(975, 'Document File - Batch 2', 5, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(976, 'Multi Copy Paper A4', 103, 6, 6, NULL, 10, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(977, 'Avant Garde Quick Ladder AG53009 8m', 10, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(978, 'Leap PQ9907S Digital Chess Timer', 9, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(979, 'Verza Chess Clock', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(980, 'Competition Ball (sepak takraw)', 19, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(981, 'Table Tennis Net', 5, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(982, 'Double Happiness Table Tennis Net', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(983, 'Table Tennis Racket', 6, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(984, 'Aristo Stopwatch Series', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(985, 'Super K SK6128 Professional Quartz Timer', 2, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(986, 'Orange Traffic Cone', 20, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(987, 'Chessboard Set', 2, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(988, 'Cima Manual Scoreboard', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(989, 'Digital Scoreboard', 3, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(990, 'Stix Headgear (blue)', 2, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(991, 'Stix Headgear (red) (PCS) - Batch 1', 1, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(992, 'Swimfit Swimming Goggles', 5, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(993, 'Boxing Gloves Pair (blue)', 3, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(994, 'Boxing Gloves Pair (red)', 3, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(995, 'Kix Taekwondo Headgear (red)', 7, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(996, 'Kix Taekwondo Headgear (blue)', 4, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(997, 'Guide Overgrip', 0, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(998, 'Stix Headgear (red) (PCS) - Batch 2', 3, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(999, 'Kix Taekwondo Shin Guard Pair (red)', 4, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1000, 'Kix Taekwondo Shin Guard Pair (blue)', 3, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1001, 'Clever Shin and Instep Guard Pair (red)', 1, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1002, 'Taekwondo Gloves Pair (white)', 1, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1003, 'Excalibur Boxing Helmet (black)', 2, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1004, 'K-SPORT Boxing Headgear (black)', 2, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1005, 'Boxing Headgear (red)', 0, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1006, 'Chess Mat Set', 10, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1007, 'Scrabble Wooden Board Set', 1, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1008, 'Kix Taekwondo Body Armor', 23, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1009, 'Kix Taekwondo Kick Pad', 11, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1010, 'Stix Groin Guard (male)', 5, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1011, 'Kix Groin Guard (male)', 2, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1012, 'Stix Groin Guard (female)', 5, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1013, 'Kix Groin Guard (female)', 4, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1014, 'Kang O Fitness Boxing Hand Wraps 3m (black)', 0, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1015, 'Kang O Fitness Boxing Hand Wraps 3m (red)', 0, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1016, 'Ciever Master Boxing Hand Wraps', 1, 18, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1017, 'Super K Coaching Board', 5, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1018, 'Super K Frisbee (orange)', 3, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1019, 'Super K Frisbee (dark green)', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1020, 'Super K Frisbee (green)', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1021, 'Classic Fox40 Referee Whistle (dark blue)', 3, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1022, 'Classic Fox40 RefereeWhistle (black)', 2, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1023, 'Elite Fitness Yoga Mat', 4, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1024, 'Pogo Stick', 2, 18, 3, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 22:53:46', 8),
(1025, 'Mizuno Tennis Racket', 1, 18, 3, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 22:53:46', 8),
(1026, 'Speedrino Tennis Racket', 1, 18, 3, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 22:53:46', 8),
(1027, 'Yonex Badminton', 3, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1028, 'GTO Tennis Net', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1029, 'Table Tennis Net and Post Set', 1, 18, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1030, 'Stix Arnis Hand Gloves Pair', 3, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1031, 'Super K Table Tennis Net and Post Set', 0, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1032, 'Ys-902 Digital Chess timer', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1033, 'Kix Taekwondo Kick Shield', 4, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1034, 'Molten B64500 Basketball', 6, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1035, 'Mikasa  Volleyball', 3, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1036, 'File Organizer with separator', 16, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1037, 'Ruler - Batch 1', 2, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1038, 'Ruler - Batch 2', 2, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1039, 'Hardcopy Bond Paper', 1, 6, 6, NULL, 2, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1040, 'Paper Clips', 7, 6, 6, NULL, 5, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1041, 'Omni PVC Utility Box', 1, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1042, 'ROYU 1 gang aircon outlit', 2, 8, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1043, 'ROYU Safety Breaker', 1, 8, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1044, 'Jiabao Charger Battery', 1, 8, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1045, 'Fuse', 74, 8, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1046, 'Firefly Regular Plug Socket - Batch 1', 22, 8, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1047, 'Firefly Regular Plug Socket - Batch 2', 8, 8, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1048, 'Caution Tape', 4, 14, 6, NULL, 12, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1049, 'Masking Tape - Batch 1', 8, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1050, 'Masking Tape - Batch 2', 8, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1051, 'Electrical Tape', 4, 8, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1052, 'Duct TAPE', 4, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1053, 'Air Purifier', 5, 1, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1054, 'Archfile folder', 23, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1055, 'Circuit Breaker', 13, 8, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1056, 'XLR Cable Connector', 2, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1057, 'Curtain Rod', 8, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1058, 'High Pressure Washer', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1059, 'Fire Extinguisher', 3, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1060, 'Portable Basket Ball Stand', 1, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1061, 'Kelver Speaker', 2, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1062, 'Top pro Speaker', 2, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1063, 'DEECO 2-pin AC power cords', 8, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1064, 'Heavy duty lock', 2, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1065, 'Admiral Paper Shredder', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1066, 'CD-R King Paper shredder', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1067, 'Epson Ribbon Cartridge - Batch 1', 46, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1068, 'Epson Ribbon Cartridge - Batch 2', 79, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1069, 'External hard drive pouch', 3, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1070, 'Ethyl Alcohol', 10, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1071, 'Tissue', 12, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1072, 'Liquid hand sanitizer', 4, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1073, 'Safe guard Hand liquid soap', 6, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1074, 'Greencross sanitizing Gel', 7, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1075, 'Zim glass cleaner', 1, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1076, 'Zim liquid Zosa', 5, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1077, 'Power house Furniture Polish', 4, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1078, 'Splenda Furniture polish', 22, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1079, 'Sponge', 6, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1080, 'Detergent Bar soap', 23, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1081, 'Mop refill', 2, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1082, 'Scrubbing pad', 23, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1083, 'Air freshener', 7, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1084, 'Ethly alcohol', 6, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1085, 'Dust pan', 25, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1086, 'Rubber mat', 8, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1087, 'Hanger', 29, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1088, 'Toilet brush', 6, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1089, 'Rubbing compound', 1, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1090, 'Bunot', 1, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1091, 'Window cleaning scarper', 1, 17, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1092, 'Come Binding Machine', 1, 15, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1093, 'Speaker Stand', 2, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1094, 'Projection screen', 4, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1095, 'Trident mixer 16 Channel', 1, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1096, 'Tempes power mixer 8 Channel', 1, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1097, 'ASR key board', 1, 6, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 15),
(1098, 'UPS Power supply', 1, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1099, 'ABR Power Supply', 1, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1100, 'HDTV Cable', 2, 7, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1101, 'Diploma jacket', 1205, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1102, 'Blue training cone', 10, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1103, 'Classic FOX40 referee whistle (white)', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1104, 'Molten 3x3 basketball', 4, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1105, 'Molten Soccer ball', 2, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1106, 'Baseball Homeplate', 5, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1107, 'BBSWIM kick board', 4, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1108, 'Jump rope', 3, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1109, 'Sepak takraw', 3, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1110, 'Basketball ring net', 2, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1111, 'Life jacket', 3, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1112, 'Stix Body armor', 3, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1113, 'Goal keeper gloves', 3, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1114, 'Shin football guard', 11, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1115, 'Light Arnis (Red)', 18, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1116, 'Light Arnis (Blue)', 8, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1117, 'Arnis', 13, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1118, 'Stix arm guard (Red)', 6, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1119, 'Stix arm guard (Blue)', 9, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1120, 'Stix leg guard (Red)', 5, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1121, 'Stix leg guard (blue)', 8, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1122, 'Hand paddle', 1, 13, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1123, 'Swimming cup', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1124, 'Kangrui gloves (Red)', 1, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1125, 'Kangrui gloves (Blue)', 1, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1126, 'Kangrui shin and instep guard (Blue)', 1, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1127, 'Kangrui shin and instep guard (Red)', 1, 14, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1128, 'Kangrui body armor', 2, 14, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1129, 'Table tennis net holder', 1, 18, 6, NULL, 11, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1130, 'Master quartz chest timer', 6, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1131, 'Quartz chest timer', 1, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1132, 'Lineup discuss throw 2kg', 2, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1133, 'Lineup discuss throw 1kg', 2, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1134, 'Lineup shotput 4kg', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1135, 'Pro sport discuss throw 2kg', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1136, 'Pro sport discuss throw 1kg', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1137, 'Pro sport shotput 4kg', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1138, 'Super-k shotput', 2, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1139, 'Super-k relay baton', 6, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1140, 'Shuttle cock', 2, 13, 6, NULL, 13, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1141, 'Weigth scale', 1, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1142, 'Arnis bag', 8, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1143, 'Puzzle mat', 36, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1144, 'Arnis sword props', 1, 18, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 8),
(1145, 'Foam', 2, 13, 6, NULL, 9, 19, '2026-07-15 20:42:05', '2026-07-15 21:19:51', 10),
(1146, 'Lovein Gel Ink Pen (Red)', 31, 17, 3, NULL, 9, 19, '2026-07-15 20:49:29', '2026-07-15 21:19:51', 15),
(1147, 'LOVEIN Gel Ink Pen (Blue)', 19, 17, 3, NULL, 9, 19, '2026-07-15 20:50:11', '2026-07-15 21:19:51', 15),
(1148, 'PILOT Permanent Marker (Red)', 50, 17, 3, NULL, 9, 19, '2026-07-15 20:54:57', '2026-07-15 21:19:51', 15),
(1149, 'PILOT Permanent Marker (Blue)', 16, 17, 3, NULL, 9, 19, '2026-07-15 20:55:20', '2026-07-15 21:19:51', 15),
(1150, 'PILOT Permanent Marker (Black)', 19, 17, 3, NULL, 9, 19, '2026-07-15 20:55:56', '2026-07-15 21:19:51', 15),
(1151, 'LCT Bulldog Clip (38mm)', 96, 17, 3, NULL, 9, 19, '2026-07-15 20:58:58', '2026-07-15 21:19:51', 15),
(1152, 'LCT Bulldog Clip (51mm)', 44, 17, 3, NULL, 9, 19, '2026-07-15 21:01:40', '2026-07-15 21:19:51', 15),
(1153, 'LCT Bulldog Clip', 84, 17, 3, NULL, 9, 19, '2026-07-15 21:02:16', '2026-07-15 21:19:51', 15),
(1154, 'for testing', 76, 18, 3, NULL, 9, 19, '2026-07-15 21:08:08', '2026-07-17 20:45:14', 8),
(1166, 'Printer', 0, 7, 1, 15, 2, 21, '2026-07-16 21:11:11', '2026-07-16 21:11:11', 1);

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
(842, 801, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(843, 802, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(844, 803, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(845, 804, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(846, 805, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(847, 806, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(848, 807, 19, 'stock_in', 75, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(849, 808, 19, 'stock_in', 45, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(850, 809, 19, 'stock_in', 29, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(851, 810, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(852, 811, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(853, 812, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(854, 813, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(855, 814, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(856, 815, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(857, 816, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(858, 817, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(859, 818, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(860, 819, 19, 'stock_in', 17, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(861, 820, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(862, 821, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(863, 822, 19, 'stock_in', 14, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(864, 823, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(865, 824, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(866, 825, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(867, 826, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(868, 827, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(869, 828, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(870, 829, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(871, 830, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(872, 831, 19, 'stock_in', 32, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(873, 832, 19, 'stock_in', 11, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(874, 833, 19, 'stock_in', 9, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(875, 834, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(876, 835, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(877, 836, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(878, 837, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(879, 838, 19, 'stock_in', 24, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(880, 839, 19, 'stock_in', 17, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(881, 840, 19, 'stock_in', 20, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(882, 841, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(883, 842, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(884, 843, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(885, 844, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(886, 845, 19, 'stock_in', 12, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(887, 846, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(888, 847, 19, 'stock_in', 26, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(889, 848, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(890, 849, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(891, 850, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(892, 851, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(893, 852, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(894, 853, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(895, 854, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(896, 855, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(897, 856, 19, 'stock_in', 87, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(898, 857, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(899, 858, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(900, 859, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(901, 860, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(902, 861, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(903, 862, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(904, 863, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(905, 864, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(906, 865, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(907, 866, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(908, 867, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(909, 868, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(910, 869, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(911, 870, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(912, 871, 19, 'stock_in', 34, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(913, 872, 19, 'stock_in', 49, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(914, 873, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(915, 874, 19, 'stock_in', 57, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(916, 875, 19, 'stock_in', 50, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(917, 876, 19, 'stock_in', 9, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(918, 877, 19, 'stock_in', 11, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(919, 878, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(920, 879, 19, 'stock_in', 9, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(921, 880, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(922, 881, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(923, 882, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(924, 883, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(925, 884, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(926, 885, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(927, 886, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(928, 887, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(929, 888, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(930, 889, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(931, 890, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(932, 891, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(933, 892, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(934, 893, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(935, 894, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(936, 895, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(937, 896, 19, 'stock_in', 19, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(938, 897, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(939, 898, 19, 'stock_in', 35, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(940, 899, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(941, 900, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(942, 901, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(943, 902, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(944, 903, 19, 'stock_in', 17, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(945, 904, 19, 'stock_in', 35, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(946, 905, 19, 'stock_in', 18, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(947, 906, 19, 'stock_in', 175, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(948, 907, 19, 'stock_in', 36, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(949, 908, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(950, 909, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(951, 910, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(952, 911, 19, 'stock_in', 100, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(953, 912, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(954, 913, 19, 'stock_in', 41, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(955, 914, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(956, 915, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(957, 916, 19, 'stock_in', 38, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(958, 917, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(959, 918, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(960, 919, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(961, 920, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(962, 921, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(963, 922, 19, 'stock_in', 2000, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(964, 923, 19, 'stock_in', 96, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(965, 924, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(966, 925, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(967, 926, 19, 'stock_in', 30, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(968, 927, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(969, 928, 19, 'stock_in', 19, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(970, 929, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(971, 930, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(972, 931, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(973, 932, 19, 'stock_in', 9, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(974, 933, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(975, 934, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(976, 935, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(977, 936, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(978, 937, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(979, 938, 19, 'stock_in', 47, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(980, 939, 19, 'stock_in', 40, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(981, 940, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(982, 941, 19, 'stock_in', 49, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(983, 942, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(984, 943, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(985, 944, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(986, 945, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(987, 946, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(988, 947, 19, 'stock_in', 90, 'Imported from Excel', '2026-07-15 20:42:04', '2026-07-15 20:42:04'),
(989, 948, 19, 'stock_in', 40, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(990, 949, 19, 'stock_in', 9, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(991, 950, 19, 'stock_in', 173, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(992, 951, 19, 'stock_in', 185, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(993, 952, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(994, 953, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(995, 954, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(996, 955, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(997, 956, 19, 'stock_in', 19, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(998, 957, 19, 'stock_in', 327, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(999, 958, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1000, 959, 19, 'stock_in', 50, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1001, 960, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1002, 961, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1003, 962, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1004, 963, 19, 'stock_in', 9, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1005, 964, 19, 'stock_in', 14, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1006, 965, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1007, 966, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1008, 967, 19, 'stock_in', 33, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1009, 968, 19, 'stock_in', 20, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1010, 969, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1011, 970, 19, 'stock_in', 51, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1012, 971, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1013, 972, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1014, 973, 19, 'stock_in', 64, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1015, 974, 19, 'stock_in', 43, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1016, 975, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1017, 976, 19, 'stock_in', 103, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1018, 977, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1019, 978, 19, 'stock_in', 9, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1020, 979, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1021, 980, 19, 'stock_in', 19, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1022, 981, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1023, 982, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1024, 983, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1025, 984, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1026, 985, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1027, 986, 19, 'stock_in', 20, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1028, 987, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1029, 988, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1030, 989, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1031, 990, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1032, 991, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1033, 992, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1034, 993, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1035, 994, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1036, 995, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1037, 996, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1038, 997, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1039, 998, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1040, 999, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1041, 1000, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1042, 1001, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1043, 1002, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1044, 1003, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1045, 1004, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1046, 1005, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1047, 1006, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1048, 1007, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1049, 1008, 19, 'stock_in', 23, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1050, 1009, 19, 'stock_in', 11, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1051, 1010, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1052, 1011, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1053, 1012, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1054, 1013, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1055, 1014, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1056, 1015, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1057, 1016, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1058, 1017, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1059, 1018, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1060, 1019, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1061, 1020, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1062, 1021, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1063, 1022, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1064, 1023, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1065, 1024, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1066, 1025, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1067, 1026, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1068, 1027, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1069, 1028, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1070, 1029, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1071, 1030, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1072, 1031, 19, 'stock_in', 0, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1073, 1032, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1074, 1033, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1075, 1034, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1076, 1035, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1077, 1036, 19, 'stock_in', 16, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1078, 1037, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1079, 1038, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1080, 1039, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1081, 1040, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1082, 1041, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1083, 1042, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1084, 1043, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1085, 1044, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1086, 1045, 19, 'stock_in', 74, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1087, 1046, 19, 'stock_in', 22, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1088, 1047, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1089, 1048, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1090, 1049, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1091, 1050, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1092, 1051, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1093, 1052, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1094, 1053, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1095, 1054, 19, 'stock_in', 23, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1096, 1055, 19, 'stock_in', 13, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1097, 1056, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1098, 1057, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1099, 1058, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1100, 1059, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1101, 1060, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1102, 1061, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1103, 1062, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1104, 1063, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1105, 1064, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1106, 1065, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1107, 1066, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1108, 1067, 19, 'stock_in', 46, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1109, 1068, 19, 'stock_in', 79, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1110, 1069, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1111, 1070, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1112, 1071, 19, 'stock_in', 12, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1113, 1072, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1114, 1073, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1115, 1074, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1116, 1075, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1117, 1076, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1118, 1077, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1119, 1078, 19, 'stock_in', 22, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1120, 1079, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1121, 1080, 19, 'stock_in', 23, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1122, 1081, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1123, 1082, 19, 'stock_in', 23, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1124, 1083, 19, 'stock_in', 7, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1125, 1084, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1126, 1085, 19, 'stock_in', 25, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1127, 1086, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1128, 1087, 19, 'stock_in', 29, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1129, 1088, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1130, 1089, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1131, 1090, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1132, 1091, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1133, 1092, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1134, 1093, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1135, 1094, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1136, 1095, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1137, 1096, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1138, 1097, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1139, 1098, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1140, 1099, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1141, 1100, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1142, 1101, 19, 'stock_in', 1205, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1143, 1102, 19, 'stock_in', 10, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1144, 1103, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1145, 1104, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1146, 1105, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1147, 1106, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1148, 1107, 19, 'stock_in', 4, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1149, 1108, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1150, 1109, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1151, 1110, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1152, 1111, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1153, 1112, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1154, 1113, 19, 'stock_in', 3, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1155, 1114, 19, 'stock_in', 11, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1156, 1115, 19, 'stock_in', 18, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1157, 1116, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1158, 1117, 19, 'stock_in', 13, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1159, 1118, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1160, 1119, 19, 'stock_in', 9, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1161, 1120, 19, 'stock_in', 5, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1162, 1121, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1163, 1122, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1164, 1123, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1165, 1124, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1166, 1125, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1167, 1126, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1168, 1127, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1169, 1128, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1170, 1129, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1171, 1130, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1172, 1131, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1173, 1132, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1174, 1133, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1175, 1134, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1176, 1135, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1177, 1136, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1178, 1137, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1179, 1138, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1180, 1139, 19, 'stock_in', 6, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1181, 1140, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1182, 1141, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1183, 1142, 19, 'stock_in', 8, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1184, 1143, 19, 'stock_in', 36, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1185, 1144, 19, 'stock_in', 1, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1186, 1145, 19, 'stock_in', 2, 'Imported from Excel', '2026-07-15 20:42:05', '2026-07-15 20:42:05'),
(1187, 1146, 19, 'stock_in', 7, 'Initial stock added', '2026-07-15 20:49:29', '2026-07-15 20:49:29'),
(1188, 1147, 19, 'stock_in', 1, 'Initial stock added', '2026-07-15 20:50:11', '2026-07-15 20:50:11'),
(1189, 1147, 19, 'restock', 18, 'Material restocked', '2026-07-15 20:51:49', '2026-07-15 20:51:49'),
(1190, 1146, 19, 'restock', 24, 'Material restocked', '2026-07-15 20:53:17', '2026-07-15 20:53:17'),
(1191, 1148, 19, 'stock_in', 50, 'Initial stock added', '2026-07-15 20:54:57', '2026-07-15 20:54:57'),
(1192, 1149, 19, 'stock_in', 16, 'Initial stock added', '2026-07-15 20:55:20', '2026-07-15 20:55:20'),
(1193, 1150, 19, 'stock_in', 19, 'Initial stock added', '2026-07-15 20:55:56', '2026-07-15 20:55:56'),
(1194, 1151, 19, 'stock_in', 96, 'Initial stock added', '2026-07-15 20:58:58', '2026-07-15 20:58:58'),
(1195, 1152, 19, 'stock_in', 44, 'Initial stock added', '2026-07-15 21:01:40', '2026-07-15 21:01:40'),
(1196, 1153, 19, 'stock_in', 84, 'Initial stock added', '2026-07-15 21:02:16', '2026-07-15 21:02:16'),
(1197, 1154, 19, 'stock_in', 100, 'Initial stock added', '2026-07-15 21:08:08', '2026-07-15 21:08:08'),
(1198, 1154, 19, 'DEDUCTED', 2, 'Walk-In Issue #WI-20260716070952', '2026-07-15 23:09:52', '2026-07-15 23:09:52'),
(1199, 1154, 19, 'DEDUCTED', 3, 'Walk-In Issue #WI-20260716070952', '2026-07-15 23:09:52', '2026-07-15 23:09:52'),
(1205, 1166, 21, 'stock_in', 0, 'Created via PPMP item entry', '2026-07-16 21:11:11', '2026-07-16 21:11:11'),
(1206, 1154, 19, 'deducted', 5, 'Request #: MR-2026-0001 | Requested by: joy | Approved by: raymond', '2026-07-16 23:28:15', '2026-07-16 23:28:15'),
(1207, 1154, 19, 'deducted', 5, 'Request #: MR-2026-0044 | Requested by: regene | Approved by: raymond', '2026-07-17 00:07:56', '2026-07-17 00:07:56'),
(1209, 1154, 19, 'DEDUCTED', 5, 'Walk-In Issue #WI-20260718035555', '2026-07-17 19:55:55', '2026-07-17 19:55:55'),
(1213, 1154, 19, 'deducted', 4, 'Request #: MR-2026-0045 | Requested by: joseph | Approved by: raymond', '2026-07-17 20:45:14', '2026-07-17 20:45:14');

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `room` varchar(255) DEFAULT NULL,
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

INSERT INTO `material_requests` (`id`, `request_number`, `user_id`, `department_id`, `room`, `status`, `purpose`, `remarks`, `released_by`, `released_at`, `created_at`, `updated_at`) VALUES
(39, 'MR-2026-0001', 27, 8, NULL, 'released', 'for cleaning', NULL, NULL, NULL, '2026-07-16 23:17:14', '2026-07-16 23:28:55'),
(40, 'MR-2026-0040', 27, 8, NULL, 'rejected', 'testing again', NULL, NULL, NULL, '2026-07-16 23:29:44', '2026-07-16 23:29:57'),
(43, 'MR-2026-0041', 27, 8, NULL, 'rejected', 'for printing', NULL, NULL, NULL, '2026-07-17 00:06:47', '2026-07-17 00:07:54'),
(44, 'MR-2026-0044', 21, 1, 'Star 201', 'released', 'for testing', NULL, NULL, NULL, '2026-07-17 00:07:35', '2026-07-17 00:08:00'),
(47, 'MR-2026-0045', 34, 1, '107', 'released', 'for printing', NULL, NULL, NULL, '2026-07-17 20:44:48', '2026-07-17 20:50:50');

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
(46, 39, 1154, 5, NULL, '2026-07-16 23:17:14', '2026-07-16 23:17:14'),
(47, 40, 1154, 5, NULL, '2026-07-16 23:29:44', '2026-07-16 23:29:44'),
(50, 43, 1154, 4, NULL, '2026-07-17 00:06:47', '2026-07-17 00:06:47'),
(51, 44, 1154, 5, NULL, '2026-07-17 00:07:35', '2026-07-17 00:07:35'),
(52, 47, 1154, 4, NULL, '2026-07-17 20:44:48', '2026-07-17 20:44:48');

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
(12, 1147, 'RST-2026-0001', 1, 18, 18, 19, NULL, NULL, 0, NULL, NULL, 19, '2026-07-15 20:51:49', '2026-07-15 20:51:49'),
(13, 1146, 'RST-2026-0013', 7, 24, 24, 31, NULL, NULL, 0, NULL, NULL, 19, '2026-07-15 20:53:17', '2026-07-15 20:53:17');

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
(28, '2026_06_13_032758_create_walkin_request_items_table', 13),
(29, '2026_06_30_020343_create_procurement_plans_table', 14),
(30, '2026_06_30_020350_create_procurement_plan_items_table', 14),
(31, '2026_07_06_034146_create_procurement_classifications_table', 15),
(32, '2026_07_06_041143_add_classification_id_to_materials_table', 16),
(33, '2026_07_06_060106_update_inventory_movement_type_enum', 17),
(34, '2026_07_06_061113_change_inventory_movement_type_to_string', 17),
(35, '2026_07_07_001832_create_employment_types_table', 18),
(36, '2026_07_07_014742_create_positions_table', 19),
(37, '2026_07_07_023111_add_employee_master_fields_to_personnel_table', 20),
(38, '2026_07_08_003100_create_roles_table', 21),
(39, '2026_07_08_003244_add_role_id_to_users_table', 21),
(40, '2026_07_08_050831_create_employee_profiles_table', 22),
(41, '2026_07_08_080214_create_employee_contacts_table', 23),
(42, '2026_07_10_023802_create_employee_educations_table', 24),
(43, '2026_07_14_004827_create_permissions_table', 25),
(44, '2026_07_14_004837_create_permission_role_table', 25),
(45, '2026_07_16_073202_create_settings_table', 26),
(46, '2026_07_16_091131_fix_missing_classification_id_on_materials_table', 27),
(47, '2026_07_17_051436_add_created_by_to_procurement_plan_items_table', 28),
(48, '2026_07_17_054252_create_procurement_plan_item_logs_table', 29),
(49, '2026_07_17_055602_add_is_approved_to_procurement_plan_items_table', 30),
(50, '2026_07_17_073420_add_room_to_material_requests_table', 31),
(51, '2026_07_18_042310_add_must_change_password_to_users_table', 32),
(52, '2026_07_18_043338_create_password_reset_tokens_table', 33),
(53, '2026_07_18_043818_add_remember_token_to_users_table', 34),
(54, '2026_07_18_050710_add_target_user_id_to_activity_logs_table', 35);

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
(9, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-27 17:53:52', '2026-05-28 00:35:14', '/supervisor/material-requests'),
(11, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-27 19:09:33', '2026-05-28 00:35:10', '/supervisor/material-requests'),
(13, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:01:40', '2026-05-28 00:02:53', '/supervisor/material-requests'),
(15, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:03:51', '2026-05-28 00:11:38', '/supervisor/material-requests'),
(16, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:13:01', '2026-05-28 00:13:14', '/supervisor/material-requests'),
(18, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:21:41', '2026-05-28 00:25:58', '/supervisor/material-requests'),
(20, 3, 'inventory', 'Critical Stock Alert', 'LED Bulbs stock is critically low (5 remaining).', 1, '2026-05-28 00:22:03', '2026-05-28 00:22:11', 'http://127.0.0.1:8000/materials'),
(22, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:35:37', '2026-05-28 00:35:56', '/supervisor/material-requests'),
(23, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:48:43', '2026-05-28 00:48:55', '/supervisor/material-requests'),
(26, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 00:58:23', '2026-05-28 00:58:35', '/supervisor/material-requests'),
(27, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 01:33:35', '2026-05-28 01:33:48', '/supervisor/material-requests'),
(28, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-28 01:39:49', '2026-05-28 01:40:00', '/supervisor/material-requests'),
(29, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-31 21:16:47', '2026-05-31 21:17:01', '/supervisor/material-requests'),
(30, 3, 'user', 'New User Registration', 'Nerrisa Cator registered and needs approval', 1, '2026-05-31 21:18:05', '2026-05-31 21:18:22', NULL),
(31, 3, 'user_registration', 'New User Registration', 'test registered and needs approval', 1, '2026-05-31 21:38:37', '2026-05-31 21:41:09', 'http://127.0.0.1:8000/admin/users/pending'),
(32, 3, 'user_registration', 'New User Registration', 'Neri Cator registered and needs approval', 1, '2026-05-31 21:57:05', '2026-05-31 21:57:20', '/admin/users/pending'),
(33, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-05-31 23:52:12', '2026-05-31 23:52:23', '/supervisor/material-requests'),
(34, 3, 'inventory', 'Critical Stock Alert', 'HDMI Cable stock is critically low (3 remaining).', 1, '2026-05-31 23:52:25', '2026-06-01 17:26:03', 'http://127.0.0.1:8000/materials'),
(36, 3, 'user_registration', 'New User Registration', 'Marnel KuyaBunso registered and needs approval', 1, '2026-06-01 00:22:02', '2026-06-01 17:26:01', '/admin/users/pending'),
(37, 3, 'leave', 'New Leave Request', 'Aldrin Justimbaste submitted a leave request.', 1, '2026-06-01 01:31:46', '2026-06-01 01:32:00', '/leave-requests'),
(40, 3, 'leave', 'New Leave Request', 'Aldrin Justimbaste submitted a leave request.', 1, '2026-06-01 01:42:41', '2026-06-01 01:42:54', '/leave-requests'),
(43, 3, 'leave', 'New Leave Request', 'Aldrin Justimbaste submitted a leave request.', 1, '2026-06-01 17:25:26', '2026-06-01 17:25:40', '/leave-requests'),
(46, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-02 01:32:39', '2026-06-02 01:32:52', '/supervisor/material-requests'),
(48, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-02 18:28:48', '2026-06-02 18:29:06', '/supervisor/material-requests'),
(51, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-03 21:00:10', '2026-06-03 21:00:21', '/supervisor/material-requests'),
(54, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-08 00:40:56', '2026-06-08 00:41:21', '/supervisor/material-requests'),
(55, 3, 'inventory', 'Critical Stock Alert', '8.50x11 Certificate Holder stock is critically low (2 remaining).', 0, '2026-06-08 00:41:29', '2026-06-08 00:41:29', 'http://127.0.0.1:8000/materials'),
(56, 3, 'inventory', 'Critical Stock Alert', 'A4 Document Frame stock is critically low (3 remaining).', 0, '2026-06-08 00:41:30', '2026-06-08 00:41:30', 'http://127.0.0.1:8000/materials'),
(57, 3, 'inventory', 'Critical Stock Alert', 'ADVENTURER BlueCard Case stock is critically low (4 remaining).', 1, '2026-06-08 00:41:31', '2026-06-08 01:15:53', 'http://127.0.0.1:8000/materials'),
(59, 3, 'material', 'New Material Request', 'aldrin submitted a material request.', 1, '2026-06-08 23:45:55', '2026-06-08 23:46:12', '/supervisor/material-requests'),
(60, 3, 'inventory', 'Critical Stock Alert', 'A4 Document Frame stock is critically low (2 remaining).', 0, '2026-06-08 23:46:28', '2026-06-08 23:46:28', 'http://127.0.0.1:8000/materials'),
(63, 3, 'user_registration', 'New User Registration', 'test registered and needs approval', 0, '2026-07-06 21:54:09', '2026-07-06 21:54:09', '/admin/users/pending'),
(64, 3, 'user_registration', 'New User Registration', 'test22 registered and needs approval', 0, '2026-07-06 22:36:33', '2026-07-06 22:36:33', '/admin/users/pending'),
(65, 3, 'user_registration', 'New User Registration', 'test3 registered and needs approval', 0, '2026-07-06 22:41:05', '2026-07-06 22:41:05', '/admin/users/pending'),
(66, 3, 'user_registration', 'New User Registration', 'Mark Anthony Abril registered and needs approval', 0, '2026-07-07 15:21:51', '2026-07-07 15:21:51', '/admin/users/pending'),
(67, 3, 'user_registration', 'New User Registration', 'Mary Ann registered and needs approval', 0, '2026-07-07 19:48:42', '2026-07-07 19:48:42', '/admin/users/pending'),
(68, 3, 'user_registration', 'New User Registration', 'Rochelle C. Malabayabas registered and needs approval', 0, '2026-07-08 19:33:32', '2026-07-08 19:33:32', '/admin/users/pending'),
(69, 3, 'user_registration', 'New User Registration', 'Diana H. Cortez registered and needs approval', 0, '2026-07-08 19:35:03', '2026-07-08 19:35:03', '/admin/users/pending'),
(70, 3, 'user_registration', 'New User Registration', 'Raymond T. Uminga registered and needs approval', 0, '2026-07-08 19:35:56', '2026-07-08 19:35:56', '/admin/users/pending'),
(71, 3, 'user_registration', 'New User Registration', 'Arnold Balingit registered and needs approval', 0, '2026-07-08 19:36:40', '2026-07-08 19:36:40', '/admin/users/pending'),
(72, 3, 'user_registration', 'New User Registration', 'Regene G. Hernandez registered and needs approval', 0, '2026-07-08 19:37:22', '2026-07-08 19:37:22', '/admin/users/pending'),
(73, 3, 'user_registration', 'New User Registration', 'Aldrin Justimbaste registered and needs approval', 0, '2026-07-15 23:47:35', '2026-07-15 23:47:35', '/admin/users/pending'),
(75, 18, 'user_onboarding', 'Onboarding Approved', 'Your employee onboarding has been completed and your account is now active.', 0, '2026-07-16 18:43:25', '2026-07-16 18:43:25', '/personnel/dashboard'),
(78, 21, 'procurement_item', 'Procurement Item Removed', 'Your item \"A4 Document Frame\" was removed from PPMP-PPMP-2027-0001. Reason: madami pang A4 frame', 0, '2026-07-16 21:49:06', '2026-07-16 21:49:06', '/procurement/plans/18'),
(79, 21, 'procurement_item', 'Procurement Item Updated', 'Your item \"Printer\" was updated. Reason: bilhin na ung mas mahal', 0, '2026-07-16 21:50:42', '2026-07-16 21:50:42', '/procurement/plans/18'),
(80, 3, 'user_registration', 'New User Registration', 'Joy Siochi registered and needs approval', 0, '2026-07-16 21:55:50', '2026-07-16 21:55:50', '/admin/users/pending'),
(81, 27, 'user_onboarding', 'Onboarding Approved', 'Your employee onboarding has been completed and your account is now active.', 0, '2026-07-16 21:56:24', '2026-07-16 21:56:24', '/personnel/dashboard'),
(82, 21, 'procurement_item', 'Procurement Item Updated', 'Your item \"Printer\" was updated.', 0, '2026-07-16 22:00:21', '2026-07-16 22:00:21', '/procurement/plans/22'),
(83, 21, 'procurement_item', 'Procurement Item Updated', 'Your item \"Printer\" was updated. Reason: nag mahal na ang printer', 0, '2026-07-16 22:05:41', '2026-07-16 22:05:41', '/procurement/plans/18'),
(84, 27, 'procurement_item', 'Procurement Item Updated', 'Your item \"Printer\" was updated. Reason: ung mura lang dapat para pasok sa budget', 0, '2026-07-16 22:11:38', '2026-07-16 22:11:38', '/procurement/plans/23'),
(87, 21, 'procurement_item', 'Procurement Item Updated', 'Your item \"Printer\" was updated.', 1, '2026-07-16 22:12:57', '2026-07-16 22:15:30', '/procurement/plans/24'),
(89, 3, 'user_registration', 'New User Registration', 'Jenny Beb F. Espineli registered and needs approval', 0, '2026-07-16 22:22:31', '2026-07-16 22:22:31', '/admin/users/pending'),
(90, 28, 'user_onboarding', 'Onboarding Approved', 'Your employee onboarding has been completed and your account is now active.', 0, '2026-07-16 22:24:37', '2026-07-16 22:24:37', '/personnel/dashboard'),
(91, 28, 'user_onboarding', 'Onboarding Approved', 'Your employee onboarding has been completed and your account is now active.', 0, '2026-07-16 22:26:26', '2026-07-16 22:26:26', '/personnel/dashboard'),
(93, 3, 'material', 'New Material Request', 'joy submitted a material request.', 1, '2026-07-16 23:17:14', '2026-07-16 23:18:13', '/supervisor/material-requests'),
(94, 27, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-07-16 23:28:15', '2026-07-16 23:28:31', '/material-request/history'),
(95, 27, 'material', 'Materials Released', 'Your requested materials are ready for pickup.', 1, '2026-07-16 23:28:55', '2026-07-16 23:29:09', '/material-request/history'),
(96, 3, 'material', 'New Material Request', 'joy submitted a material request.', 0, '2026-07-16 23:29:44', '2026-07-16 23:29:44', '/supervisor/material-requests'),
(97, 19, 'material', 'New Material Request', 'joy submitted a material request.', 1, '2026-07-16 23:29:44', '2026-07-16 23:29:55', '/supervisor/material-requests'),
(98, 28, 'material', 'New Material Request', 'joy submitted a material request.', 0, '2026-07-16 23:29:44', '2026-07-16 23:29:44', '/supervisor/material-requests'),
(99, 27, 'material', 'Request Rejected', 'Your material request has been rejected.', 0, '2026-07-16 23:29:57', '2026-07-16 23:29:57', '/material-request/history'),
(106, 3, 'material', 'New Material Request', 'joy submitted a material request.', 0, '2026-07-17 00:06:47', '2026-07-17 00:06:47', '/supervisor/material-requests'),
(107, 19, 'material', 'New Material Request', 'joy submitted a material request.', 1, '2026-07-17 00:06:48', '2026-07-17 00:07:49', '/supervisor/material-requests'),
(108, 28, 'material', 'New Material Request', 'joy submitted a material request.', 0, '2026-07-17 00:06:48', '2026-07-17 00:06:48', '/supervisor/material-requests'),
(109, 3, 'material', 'New Material Request', 'regene submitted a material request.', 0, '2026-07-17 00:07:35', '2026-07-17 00:07:35', '/supervisor/material-requests'),
(110, 19, 'material', 'New Material Request', 'regene submitted a material request.', 1, '2026-07-17 00:07:35', '2026-07-17 20:50:41', '/supervisor/material-requests'),
(111, 28, 'material', 'New Material Request', 'regene submitted a material request.', 0, '2026-07-17 00:07:35', '2026-07-17 00:07:35', '/supervisor/material-requests'),
(112, 27, 'material', 'Request Rejected', 'Your material request has been rejected.', 0, '2026-07-17 00:07:54', '2026-07-17 00:07:54', '/material-request/history'),
(113, 21, 'material', 'Request Approved', 'Your material request has been approved.', 0, '2026-07-17 00:07:56', '2026-07-17 00:07:56', '/material-request/history'),
(114, 21, 'material', 'Materials Released', 'Your requested materials are ready for pickup.', 1, '2026-07-17 00:08:00', '2026-07-17 00:08:09', '/material-request/history'),
(115, 3, 'user_registration', 'New User Registration', 'Joe Marlou A. Opella registered and needs approval', 0, '2026-07-17 01:02:04', '2026-07-17 01:02:04', '/admin/users/pending'),
(116, 3, 'material', 'New Material Request', 'joseph submitted a material request.', 0, '2026-07-17 20:44:48', '2026-07-17 20:44:48', '/supervisor/material-requests'),
(117, 19, 'material', 'New Material Request', 'joseph submitted a material request.', 1, '2026-07-17 20:44:49', '2026-07-17 20:45:07', '/supervisor/material-requests'),
(118, 28, 'material', 'New Material Request', 'joseph submitted a material request.', 0, '2026-07-17 20:44:49', '2026-07-17 20:44:49', '/supervisor/material-requests'),
(119, 34, 'material', 'Request Approved', 'Your material request has been approved.', 1, '2026-07-17 20:45:14', '2026-07-17 20:45:40', '/material-request/history'),
(120, 34, 'material', 'Materials Released', 'Your requested materials are ready for pickup.', 0, '2026-07-17 20:50:50', '2026-07-17 20:50:50', '/material-request/history');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('king@mail.com', '$2y$12$zXySjGjoTqR4wnDUmOXzJuNUEppghQXgMIILL8YM39A3H7c6fkMe2', '2026-07-17 20:39:25'),
('markanthonyrabril@gmail.com', '$2y$12$3SDEcLyb1ftDJwZ8VUveQOTHAePD42wQLYt8LVAGuWsV6ihT7HlYq', '2026-07-17 20:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Employee Master', 'View Employees', 'view-employees', 'Can view employee records', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(2, 'Employee Master', 'Create Employees', 'create-employees', 'Can create employee records', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(3, 'Employee Master', 'Edit Employees', 'edit-employees', 'Can edit employee records', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(4, 'Employee Master', 'Delete Employees', 'delete-employees', 'Can delete employee records', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(5, 'Employee Master', 'View Employee Profile', 'view-employee-profile', 'Can view employee profile', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(6, 'Employee Master', 'Edit Employee Profile', 'edit-employee-profile', 'Can edit employee profile', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(7, 'Inventory', 'View Materials', 'view-materials', 'Can view materials', 1, '2026-07-13 17:17:53', '2026-07-13 17:17:53'),
(8, 'Inventory', 'Create Materials', 'create-materials', 'Can create materials', 1, '2026-07-13 17:17:53', '2026-07-13 17:17:53'),
(9, 'Inventory', 'Edit Materials', 'edit-materials', 'Can edit materials', 1, '2026-07-13 17:17:53', '2026-07-13 17:17:53'),
(10, 'Inventory', 'Delete Materials', 'delete-materials', 'Can delete materials', 1, '2026-07-13 17:17:53', '2026-07-13 17:17:53'),
(11, 'Inventory', 'Restock Materials', 'restock-materials', 'Can restock inventory', 1, '2026-07-13 17:17:53', '2026-07-13 17:17:53'),
(12, 'Procurement Planning', 'View PPMP', 'view-ppmp', 'Can view PPMP', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(13, 'Procurement Planning', 'Create PPMP', 'create-ppmp', 'Can create PPMP', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(14, 'Procurement Planning', 'Edit PPMP', 'edit-ppmp', 'Can edit PPMP', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(15, 'Reports Center', 'View Reports', 'view-reports', NULL, 1, '2026-07-13 17:17:53', '2026-07-16 19:07:56'),
(16, 'Reports Center', 'Export Reports', 'export-reports', 'Can export reports', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(17, 'User Approval', 'Approve Users', 'approve-users', 'Can approve users', 1, '2026-07-13 17:17:53', '2026-07-16 00:02:30'),
(18, 'User Access', 'Assign Roles', 'assign-roles', 'Can assign user roles', 1, '2026-07-13 17:17:53', '2026-07-16 00:03:11'),
(19, 'Inventory', 'View Categories', 'view-categories', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(20, 'Inventory', 'Create Categories', 'create-categories', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(21, 'Inventory', 'Edit Categories', 'edit-categories', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(22, 'Inventory', 'Delete Categories', 'delete-categories', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(23, 'Inventory', 'View Units', 'view-units', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(24, 'Inventory', 'Create Units', 'create-units', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(25, 'Inventory', 'Edit Units', 'edit-units', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(26, 'Inventory', 'Delete Units', 'delete-units', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(27, 'Inventory', 'View Departments', 'view-departments', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(28, 'Inventory', 'Create Departments', 'create-departments', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(29, 'Inventory', 'Edit Departments', 'edit-departments', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(30, 'Inventory', 'Delete Departments', 'delete-departments', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(31, 'Inventory', 'View Inventory Movements', 'view-inventory-movements', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(32, 'Inventory', 'View Department Inventory', 'view-department-inventory', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(33, 'Inventory', 'View Material Logs', 'view-material-logs', NULL, 1, '2026-07-13 22:36:27', '2026-07-13 22:36:27'),
(34, 'Procurement Planning', 'Delete PPMP', 'delete-ppmp', NULL, 1, '2026-07-13 22:36:27', '2026-07-16 00:02:30'),
(35, 'Reports Center', 'Print Reports', 'print-reports', NULL, 1, '2026-07-13 22:36:27', '2026-07-16 00:02:30'),
(36, 'User Approval', 'Reject Users', 'reject-users', NULL, 1, '2026-07-13 22:36:27', '2026-07-16 00:02:30'),
(37, 'User Approval', 'Onboard Users', 'onboard-users', NULL, 1, '2026-07-13 22:36:27', '2026-07-16 00:02:30'),
(38, 'Role Management', 'Manage Roles', 'manage-roles', NULL, 1, '2026-07-13 22:36:27', '2026-07-16 00:02:30'),
(39, 'Permission Management', 'Manage Permissions', 'manage-permissions', NULL, 1, '2026-07-13 22:36:27', '2026-07-16 00:02:30'),
(40, 'User Access', 'View User Access', 'view-user-access', NULL, 1, '2026-07-14 22:00:16', '2026-07-14 22:00:16'),
(41, 'User Access', 'Manage User Status', 'manage-user-status', NULL, 1, '2026-07-14 22:00:16', '2026-07-14 22:00:16'),
(42, 'Walk-In Issuance', 'View Walk-In Requests', 'view-walkin-requests', NULL, 1, '2026-07-15 19:29:28', '2026-07-15 19:29:28'),
(43, 'Walk-In Issuance', 'Create Walk-In Requests', 'create-walkin-requests', NULL, 1, '2026-07-15 19:29:28', '2026-07-15 19:29:28'),
(44, 'System Administration', 'View Activity Logs', 'view-activity-logs', NULL, 1, '2026-07-15 23:22:16', '2026-07-15 23:22:16'),
(45, 'System Administration', 'Manage System Settings', 'manage-system-settings', NULL, 1, '2026-07-15 23:33:22', '2026-07-15 23:33:22'),
(46, 'Procurement', 'Submit PPMP', 'submit-ppmp', NULL, 1, '2026-07-16 00:54:35', '2026-07-16 00:54:35'),
(47, 'Procurement', 'Approve PPMP', 'approve-ppmp', NULL, 1, '2026-07-16 00:54:35', '2026-07-16 00:54:35'),
(48, 'Procurement', 'Reject PPMP', 'reject-ppmp', NULL, 1, '2026-07-16 00:54:35', '2026-07-16 00:54:35'),
(49, 'Procurement', 'View Budget Monitoring', 'view-budget-monitoring', NULL, 1, '2026-07-16 00:54:35', '2026-07-16 00:54:35'),
(50, 'Procurement', 'View Purchase Forecast', 'view-purchase-forecast', NULL, 1, '2026-07-16 00:54:35', '2026-07-16 00:54:35'),
(51, 'Procurement', 'View Procurement Calendar', 'view-procurement-calendar', NULL, 1, '2026-07-16 00:54:35', '2026-07-16 00:54:35'),
(52, 'Procurement', 'Manage Own Department PPMP Items', 'manage-own-department-ppmp-items', NULL, 1, '2026-07-16 17:58:56', '2026-07-16 17:58:56'),
(53, 'Inventory', 'Process Material Requests', 'process-material-requests', NULL, 1, '2026-07-16 23:26:04', '2026-07-16 23:26:04'),
(54, 'User Access', 'Reset User Passwords', 'reset-user-passwords', NULL, 1, '2026-07-17 20:57:19', '2026-07-17 20:57:19');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, NULL, NULL),
(2, 2, 4, NULL, NULL),
(3, 2, 6, NULL, NULL),
(4, 2, 3, NULL, NULL),
(5, 2, 16, NULL, NULL),
(6, 2, 5, NULL, NULL),
(7, 2, 1, NULL, NULL),
(8, 2, 15, NULL, NULL),
(9, 4, 8, NULL, NULL),
(10, 4, 10, NULL, NULL),
(11, 4, 9, NULL, NULL),
(12, 4, 11, NULL, NULL),
(13, 4, 7, NULL, NULL),
(14, 4, 15, NULL, NULL),
(15, 6, 13, NULL, NULL),
(16, 6, 14, NULL, NULL),
(17, 6, 12, NULL, NULL),
(18, 6, 15, NULL, NULL),
(19, 9, 5, NULL, NULL),
(20, 4, 20, NULL, NULL),
(21, 4, 28, NULL, NULL),
(22, 4, 24, NULL, NULL),
(23, 4, 22, NULL, NULL),
(24, 4, 30, NULL, NULL),
(25, 4, 26, NULL, NULL),
(26, 4, 21, NULL, NULL),
(27, 4, 29, NULL, NULL),
(28, 4, 25, NULL, NULL),
(29, 4, 19, NULL, NULL),
(30, 4, 32, NULL, NULL),
(31, 4, 27, NULL, NULL),
(32, 4, 31, NULL, NULL),
(33, 4, 33, NULL, NULL),
(34, 4, 23, NULL, NULL),
(35, 1, 17, NULL, NULL),
(36, 1, 18, NULL, NULL),
(37, 1, 20, NULL, NULL),
(38, 1, 28, NULL, NULL),
(39, 1, 2, NULL, NULL),
(40, 1, 8, NULL, NULL),
(41, 1, 13, NULL, NULL),
(42, 1, 24, NULL, NULL),
(43, 1, 22, NULL, NULL),
(44, 1, 30, NULL, NULL),
(45, 1, 4, NULL, NULL),
(46, 1, 10, NULL, NULL),
(47, 1, 34, NULL, NULL),
(48, 1, 26, NULL, NULL),
(49, 1, 21, NULL, NULL),
(50, 1, 29, NULL, NULL),
(51, 1, 6, NULL, NULL),
(52, 1, 3, NULL, NULL),
(53, 1, 9, NULL, NULL),
(54, 1, 14, NULL, NULL),
(55, 1, 25, NULL, NULL),
(56, 1, 16, NULL, NULL),
(57, 1, 39, NULL, NULL),
(58, 1, 38, NULL, NULL),
(59, 1, 37, NULL, NULL),
(60, 1, 35, NULL, NULL),
(61, 1, 36, NULL, NULL),
(62, 1, 11, NULL, NULL),
(63, 1, 19, NULL, NULL),
(64, 1, 32, NULL, NULL),
(65, 1, 27, NULL, NULL),
(66, 1, 5, NULL, NULL),
(67, 1, 1, NULL, NULL),
(68, 1, 31, NULL, NULL),
(69, 1, 33, NULL, NULL),
(70, 1, 7, NULL, NULL),
(71, 1, 12, NULL, NULL),
(72, 1, 15, NULL, NULL),
(73, 1, 23, NULL, NULL),
(74, 1, 41, NULL, NULL),
(75, 1, 40, NULL, NULL),
(76, 1, 43, NULL, NULL),
(77, 1, 42, NULL, NULL),
(78, 4, 43, NULL, NULL),
(79, 4, 42, NULL, NULL),
(80, 4, 35, NULL, NULL),
(81, 1, 44, NULL, NULL),
(82, 1, 45, NULL, NULL),
(83, 3, 20, NULL, NULL),
(84, 3, 28, NULL, NULL),
(85, 3, 8, NULL, NULL),
(86, 3, 24, NULL, NULL),
(87, 3, 22, NULL, NULL),
(88, 3, 30, NULL, NULL),
(89, 3, 10, NULL, NULL),
(90, 3, 26, NULL, NULL),
(91, 3, 21, NULL, NULL),
(92, 3, 29, NULL, NULL),
(93, 3, 9, NULL, NULL),
(94, 3, 25, NULL, NULL),
(95, 3, 11, NULL, NULL),
(96, 3, 19, NULL, NULL),
(97, 3, 32, NULL, NULL),
(98, 3, 27, NULL, NULL),
(99, 3, 31, NULL, NULL),
(100, 3, 33, NULL, NULL),
(101, 3, 7, NULL, NULL),
(102, 3, 23, NULL, NULL),
(103, 3, 13, NULL, NULL),
(104, 3, 34, NULL, NULL),
(105, 3, 14, NULL, NULL),
(106, 3, 12, NULL, NULL),
(107, 3, 16, NULL, NULL),
(108, 3, 35, NULL, NULL),
(109, 3, 15, NULL, NULL),
(110, 3, 45, NULL, NULL),
(111, 3, 44, NULL, NULL),
(112, 1, 47, NULL, NULL),
(113, 1, 48, NULL, NULL),
(114, 1, 46, NULL, NULL),
(115, 1, 49, NULL, NULL),
(116, 1, 51, NULL, NULL),
(117, 1, 50, NULL, NULL),
(118, 6, 46, NULL, NULL),
(119, 6, 49, NULL, NULL),
(120, 6, 51, NULL, NULL),
(121, 6, 50, NULL, NULL),
(122, 3, 46, NULL, NULL),
(123, 3, 49, NULL, NULL),
(124, 3, 51, NULL, NULL),
(125, 3, 50, NULL, NULL),
(126, 1, 52, NULL, NULL),
(127, 15, 52, NULL, NULL),
(128, 3, 47, NULL, NULL),
(129, 3, 48, NULL, NULL),
(131, 1, 53, NULL, NULL),
(132, 4, 53, NULL, NULL),
(133, 1, 54, NULL, NULL),
(134, 4, 54, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employment_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position_id` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `personnel` (`id`, `employee_id`, `employment_type_id`, `department_id`, `position_id`, `fullname`, `position`, `department`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(9, 'REGF001', 1, 1, 6, 'Mark Anthony Abril', 'Assistant Professor III', 'Department of Industrial and Information Technology', 15, 'Active', NULL, NULL),
(10, 'ADMP001', 3, 2, 16, 'Mary Ann', 'Administrative Aide I', 'Department of Management', 16, 'Active', NULL, NULL),
(11, 'ADMP002', 3, 6, 16, 'Raymond T. Uminga', 'Administrative Aide I', 'General Inventory', 19, 'Active', NULL, NULL),
(12, 'UTIL001', 5, 14, 30, 'Aldrin Justimbaste', 'Utility Worker', 'Admin Department', 22, 'Active', NULL, NULL),
(13, 'REGF002', 1, 1, 7, 'Rochelle C. Malabayabas', 'Associate Professor I', 'Department of Industrial and Information Technology', 17, 'Active', NULL, NULL),
(16, 'REGF003', 1, 1, 8, 'Regene G. Hernandez', 'Associate Professor II', 'Department of Industrial and Information Technology', 21, 'Active', NULL, NULL),
(18, 'ADMP003', 3, 14, 17, 'Diana H. Cortez', 'Administrative Aide II', 'Admin Department', 18, 'Active', NULL, NULL),
(19, 'REGF004', 1, 8, 7, 'Joy Siochi', 'Associate Professor I', 'Department of Teacher Education', 27, 'Active', NULL, NULL),
(20, 'ADMP004', 3, 14, 20, 'Jenny Beb F. Espineli', 'Administrative Officer II', 'Admin Department', 28, 'Active', NULL, NULL),
(21, 'ADMP005', 3, 14, 20, 'Jenny Beb F. Espineli', 'Administrative Officer II', 'Admin Department', 28, 'Active', NULL, NULL),
(24, 'NTP001', 4, 12, 47, 'Qwncy Amie B. Abril', 'Nurse', 'Medical Health', 32, 'Active', NULL, NULL),
(25, 'ADMP006', 3, 11, 19, 'Joseph E. Cuarez', 'Administrative Officer I', 'Registrar', 34, 'Active', NULL, NULL),
(26, 'CONT001', 7, 10, 39, 'King Ronmark B. Abril', 'Laboratory Assistant', 'Library', 36, 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position_name` varchar(255) NOT NULL,
  `employment_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position_code` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `position_name`, `employment_type_id`, `position_code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Instructor I', NULL, 'INST1', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(2, 'Instructor II', NULL, 'INST2', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(3, 'Instructor III', NULL, 'INST3', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(4, 'Assistant Professor I', NULL, 'AP1', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(5, 'Assistant Professor II', NULL, 'AP2', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(6, 'Assistant Professor III', NULL, 'AP3', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(7, 'Associate Professor I', NULL, 'ASP1', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(8, 'Associate Professor II', NULL, 'ASP2', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(9, 'Associate Professor III', NULL, 'ASP3', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(10, 'Professor I', NULL, 'PROF1', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(11, 'Professor II', NULL, 'PROF2', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(12, 'Professor III', NULL, 'PROF3', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(13, 'Lecturer', NULL, 'LECT', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(14, 'Laboratory Instructor', NULL, 'LABINST', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(15, 'Part-time Instructor', NULL, 'PTINST', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(16, 'Administrative Aide I', NULL, 'AA1', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(17, 'Administrative Aide II', NULL, 'AA2', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(18, 'Administrative Assistant I', NULL, 'AAS1', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(19, 'Administrative Officer I', NULL, 'AO1', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(20, 'Administrative Officer II', NULL, 'AO2', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(21, 'HR Officer', NULL, 'HRO', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(22, 'Accountant', NULL, 'ACC', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(23, 'Cashier', NULL, 'CASH', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(24, 'Supply Officer', NULL, 'SUP', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(25, 'Registrar Staff', NULL, 'REG', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(26, 'Library Staff', NULL, 'LIB', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(27, 'Guidance Staff', NULL, 'GUIDE', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(28, 'IT Officer', NULL, 'IT', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(29, 'Research Assistant', NULL, 'RA', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(30, 'Utility Worker', NULL, 'UTIL', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(31, 'Groundskeeper', NULL, 'GROUND', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(32, 'Maintenance Worker', NULL, 'MAIN', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(33, 'Office Assistant', NULL, 'OA', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(34, 'Driver', NULL, 'DRV', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(35, 'Security Aide', NULL, 'SEC', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(36, 'Office Helper', NULL, 'HELP', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(37, 'Project Staff', NULL, 'PS', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(38, 'Technical Assistant', NULL, 'TA', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(39, 'Laboratory Assistant', NULL, 'LABA', NULL, 1, '2026-07-07 00:58:10', '2026-07-07 00:58:10'),
(40, 'Assistant Professor IV', NULL, 'AP4', NULL, 1, '2026-07-17 19:24:22', '2026-07-17 19:24:22'),
(41, 'Associate Professor IV', NULL, 'ASP4', NULL, 1, '2026-07-17 19:24:22', '2026-07-17 19:24:22'),
(42, 'Associate Professor V', NULL, 'ASP5', NULL, 1, '2026-07-17 19:24:22', '2026-07-17 19:24:22'),
(43, 'Professor IV', NULL, 'PROF4', NULL, 1, '2026-07-17 19:24:22', '2026-07-17 19:24:22'),
(44, 'Professor V', NULL, 'PROF5', NULL, 1, '2026-07-17 19:24:22', '2026-07-17 19:24:22'),
(45, 'Professor VI', NULL, 'PROF6', NULL, 1, '2026-07-17 19:24:22', '2026-07-17 19:24:22'),
(46, 'University Professor', NULL, 'UNIVPROF', NULL, 1, '2026-07-17 19:24:22', '2026-07-17 19:24:22'),
(47, 'Nurse', NULL, 'NURSE', NULL, 1, '2026-07-17 19:43:17', '2026-07-17 19:43:17');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_classifications`
--

CREATE TABLE `procurement_classifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part` varchar(100) NOT NULL,
  `main_category` varchar(150) NOT NULL,
  `sub_category_a` varchar(255) NOT NULL,
  `sub_category_b` varchar(255) NOT NULL,
  `sub_category_c` varchar(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `uacs_code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procurement_classifications`
--

INSERT INTO `procurement_classifications` (`id`, `part`, `main_category`, `sub_category_a`, `sub_category_b`, `sub_category_c`, `code`, `uacs_code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'NON-PS', 'CO', 'Buildings and Other Structures', 'Buildings', 'BUILDINGS', 'S1', '1060401000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(2, 'NON-PS', 'CO', 'Buildings and Other Structures', 'Hostels and Dormitories', 'HOSTELS AND DORMITORIES', 'S4', '1060406000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(3, 'NON-PS', 'CO', 'Buildings and Other Structures', 'Other Structures', 'OTHER STRUCTURES', 'S5', '1060499000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(4, 'NON-PS', 'CO', 'Buildings and Other Structures', 'School Buildings', 'SCHOOL BUILDINGS', 'S2', '1060402000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(5, 'NON-PS', 'CO', 'Buildings and Other Structures', 'Slaughterhouses', 'SLAUGHTERHOUSES', 'S3', '1060405000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(6, 'NON-PS', 'CO', 'Furniture, Fixtures and Books', 'Books (P50,000 AND UP PER COPY)', 'BOOKS', 'V2', '1060702002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(7, 'NON-PS', 'CO', 'Furniture, Fixtures and Books', 'Furniture & Fixtures', 'FURNITURE AND FIXTURES', 'V1', '1060701000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(8, 'NON-PS', 'CO', 'Infrastructure Assets', 'Power  Supply  Systems', 'POWER SUPPLY SYSTEMS', 'R2', '1060305000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(9, 'NON-PS', 'CO', 'Infrastructure Assets', 'Water Supply Systems', 'WATER SUPPLY SYSTEMS', 'R1', '1060304000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(10, 'NON-PS', 'CO', 'Land Improvement', 'Land Improvement', 'LAND IMPROVEMENT', 'Q1', '1060201000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(11, 'NON-PS', 'CO', 'Land Improvement', 'Sewer System', 'SEWER SYSTEM', 'Q2', '1060303000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(12, 'NON-PS', 'CO', 'Machinery and Equipment', 'Agricultural and Forestry Equipment', 'AGRICULTURAL AND FORESTRY EQUIPMENT', 'T4', '1060504000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(13, 'NON-PS', 'CO', 'Machinery and Equipment', 'Communication Equipment', 'COMMUNICATION EQUIPMENT', 'T5', '1060507000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(14, 'NON-PS', 'CO', 'Machinery and Equipment', 'Firefighting Equipment and Accessories', 'FIREFIGHTING EQUIPMENT & ACCESSORIES', 'T6', '1060509000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(15, 'NON-PS', 'CO', 'Machinery and Equipment', 'ICT Equipment & Software', 'ICT EQUIPMENT AND SOFTWARE', 'T3', '1060503000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(16, 'NON-PS', 'CO', 'Machinery and Equipment', 'Medical, Dental and Laboratory Equipment', 'MEDICAL, DENTAL & LAB EQUIPMENT', 'T7', '1060511000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(17, 'NON-PS', 'CO', 'Machinery and Equipment', 'Office Equipment', 'OFFICE EQUIPMENT', 'T2', '1060502000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(18, 'NON-PS', 'CO', 'Machinery and Equipment', 'Other Machinery and Equipment', 'OTHER MACHINERY & EQUIPMENT', 'T11', '1060599000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(19, 'NON-PS', 'CO', 'Machinery and Equipment', 'Printing Equipment', 'PRINTING EQUIPMENT', 'T8', '1060512000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(20, 'NON-PS', 'CO', 'Machinery and Equipment', 'Sports Equipment', 'SPORTS EQUIPMENT', 'T9', '1060513000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(21, 'NON-PS', 'CO', 'Machinery and Equipment', 'Technical and Scientific Equipment', 'TECHNICAL & SCIENTIFIC EQUIPMENT', 'T10', '1060514000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(22, 'NON-PS', 'CO', 'Transportation Equipment', 'Motor Vehicle', 'MOTOR VEHICLE', 'U1', '1060601000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(23, 'NON-PS', 'MOOE', 'Awards/Rewards Expenses', 'Awards/ Rewards Expenses', 'AWARDS / INCENTIVES', 'H2', '5020601002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(24, 'NON-PS', 'MOOE', 'Awards/Rewards Expenses', 'Awards/ Rewards Expenses', 'AWARDS / REWARDS', 'H1', '5020601001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(25, 'NON-PS', 'MOOE', 'Communication Expenses', 'Internet Expenses', 'INTERNET SERVICES', 'G4', '5020503000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(26, 'NON-PS', 'MOOE', 'Communication Expenses', 'Postage & Deliveries', 'POSTAGE & DELIVERIES', 'G1', '5020501000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(27, 'NON-PS', 'MOOE', 'Communication Expenses', 'Telephone Expenses - Landline', 'TELEPHONE EXPENSE - LANDLINE', 'G3', '5020502002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(28, 'NON-PS', 'MOOE', 'Communication Expenses', 'Telephone Expenses- Mobile', 'TELEPHONE EXPENSE - MOBILE', 'G2', '5020502001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(29, 'NON-PS', 'MOOE', 'General Services', 'Environment/Sanitary Services', 'ENVIRONMENTAL/SANITARY SERVICES', 'M1', '5021201000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(30, 'NON-PS', 'MOOE', 'General Services', 'Janitorial Services', 'JANITORIAL SERVICES', 'M2', '5021202000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(31, 'NON-PS', 'MOOE', 'General Services', 'Other General Services', 'OTHER GENERAL SERVICES', 'M4', '5021299000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(32, 'NON-PS', 'MOOE', 'General Services', 'Security Services', 'SECURITY SERVICES', 'M3', '5021203000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(33, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Advertising Expenses', 'N/A', 'P1', '5029901000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(34, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'ACCREDITATION', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(35, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'EXTENSION', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(36, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'FOUNDATION', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(37, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'GRADUATION', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(38, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'N/A', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(39, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'N/A', 'P9', '5029907000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(40, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(41, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(42, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(43, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'RESEARCH', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(44, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Other Maintenance & Operating Expenses', 'UNIVERSITY GAMES', 'P12', '5029999099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(45, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing & Binding Expenses', 'N/A', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(46, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing and Binding Expenses', 'EXTENSION', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(47, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing and Binding Expenses', 'FOUNDATION', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(48, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing and Binding Expenses', 'GRADUATION', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(49, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing and Binding Expenses', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(50, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing and Binding Expenses', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(51, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing and Binding Expenses', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(52, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing and Binding Expenses', 'RESEARCH', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(53, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Printing and Binding Expenses', 'UNIVERSITY GAMES', 'P2', '5029902000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(54, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Equipment', 'EXTENSION', 'P8', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(55, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Equipment', 'FOUNDATION', 'P8', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(56, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Equipment', 'GRADUATION', 'P8', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(57, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Equipment', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'P8', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(58, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Equipment', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'P8', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(59, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Equipment', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'P8', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(60, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Equipment', 'RESEARCH', 'P8', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(61, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Equipment', 'UNIVERSITY GAMES', 'P8', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(62, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'ACCREDITATION', 'P6', '5029905001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(63, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'EXTENSION', 'P6', '1060501000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(64, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'FOUNDATION', 'P6', '5029905001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(65, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'GRADUATION', 'P6', '5029905001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(66, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'P6', '5029905001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(67, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'P6', '1060501000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(68, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'P6', '1060501000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(69, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'RESEARCH', 'P6', '5029905001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(70, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Land', 'UNIVERSITY GAMES', 'P6', '1060501000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(71, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'ACCREDITATION', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(72, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'EXTENSION', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(73, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'FOUNDATION', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(74, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'GRADUATION', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(75, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(76, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(77, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(78, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'RESEARCH', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(79, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Rent Expense - Motor Vehicle', 'UNIVERSITY GAMES', 'P7', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(80, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'ACCREDITATION', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(81, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'EXTENSION', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(82, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'FOUNDATION', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(83, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'GRADUATION', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(84, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(85, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(86, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(87, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'RESEARCH', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(88, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expense', 'UNIVERSITY GAMES', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(89, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Representation Expenses', 'N/A', 'P3', '5029903000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(90, 'NON-PS', 'MOOE', 'Other Maintenance & Operating Expenses', 'Transportation and Delivery Expenses', 'N/A', 'P4', '5029904000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(91, 'NON-PS', 'MOOE', 'Prizes', 'Prizes', 'PRIZES', 'H3', '5020602000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(92, 'NON-PS', 'MOOE', 'Professional Services', 'Auditing Services', 'AUDITING SERVICES', 'L2', '5021102000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(93, 'NON-PS', 'MOOE', 'Professional Services', 'Consultancy Services', 'CONSULTANCY SERVICES', 'L3', '5021103000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(94, 'NON-PS', 'MOOE', 'Professional Services', 'Legal Services', 'LEGAL SERVICES', 'L1', '5021101000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(95, 'NON-PS', 'MOOE', 'Professional Services', 'Other Professional Services', 'OTHER PROFESSIONAL SERVICES', 'L4', '5021199000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(96, 'NON-PS', 'MOOE', 'Rent / Lease Expenses', 'Buildings and Structures', 'N/A', 'P5', '5029905001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(97, 'NON-PS', 'MOOE', 'Rent / Lease Expenses', 'Equipment', 'N/A', 'P8', '5029905004', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(98, 'NON-PS', 'MOOE', 'Rent / Lease Expenses', 'Land', 'N/A', 'P6', '5029905002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(99, 'NON-PS', 'MOOE', 'Rent / Lease Expenses', 'Motor Vehicle', 'N/A', 'P7', '5029905003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(100, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M - Furniture and Fixtures', 'FURNITURES AND FIXTURES', 'N5-1', '5021307000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(101, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M - Infrastructure Assets', 'OTHER LAND IMPROVEMENTS', 'N1-1', '5021302099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(102, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M - Infrastructure Assets', 'POWER SUPPLY SYSTEM', 'N1-4', '5021303005', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(103, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M - Infrastructure Assets', 'SEWER SYSTEMS', 'N1-2', '5021303003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(104, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M - Infrastructure Assets', 'WATER SUPPLY SYSTEM', 'N1-3', '5021303004', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(105, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M - Transportation Equipment', 'MOTOR VEHICLE', 'N4-1', '5021306001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(106, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Buildings and Other Structures', 'BUILDINGS', 'N2-1', '5021304001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(107, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Buildings and Other Structures', 'HOSPITALS AND HEALTH CENTERS', 'N2-3', '5021304003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(108, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Buildings and Other Structures', 'HOSTELS AND DORMITORIES', 'N2-4', '5021304006', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(109, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Buildings and Other Structures', 'OTHER STRUCTURES', 'N2-5', '5021304099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(110, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Buildings and Other Structures', 'SCHOOL BUILDINGS', 'N2-2', '5021304002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(111, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'AGRICULTURAL AND FORESTRY EQUIPMENT', 'N3-4', '5021305004', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(112, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'COMMUNICATION EQUIPMENT', 'N3-6', '5021305007', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(113, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'Disaster, Response and Rescue Equipment', 'N3-7', '5021305009', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(114, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'ICT EQUIPMENT', 'N3-3', '5021305003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(115, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'MEDICAL EQUIPMENT', 'N3-9', '5021305011', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(116, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'Marine and Fishery Equipment', 'N3-5', '50213050 05', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(117, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'Military Police and Security Equipment', 'N3-8', '5021305010', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(118, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'OFFICE EQUIPMENT', 'N3-2', '5021305002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(119, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'OTHER MACHINERY & EQUIPMENT', 'N3-13', '5021305099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(120, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'PRINTING EQUIPMENT', 'N3-10', '5021305012', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(121, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'SPORTS EQUIPMENT', 'N3-11', '5021305013', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(122, 'NON-PS', 'MOOE', 'Repair and Maintenance (R&M)', 'R&M -- Machinery and Equipment', 'TECHNICAL AND SCIENTIFIC EQUIPMENT', 'N3-12', '5021305014', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(123, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Agricultural and Forestry Equipment', 'AGRICULTURAL AND FORESTRY EQUIPMENT', 'D4', '5020321004', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(124, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Communication Equipment', 'COMMUNICATION EQUIPMENT', 'D6', '5020321007', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(125, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Disaster Response and Rescue Equipment', 'DISASTER RESPONSE AND RESCUE EQUIPMENT', 'D7', '5020321008', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(126, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable ICT Equipment', 'ICT EQUIPMENT', 'D3', '5020321003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(127, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Medical Equipment', 'MEDICAL EQUIPMENT', 'D9', '5020321010', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(128, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Military, Police and Security Equipment', 'MILITARY, POLICE AND SECURITY EQUIPMENT', 'D8', '5020321009', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(129, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Office Equipment', 'OFFICE EQUIPMENT', 'D2', '5020321002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(130, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Other Machinery and Equipment', 'CLEANING MACHINERY AND EQUIPMENT', 'D13', '5020321099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(131, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Other Machinery and Equipment', 'ELECTRICAL, CONSTRUCTION, PLUMBING MACHINERY & EQUIPMENT', 'D13', '5020321099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(132, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Other Machinery and Equipment', 'KITCHEN EQUIPMENT', 'D13', '5020321099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(133, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Other Machinery and Equipment', 'OTHERS', 'D13', '5020321099', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(134, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Printing Equipment', 'PRINTING EQUIPMENT', 'D10', '5020321011', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(135, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Sports Equipment', 'SPORTS EQUIPMENT', 'D11', '5020321012', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(136, 'NON-PS', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Technical and Scientific Equipment', 'TECHNICAL AND SCIENTIFIC EQUIPMENT', 'D12', '5020321013', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(137, 'NON-PS', 'MOOE', 'Semi Expendables Furnitures, Fixtures and Books', 'Semi Expendables Furnitures, Fixtures and Books', 'BOOKS BELOW P50,000     PER UNIT', 'E2', '5020322002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(138, 'NON-PS', 'MOOE', 'Semi Expendables Furnitures, Fixtures and Books Expenses', 'Semi Expendables Furnitures, Fixtures and Books', 'FURNITURES AND FIXTURES', 'E1', '5020322001', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(139, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Accountable Forms Expenses', 'ACCOUNTABLE FORMS', 'C2', '5020302000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(140, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Agricultural Supplies Expenses', 'AGRICULTURAL SUPPLIES', 'C9', '5020310000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(141, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Animal/Zoological Supplies Expenses', 'Animal/Zoological Supplies Expenses', 'C4', '5020304000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(142, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'DRUGS AND MEDICINES', 'DRUGS AND MEDICINES', 'C6', '5020307000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(143, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'ACCREDITATION', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(144, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'EXTENSION', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(145, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'FOUNDATION', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(146, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'FUEL, OIL AND LUBRICANTS', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(147, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'GRADUATION', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(148, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(149, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(150, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(151, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'RESEARCH', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(152, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Fuel, Oil and Lubricants Expenses', 'UNIVERSITY GAMES', 'C8', '5020309000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(153, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Medical, Dental and Laboratory Supplies Expenses', 'MEDICAL, DENTAL AND LABORATORY SUPPLIES', 'C7', '5020308000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(154, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Non Accountable Forms Expenses', 'NON ACCOUNTABLE FORMS', 'C3', '5020303000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(155, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'ACCREDITATION', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(156, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'COMMON OFFICE SUPPLIES AND MATERIALS', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(157, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'EXTENSION', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(158, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'FILING SUPPLIES AND MATERIALS', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(159, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'FOUNDATION', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(160, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'GRADUATION', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(161, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(162, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(163, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(164, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'PAPER PRODUCTS', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(165, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'RESEARCH', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(166, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'RIBBONS, INKS, TONERS AND CARTRIDGES', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(167, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'UNIVERSITY GAMES', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(168, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'ACCREDITATION', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(169, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'AUTOMOTIVE, CONSTRUCTION AND PLUMBING, TOOLS, SUPPLIES AND MATERIALS', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(170, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'COMMON CLEANING SUPPLIES AND MATERIALS', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(171, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'ELECTRICAL, LIGHTING FIXTURES, TOOLS AND ACCESSORIES', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(172, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'EXTENSION', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(173, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'FOUNDATION', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(174, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'GRADUATION', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(175, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'KITCHEN SUPPLIES AND MATERIALS', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(176, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'MILITARY, POLICE AND TRAFFIC SUPPLIES', 'C11', '5020312000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(177, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'OTHER SUPPLIES AND MATERIALS', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(178, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'OTHER UNIVERSITY ACTIVITY 1\n(Capability Enhancement Training & Year-End Review)', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(179, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'OTHER UNIVERSITY ACTIVITY 2\n(Carmona Sorteo Festival)', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(180, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'OTHER UNIVERSITY ACTIVITY 3\n(Socio-Cultural Festival)', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(181, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'RESEARCH', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(182, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'UNIVERSITY GAMES', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(183, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Textbooks and Instructional Materials Expenses', 'MANUALS', 'C10', '5020311000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(184, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Textbooks and Instructional Materials Expenses', 'OTHER INSTRUCTIONAL MATERIALS', 'C10', '5020311000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(185, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Textbooks and Instructional Materials Expenses', 'PRINTED JOURNALS', 'C10', '5020311000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(186, 'NON-PS', 'MOOE', 'Supplies and Materials Expenses', 'Textbooks and Instructional Materials Expenses', 'TEXTBOOKS AND WORKBOOKS', 'C10', '5020311000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(187, 'NON-PS', 'MOOE', 'Survey, Research, Exploration and Development Expenses', 'Research, Exploration and Development', 'N/A', 'J1', '5020702000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(188, 'NON-PS', 'MOOE', 'Training and Scholarship Expenses', 'Training Expenses', 'CONDUCT OF TRAINING', 'B1', '5020201000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(189, 'NON-PS', 'MOOE', 'Training and Scholarship Expenses', 'Training Expenses', 'EXTENSION', 'B1', '5020201000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(190, 'NON-PS', 'MOOE', 'Traveling Expenses', 'Travelling Expense-Foreign', 'INTERNATIONAL TRAVEL', 'A2', '5020102000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(191, 'NON-PS', 'MOOE', 'Traveling Expenses', 'Travelling Expense-Local', 'LOCAL TRAVEL', 'A1', '5020101000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(192, 'PS-DBM', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Disaster Response and Rescue Equipment Expenses', 'Disaster Response and Rescue Equipment', 'D7', '5020321008', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(193, 'PS-DBM', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable ICT Equipment Expenses', 'ICT EQUIPMENT', 'D3', '5020321003', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(194, 'PS-DBM', 'MOOE', 'Semi Expendable Machinery and Equipment Expenses', 'Semi Expendable Office Equipment Expenses', 'OFFICE EQUIPMENT', 'D2', '5020321002', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(195, 'PS-DBM', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'COMMON OFFICE SUPPLIES', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(196, 'PS-DBM', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'FORMS', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(197, 'PS-DBM', 'MOOE', 'Supplies and Materials Expenses', 'Office Supplies Expenses', 'RIBBONS, INKS, TONERS AND CARTRIDGES', 'C1', '5020301000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(198, 'PS-DBM', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'CLEANING SUPPLIES AND MATERIALS', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(199, 'PS-DBM', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'COVID RECOVERY SUPPLIES', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24'),
(200, 'PS-DBM', 'MOOE', 'Supplies and Materials Expenses', 'Other Supplies & Materials Expenses', 'ELECTRICAL SUPPLIES AND MATERIALS', 'C12', '5020399000', NULL, 1, '2026-07-16 01:12:24', '2026-07-16 01:12:24');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_plans`
--

CREATE TABLE `procurement_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plan_number` varchar(255) NOT NULL,
  `year` year(4) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `allocated_budget` decimal(15,2) NOT NULL DEFAULT 0.00,
  `approved_budget` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_budget` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('Draft','Submitted','Reviewed','Approved','Rejected','Archived') NOT NULL DEFAULT 'Draft',
  `prepared_by` bigint(20) UNSIGNED NOT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procurement_plans`
--

INSERT INTO `procurement_plans` (`id`, `plan_number`, `year`, `department_id`, `allocated_budget`, `approved_budget`, `remaining_budget`, `status`, `prepared_by`, `reviewed_by`, `approved_by`, `remarks`, `submitted_at`, `approved_at`, `created_at`, `updated_at`) VALUES
(18, 'PPMP-2027-0001', '2027', 1, 150000.00, 150000.00, 150000.00, 'Approved', 17, NULL, 17, 'testing for DIIT v2', '2026-07-16 22:05:52', '2026-07-16 22:13:09', '2026-07-16 19:22:44', '2026-07-16 22:13:09'),
(23, 'PPMP-2027-0002', '2027', 8, 50000.00, 50000.00, 50000.00, 'Approved', 17, NULL, 17, 'for DTE', '2026-07-16 22:12:11', '2026-07-16 22:12:13', '2026-07-16 22:07:21', '2026-07-16 22:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_plan_items`
--

CREATE TABLE `procurement_plan_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `material_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estimated_unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `q1` int(11) NOT NULL DEFAULT 0,
  `q2` int(11) NOT NULL DEFAULT 0,
  `q3` int(11) NOT NULL DEFAULT 0,
  `q4` int(11) NOT NULL DEFAULT 0,
  `annual_quantity` int(11) NOT NULL DEFAULT 0,
  `annual_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `priority` enum('Low','Medium','High','Critical') NOT NULL DEFAULT 'Medium',
  `procurement_method` varchar(255) DEFAULT NULL,
  `source_of_fund` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procurement_plan_items`
--

INSERT INTO `procurement_plan_items` (`id`, `plan_id`, `material_id`, `material_name`, `description`, `unit_id`, `estimated_unit_cost`, `q1`, `q2`, `q3`, `q4`, `annual_quantity`, `annual_cost`, `priority`, `procurement_method`, `source_of_fund`, `remarks`, `created_by`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 447, 'A4 COPY ONE', NULL, 3, 200.00, 1, 2, 1, 1, 5, 1000.00, 'Medium', NULL, NULL, NULL, NULL, 0, '2026-06-30 16:42:14', '2026-06-30 21:06:56'),
(2, 1, 328, 'A4 Document Frame', NULL, 6, 50.00, 2, 2, 2, 2, 8, 400.00, 'Medium', NULL, NULL, NULL, NULL, 0, '2026-06-30 16:46:47', '2026-06-30 16:46:47'),
(20, 18, 1166, 'Printer', NULL, 2, 6000.00, 1, 1, 1, 1, 4, 24000.00, 'Medium', 'COMPETITIVE BIDDING', NULL, NULL, 21, 1, '2026-07-16 21:49:51', '2026-07-16 22:05:46'),
(22, 23, 1166, 'Printer', NULL, 2, 20000.00, 1, 0, 0, 0, 1, 20000.00, 'Medium', 'COMPETITIVE BIDDING', NULL, NULL, 27, 1, '2026-07-16 22:08:24', '2026-07-16 22:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_plan_item_logs`
--

CREATE TABLE `procurement_plan_item_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `action` enum('edited','deleted') NOT NULL,
  `reason` text DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procurement_plan_item_logs`
--

INSERT INTO `procurement_plan_item_logs` (`id`, `plan_id`, `material_name`, `action`, `reason`, `performed_by`, `created_at`, `updated_at`) VALUES
(4, 18, 'A4 Document Frame', 'deleted', 'madami pang A4 frame', 17, '2026-07-16 21:49:06', '2026-07-16 21:49:06'),
(5, 18, 'Printer', 'edited', 'bilhin na ung mas mahal', 17, '2026-07-16 21:50:42', '2026-07-16 21:50:42'),
(6, 18, 'Printer', 'edited', '4 na printer na pwede sa 20k', 21, '2026-07-16 21:51:53', '2026-07-16 21:51:53'),
(8, 18, 'Printer', 'edited', 'nag mahal na ang printer', 17, '2026-07-16 22:05:41', '2026-07-16 22:05:41'),
(9, 23, 'Printer', 'edited', 'ung mura lang dapat para pasok sa budget', 17, '2026-07-16 22:11:38', '2026-07-16 22:11:38');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'Administrative access', 1, '2026-07-07 17:12:34', '2026-07-07 17:12:34'),
(2, 'HR Officer', 'Human Resource Management', 1, '2026-07-07 17:12:34', '2026-07-07 17:12:34'),
(3, 'Secretary', 'Department Secretary', 1, '2026-07-07 17:12:34', '2026-07-07 17:12:34'),
(4, 'Inventory Custodian', 'Inventory Management', 1, '2026-07-07 17:12:34', '2026-07-07 17:12:34'),
(5, 'General Services Officer', 'General Services', 1, '2026-07-07 17:12:34', '2026-07-07 18:49:21'),
(6, 'Procurement Officer', 'Procurement', 1, '2026-07-07 17:12:34', '2026-07-07 17:12:34'),
(7, 'Research Coordinator', 'Research', 1, '2026-07-07 17:12:34', '2026-07-07 17:12:34'),
(8, 'Extension Coordinator', 'Extension', 1, '2026-07-07 17:12:34', '2026-07-07 17:12:34'),
(9, 'Employee - Faculty', 'Standard employee access', 1, '2026-07-07 17:12:34', '2026-07-17 01:05:05'),
(10, 'Property Custodian', 'Responsible for university property and assets.', 1, '2026-07-07 18:43:32', '2026-07-07 18:43:32'),
(11, 'Utility Role', 'General utility/maintenance staff role.', 0, '2026-07-15 23:53:13', '2026-07-15 23:58:20'),
(12, 'Utility', NULL, 0, '2026-07-15 23:55:38', '2026-07-15 23:57:11'),
(15, 'Department Chair / Unit Head', 'Manages PPMP line items for their own department', 1, '2026-07-16 17:58:56', '2026-07-16 17:58:56'),
(16, 'Employee - Non Acad', 'Non Academic Staff', 1, '2026-07-17 01:04:51', '2026-07-17 01:04:51');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 'Box', NULL, NULL),
(5, 'Pack', '2026-06-02 22:33:01', '2026-06-02 22:33:01'),
(6, 'Bottle', '2026-06-02 22:33:01', '2026-06-03 23:45:57'),
(7, 'Set', '2026-06-02 23:41:02', '2026-06-02 23:41:02'),
(9, 'Pieces', '2026-07-15 20:39:57', '2026-07-15 21:07:14'),
(10, 'Reams', '2026-07-15 20:39:58', '2026-07-15 21:07:24'),
(11, 'Pair', '2026-07-15 20:39:58', '2026-07-15 21:07:29'),
(12, 'Rolls', '2026-07-15 20:39:58', '2026-07-15 21:07:34'),
(13, 'Dozen', '2026-07-15 20:39:58', '2026-07-15 21:07:40'),
(14, 'Lot', '2026-07-16 21:07:32', '2026-07-16 21:07:32');

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
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birth_month` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'personnel',
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `must_change_password`, `remember_token`, `birthdate`, `birth_month`, `age`, `role`, `role_id`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Super Admin', 'admin@cvsu.edu.ph', 'superadmin', '$2y$12$ZbYz9wJOW1UYVUij8n8G2OVvZ0mBpyrXn/5wy.M6F8VV9Zl29Kgji', 0, NULL, '2011-02-16', 'February', 15, 'supervisor', 1, 'approved', '2026-04-27 22:35:31', '2026-07-16 00:56:55'),
(15, 'Mark Anthony Abril', 'markanthony.abril@cvsu.edu.ph', 'mark', '$2y$12$2hC1p2Szt.nKQEtK9AUJeeD62/.0XWdnyFhLpsQrj2bAHUj71vu/u', 0, NULL, '1987-09-09', 'September', 38, 'personnel', NULL, 'approved', '2026-07-07 15:21:51', '2026-07-15 18:45:15'),
(16, 'Mary Ann', 'mary@mail.com', 'mary', '$2y$12$H/OHkhxywu1mUagFYTWZRu.pd7a4ANPOL6QM8mVqCjqWgB981uHYS', 0, NULL, '2009-02-19', 'February', 17, 'personnel', NULL, 'approved', '2026-07-07 19:48:42', '2026-07-16 18:45:21'),
(17, 'Rochelle C. Malabayabas', 'rochelle.malabayabas@cvsu.edu.ph', 'rochelle', '$2y$12$8oNJV7ODLPiuqgkEDQNqYuZ36GBlSHSK/bDP90xiAky5LpyxzLlYW', 0, NULL, '2008-04-19', 'April', 18, 'personnel', 3, 'approved', '2026-07-08 19:33:31', '2026-07-16 00:56:55'),
(18, 'Diana H. Cortez', 'diana.cortez@cvsu.edu.ph', 'diana', '$2y$12$YxhcJD7cTrNPMaw6Iiyb/eQy61.RKf7hDIkT9Lx9acy7hvbcpg9ae', 0, NULL, '2009-04-18', 'April', 17, 'personnel', 9, 'approved', '2026-07-08 19:35:03', '2026-07-16 18:43:25'),
(19, 'Raymond T. Uminga', 'raymond.uminga@cvsu.edu.ph', 'raymond', '$2y$12$Mcz7Ro3AoVhJvz05BHJIT.d5MP05PBwPt5Ki5.JOy1DkxKLz5bTEK', 0, NULL, '2009-03-17', 'March', 17, 'personnel', 4, 'approved', '2026-07-08 19:35:56', '2026-07-15 18:48:23'),
(20, 'Arnold Balingit', 'arnold@mail.com', 'arnold', '$2y$12$b8Yv1SQs7zzMxTZ/ayjYU.o7EMnBj6Vkmt.Der4Wd3wKrz5GhaPc6', 0, NULL, '2013-01-19', 'January', 13, 'personnel', NULL, 'rejected', '2026-07-08 19:36:40', '2026-07-15 18:47:19'),
(21, 'Regene G. Hernandez', 'regene.hernandez@cvsu.edu.ph', 'regene', '$2y$12$2cbiKv4dMinaboSXLUw9rubuM9XA6ORHKTq0XydMDcxq.ItK2.2JO', 0, NULL, '2010-03-18', 'March', 16, 'personnel', 15, 'approved', '2026-07-08 19:37:22', '2026-07-16 18:52:15'),
(22, 'Aldrin Justimbaste', 'aldrin@mail.com', 'aldrin', '$2y$12$nMz48Sz.kMm350JO.oqz9uIMZw5m9fugFBjtUjvrJ4RXWZuZvimqi', 0, NULL, '2011-03-18', 'March', 15, 'personnel', 9, 'approved', '2026-07-15 23:47:35', '2026-07-15 23:48:22'),
(27, 'Joy Siochi', 'joy@cvsu.edu.ph', 'joy', '$2y$12$tpwjGMeNZS8KAGF63vsdHesnLS2kpOyLE3dnRnq.pYzN/TuFwj5Gu', 0, NULL, '1957-02-19', 'February', 69, 'personnel', 15, 'approved', '2026-07-16 21:55:50', '2026-07-17 21:03:13'),
(28, 'Jenny Beb F. Espineli', 'jennybeb_espineli@cvsu.edu.ph', 'jenny', '$2y$12$q7jTK/F9QlL/Kg7XvblpcezCSBl9R4rizJcJEs6H2MNtfeXsvWN7W', 0, NULL, '2010-04-17', 'April', 16, 'personnel', 1, 'approved', '2026-07-16 22:22:31', '2026-07-16 22:27:00'),
(29, 'Joe Marlou A. Opella', 'Jou@cvsu.edu', 'ops', '$2y$12$cyx9BZiTyN7PRUyIoak0nOguhSVYsqkq9ZhGVtWYatvlovzJ3eRjG', 0, NULL, '1967-02-19', 'February', 59, 'personnel', NULL, 'pending', '2026-07-17 01:02:04', '2026-07-17 01:02:04'),
(32, 'Qwncy Amie B. Abril', 'qwncy@mail.com', 'qwncy', '$2y$12$H./1E4dQAN5U0VFaxg.Dquuo8HhB.AqfrLgWbAwgTBu8OeHRbYaam', 0, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-17 19:45:44', '2026-07-17 19:45:44'),
(34, 'Joseph E. Cuarez', 'joseph@mail.com', 'joseph', '$2y$12$2Y2CxAPihu8T.VprhFiDDe55W/D1niI/IolFGACe3Cus6U7wRgQI2', 0, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-17 20:34:01', '2026-07-17 20:34:35'),
(36, 'King Ronmark B. Abril', 'king@mail.com', 'king', '$2y$12$nENU6EieBQNA2GAUKapflekVgUH1NnEHRIvcWAAK8X673XXcy.T12', 1, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-17 20:54:22', '2026-07-17 20:54:22');

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
(1, 'WI-20260623074328', 'Mark Anthony Abril', 9, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-22 23:43:28', NULL, NULL, '2026-06-22 23:43:28', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(2, 'WI-20260623080457', 'Mark Anthony Abril', 9, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:04:57', NULL, NULL, '2026-06-23 00:04:57', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(3, 'WI-20260623082113', 'Mark Anthony Abril', 9, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:21:13', NULL, NULL, '2026-06-23 00:21:13', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(4, 'WI-20260623082938', 'Mark Anthony Abril', 9, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:29:38', NULL, NULL, '2026-06-23 00:29:38', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(5, 'WI-20260623083122', 'Mark Anthony Abril', 9, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:31:22', NULL, NULL, '2026-06-23 00:31:22', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(6, 'WI-20260623084042', 'Mark Anthony Abril', 9, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 00:40:42', NULL, NULL, '2026-06-23 00:40:42', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(7, 'WI-20260624044105', 'Mark Anthony Abril', 9, 8, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:41:05', NULL, NULL, '2026-06-23 20:41:05', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(8, 'WI-20260624044243', 'Mark Anthony Abril', 9, 2, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:42:43', NULL, NULL, '2026-06-23 20:42:43', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(9, 'WI-20260624044545', 'Mark Anthony Abril', 9, 2, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:45:45', NULL, NULL, '2026-06-23 20:45:45', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(10, 'WI-20260624044723', 'Mark Anthony Abril', 9, 8, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:47:23', NULL, NULL, '2026-06-23 20:47:23', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(11, 'WI-20260624045534', 'Mark Anthony Abril', 9, 8, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-06-23 20:55:34', NULL, NULL, '2026-06-23 20:55:34', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(12, 'WI-20260624050143', 'Mark Anthony Abril', 9, 2, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 21:01:43', NULL, NULL, '2026-06-23 21:01:43', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(13, 'WI-20260624050543', 'Mark Anthony Abril', 9, 2, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 21:05:43', NULL, NULL, '2026-06-23 21:05:43', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(14, 'WI-20260624053241', 'Mark Anthony Abril', 9, 7, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-23 21:32:41', NULL, NULL, '2026-06-23 21:32:41', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(15, 'WI-20260624080607', 'Mark Anthony Abril', 9, 7, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-24 00:06:07', NULL, NULL, '2026-06-24 00:06:07', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(16, 'WI-20260624080919', 'Mark Anthony Abril', 9, 8, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-24 00:09:19', NULL, NULL, '2026-06-24 00:09:19', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(17, 'WI-20260625030526', 'Cua', NULL, 1, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-06-24 19:05:26', NULL, NULL, '2026-06-24 19:05:26', '2026-06-24 19:05:26', 'WALK-IN ISSUE'),
(18, 'WI-20260706070900', 'Cua', NULL, 10, 'Admin office', 'Replace', 'Normal', NULL, 3, 'Issued', '2026-07-05 23:09:00', NULL, NULL, '2026-07-05 23:09:00', '2026-07-05 23:09:00', 'WALK-IN ISSUE'),
(19, 'WI-20260706071508', 'Juls', NULL, 12, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-07-05 23:15:08', NULL, NULL, '2026-07-05 23:15:08', '2026-07-05 23:15:08', 'WALK-IN ISSUE'),
(20, 'WI-20260706072321', 'Juls', NULL, 11, 'Admin office', 'Printing', 'Normal', NULL, 3, 'Issued', '2026-07-05 23:23:21', NULL, NULL, '2026-07-05 23:23:21', '2026-07-05 23:23:21', 'WALK-IN ISSUE'),
(21, 'WI-20260716070952', 'Mark Anthony Abril', 9, 1, 'Admin office', 'Printing', 'Normal', NULL, 19, 'Issued', '2026-07-15 23:09:52', NULL, NULL, '2026-07-15 23:09:52', '2026-07-17 20:06:37', 'WALK-IN ISSUE'),
(23, 'WI-20260718035555', 'Qwncy Amie B. Abril', 24, 1, 'Star 201', 'Printing', 'Normal', NULL, 19, 'Issued', '2026-07-17 19:55:55', NULL, NULL, '2026-07-17 19:55:55', '2026-07-17 19:55:55', 'WALK-IN ISSUE');

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
(22, 21, 1154, 2.00, 'Pieces', NULL, 100.00, 98.00, '2026-07-15 23:09:52', '2026-07-15 23:09:52'),
(23, 21, 1154, 3.00, 'Pieces', NULL, 98.00, 95.00, '2026-07-15 23:09:52', '2026-07-15 23:09:52'),
(25, 23, 1154, 5.00, 'Pieces', NULL, 85.00, 80.00, '2026-07-17 19:55:55', '2026-07-17 19:55:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_target_user_id_foreign` (`target_user_id`);

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
-- Indexes for table `employee_contacts`
--
ALTER TABLE `employee_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_contacts_personnel_id_foreign` (`personnel_id`);

--
-- Indexes for table `employee_educations`
--
ALTER TABLE `employee_educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_educations_personnel_id_foreign` (`personnel_id`);

--
-- Indexes for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_profiles_personnel_id_unique` (`personnel_id`);

--
-- Indexes for table `employment_types`
--
ALTER TABLE `employment_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employment_types_name_unique` (`name`);

--
-- Indexes for table `employment_type_position`
--
ALTER TABLE `employment_type_position`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_mapping` (`employment_type_id`,`position_id`),
  ADD KEY `fk_etp_position` (`position_id`);

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
  ADD KEY `materials_department_id_foreign` (`department_id`),
  ADD KEY `materials_classification_id_foreign` (`classification_id`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_role_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `permission_role_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personnel_user_id_foreign` (`user_id`),
  ADD KEY `personnel_employment_type_id_foreign` (`employment_type_id`),
  ADD KEY `personnel_department_id_foreign` (`department_id`),
  ADD KEY `personnel_position_id_foreign` (`position_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `positions_position_name_unique` (`position_name`),
  ADD UNIQUE KEY `positions_position_code_unique` (`position_code`),
  ADD KEY `fk_positions_employment_type` (`employment_type_id`);

--
-- Indexes for table `procurement_classifications`
--
ALTER TABLE `procurement_classifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `procurement_classification_unique` (`part`,`main_category`,`sub_category_a`,`sub_category_b`,`sub_category_c`,`code`,`uacs_code`) USING HASH,
  ADD KEY `procurement_classifications_part_index` (`part`),
  ADD KEY `procurement_classifications_main_category_index` (`main_category`),
  ADD KEY `procurement_classifications_uacs_code_index` (`uacs_code`);

--
-- Indexes for table `procurement_plans`
--
ALTER TABLE `procurement_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `procurement_plans_plan_number_unique` (`plan_number`),
  ADD KEY `procurement_plans_department_id_foreign` (`department_id`),
  ADD KEY `procurement_plans_prepared_by_foreign` (`prepared_by`),
  ADD KEY `procurement_plans_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `procurement_plans_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `procurement_plan_items`
--
ALTER TABLE `procurement_plan_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procurement_plan_items_created_by_foreign` (`created_by`);

--
-- Indexes for table `procurement_plan_item_logs`
--
ALTER TABLE `procurement_plan_item_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procurement_plan_item_logs_plan_id_foreign` (`plan_id`),
  ADD KEY `procurement_plan_item_logs_performed_by_foreign` (`performed_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

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
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_role_id_foreign` (`role_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `department_materials`
--
ALTER TABLE `department_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `employee_contacts`
--
ALTER TABLE `employee_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_educations`
--
ALTER TABLE `employee_educations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employment_types`
--
ALTER TABLE `employment_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employment_type_position`
--
ALTER TABLE `employment_type_position`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1183;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1168;

--
-- AUTO_INCREMENT for table `material_logs`
--
ALTER TABLE `material_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1214;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `material_request_items`
--
ALTER TABLE `material_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `material_restock_logs`
--
ALTER TABLE `material_restock_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `procurement_classifications`
--
ALTER TABLE `procurement_classifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `procurement_plans`
--
ALTER TABLE `procurement_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `procurement_plan_items`
--
ALTER TABLE `procurement_plan_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `procurement_plan_item_logs`
--
ALTER TABLE `procurement_plan_item_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `walkin_requests`
--
ALTER TABLE `walkin_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `walkin_request_items`
--
ALTER TABLE `walkin_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_target_user_id_foreign` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `department_materials`
--
ALTER TABLE `department_materials`
  ADD CONSTRAINT `fk_department_materials_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`);

--
-- Constraints for table `employee_contacts`
--
ALTER TABLE `employee_contacts`
  ADD CONSTRAINT `employee_contacts_personnel_id_foreign` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_educations`
--
ALTER TABLE `employee_educations`
  ADD CONSTRAINT `employee_educations_personnel_id_foreign` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  ADD CONSTRAINT `employee_profiles_personnel_id_foreign` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employment_type_position`
--
ALTER TABLE `employment_type_position`
  ADD CONSTRAINT `fk_etp_employment` FOREIGN KEY (`employment_type_id`) REFERENCES `employment_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_etp_position` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `materials_classification_id_foreign` FOREIGN KEY (`classification_id`) REFERENCES `procurement_classifications` (`id`) ON DELETE SET NULL,
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
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `personnel_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `personnel_employment_type_id_foreign` FOREIGN KEY (`employment_type_id`) REFERENCES `employment_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `personnel_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `personnel_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `positions`
--
ALTER TABLE `positions`
  ADD CONSTRAINT `fk_positions_employment_type` FOREIGN KEY (`employment_type_id`) REFERENCES `employment_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `procurement_plans`
--
ALTER TABLE `procurement_plans`
  ADD CONSTRAINT `procurement_plans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `procurement_plans_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `procurement_plans_prepared_by_foreign` FOREIGN KEY (`prepared_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `procurement_plans_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `procurement_plan_items`
--
ALTER TABLE `procurement_plan_items`
  ADD CONSTRAINT `procurement_plan_items_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `procurement_plan_item_logs`
--
ALTER TABLE `procurement_plan_item_logs`
  ADD CONSTRAINT `procurement_plan_item_logs_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `procurement_plan_item_logs_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `procurement_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

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
