-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2026 at 08:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `type` varchar(80) NOT NULL,
  `asset_tag` varchar(50) NOT NULL,
  `qr_code` varchar(50) NOT NULL,
  `status` enum('available','borrowed','out_of_service') NOT NULL DEFAULT 'available',
  `cabinet` varchar(80) DEFAULT NULL,
  `shelf` varchar(80) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `name`, `type`, `asset_tag`, `qr_code`, `status`, `cabinet`, `shelf`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Acer TravelMate P', 'Laptop', 'LAP-0000-21-108-0133', 'QR-DEV-L001', 'available', 'Cabinet A', 'Shelf 1', '', '2026-05-20 08:33:11', '2026-05-20 09:07:12'),
(2, 'Acer TravelMate P', 'Laptop', 'ICS-LAP-1700-24-255-00007', 'QR-DEV-L002', 'available', 'Cabinet A', 'Shelf 1', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(3, 'Acer TravelMate P', 'Laptop', 'ICS-LAP-1700-24-255-00008', 'QR-DEV-L003', 'available', 'Cabinet A', 'Shelf 2', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(4, 'Acer TravelMate P', 'Laptop', 'LAP-0000-22-039-0206', 'QR-DEV-L004', 'available', 'Cabinet A', 'Shelf 2', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(5, 'Acer TravelMate P2', 'Laptop', 'LAP-0000-22-039-0205', 'QR-DEV-L005', 'available', 'Cabinet A', 'Shelf 3', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(6, 'Acer Predator Helios 300', 'Laptop', 'LAP-1700-23-252-00003', 'QR-DEV-L006', 'available', 'Cabinet B', 'Shelf 1', '', '2026-05-20 08:33:11', '2026-05-20 09:15:39'),
(7, 'Acer TravelMate P2', 'Laptop', 'LAP-0000-21-108-0131', 'QR-DEV-L007', 'available', 'Cabinet B', 'Shelf 1', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(8, 'Acer TravelMate P2', 'Laptop', 'LAP-0000-22-029', 'QR-DEV-L008', 'available', 'Cabinet B', 'Shelf 2', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(9, 'Acer TravelMate P2', 'Laptop', 'LAP-0000-21-108-0132', 'QR-DEV-L009', 'available', 'Cabinet C', 'Shelf 1', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(10, 'Acer TravelMate P', 'Laptop', 'ICS-LAP-1700-24-255-00009', 'QR-DEV-L010', 'available', 'Cabinet C', 'Shelf 1', '', '2026-05-20 08:33:11', '2026-05-20 09:12:01'),
(11, 'Lenovo ThinkPad L14 Gen1', 'Laptop', 'LAP-0000-21-029-0232', 'QR-DEV-L011', 'available', 'Cabinet C', 'Shelf 2', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(12, 'Acer Nitro 5', 'Laptop', 'LAP-1700-23-046-00002', 'QR-DEV-L012', 'available', 'Cabinet C', 'Shelf 2', '', '2026-05-20 08:33:11', '2026-05-20 09:15:41'),
(13, 'Acer TravelMate P', 'Laptop', 'LAP-0000-21-108-0134', 'QR-DEV-L013', 'available', 'Cabinet C', 'Shelf 3', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(14, 'Samsung A9+ CBMS-1', 'Tablet', 'ICS-DIT-0000-24-053-18369', 'QR-DEV-T001', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(15, 'Samsung A9+ CBMS-2', 'Tablet', 'ICS-DIT-0000-24-053-18370', 'QR-DEV-T002', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(16, 'Samsung A9+ CBMS-3', 'Tablet', 'ICS-DIT-0000-24-053-18371', 'QR-DEV-T003', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(17, 'Samsung A9+ CBMS-4', 'Tablet', 'ICS-DIT-0000-24-053-18372', 'QR-DEV-T004', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(18, 'Samsung A9+ CBMS-5', 'Tablet', 'ICS-DIT-0000-24-053-18373', 'QR-DEV-T005', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(19, 'Samsung A9+ CBMS-6', 'Tablet', 'ICS-DIT-0000-24-053-18374', 'QR-DEV-T006', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(20, 'Samsung A9+ CBMS-7', 'Tablet', 'ICS-DIT-0000-24-053-18375', 'QR-DEV-T007', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(21, 'Samsung A9+ CBMS-8', 'Tablet', 'ICS-DIT-0000-24-053-18376', 'QR-DEV-T008', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(22, 'Samsung A9+ CBMS-9', 'Tablet', 'ICS-DIT-0000-24-053-18377', 'QR-DEV-T009', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(23, 'Samsung A9+ CBMS-10', 'Tablet', 'ICS-DIT-0000-24-053-18378', 'QR-DEV-T010', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(24, 'Samsung A9+ CBMS-11', 'Tablet', 'ICS-DIT-0000-24-053-18379', 'QR-DEV-T011', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(25, 'Samsung A9+ CBMS-12', 'Tablet', 'ICS-DIT-0000-24-053-18380', 'QR-DEV-T012', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(26, 'Samsung A9+ CBMS-13', 'Tablet', 'ICS-DIT-0000-24-053-18381', 'QR-DEV-T013', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(27, 'Samsung A9+ CBMS-14', 'Tablet', 'ICS-DIT-0000-24-053-18382', 'QR-DEV-T014', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(28, 'Samsung A9+ CBMS-15', 'Tablet', 'ICS-DIT-0000-24-053-18383', 'QR-DEV-T015', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(29, 'Samsung A9+ CBMS-16', 'Tablet', 'ICS-DIT-0000-24-053-18384', 'QR-DEV-T016', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(30, 'Samsung A9+ CBMS-17', 'Tablet', 'ICS-DIT-0000-24-053-18385', 'QR-DEV-T017', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(31, 'Samsung A9+ CBMS-18', 'Tablet', 'ICS-DIT-0000-24-053-18386', 'QR-DEV-T018', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(32, 'Samsung A9+ CBMS-19', 'Tablet', 'ICS-DIT-0000-24-053-18387', 'QR-DEV-T019', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(33, 'Samsung A9+ CBMS-20', 'Tablet', 'ICS-DIT-0000-24-053-18388', 'QR-DEV-T020', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(34, 'Samsung A9+ CBMS-21', 'Tablet', 'ICS-DIT-0000-24-053-18389', 'QR-DEV-T021', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(35, 'Samsung A9+ CBMS-22', 'Tablet', 'ICS-DIT-0000-24-053-18390', 'QR-DEV-T022', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(36, 'Samsung A9+ CBMS-23', 'Tablet', 'ICS-DIT-0000-24-053-18391', 'QR-DEV-T023', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(37, 'Samsung A9+ CBMS-24', 'Tablet', 'ICS-DIT-0000-24-053-18392', 'QR-DEV-T024', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(38, 'Samsung A9+ CBMS-25', 'Tablet', 'ICS-DIT-0000-24-053-18393', 'QR-DEV-T025', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(39, 'Samsung A9+ CBMS-26', 'Tablet', 'ICS-DIT-0000-24-053-18394', 'QR-DEV-T026', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(40, 'Samsung A9+ CBMS-27', 'Tablet', 'ICS-DIT-0000-24-053-18395', 'QR-DEV-T027', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(41, 'Samsung A9+ CBMS-28', 'Tablet', 'ICS-DIT-0000-24-053-18396', 'QR-DEV-T028', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(42, 'Samsung A9+ CBMS-29', 'Tablet', 'ICS-DIT-0000-24-053-18397', 'QR-DEV-T029', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(43, 'Samsung A9+ CBMS-30', 'Tablet', 'ICS-DIT-0000-24-053-18398', 'QR-DEV-T030', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(44, 'Samsung A9+ CBMS-31', 'Tablet', 'ICS-DIT-0000-24-053-18399', 'QR-DEV-T031', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(45, 'Samsung A9+ CBMS-32', 'Tablet', 'ICS-DIT-0000-24-053-18400', 'QR-DEV-T032', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(46, 'Samsung A9+ CBMS-33', 'Tablet', 'ICS-DIT-0000-24-053-18401', 'QR-DEV-T033', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(47, 'Samsung A9+ CBMS-34', 'Tablet', 'ICS-DIT-0000-24-053-18402', 'QR-DEV-T034', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(48, 'Samsung A9+ CBMS-35', 'Tablet', 'ICS-DIT-0000-24-053-18403', 'QR-DEV-T035', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(49, 'Samsung A9+ CBMS-36', 'Tablet', 'ICS-DIT-0000-24-053-18404', 'QR-DEV-T036', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(50, 'Samsung A9+ CBMS-37', 'Tablet', 'ICS-DIT-0000-24-053-18405', 'QR-DEV-T037', 'available', 'Cabinet 1', 'CBMS 1A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(51, 'Samsung A9+ CBMS-38', 'Tablet', 'ICS-DIT-0000-24-053-18406', 'QR-DEV-T038', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(52, 'Samsung A9+ CBMS-39', 'Tablet', 'ICS-DIT-0000-24-053-18407', 'QR-DEV-T039', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(53, 'Samsung A9+ CBMS-40', 'Tablet', 'ICS-DIT-0000-24-053-18408', 'QR-DEV-T040', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(54, 'Samsung A9+ CBMS-41', 'Tablet', 'ICS-DIT-0000-24-053-18409', 'QR-DEV-T041', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(55, 'Samsung A9+ CBMS-42', 'Tablet', 'ICS-DIT-0000-24-053-18410', 'QR-DEV-T042', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(56, 'Samsung A9+ CBMS-43', 'Tablet', 'ICS-DIT-0000-24-053-18411', 'QR-DEV-T043', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(57, 'Samsung A9+ CBMS-44', 'Tablet', 'ICS-DIT-0000-24-053-18412', 'QR-DEV-T044', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(58, 'Samsung A9+ CBMS-45', 'Tablet', 'ICS-DIT-0000-24-053-18413', 'QR-DEV-T045', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(59, 'Samsung A9+ CBMS-46', 'Tablet', 'ICS-DIT-0000-24-053-18414', 'QR-DEV-T046', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(60, 'Samsung A9+ CBMS-47', 'Tablet', 'ICS-DIT-0000-24-053-18415', 'QR-DEV-T047', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(61, 'Samsung A9+ CBMS-48', 'Tablet', 'ICS-DIT-0000-24-053-18416', 'QR-DEV-T048', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(62, 'Samsung A9+ CBMS-49', 'Tablet', 'ICS-DIT-0000-24-053-18417', 'QR-DEV-T049', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(63, 'Samsung A9+ CBMS-50', 'Tablet', 'ICS-DIT-0000-24-053-18418', 'QR-DEV-T050', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(64, 'Samsung A9+ CBMS-51', 'Tablet', 'ICS-DIT-0000-24-053-18419', 'QR-DEV-T051', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(65, 'Samsung A9+ CBMS-52', 'Tablet', 'ICS-DIT-0000-24-053-18420', 'QR-DEV-T052', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(66, 'Samsung A9+ CBMS-53', 'Tablet', 'ICS-DIT-0000-24-053-18421', 'QR-DEV-T053', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(67, 'Samsung A9+ CBMS-54', 'Tablet', 'ICS-DIT-0000-24-053-18422', 'QR-DEV-T054', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(68, 'Samsung A9+ CBMS-55', 'Tablet', 'ICS-DIT-0000-24-053-18423', 'QR-DEV-T055', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(69, 'Samsung A9+ CBMS-56', 'Tablet', 'ICS-DIT-0000-24-053-18424', 'QR-DEV-T056', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(70, 'Samsung A9+ CBMS-57', 'Tablet', 'ICS-DIT-0000-24-053-18425', 'QR-DEV-T057', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(71, 'Samsung A9+ CBMS-58', 'Tablet', 'ICS-DIT-0000-24-053-18426', 'QR-DEV-T058', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(72, 'Samsung A9+ CBMS-59', 'Tablet', 'ICS-DIT-0000-24-053-18427', 'QR-DEV-T059', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(73, 'Samsung A9+ CBMS-60', 'Tablet', 'ICS-DIT-0000-24-053-18428', 'QR-DEV-T060', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(74, 'Samsung A9+ CBMS-61', 'Tablet', 'ICS-DIT-0000-24-053-18429', 'QR-DEV-T061', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(75, 'Samsung A9+ CBMS-62', 'Tablet', 'ICS-DIT-0000-24-053-18430', 'QR-DEV-T062', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(76, 'Samsung A9+ CBMS-63', 'Tablet', 'ICS-DIT-0000-24-053-18431', 'QR-DEV-T063', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(77, 'Samsung A9+ CBMS-64', 'Tablet', 'ICS-DIT-0000-24-053-18432', 'QR-DEV-T064', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(78, 'Samsung A9+ CBMS-65', 'Tablet', 'ICS-DIT-0000-24-053-18433', 'QR-DEV-T065', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(79, 'Samsung A9+ CBMS-66', 'Tablet', 'ICS-DIT-0000-24-053-18434', 'QR-DEV-T066', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(80, 'Samsung A9+ CBMS-67', 'Tablet', 'ICS-DIT-0000-24-053-18435', 'QR-DEV-T067', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(81, 'Samsung A9+ CBMS-68', 'Tablet', 'ICS-DIT-0000-24-053-18436', 'QR-DEV-T068', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(82, 'Samsung A9+ CBMS-69', 'Tablet', 'ICS-DIT-0000-24-053-18437', 'QR-DEV-T069', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(83, 'Samsung A9+ CBMS-70', 'Tablet', 'ICS-DIT-0000-24-053-18438', 'QR-DEV-T070', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(84, 'Samsung A9+ CBMS-71', 'Tablet', 'ICS-DIT-0000-24-053-18439', 'QR-DEV-T071', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(85, 'Samsung A9+ CBMS-72', 'Tablet', 'ICS-DIT-0000-24-053-18440', 'QR-DEV-T072', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(86, 'Samsung A9+ CBMS-73', 'Tablet', 'ICS-DIT-0000-24-053-18441', 'QR-DEV-T073', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(87, 'Samsung A9+ CBMS-74', 'Tablet', 'ICS-DIT-0000-24-053-18442', 'QR-DEV-T074', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(88, 'Samsung A9+ CBMS-75', 'Tablet', 'ICS-DIT-0000-24-053-18443', 'QR-DEV-T075', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(89, 'Samsung A9+ CBMS-76', 'Tablet', 'ICS-DIT-0000-24-053-18444', 'QR-DEV-T076', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(90, 'Samsung A9+ CBMS-77', 'Tablet', 'ICS-DIT-0000-24-053-18445', 'QR-DEV-T077', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(91, 'Samsung A9+ CBMS-78', 'Tablet', 'ICS-DIT-0000-24-053-18446', 'QR-DEV-T078', 'available', 'Cabinet 1', 'CBMS 1B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(92, 'Samsung A9+ CBMS-79', 'Tablet', 'ICS-DIT-0000-24-053-18447', 'QR-DEV-T079', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(93, 'Samsung A9+ CBMS-80', 'Tablet', 'ICS-DIT-0000-24-053-18448', 'QR-DEV-T080', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(94, 'Samsung A9+ CBMS-81', 'Tablet', 'ICS-DIT-0000-24-053-18449', 'QR-DEV-T081', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(95, 'Samsung A9+ CBMS-82', 'Tablet', 'ICS-DIT-0000-24-053-18450', 'QR-DEV-T082', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(96, 'Samsung A9+ CBMS-83', 'Tablet', 'ICS-DIT-0000-24-053-18451', 'QR-DEV-T083', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(97, 'Samsung A9+ CBMS-84', 'Tablet', 'ICS-DIT-0000-24-053-18452', 'QR-DEV-T084', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(98, 'Samsung A9+ CBMS-85', 'Tablet', 'ICS-DIT-0000-24-053-18453', 'QR-DEV-T085', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(99, 'Samsung A9+ CBMS-86', 'Tablet', 'ICS-DIT-0000-24-053-18454', 'QR-DEV-T086', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(100, 'Samsung A9+ CBMS-87', 'Tablet', 'ICS-DIT-0000-24-053-18455', 'QR-DEV-T087', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(101, 'Samsung A9+ CBMS-88', 'Tablet', 'ICS-DIT-0000-24-053-18456', 'QR-DEV-T088', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(102, 'Samsung A9+ CBMS-89', 'Tablet', 'ICS-DIT-0000-24-053-18457', 'QR-DEV-T089', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(103, 'Samsung A9+ CBMS-90', 'Tablet', 'ICS-DIT-0000-24-053-18458', 'QR-DEV-T090', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(104, 'Samsung A9+ CBMS-91', 'Tablet', 'ICS-DIT-0000-24-053-18459', 'QR-DEV-T091', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(105, 'Samsung A9+ CBMS-92', 'Tablet', 'ICS-DIT-0000-24-053-18460', 'QR-DEV-T092', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(106, 'Samsung A9+ CBMS-93', 'Tablet', 'ICS-DIT-0000-24-053-18461', 'QR-DEV-T093', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(107, 'Samsung A9+ CBMS-94', 'Tablet', 'ICS-DIT-0000-24-053-18462', 'QR-DEV-T094', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(108, 'Samsung A9+ CBMS-95', 'Tablet', 'ICS-DIT-0000-24-053-18463', 'QR-DEV-T095', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(109, 'Samsung A9+ CBMS-96', 'Tablet', 'ICS-DIT-0000-24-053-18464', 'QR-DEV-T096', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(110, 'Samsung A9+ CBMS-97', 'Tablet', 'ICS-DIT-0000-24-053-18465', 'QR-DEV-T097', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(111, 'Samsung A9+ CBMS-98', 'Tablet', 'ICS-DIT-0000-24-053-18466', 'QR-DEV-T098', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(112, 'Samsung A9+ CBMS-99', 'Tablet', 'ICS-DIT-0000-24-053-18467', 'QR-DEV-T099', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(113, 'Samsung A9+ CBMS-100', 'Tablet', 'ICS-DIT-0000-24-053-18468', 'QR-DEV-T100', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(114, 'Samsung A9+ CBMS-101', 'Tablet', 'ICS-DIT-0000-24-053-18469', 'QR-DEV-T101', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(115, 'Samsung A9+ CBMS-102', 'Tablet', 'ICS-DIT-0000-24-053-18470', 'QR-DEV-T102', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(116, 'Samsung A9+ CBMS-103', 'Tablet', 'ICS-DIT-0000-24-053-18471', 'QR-DEV-T103', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(117, 'Samsung A9+ CBMS-104', 'Tablet', 'ICS-DIT-0000-24-053-18472', 'QR-DEV-T104', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(118, 'Samsung A9+ CBMS-105', 'Tablet', 'ICS-DIT-0000-24-053-18473', 'QR-DEV-T105', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(119, 'Samsung A9+ CBMS-106', 'Tablet', 'ICS-DIT-0000-24-053-18474', 'QR-DEV-T106', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(120, 'Samsung A9+ CBMS-107', 'Tablet', 'ICS-DIT-0000-24-053-18475', 'QR-DEV-T107', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(121, 'Samsung A9+ CBMS-108', 'Tablet', 'ICS-DIT-0000-24-053-18476', 'QR-DEV-T108', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(122, 'Samsung A9+ CBMS-109', 'Tablet', 'ICS-DIT-0000-24-053-18477', 'QR-DEV-T109', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(123, 'Samsung A9+ CBMS-110', 'Tablet', 'ICS-DIT-0000-24-053-18478', 'QR-DEV-T110', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(124, 'Samsung A9+ CBMS-111', 'Tablet', 'ICS-DIT-0000-24-053-18479', 'QR-DEV-T111', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(125, 'Samsung A9+ CBMS-112', 'Tablet', 'ICS-DIT-0000-24-053-18480', 'QR-DEV-T112', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(126, 'Samsung A9+ CBMS-113', 'Tablet', 'ICS-DIT-0000-24-053-18481', 'QR-DEV-T113', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(127, 'Samsung A9+ CBMS-114', 'Tablet', 'ICS-DIT-0000-24-053-18482', 'QR-DEV-T114', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(128, 'Samsung A9+ CBMS-115', 'Tablet', 'ICS-DIT-0000-24-053-18483', 'QR-DEV-T115', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(129, 'Samsung A9+ CBMS-116', 'Tablet', 'ICS-DIT-0000-24-053-18484', 'QR-DEV-T116', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(130, 'Samsung A9+ CBMS-117', 'Tablet', 'ICS-DIT-0000-24-053-18485', 'QR-DEV-T117', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(131, 'Samsung A9+ CBMS-118', 'Tablet', 'ICS-DIT-0000-24-053-18486', 'QR-DEV-T118', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(132, 'Samsung A9+ CBMS-119', 'Tablet', 'ICS-DIT-0000-24-053-18487', 'QR-DEV-T119', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(133, 'Samsung A9+ CBMS-120', 'Tablet', 'ICS-DIT-0000-24-053-18488', 'QR-DEV-T120', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(134, 'Samsung A9+ CBMS-121', 'Tablet', 'ICS-DIT-0000-24-053-18489', 'QR-DEV-T121', 'available', 'Cabinet 1', 'CBMS 1C', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(135, 'Samsung A9+ CBMS-122', 'Tablet', 'ICS-DIT-0000-24-053-18490', 'QR-DEV-T122', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(136, 'Samsung A9+ CBMS-123', 'Tablet', 'ICS-DIT-0000-24-053-18491', 'QR-DEV-T123', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(137, 'Samsung A9+ CBMS-124', 'Tablet', 'ICS-DIT-0000-24-053-18492', 'QR-DEV-T124', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(138, 'Samsung A9+ CBMS-125', 'Tablet', 'ICS-DIT-0000-24-053-18493', 'QR-DEV-T125', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(139, 'Samsung A9+ CBMS-126', 'Tablet', 'ICS-DIT-0000-24-053-18494', 'QR-DEV-T126', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(140, 'Samsung A9+ CBMS-127', 'Tablet', 'ICS-DIT-0000-24-053-18495', 'QR-DEV-T127', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(141, 'Samsung A9+ CBMS-128', 'Tablet', 'ICS-DIT-0000-24-053-18496', 'QR-DEV-T128', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(142, 'Samsung A9+ CBMS-129', 'Tablet', 'ICS-DIT-0000-24-053-18497', 'QR-DEV-T129', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(143, 'Samsung A9+ CBMS-130', 'Tablet', 'ICS-DIT-0000-24-053-18498', 'QR-DEV-T130', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(144, 'Samsung A9+ CBMS-131', 'Tablet', 'ICS-DIT-0000-24-053-18499', 'QR-DEV-T131', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(145, 'Samsung A9+ CBMS-132', 'Tablet', 'ICS-DIT-0000-24-053-18500', 'QR-DEV-T132', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(146, 'Samsung A9+ CBMS-133', 'Tablet', 'ICS-DIT-0000-24-053-18501', 'QR-DEV-T133', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(147, 'Samsung A9+ CBMS-134', 'Tablet', 'ICS-DIT-0000-24-053-18502', 'QR-DEV-T134', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(148, 'Samsung A9+ CBMS-135', 'Tablet', 'ICS-DIT-0000-24-053-18503', 'QR-DEV-T135', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(149, 'Samsung A9+ CBMS-136', 'Tablet', 'ICS-DIT-0000-24-053-18504', 'QR-DEV-T136', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(150, 'Samsung A9+ CBMS-137', 'Tablet', 'ICS-DIT-0000-24-053-18505', 'QR-DEV-T137', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(151, 'Samsung A9+ CBMS-138', 'Tablet', 'ICS-DIT-0000-24-053-18506', 'QR-DEV-T138', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(152, 'Samsung A9+ CBMS-139', 'Tablet', 'ICS-DIT-0000-24-053-18507', 'QR-DEV-T139', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(153, 'Samsung A9+ CBMS-140', 'Tablet', 'ICS-DIT-0000-24-053-18508', 'QR-DEV-T140', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(154, 'Samsung A9+ CBMS-141', 'Tablet', 'ICS-DIT-0000-24-053-18509', 'QR-DEV-T141', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(155, 'Samsung A9+ CBMS-142', 'Tablet', 'ICS-DIT-0000-24-053-18510', 'QR-DEV-T142', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(156, 'Samsung A9+ CBMS-143', 'Tablet', 'ICS-DIT-0000-24-053-18511', 'QR-DEV-T143', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(157, 'Samsung A9+ CBMS-144', 'Tablet', 'ICS-DIT-0000-24-053-18512', 'QR-DEV-T144', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(158, 'Samsung A9+ CBMS-145', 'Tablet', 'ICS-DIT-0000-24-053-18513', 'QR-DEV-T145', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(159, 'Samsung A9+ CBMS-146', 'Tablet', 'ICS-DIT-0000-24-053-18514', 'QR-DEV-T146', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(160, 'Samsung A9+ CBMS-147', 'Tablet', 'ICS-DIT-0000-24-053-18515', 'QR-DEV-T147', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(161, 'Samsung A9+ CBMS-148', 'Tablet', 'ICS-DIT-0000-24-053-18516', 'QR-DEV-T148', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(162, 'Samsung A9+ CBMS-149', 'Tablet', 'ICS-DIT-0000-24-053-18517', 'QR-DEV-T149', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(163, 'Samsung A9+ CBMS-150', 'Tablet', 'ICS-DIT-0000-24-053-18518', 'QR-DEV-T150', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(164, 'Samsung A9+ CBMS-151', 'Tablet', 'ICS-DIT-0000-24-053-18519', 'QR-DEV-T151', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(165, 'Samsung A9+ CBMS-152', 'Tablet', 'ICS-DIT-0000-24-053-18520', 'QR-DEV-T152', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(166, 'Samsung A9+ CBMS-153', 'Tablet', 'ICS-DIT-0000-24-053-18521', 'QR-DEV-T153', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(167, 'Samsung A9+ CBMS-154', 'Tablet', 'ICS-DIT-0000-24-053-18522', 'QR-DEV-T154', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(168, 'Samsung A9+ CBMS-155', 'Tablet', 'ICS-DIT-0000-24-053-18523', 'QR-DEV-T155', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(169, 'Samsung A9+ CBMS-156', 'Tablet', 'ICS-DIT-0000-24-053-18524', 'QR-DEV-T156', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(170, 'Samsung A9+ CBMS-157', 'Tablet', 'ICS-DIT-0000-24-053-18525', 'QR-DEV-T157', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(171, 'Samsung A9+ CBMS-158', 'Tablet', 'ICS-DIT-0000-24-053-18526', 'QR-DEV-T158', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(172, 'Samsung A9+ CBMS-159', 'Tablet', 'ICS-DIT-0000-24-053-18527', 'QR-DEV-T159', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(173, 'Samsung A9+ CBMS-160', 'Tablet', 'ICS-DIT-0000-24-053-18528', 'QR-DEV-T160', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(174, 'Samsung A9+ CBMS-161', 'Tablet', 'ICS-DIT-0000-24-053-18529', 'QR-DEV-T161', 'available', 'Cabinet 1', 'CBMS 1D', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(175, 'Samsung A9+ CBMS-162', 'Tablet', 'ICS-DIT-0000-24-053-18530', 'QR-DEV-T162', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(176, 'Samsung A9+ CBMS-163', 'Tablet', 'ICS-DIT-0000-24-053-18531', 'QR-DEV-T163', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(177, 'Samsung A9+ CBMS-164', 'Tablet', 'ICS-DIT-0000-24-053-18532', 'QR-DEV-T164', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(178, 'Samsung A9+ CBMS-165', 'Tablet', 'ICS-DIT-0000-24-053-18533', 'QR-DEV-T165', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(179, 'Samsung A9+ CBMS-166', 'Tablet', 'ICS-DIT-0000-24-053-18534', 'QR-DEV-T166', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(180, 'Samsung A9+ CBMS-167', 'Tablet', 'ICS-DIT-0000-24-053-18535', 'QR-DEV-T167', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(181, 'Samsung A9+ CBMS-168', 'Tablet', 'ICS-DIT-0000-24-053-18536', 'QR-DEV-T168', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(182, 'Samsung A9+ CBMS-169', 'Tablet', 'ICS-DIT-0000-24-053-18537', 'QR-DEV-T169', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(183, 'Samsung A9+ CBMS-170', 'Tablet', 'ICS-DIT-0000-24-053-18538', 'QR-DEV-T170', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(184, 'Samsung A9+ CBMS-171', 'Tablet', 'ICS-DIT-0000-24-053-18539', 'QR-DEV-T171', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(185, 'Samsung A9+ CBMS-172', 'Tablet', 'ICS-DIT-0000-24-053-18540', 'QR-DEV-T172', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(186, 'Samsung A9+ CBMS-173', 'Tablet', 'ICS-DIT-0000-24-053-18541', 'QR-DEV-T173', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(187, 'Samsung A9+ CBMS-174', 'Tablet', 'ICS-DIT-0000-24-053-18542', 'QR-DEV-T174', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(188, 'Samsung A9+ CBMS-175', 'Tablet', 'ICS-DIT-0000-24-053-18543', 'QR-DEV-T175', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(189, 'Samsung A9+ CBMS-176', 'Tablet', 'ICS-DIT-0000-24-053-18544', 'QR-DEV-T176', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(190, 'Samsung A9+ CBMS-177', 'Tablet', 'ICS-DIT-0000-24-053-18545', 'QR-DEV-T177', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(191, 'Samsung A9+ CBMS-178', 'Tablet', 'ICS-DIT-0000-24-053-18546', 'QR-DEV-T178', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(192, 'Samsung A9+ CBMS-179', 'Tablet', 'ICS-DIT-0000-24-053-18547', 'QR-DEV-T179', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(193, 'Samsung A9+ CBMS-180', 'Tablet', 'ICS-DIT-0000-24-053-18548', 'QR-DEV-T180', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(194, 'Samsung A9+ CBMS-181', 'Tablet', 'ICS-DIT-0000-24-053-18549', 'QR-DEV-T181', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(195, 'Samsung A9+ CBMS-182', 'Tablet', 'ICS-DIT-0000-24-053-18550', 'QR-DEV-T182', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(196, 'Samsung A9+ CBMS-183', 'Tablet', 'ICS-DIT-0000-24-053-18551', 'QR-DEV-T183', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(197, 'Samsung A9+ CBMS-184', 'Tablet', 'ICS-DIT-0000-24-053-18552', 'QR-DEV-T184', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(198, 'Samsung A9+ CBMS-185', 'Tablet', 'ICS-DIT-0000-24-053-18553', 'QR-DEV-T185', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(199, 'Samsung A9+ CBMS-186', 'Tablet', 'ICS-DIT-0000-24-053-18554', 'QR-DEV-T186', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(200, 'Samsung A9+ CBMS-187', 'Tablet', 'ICS-DIT-0000-24-053-18555', 'QR-DEV-T187', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(201, 'Samsung A9+ CBMS-188', 'Tablet', 'ICS-DIT-0000-24-053-18556', 'QR-DEV-T188', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(202, 'Samsung A9+ CBMS-189', 'Tablet', 'ICS-DIT-0000-24-053-18557', 'QR-DEV-T189', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(203, 'Samsung A9+ CBMS-190', 'Tablet', 'ICS-DIT-0000-24-053-18558', 'QR-DEV-T190', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(204, 'Samsung A9+ CBMS-191', 'Tablet', 'ICS-DIT-0000-24-053-18559', 'QR-DEV-T191', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(205, 'Samsung A9+ CBMS-192', 'Tablet', 'ICS-DIT-0000-24-053-18560', 'QR-DEV-T192', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(206, 'Samsung A9+ CBMS-193', 'Tablet', 'ICS-DIT-0000-24-053-18561', 'QR-DEV-T193', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(207, 'Samsung A9+ CBMS-194', 'Tablet', 'ICS-DIT-0000-24-053-18562', 'QR-DEV-T194', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(208, 'Samsung A9+ CBMS-195', 'Tablet', 'ICS-DIT-0000-24-053-18563', 'QR-DEV-T195', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(209, 'Samsung A9+ CBMS-196', 'Tablet', 'ICS-DIT-0000-24-053-18564', 'QR-DEV-T196', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(210, 'Samsung A9+ CBMS-197', 'Tablet', 'ICS-DIT-0000-24-053-18565', 'QR-DEV-T197', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(211, 'Samsung A9+ CBMS-198', 'Tablet', 'ICS-DIT-0000-24-053-18566', 'QR-DEV-T198', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(212, 'Samsung A9+ CBMS-199', 'Tablet', 'ICS-DIT-0000-24-053-18567', 'QR-DEV-T199', 'available', 'Cabinet 2', 'CBMS 2A', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(213, 'Samsung A9+ CBMS-200', 'Tablet', 'ICS-DIT-0000-24-053-18568', 'QR-DEV-T200', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(214, 'Samsung A9+ CBMS-201', 'Tablet', 'ICS-DIT-0000-24-053-18569', 'QR-DEV-T201', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(215, 'Samsung A9+ CBMS-202', 'Tablet', 'ICS-DIT-0000-24-053-18570', 'QR-DEV-T202', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(216, 'Samsung A9+ CBMS-203', 'Tablet', 'ICS-DIT-0000-24-053-18571', 'QR-DEV-T203', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(217, 'Samsung A9+ CBMS-204', 'Tablet', 'ICS-DIT-0000-24-053-18572', 'QR-DEV-T204', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(218, 'Samsung A9+ CBMS-205', 'Tablet', 'ICS-DIT-0000-24-053-18573', 'QR-DEV-T205', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(219, 'Samsung A9+ CBMS-206', 'Tablet', 'ICS-DIT-0000-24-053-18574', 'QR-DEV-T206', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(220, 'Samsung A9+ CBMS-207', 'Tablet', 'ICS-DIT-0000-24-053-18575', 'QR-DEV-T207', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(221, 'Samsung A9+ CBMS-208', 'Tablet', 'ICS-DIT-0000-24-053-18576', 'QR-DEV-T208', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(222, 'Samsung A9+ CBMS-209', 'Tablet', 'ICS-DIT-0000-24-053-18577', 'QR-DEV-T209', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(223, 'Samsung A9+ CBMS-210', 'Tablet', 'ICS-DIT-0000-24-053-18578', 'QR-DEV-T210', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(224, 'Samsung A9+ CBMS-211', 'Tablet', 'ICS-DIT-0000-24-053-18579', 'QR-DEV-T211', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(225, 'Coby Tablet 1', 'Tablet', 'ICS-DIT-0000-23-253-05516', 'QR-DEV-T212', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(226, 'Coby Tablet 2', 'Tablet', 'ICS-DIT-0000-23-253-05517', 'QR-DEV-T213', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(227, 'Coby Tablet 3', 'Tablet', 'ICS-DIT-0000-23-253-05518', 'QR-DEV-T214', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(228, 'Coby Tablet 4', 'Tablet', 'ICS-DIT-0000-23-253-05519', 'QR-DEV-T215', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(229, 'Coby Tablet 5', 'Tablet', 'ICS-DIT-0000-23-253-05520', 'QR-DEV-T216', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(230, 'Coby Tablet 6', 'Tablet', 'ICS-DIT-0000-23-253-05521', 'QR-DEV-T217', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(231, 'Coby Tablet 7', 'Tablet', 'ICS-DIT-0000-23-253-05522', 'QR-DEV-T218', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(232, 'Coby Tablet 8', 'Tablet', 'ICS-DIT-0000-23-253-05523', 'QR-DEV-T219', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(233, 'Coby Tablet 9', 'Tablet', 'ICS-DIT-0000-23-253-05524', 'QR-DEV-T220', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(234, 'Coby Tablet 10', 'Tablet', 'ICS-DIT-0000-23-253-05525', 'QR-DEV-T221', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(235, 'Coby Tablet 11', 'Tablet', 'ICS-DIT-0000-23-253-05526', 'QR-DEV-T222', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(236, 'Coby Tablet 12', 'Tablet', 'ICS-DIT-0000-23-253-05527', 'QR-DEV-T223', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(237, 'Coby Tablet 13', 'Tablet', 'ICS-DIT-0000-23-253-05528', 'QR-DEV-T224', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(238, 'Coby Tablet 14', 'Tablet', 'ICS-DIT-0000-23-253-05529', 'QR-DEV-T225', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(239, 'Coby Tablet 15', 'Tablet', 'ICS-DIT-0000-23-253-05530', 'QR-DEV-T226', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(240, 'Coby Tablet 16', 'Tablet', 'ICS-DIT-0000-23-253-05531', 'QR-DEV-T227', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(241, 'Coby Tablet 17', 'Tablet', 'CPU-000-15-064-083-05532', 'QR-DEV-T228', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(242, 'Coby Tablet 18', 'Tablet', 'ICS-DIT-0000-23-253-05533', 'QR-DEV-T229', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(243, 'Coby Tablet 19', 'Tablet', 'ICS-DIT-0000-23-253-05534', 'QR-DEV-T230', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(244, 'Coby Tablet 20', 'Tablet', 'ICS-DIT-0000-23-253-05535', 'QR-DEV-T231', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(245, 'Coby Tablet 21', 'Tablet', 'ICS-DIT-0000-23-253-05536', 'QR-DEV-T232', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(246, 'Coby Tablet 22', 'Tablet', 'ICS-DIT-0000-23-253-05537', 'QR-DEV-T233', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(247, 'Coby Tablet 23', 'Tablet', 'ICS-DIT-0000-23-253-05538', 'QR-DEV-T234', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(248, 'Coby Tablet 24', 'Tablet', 'ICS-DIT-0000-23-253-05539', 'QR-DEV-T235', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(249, 'Coby Tablet 25', 'Tablet', 'ICS-DIT-0000-23-253-05540', 'QR-DEV-T236', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(250, 'Coby Tablet 26', 'Tablet', 'ICS-DIT-0000-23-253-05541', 'QR-DEV-T237', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(251, 'Coby Tablet 27', 'Tablet', 'ICS-DIT-0000-23-253-05542', 'QR-DEV-T238', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(252, 'Coby Tablet 28', 'Tablet', 'ICS-DIT-0000-23-253-05543', 'QR-DEV-T239', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(253, 'Coby Tablet 29', 'Tablet', 'ICS-DIT-0000-23-253-05544', 'QR-DEV-T240', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(254, 'Coby Tablet 30', 'Tablet', 'ICS-DIT-0000-23-253-05545', 'QR-DEV-T241', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(255, 'Coby Tablet 31', 'Tablet', 'ICS-DIT-0000-23-253-05546', 'QR-DEV-T242', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(256, 'Coby Tablet 32', 'Tablet', 'ICS-DIT-0000-23-253-05547', 'QR-DEV-T243', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(257, 'Coby Tablet 33', 'Tablet', 'ICS-DIT-0000-23-253-05548', 'QR-DEV-T244', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(258, 'Coby Tablet 34', 'Tablet', 'ICS-DIT-0000-23-253-05549', 'QR-DEV-T245', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(259, 'Coby Tablet 35', 'Tablet', 'ICS-DIT-0000-23-253-05550', 'QR-DEV-T246', 'available', 'Cabinet 2', 'CBMS 2B', '', '2026-05-20 08:33:11', '2026-05-20 09:16:15'),
(260, 'Coby Tablet 36', 'Tablet', 'ICS-DIT-0000-23-253-05551', 'QR-DEV-T247', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(261, 'Coby Tablet 37', 'Tablet', 'ICS-DIT-0000-23-253-05552', 'QR-DEV-T248', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(262, 'Coby Tablet 38', 'Tablet', 'ICS-DIT-0000-23-253-05553', 'QR-DEV-T249', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(263, 'Coby Tablet 39', 'Tablet', 'ICS-DIT-0000-23-253-05554', 'QR-DEV-T250', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(264, 'Coby Tablet 40', 'Tablet', 'ICS-DIT-0000-23-253-05555', 'QR-DEV-T251', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(265, 'Coby Tablet 41', 'Tablet', 'ICS-DIT-0000-23-392-02076', 'QR-DEV-T252', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(266, 'Coby Tablet 42', 'Tablet', 'ICS-DIT-0000-23-392-02077', 'QR-DEV-T253', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(267, 'Coby Tablet 43', 'Tablet', 'ICS-DIT-0000-23-392-02078', 'QR-DEV-T254', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(268, 'Coby Tablet 44', 'Tablet', 'ICS-DIT-0000-23-392-02079', 'QR-DEV-T255', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(269, 'Coby Tablet 45', 'Tablet', 'ICS-DIT-0000-23-392-02080', 'QR-DEV-T256', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(270, 'Coby Tablet 46', 'Tablet', 'ICS-DIT-0000-23-392-02081', 'QR-DEV-T257', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(271, 'Coby Tablet 47', 'Tablet', 'ICS-DIT-0000-23-392-02082', 'QR-DEV-T258', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(272, 'Coby Tablet 48', 'Tablet', 'ICS-DIT-0000-23-392-02083', 'QR-DEV-T259', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(273, 'Coby Tablet 49', 'Tablet', 'ICS-DIT-0000-23-392-02084', 'QR-DEV-T260', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(274, 'Coby Tablet 50', 'Tablet', 'ICS-DIT-0000-23-392-02085', 'QR-DEV-T261', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(275, 'Coby Tablet 51', 'Tablet', 'ICS-DIT-0000-23-392-02086', 'QR-DEV-T262', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(276, 'Coby Tablet 52', 'Tablet', 'ICS-DIT-0000-23-392-02087', 'QR-DEV-T263', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(277, 'Coby Tablet 53', 'Tablet', 'ICS-DIT-0000-23-392-02088', 'QR-DEV-T264', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(278, 'Coby Tablet 54', 'Tablet', 'ICS-DIT-0000-23-396-02551', 'QR-DEV-T265', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(279, 'Coby Tablet 55', 'Tablet', 'ICS-DIT-0000-23-396-02552', 'QR-DEV-T266', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(280, 'Coby Tablet 56', 'Tablet', 'ICS-DIT-0000-23-396-02553', 'QR-DEV-T267', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(281, 'Coby Tablet 57', 'Tablet', 'ICS-DIT-0000-23-396-02554', 'QR-DEV-T268', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(282, 'Coby Tablet 58', 'Tablet', 'ICS-DIT-0000-23-396-02555', 'QR-DEV-T269', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(283, 'Coby Tablet 59', 'Tablet', 'ICS-DIT-0000-23-396-02556', 'QR-DEV-T270', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(284, 'Coby Tablet 60', 'Tablet', 'ICS-DIT-0000-23-396-02557', 'QR-DEV-T271', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(285, 'Coby Tablet 61', 'Tablet', 'ICS-DIT-0000-23-396-02558', 'QR-DEV-T272', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(286, 'Coby Tablet 62', 'Tablet', 'ICS-DIT-0000-23-396-02559', 'QR-DEV-T273', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(287, 'Coby Tablet 63', 'Tablet', 'ICS-DIT-0000-23-396-02560', 'QR-DEV-T274', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(288, 'Coby Tablet 64', 'Tablet', 'ICS-DIT-0000-23-396-02561', 'QR-DEV-T275', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(289, 'Coby Tablet 65', 'Tablet', 'ICS-DIT-0000-23-396-02562', 'QR-DEV-T276', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(290, 'Coby Tablet 66', 'Tablet', 'ICS-DIT-0000-23-396-02563', 'QR-DEV-T277', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(291, 'Coby Tablet 67', 'Tablet', 'ICS-DIT-0000-23-396-02564', 'QR-DEV-T278', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(292, 'Coby Tablet 68', 'Tablet', 'ICS-DIT-0000-23-396-02565', 'QR-DEV-T279', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(293, 'Coby Tablet 69', 'Tablet', 'ICS-DIT-0000-23-396-02566', 'QR-DEV-T280', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(294, 'Coby Tablet 70', 'Tablet', 'ICS-DIT-0000-23-396-02567', 'QR-DEV-T281', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11');
INSERT INTO `devices` (`id`, `name`, `type`, `asset_tag`, `qr_code`, `status`, `cabinet`, `shelf`, `notes`, `created_at`, `updated_at`) VALUES
(295, 'Coby Tablet 71', 'Tablet', 'ICS-DIT-0000-23-396-02568', 'QR-DEV-T282', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(296, 'Coby Tablet 72', 'Tablet', 'ICS-DIT-0000-23-396-02569', 'QR-DEV-T283', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(297, 'Coby Tablet 73', 'Tablet', 'ICS-DIT-0000-23-396-02570', 'QR-DEV-T284', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(298, 'Coby Tablet 74', 'Tablet', 'ICS-DIT-0000-23-396-02571', 'QR-DEV-T285', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(299, 'Coby Tablet 75', 'Tablet', 'ICS-DIT-0000-23-396-02572', 'QR-DEV-T286', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(300, 'Coby Tablet 76', 'Tablet', 'ICS-DIT-0000-23-396-02573', 'QR-DEV-T287', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(301, 'Coby Tablet 77', 'Tablet', 'ICS-DIT-0000-23-396-02574', 'QR-DEV-T288', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(302, 'Coby Tablet 78', 'Tablet', 'ICS-DIT-0000-23-396-02575', 'QR-DEV-T289', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(303, 'Coby Tablet 79', 'Tablet', 'ICS-DIT-0000-23-396-02576', 'QR-DEV-T290', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(304, 'Coby Tablet 80', 'Tablet', 'ICS-DIT-0000-23-396-02577', 'QR-DEV-T291', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(305, 'Coby Tablet 81', 'Tablet', 'ICS-DIT-0000-23-396-02578', 'QR-DEV-T292', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(306, 'Coby Tablet 82', 'Tablet', 'ICS-DIT-0000-23-396-02579', 'QR-DEV-T293', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(307, 'Coby Tablet 83', 'Tablet', 'ICS-DIT-0000-23-396-02580', 'QR-DEV-T294', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(308, 'Coby Tablet 84', 'Tablet', 'ICS-DIT-0000-23-396-02581', 'QR-DEV-T295', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(309, 'Coby Tablet 85', 'Tablet', 'ICS-DIT-0000-23-396-02582', 'QR-DEV-T296', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(310, 'Coby Tablet 86', 'Tablet', 'ICS-DIT-0000-23-396-02583', 'QR-DEV-T297', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(311, 'Coby Tablet 87', 'Tablet', 'ICS-DIT-0000-23-396-02584', 'QR-DEV-T298', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(312, 'Coby Tablet 88', 'Tablet', 'ICS-DIT-0000-23-396-02585', 'QR-DEV-T299', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(313, 'Coby Tablet 89', 'Tablet', 'ICS-DIT-0000-23-396-02586', 'QR-DEV-T300', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(314, 'Coby Tablet 90', 'Tablet', 'ICS-DIT-0000-23-396-02587', 'QR-DEV-T301', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(315, 'Coby Tablet 91', 'Tablet', 'ICS-DIT-0000-23-396-02588', 'QR-DEV-T302', 'available', 'Cabinet 2', 'CBMS 2B', NULL, '2026-05-20 08:33:11', '2026-05-20 08:33:11');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `qr_code` varchar(50) NOT NULL,
  `role` enum('admin','it_staff','borrower') NOT NULL DEFAULT 'borrower',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `department`, `qr_code`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Gemma N. Opis', 'Administrative Unit', 'CSS-GNO', 'admin', '2026-05-20 08:33:11', '2026-05-20 09:03:43'),
(2, 'Orlando L. Mercene', 'Statistical Unit', 'SSS-OLM', 'borrower', '2026-05-20 08:33:11', '2026-05-20 09:20:34'),
(3, 'Ginalyn M. Muhi', 'Administrative Unit', 'RO2-GMM', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(4, 'Olivia J. Jasmin', 'Administrative Unit', 'AO1-OJJ', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(5, 'Hazel J. Cuadrasal', 'Administrative Unit', 'AO1-HJC', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(6, 'Maria Baby Jane M. Sualog', 'Statistical Unit', 'SS2-MBJMS', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(7, 'James Allen M. Josue', 'Statistical Unit', 'ISA1-JAMJ', 'admin', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(8, 'Mary Michelle M. Macutong', 'Civil Registration Unit', 'RO1-MMMM', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(9, 'Purita H. Olivar', 'Statistical Unit', 'AS-PHO', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(10, 'Jennen M. Mutya', 'Administrative Unit', 'AC-JMM', 'it_staff', '2026-05-20 08:33:11', '2026-05-20 09:04:32'),
(11, 'Sonny Jr R. Dela Cruz', 'Statistical Unit', 'SA-SJRDC', 'it_staff', '2026-05-20 08:33:11', '2026-05-20 08:41:56'),
(12, 'Michelle V. Luci', 'Statistical Unit', 'AS-MVL', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(13, 'Danica J. Gamara', 'National ID Unit', 'IO-DJG', 'borrower', '2026-05-20 08:33:11', '2026-05-20 09:03:59'),
(14, 'Jiezle B. Janda', 'National ID Unit', 'RO2-JBJ', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(15, 'John Mar A. Nambio', 'National ID Unit', 'ISA1-JMAN', 'it_staff', '2026-05-20 08:33:11', '2026-05-20 09:04:37'),
(16, 'Shiela Mae D. Matining', 'Civil Registration Unit', 'BRC-SMDM', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(17, 'Harvy M. Fabaleña', 'Statistical Unit', 'SRSS-HMF', 'it_staff', '2026-05-20 08:33:11', '2026-05-20 09:04:25'),
(18, 'Frenz Darren J. Medallon', 'Statistical Unit', 'ISA1-FJDM', 'admin', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(19, 'Jean A. Larosa', 'Administrative Unit', 'AC-JAL', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(20, 'Liza S. Semilla', 'Civil Registration Unit', 'UPP-LSS', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(21, 'Charlotte M. Manlisis', 'Statistical Unit', 'AS-CMM', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(22, 'Kane Carol T. Matibag', 'Statistical Unit', 'SA-KCTM', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(23, 'Myla G. Minay', 'Civil Registration Unit', 'CRC-MGM', 'it_staff', '2026-05-20 08:33:11', '2026-05-20 09:05:06'),
(24, 'Abejen Shayne P. Mayores', 'Statistical Unit', 'SA-ASPM', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11'),
(25, 'Brenz Axel M. Rabi', 'Statistical Unit', 'SA-BAMR', 'borrower', '2026-05-20 08:33:11', '2026-05-20 08:33:11');

-- --------------------------------------------------------

--
-- Table structure for table `reconciliations`
--

CREATE TABLE `reconciliations` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `performed_by` int(10) UNSIGNED NOT NULL,
  `old_status` varchar(30) DEFAULT NULL,
  `new_status` varchar(30) DEFAULT NULL,
  `reason` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `borrower_id` int(10) UNSIGNED NOT NULL,
  `facilitated_by` int(10) UNSIGNED DEFAULT NULL,
  `returned_by` int(10) UNSIGNED DEFAULT NULL,
  `borrowed_at` datetime DEFAULT current_timestamp(),
  `purpose` varchar(255) DEFAULT NULL,
  `expected_return_at` date DEFAULT NULL,
  `returned_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asset_tag` (`asset_tag`),
  ADD UNIQUE KEY `qr_code` (`qr_code`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_code` (`qr_code`);

--
-- Indexes for table `reconciliations`
--
ALTER TABLE `reconciliations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`),
  ADD KEY `performed_by` (`performed_by`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`),
  ADD KEY `borrower_id` (`borrower_id`),
  ADD KEY `facilitated_by` (`facilitated_by`),
  ADD KEY `returned_by` (`returned_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `reconciliations`
--
ALTER TABLE `reconciliations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reconciliations`
--
ALTER TABLE `reconciliations`
  ADD CONSTRAINT `reconciliations_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`),
  ADD CONSTRAINT `reconciliations_ibfk_2` FOREIGN KEY (`performed_by`) REFERENCES `employees` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`borrower_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`facilitated_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`returned_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
