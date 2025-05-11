-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 01:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `one_zambo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `extension_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `position` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `barangay_logo` varchar(255) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `role` enum('admin','superadmin') NOT NULL,
  `proof_image` varchar(255) NOT NULL,
  `status` enum('active','inactive','done') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `username`, `password`, `image`, `birthday`, `age`, `gender`, `position`, `city`, `barangay`, `barangay_logo`, `contact`, `role`, `proof_image`, `status`, `last_login`, `verification_code`) VALUES
(1, 'Dom', 'D.', 'Kang', '', 'superadmin@gmail.com', 'superadmin', '$2y$10$Q8PdEXgBq9/Wh9vjDTVLLOTUO9p2HshqhoqSM90/pEsMtr5dB2vLu', '../../assets/uploads/profiles/dom.jpg', '0000-00-00', 0, '', '', '', '', NULL, '0', 'superadmin', '', 'inactive', '2024-11-24 22:53:17', NULL),
(2, 'Jay', 'D.', 'Jo', '', 'admin@gmail.com', 'admin', '$2y$10$1/0RxskZuTnu3vWP0TW.C.1RM1H8n8o.M.hUIUz/uA5gF.yI7rWqy', '../../assets/uploads/profiles/1736694970_download.jpg', '2004-10-25', 20, 'Male', 'Captain', 'Zamboanga', 'Tetuan', NULL, '2147483647', 'admin', '../../assets/uploads/appointments/1736694970_captain.webp', 'active', '2025-05-02 14:14:32', ''),
(29, 'Los Eli', '', 'Angeles', 'Hon.', 'tk4027578@gmail.com', 'AngelesAdmin', '$2y$10$nPWUl7YKNp35067vbA6FnO7huHHnf0y8N7wIs3XhnNN66g97C3BEe', '', '1983-05-10', 41, 'Male', 'Barangay Captain', 'Zamboanga City', 'Sta. Maria', '', '09335212345', 'admin', '../../assets/uploads/appointments/captain.webp', 'inactive', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assigned_worker`
--

CREATE TABLE `assigned_worker` (
  `id` int(11) NOT NULL,
  `assigned_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('assigned','unassigned') NOT NULL DEFAULT 'assigned',
  `worker_id` int(11) NOT NULL,
  `evacuation_center_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `assigned_worker`
--

INSERT INTO `assigned_worker` (`id`, `assigned_date`, `status`, `worker_id`, `evacuation_center_id`) VALUES
(18, '2025-05-02 09:39:10', 'assigned', 1, 26),
(19, '2025-05-02 09:39:10', 'assigned', 4, 26);

-- --------------------------------------------------------

--
-- Table structure for table `barangay`
--

CREATE TABLE `barangay` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barangay`
--

INSERT INTO `barangay` (`id`, `name`) VALUES
(1, 'Talon-Talon'),
(2, 'Pasonanca'),
(3, 'Sta. Maria'),
(4, 'San Roque'),
(6, 'Tetuan'),
(7, 'Putik'),
(8, 'Divisoria'),
(9, 'Guiwan'),
(10, 'Ayala'),
(11, 'Sta. Catalina'),
(12, 'Arena Blanco'),
(13, 'Canelar'),
(14, 'Southcom Village'),
(16, 'Earth'),
(17, 'Laughtale');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `admin_id`) VALUES
(1, 'Food', 2),
(14, 'Starting Kit', 2),
(19, 'Starting Kit', 29);

-- --------------------------------------------------------

--
-- Table structure for table `distribute`
--

