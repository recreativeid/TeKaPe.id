-- =======================================================
-- Database SQL Dump for TeKaPe.id (MySQL / MariaDB XAMPP)
-- Database Name: tekape_db
-- =======================================================

CREATE DATABASE IF NOT EXISTS `tekape_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tekape_db`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `subscriptions`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `attendances`;
DROP TABLE IF EXISTS `schedules`;
DROP TABLE IF EXISTS `tryout_answers`;
DROP TABLE IF EXISTS `tryout_sessions`;
DROP TABLE IF EXISTS `scoring_settings`;
DROP TABLE IF EXISTS `question_options`;
DROP TABLE IF EXISTS `questions`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `packages`;
DROP TABLE IF EXISTS `students_meta`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'murid',
  `phone_whatsapp` VARCHAR(30) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `students_meta` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `is_premium` TINYINT(1) DEFAULT 0,
  `premium_start` DATE NULL,
  `premium_expiry` DATE NULL,
  `attendance_rate` INT DEFAULT 92
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `packages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NULL,
  `type` VARCHAR(20) DEFAULT 'free',
  `price` INT DEFAULT 0,
  `duration_days` INT DEFAULT 30,
  `status` VARCHAR(20) DEFAULT 'active',
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `package_id` INT DEFAULT 0,
  `code` VARCHAR(50) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `max_score` INT DEFAULT 100,
  `scoring_rule` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `questions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `package_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `question_number` INT DEFAULT 1,
  `type` VARCHAR(30) DEFAULT 'pilihan_ganda',
  `narrative` TEXT NOT NULL,
  `image_url` VARCHAR(255) NULL,
  `expected_answer` TEXT NULL,
  `discussion` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `question_options` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `question_id` INT NOT NULL,
  `option_label` VARCHAR(5) NOT NULL,
  `option_text` TEXT NOT NULL,
  `score` DECIMAL(6,2) DEFAULT 0.00,
  `is_correct` TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `scoring_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `package_id` INT NOT NULL,
  `formula_type` VARCHAR(50) DEFAULT 'average_category',
  `twk_enabled` TINYINT(1) DEFAULT 1,
  `tiu_enabled` TINYINT(1) DEFAULT 1,
  `tkp_enabled` TINYINT(1) DEFAULT 1,
  `twk_score_rule` VARCHAR(100) DEFAULT '5 per benar',
  `tiu_score_rule` VARCHAR(100) DEFAULT '5 per benar',
  `tkp_score_rule` VARCHAR(100) DEFAULT 'Skala 1-5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tryout_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `package_id` INT NOT NULL,
  `status` VARCHAR(30) DEFAULT 'completed',
  `start_time` DATETIME NULL,
  `end_time` DATETIME NULL,
  `duration_seconds` INT DEFAULT 0,
  `final_score` DECIMAL(6,2) DEFAULT 0.00,
  `twk_score` DECIMAL(6,2) DEFAULT 0.00,
  `tiu_score` DECIMAL(6,2) DEFAULT 0.00,
  `tkp_score` DECIMAL(6,2) DEFAULT 0.00,
  `correct_count` INT DEFAULT 0,
  `incorrect_count` INT DEFAULT 0,
  `is_manual_edited` TINYINT(1) DEFAULT 0,
  `edited_by` VARCHAR(100) NULL,
  `edited_at` DATETIME NULL,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tryout_answers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_id` INT NOT NULL,
  `question_id` INT NOT NULL,
  `user_answer` TEXT NULL,
  `is_correct` TINYINT(1) DEFAULT 0,
  `score_earned` DECIMAL(6,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `schedules` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject` VARCHAR(50) NOT NULL,
  `day` VARCHAR(20) NOT NULL,
  `date` DATE NULL,
  `start_time` VARCHAR(10) NOT NULL,
  `end_time` VARCHAR(10) NOT NULL,
  `platform` VARCHAR(50) NOT NULL,
  `meeting_link` VARCHAR(255) NULL,
  `status` VARCHAR(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `attendances` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `schedule_id` INT NULL,
  `subject` VARCHAR(50) NOT NULL,
  `date` DATE NOT NULL,
  `status` VARCHAR(30) DEFAULT 'hadir',
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `subscriptions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `order_id` VARCHAR(100) NOT NULL,
  `amount` INT DEFAULT 149000,
  `payment_method` VARCHAR(100) DEFAULT 'QRIS / E-Wallet',
  `status` VARCHAR(30) DEFAULT 'paid',
  `created_at` DATETIME NULL,
  `expired_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Initial Seed Data
INSERT INTO `users` (`id`, `name`, `username`, `password_hash`, `role`, `phone_whatsapp`, `created_at`, `updated_at`) VALUES
(1, 'Admin TeKaPe', 'admin', '$2y$10$425Xl8jQ9f8.r6H.45FhkeTfLdM3g9oN1m131x2Z7LgZzKk4N9S2G', 'admin', '6281234567890', NOW(), NOW()),
(2, 'Tentor Bima Satria, M.Pd.', 'tentor', '$2y$10$425Xl8jQ9f8.r6H.45FhkeTfLdM3g9oN1m131x2Z7LgZzKk4N9S2G', 'tentor', '6289876543210', NOW(), NOW()),
(3, 'Simon Petrus', 'simon', '$2y$10$425Xl8jQ9f8.r6H.45FhkeTfLdM3g9oN1m131x2Z7LgZzKk4N9S2G', 'murid', '6281311223344', NOW(), NOW()),
(4, 'Budi Santoso', 'budi', '$2y$10$425Xl8jQ9f8.r6H.45FhkeTfLdM3g9oN1m131x2Z7LgZzKk4N9S2G', 'murid', '6281555667788', NOW(), NOW()),
(5, 'Siti Rahma', 'siti', '$2y$10$425Xl8jQ9f8.r6H.45FhkeTfLdM3g9oN1m131x2Z7LgZzKk4N9S2G', 'murid', '6281999887766', NOW(), NOW());

INSERT INTO `students_meta` (`user_id`, `is_premium`, `premium_start`, `premium_expiry`, `attendance_rate`) VALUES
(3, 1, DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY), 92),
(4, 0, NULL, NULL, 84),
(5, 1, DATE_SUB(CURDATE(), INTERVAL 15 DAY), DATE_ADD(CURDATE(), INTERVAL 15 DAY), 96);

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('website_name', 'TeKaPe.id'),
('website_subtitle', 'Tempa Karakteristik & Pengetahuan'),
('admin_whatsapp', '6281234567890'),
('tentor_whatsapp', '6289876543210'),
('tentor_name', 'Tentor Bima Satria, M.Pd.'),
('secondary_password_hash', '$2y$10$425Xl8jQ9f8.r6H.45FhkeTfLdM3g9oN1m131x2Z7LgZzKk4N9S2G'),
('premium_price', '149000'),
('premium_duration_days', '30'),
('payment_gateway_name', 'Midtrans / QRIS Otomatis'),
('payment_gateway_status', 'active');

SET FOREIGN_KEY_CHECKS = 1;
