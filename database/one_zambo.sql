-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2024 at 02:31 AM
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
  `gender` varchar(50) NOT NULL,
  `position` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `barangay_logo` varchar(255) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `role` enum('admin','superadmin') NOT NULL,
  `proof_image` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `username`, `password`, `image`, `gender`, `position`, `city`, `barangay`, `barangay_logo`, `contact`, `role`, `proof_image`, `status`, `last_login`, `verification_code`) VALUES
(1, 'Mitsuyoshi', 'D.', 'Anzai', 'II', 'superadmin@gmail.com', 'superadmin12', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/Mitsuyoshi Anzai.jpg', '', '', '', '', NULL, '0', 'superadmin', '', 'active', '2024-11-13 13:33:58', NULL),
(2, 'Hanamichi', 'D.', 'Sakuragi', '', 'admin@gmail.com', 'admin123', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/sakuragi.png', 'Male', 'Captain', 'Zamboanga', 'Guiwan', NULL, '2147483647', 'admin', '../../assets/uploads/appointments/journal format.png', 'active', '2024-11-14 08:18:44', ''),
(3, 'Kaede', 'D', 'Rukawa', '', 'binimaloi@gmail.com', 'RukawaAdmin', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/rukawa.jpg', 'Male', 'Ace Player', 'Zamboanga City', 'Shohoku', NULL, '2147483647', 'admin', '../../assets/uploads/appointments/bini.jpg', 'inactive', '2024-11-04 22:36:44', ''),
(4, 'Maloi', 'D', 'Ricalde', '', 'kaizoku@gmail.com', 'RicaldeAdmin', '$2y$10$.cQt6NadI2oLMwFn3Ne2T.gvb.fUGwA0A06ICcgeOpvmqvX2s4Obm', '../../assets/uploads/profiles/bini_maloi.png', 'Female', 'Main Vocalist', 'Zamboanga City', 'Kasanyangan', NULL, '09551078233', 'admin', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'active', '2024-11-08 23:02:03', NULL),
(5, 'Aiah', 'D', 'Arceta', '', 'masterjho@gmail.com', 'ArcetaAdmin', '$2y$10$t8XryzbGRzJaEa6PW4Zz6e/fopHBGky8oTRhaN/sbgMI21EdM49q.', '', 'Female', 'Main Visual', 'Zamboanga City', 'Talon-talon', NULL, '123456789', 'admin', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'active', '2024-11-08 23:27:32', NULL),
(6, 'Mikha', 'D', 'Lim', '', 'samcena@gmail.com', 'LimAdmin', '$2y$10$0K8XeBqdvMj0I.iaEF4hSOId39U7kYlZCTgF1LJHOyOUUdhJTAKda', '../../assets/uploads/profiles/bini_mikha.png', 'Female', 'Main Rapper', 'Zamboanga City', 'Sta.Maria', '', '09881078332', 'admin', '../../assets/uploads/appointments/1.png', 'active', '2024-11-08 23:36:17', NULL),
(7, 'Colet', 'D', 'Vergara', '', 'binimaloi2@gmail.com', 'VergaraAdmin', '$2y$10$QcOe1IvngDYKv4TJB6EdQOqgLcYOvfG3C3FBGws.gowDxrdbZLGg.', '../../assets/uploads/profiles/bini_colet.png', 'Female', 'Lead Vocalist', 'Zamboanga City', 'San Roque', '../../assets/uploads/barangay/3.png', '0988106322', 'admin', '../../assets/uploads/appointments/bini.jpg', 'active', NULL, ''),
(8, 'Mark', 'D', 'Tabotabo', '', 'binimaloi352@gmail.com', 'TabotaboAdmin', '$2y$10$YrJvTubDon2uf/MWbVFWsuZFuUihNV3xtCR5wqFDdE3j/w13oKQYO', '../../assets/uploads/profiles/archi.jpg', 'Male', 'Programmer', 'Zamboanga City', 'Kasanyangan', '../../assets/uploads/barangay/recipic.png', '0955107334', 'admin', '../../assets/uploads/appointments/1232342112.jpg', 'active', '2024-11-12 21:11:52', NULL);

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
(11, '2024-11-10 15:54:24', 'assigned', 1, 1),
(14, '2024-11-10 16:46:29', 'assigned', 1, 2),
(16, '2024-11-13 13:53:48', 'assigned', 1, 5),
(17, '2024-11-13 13:54:16', 'assigned', 4, 5);

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
(2, 'Soft Drinks', 2),
(3, 'Clothes', 2),
(5, 'Ayuda Pack', 2),
(6, 'Buffet', 2),
(7, 'Catering', 2),
(8, 'Free Lugaw', 2);

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

INSERT INTO `distribute` (`id`, `supply_name`, `date`, `evacuees_id`, `supply_id`, `quantity`) VALUES
(1, 'Supply', '2024-11-15 08:25:06', 6, 3, 0),
(2, 'Supply', '2024-11-15 08:32:03', 6, 3, 20),
(3, 'Supply', '2024-11-15 08:32:47', 6, 3, 4),
(4, 'Supply', '2024-11-15 08:36:56', 6, 3, 21),
(5, 'Supply', '2024-11-15 08:37:31', 6, 3, 28),
(6, 'Supply', '2024-11-15 08:38:09', 6, 3, 28),
(7, 'Supply', '2024-11-15 08:41:06', 6, 3, 0),
(8, 'Magi', '2024-11-15 08:55:24', 6, 3, 0),
(9, 'Magi', '2024-11-15 09:05:05', 6, 3, 0),
(10, 'Magi', '2024-11-15 09:08:56', 6, 3, 13);

-- --------------------------------------------------------

--
-- Table structure for table `evacuation_center`
--

CREATE TABLE `evacuation_center` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `evacuation_center`
--

INSERT INTO `evacuation_center` (`id`, `name`, `location`, `capacity`, `admin_id`, `image`, `created_at`) VALUES
(1, 'Don Gems', 'Grandline', '122', 2, '../../assets/uploads/evacuation_centers/spirited_away_by_snatti89_ddj15iy.jpg', '2024-11-10 11:18:41'),
(2, 'ZCHS', 'Grandline', '122', 2, '', '2024-11-06 11:18:41'),
(3, 'Avengers Tower', 'New York', '4', 2, '../../assets/uploads/evacuation_centers/one_piece_scene_zoro_by_uoa7_d5hhg5y.jpg', '2024-11-10 11:18:41'),
(4, 'Barangay Hall', 'Bugguk', '20', 2, '', '2024-11-01 12:15:50'),
(5, 'WMSU Main Campus', 'Guiwan Street', '40', 2, '../../assets/uploads/evacuation_centers/ScreenShot-2022-9-8_20-32-54.png', '2024-11-10 12:18:59'),
(10, 'Zamcelco', 'Di Makita Street', '22', 2, '../../assets/uploads/evacuation_centers/weathering with you.jpg', '2024-11-04 10:29:54'),
(11, 'Barangay Hall of Bugguk', 'Kasanyangan', '22', 8, '../../assets/uploads/evacuation_centers/the_valley_of_the_wind__nausicaa_by_syntetyc_d5wb09s-fullview.jpg', '2024-11-12 11:12:18');

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
  `date` date DEFAULT curdate(),
  `status` enum('Admitted','Transfer','Transferred','Moved-out') NOT NULL DEFAULT 'Admitted'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `evacuees`
--

INSERT INTO `evacuees` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `gender`, `position`, `disaster_type`, `barangay`, `birthday`, `age`, `occupation`, `contact`, `monthly_income`, `damage`, `cost_damage`, `house_owner`, `admin_id`, `evacuation_center_id`, `date`, `status`) VALUES
(1, 'Sam', 'D.', 'Cena', '', 'female', 'Owner', 'Flood', 'Guiwan', '2024-11-13', 22, 'Idol', '09092738051', '12223', 'totally', '11111', 'Colet Vergara', 2, 1, '2024-11-12', 'Moved-out'),
(2, 'Sammy', 'D.', 'Dragon', '', 'Male', 'Owner', 'Flood', 'Guiwan', '2024-11-10', 22, 'Idol', '09278935682', '20000', 'totally', '10000', 'Colet Vergara', 2, 2, '2024-11-12', 'Admitted'),
(3, 'Samkuragi', 'D.', 'Smile', '', 'Male', 'Sharer', 'Flood', 'Guiwan', '2024-11-21', 22, 'Idol', '09116464286', '11', 'totally', '111', 'Colet Vergara', 2, 3, '2024-11-12', 'Admitted'),
(4, 'Samkuragi', 'D.', 'Smile', '', 'Male', 'Sharer', 'Flood', 'Guiwan', '2024-11-21', 22, 'Idol', '09745514414', '11', 'totally', '111', 'Colet Vergara', 2, 3, '2024-11-12', 'Admitted'),
(5, 'Samkuragi', 'D.', 'Smile', '', 'Male', 'Sharer', 'Flood', 'Guiwan', '2024-11-21', 22, 'Idol', '09378179246', '11', 'totally', '111', 'Colet Vergara', 2, 3, '2024-11-12', 'Admitted'),
(6, 'Peter', 'D', 'Parker', 'II', 'Male', 'Owner', 'Pandemic', 'Guiwan', '2005-02-02', 12, 'Superhero', '12345696969', '20', 'totally', '69', 'Peter D Parker II', 2, 5, '2024-11-12', 'Admitted'),
(7, 'Tony', 'D', 'Stark', '', 'Male', 'Owner', 'Fire', 'Guiwan', '2018-02-12', 20, 'Billionaire', '123456789', '999999999999', 'totally', '69', 'Tony D Stark', 2, 3, '2024-11-12', 'Admitted');

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
(18, 2, 'admin', '13 packs of Magi distributed to Parker', '2024-11-15 09:08:56', 'notify');


-- --------------------------------------------------------

--
-- Table structure for table `evacuees_log`
--

CREATE TABLE `evacuees_log` (
  `id` int(11) NOT NULL,
  `log_msg` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('notify','cleared') NOT NULL,
   `evacuees_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `occupation` varchar(255) NOT NULL,
  `evacuees_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `relation`, `education`, `gender`, `age`, `occupation`, `evacuees_id`) VALUES
(1, 'Mikha', 'Bini', 'Lim', '', 'Brother', 'College', 'Male', 23, 'Red Hair Badass', 1),
(2, 'Aiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'male', 23, 'Idol', 2),
(3, 'May', 'D', 'Parker', '', 'Auntie', 'College', 'Female', 33, 'Auntie Wife', 6),
(4, 'Ben', 'D', 'Parker', '', 'Uncle', 'SHS', 'Male', 44, 'Plumber', 6);

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
(4, 1, 'admin', 'New Admin Account Added: Mark D Tabotabo', '2024-11-10 00:00:00', 'cleared'),
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
(61, 1, 'admin', 'Evacuation Center WMSU Main Campus Updated to WMSU in Barangay Guiwan', '2024-11-13 00:00:00', 'notify'),
(62, 2, 'admin', 'Evacuation Center WMSU Updated to WMSU Main Campus.', '2024-11-13 00:00:00', 'viewed'),
(63, 2, 'admin', 'Evacuation center has been inactive: ZCHS', '2024-11-14 00:00:00', 'viewed');

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
  `supply_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `date`, `time`, `from`, `quantity`, `original_quantity`, `unit`, `supply_id`) VALUES
(1, '2024-11-06', '06:58:00', 'Kobe Bryant', 0, 0, 'pack', 3),
(2, '2024-11-06', '06:58:00', 'Lebron James', 0, 0, 'pack', 3),
(3, '2024-11-14', '07:21:00', 'Bini', 7, 7, 'piece', 17);

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
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supply`
--

INSERT INTO `supply` (`id`, `name`, `description`, `quantity`, `original_quantity`, `unit`, `image`, `date`, `time`, `from`, `evacuation_center_id`, `category_id`) VALUES
(1, 'Mag', '123', 22, 22, 'piece', '', '2024-11-13', '17:47:00', 'Abdul Jakul', 2, 1),
(2, 'Magi', '123', 22, 22, 'piece', '', '2024-11-13', '17:47:00', 'Abdul Jakul', 2, 1),
(3, 'Magi', 'Donated from Singapore', 12, 12, 'pack', '../../assets/uploads/supplies/karimagi.jpg', '2024-11-13', '17:47:00', 'Salsalani', 5, 5),
(4, 'Cat Food', 'Meow', 20, 20, 'pack', '../../assets/uploads/supplies/download (1).jpg', '2024-11-14', '17:53:00', 'Abdul Jakul Salsalani', 5, 5),
(5, 'Chicken', 'Bida ang saya', 22, 22, 'piece', '../../assets/uploads/supplies/download (2).jpg', '2024-11-13', '18:36:00', 'Kentucky Fried Chicken', 5, 6),
(12, 'Ahmad M. Salasain ', 'Sarap mag Canton', 33, 33, '', '', '2024-11-14', '21:00:00', 'Tabotabo', 2, 1),
(13, 'Ahmad M. Salasain ', 'Sarap mag Canton', 33, 33, 'piece', '', '2024-11-14', '21:00:00', 'Tabotabo', 2, 1),
(14, 'Ahmad M. Salasain ', 'Sarap mag Canton', 33, 33, 'pack', '', '2024-11-14', '21:00:00', 'Tabotabo', 2, 1),
(17, 'Maloi', 'Bini', 1, 1, 'piece', '../../assets/uploads/supplies/bini_maloi.png', '2024-11-14', '23:05:00', 'Heart', 5, 1),
(20, 'Ahmad M. Salasain ', 'Bini', 22, 22, 'piece', '', '2024-11-08', '09:40:00', 'Abdul Jakul', 5, 1),
(21, 'Venyz Bangquiao', '123', 33, 33, 'piece', '', '2024-11-07', '09:45:00', 'Tabotabo', 5, 3),
(22, 'Nadzrin Alhari', '123', 12, 12, 'pack', '', '2024-10-30', '09:48:00', 'Tabotabo', 5, 2);

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

INSERT INTO `worker` (`id`, `email`, `username`, `first_name`, `middle_name`, `last_name`, `extension_name`, `password`, `image`, `age`, `gender`, `position`, `city`, `barangay`, `contact`, `proof_image`, `status`, `last_login`, `verification_code`, `admin_id`) VALUES
(1, 'worker@gmail.com', 'Worker123', 'Ryota', 'D.', 'Miyagi', '', '$2y$10$qgHnhQJT1K2D.hEfuCCb0.PNDlgsSwiZJTFzOq7LAthmftb1bHIMe', '../../assets/uploads/profiles/ryota.jpg', 30, 'Male', 'Technician', 'Zamboanga City', 'Guiwan', '1234567890', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'active', '2024-11-14 08:13:53', NULL, 2),
(4, 'bini@gmail.com', 'RyotaTeamManager', 'Ayako', 'D', 'Ryota', '', '$2y$10$FRKQZtVToUBv4S0.tXAVhuXXgnh2VWV0ejI23KzxMCMQOvUB5yi7a', '../../assets/uploads/profiles/ayako.jpg', 22, 'Female', 'Team Manager', 'Zamboanga City', 'Guiwan', '12345555', '../../assets/uploads/appointments/1232342112.jpg', 'active', '2024-11-10 20:27:53', NULL, 2);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `assigned_worker`
--
ALTER TABLE `assigned_worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `distribute`
--
ALTER TABLE `distribute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `evacuation_center`
--
ALTER TABLE `evacuation_center`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `evacuees`
--
ALTER TABLE `evacuees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supply`
--
ALTER TABLE `supply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `worker`
--
ALTER TABLE `worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
