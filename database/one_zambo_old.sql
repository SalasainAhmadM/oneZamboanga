-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 10:03 PM
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
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `username`, `password`, `image`, `birthday`, `age`, `gender`, `position`, `city`, `barangay`, `barangay_logo`, `contact`, `role`, `proof_image`, `status`, `last_login`, `verification_code`) VALUES
(1, 'Dom', 'D.', 'Kang', '', 'superadmin@gmail.com', 'superadmin', '$2y$10$mF5tn2TulAbw5TFDo87Q8O9N49ohdbGoHkJOStawWbn7jAAob1CGW', '../../assets/uploads/profiles/dom.jpg', '0000-00-00', 0, '', '', '', '', NULL, '0', 'superadmin', '', 'inactive', '2024-11-24 22:53:17', NULL),
(2, 'Jay', '', 'Jo', '', 'admin@gmail.com', 'admin123', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/1733463657_download.jpg', '2004-10-25', 20, 'Male', 'Captain', 'Zamboanga', 'Guiwan', NULL, '2147483647', 'admin', '../../assets/uploads/appointments/journal format.png', 'active', '2024-12-06 07:30:46', ''),
(7, 'Shelly', '', 'Scott', '', 'windbreaker@gmail.com', 'VergaraAdmin', '$2y$10$QcOe1IvngDYKv4TJB6EdQOqgLcYOvfG3C3FBGws.gowDxrdbZLGg.', '../../assets/uploads/profiles/1733463771_Shelly Scott.jpg', '1994-10-25', 30, 'Female', 'Strategist', 'Zamboanga City', 'San Roque', '../../assets/uploads/barangay/3.png', '0988106322', 'admin', '../../assets/uploads/appointments/bini.jpg', 'active', NULL, ''),
(12, 'Mark Larenz', '', 'Tabotabo', '', 'marklarenztabotabo@gmail.com', 'TabotaboAdmin', '$2y$10$7sa8/Mm9kF6jS1tqKqWwDubpQ3Zyk1.Lm47j6abdjw.Sj07jaFUze', '../../assets/uploads/profiles/hero.jpg', '2001-11-15', 23, 'Male', 'Barangay Captain', 'Zamboanga City', 'Tetuan', '', '09352453795', 'admin', '../../assets/uploads/appointments/journal format.png', 'active', '2024-12-12 05:01:30', NULL),
(13, 'Raiza', '', 'Beligolo', '', 'beligoloraiza@gmail.com', 'BeligoloAdmin', '$2y$10$IFeD29N.QKbV79/D/f2KVulO551JkeJ002xUSZK0c5jos3Dba/P6K', '', '2002-11-28', 22, 'Female', 'Barangay Captain', 'Zamboanga City', 'Tugbungan', '', '09776702283', 'admin', '../../assets/uploads/appointments/journal format.png', 'active', '2024-12-12 02:35:21', NULL),
(16, 'Kevin', '', 'Durant', '', 'rodrigojon01@gmail.com', 'DurantAdmin', '$2y$10$35LlAKMQScBqoCafMS.YRuK/x/TnNRmS/ZwTfxPK8MmpGB7Xh/p.a', '', '2000-02-03', 24, 'Male', 'Barangay Captain', 'Zamboanga City', 'Canelar', '', '0938847588', 'admin', '../../assets/uploads/appointments/captain.webp', 'inactive', NULL, NULL);

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
(23, '2024-12-04 21:52:13', 'assigned', 8, 14),
(24, '2024-12-10 22:54:45', 'assigned', 8, 24),
(25, '2024-12-10 23:01:36', 'assigned', 9, 20),
(26, '2024-12-10 23:21:42', 'assigned', 9, 25),
(27, '2024-12-11 17:47:31', 'assigned', 11, 14);

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
(16, 'Tugbungan');

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
(3, 'Clothes', 2),
(5, 'Ayuda Pack', 2),
(14, 'Supply Kit', 2),
(24, 'Food', 12),
(25, 'Starting Kit', 12),
(27, 'Starting Kit', 2),
(28, 'Starting Kit', 13),
(29, 'Food', 13);

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
(104, 'Canton', '2024-12-05 21:00:46', 40, 34, 1, 12, 'admin'),
(105, 'Evacuees Supply Pack', '2024-12-05 21:01:05', 40, 35, 1, 12, 'admin'),
(106, 'Canton', '2024-12-06 07:18:10', 49, 41, 1, 13, 'admin'),
(107, 'Starter Kit', '2024-12-06 07:18:36', 49, 39, 1, 13, 'admin'),
(108, 'Starter Kit', '2024-12-06 14:46:42', 51, 35, 1, 12, 'admin'),
(109, 'Canton', '2024-12-06 14:48:16', 51, 34, 16, 12, 'admin'),
(110, 'Starter Kit', '2024-12-10 23:17:11', 65, 45, 1, 13, 'admin'),
(111, 'Canton', '2024-12-10 23:19:09', 64, 46, 1, 13, 'admin'),
(112, 'Canton', '2024-12-10 23:19:09', 66, 46, 1, 13, 'admin'),
(113, 'Starter Kit', '2024-12-10 23:49:29', 72, 45, 1, 13, 'admin'),
(114, 'Chicken', '2024-12-11 00:18:24', 82, 48, 1, 13, 'admin'),
(115, 'Starter Kit', '2024-12-11 16:47:13', 86, 45, 1, 13, 'admin'),
(116, 'Starter Kit', '2024-12-11 16:47:54', 87, 45, 1, 13, 'admin');

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
(10, 'Zamcelco', 'Di Makita Street', '', '22', 2, '../../assets/uploads/evacuation_centers/weathering with you.jpg', '2024-11-18 10:29:54'),
(13, 'Gymnasium', 'Chico', ' Guiwan', '10', 2, '../../assets/uploads/evacuation_centers/front22.png', '2024-11-24 17:02:44'),
(14, 'Tetuan Central School', ' Dr. J. Estrada', ' Tetuan', '100', 12, '', '2024-12-04 13:30:17'),
(20, 'Barangay Hall', 'Tugbungan Rd', ' Tugbungan', '50', 13, '', '2024-12-05 13:53:32'),
(24, 'Zamboanga City Highschool', 'Alfaro street', ' Tetuan', '20', 12, '', '2024-12-06 06:45:19'),
(25, 'Tugbungan Central School', 'Tugbungan Rd', ' Tugbungan', '3', 13, '', '2024-12-10 15:05:33'),
(26, 'Tugbungan Highschool', 'Tugbungan Rd', ' Tugbungan', '5', 13, '', '2024-12-11 08:33:06'),
(27, 'ICAS', 'tetuan street', ' Tetuan', '3', 12, '', '2024-12-11 09:49:34');

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
  `status` enum('Admitted','Transfer','Transferred','Moved-out','Admit') NOT NULL DEFAULT 'Admitted'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `evacuees`
--

INSERT INTO `evacuees` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `gender`, `position`, `disaster_type`, `barangay`, `birthday`, `age`, `occupation`, `contact`, `monthly_income`, `damage`, `cost_damage`, `house_owner`, `admin_id`, `evacuation_center_id`, `origin_evacuation_center_id`, `date`, `status`) VALUES
(1, 'Mark', 'D.', 'Tabotabo', '', 'Male', 'Owner', 'Flood', 'Guiwan', '1999-02-20', 25, 'Idol', '9092738051', '12223', 'totally', '11111', 'Colet Vergara', 2, 10, 4, '2024-11-12', 'Transfer'),
(2, 'Sammy1', 'D.', 'Dragon', '', 'Male', 'Owner', 'Flood', 'Guiwan', '2024-11-10', 22, 'Idol', '9278935682', '20000', 'totally', '10000', 'Colet Vergara', 2, 10, 5, '2024-11-12', 'Transfer'),
(28, 'Celso', 'Q', 'Lobregat', '', 'Male', 'Owner', 'Fire', 'Guiwan', '2019-01-08', 5, 'Mayor', '322', '34234', 'totally', '', 'Celso Q Lobregat', 2, 10, 10, '2024-12-03', 'Admitted'),
(35, 'Derrick', '', 'Rose', '', 'Female', 'Sharer', 'Flood', 'Guiwan', '1999-08-20', 25, 'Point Guard', '123', '20000', 'totally', '12345', 'Dirk Nowitzki', 2, 13, 1, '2024-12-03', 'Admitted'),
(37, 'Angelica', '', 'Nalzaro', '', 'Female', 'Owner', 'Fire', 'Tetuan', '2001-10-16', 23, 'Student', '9090909090', '0', 'totally', '5000', 'Angelica  Nalzaro', 12, 14, 14, '2024-12-04', 'Admitted'),
(40, 'Kyrie', '', 'Irving', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2003-06-18', 21, 'Basketball player', '9090909090', '5000', 'totally', '10000', 'Kyrie  Irving', 12, 14, 14, '2024-12-05', 'Moved-out'),
(47, 'Charls', '', 'Hermosa', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2009-05-04', 15, 'Student', '9090909090', '0', 'totally', '5000', 'Charls  Hermosa', 12, 14, 14, '2024-12-05', 'Transferred'),
(49, 'Aldwin', '', 'Aguilo', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '2003-07-24', 21, 'Student', '9090909090', '0', 'totally', '5000', 'Aldwin  Aguilo', 13, 20, 14, '2024-12-05', 'Moved-out'),
(50, 'Charls', '', 'Hermosa', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '2009-05-04', 15, 'Student', '9090909090', '0', 'totally', '5000', 'Charls  Hermosa', 13, 20, 15, '2024-12-06', 'Transferred'),
(51, 'Naruto', '', 'Uzumaki', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2001-10-16', 23, 'Ninja', '9090909090', '0', 'totally', '1000', 'Naruto  Uzumaki', 12, 14, 14, '2024-12-06', 'Moved-out'),
(52, 'Itachi', '', 'Uchiha', '', 'Male', 'Owner', 'Flood', 'Tetuan', '1992-06-09', 32, 'Ninja', '9776702283', '0', 'totally', '1000', 'Itachi  Uchiha', 12, 14, 14, '2024-12-06', 'Transferred'),
(53, 'Itachi', '', 'Uchiha', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '1992-06-09', 32, 'Ninja', '9776702283', '0', 'totally', '1000', 'Itachi  Uchiha', 13, 20, 14, '2024-12-06', 'Admitted'),
(54, 'Locine', '', 'Christian', '', 'Female', 'Owner', 'Fire', 'Tetuan', '2001-06-13', 23, 'Student', '9090909090', '0', 'totally', '1000', 'Locine  Christian', 12, 14, 14, '2024-12-06', 'Transferred'),
(55, 'Quecia Mae', '', 'Brilliantes', '', 'Female', 'Owner', 'Flood', 'Tetuan', '2004-06-08', 20, 'student', '9090909090', '0', 'totally', '0', 'Quecia Mae  Brilliantes', 12, 14, 14, '2024-12-06', 'Transfer'),
(56, 'Locine', '', 'Christian', '', 'Female', 'Owner', 'Fire', 'Tetuan', '2001-06-13', 23, 'Student', '9090909090', '0', 'totally', '1000', 'Locine  Christian', 12, 24, 14, '2024-12-06', 'Admitted'),
(57, 'Zild John', '', 'Abule', '', 'Male', 'Owner', 'Fire', 'Tetuan', '2004-06-06', 20, 'Student', '9090909090', '0', 'totally', '1000', 'Zild John  Abule', 12, 14, 14, '2024-12-06', 'Transferred'),
(58, 'Jang', '', 'Gang', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '1999-02-02', 25, 'Student', '9090909090', '0', 'totally', '1000', 'Jang  Gang', 13, 20, 20, '2024-12-06', 'Admitted'),
(59, 'Quecia Mae', '', 'Brilliantes', '', 'Female', 'Owner', 'Flood', 'Tugbungan', '2004-06-08', 20, 'student', '9090909090', '0', 'totally', '0', 'Quecia Mae  Brilliantes', 13, 20, 14, '2024-12-06', 'Transfer'),
(60, 'Charls', '', 'Hermosa', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2009-05-04', 15, 'Student', '9090909090', '0', 'totally', '5000', 'Charls  Hermosa', 12, 14, 20, '2024-12-06', 'Transferred'),
(62, 'Zild John', '', 'Abule', '', 'Male', 'Owner', 'Fire', 'Tetuan', '2004-06-06', 20, 'Student', '9090909090', '0', 'totally', '1000', 'Zild John  Abule', 12, 24, 14, '2024-12-06', 'Transferred'),
(63, 'Zild John', '', 'Abule', '', 'Male', 'Owner', 'Fire', 'Tugbungan', '2004-06-06', 20, 'Student', '9090909090', '0', 'totally', '1000', 'Zild John  Abule', 13, 20, 24, '2024-12-10', 'Admitted'),
(64, 'William', '', 'Santinlo', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '2001-02-06', 23, 'Student', '9090909090', '0', 'totally', '2000', 'William  Santinlo', 13, 25, 25, '2024-12-10', 'Transferred'),
(65, 'Santa', '', 'Cruz', '', 'Male', 'Renter', 'Fire', 'Tugbungan', '2005-05-17', 19, 'Rider', '9352453795', '5000', 'totally', '3000', 'Marko Polo', 13, 25, 25, '2024-12-10', 'Moved-out'),
(66, 'Jose', '', 'Sun', '', 'Male', 'Renter', 'Flood', 'Tugbungan', '2005-02-08', 19, 'Student', '923726340', '0', 'totally', '1000', 'Mika', 13, 25, 25, '2024-12-10', 'Transferred'),
(67, 'Bangbang', '', 'Marcos', '', 'Male', 'Owner', 'Flood', 'Tetuan', '1995-06-06', 29, 'Basketball player', '923726340', '20000', 'totally', '1500', 'Bangbang  Marcos', 12, 14, 14, '2024-12-10', 'Admitted'),
(68, 'Kelra', '', 'Gold', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2006-10-16', 18, 'ML Player', '9776702283', '50000', 'totally', '1000', 'Kelra  Gold', 12, 14, 14, '2024-12-10', 'Admitted'),
(69, 'Jose', '', 'Sun', '', 'Male', 'Renter', 'Flood', 'Tugbungan', '2005-02-08', 19, 'Student', '923726340', '0', 'totally', '1000', 'Mika', 13, 20, 25, '2024-12-10', 'Admitted'),
(70, 'William', '', 'Santinlo', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2001-02-06', 23, 'Student', '9090909090', '0', 'totally', '2000', 'William  Santinlo', 12, 24, 25, '2024-12-10', 'Admitted'),
(71, 'Wally', '', 'Bayola', '', 'Male', 'Owner', 'Fire', 'Tugbungan', '1998-06-10', 26, 'Actor', '923726340', '50000', 'totally', '100000', 'Wally  Bayola', 13, 25, 25, '2024-12-10', 'Transferred'),
(72, 'Jose', '', 'Manny', '', 'Male', 'Owner', 'Fire', 'Tugbungan', '2005-07-14', 19, 'Student', '9352453795', '0', 'totally', '2000', 'Jose  Manny', 13, 25, 25, '2024-12-10', 'Moved-out'),
(73, 'Wally', '', 'Bayola', '', 'Male', 'Owner', 'Fire', 'Tugbungan', '1998-06-10', 26, 'Actor', '923726340', '50000', 'totally', '100000', 'Wally  Bayola', 13, 20, 25, '2024-12-10', 'Admitted'),
(74, 'King', '', 'Kong', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '2000-10-10', 24, 'ML Player', '9352453795', '500000', 'totally', '1000', 'King  Kong', 12, 14, 14, '2024-12-10', 'Admitted'),
(75, 'Jose', '', 'Sun', '', 'Male', 'Renter', 'Flood', 'Tugbungan', '2005-02-08', 19, 'Student', '923726340', '0', 'totally', '1000', 'Mika', 13, 25, 20, '2024-12-10', 'Transferred'),
(76, 'Jose', '', 'Sun', '', 'Male', 'Renter', 'Flood', 'Tugbungan', '2005-02-08', 19, 'Student', '923726340', '0', 'totally', '1000', 'Mika', 13, 25, 20, '2024-12-10', 'Transferred'),
(77, 'Jose', '', 'Sun', '', 'Male', 'Renter', 'Flood', 'Tugbungan', '2005-02-08', 19, 'Student', '923726340', '0', 'totally', '1000', 'Mika', 13, 25, 20, '2024-12-10', 'Transferred'),
(78, 'Itachi', '', 'Uchiha', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '1992-06-09', 32, 'Ninja', '9776702283', '0', 'totally', '1000', 'Itachi  Uchiha', 13, 25, 20, '2024-12-06', 'Transferred'),
(79, 'Itachi', '', 'Uchiha', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '1992-06-09', 32, 'Ninja', '9776702283', '0', 'totally', '1000', 'Itachi  Uchiha', 13, 25, 20, '2024-12-06', 'Transferred'),
(80, 'Itachi', '', 'Uchiha', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '1992-06-09', 32, 'Ninja', '9776702283', '0', 'totally', '1000', 'Itachi  Uchiha', 13, 25, 20, '2024-12-06', 'Transferred'),
(81, 'Charls', '', 'Hermosa', '', 'Male', 'Owner', 'Flood', 'Tetuan', '2009-05-04', 15, 'Student', '9090909090', '0', 'totally', '5000', 'Charls  Hermosa', 12, 24, 14, '2024-12-06', 'Transferred'),
(82, 'Charls', '', 'Hermosa', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '2009-05-04', 15, 'Student', '9090909090', '0', 'totally', '5000', 'Charls  Hermosa', 13, 25, 14, '2024-12-11', 'Admitted'),
(83, 'Shin', '', 'Boo', '', 'Male', 'Owner', 'Fire', 'Tugbungan', '2006-01-11', 18, 'Basketball player', '923726340', '50000', 'totally', '1000', 'Shin  Boo', 12, 24, 24, '2024-12-11', 'Transfer'),
(84, 'Hinata', '', 'Hyuga', '', 'Male', 'Owner', 'Flood', 'Tetuan', '1999-11-17', 25, 'Student', '9352453795', '0', 'totally', '50000', 'Hinata  Hyuga', 12, 14, 14, '2024-12-11', 'Transferred'),
(85, 'Hinata', '', 'Hyuga', '', 'Male', 'Owner', 'Flood', 'Tetuan', '1999-11-17', 25, 'Student', '9352453795', '0', 'totally', '50000', 'Hinata  Hyuga', 12, 24, 14, '2024-12-11', 'Transferred'),
(86, 'Hinata', '', 'Hyuga', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '1999-11-17', 25, 'Student', '9352453795', '0', 'totally', '50000', 'Hinata  Hyuga', 13, 25, 24, '2024-12-11', 'Moved-out'),
(87, 'Rock', '', 'Lee', 'Jr', 'Male', 'Sharer', 'Fire', 'Tugbungan', '2001-01-11', 23, 'Student', '923726340', '0', 'totally', '100000', 'Mr Lee', 13, 25, 25, '2024-12-11', 'Moved-out'),
(88, 'Bruce', '', 'Lee', '', 'Male', 'Owner', 'Earthquake', 'Tugbungan', '2002-06-12', 22, 'Martial Artist', '923726340', '50000', 'totally', '1000', 'Bruce  Lee', 13, 26, 26, '2024-12-11', 'Admitted'),
(89, 'Mohammad', '', 'Ali', '', 'Male', 'Renter', 'Fire', 'Tetuan', '2010-02-12', 14, 'Student', '923726340', '0', 'totally', '0', 'Mr Lee', 12, 24, 24, '2024-12-11', 'Admitted'),
(90, 'Shin', '', 'Boo', '', 'Male', 'Owner', 'Fire', 'Tugbungan', '2006-01-11', 18, 'Basketball player', '923726340', '50000', 'totally', '1000', 'Shin  Boo', 12, 27, 24, '2024-12-11', 'Transfer'),
(91, 'Kyla', '', 'Rico', '', 'Male', 'Owner', 'Flood', 'Tugbungan', '1998-01-12', 26, 'Student', '9352453795', '0', 'totally', '1000', 'Kyla  Rico', 13, 25, 25, '2024-12-12', 'Admitted'),
(92, 'Ethan', '', 'Felix', '', 'Male', 'Owner', 'Fire', 'Tugbungan', '2006-06-13', 18, 'student', '923726340', '0', 'totally', '600', 'Ethan  Felix', 13, 25, 25, '2024-12-12', 'Admitted');

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
(2, 'Admitted', '2024-11-15 16:35:33', 'notify', 1),
(3, 'Admitted', '2024-11-15 16:35:33', 'notify', 2),
(28, 'Moved-out', '2024-11-15 16:35:33', 'notify', 1),
(37, 'Moved-out', '2024-11-15 18:48:17', 'notify', 1),
(38, 'Admitted back', '2024-11-15 18:55:49', 'notify', 1),
(58, 'Updated', '2024-11-17 18:30:37', 'notify', 2),
(59, 'Updated', '2024-11-17 18:38:39', 'notify', 1),
(61, 'Requesting transfer to Barangay Hall', '2024-11-17 20:24:07', 'notify', 1),
(62, 'Transfer approved.', '2024-11-17 20:30:36', 'notify', 1),
(63, 'Requesting transfer to Don Gems', '2024-11-17 20:43:01', 'notify', 1),
(91, 'Updated', '2024-11-18 10:33:57', 'notify', 1),
(111, 'Transfer approved.', '2024-11-18 14:27:19', 'notify', 1),
(121, '1 piece of Shabu have been distributed.', '2024-11-25 01:19:23', 'notify', 2),
(124, 'Requesting transfer to Zamcelco', '2024-12-02 12:32:55', 'notify', 1),
(127, 'Requesting transfer to Zamcelco', '2024-12-02 12:57:16', 'notify', 2),
(143, 'Admitted', '2024-12-03 19:54:23', 'notify', 28),
(151, 'Requesting transfer to Gymnasium', '2024-12-04 13:49:05', 'notify', 35),
(152, 'Transfer approved.', '2024-12-04 13:49:29', 'notify', 35),
(177, 'Admitted', '2024-12-05 20:57:42', 'notify', 40),
(179, '1 pack of Canton have been distributed.', '2024-12-05 21:00:46', 'notify', 40),
(180, '1 pack of Evacuees Supply Pack have been distributed.', '2024-12-05 21:01:05', 'notify', 40),
(181, 'Moved-out', '2024-12-05 21:01:13', 'notify', 40),
(195, 'Admitted', '2024-12-05 21:30:43', 'notify', 47),
(196, 'Requesting transfer to Zamboanga City Highschool', '2024-12-05 21:30:56', 'notify', 47),
(199, 'Transfer approved.', '2024-12-05 21:31:03', 'notify', 47),
(201, 'Admitted', '2024-12-05 21:59:21', 'notify', 49),
(202, 'Requesting transfer to Barangay Hall', '2024-12-05 21:59:37', 'notify', 49),
(203, 'Transfer approved.', '2024-12-05 22:00:19', 'notify', 49),
(205, 'Requesting transfer to Barangay Hall', '2024-12-06 07:09:38', 'notify', 50),
(206, 'Transfer approved.', '2024-12-06 07:13:47', 'notify', 50),
(208, '1 pack of Canton have been distributed.', '2024-12-06 07:18:10', 'notify', 49),
(209, '1 pack of Starter Kit have been distributed.', '2024-12-06 07:18:36', 'notify', 49),
(210, 'Moved-out', '2024-12-06 07:18:52', 'notify', 49),
(211, 'Admitted back', '2024-12-06 07:19:28', 'notify', 49),
(212, 'Updated', '2024-12-06 07:20:00', 'notify', 49),
(213, 'Moved-out', '2024-12-06 07:26:55', 'notify', 49),
(214, 'Admitted', '2024-12-06 07:35:19', 'notify', 51),
(215, 'Admitted', '2024-12-06 07:36:47', 'notify', 52),
(216, '1 pack of Starter Kit have been distributed.', '2024-12-06 14:46:42', 'notify', 51),
(217, '16 packs of Canton have been distributed.', '2024-12-06 14:48:16', 'notify', 51),
(218, 'Moved-out', '2024-12-06 14:48:32', 'notify', 51),
(219, 'Admitted back', '2024-12-06 14:48:50', 'notify', 51),
(220, 'Moved-out', '2024-12-06 14:49:12', 'notify', 51),
(221, 'Requesting transfer to Barangay Hall', '2024-12-06 14:49:53', 'notify', 52),
(222, 'Requesting transfer to Barangay Hall', '2024-12-06 14:49:53', 'notify', 53),
(223, 'Transfer approved.', '2024-12-06 14:50:54', 'notify', 53),
(224, 'Transfer approved.', '2024-12-06 14:50:54', 'notify', 52),
(225, 'Admitted', '2024-12-06 14:53:09', 'notify', 54),
(226, 'Admitted', '2024-12-06 15:56:46', 'notify', 55),
(227, 'Updated', '2024-12-06 15:57:49', 'notify', 55),
(228, 'Requesting transfer to Zamboanga City Highschool', '2024-12-06 19:28:23', 'notify', 54),
(229, 'Requesting transfer to Zamboanga City Highschool', '2024-12-06 19:28:23', 'notify', 56),
(230, 'Admitted', '2024-12-06 19:30:16', 'notify', 57),
(231, 'Pending for approval', '2024-12-06 19:40:27', 'notify', 58),
(232, 'Requesting transfer to Barangay Hall', '2024-12-06 20:17:19', 'notify', 55),
(233, 'Requesting transfer to Barangay Hall', '2024-12-06 20:17:19', 'notify', 59),
(234, 'Requesting transfer to Tetuan Central School', '2024-12-06 20:24:18', 'notify', 50),
(235, 'Requesting transfer to Tetuan Central School', '2024-12-06 20:24:18', 'notify', 60),
(236, 'Transfer approved.', '2024-12-06 20:25:15', 'notify', 60),
(237, 'Transfer approved.', '2024-12-06 20:25:15', 'notify', 50),
(238, 'Requesting transfer to Zamboanga City Highschool', '2024-12-10 14:35:15', 'notify', 57),
(240, 'Transfer declined.', '2024-12-10 14:39:43', 'notify', 57),
(241, 'Requesting transfer to Zamboanga City Highschool', '2024-12-10 14:40:03', 'notify', 57),
(242, 'Requesting transfer to Zamboanga City Highschool', '2024-12-10 14:40:03', 'notify', 62),
(243, 'Transfer approved.', '2024-12-10 14:41:53', 'notify', 62),
(244, 'Transfer approved.', '2024-12-10 14:41:53', 'notify', 57),
(245, 'Requesting transfer to Barangay Hall', '2024-12-10 14:55:12', 'notify', 62),
(246, 'Requesting transfer to Barangay Hall', '2024-12-10 14:55:12', 'notify', 63),
(247, 'Transfer approved.', '2024-12-10 20:29:54', 'notify', 63),
(248, 'Transfer approved.', '2024-12-10 20:29:54', 'notify', 62),
(249, 'Admission approved.', '2024-12-10 23:02:32', 'notify', 58),
(250, 'Admitted', '2024-12-10 23:07:40', 'notify', 64),
(251, 'Admitted', '2024-12-10 23:09:07', 'notify', 65),
(252, 'Admitted', '2024-12-10 23:11:38', 'notify', 66),
(253, '1 pack of Starter Kit have been distributed.', '2024-12-10 23:17:11', 'notify', 65),
(254, 'Moved-out', '2024-12-10 23:17:38', 'notify', 65),
(255, '1 pack of Canton have been distributed.', '2024-12-10 23:19:09', 'notify', 64),
(256, '1 pack of Canton have been distributed.', '2024-12-10 23:19:09', 'notify', 66),
(257, 'Pending for approval', '2024-12-10 23:22:51', 'notify', 67),
(258, 'Pending for approval', '2024-12-10 23:25:14', 'notify', 68),
(259, 'Admission approved.', '2024-12-10 23:26:16', 'notify', 67),
(260, 'Admission approved.', '2024-12-10 23:26:22', 'notify', 68),
(261, 'Requesting transfer to Barangay Hall', '2024-12-10 23:27:44', 'notify', 66),
(262, 'Requesting transfer to Barangay Hall', '2024-12-10 23:27:44', 'notify', 69),
(263, 'Transfer approved.', '2024-12-10 23:28:09', 'notify', 69),
(264, 'Transfer approved.', '2024-12-10 23:28:09', 'notify', 66),
(265, 'Requesting transfer to Zamboanga City Highschool', '2024-12-10 23:28:45', 'notify', 64),
(266, 'Requesting transfer to Zamboanga City Highschool', '2024-12-10 23:28:45', 'notify', 70),
(267, 'Transfer approved.', '2024-12-10 23:29:35', 'notify', 70),
(268, 'Transfer approved.', '2024-12-10 23:29:35', 'notify', 64),
(269, 'Admitted back', '2024-12-10 23:30:15', 'notify', 65),
(270, 'Moved-out', '2024-12-10 23:31:20', 'notify', 65),
(271, 'Admitted', '2024-12-10 23:40:19', 'notify', 71),
(272, 'Admitted', '2024-12-10 23:44:26', 'notify', 72),
(273, '1 pack of Starter Kit have been distributed.', '2024-12-10 23:49:29', 'notify', 72),
(274, 'Moved-out', '2024-12-10 23:50:00', 'notify', 72),
(275, 'Requesting transfer to Barangay Hall', '2024-12-10 23:50:55', 'notify', 71),
(276, 'Requesting transfer to Barangay Hall', '2024-12-10 23:50:55', 'notify', 73),
(277, 'Transfer approved.', '2024-12-10 23:52:06', 'notify', 73),
(278, 'Transfer approved.', '2024-12-10 23:52:06', 'notify', 71),
(279, 'Pending for approval', '2024-12-10 23:57:04', 'notify', 74),
(280, 'Admission approved.', '2024-12-10 23:59:47', 'notify', 74),
(281, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:06:36', 'notify', 69),
(282, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:06:36', 'notify', 75),
(283, 'Transfer approved.', '2024-12-11 00:07:19', 'notify', 69),
(284, 'Transfer approved.', '2024-12-11 00:07:19', 'notify', 75),
(285, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:08:41', 'notify', 69),
(286, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:08:41', 'notify', 76),
(287, 'Transfer approved.', '2024-12-11 00:09:19', 'notify', 69),
(288, 'Transfer approved.', '2024-12-11 00:09:19', 'notify', 76),
(289, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:11:23', 'notify', 69),
(290, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:11:23', 'notify', 77),
(291, 'Transfer approved.', '2024-12-11 00:11:30', 'notify', 69),
(292, 'Transfer approved.', '2024-12-11 00:11:30', 'notify', 77),
(293, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:12:03', 'notify', 53),
(294, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:12:03', 'notify', 78),
(295, 'Transfer approved.', '2024-12-11 00:12:14', 'notify', 53),
(296, 'Transfer approved.', '2024-12-11 00:12:14', 'notify', 78),
(297, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:12:51', 'notify', 53),
(298, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:12:51', 'notify', 79),
(299, 'Transfer approved.', '2024-12-11 00:13:07', 'notify', 53),
(300, 'Transfer approved.', '2024-12-11 00:13:07', 'notify', 79),
(301, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:13:42', 'notify', 53),
(302, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:13:42', 'notify', 80),
(303, 'Transfer approved.', '2024-12-11 00:13:49', 'notify', 53),
(304, 'Transfer approved.', '2024-12-11 00:13:49', 'notify', 80),
(305, 'Requesting transfer to Zamboanga City Highschool', '2024-12-11 00:14:31', 'notify', 60),
(306, 'Requesting transfer to Zamboanga City Highschool', '2024-12-11 00:14:31', 'notify', 81),
(307, 'Transfer approved.', '2024-12-11 00:14:37', 'notify', 60),
(308, 'Transfer approved.', '2024-12-11 00:14:37', 'notify', 81),
(309, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:16:15', 'notify', 60),
(310, 'Requesting transfer to Tugbungan Central School', '2024-12-11 00:16:15', 'notify', 82),
(311, 'Transfer approved.', '2024-12-11 00:16:56', 'notify', 82),
(312, 'Transfer approved.', '2024-12-11 00:16:56', 'notify', 60),
(313, '1 piece of Chicken have been distributed.', '2024-12-11 00:18:24', 'notify', 82),
(314, 'Pending for approval', '2024-12-11 00:19:28', 'notify', 83),
(315, 'Admission approved.', '2024-12-11 00:19:56', 'notify', 83),
(316, 'Admitted', '2024-12-11 16:06:18', 'notify', 84),
(317, 'Requesting transfer to Zamboanga City Highschool', '2024-12-11 16:06:34', 'notify', 84),
(318, 'Requesting transfer to Zamboanga City Highschool', '2024-12-11 16:06:34', 'notify', 85),
(319, 'Transfer approved.', '2024-12-11 16:07:02', 'notify', 85),
(320, 'Transfer approved.', '2024-12-11 16:07:02', 'notify', 84),
(321, 'Requesting transfer to Tugbungan Central School', '2024-12-11 16:09:22', 'notify', 85),
(322, 'Requesting transfer to Tugbungan Central School', '2024-12-11 16:09:22', 'notify', 86),
(323, 'Transfer approved.', '2024-12-11 16:11:23', 'notify', 86),
(324, 'Transfer approved.', '2024-12-11 16:11:23', 'notify', 85),
(325, 'Admitted', '2024-12-11 16:31:46', 'notify', 87),
(326, 'Admitted', '2024-12-11 16:36:55', 'notify', 88),
(327, 'Pending for approval', '2024-12-11 16:41:48', 'notify', 89),
(328, 'Admission approved.', '2024-12-11 16:42:26', 'notify', 89),
(329, '1 pack of Starter Kit have been distributed.', '2024-12-11 16:47:13', 'notify', 86),
(330, 'Moved-out', '2024-12-11 16:47:27', 'notify', 86),
(331, '1 pack of Starter Kit have been distributed.', '2024-12-11 16:47:54', 'notify', 87),
(332, 'Moved-out', '2024-12-11 16:48:08', 'notify', 87),
(333, 'Admitted back', '2024-12-11 16:48:50', 'notify', 86),
(334, 'Moved-out', '2024-12-11 16:49:45', 'notify', 86),
(335, 'Admitted back', '2024-12-12 01:57:34', 'notify', 37),
(336, 'Requesting transfer to ICAS', '2024-12-12 02:01:37', 'notify', 83),
(337, 'Requesting transfer to ICAS', '2024-12-12 02:01:37', 'notify', 90),
(338, 'Admitted', '2024-12-12 02:28:05', 'notify', 91),
(339, 'Admitted', '2024-12-12 02:30:11', 'notify', 92),
(340, 'Transfer approved.', '2024-12-12 05:02:24', 'notify', 56),
(341, 'Transfer approved.', '2024-12-12 05:02:24', 'notify', 54);

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
(148, 2, 'admin', '1 piece of Beef Loaf distributed to Butler.', '2024-12-04 13:50:58', 'notify'),
(149, 2, 'admin', '1 pack of Evacuation Kit distributed to Butler.', '2024-12-04 13:52:15', 'notify'),
(150, 2, 'admin', '1 pack of Evacuation Kit distributed to Manoban.', '2024-12-04 13:52:15', 'notify'),
(151, 12, 'admin', 'Angelica  Nalzaro admitted to Tetuan Central School.', '2024-12-04 21:59:37', 'notify'),
(152, 12, 'admin', '20 packs of Evacuees Supply Pack added at Tetuan Central School', '2024-12-04 22:12:05', 'notify'),
(153, 12, 'admin', '1 pack of Evacuees Supply Pack distributed to Nalzaro.', '2024-12-04 22:23:35', 'notify'),
(154, 12, 'admin', '1 pack of Evacuees Supply Pack redistributed to Nalzaro.', '2024-12-04 22:31:21', 'notify'),
(155, 12, 'admin', '1 pack of Evacuees Supply Pack redistributed to Nalzaro.', '2024-12-04 22:41:58', 'notify'),
(156, 12, 'admin', '20 packs of Canton added at Tetuan Central School', '2024-12-04 22:44:03', 'notify'),
(157, 12, 'admin', '1 pack of Canton distributed to Nalzaro.', '2024-12-04 22:44:51', 'notify'),
(158, 12, 'admin', '1 pack of Canton redistributed to Nalzaro.', '2024-12-04 22:46:07', 'notify'),
(159, 12, 'admin', '20 packs of Evacuees Supply Pack added at Tetuan Central School', '2024-12-04 23:23:32', 'notify'),
(160, 12, 'admin', '1 pack of Evacuees Supply Pack distributed to Nalzaro.', '2024-12-04 23:23:55', 'notify'),
(161, 12, 'admin', '20 packs of Canton added at Zamboanga City Highschool', '2024-12-05 17:48:15', 'notify'),
(162, 12, 'admin', 'James  Lebron admitted to Zamboanga City Highschool.', '2024-12-05 17:50:56', 'notify'),
(163, 12, 'admin', '1 pack of Canton distributed to Lebron.', '2024-12-05 17:51:18', 'notify'),
(164, 12, 'admin', '1 pack of Starter Kit distributed to Lebron.', '2024-12-05 17:51:47', 'notify'),
(165, 12, 'admin', 'Steph  Curry admitted to Zamboanga City Highschool.', '2024-12-05 17:53:35', 'notify'),
(166, 12, 'admin', '20 packs of Evacuation kit added at Zamboanga City Highschool', '2024-12-05 17:54:15', 'notify'),
(167, 12, 'admin', '1 pack of Evacuation kit distributed to Curry.', '2024-12-05 17:54:29', 'notify'),
(168, 12, 'admin', 'Kyrie  Irving admitted to Tetuan Central School.', '2024-12-05 20:57:42', 'notify'),
(169, 12, 'admin', 'Luka  Doncic admitted to Tetuan Central School.', '2024-12-05 21:00:06', 'notify'),
(170, 12, 'admin', '1 pack of Canton distributed to Irving.', '2024-12-05 21:00:46', 'notify'),
(171, 12, 'admin', '1 pack of Evacuees Supply Pack distributed to Irving.', '2024-12-05 21:01:05', 'notify'),
(172, 12, 'admin', 'Jaylen  Brown admitted to Tetuan Central School.', '2024-12-05 21:03:58', 'notify'),
(173, 12, 'admin', 'Arpj  Villares admitted to Tetuan Central School.', '2024-12-05 21:13:51', 'notify'),
(174, 12, 'admin', 'Faustine  Delica admitted to Tetuan Central School.', '2024-12-05 21:17:20', 'notify'),
(175, 12, 'admin', 'John  Balan admitted to Tetuan Central School.', '2024-12-05 21:22:34', 'notify'),
(176, 12, 'admin', 'Riean  Sibul admitted to Tetuan Central School.', '2024-12-05 21:26:27', 'notify'),
(177, 12, 'admin', 'Charls  Hermosa admitted to Tetuan Central School.', '2024-12-05 21:30:43', 'notify'),
(178, 12, 'admin', 'Aldwin  Aguilo admitted to Tetuan Central School.', '2024-12-05 21:59:21', 'notify'),
(179, 13, 'admin', '20 packs of Canton added at Barangay Hall', '2024-12-06 07:17:53', 'notify'),
(180, 13, 'admin', '1 pack of Canton distributed to Aguilo.', '2024-12-06 07:18:10', 'notify'),
(181, 13, 'admin', '1 pack of Starter Kit distributed to Aguilo.', '2024-12-06 07:18:36', 'notify'),
(182, 12, 'admin', 'Naruto  Uzumaki admitted to Tetuan Central School.', '2024-12-06 07:35:19', 'notify'),
(183, 12, 'admin', 'Itachi  Uchiha admitted to Tetuan Central School.', '2024-12-06 07:36:47', 'notify'),
(184, 12, 'admin', '1 pack of Starter Kit distributed to Uzumaki.', '2024-12-06 14:46:42', 'notify'),
(185, 12, 'admin', '16 packs of Canton distributed to Uzumaki.', '2024-12-06 14:48:16', 'notify'),
(186, 12, 'admin', 'Locine  Christian admitted to Tetuan Central School.', '2024-12-06 14:53:09', 'notify'),
(187, 12, 'admin', 'Quecia Mae  Brilliantes admitted to Tetuan Central School.', '2024-12-06 15:56:46', 'notify'),
(188, 12, 'admin', 'Zild John  Abule admitted to Tetuan Central School.', '2024-12-06 19:30:16', 'notify'),
(189, 13, 'admin', 'Jang  Gang is pending for approval in Barangay Hall.', '2024-12-06 19:40:27', 'notify'),
(190, 13, 'admin', 'William  Santinlo admitted to Tugbungan Central School.', '2024-12-10 23:07:41', 'notify'),
(191, 13, 'admin', 'Santa  Cruz admitted to Tugbungan Central School.', '2024-12-10 23:09:07', 'notify'),
(192, 13, 'admin', 'Jose  Sun admitted to Tugbungan Central School.', '2024-12-10 23:11:38', 'notify'),
(193, 13, 'admin', '1 pack of Starter Kit distributed to Cruz.', '2024-12-10 23:17:11', 'notify'),
(194, 13, 'admin', '20 packs of Canton added at Tugbungan Central School', '2024-12-10 23:18:47', 'notify'),
(195, 13, 'admin', '1 pack of Canton distributed to Santinlo.', '2024-12-10 23:19:09', 'notify'),
(196, 13, 'admin', '1 pack of Canton distributed to Sun.', '2024-12-10 23:19:09', 'notify'),
(197, 12, 'admin', 'Bangbang  Marcos is pending for approval in Tetuan Central School.', '2024-12-10 23:22:51', 'notify'),
(198, 12, 'admin', 'Kelra  Gold is pending for approval in Tetuan Central School.', '2024-12-10 23:25:14', 'notify'),
(199, 13, 'admin', 'Wally  Bayola admitted to Tugbungan Central School.', '2024-12-10 23:40:19', 'notify'),
(200, 13, 'admin', 'Jose  Manny admitted to Tugbungan Central School.', '2024-12-10 23:44:26', 'notify'),
(201, 13, 'admin', '1 pack of Starter Kit distributed to Manny.', '2024-12-10 23:49:29', 'notify'),
(202, 12, 'admin', 'King  Kong is pending for approval in Tetuan Central School.', '2024-12-10 23:57:04', 'notify'),
(203, 13, 'admin', '20 pieces of Chicken added at Tugbungan Central School', '2024-12-11 00:00:54', 'notify'),
(204, 13, 'admin', '20 pieces of Chicken added at Tugbungan Central School', '2024-12-11 00:02:04', 'notify'),
(205, 13, 'admin', '1 piece of Chicken distributed to Hermosa.', '2024-12-11 00:18:24', 'notify'),
(206, 12, 'admin', 'Shin  Boo is pending for approval in Zamboanga City Highschool.', '2024-12-11 00:19:28', 'notify'),
(207, 12, 'admin', 'Hinata  Hyuga admitted to Tetuan Central School.', '2024-12-11 16:06:18', 'notify'),
(208, 13, 'admin', 'Rock  Lee admitted to Tugbungan Central School.', '2024-12-11 16:31:46', 'notify'),
(209, 13, 'admin', 'Bruce  Lee admitted to Tugbungan Highschool.', '2024-12-11 16:36:55', 'notify'),
(210, 12, 'admin', 'Mohammad  Ali is pending for approval in Zamboanga City Highschool.', '2024-12-11 16:41:48', 'notify'),
(211, 13, 'admin', '1 pack of Starter Kit distributed to Hyuga.', '2024-12-11 16:47:13', 'notify'),
(212, 13, 'admin', '1 pack of Starter Kit distributed to Lee.', '2024-12-11 16:47:54', 'notify'),
(213, 13, 'admin', '50 pieces of Chicken added at Barangay Hall', '2024-12-11 17:01:13', 'notify'),
(214, 13, 'admin', 'Kyla  Rico admitted to Tugbungan Central School.', '2024-12-12 02:28:05', 'notify'),
(215, 13, 'admin', 'Ethan  Felix admitted to Tugbungan Central School.', '2024-12-12 02:30:11', 'notify'),
(216, 13, 'admin', '20 pieces of Inasal added at Barangay Hall', '2024-12-12 02:34:09', 'notify');

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
(113, 'Mikha', 'Bini', 'Lim1', '', 'Brother', 'College', 'Male', 23, 'Red Hair Badass', 1),
(115, 'Aiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Male', 22, 'Power Forward', 2),
(129, 'Beng', '', 'Climaco', '', 'Cousin', 'College', 'Female', 33, 'Zumba Dancer', 28),
(134, 'Alex', '', 'Caruso', '', 'Mate', 'College', 'Male', 23, 'Shooting Guard', 35),
(136, 'Aljhun', '', 'Nalzaro', '', 'Brother', 'Highschool', 'Male', 22, 'student', 37),
(138, 'Fredrick', '', 'Lim', '', 'Teammate ', 'Highschool', 'Male', 22, 'student', 49),
(139, 'Sasuke', '', 'Uchiha', '', 'Brother', 'Highschool', 'Male', 19, 'Ninja', 52),
(140, 'Sasuke', '', 'Uchiha', '', 'Brother', 'Highschool', 'Male', 19, 'Ninja', 53),
(141, 'Rodrigo', '', 'Brilliantes', '', 'brother', 'Highschool', 'Male', 22, 'student', 55),
(142, 'Rodrigo', '', 'Brilliantes', '', 'brother', 'Highschool', 'Male', 22, 'student', 59),
(143, 'Bryan', '', 'Santinlo', '', 'Son', 'N/A', 'Male', 2, 'N/A', 64),
(144, 'Bryan', '', 'Santinlo', '', 'Son', 'N/A', 'Male', 2, 'N/A', 70),
(145, 'Willy', '', 'Bayola', '', 'Father', 'N/A', 'Male', 10, 'N/A', 71),
(146, 'Willy', '', 'Bayola', '', 'Father', 'N/A', 'Male', 10, 'N/A', 73),
(147, 'Sasuke', '', 'Uchiha', '', 'Brother', 'Highschool', 'Male', 19, 'Ninja', 78),
(148, 'Sasuke', '', 'Uchiha', '', 'Brother', 'Highschool', 'Male', 19, 'Ninja', 79),
(149, 'Sasuke', '', 'Uchiha', '', 'Brother', 'Highschool', 'Male', 19, 'Ninja', 80);

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
(73, 1, 'admin', 'New Admin Account Added: Test', '2024-11-14 00:00:00', 'cleared'),
(74, 8, 'admin', 'Evacuation center has been inactive: Barangay Hall of Bugguk', '2024-11-20 17:27:40', 'viewed'),
(75, 8, 'admin', 'Evacuation center has been inactive: Basketball Court of Bugguk', '2024-11-20 17:27:40', 'viewed'),
(76, 2, 'admin', 'Sam Cena is requesting to transfer to Western Mindanao State University Main Campus', '2024-11-21 20:09:14', 'viewed'),
(77, 1, 'admin', 'New Admin Account Added: Chinatsu  Kano', '2024-11-24 23:04:47', 'cleared'),
(78, 2, 'admin', 'New Worker Account Added: Taiki  Inomata', '2024-11-25 00:39:03', 'viewed'),
(79, 2, 'admin', 'New Evacuation Center Added: Gymnasium', '2024-11-25 01:02:44', 'viewed'),
(80, 1, 'admin', 'New Evacuation Center Added: Gymnasium at Barangay: Guiwan', '2024-11-25 01:02:44', 'cleared'),
(81, 1, 'admin', 'New Admin Account Added: Maloi D Ricalde', '2024-11-25 12:46:07', 'cleared'),
(82, 2, 'admin', 'New Worker Account Added: Maloi D Arceta', '2024-11-25 13:10:25', 'viewed'),
(83, 2, 'admin', 'Evacuation center has been inactive: Avengers Tower', '2024-11-27 08:49:37', 'viewed'),
(84, 2, 'admin', 'Evacuation center has been inactive: Western Mindanao State University Main Campus', '2024-11-27 08:49:37', 'viewed'),
(85, 2, 'admin', 'Evacuation center has been inactive: Zamcelco', '2024-11-27 08:49:37', 'viewed'),
(86, 2, 'admin', 'New Worker Account Added: Haruko  Akagi', '2024-11-27 17:32:20', 'viewed'),
(87, 8, 'admin', 'Evacuation center has been inactive: Barangay Hall of Bugguk', '2024-11-28 19:15:56', 'notify'),
(88, 8, 'admin', 'Evacuation center has been inactive: Basketball Court of Bugguk', '2024-11-28 19:15:56', 'notify'),
(89, 2, 'admin', 'Evacuation center has been inactive: Don Gems', '2024-11-29 16:02:42', 'viewed'),
(90, 2, 'admin', 'Evacuation center has been inactive: Western Mindanao State University', '2024-11-29 18:15:49', 'viewed'),
(91, 2, 'admin', 'Sam123 D. Cena is requesting to transfer to Zamcelco', '2024-12-02 12:32:55', 'viewed'),
(92, 8, 'admin', 'Samkuragi D. Smile is requesting to transfer to Basketball Court of Bugguk', '2024-12-02 12:36:00', 'notify'),
(93, 2, 'admin', 'asdasd sdasd Cenaaa is requesting to transfer to Avengers Tower', '2024-12-02 12:39:25', 'viewed'),
(94, 2, 'admin', 'Sammy1 D. Dragon is requesting to transfer to Zamcelco', '2024-12-02 12:57:16', 'viewed'),
(95, 2, 'admin', 'Samkuragi D. Haruko is requesting to transfer to Don Gems', '2024-12-02 13:01:11', 'viewed'),
(96, 2, 'admin', 'Maloi D. Ricaldedddddddd is requesting to transfer to Barangay Hall', '2024-12-02 13:04:15', 'viewed'),
(97, 2, 'admin', 'Evacuation center has been inactive: Gymnasium', '2024-12-03 09:21:09', 'viewed'),
(98, 2, 'admin', 'Jimmy Butler is requesting to transfer to Don Gems', '2024-12-03 14:00:41', 'viewed'),
(99, 1, 'admin', 'New Admin Account Added: Mark Larenz Bacane Tabotabo', '2024-12-04 13:04:45', 'cleared'),
(100, 2, 'admin', 'Derrick  Rose is requesting to transfer to Gymnasium', '2024-12-04 13:49:05', 'viewed'),
(101, 2, 'admin', 'Micheal D. Cena is requesting to transfer to Don Gems', '2024-12-04 13:55:06', 'viewed'),
(102, 12, 'admin', 'New Evacuation Center Added: Tetuan Central School', '2024-12-04 21:30:17', 'viewed'),
(103, 1, 'admin', 'New Evacuation Center Added: Tetuan Central School at Barangay: Tetuan', '2024-12-04 21:30:17', 'notify'),
(104, 12, 'admin', 'New Worker Account Added: Jondino  Rodrigo', '2024-12-04 21:34:51', 'viewed'),
(105, 12, 'admin', 'New Evacuation Center Added: Zamboanga City Highschool', '2024-12-05 17:44:27', 'viewed'),
(106, 1, 'admin', 'New Evacuation Center Added: Zamboanga City Highschool at Barangay: Tetuan', '2024-12-05 17:44:28', 'notify'),
(107, 2, 'admin', 'Luka  Doncic is requesting to transfer to Zamboanga City High School Main', '2024-12-05 21:02:05', 'viewed'),
(108, 2, 'admin', 'Jaylen  Brown is requesting to transfer to Zamboanga City High School Main', '2024-12-05 21:04:20', 'viewed'),
(109, 2, 'admin', 'Arpj  Villares is requesting to transfer to Zamboanga City High School Main', '2024-12-05 21:14:46', 'viewed'),
(110, 2, 'admin', 'Faustine  Delica is requesting to transfer to Zamboanga City High School Main', '2024-12-05 21:17:54', 'viewed'),
(111, 2, 'admin', 'John  Balan is requesting to transfer to Zamboanga City High School Main', '2024-12-05 21:24:21', 'viewed'),
(112, 2, 'admin', 'Riean  Sibul is requesting to transfer to Zamboanga City High School Main', '2024-12-05 21:26:58', 'viewed'),
(113, 12, 'admin', 'Charls  Hermosa is requesting to transfer to Zamboanga City Highschool', '2024-12-05 21:30:56', 'viewed'),
(114, 12, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-12-05 21:33:56', 'viewed'),
(115, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Tetuan', '2024-12-05 21:33:56', 'notify'),
(116, 1, 'admin', 'New Admin Account Added: Raiza  Beligolo', '2024-12-05 21:42:02', 'notify'),
(117, 13, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-12-05 21:46:43', 'viewed'),
(118, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Tugbungan', '2024-12-05 21:46:43', 'notify'),
(119, 13, 'admin', 'Evacuation Center Deleted: Barangay Hall', '2024-12-05 21:47:14', 'viewed'),
(120, 1, 'admin', 'Evacuation Center Deleted: Barangay Hall at Barangay: Tugbungan', '2024-12-05 21:47:14', 'notify'),
(121, 13, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-12-05 21:47:23', 'viewed'),
(122, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Tugbungan', '2024-12-05 21:47:23', 'notify'),
(123, 12, 'admin', 'New Evacuation Center Added: Plaza Tetuan', '2024-12-05 21:48:15', 'viewed'),
(124, 1, 'admin', 'New Evacuation Center Added: Plaza Tetuan at Barangay: Tetuan', '2024-12-05 21:48:15', 'notify'),
(125, 13, 'admin', 'Evacuation Center Deleted: Barangay Hall', '2024-12-05 21:52:22', 'viewed'),
(126, 1, 'admin', 'Evacuation Center Deleted: Barangay Hall at Barangay: Tugbungan', '2024-12-05 21:52:22', 'notify'),
(127, 13, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-12-05 21:53:32', 'viewed'),
(128, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Tugbungan', '2024-12-05 21:53:32', 'notify'),
(129, 13, 'admin', 'Aldwin  Aguilo is requesting to transfer to Barangay Hall', '2024-12-05 21:59:37', 'viewed'),
(130, 13, 'admin', 'New Evacuation Center Added: Central School', '2024-12-05 22:29:03', 'viewed'),
(131, 1, 'admin', 'New Evacuation Center Added: Central School at Barangay: Tugbungan', '2024-12-05 22:29:03', 'notify'),
(132, 2, 'admin', 'Evacuation Center Deleted: Zamboanga City High School Main', '2024-12-06 07:31:58', 'viewed'),
(133, 1, 'admin', 'Evacuation Center Deleted: Zamboanga City High School Main at Barangay: Guiwan', '2024-12-06 07:31:58', 'notify'),
(134, 2, 'admin', 'Evacuation Center Deleted: Avengers Tower', '2024-12-06 07:32:08', 'viewed'),
(135, 1, 'admin', 'Evacuation Center Deleted: Avengers Tower at Barangay: Guiwan', '2024-12-06 07:32:08', 'notify'),
(136, 2, 'admin', 'Evacuation Center Deleted: Don Gems', '2024-12-06 07:32:18', 'viewed'),
(137, 1, 'admin', 'Evacuation Center Deleted: Don Gems at Barangay: Guiwan', '2024-12-06 07:32:18', 'notify'),
(138, 2, 'admin', 'Evacuation Center Deleted: Barangay Hall', '2024-12-06 07:32:29', 'viewed'),
(139, 1, 'admin', 'Evacuation Center Deleted: Barangay Hall at Barangay: Guiwan', '2024-12-06 07:32:29', 'notify'),
(140, 2, 'admin', 'Evacuation Center Deleted: Western Mindanao State University', '2024-12-06 07:32:46', 'viewed'),
(141, 1, 'admin', 'Evacuation Center Deleted: Western Mindanao State University at Barangay: Guiwan', '2024-12-06 07:32:46', 'notify'),
(142, 12, 'admin', 'Evacuation Center Deleted: Plaza Tetuan', '2024-12-06 07:39:30', 'viewed'),
(143, 1, 'admin', 'Evacuation Center Deleted: Plaza Tetuan at Barangay: Tetuan', '2024-12-06 07:39:30', 'notify'),
(144, 12, 'admin', 'Evacuation Center Deleted: Zamboanga City Highschool', '2024-12-06 07:39:49', 'viewed'),
(145, 1, 'admin', 'Evacuation Center Deleted: Zamboanga City Highschool at Barangay: Tetuan', '2024-12-06 07:39:49', 'notify'),
(146, 12, 'admin', 'New Evacuation Center Added: Zamboanga City Highschool', '2024-12-06 07:40:38', 'viewed'),
(147, 1, 'admin', 'New Evacuation Center Added: Zamboanga City Highschool at Barangay: Tetuan', '2024-12-06 07:40:38', 'notify'),
(148, 12, 'admin', 'Evacuation Center Deleted: Zamboanga City Highschool', '2024-12-06 07:41:48', 'viewed'),
(149, 1, 'admin', 'Evacuation Center Deleted: Zamboanga City Highschool at Barangay: Tetuan', '2024-12-06 07:41:48', 'notify'),
(150, 12, 'admin', 'Evacuation Center Deleted: Barangay Hall', '2024-12-06 13:32:41', 'viewed'),
(151, 1, 'admin', 'Evacuation Center Deleted: Barangay Hall at Barangay: Tetuan', '2024-12-06 13:32:41', 'notify'),
(152, 12, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-12-06 13:33:20', 'viewed'),
(153, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Tetuan', '2024-12-06 13:33:20', 'notify'),
(154, 12, 'admin', 'Evacuation Center Deleted: Barangay Hall', '2024-12-06 13:33:45', 'viewed'),
(155, 1, 'admin', 'Evacuation Center Deleted: Barangay Hall at Barangay: Tetuan', '2024-12-06 13:33:45', 'notify'),
(156, 12, 'admin', 'New Evacuation Center Added: Zamboanga City Highschool', '2024-12-06 14:45:19', 'viewed'),
(157, 1, 'admin', 'New Evacuation Center Added: Zamboanga City Highschool at Barangay: Tetuan', '2024-12-06 14:45:19', 'notify'),
(158, 1, 'admin', 'New Admin Account Added: Petter B Parker', '2024-12-06 15:54:06', 'notify'),
(159, 12, 'admin', 'Locine  Christian is requesting to transfer to Zamboanga City Highschool', '2024-12-06 19:28:23', 'viewed'),
(160, 12, 'admin', 'Zild John  Abule is requesting to transfer to Zamboanga City Highschool', '2024-12-10 14:35:15', 'viewed'),
(161, 12, 'admin', 'Zild John  Abule is requesting to transfer to Zamboanga City Highschool', '2024-12-10 14:40:03', 'viewed'),
(162, 13, 'admin', 'Zild John  Abule is requesting to transfer to Barangay Hall', '2024-12-10 14:55:12', 'viewed'),
(163, 1, 'admin', 'New Admin Account Added: Evelyn  Tabs', '2024-12-10 20:04:19', 'notify'),
(164, 13, 'admin', 'New Worker Account Added: Franz  Valdez', '2024-12-10 22:57:16', 'viewed'),
(165, 13, 'admin', 'Evacuation Center Central School Updated to Central Schools.', '2024-12-10 23:04:36', 'viewed'),
(166, 1, 'admin', 'Evacuation Center Central School Updated to Central Schools in Barangay Tugbungan', '2024-12-10 23:04:36', 'notify'),
(167, 13, 'admin', 'Evacuation Center Deleted: Central Schools', '2024-12-10 23:04:57', 'viewed'),
(168, 1, 'admin', 'Evacuation Center Deleted: Central Schools at Barangay: Tugbungan', '2024-12-10 23:04:57', 'notify'),
(169, 13, 'admin', 'New Evacuation Center Added: Tugbungan Central School', '2024-12-10 23:05:33', 'viewed'),
(170, 1, 'admin', 'New Evacuation Center Added: Tugbungan Central School at Barangay: Tugbungan', '2024-12-10 23:05:33', 'notify'),
(171, 13, 'admin', 'Jose  Sun is requesting to transfer to Barangay Hall', '2024-12-10 23:27:44', 'viewed'),
(172, 12, 'admin', 'William  Santinlo is requesting to transfer to Zamboanga City Highschool', '2024-12-10 23:28:45', 'viewed'),
(173, 13, 'admin', 'Wally  Bayola is requesting to transfer to Barangay Hall', '2024-12-10 23:50:55', 'viewed'),
(174, 13, 'admin', 'Jose  Sun is requesting to transfer to Tugbungan Central School', '2024-12-11 00:06:36', 'viewed'),
(175, 13, 'admin', 'Jose  Sun is requesting to transfer to Tugbungan Central School', '2024-12-11 00:08:41', 'viewed'),
(176, 13, 'admin', 'Jose  Sun is requesting to transfer to Tugbungan Central School', '2024-12-11 00:11:23', 'viewed'),
(177, 13, 'admin', 'Itachi  Uchiha is requesting to transfer to Tugbungan Central School', '2024-12-11 00:12:03', 'viewed'),
(178, 13, 'admin', 'Itachi  Uchiha is requesting to transfer to Tugbungan Central School', '2024-12-11 00:12:51', 'viewed'),
(179, 13, 'admin', 'Itachi  Uchiha is requesting to transfer to Tugbungan Central School', '2024-12-11 00:13:42', 'viewed'),
(180, 12, 'admin', 'Charls  Hermosa is requesting to transfer to Zamboanga City Highschool', '2024-12-11 00:14:31', 'viewed'),
(181, 13, 'admin', 'Charls  Hermosa is requesting to transfer to Tugbungan Central School', '2024-12-11 00:16:15', 'viewed'),
(182, 12, 'admin', 'Hinata  Hyuga is requesting to transfer to Zamboanga City Highschool', '2024-12-11 16:06:34', 'viewed'),
(183, 13, 'admin', 'Hinata  Hyuga is requesting to transfer to Tugbungan Central School', '2024-12-11 16:09:22', 'viewed'),
(184, 13, 'admin', 'New Evacuation Center Added: Tugbungan Highschool', '2024-12-11 16:33:06', 'viewed'),
(185, 1, 'admin', 'New Evacuation Center Added: Tugbungan Highschool at Barangay: Tugbungan', '2024-12-11 16:33:06', 'notify'),
(186, 12, 'admin', 'New Worker Account Added: Joshua  Saavedra', '2024-12-11 17:41:01', 'notify'),
(187, 12, 'admin', 'New Worker Account Added: Devin  Booker', '2024-12-11 17:46:05', 'notify'),
(188, 12, 'admin', 'New Evacuation Center Added: ICAS', '2024-12-11 17:49:34', 'notify'),
(189, 1, 'admin', 'New Evacuation Center Added: ICAS at Barangay: Tetuan', '2024-12-11 17:49:34', 'notify'),
(190, 12, 'admin', 'Evacuation center has been inactive: Tetuan Central School', '2024-12-12 01:42:08', 'notify'),
(191, 1, 'admin', 'New Admin Account Added: Kevin  Durant', '2024-12-12 01:50:08', 'notify'),
(192, 12, 'admin', 'Shin  Boo is requesting to transfer to ICAS', '2024-12-12 02:01:37', 'notify');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `expires_at`) VALUES
('ziarahh215@gmail.com', '5e9fdcbd8d56d72cbf3d1a45293bd09fa3d6784ee797f22f087dedef88cef8c4', '2024-12-10 20:42:28');

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
(6, '2024-12-05', '21:53:00', 'DSWD', 19, 0, 'pack', 39, 0),
(8, '2024-12-06', '14:45:00', 'DSWD', 20, 0, 'pack', 44, 0),
(9, '2024-12-10', '23:14:00', 'DSWD', 0, 3, 'pack', 45, 0),
(10, '2024-12-10', '23:49:00', 'DSWD', 4, 5, 'pack', 45, 0),
(11, '2024-12-11', '17:50:00', 'DSWD', 30, 30, 'pack', 51, 0);

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
(34, 'Canton', 'canton ito', 1, 20, 'pack', '../../assets/uploads/supplies/canton.png', '2024-12-04', '22:43:00', 'DSWD', 14, 24, 1),
(35, 'Starter Kit', 'required for move out', 17, 20, 'pack', '../../assets/uploads/supplies/supplies.png', '2024-12-04', '23:23:00', 'DSWD', 14, 25, 1),
(39, 'Starter Kit', 'Required before moving out.', 0, 0, 'pack', '', '2024-12-05', '21:53:32', 'Barangay', 20, 28, 1),
(41, 'Canton', 'Instant noodles for evacuees', 19, 20, 'pack', '../../assets/uploads/supplies/canton.png', '2024-12-06', '07:17:00', 'DSWD', 20, 29, 1),
(44, 'Starter Kit', 'Required before moving out.', 0, 0, 'pack', '', '2024-12-06', '14:45:19', 'Barangay', 24, 25, 1),
(45, 'Starter Kit', 'Required before moving out.', 0, 0, 'pack', '', '2024-12-10', '23:05:33', 'Barangay', 25, 28, 1),
(46, 'Cantons', '', 18, 20, 'pack', '../../assets/uploads/supplies/canton.png', '2024-12-10', '23:18:00', 'DSWD', 25, 29, 1),
(48, 'Chicken', '', 19, 20, 'piece', '../../assets/uploads/supplies/1733376338_download (2).jpg', '2024-12-11', '00:02:00', 'DSWD', 25, 29, 1),
(49, 'Starter Kit', 'Required before moving out.', 0, 0, 'pack', '', '2024-12-11', '16:33:06', 'Barangay', 26, 28, 1),
(50, 'Chicken', '', 50, 50, 'piece', '../../assets/uploads/supplies/1733376338_download (2).jpg', '2024-12-11', '17:00:00', 'DSWD', 20, 29, 1),
(51, 'Starter Kit', 'Required before moving out.', 0, 0, 'pack', '', '2024-12-11', '17:49:34', 'Barangay', 27, 25, 1),
(53, 'Inasal', '', 20, 20, 'piece', '../../assets/uploads/supplies/1733376338_download (2).jpg', '2024-12-12', '02:33:00', 'DSWD', 20, 29, 1);

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
(1, 'worker@gmail.com', 'Worker123', 'Ryota', 'D.', 'Miyagi', '', '$2y$10$qgHnhQJT1K2D.hEfuCCb0.PNDlgsSwiZJTFzOq7LAthmftb1bHIMe', '../../assets/uploads/profiles/ryota.jpg', '2014-11-20', 30, 'Male', 'Technician', 'Zamboanga City', 'Guiwan', '1234567890', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'inactive', '2024-12-03 20:48:33', NULL, 2),
(4, 'bini@gmail.com', 'RyotaTeamManager', 'Ayako', 'D', 'Ryota', '', '$2y$10$FRKQZtVToUBv4S0.tXAVhuXXgnh2VWV0ejI23KzxMCMQOvUB5yi7a', '../../assets/uploads/profiles/ayako.jpg', '2014-11-17', 22, 'Female', 'Team Manager', 'Zamboanga City', 'Guiwan', '12345555', '../../assets/uploads/appointments/1232342112.jpg', 'inactive', '2024-11-10 20:27:53', NULL, 2),
(5, 'binimaloiworker@gmail.com', 'InomataBadmintonPlayer', 'Taiki', '', 'Inomata', '', '$2y$10$ppSCKg5seNV8IWbL.m9PtuT8ZTX1RVTo8taBg3Gri1NOeN/QZgB06', '../../assets/uploads/profiles/haruko.jpg', '1998-07-06', 26, 'Male', 'Badminton Player', 'Zamboanga City', 'Guiwan', '123456', '../../assets/uploads/appointments/download (2).jpg', 'inactive', '2024-11-25 00:40:20', NULL, 2),
(7, 'binimaloi352@gmail.com', 'AkagiBarangayCaptain', 'Haruko', '', 'Akagi', '', '$2y$10$LLrfn0tBXJDfz1pz9b3AsexyC82ONnREACu9ImkDS3v0sWtflexWu', '', '2003-02-13', 21, 'Male', 'Barangay Captain', 'Zamboanga City', 'Guiwan', '1221323', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'active', NULL, 'd1dc76fd70', 2),
(8, 'nazarethxd4@gmail.com', 'RodrigoSKKagawad', 'Jondino', '', 'Rodrigo', '', '$2y$10$TeOyPrgRUciWZvO7.P1eBe1JnRyhzQqPTiY8HAyUD/TY2Z.Y/xcWG', '', '2002-06-04', 22, 'Male', 'SK Kagawad', 'Zamboanga City', 'Tetuan', '09090909090', '../../assets/uploads/appointments/captain.webp', 'active', '2024-12-12 02:25:59', NULL, 12),
(9, 'ziarahh215@gmail.com', 'Valdez', 'Franz', '', 'Valdez', '', '$2y$10$YQutgKcKgLe3rmIICpDXdOwAvUjwzWlifvOhZV/nJoXNrIj3TkjBm', '../../assets/uploads/profiles/photo_2024-01-13_21-20-44.jpg', '1995-05-16', 29, 'Male', 'SK Kagawad', 'Zamboanga City', 'Tugbungan', '09352457387', '../../assets/uploads/appointments/captain.webp', 'active', '2024-12-12 04:59:12', NULL, 13),
(10, 'kevincurry850@gmail.com', 'SaavedraSKKagawad', 'Joshua', '', 'Saavedra', '', '$2y$10$DTgBin8/lpMDlOXb07mvPOhmkEjcb2B3fmbGLMEIAKGWHjhrkl28K', '', '2002-01-11', 22, 'Male', 'SK Kagawad', 'Zamboanga City', 'Tetuan', '09090402751', '../../assets/uploads/appointments/captain.webp', 'active', NULL, '2ad1cde58c', 12),
(11, 'bookd303@gmail.com', 'BookerSKKagawad', 'Devin', '', 'Booker', '', '$2y$10$4CkylndzlD9dC5eWpXJXhOEWRpmyRcU2HyN/G5s.LRW8bbFrOaUH6', '', '1993-05-12', 31, 'Male', 'SK Kagawad', 'Zamboanga City', 'Tetuan', '09356278695', '../../assets/uploads/appointments/captain.webp', 'active', '2024-12-11 17:48:06', NULL, 12);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `assigned_worker`
--
ALTER TABLE `assigned_worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `barangay`
--
ALTER TABLE `barangay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `distribute`
--
ALTER TABLE `distribute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `evacuation_center`
--
ALTER TABLE `evacuation_center`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `evacuees`
--
ALTER TABLE `evacuees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `evacuees_log`
--
ALTER TABLE `evacuees_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `supply`
--
ALTER TABLE `supply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `worker`
--
ALTER TABLE `worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
