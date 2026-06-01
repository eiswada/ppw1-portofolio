-- ============================================
-- DATABASE: todo_db
-- Import file ini ke phpMyAdmin
-- ============================================

CREATE DATABASE IF NOT EXISTS `todo_db` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `todo_db`;

-- Tabel todos
CREATE TABLE IF NOT EXISTS `todos` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `task`        VARCHAR(255) NOT NULL,
  `status`      ENUM('pending','completed') NOT NULL DEFAULT 'pending',
  `due_date`    DATE         DEFAULT NULL,
  `priority`    ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data contoh
INSERT INTO `todos` (`task`, `status`, `due_date`, `priority`) VALUES
('Buy groceries for next week',  'completed', '2024-06-28', 'medium'),
('Renew car insurance',          'pending',   '2024-06-28', 'high'),
('Sign up for online course',    'pending',    NULL,         'low');
