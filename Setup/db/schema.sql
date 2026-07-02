-- Great Endured Technology — Database Schema Migration
-- Governed by RBP (AGENTS.md)

CREATE TABLE IF NOT EXISTS `leads` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `service` VARCHAR(100) DEFAULT 'General Inquiry',
    `message` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
