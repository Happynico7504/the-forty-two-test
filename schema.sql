-- Sinn-des-Lebens vs. Weltproblem-Score
-- Datenbankschema

CREATE DATABASE IF NOT EXISTS weltproblem_score
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE weltproblem_score;

CREATE TABLE IF NOT EXISTS entries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    participants INT UNSIGNED NOT NULL,
    problems INT UNSIGNED NOT NULL,
    nice_things INT UNSIGNED NOT NULL,
    score DECIMAL(12,4) NOT NULL,
    meaning_wins TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;