CREATE TABLE `distribute` (
  `id` int(11) NOT NULL,
  `supply_name` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `evacuees_id` int(11) NOT NULL,
  `supply_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `distributor_id` int(11) NOT NULL,
  `distributor_type` enum('admin','worker') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `distribute`
--

INSERT INTO `distribute` (`id`, `supply_name`, `date`, `evacuees_id`, `supply_id`, `quantity`, `distributor_id`, `distributor_type`) VALUES
(114, 'Canton', '2025-01-12 23:35:10', 56, 39, 1, 2, 'admin'),
(115, 'Starter Kit', '2025-05-02 09:42:53', 58, 35, 1, 2, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `evacuation_center`
--

CREATE TABLE `evacuation_center` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `capacity` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `evacuation_center`
--

INSERT INTO `evacuation_center` (`id`, `name`, `location`, `barangay`, `capacity`, `admin_id`, `image`, `created_at`) VALUES
(26, 'Barangay Hall', 'Natividad Street Zamboanga City', ' Guiwan', '21', 2, '', '2024-12-05 13:51:04'),
(31, 'Higschool Main', 'don toribio', ' Tetuan', '2', 2, '../../assets/uploads/evacuation_centers/3.png', '2025-05-02 01:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `evacuees`
--

CREATE TABLE `evacuees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `extension_name` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `position` varchar(255) NOT NULL,
  `disaster_type` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `age` int(11) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `monthly_income` varchar(255) NOT NULL,
  `damage` enum('totally','partially') NOT NULL,
  `cost_damage` varchar(255) NOT NULL,
  `house_owner` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `evacuation_center_id` int(11) NOT NULL,
  `origin_evacuation_center_id` int(11) DEFAULT NULL,
  `date` date DEFAULT curdate(),
  `status` enum('Admitted','Transfer','Transferred','Moved-out','Admit','Pending') NOT NULL DEFAULT 'Admitted'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `evacuees`
--

INSERT INTO `evacuees` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `gender`, `position`, `disaster_type`, `barangay`, `birthday`, `age`, `occupation`, `contact`, `monthly_income`, `damage`, `cost_damage`, `house_owner`, `admin_id`, `evacuation_center_id`, `origin_evacuation_center_id`, `date`, `status`) VALUES
(56, 'Naruto', '', 'Uzumaki', '', 'Male', 'Owner', 'Flood', 'Tetuan', '1997-05-13', 27, 'Teacher', '9365716271', '50000', 'totally', '10000', 'Naruto  Uzumaki', 2, 26, 26, '2025-01-12', 'Admitted'),
(58, 'Sasuke', '', 'Uchiha', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2001-06-12', 23, 'Teacher', '9362517561', '60000', 'totally', '2000', 'Sasuke  Uchiha', 2, 26, 30, '2025-01-12', 'Admitted'),
(60, 'Rock', '', 'Lee', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2002-07-26', 22, 'Student', '912343245321', '0', 'totally', '1000', 'Rock  Lee', 2, 26, 26, '2025-04-30', 'Admitted'),
(61, 'Ethan', '', 'Felix', '', 'Male', 'Owner', 'Fire', 'Tetuan', '1995-10-17', 29, 'Teacher', '9346781958', '20000', 'totally', '3000', 'Ethan  Felix', 2, 26, 26, '2025-04-30', 'Admitted');

-- --------------------------------------------------------

--
-- Table structure for table `evacuees_log`
--

CREATE TABLE `evacuees_log` (
  `id` int(11) NOT NULL,
  `log_msg` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('notify','cleared') NOT NULL,
  `evacuees_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `evacuees_log`
--

INSERT INTO `evacuees_log` (`id`, `log_msg`, `created_at`, `status`, `evacuees_id`) VALUES
(276, 'Admitted', '2025-01-12 23:28:44', 'notify', 56),
(277, '1 piece of Canton have been distributed.', '2025-01-12 23:29:00', 'notify', 56),
(279, '1 pack of Canton have been distributed.', '2025-01-12 23:35:10', 'notify', 56),
(281, 'Requesting transfer to Barangay Hall', '2025-01-12 23:36:30', 'notify', 58),
(282, 'Transfer approved.', '2025-01-12 23:37:02', 'notify', 58),
(285, 'Admitted', '2025-04-30 17:59:46', 'notify', 60),
(286, 'Admitted', '2025-04-30 18:25:45', 'notify', 61),
(287, '1 pack of Starter Kit have been distributed.', '2025-05-02 09:42:53', 'notify', 58);

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE `feeds` (
  `id` int(11) NOT NULL,
  `logged_in_id` int(11) NOT NULL,
  `user_type` enum('admin','worker') NOT NULL,
  `feed_msg` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('notify','cleared') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `feeds`
--

INSERT INTO `feeds` (`id`, `logged_in_id`, `user_type`, `feed_msg`, `created_at`, `status`) VALUES
(1, 2, 'admin', '22 piece of Chicken Added.', '2024-11-13 00:00:00', 'notify'),
(10, 2, 'admin', '69 pieces of Chicken Joy Added.', '2024-11-13 00:00:00', 'notify'),
(11, 2, 'admin', '69 packs of Chicken Joy Added.', '2024-11-13 00:00:00', 'notify'),
(12, 2, 'admin', '1 piece of Maloi Added.', '2024-11-13 00:00:00', 'notify'),
(13, 2, 'admin', '22 pieces of Ahmad M. Salasain  Added.', '2024-11-14 00:00:00', 'notify'),
(14, 2, 'admin', '33 pieces of Venyz Bangquiao added at WMSU Main Campus', '2024-11-14 00:00:00', 'notify'),
(15, 2, 'admin', '12 packs of Nadzrin Alhari added at WMSU Main Campus', '2024-11-14 09:46:04', 'notify'),
(16, 2, 'admin', 'Evacuee \"Peter D Parker II\" admitted to \"WMSU Main Campus\".', '2024-11-15 08:55:24', 'notify'),
(17, 2, 'admin', '0 pack(s) of Magi distributed to Parker', '2024-11-15 09:05:05', 'notify'),
(18, 2, 'admin', '13 packs of Magi distributed to Parker', '2024-11-15 09:08:56', 'notify'),
(19, 2, 'admin', '9 packs of Ahmad M. Salasain  added at WMSU Main Campus', '2024-11-15 09:36:53', 'notify'),
(20, 2, 'admin', '22 packs of Ahmad M. Salasain  added at WMSU Main Campus', '2024-11-15 09:39:12', 'notify'),
(21, 2, 'admin', '22 pieces of Ahmad M. Salasain  added at WMSU Main Campus', '2024-11-15 09:39:25', 'notify'),
(22, 2, 'admin', '2 packs of Magi distributed to Parker.', '2024-11-15 09:46:57', 'notify'),
(23, 2, 'admin', '13 packs of Magi distributed to Parker.', '2024-11-15 09:47:45', 'notify'),
(24, 2, 'admin', '22 pieces of Chicken Joy added at Avengers Tower', '2024-11-15 10:00:04', 'notify'),
(25, 2, 'admin', '2 pieces of Chicken Joy distributed to Smile.', '2024-11-15 10:00:35', 'notify'),
(26, 2, 'admin', '2 pieces of Chicken Joy distributed to Smile.', '2024-11-15 10:00:35', 'notify'),
(27, 2, 'admin', '1 packs of Magi distributed to Parker.', '2024-11-15 10:15:48', 'notify'),
(29, 2, 'admin', '1 pack of Cat Food distributed to Parker.', '2024-11-15 10:40:45', 'notify'),
(30, 2, 'admin', '3 packs of Cat Food distributed to Parker.', '2024-11-15 10:40:57', 'notify'),
(31, 2, 'admin', '2 packs of Cat Food distributed to Parker.', '2024-11-15 11:54:57', 'notify'),
(32, 2, 'admin', '15 packs of Magi distributed to Parker.', '2024-11-15 12:19:47', 'notify'),
(33, 2, 'admin', '13 packs of Magi distributed to Parker.', '2024-11-15 12:23:10', 'notify'),
(34, 2, 'admin', '15 packs of Magi distributed to Parker.', '2024-11-15 12:27:50', 'notify'),
(35, 2, 'admin', '14 packs of Magi distributed to Parker.', '2024-11-15 12:27:50', 'notify'),
(36, 2, 'admin', '13 packs of Magi distributed to Parker.', '2024-11-15 12:27:50', 'notify'),
(37, 2, 'admin', '3 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:40:59', 'notify'),
(38, 2, 'admin', '3 pieces of Chicken Joy distributed to Stark.', '2024-11-15 12:40:59', 'notify'),
(39, 2, 'admin', '1 piece of Chicken Joy distributed to Smile.', '2024-11-15 12:42:32', 'notify'),
(40, 2, 'admin', '15 packs of Magi distributed to Parker.', '2024-11-15 12:47:28', 'notify'),
(41, 2, 'admin', '12 packs of Magi distributed to Parker.', '2024-11-15 12:47:56', 'notify'),
(42, 2, 'admin', '10 packs of Magi distributed to Parker.', '2024-11-15 12:48:06', 'notify'),
(43, 2, 'admin', '20 packs of Magi distributed to Parker.', '2024-11-15 12:48:33', 'notify'),
(44, 2, 'admin', '5 packs of Magi distributed to Parker.', '2024-11-15 12:48:46', 'notify'),
(45, 2, 'admin', '4 packs of Magi distributed to Parker.', '2024-11-15 12:48:53', 'notify'),
(46, 2, 'admin', '1 pack of Magi distributed to Parker.', '2024-11-15 12:51:04', 'notify'),
(47, 2, 'admin', '5 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:51:11', 'notify'),
(48, 2, 'admin', '5 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:51:11', 'notify'),
(49, 2, 'admin', '7 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:51:39', 'notify'),
(50, 2, 'admin', '7 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:51:39', 'notify'),
(51, 2, 'admin', '8 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:51:39', 'notify'),
(52, 2, 'admin', '5 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:52:05', 'notify'),
(53, 2, 'admin', '5 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:52:05', 'notify'),
(54, 2, 'admin', '5 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:52:05', 'notify'),
(55, 2, 'admin', '7 pieces of Chicken Joy distributed to Stark.', '2024-11-15 12:52:05', 'notify'),
(56, 2, 'admin', '5 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:52:30', 'notify'),
(57, 2, 'admin', '5 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:52:30', 'notify'),
(58, 2, 'admin', '5 pieces of Chicken Joy distributed to Smile.', '2024-11-15 12:52:30', 'notify'),
(59, 2, 'admin', '5 pieces of Chicken Joy distributed to Stark.', '2024-11-15 12:52:30', 'notify'),
(60, 2, 'admin', '5 packs of Magi distributed to Parker.', '2024-11-15 13:25:30', 'notify'),
(61, 2, 'admin', '7 packs of Magi distributed to Parker.', '2024-11-15 13:25:38', 'notify'),
(62, 2, 'admin', '24 packs of Magi distributed to Parker.', '2024-11-15 13:26:11', 'notify'),
(63, 2, 'admin', '25 packs of Magi distributed to Parker.', '2024-11-15 13:26:24', 'notify'),
(64, 2, 'admin', '30 packs of Magi distributed to Parker.', '2024-11-15 13:30:24', 'notify'),
(65, 2, 'admin', '25 packs of Magi distributed to Parker.', '2024-11-15 13:30:36', 'notify'),
(66, 2, 'admin', '5 packs of Magi distributed to Parker.', '2024-11-15 13:30:40', 'notify'),
(67, 2, 'admin', '5 packs of Magi distributed to Parker.', '2024-11-15 13:30:59', 'notify'),
(68, 2, 'admin', '8 packs of Magi distributed to Parker.', '2024-11-15 13:33:35', 'notify'),
(69, 2, 'admin', '5 packs of Magi distributed to Parker.', '2024-11-15 13:43:48', 'notify'),
(70, 2, 'admin', '5 packs of Magi distributed to Parker.', '2024-11-15 13:45:07', 'notify'),
(71, 2, 'admin', 'Micheal D. Jordan admitted to 3.', '2024-11-15 16:35:33', 'notify'),
(78, 2, 'admin', '3 pieces of Chicken Joy distributed to Haruko.', '2024-11-15 17:06:02', 'notify'),
(79, 2, 'admin', '3 pieces of Chicken Joy distributed to Jordan.', '2024-11-15 17:06:02', 'notify'),
(80, 2, 'admin', '3  of Chicken Joy have been returned to Avengers Tower Evacuation Center.', '2024-11-15 17:06:09', 'notify'),
(81, 2, 'admin', '3  of Chicken Joy have been returned to Avengers Tower Evacuation Center.', '2024-11-15 17:06:09', 'notify'),
(82, 2, 'admin', '4 pieces of Chicken Joy distributed to Haruko.', '2024-11-15 17:06:36', 'notify'),
(83, 2, 'admin', '4 pieces of Chicken Joy distributed to Smile.', '2024-11-15 17:06:36', 'notify'),
(84, 2, 'admin', '2 pieces of Chicken Joy have been returned to Avengers Tower.', '2024-11-15 17:15:44', 'notify'),
(85, 2, 'admin', '1 piece of Chicken Joy have been returned to Avengers Tower.', '2024-11-15 17:15:44', 'notify'),
(86, 2, 'admin', '1 piece of Chicken Joy have been returned to Avengers Tower.', '2024-11-15 17:16:13', 'notify'),
(87, 1, 'admin', 'Sam D. Cena admitted to 5.', '2024-11-17 20:11:16', 'notify'),
(88, 1, 'worker', 'Maloi D. Ricalde admitted to 4.', '2024-11-17 20:44:54', 'notify'),
(89, 1, 'worker', 'Kai D Zoku admitted to Barangay Hall.', '2024-11-17 20:51:24', 'notify'),
(90, 2, 'admin', '25 packs of WMSU Clothe added at Western Mindanao State University Main Campus', '2024-11-17 21:07:32', 'notify'),
(91, 2, 'admin', '33 packs of Magi distributed to Parker.', '2024-11-17 21:58:25', 'notify'),
(92, 2, 'admin', '33 packs of Magi distributed to Jordan.', '2024-11-17 21:58:25', 'notify'),
(93, 1, 'admin', '33 packs of Magi distributed to Cena.', '2024-11-17 21:58:25', 'notify'),
(94, 2, 'admin', '33 packs of Magi have been returned to Western Mindanao State University Main Campus.', '2024-11-17 21:58:43', 'notify'),
(95, 2, 'admin', '33 packs of Magi have been returned to Western Mindanao State University Main Campus.', '2024-11-17 21:58:43', 'notify'),
(96, 1, 'admin', '32 packs of Magi have been returned to Western Mindanao State University Main Campus.', '2024-11-17 21:58:43', 'notify'),
(97, 1, 'admin', '1 pack of Magi have been returned to Western Mindanao State University Main Campus.', '2024-11-17 21:58:52', 'notify'),
(98, 2, 'admin', '2 packs of Cat Food distributed to Parker.', '2024-11-17 22:29:46', 'notify'),
(99, 2, 'admin', '22 packs of Magi distributed to Parker.', '2024-11-18 02:59:03', 'notify'),
(100, 2, 'admin', '5 packs of Magi distributed to Parker.', '2024-11-18 09:05:42', 'notify'),
(101, 2, 'admin', '5 packs of Magi distributed to Jordan.', '2024-11-18 09:05:42', 'notify'),
(102, 1, 'admin', '5 packs of Magi distributed to Cena.', '2024-11-18 09:05:42', 'notify'),
(103, 2, 'admin', '129 packs of Magi have been returned to Western Mindanao State University Main Campus.', '2024-11-18 09:25:42', 'notify'),
(104, 2, 'admin', '23 packs of Magi have been returned to Western Mindanao State University Main Campus.', '2024-11-18 09:25:42', 'notify'),
(105, 1, 'admin', '7 packs of Magi have been returned to Western Mindanao State University Main Campus.', '2024-11-18 09:25:42', 'notify'),
(106, 2, 'admin', '2 packs of Magi distributed to Parker.', '2024-11-18 09:26:20', 'notify'),
(107, 2, 'admin', '2 packs of Magi distributed to Jordan.', '2024-11-18 09:26:20', 'notify'),
(108, 2, 'admin', '22 packs of Magi distributed to Parker.', '2024-11-18 09:29:04', 'notify'),
(109, 2, 'admin', '22 packs of Magi distributed to Jordan.', '2024-11-18 09:29:04', 'notify'),
(110, 1, 'admin', '22 packs of Magi distributed to Cena.', '2024-11-18 09:29:04', 'notify'),
(113, 2, 'admin', '2 packs of Magi distributed to Cena.', '2024-11-18 11:14:12', 'notify'),
(114, 2, 'admin', '2 packs of Magi distributed to Ricalde.', '2024-11-18 11:14:12', 'notify'),
(115, 2, 'admin', '2 packs of Cat Food distributed to Cena.', '2024-11-18 11:41:22', 'notify'),
(116, 2, 'admin', '2 packs of Cat Food distributed to Ricalde.', '2024-11-18 11:43:06', 'notify'),
(117, 2, 'admin', '2 packs of Cat Food distributed to Cena.', '2024-11-18 11:43:31', 'notify'),
(118, 2, 'admin', '2 packs of Magi redistributed to Cena.', '2024-11-18 11:49:31', 'notify'),
(119, 2, 'admin', '2 packs of Magi redistributed to Ricalde.', '2024-11-18 11:49:31', 'notify'),
(120, 2, 'admin', '2 packs of Magi redistributed to Cena.', '2024-11-18 12:02:52', 'notify'),
(121, 2, 'admin', '2 packs of Magi redistributed to Ricalde.', '2024-11-18 12:02:52', 'notify'),
(122, 2, 'admin', '20 packs of Magi redistributed to Cena.', '2024-11-18 12:03:03', 'notify'),
(123, 2, 'admin', '20 packs of Magi redistributed to Ricalde.', '2024-11-18 12:03:03', 'notify'),
(124, 2, 'admin', '8 packs of Magi distributed to Ricalde.', '2024-11-18 13:36:22', 'notify'),
(125, 2, 'admin', '1 pack of Magi redistributed to Ricalde.', '2024-11-18 13:36:29', 'notify'),
(126, 2, 'admin', '1 pack of Magi redistributed to Ricalde.', '2024-11-20 17:47:05', 'notify'),
(127, 2, 'admin', 'weqwe weqw Cenaqwe admitted to Zamboanga City High School Main.', '2024-11-22 10:20:34', 'notify'),
(128, 2, 'admin', 'asdasd sdasd Cenaaa admitted to Don Gems.', '2024-11-22 10:27:18', 'notify'),
(129, 2, 'admin', '1 piece of Shabu  distributed to Dragon.', '2024-11-25 01:19:23', 'notify'),
(130, 2, 'admin', '10 packs of San Marino added at Don Gems', '2024-11-30 10:02:19', 'notify'),
(131, 2, 'admin', '69 pieces of Mega Sardines added at Don Gems', '2024-11-30 10:04:58', 'notify'),
(132, 2, 'admin', '69 pieces of Beef Loaf added at Don Gems', '2024-11-30 10:09:33', 'notify'),
(133, 2, 'admin', '100 packs of Evacuation Kit added at Don Gems', '2024-12-02 10:54:07', 'notify'),
(134, 2, 'admin', '100 packs of Starter Kit added at Don Gems', '2024-12-02 10:55:23', 'notify'),
(135, 2, 'admin', '1 pack of Evacuation Kit distributed to Jordan.', '2024-12-02 11:21:19', 'notify'),
(136, 2, 'admin', 'Derrick  Rose admitted to Don Gems.', '2024-12-03 18:46:21', 'notify'),
(137, 2, 'admin', 'Lalisa  Manoban admitted to Don Gems.', '2024-12-03 18:53:32', 'notify'),
(138, 2, 'admin', 'Odin  Asgard admitted to Avengers Tower.', '2024-12-03 19:40:08', 'notify'),
(139, 8, 'admin', 'Randy D Orton admitted to Barangay Hall of Bugguk.', '2024-12-03 19:42:49', 'notify'),
(140, 8, 'admin', 'Sam D. Ricalde admitted to Basketball Court of Bugguk.', '2024-12-03 19:51:36', 'notify'),
(141, 2, 'admin', 'Celso Q Lobregat admitted to Zamcelco.', '2024-12-03 19:54:23', 'notify'),
(142, 8, 'admin', 'Micheal D Jordan admitted to Barangay Hall of Bugguk.', '2024-12-03 19:55:48', 'notify'),
(143, 8, 'admin', 'One  Zambo pending for approval in Basketball Court of Bugguk.', '2024-12-03 19:58:22', 'notify'),
(145, 2, 'admin', 'Micheal D. Cena admitted to Zamboanga City High School Main.', '2024-12-03 20:46:15', 'notify'),
(146, 2, 'admin', 'Kevin  Durant admitted to Western Mindanao State University.', '2024-12-03 20:49:52', 'notify'),
(147, 8, 'admin', 'Jimmy G Jordan is pending for approval in Barangay Hall of Bugguk.', '2024-12-03 20:54:30', 'notify'),
(148, 2, 'admin', '1 pack of Starter Kit distributed to Butler.', '2024-12-05 03:43:42', 'notify'),
(149, 2, 'admin', '2 packs of Starter Kit redistributed to Butler.', '2024-12-05 03:43:58', 'notify'),
(150, 2, 'admin', '1 piece of Beef Loaf distributed to Butler.', '2024-12-05 03:44:41', 'notify'),
(151, 2, 'admin', '2 pieces of Beef Loaf redistributed to Butler.', '2024-12-05 03:44:50', 'notify'),
(152, 2, 'admin', '1 pack of Evacuation Kit distributed to Rose.', '2024-12-05 20:39:05', 'notify'),
(153, 2, 'admin', '1 piece of Beef Loaf distributed to Manoban.', '2024-12-09 20:57:32', 'notify'),
(154, 2, 'admin', '1 piece of Beef Loaf distributed to Ricalde.', '2024-12-09 20:57:32', 'notify'),
(155, 2, 'admin', '1 piece of Beef Loaf redistributed to Manoban.', '2024-12-09 20:58:40', 'notify'),
(156, 2, 'admin', '1 piece of Beef Loaf redistributed to Ricalde.', '2024-12-09 20:58:40', 'notify'),
(157, 2, 'admin', '1 pack of Evacuation Kit redistributed to Jordan.', '2024-12-09 21:23:33', 'notify'),
(158, 2, 'admin', '1 pack of Evacuation Kit redistributed to Rose.', '2024-12-09 21:23:33', 'notify'),
(159, 2, 'admin', '1 pack of Starter Kit distributed to Rose.', '2024-12-11 20:23:53', 'notify'),
(160, 2, 'admin', '1 pack of Starter Kit distributed to Rose.', '2024-12-11 20:27:16', 'notify'),
(161, 2, 'admin', '23 pieces of Mega Sardines distributed to Haruko.', '2024-12-11 22:33:11', 'notify'),
(162, 2, 'admin', '23 pieces of Mega Sardines distributed to Ricalde.', '2024-12-11 22:33:11', 'notify'),
(163, 2, 'admin', '23 pieces of Mega Sardines distributed to Butler.', '2024-12-11 22:33:11', 'notify'),
(164, 2, 'admin', '1 piece of Lucky Me Canton distributed to Rose.', '2024-12-12 01:14:20', 'notify'),
(165, 2, 'admin', '1 piece of Lucky Me Canton redistributed to Rose.', '2024-12-12 01:15:47', 'notify'),
(166, 2, 'admin', '1 piece of Mag distributed to Rose.', '2024-12-12 01:21:15', 'notify'),
(167, 2, 'admin', 'sdas sdas Cena admitted to Don Gems.', '2025-01-07 19:33:58', 'notify'),
(168, 2, 'admin', 'sdasd sdasd asdas is pending for approval in Barangay Hall.', '2025-01-09 12:21:18', 'notify'),
(169, 2, 'admin', 'dasdasd sdasd asad admitted to Avengers Tower.', '2025-01-09 12:36:08', 'notify'),
(170, 2, 'admin', '45 pieces of Beef Loaf distributed to Haruko.', '2025-01-09 21:21:25', 'notify'),
(171, 2, 'admin', '50 pieces of Beef Loaf distributed to Butler.', '2025-01-09 21:21:25', 'notify'),
(172, 2, 'admin', '50 pieces of Beef Loaf distributed to Cena.', '2025-01-09 21:21:25', 'notify'),
(173, 2, 'admin', '1 piece of Beef Loaf redistributed to Manoban.', '2025-01-09 22:05:05', 'notify'),
(174, 2, 'admin', '1 piece of Beef Loaf redistributed to Ricalde.', '2025-01-09 22:05:05', 'notify'),
(175, 2, 'admin', '1 piece of Beef Loaf redistributed to Haruko.', '2025-01-09 22:05:05', 'notify'),
(176, 2, 'admin', '1 piece of Beef Loaf redistributed to Butler.', '2025-01-09 22:05:05', 'notify'),
(177, 2, 'admin', '1 piece of Beef Loaf redistributed to Cena.', '2025-01-09 22:05:05', 'notify'),
(178, 2, 'admin', '1 pack of Evacuation Kit distributed to Haruko.', '2025-01-09 22:05:28', 'notify'),
(179, 2, 'admin', '1 pack of Evacuation Kit distributed to Butler.', '2025-01-09 22:05:28', 'notify'),
(180, 2, 'admin', '1 pack of Evacuation Kit distributed to Cena.', '2025-01-09 22:05:28', 'notify'),
(181, 8, 'admin', 'xcasc casc Cena admitted to Grandstand.', '2025-01-09 22:11:29', 'notify'),
(182, 2, 'admin', 'fsdfs dfsd dfsd admitted to Barangay Hall.', '2025-01-09 22:13:32', 'notify'),
(183, 3, 'admin', 'dfsd sdfs fsdfs admitted to TESTEST.', '2025-01-10 02:18:32', 'notify'),
(184, 2, 'admin', '1 piece of Canton added at Barangay Hall', '2025-01-12 23:26:16', 'notify'),
(185, 2, 'admin', 'Naruto  Uzumaki admitted to Barangay Hall.', '2025-01-12 23:28:44', 'notify'),
(186, 2, 'admin', '1 piece of Canton distributed to Uzumaki.', '2025-01-12 23:29:00', 'notify'),
(187, 2, 'admin', 'Sasuke  Uchiha admitted to Tetuan Central School.', '2025-01-12 23:32:01', 'notify'),
(188, 2, 'admin', '1 pack of Canton added at Barangay Hall', '2025-01-12 23:35:00', 'notify'),
(189, 2, 'admin', '1 pack of Canton distributed to Uzumaki.', '2025-01-12 23:35:10', 'notify'),
(190, 2, 'admin', 'Neji  Hyuga admitted to Tetuan Central School.', '2025-01-12 23:38:07', 'notify'),
(191, 2, 'admin', 'Rock  Lee admitted to Barangay Hall.', '2025-04-30 17:59:46', 'notify'),
(192, 2, 'admin', 'Ethan  Felix admitted to Barangay Hall.', '2025-04-30 18:25:45', 'notify'),
(193, 2, 'admin', '1 pack of Starter Kit distributed to Uchiha.', '2025-05-02 09:42:53', 'notify');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `extension_name` varchar(255) NOT NULL,
  `relation` varchar(255) NOT NULL,
  `education` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `occupation` varchar(255) NOT NULL,
  `evacuees_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `relation`, `education`, `gender`, `age`, `birthdate`, `occupation`, `evacuees_id`) VALUES
(167, 'Boruto', '', 'Uzumaki', '', 'Son', 'Elementary', 'Male', 11, '2013-05-06', 'Student', 56),
(168, 'Hinata', '', 'Uzumaki', '', 'Wife', 'College', 'Male', 18, '2006-09-12', 'Teacher', 56);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `logged_in_id` int(11) NOT NULL,
  `user_type` enum('admin','worker') NOT NULL,
  `notification_msg` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('notify','viewed','cleared') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `logged_in_id`, `user_type`, `notification_msg`, `created_at`, `status`) VALUES
(1, 2, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-11-10 00:00:00', 'cleared'),
(2, 2, 'admin', 'New Evacuation Center Added: WMSU', '2024-11-10 00:00:00', 'cleared'),
(3, 2, 'admin', 'New Worker Account Added: Ayako D Ryota', '2024-11-10 00:00:00', 'cleared'),
(4, 1, 'admin', 'New Admin Account Added: Mark D Tabotabo', '2024-11-10 00:00:00', 'notify'),
(5, 2, 'admin', 'Worker Account Has Been Deleted: Takenori Akagi', '2024-11-10 00:00:00', 'cleared'),
(6, 2, 'admin', 'Worker Account Has Been Deleted: Kiminobu Kogure', '2024-11-10 00:00:00', 'cleared'),
(22, 2, 'admin', 'Evacuation center has been inactive: Barangay Hall', '2024-11-12 00:00:00', 'cleared'),
(27, 2, 'admin', 'New Evacuation Center Added: Zamcelco', '2024-11-12 00:00:00', 'cleared'),
(28, 1, 'admin', 'New Evacuation Center Added: Zamcelco at Barangay: Guiwan', '2024-11-12 00:00:00', 'cleared'),
(30, 8, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-11-01 00:00:00', 'cleared'),
(31, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Kasanyangan', '2024-11-12 00:00:00', 'cleared'),
(40, 2, 'admin', 'Evacuation center has been inactive: Zamcelco', '2024-11-12 00:00:00', 'cleared'),
(58, 2, 'admin', 'Evacuation Center WMSU12 Updated to WMSU in Barangay Guiwan', '2024-11-13 00:00:00', 'cleared'),
(59, 2, 'admin', 'Evacuation Center WMSU Updated to WMSU Main Campus in Barangay Guiwan', '2024-11-13 00:00:00', 'viewed'),
(60, 2, 'admin', 'Evacuation Center WMSU Main Campus Updated to WMSU.', '2024-11-13 00:00:00', 'viewed'),
(61, 1, 'admin', 'Evacuation Center WMSU Main Campus Updated to WMSU in Barangay Guiwan', '2024-11-13 00:00:00', 'cleared'),
(62, 2, 'admin', 'Evacuation Center WMSU Updated to WMSU Main Campus.', '2024-11-13 00:00:00', 'viewed'),
(63, 2, 'admin', 'Evacuation center has been inactive: ZCHS', '2024-11-14 00:00:00', 'viewed'),
(64, 2, 'admin', 'Evacuation center has been inactive: Zamboanga City High School Main', '2024-11-15 21:43:32', 'viewed'),
(65, 1, 'worker', 'test', '2024-11-15 21:43:32', 'notify'),
(66, 2, 'admin', 'Evacuation center has been inactive: Don Gems', '2024-11-18 02:12:55', 'viewed'),
(67, 2, 'admin', 'Evacuation center has been inactive: Avengers Tower', '2024-11-18 02:12:55', 'viewed'),
(68, 2, 'admin', 'Evacuation center has been inactive: Western Mindanao State University Main Campus', '2024-11-18 02:12:55', 'viewed'),
(69, 2, 'admin', 'Peter D Parker II is requesting to transfer to Don Gems', '2024-11-18 10:52:48', 'viewed'),
(70, 2, 'admin', 'Maloi D. Ricalde is requesting to transfer to Zamboanga City High School Main', '2024-11-18 13:40:47', 'viewed'),
(71, 2, 'admin', 'Sam D. Cena III is requesting to transfer to Don Gems', '2024-11-18 14:02:02', 'viewed'),
(72, 2, 'admin', 'Samkuragi D. Haruko is requesting to transfer to Western Mindanao State University Main Campus', '2024-11-18 14:27:38', 'viewed'),
(73, 1, 'admin', 'New Admin Account Added: Test', '2024-11-14 00:00:00', 'notify'),
(74, 8, 'admin', 'Evacuation center has been inactive: Barangay Hall of Bugguk', '2024-11-20 17:27:40', 'viewed'),
(75, 8, 'admin', 'Evacuation center has been inactive: Basketball Court of Bugguk', '2024-11-20 17:27:40', 'viewed'),
(76, 2, 'admin', 'Sam Cena is requesting to transfer to Western Mindanao State University Main Campus', '2024-11-21 20:09:14', 'viewed'),
(77, 1, 'admin', 'New Admin Account Added: Chinatsu  Kano', '2024-11-24 23:04:47', 'notify'),
(78, 2, 'admin', 'New Worker Account Added: Taiki  Inomata', '2024-11-25 00:39:03', 'viewed'),
(79, 2, 'admin', 'New Evacuation Center Added: Gymnasium', '2024-11-25 01:02:44', 'viewed'),
(80, 1, 'admin', 'New Evacuation Center Added: Gymnasium at Barangay: Guiwan', '2024-11-25 01:02:44', 'notify'),
(81, 1, 'admin', 'New Admin Account Added: Maloi D Ricalde', '2024-11-25 12:46:07', 'notify'),
(82, 2, 'admin', 'New Worker Account Added: Maloi D Arceta', '2024-11-25 13:10:25', 'viewed'),
(83, 2, 'admin', 'Evacuation center has been inactive: Avengers Tower', '2024-11-27 08:49:37', 'viewed'),
(84, 2, 'admin', 'Evacuation center has been inactive: Western Mindanao State University Main Campus', '2024-11-27 08:49:37', 'viewed'),
(85, 2, 'admin', 'Evacuation center has been inactive: Zamcelco', '2024-11-27 08:49:37', 'viewed'),
(86, 2, 'admin', 'New Worker Account Added: Haruko  Akagi', '2024-11-27 17:32:20', 'viewed'),
(87, 8, 'admin', 'Evacuation center has been inactive: Barangay Hall of Bugguk', '2024-11-28 19:15:56', 'viewed'),
(88, 8, 'admin', 'Evacuation center has been inactive: Basketball Court of Bugguk', '2024-11-28 19:15:56', 'viewed'),
(89, 2, 'admin', 'Evacuation center has been inactive: Don Gems', '2024-11-29 16:02:42', 'viewed'),
(90, 2, 'admin', 'Evacuation center has been inactive: Western Mindanao State University', '2024-11-29 18:15:49', 'viewed'),
(91, 2, 'admin', 'Sam123 D. Cena is requesting to transfer to Zamcelco', '2024-12-02 12:32:55', 'viewed'),
(92, 8, 'admin', 'Samkuragi D. Smile is requesting to transfer to Basketball Court of Bugguk', '2024-12-02 12:36:00', 'viewed'),
(93, 2, 'admin', 'asdasd sdasd Cenaaa is requesting to transfer to Avengers Tower', '2024-12-02 12:39:25', 'viewed'),
(94, 2, 'admin', 'Sammy1 D. Dragon is requesting to transfer to Zamcelco', '2024-12-02 12:57:16', 'viewed'),
(95, 2, 'admin', 'Samkuragi D. Haruko is requesting to transfer to Don Gems', '2024-12-02 13:01:11', 'viewed'),
(96, 2, 'admin', 'Maloi D. Ricaldedddddddd is requesting to transfer to Barangay Hall', '2024-12-02 13:04:15', 'viewed'),
(97, 2, 'admin', 'Evacuation center has been inactive: Gymnasium', '2024-12-03 09:21:09', 'viewed'),
(98, 2, 'admin', 'Jimmy Butler is requesting to transfer to Don Gems', '2024-12-03 14:00:41', 'viewed'),
(99, 8, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-12-05 03:16:28', 'viewed'),
(100, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Kasanyangan', '2024-12-05 03:16:28', 'notify'),
(101, 8, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-12-05 03:23:55', 'viewed'),
(102, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Kasanyangan', '2024-12-05 03:23:55', 'notify'),
(103, 8, 'admin', 'New Evacuation Center Added: Don Gems', '2024-12-05 03:24:52', 'viewed'),
(104, 1, 'admin', 'New Evacuation Center Added: Don Gems at Barangay: Kasanyangan', '2024-12-05 03:24:52', 'notify'),
(105, 8, 'admin', 'New Evacuation Center Added: Don Gemssda', '2024-12-05 03:25:40', 'viewed'),
(106, 1, 'admin', 'New Evacuation Center Added: Don Gemssda at Barangay: Kasanyangan', '2024-12-05 03:25:40', 'notify'),
(107, 2, 'admin', 'New Evacuation Center Added: Way Akkal', '2024-12-05 20:28:11', 'viewed'),
(108, 1, 'admin', 'New Evacuation Center Added: Way Akkal at Barangay: Guiwan', '2024-12-05 20:28:11', 'notify'),
(109, 8, 'admin', 'Micheal D. Cena is requesting to transfer to Grandstand', '2024-12-05 21:10:02', 'viewed'),
(110, 8, 'admin', 'Odin  Asgard is requesting to transfer to Grandstand', '2024-12-05 21:12:40', 'viewed'),
(111, 2, 'admin', 'Evacuation center has been inactive: Avengers Tower', '2024-12-05 21:13:01', 'viewed'),
(112, 8, 'admin', 'Lalisa  Manoban is requesting to transfer to Grandstand', '2024-12-05 21:21:34', 'viewed'),
(113, 2, 'admin', 'Sam D. Ricalde is requesting to transfer to Don Gems', '2024-12-05 21:25:13', 'viewed'),
(114, 8, 'admin', 'Samkuragi D. Akagami is requesting to transfer to Grandstand', '2024-12-05 21:35:42', 'viewed'),
(115, 2, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-12-05 21:51:04', 'viewed'),
(116, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Guiwan', '2024-12-05 21:51:04', 'notify'),
(117, 8, 'admin', 'Jimmy D Butler is requesting to transfer to Grandstand', '2024-12-05 22:08:21', 'viewed'),
(118, 2, 'admin', 'Tony D Stark is requesting to transfer to Zamboanga City High School Main', '2024-12-05 22:29:01', 'viewed'),
(119, 8, 'admin', 'Tony D Stark is requesting to transfer to Basketball Court of Bugguk', '2024-12-05 22:41:13', 'viewed'),
(120, 8, 'admin', 'Lalisa  Manoban is requesting to transfer to Barangay Hall of Bugguk', '2024-12-08 11:38:06', 'viewed'),
(121, 2, 'admin', 'Evacuation center has been inactive: Western Mindanao State University', '2024-12-08 11:38:44', 'viewed'),
(122, 8, 'admin', 'Lalisa  Manoban is requesting to transfer to Grandstand', '2024-12-08 11:49:39', 'notify'),
(123, 2, 'admin', 'John D. Cena is requesting to transfer to Don Gems', '2024-12-09 21:06:16', 'viewed'),
(124, 2, 'admin', 'Lalisa d Manoban is requesting to transfer to Avengers Tower', '2024-12-10 21:10:52', 'viewed'),
(125, 2, 'admin', 'John D. Cena is requesting to transfer to Zamcelco', '2024-12-10 21:25:01', 'viewed'),
(126, 1, 'admin', 'New Admin Account Added: Maloiiiss D Arceta', '2024-12-10 22:22:46', 'notify'),
(127, 2, 'admin', 'Evacuation center has been inactive: Gymnasium', '2024-12-11 19:48:13', 'viewed'),
(128, 2, 'admin', 'Jimmy D Butler is requesting to transfer to Don Gems', '2024-12-11 22:17:44', 'viewed'),
(129, 2, 'admin', 'Jimmy D Butler is requesting to transfer to Avengers Tower', '2024-12-11 22:23:04', 'viewed'),
(130, 2, 'admin', 'Derrick  Rose is requesting to transfer to Zamboanga City High School Main', '2024-12-11 22:31:29', 'viewed'),
(131, 2, 'admin', 'Sam D. Ricalde is requesting to transfer to Barangay Hall', '2024-12-12 00:14:38', 'viewed'),
(132, 3, 'admin', 'New Evacuation Center Added: Don Gems', '2024-12-12 10:46:48', 'notify'),
(133, 1, 'admin', 'New Evacuation Center Added: Don Gems at Barangay: Shohoku', '2024-12-12 10:46:48', 'notify'),
(134, 8, 'admin', 'Evacuation center has been inactive: Grandstand', '2024-12-15 23:29:08', 'notify'),
(135, 2, 'admin', 'Evacuation center has been inactive: Way Akkal', '2024-12-15 23:29:08', 'viewed'),
(136, 2, 'admin', 'Evacuation center has been inactive: Barangay Hall', '2024-12-15 23:29:08', 'viewed'),
(137, 2, 'admin', 'Evacuation center has been inactive: Western Mindanao State University', '2025-01-07 18:57:01', 'viewed'),
(138, 2, 'admin', 'Evacuation center has been inactive: Gymnasium', '2025-01-07 18:57:01', 'viewed'),
(139, 8, 'admin', 'Evacuation center has been inactive: Grandstand', '2025-01-07 18:57:01', 'notify'),
(140, 2, 'admin', 'Evacuation center has been inactive: Way Akkal', '2025-01-07 18:57:01', 'viewed'),
(141, 2, 'admin', 'Evacuation center has been inactive: Barangay Hall', '2025-01-07 18:57:01', 'viewed'),
(142, 3, 'admin', 'Evacuation center has been inactive: Don Gems', '2025-01-07 18:57:01', 'notify'),
(143, 2, 'admin', 'New Worker Account Added: aa asa Arceta', '2025-01-09 22:50:26', 'viewed'),
(144, 2, 'admin', 'New Worker Account Added: sda sdas asd', '2025-01-09 22:53:51', 'viewed'),
(145, 3, 'admin', 'New Worker Account Added: Maloi  Ryota', '2025-01-10 02:19:34', 'notify'),
(146, 28, 'admin', 'Evacuation center has been inactive: TESTEST2', '2025-01-12 23:12:54', 'notify'),
(147, 2, 'admin', 'Evacuation Center Deleted: Don Gems', '2025-01-12 23:19:44', 'viewed'),
(148, 1, 'admin', 'Evacuation Center Deleted: Don Gems at Barangay: Guiwan', '2025-01-12 23:19:44', 'notify'),
(149, 2, 'admin', 'Evacuation Center Deleted: Zamboanga City High School Main', '2025-01-12 23:20:01', 'viewed'),
(150, 1, 'admin', 'Evacuation Center Deleted: Zamboanga City High School Main at Barangay: Guiwan', '2025-01-12 23:20:01', 'notify'),
(151, 2, 'admin', 'Evacuation Center Deleted: Avengers Tower', '2025-01-12 23:23:55', 'viewed'),
(152, 1, 'admin', 'Evacuation Center Deleted: Avengers Tower at Barangay: Tetuan', '2025-01-12 23:23:55', 'notify'),
(153, 2, 'admin', 'Evacuation Center Deleted: Barangay Hall', '2025-01-12 23:24:03', 'viewed'),
(154, 1, 'admin', 'Evacuation Center Deleted: Barangay Hall at Barangay: Tetuan', '2025-01-12 23:24:03', 'notify'),
(155, 2, 'admin', 'Evacuation Center Deleted: Western Mindanao State University', '2025-01-12 23:24:11', 'viewed'),
(156, 1, 'admin', 'Evacuation Center Deleted: Western Mindanao State University at Barangay: Tetuan', '2025-01-12 23:24:11', 'notify'),
(157, 2, 'admin', 'Evacuation Center Deleted: Zamcelco', '2025-01-12 23:24:17', 'viewed'),
(158, 1, 'admin', 'Evacuation Center Deleted: Zamcelco at Barangay: Tetuan', '2025-01-12 23:24:17', 'notify'),
(159, 2, 'admin', 'Evacuation Center Deleted: Gymnasium', '2025-01-12 23:24:25', 'viewed'),
(160, 1, 'admin', 'Evacuation Center Deleted: Gymnasium at Barangay: Tetuan', '2025-01-12 23:24:25', 'notify'),
(161, 2, 'admin', 'Evacuation Center Deleted: Way Akkal', '2025-01-12 23:24:33', 'viewed'),
(162, 1, 'admin', 'Evacuation Center Deleted: Way Akkal at Barangay: Tetuan', '2025-01-12 23:24:33', 'notify'),
(163, 2, 'admin', 'New Evacuation Center Added: Tetuan Central School', '2025-01-12 23:29:59', 'viewed'),
(164, 1, 'admin', 'New Evacuation Center Added: Tetuan Central School at Barangay: ', '2025-01-12 23:29:59', 'notify'),
(165, 2, 'admin', 'Sasuke  Uchiha is requesting to transfer to Barangay Hall', '2025-01-12 23:36:30', 'viewed'),
(166, 2, 'admin', 'Evacuation center has been inactive: Tetuan Central School', '2025-03-20 16:13:42', 'viewed'),
(167, 1, 'admin', 'New Admin Account Added: Los Eli  Angeles', '2025-03-20 16:21:33', 'notify'),
(168, 2, 'admin', 'Evacuation center has been inactive: Tetuan Central School', '2025-04-29 17:59:13', 'viewed'),
(169, 2, 'admin', 'Evacuation Center Barangay Hall Updated to Barangay Hall.', '2025-04-30 18:02:26', 'viewed'),
(170, 1, 'admin', 'Evacuation Center Barangay Hall Updated to Barangay Hall in Barangay Tetuan', '2025-04-30 18:02:26', 'notify'),
(171, 2, 'admin', 'New Evacuation Center Added: Higschool Main', '2025-05-02 09:41:18', 'notify'),
(172, 1, 'admin', 'New Evacuation Center Added: Higschool Main at Barangay: Tetuan', '2025-05-02 09:41:18', 'notify'),
(173, 2, 'admin', 'Evacuation Center Deleted: Tetuan Central School', '2025-05-02 09:41:24', 'notify'),
(174, 1, 'admin', 'Evacuation Center Deleted: Tetuan Central School at Barangay: Tetuan', '2025-05-02 09:41:24', 'notify');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `from` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `original_quantity` int(11) NOT NULL DEFAULT 0,
  `unit` enum('piece','pack') NOT NULL,
  `supply_id` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `date`, `time`, `from`, `quantity`, `original_quantity`, `unit`, `supply_id`, `approved`) VALUES
(8, '2024-12-11', '21:53:00', 'Tabotabo', 21, 22, 'pack', 35, 0);

-- --------------------------------------------------------

--
-- Table structure for table `supply`
--

CREATE TABLE `supply` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `original_quantity` int(11) NOT NULL DEFAULT 0,
  `unit` enum('piece','pack') NOT NULL DEFAULT 'piece',
  `image` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `from` varchar(255) NOT NULL,
  `evacuation_center_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supply`
--

INSERT INTO `supply` (`id`, `name`, `description`, `quantity`, `original_quantity`, `unit`, `image`, `date`, `time`, `from`, `evacuation_center_id`, `category_id`, `approved`) VALUES
(35, 'Starter Kit', 'Required before moving out.', 0, 0, 'pack', '', '2024-12-05', '21:51:04', 'Barangay', 26, 14, 1),
(39, 'Canton', '', 0, 1, 'pack', '../../assets/uploads/supplies/canton.png', '2025-01-12', '23:34:00', 'DSWD', 26, 1, 1),
(40, 'Starter Kit', 'Required before moving out.', 0, 0, 'pack', '', '2025-05-02', '09:41:18', 'Barangay', 31, 14, 1);

-- --------------------------------------------------------

--
-- Table structure for table `worker`
--

CREATE TABLE `worker` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `extension_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `position` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `proof_image` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `worker`
--

INSERT INTO `worker` (`id`, `email`, `username`, `first_name`, `middle_name`, `last_name`, `extension_name`, `password`, `image`, `birthday`, `age`, `gender`, `position`, `city`, `barangay`, `contact`, `proof_image`, `status`, `last_login`, `verification_code`, `admin_id`) VALUES
(1, 'worker@gmail.com', 'Worker123', 'Ryota', 'D.', 'Miyagi', '', '$2y$10$qgHnhQJT1K2D.hEfuCCb0.PNDlgsSwiZJTFzOq7LAthmftb1bHIMe', '../../assets/uploads/profiles/1733368677_ryota.jpg', '2014-11-20', 30, 'Female', 'Technician', 'Zamboanga City', 'Guiwan', '12345678901', '../../assets/uploads/appointments/1733368677_box.jpg', 'active', '2025-01-12 23:45:08', NULL, 2),
(4, 'bini@gmail.com', 'RyotaTeamManager', 'Ayako', 'D', 'Ryota', '', '$2y$10$FRKQZtVToUBv4S0.tXAVhuXXgnh2VWV0ejI23KzxMCMQOvUB5yi7a', '../../assets/uploads/profiles/ayako.jpg', '2014-11-17', 22, 'Female', 'Team Manager', 'Zamboanga City', 'Guiwan', '12345555', '../../assets/uploads/appointments/1232342112.jpg', 'inactive', '2024-11-10 20:27:53', NULL, 2),
(5, 'binimaloiworker@gmail.com', 'InomataBadmintonPlayer', 'Taiki', '', 'Inomata', '', '$2y$10$ppSCKg5seNV8IWbL.m9PtuT8ZTX1RVTo8taBg3Gri1NOeN/QZgB06', '../../assets/uploads/profiles/haruko.jpg', '1998-07-06', 26, 'Male', 'Badminton Player', 'Zamboanga City', 'Guiwan', '123456', '../../assets/uploads/appointments/download (2).jpg', 'inactive', '2024-11-25 00:40:20', NULL, 2),
(7, 'binimaloi35@gmail.com', 'HarukoMyLoves', 'Haruko', '', 'Akagi', '', '$2y$10$J.IJfrP6PamNlzSM3ooB6.Us3wt8tkjBOqz1VVinbesta0Hk/GHaK', '../../assets/uploads/profiles/haruko.jpg', '2003-02-13', 21, 'Male', 'Barangay Captain', 'Zamboanga City', 'Guiwan', '1221323', '../../assets/uploads/appointments/haruko.jpg', 'inactive', '2024-12-03 21:51:00', '', 2),
(9, 'binimaloi121312@gmail.com', 'asd21323', 'sda', 'sdas', 'asd', '', '$2y$10$kc23PFfsA/j9vl7Xh/AkduH2Yfy9R7HzKkMM7jWFpG.WpfBCBCwdW', '', '2018-01-08', 7, 'Male', '21323', 'Zamboanga City', 'Guiwan', '121', '../../assets/uploads/appointments/Screenshot 2025-01-09 210107.png', 'active', '2025-01-09 22:54:52', NULL, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assigned_worker`
--
ALTER TABLE `assigned_worker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assigned_worker_worker` (`worker_id`),
  ADD KEY `fk_assigned_worker_evacuation_center` (`evacuation_center_id`);

--
-- Indexes for table `barangay`
--
ALTER TABLE `barangay`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category_admin` (`admin_id`);

--
-- Indexes for table `distribute`
--
ALTER TABLE `distribute`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_distribute_evacuees` (`evacuees_id`),
  ADD KEY `fk_distribute_supply` (`supply_id`);

--
-- Indexes for table `evacuation_center`
--
ALTER TABLE `evacuation_center`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_evacuation_center_admin` (`admin_id`);

--
-- Indexes for table `evacuees`
--
ALTER TABLE `evacuees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_evacuees_admin` (`admin_id`),
  ADD KEY `fk_evacuees_evacuation_center` (`evacuation_center_id`);

--
-- Indexes for table `evacuees_log`
--
ALTER TABLE `evacuees_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_evacuees_log_evacuees` (`evacuees_id`);

--
-- Indexes for table `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_members_evacuees` (`evacuees_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`token`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stock_supply` (`supply_id`);

--
-- Indexes for table `supply`
--
ALTER TABLE `supply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_supply_evacuation_center` (`evacuation_center_id`),
  ADD KEY `fk_supply_category` (`category_id`);

--
-- Indexes for table `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_worker_admin` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `assigned_worker`
--
ALTER TABLE `assigned_worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `barangay`
--
ALTER TABLE `barangay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `distribute`
--
ALTER TABLE `distribute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `evacuation_center`
--
ALTER TABLE `evacuation_center`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `evacuees`
--
ALTER TABLE `evacuees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `evacuees_log`
--
ALTER TABLE `evacuees_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=288;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `supply`
--
ALTER TABLE `supply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `worker`
--
ALTER TABLE `worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_worker`
--
ALTER TABLE `assigned_worker`
  ADD CONSTRAINT `fk_assigned_worker_evacuation_center` FOREIGN KEY (`evacuation_center_id`) REFERENCES `evacuation_center` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_assigned_worker_worker` FOREIGN KEY (`worker_id`) REFERENCES `worker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `distribute`
--
ALTER TABLE `distribute`
  ADD CONSTRAINT `fk_distribute_evacuees` FOREIGN KEY (`evacuees_id`) REFERENCES `evacuees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_distribute_supply` FOREIGN KEY (`supply_id`) REFERENCES `supply` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evacuation_center`
--
ALTER TABLE `evacuation_center`
  ADD CONSTRAINT `fk_evacuation_center_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evacuees`
--
ALTER TABLE `evacuees`
  ADD CONSTRAINT `fk_evacuees_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_evacuees_evacuation_center` FOREIGN KEY (`evacuation_center_id`) REFERENCES `evacuation_center` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evacuees_log`
--
ALTER TABLE `evacuees_log`
  ADD CONSTRAINT `fk_evacuees_log_evacuees` FOREIGN KEY (`evacuees_id`) REFERENCES `evacuees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `fk_members_evacuees` FOREIGN KEY (`evacuees_id`) REFERENCES `evacuees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_supply` FOREIGN KEY (`supply_id`) REFERENCES `supply` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supply`
--
ALTER TABLE `supply`
  ADD CONSTRAINT `fk_supply_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_supply_evacuation_center` FOREIGN KEY (`evacuation_center_id`) REFERENCES `evacuation_center` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `worker`
--
ALTER TABLE `worker`
  ADD CONSTRAINT `fk_worker_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
