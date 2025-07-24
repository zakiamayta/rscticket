-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for dbrscticket
CREATE DATABASE IF NOT EXISTS `dbrscticket` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dbrscticket`;

-- Dumping structure for view dbrscticket.admin_transaction_view
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `admin_transaction_view` (
	`transaction_id` INT(10) NOT NULL,
	`email` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`payment_status` ENUM('unpaid','paid') NULL COLLATE 'utf8mb4_0900_ai_ci',
	`checkout_time` DATETIME NULL,
	`paid_time` DATETIME NULL,
	`qris_invoice_id` VARCHAR(100) NULL COLLATE 'utf8mb4_0900_ai_ci',
	`qris_url` TEXT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`nik` BIGINT(19) NOT NULL,
	`name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`phone_number` BIGINT(19) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for table dbrscticket.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrscticket.cache: ~0 rows (approximately)

-- Dumping structure for table dbrscticket.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrscticket.cache_locks: ~0 rows (approximately)

-- Dumping structure for table dbrscticket.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrscticket.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table dbrscticket.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrscticket.jobs: ~0 rows (approximately)

-- Dumping structure for table dbrscticket.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrscticket.job_batches: ~0 rows (approximately)

-- Dumping structure for table dbrscticket.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrscticket.migrations: ~3 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1);

-- Dumping structure for table dbrscticket.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrscticket.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table dbrscticket.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table dbrscticket.sessions: ~1 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('KKGGAC1yZMouiJ5MEU32yAzeWPfy7CDIVs6O5haU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibHBkejMwT01mamJReGlMd3lBQ1B4VzFEM2xiWDlJSnRLNVpSM1JQYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1753007566),
	('Kpj1UpYDGA2YCcfhaVM32e22AE2E2ggUnqToTbTH', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibkJQWW9EeElxTnNEWGJpWlZNVDFnWlVtZ3owcWpMbklGMVR3MTJ1aCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1753111840);

-- Dumping structure for table dbrscticket.ticket_stock
CREATE TABLE IF NOT EXISTS `ticket_stock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `available_stock` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dbrscticket.ticket_stock: ~0 rows (approximately)
REPLACE INTO `ticket_stock` (`id`, `available_stock`, `created_at`, `updated_at`) VALUES
	(1, 5, '2025-07-20 08:50:21', '2025-07-20 11:19:32');

-- Dumping structure for table dbrscticket.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `checkout_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `paid_time` datetime DEFAULT NULL,
  `payment_status` enum('unpaid','paid') DEFAULT 'unpaid',
  `qris_invoice_id` varchar(100) DEFAULT NULL,
  `qris_url` text,
  `total_amount` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dbrscticket.transactions: ~6 rows (approximately)
REPLACE INTO `transactions` (`id`, `email`, `checkout_time`, `paid_time`, `payment_status`, `qris_invoice_id`, `qris_url`, `total_amount`) VALUES
	(1, 'ziamayta@gmail.com', '2025-07-20 01:50:53', NULL, 'unpaid', NULL, NULL, 0),
	(2, 'ziamayta@gmail.com', '2025-07-20 02:37:20', NULL, 'unpaid', NULL, NULL, 0),
	(3, 'ziamayta@gmail.com', '2025-07-20 02:38:38', NULL, 'unpaid', NULL, NULL, 0),
	(4, 'ziamayta@gmail.com', '2025-07-20 04:13:43', NULL, 'unpaid', NULL, NULL, 50000),
	(5, 'ziamayta@gmail.com', '2025-07-20 04:14:18', NULL, 'unpaid', NULL, NULL, 100000),
	(6, 'ziamayta@gmail.com', '2025-07-20 04:19:32', NULL, 'unpaid', NULL, NULL, 100000);

-- Dumping structure for table dbrscticket.transaction_details
CREATE TABLE IF NOT EXISTS `transaction_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` int NOT NULL,
  `nik` bigint NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone_number` bigint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nik` (`nik`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dbrscticket.transaction_details: ~7 rows (approximately)
REPLACE INTO `transaction_details` (`id`, `transaction_id`, `nik`, `name`, `phone_number`) VALUES
	(1, 1, 575464, 'Zakia Mayta Proborini', 85230088828),
	(4, 3, 12897283996, 'zia', 9812873917378219),
	(5, 4, 128972839456, 'zia', 9812873917378219),
	(6, 5, 128972839890, 'zia', 9812873917378219),
	(7, 5, 575464897911, 'ziah', 9812873917378219),
	(8, 6, 128972839897, 'zia', 9812873917378219),
	(9, 6, 57546489794, 'ziah', 9812873917378219);

-- Dumping structure for table dbrscticket.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table dbrscticket.users: ~1 rows (approximately)
REPLACE INTO `users` (`id`, `email`, `PASSWORD`, `created_at`) VALUES
	(1, 'admin@gmail.com', '$2y$12$Mf.uyuFdiZWuI64g6nHl3emoJ/ED/GMlj1yBMaQSfIU6EQhWoU72a', '2025-07-21 14:32:41');

-- Dumping structure for view dbrscticket.admin_transaction_view
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `admin_transaction_view`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `admin_transaction_view` AS select `t`.`id` AS `transaction_id`,`t`.`email` AS `email`,`t`.`payment_status` AS `payment_status`,`t`.`checkout_time` AS `checkout_time`,`t`.`paid_time` AS `paid_time`,`t`.`qris_invoice_id` AS `qris_invoice_id`,`t`.`qris_url` AS `qris_url`,`td`.`nik` AS `nik`,`td`.`name` AS `name`,`td`.`phone_number` AS `phone_number` from (`transactions` `t` join `transaction_details` `td` on((`t`.`id` = `td`.`transaction_id`))) order by `t`.`checkout_time` desc;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
