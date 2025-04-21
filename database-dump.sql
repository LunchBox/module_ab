-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- 主机： db
-- 生成日期： 2025-04-21 11:21:56
-- 服务器版本： 8.0.42
-- PHP 版本： 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `laravel`
--

-- --------------------------------------------------------

--
-- 表的结构 `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `campuses`
--

CREATE TABLE `campuses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `campuses`
--

INSERT INTO `campuses` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Campus A', '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(2, 'Campus B', '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(3, 'Campus C', '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(4, 'Campus D', '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(5, 'Campus E', '2025-04-21 11:15:29', '2025-04-21 11:15:29');

-- --------------------------------------------------------

--
-- 表的结构 `clients`
--

CREATE TABLE `clients` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `clients`
--

INSERT INTO `clients` (`id`, `email`, `password`, `firstname`, `lastname`, `phone_number`, `created_at`, `updated_at`) VALUES
(1, 'client@wsc.com', '$2y$12$dMj7chIMfr2fJRr8AKmTI.CzIh/f81rr.zP3lC2eIma8aoj9V6BSW', 'John', 'Doe', '11890481', '2025-04-21 11:15:30', '2025-04-21 11:15:30'),
(2, 'smith@wsc.com', '$2y$12$jFSo3S4Fn58LUxcCBzNro.t6wcpIQoWZPg81.UMV22pH1pvE.OtEO', 'Mary', 'Smith', '33178732', '2025-04-21 11:15:30', '2025-04-21 11:15:30');

-- --------------------------------------------------------

--
-- 表的结构 `containers`
--

CREATE TABLE `containers` (
  `id` bigint UNSIGNED NOT NULL,
  `from_campus_id` bigint UNSIGNED NOT NULL,
  `to_campus_id` bigint UNSIGNED NOT NULL,
  `trucker_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('waiting','loaded','unloaded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `container_package`
--

CREATE TABLE `container_package` (
  `id` bigint UNSIGNED NOT NULL,
  `container_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_21_103032_create_clients_table', 1),
(5, '2025_04_21_103114_create_campuses_table', 1),
(6, '2025_04_21_103115_create_routes_table', 1),
(7, '2025_04_21_103116_create_staff_table', 1),
(8, '2025_04_21_103326_create_route_campus_table', 1),
(9, '2025_04_21_103500_create_containers_table', 1),
(10, '2025_04_21_103501_create_packages_table', 1),
(11, '2025_04_21_103558_create_package_progress_table', 1),
(12, '2025_04_21_103710_create_container_package_table', 1),
(13, '2025_04_21_103746_create_tokens_table', 1);

-- --------------------------------------------------------

--
-- 表的结构 `packages`
--

CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `from_campus_id` bigint UNSIGNED NOT NULL,
  `from_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_campus_id` bigint UNSIGNED NOT NULL,
  `to_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_phone_number` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Pending pickup','Picked up','Pending transit','In transit','Pending delivery','Delivering','Signed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending pickup',
  `returning` tinyint(1) NOT NULL DEFAULT '0',
  `pickup_courier_id` bigint UNSIGNED DEFAULT NULL,
  `delivery_courier_id` bigint UNSIGNED DEFAULT NULL,
  `container_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `package_progress`
--

CREATE TABLE `package_progress` (
  `id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campus_id` bigint UNSIGNED DEFAULT NULL,
  `returning` tinyint(1) NOT NULL DEFAULT '0',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `routes`
--

CREATE TABLE `routes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `routes`
--

INSERT INTO `routes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Route 1', '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(2, 'Route 2', '2025-04-21 11:15:29', '2025-04-21 11:15:29');

-- --------------------------------------------------------

--
-- 表的结构 `route_campuses`
--

CREATE TABLE `route_campuses` (
  `id` bigint UNSIGNED NOT NULL,
  `route_id` bigint UNSIGNED NOT NULL,
  `campus_id` bigint UNSIGNED NOT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `route_campuses`
--

INSERT INTO `route_campuses` (`id`, `route_id`, `campus_id`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(2, 1, 2, 2, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(3, 1, 3, 3, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(4, 1, 4, 4, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(5, 1, 5, 5, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(6, 2, 5, 1, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(7, 2, 4, 2, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(8, 2, 3, 3, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(9, 2, 2, 4, '2025-04-21 11:15:29', '2025-04-21 11:15:29'),
(10, 2, 1, 5, '2025-04-21 11:15:29', '2025-04-21 11:15:29');

-- --------------------------------------------------------

--
-- 表的结构 `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `staff`
--

CREATE TABLE `staff` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('courier','trucker') COLLATE utf8mb4_unicode_ci NOT NULL,
  `campus_id` bigint UNSIGNED DEFAULT NULL,
  `route_id` bigint UNSIGNED DEFAULT NULL,
  `plate_number` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online` tinyint(1) NOT NULL DEFAULT '0',
  `remaining_capacity` int NOT NULL DEFAULT '5',
  `total_picked` int NOT NULL DEFAULT '0',
  `total_delivered` int NOT NULL DEFAULT '0',
  `total_unloaded` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `staff`
--

INSERT INTO `staff` (`id`, `email`, `password`, `firstname`, `lastname`, `phone_number`, `role`, `campus_id`, `route_id`, `plate_number`, `online`, `remaining_capacity`, `total_picked`, `total_delivered`, `total_unloaded`, `created_at`, `updated_at`) VALUES
(1, 'courier@wsc.com', '$2y$12$ygcpCklGqYaewHiaXNo9nufCquOKZ22dHPDri2P7b3LYPFZV/aME.', 'Matthew', 'Green', '12890482', 'courier', 1, NULL, NULL, 0, 5, 0, 0, 0, '2025-04-21 11:15:30', '2025-04-21 11:15:30'),
(2, 'trucker@wsc.com', '$2y$12$0HHPEyqKR6XZ2aDNucKhU.DpqnoVAUJ/mrewnEq33nhivdT45Dlwy', 'Harper', 'Lopez', NULL, 'trucker', NULL, 1, 'A10001', 0, 15, 0, 0, 0, '2025-04-21 11:15:31', '2025-04-21 11:15:31');

-- --------------------------------------------------------

--
-- 表的结构 `tokens`
--

CREATE TABLE `tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$btt8xxTUSOHYi9yTwYllyeloTEt85AAbIqgGHWNGleo6H5RPlgppq', NULL, '2025-04-21 11:15:30', '2025-04-21 11:15:30');

--
-- 转储表的索引
--

--
-- 表的索引 `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- 表的索引 `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- 表的索引 `campuses`
--
ALTER TABLE `campuses`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_email_unique` (`email`);

--
-- 表的索引 `containers`
--
ALTER TABLE `containers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `containers_from_campus_id_foreign` (`from_campus_id`),
  ADD KEY `containers_to_campus_id_foreign` (`to_campus_id`),
  ADD KEY `containers_trucker_id_foreign` (`trucker_id`);

--
-- 表的索引 `container_package`
--
ALTER TABLE `container_package`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `container_package_container_id_package_id_unique` (`container_id`,`package_id`),
  ADD KEY `container_package_package_id_foreign` (`package_id`);

--
-- 表的索引 `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- 表的索引 `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- 表的索引 `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `packages_tracking_number_unique` (`tracking_number`),
  ADD KEY `packages_client_id_foreign` (`client_id`),
  ADD KEY `packages_from_campus_id_foreign` (`from_campus_id`),
  ADD KEY `packages_to_campus_id_foreign` (`to_campus_id`),
  ADD KEY `packages_pickup_courier_id_foreign` (`pickup_courier_id`),
  ADD KEY `packages_delivery_courier_id_foreign` (`delivery_courier_id`),
  ADD KEY `packages_container_id_foreign` (`container_id`);

--
-- 表的索引 `package_progress`
--
ALTER TABLE `package_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_progress_package_id_foreign` (`package_id`),
  ADD KEY `package_progress_campus_id_foreign` (`campus_id`);

--
-- 表的索引 `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `route_campuses`
--
ALTER TABLE `route_campuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `route_campuses_route_id_foreign` (`route_id`),
  ADD KEY `route_campuses_campus_id_foreign` (`campus_id`);

--
-- 表的索引 `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- 表的索引 `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_email_unique` (`email`),
  ADD KEY `staff_campus_id_foreign` (`campus_id`),
  ADD KEY `staff_route_id_foreign` (`route_id`);

--
-- 表的索引 `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tokens_token_unique` (`token`),
  ADD KEY `tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `campuses`
--
ALTER TABLE `campuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `containers`
--
ALTER TABLE `containers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `container_package`
--
ALTER TABLE `container_package`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `package_progress`
--
ALTER TABLE `package_progress`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `route_campuses`
--
ALTER TABLE `route_campuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 限制导出的表
--

--
-- 限制表 `containers`
--
ALTER TABLE `containers`
  ADD CONSTRAINT `containers_from_campus_id_foreign` FOREIGN KEY (`from_campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `containers_to_campus_id_foreign` FOREIGN KEY (`to_campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `containers_trucker_id_foreign` FOREIGN KEY (`trucker_id`) REFERENCES `staff` (`id`);

--
-- 限制表 `container_package`
--
ALTER TABLE `container_package`
  ADD CONSTRAINT `container_package_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`),
  ADD CONSTRAINT `container_package_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

--
-- 限制表 `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `packages_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`),
  ADD CONSTRAINT `packages_delivery_courier_id_foreign` FOREIGN KEY (`delivery_courier_id`) REFERENCES `staff` (`id`),
  ADD CONSTRAINT `packages_from_campus_id_foreign` FOREIGN KEY (`from_campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `packages_pickup_courier_id_foreign` FOREIGN KEY (`pickup_courier_id`) REFERENCES `staff` (`id`),
  ADD CONSTRAINT `packages_to_campus_id_foreign` FOREIGN KEY (`to_campus_id`) REFERENCES `campuses` (`id`);

--
-- 限制表 `package_progress`
--
ALTER TABLE `package_progress`
  ADD CONSTRAINT `package_progress_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `package_progress_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

--
-- 限制表 `route_campuses`
--
ALTER TABLE `route_campuses`
  ADD CONSTRAINT `route_campuses_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `route_campuses_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);

--
-- 限制表 `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`),
  ADD CONSTRAINT `staff_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
