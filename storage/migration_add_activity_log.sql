-- Migration: add activity_log table
-- Run once in phpMyAdmin → inventory_db → SQL tab
-- Safe to run on existing installs (CREATE TABLE IF NOT EXISTS)

CREATE TABLE IF NOT EXISTS `activity_log` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED        NULL,
    `user_name`   VARCHAR(120)    NOT NULL DEFAULT '',
    `user_role`   VARCHAR(20)     NOT NULL DEFAULT '',
    `action`      VARCHAR(60)     NOT NULL,
    `description` TEXT            NOT NULL,
    `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_created` (`created_at`),
    KEY `idx_user`    (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
