-- ============================================================
--  IT Equipment Inventory & Borrowing System
--  MySQL Schema + Seed Data
--  Import via: phpMyAdmin → inventory_db → Import → this file
-- ============================================================

CREATE DATABASE IF NOT EXISTS inventory_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventory_db;

-- ── Tables ──────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS employees (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    department  VARCHAR(100),
    qr_code     VARCHAR(50)  NOT NULL UNIQUE,
    role        ENUM('admin','it_staff','borrower') NOT NULL DEFAULT 'borrower',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS devices (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    type        VARCHAR(80)  NOT NULL,
    asset_tag   VARCHAR(50)  NOT NULL UNIQUE,
    qr_code     VARCHAR(50)  NOT NULL UNIQUE,
    status      ENUM('available','borrowed','out_of_service') NOT NULL DEFAULT 'available',
    cabinet     VARCHAR(80),
    shelf       VARCHAR(80),
    notes       TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS transactions (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    device_id       INT UNSIGNED NOT NULL,
    borrower_id     INT UNSIGNED NOT NULL,
    facilitated_by  INT UNSIGNED NULL,
    returned_by     INT UNSIGNED NULL,
    borrowed_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    returned_at     DATETIME NULL,
    notes           TEXT,
    FOREIGN KEY (device_id)      REFERENCES devices(id)   ON DELETE RESTRICT,
    FOREIGN KEY (borrower_id)    REFERENCES employees(id) ON DELETE RESTRICT,
    FOREIGN KEY (facilitated_by) REFERENCES employees(id) ON DELETE SET NULL,
    FOREIGN KEY (returned_by)    REFERENCES employees(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reconciliations (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    device_id    INT UNSIGNED NOT NULL,
    performed_by INT UNSIGNED NOT NULL,
    old_status   VARCHAR(30),
    new_status   VARCHAR(30),
    reason       TEXT NOT NULL,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (device_id)    REFERENCES devices(id)   ON DELETE RESTRICT,
    FOREIGN KEY (performed_by) REFERENCES employees(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ── Seed: Employees ─────────────────────────────────────────

INSERT INTO employees (name, department, qr_code, role) VALUES
('James Allen Josue',    'IT Department', 'EMP-001', 'admin'),
('Frenz Darren Medallon','IT Department', 'EMP-002', 'it_staff'),
('Maria Santos',         'Finance',       'EMP-003', 'borrower'),
('Carlos Reyes',         'HR',            'EMP-004', 'borrower'),
('Ana Lim',              'Operations',    'EMP-005', 'borrower'),
('Rico Dela Cruz',       'Sales',         'EMP-006', 'borrower'),
('Patricia Gomez',       'Marketing',     'EMP-007', 'borrower'),
('Ben Aquino',           'IT Department', 'EMP-008', 'it_staff');

-- ── Seed: Devices ───────────────────────────────────────────

INSERT INTO devices (name, type, asset_tag, qr_code, status, cabinet, shelf) VALUES
('ThinkPad X1 Carbon',    'Laptop',   'DEV-L001', 'QR-DEV-L001', 'borrowed',  'Cabinet A', 'Shelf 1'),
('ThinkPad E15',          'Laptop',   'DEV-L002', 'QR-DEV-L002', 'available', 'Cabinet A', 'Shelf 1'),
('Dell Latitude 5520',    'Laptop',   'DEV-L003', 'QR-DEV-L003', 'available', 'Cabinet A', 'Shelf 2'),
('HP EliteBook 840',      'Laptop',   'DEV-L004', 'QR-DEV-L004', 'available', 'Cabinet A', 'Shelf 2'),
('Acer Aspire 5',         'Laptop',   'DEV-L005', 'QR-DEV-L005', 'available', 'Cabinet A', 'Shelf 3'),
('Dell U2722D 27"',       'Monitor',  'DEV-M001', 'QR-DEV-M001', 'borrowed',  'Cabinet B', 'Shelf 1'),
('LG 24MK430H',           'Monitor',  'DEV-M002', 'QR-DEV-M002', 'available', 'Cabinet B', 'Shelf 1'),
('Samsung 27" Curved',    'Monitor',  'DEV-M003', 'QR-DEV-M003', 'available', 'Cabinet B', 'Shelf 2'),
('Logitech MX Keys',      'Keyboard', 'DEV-K001', 'QR-DEV-K001', 'available', 'Cabinet C', 'Shelf 1'),
('Apple Magic Keyboard',  'Keyboard', 'DEV-K002', 'QR-DEV-K002', 'available', 'Cabinet C', 'Shelf 1'),
('Logitech MX Master 3',  'Mouse',    'DEV-MS001','QR-DEV-MS001','available', 'Cabinet C', 'Shelf 2'),
('Apple Magic Mouse',     'Mouse',    'DEV-MS002','QR-DEV-MS002','available', 'Cabinet C', 'Shelf 2'),
('Jabra Evolve2 75',      'Headset',  'DEV-H001', 'QR-DEV-H001', 'available', 'Cabinet C', 'Shelf 3'),
('Sony WH-1000XM5',       'Headset',  'DEV-H002', 'QR-DEV-H002', 'out_of_service', 'Cabinet C', 'Shelf 3'),
('Logitech C920 Webcam',  'Webcam',   'DEV-W001', 'QR-DEV-W001', 'available', 'Cabinet D', 'Shelf 1'),
('Anker USB-C Hub',       'Hub',      'DEV-HB001','QR-DEV-HB001','available', 'Cabinet D', 'Shelf 2'),
('Belkin HDMI Cable 2m',  'Cable',    'DEV-C001', 'QR-DEV-C001', 'available', 'Cabinet D', 'Shelf 3'),
('TP-Link TL-WN823N',     'USB WiFi', 'DEV-U001', 'QR-DEV-U001', 'available', 'Cabinet D', 'Shelf 3');

-- ── Seed: Transactions ──────────────────────────────────────

-- Active borrow: Maria borrowed ThinkPad X1 (facilitated by Frenz)
INSERT INTO transactions (device_id, borrower_id, facilitated_by, borrowed_at)
SELECT d.id, e1.id, e2.id, NOW() - INTERVAL 3 HOUR
FROM devices d, employees e1, employees e2
WHERE d.asset_tag='DEV-L001' AND e1.qr_code='EMP-003' AND e2.qr_code='EMP-002';

-- Active borrow: Carlos borrowed Dell Monitor (self-service)
INSERT INTO transactions (device_id, borrower_id, borrowed_at)
SELECT d.id, e.id, NOW() - INTERVAL 1 HOUR
FROM devices d, employees e
WHERE d.asset_tag='DEV-M001' AND e.qr_code='EMP-004';

-- Past completed transactions
INSERT INTO transactions (device_id, borrower_id, borrowed_at, returned_at) VALUES
(3, 5, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 1 DAY),
(4, 6, NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 2 DAY),
(7, 3, NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 3 DAY),
(9, 7, NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 4 DAY),
(5, 4, NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 5 DAY),
(2, 6, NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 6 DAY);

-- Proxy return example: Ana returned on behalf of Rico
INSERT INTO transactions (device_id, borrower_id, returned_by, borrowed_at, returned_at)
SELECT d.id, e1.id, e2.id, NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 7 DAY
FROM devices d, employees e1, employees e2
WHERE d.asset_tag='DEV-L003' AND e1.qr_code='EMP-006' AND e2.qr_code='EMP-005';

-- ── Seed: Reconciliations ────────────────────────────────────

INSERT INTO reconciliations (device_id, performed_by, old_status, new_status, reason)
SELECT d.id, e.id, 'borrowed', 'out_of_service', 'Device found damaged during physical inventory. Screen cracked.'
FROM devices d, employees e
WHERE d.asset_tag='DEV-H002' AND e.qr_code='EMP-001';
