-- ============================================================
--  Migration: Add locations table
--  Run this in phpMyAdmin → inventory_db → SQL tab
--  Safe to run on existing installs — uses IF NOT EXISTS
-- ============================================================

CREATE TABLE IF NOT EXISTS locations (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cabinet    VARCHAR(80) NOT NULL,
    shelf      VARCHAR(80) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_location (cabinet, shelf)
) ENGINE=InnoDB;

-- Seed from existing device data — skip duplicates
INSERT IGNORE INTO locations (cabinet, shelf) VALUES
('Cabinet A', 'Shelf 1'),
('Cabinet A', 'Shelf 2'),
('Cabinet A', 'Shelf 3'),
('Cabinet B', 'Shelf 1'),
('Cabinet B', 'Shelf 2'),
('Cabinet C', 'Shelf 1'),
('Cabinet C', 'Shelf 2'),
('Cabinet C', 'Shelf 3'),
('Cabinet 1', 'CBMS 1A'),
('Cabinet 1', 'CBMS 1B'),
('Cabinet 1', 'CBMS 1C'),
('Cabinet 1', 'CBMS 1D'),
('Cabinet 2', 'CBMS 2A'),
('Cabinet 2', 'CBMS 2B');
