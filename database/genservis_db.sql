-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 24, 2026 at 06:54 AM
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
(15, 'Mark Anthony Abril', 'markanthony.abril@cvsu.edu.ph', 'mark', '$2y$12$2hC1p2Szt.nKQEtK9AUJeeD62/.0XWdnyFhLpsQrj2bAHUj71vu/u', 0, NULL, '1987-09-09', 'September', 38, 'personnel', 5, 'approved', '2026-07-07 15:21:51', '2026-07-17 22:05:07'),
(16, 'Mary Ann', 'mary@mail.com', 'mary', '$2y$12$H/OHkhxywu1mUagFYTWZRu.pd7a4ANPOL6QM8mVqCjqWgB981uHYS', 0, NULL, '2009-02-19', 'February', 17, 'personnel', NULL, 'approved', '2026-07-07 19:48:42', '2026-07-16 18:45:21'),
(17, 'Rochelle C. Malabayabas', 'rochelle.malabayabas@cvsu.edu.ph', 'rochelle', '$2y$12$8oNJV7ODLPiuqgkEDQNqYuZ36GBlSHSK/bDP90xiAky5LpyxzLlYW', 0, NULL, '2008-04-19', 'April', 18, 'personnel', 3, 'approved', '2026-07-08 19:33:31', '2026-07-16 00:56:55'),
(18, 'Diana H. Cortez', 'diana.cortez@cvsu.edu.ph', 'diana', '$2y$12$YxhcJD7cTrNPMaw6Iiyb/eQy61.RKf7hDIkT9Lx9acy7hvbcpg9ae', 0, NULL, '2009-04-18', 'April', 17, 'personnel', 2, 'approved', '2026-07-08 19:35:03', '2026-07-20 17:17:16'),
(19, 'Raymond T. Uminga', 'raymond.uminga@cvsu.edu.ph', 'raymond', '$2y$12$Mcz7Ro3AoVhJvz05BHJIT.d5MP05PBwPt5Ki5.JOy1DkxKLz5bTEK', 0, NULL, '2009-03-17', 'March', 17, 'personnel', 4, 'approved', '2026-07-08 19:35:56', '2026-07-15 18:48:23'),
(20, 'Arnold Balingit', 'arnold@mail.com', 'arnold', '$2y$12$b8Yv1SQs7zzMxTZ/ayjYU.o7EMnBj6Vkmt.Der4Wd3wKrz5GhaPc6', 0, NULL, '2013-01-19', 'January', 13, 'personnel', 16, 'approved', '2026-07-08 19:36:40', '2026-07-18 00:00:46'),
(21, 'Regene G. Hernandez', 'regene.hernandez@cvsu.edu.ph', 'regene', '$2y$12$2cbiKv4dMinaboSXLUw9rubuM9XA6ORHKTq0XydMDcxq.ItK2.2JO', 0, NULL, '2010-03-18', 'March', 16, 'personnel', 15, 'approved', '2026-07-08 19:37:22', '2026-07-16 18:52:15'),
(22, 'Aldrin Justimbaste', 'aldrin@mail.com', 'aldrin', '$2y$12$nMz48Sz.kMm350JO.oqz9uIMZw5m9fugFBjtUjvrJ4RXWZuZvimqi', 0, NULL, '2011-03-18', 'March', 15, 'personnel', 16, 'approved', '2026-07-15 23:47:35', '2026-07-19 16:14:49'),
(27, 'Joy Siochi', 'joy@cvsu.edu.ph', 'joy', '$2y$12$tpwjGMeNZS8KAGF63vsdHesnLS2kpOyLE3dnRnq.pYzN/TuFwj5Gu', 0, NULL, '1957-02-19', 'February', 69, 'personnel', 15, 'approved', '2026-07-16 21:55:50', '2026-07-17 21:03:13'),
(28, 'Jenny Beb F. Espineli', 'jennybeb_espineli@cvsu.edu.ph', 'jenny', '$2y$12$q7jTK/F9QlL/Kg7XvblpcezCSBl9R4rizJcJEs6H2MNtfeXsvWN7W', 0, NULL, '2010-04-17', 'April', 16, 'personnel', 1, 'approved', '2026-07-16 22:22:31', '2026-07-16 22:27:00'),
(29, 'Joe Marlou A. Opella', 'Jou@cvsu.edu', 'ops', '$2y$12$cyx9BZiTyN7PRUyIoak0nOguhSVYsqkq9ZhGVtWYatvlovzJ3eRjG', 0, NULL, '1967-02-19', 'February', 59, 'personnel', NULL, 'pending', '2026-07-17 01:02:04', '2026-07-17 01:02:04'),
(32, 'Qwncy Amie B. Abril', 'qwncy@mail.com', 'qwncy', '$2y$12$H./1E4dQAN5U0VFaxg.Dquuo8HhB.AqfrLgWbAwgTBu8OeHRbYaam', 0, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-17 19:45:44', '2026-07-17 19:45:44'),
(34, 'Joseph E. Cuarez', 'joseph@mail.com', 'joseph', '$2y$12$2Y2CxAPihu8T.VprhFiDDe55W/D1niI/IolFGACe3Cus6U7wRgQI2', 0, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-17 20:34:01', '2026-07-17 20:34:35'),
(36, 'King Ronmark B. Abril', 'king@mail.com', 'king', '$2y$12$nENU6EieBQNA2GAUKapflekVgUH1NnEHRIvcWAAK8X673XXcy.T12', 1, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-17 20:54:22', '2026-07-17 20:54:22'),
(39, 'Rony M. Basilan', 'rony@mail.com', 'rony', '$2y$12$q0oji8SUrttw77iBioGae.Ys8nGPNtny.gQ43QhVDjF1IGAd5L7Nm', 0, NULL, '2011-02-18', 'February', 15, 'personnel', 18, 'approved', '2026-07-17 22:11:04', '2026-07-17 22:15:21'),
(40, 'Marilou Corrales', 'marilou@gmail.com', 'Malou', '$2y$12$wILoBqBIzCYAdAJLudbtSeHaDfnNlEcBsrXMktlDng5pR1WLMCkm2', 0, NULL, '2009-02-18', 'February', 17, 'personnel', 16, 'approved', '2026-07-17 23:55:48', '2026-07-17 23:58:07'),
(41, 'Aileen B. Estrada', 'aileen@gmail.com', 'aileen', '$2y$12$ACJTqCX4xlzneny0HgPt1uyOJ0bqgV0lxjG.espHgQ82yst5rbEsO', 0, NULL, '2009-02-19', 'February', 17, 'personnel', 16, 'approved', '2026-07-17 23:56:30', '2026-07-20 19:17:42'),
(42, 'Nerrisa B. Cator', 'Nerrisa@mail.com', 'nerissa', '$2y$12$joIwvaLdzVEB25Z5UKU4SOTWV/WhwLVrSOGupitPEc6PPnr/seKSm', 0, NULL, '2009-03-19', 'March', 17, 'personnel', 16, 'approved', '2026-07-17 23:57:07', '2026-07-17 23:57:36'),
(43, 'John Mathew A. Espeleta', 'john@mail.com', 'john', '$2y$12$jO.TDCb184O2Z.6JQbDMZufIXnwEPEtTx1ESzRa7TENPaOS/QxdMq', 0, NULL, '2010-04-18', 'April', 16, 'personnel', 16, 'approved', '2026-07-19 16:19:24', '2026-07-19 16:20:13'),
(44, 'Ernesto Bergonia Jr.', 'ernesto@mail.com', 'ernesto', '$2y$12$YgGevYP0X6VouTy83.4Vneok2MdpbJDZ8tcLdNUIdULFZzPRc40mm', 0, NULL, '2010-04-18', 'April', 16, 'personnel', 16, 'approved', '2026-07-19 16:21:18', '2026-07-20 19:17:54'),
(45, 'Julius Mendoza', 'julius@mail.com', 'julius', '$2y$12$Gdy8m9TPXZdIF4WrhWI8reVXVECkqkVVe4DltefAFILaAeWp/0Sum', 0, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-19 21:05:10', '2026-07-19 21:28:10'),
(46, 'John Benneth C. Abuan', 'johnbenneth@cvsu.email.com', 'johnb', '$2y$12$jEvK//SBQwpOsGh.NO9drOJ3xzTtoPdyH0wTzPF23tSyuJSH49EEi', 0, NULL, '2009-03-18', 'March', 17, 'personnel', 10, 'approved', '2026-07-20 16:27:01', '2026-07-20 17:16:00'),
(47, 'Kris C. Alforte', 'kris@mail.com', 'kris', '$2y$12$HQOGsbNLqJ47GmuJK5f3yudjIQ26d.Vi5e.SALjh7PZvieQl2hZZC', 0, NULL, '2009-04-18', 'April', 17, 'personnel', 21, 'approved', '2026-07-21 21:55:30', '2026-07-21 23:38:13'),
(48, 'Antonino Jose L. Bayson', 'antoninojose.bayson@cvsu.edu.ph', 'antonino', '$2y$12$T8KTO4cWl/Aj6rNtOA24CuGHqbGffXZnXNrhGM4t2oQLz0AKoYVhi', 1, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-21 22:18:17', '2026-07-21 22:18:17'),
(49, 'Carlo Emil B. Mañabo', 'carloemil.manabo@cvsu.edu.ph', 'carlo', '$2y$12$WBGSg/43x4cj8eY/yEVdB.9gYQtRtg/wgP6KLtPq5BQQi9W9X3LoO', 1, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-21 22:31:35', '2026-07-21 22:31:35'),
(50, 'Brendaln O. Guinoban', 'brendalyn.guinoban@cvsu.edu.ph', 'brendaln', '$2y$12$ddo2Fsgfn7r1r.3HpzC3NOWo7t2c16N9FXU6AGd10SyKmtDktGCk6', 1, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-21 22:38:24', '2026-07-21 22:38:24'),
(51, 'Jhumel C. Ignas', 'jhumel.ignas@cvsu.edu.ph', 'jhumel', '$2y$12$JXhF1w/qyWNFJKwS.qFvUOuNkQcCtjxjpIFpYupnEGqai0P/cQrai', 1, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-21 22:42:33', '2026-07-21 22:42:33'),
(52, 'Shiela L. Vidallon', 'shiela.vidallon@cvsu.edu.ph', 'shiela', '$2y$12$1Ivic8B1L69sAeq50I3DXukRq9kgjpcTU6c9U/gGK91ddzmqEuwQK', 1, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-21 22:44:48', '2026-07-21 22:44:48'),
(53, 'Maria Andrea C. Francia', 'mariaandrea.francia@cvsu.edu.ph', 'andrea', '$2y$12$RbUCe99D75r.XZqNXZgWbe/6EVlTnLmRgJwNq07wUGRMmBB5HiK1K', 1, NULL, NULL, NULL, NULL, 'personnel', 16, 'approved', '2026-07-21 22:54:18', '2026-07-21 22:54:18'),
(54, 'Ronlie Rj A. Espeleta', 'ronlierj.espeleta@cvsu.edu.ph', 'anje', '$2y$12$qWO1CWW/etWOzO/QlPCdheq5TULRZpkuVucQqogO6EzxC.wAJkxLe', 0, NULL, '2010-03-18', 'March', 16, 'personnel', 23, 'approved', '2026-07-22 00:21:54', '2026-07-22 00:27:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
