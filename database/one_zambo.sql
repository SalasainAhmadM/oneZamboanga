-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2025 at 03:42 PM
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
(1, 'Mitsuyoshi', 'D.', 'Anzai', 'II', 'superadmin@gmail.com', 'superadmin12', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/Mitsuyoshi Anzai.jpg', '0000-00-00', 0, '', '', '', '', NULL, '0', 'superadmin', '', 'inactive', '2024-11-24 22:53:17', NULL),
(2, 'Hanamichi', 'D.', 'Sakuragi', '', 'admin@gmail.com', 'admin123', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/sakuragi.png', '2004-10-25', 20, 'Male', 'Captain', 'Zamboanga', 'Guiwan', NULL, '2147483647', 'admin', '../../assets/uploads/appointments/journal format.png', 'active', '2025-01-09 22:16:26', ''),
(3, 'Kaede', 'D', 'Rukawa1', '', 'binimaloi@gmail.com', 'RukawaAdmin', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/1733376338_download (3).jpg', '1994-10-25', 30, 'Male', 'Ace Player', 'Zamboanga City', 'Shohoku', '../../assets/uploads/barangay/1733376338_ccs.png', '2147483647', 'admin', '../../assets/uploads/appointments/1733376338_download (2).jpg', 'done', '2024-11-04 22:36:44', ''),
(4, 'Maloi', 'D', 'Ricalde', '', 'kaizoku@gmail.com', 'RicaldeAdmin', '$2y$10$.cQt6NadI2oLMwFn3Ne2T.gvb.fUGwA0A06ICcgeOpvmqvX2s4Obm', '../../assets/uploads/profiles/bini_maloi.png', '2004-10-25', 20, 'Female', 'Main Vocalist', 'Zamboanga City', 'Kasalamatan', NULL, '09551078233', 'admin', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'inactive', '2024-11-08 23:02:03', NULL),
(5, 'Aiah', 'D', 'Arceta', '', 'masterjho@gmail.com', 'ArcetaAdmin', '$2y$10$t8XryzbGRzJaEa6PW4Zz6e/fopHBGky8oTRhaN/sbgMI21EdM49q.', '', '1994-10-25', 30, 'Female', 'Main Visual', 'Zamboanga City', 'Talon-talon', NULL, '123456789', 'admin', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'inactive', '2024-11-08 23:27:32', NULL),
(6, 'Mikha', 'D', 'Lim', '', 'samcena@gmail.com', 'LimAdmin', '$2y$10$0K8XeBqdvMj0I.iaEF4hSOId39U7kYlZCTgF1LJHOyOUUdhJTAKda', '../../assets/uploads/profiles/bini_mikha.png', '2004-10-25', 20, 'Female', 'Main Rapper', 'Zamboanga City', 'Sta.Maria', '', '09881078332', 'admin', '../../assets/uploads/appointments/1.png', 'inactive', '2024-11-08 23:36:17', NULL),
(7, 'Colet', 'D', 'Vergara', '', 'binimaloi2@gmail.com', 'VergaraAdmin', '$2y$10$QcOe1IvngDYKv4TJB6EdQOqgLcYOvfG3C3FBGws.gowDxrdbZLGg.', '../../assets/uploads/profiles/bini_colet.png', '1994-10-25', 30, 'Female', 'Lead Vocalist', 'Zamboanga City', 'San Roque', '../../assets/uploads/barangay/3.png', '0988106322', 'admin', '../../assets/uploads/appointments/bini.jpg', 'active', NULL, ''),
(8, 'Mark', 'D', 'Tabotabo', '', 'binimaloi27@gmail.com', 'TabotaboAdmin', '$2y$10$hZ3pXCLp9WKaC.O25J2BAuCznkwudGOwUr.hDmS74LlCWU7nnYo9e', '../../assets/uploads/profiles/archi.jpg', '2004-10-25', 20, 'Male', 'Programmer', 'Zamboanga City', 'Kasanyangan', '../../assets/uploads/barangay/1733376439_wmsu.png', '0955107334', 'admin', '../../assets/uploads/appointments/1232342112.jpg', 'inactive', '2024-12-08 12:47:44', NULL),
(9, 'Chinatsu', '', 'Kano', '', 'chinatsukano@gmail.com', 'KanoAdmin', '$2y$10$hZ3pXCLp9WKaC.O25J2BAuCznkwudGOwUr.hDmS74LlCWU7nnYo9e', '', '1994-10-25', 30, 'Male', 'Basketball Player', 'Zamboanga City', 'Talon-Talon', '../../assets/uploads/barangay/admin.png', '123', 'admin', '../../assets/uploads/appointments/canton.png', 'inactive', '2024-11-24 23:12:23', NULL),
(12, 'Maloiiiss', 'D', 'Arceta', '', 'binimaloi123@gmail.com', 'ArcetaAdmin', '$2y$10$UJnja1QP/nMMwSuASM0Sx.LfZ/ki12x2QY/h8HTNNNhtNmqmOQLIW', '', '2000-01-04', 24, 'Female', 'Barangay Captain', 'Zamboanga City', 'Laughtale', '', '12345', 'admin', '../../assets/uploads/appointments/download.jpg', 'inactive', NULL, '657d7d7799'),
(28, 'Kaede', 'D', 'Rukawa1', '', 'binimaloi352@gmail.com', 'Rukawa1Admin', '$2y$10$aet7KYHzPoOMhZLllsxiAeLXAecYa65t25N99XU.z1LRwVPNcks4y', '', '1994-10-25', 30, 'Male', 'Ace Player', 'Zamboanga City', 'Shohoku', '', '2147483647', 'admin', '', 'inactive', NULL, NULL);

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
(2, 'Soft Drinks', 2),
(3, 'Clothes', 2),
(5, 'Ayuda Pack', 2),
(6, 'Buffet', 2),
(7, 'Catering', 2),
(14, 'Starting Kit', 2),
(15, 'Starting Kit', 9),
(17, 'Starting Kit', 8),
(18, 'Starting Kit', 3);

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
(85, 'Magi', '2024-11-18 11:14:12', 10, 3, 26, 2, 'admin'),
(89, 'Cat Food', '2024-11-18 11:43:31', 10, 4, 2, 2, 'admin'),
(93, 'Ahmad M. Salasain ', '2024-11-25 01:19:23', 2, 20, 1, 2, 'admin'),
(94, 'Evacuation Kit', '2024-12-02 11:21:19', 9, 31, 2, 2, 'admin'),
(98, 'Beef Loaf', '2024-12-09 20:57:32', 24, 30, 3, 2, 'admin'),
(99, 'Beef Loaf', '2024-12-09 20:57:32', 27, 30, 3, 2, 'admin'),
(102, 'Mega Sardines', '2024-12-11 22:33:11', 3, 29, 23, 2, 'admin'),
(103, 'Mega Sardines', '2024-12-11 22:33:11', 27, 29, 23, 2, 'admin'),
(104, 'Mega Sardines', '2024-12-11 22:33:11', 46, 29, 23, 2, 'admin'),
(105, 'Lucky Me Canton', '2024-12-12 01:14:20', 48, 13, 2, 2, 'admin'),
(106, 'Mag', '2024-12-12 01:21:15', 48, 1, 1, 2, 'worker'),
(107, 'Beef Loaf', '2025-01-09 21:21:25', 3, 30, 46, 2, 'admin'),
(108, 'Beef Loaf', '2025-01-09 21:21:25', 46, 30, 51, 2, 'admin'),
(109, 'Beef Loaf', '2025-01-09 21:21:25', 50, 30, 51, 2, 'admin'),
(110, 'Evacuation Kit', '2025-01-09 22:05:28', 3, 31, 1, 2, 'admin'),
(111, 'Evacuation Kit', '2025-01-09 22:05:28', 46, 31, 1, 2, 'admin'),
(112, 'Evacuation Kit', '2025-01-09 22:05:28', 50, 31, 1, 2, 'admin');

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
(1, 'Don Gems', 'Grandline', '', '3', 2, '../../assets/uploads/evacuation_centers/spirited_away_by_snatti89_ddj15iy.jpg', '2024-11-10 11:18:41'),
(2, 'Zamboanga City High School Main', 'Grandline', '', '122', 2, '', '2024-11-06 11:18:41'),
(3, 'Avengers Tower', 'New York', '', '3', 2, '../../assets/uploads/evacuation_centers/one_piece_scene_zoro_by_uoa7_d5hhg5y.jpg', '2024-11-10 11:18:41'),
(4, 'Barangay Hall', 'Bugguk', '', '20', 2, '', '2024-11-01 12:15:50'),
(5, 'Western Mindanao State University', 'Guiwan Street', '', '40', 2, '../../assets/uploads/evacuation_centers/ScreenShot-2022-9-8_20-32-54.png', '2024-11-10 12:18:59'),
(10, 'Zamcelco', 'Di Makita Street', '', '22', 2, '../../assets/uploads/evacuation_centers/weathering with you.jpg', '2024-11-18 10:29:54'),
(11, 'Barangay Hall of Bugguk', 'Kasanyangan', '', '22', 8, '../../assets/uploads/evacuation_centers/the_valley_of_the_wind__nausicaa_by_syntetyc_d5wb09s-fullview.jpg', '2024-11-12 11:12:18'),
(12, 'Basketball Court of Bugguk', 'Kasanyangan', '', '22', 8, '../../assets/uploads/evacuation_centers/the_valley_of_the_wind__nausicaa_by_syntetyc_d5wb09s-fullview.jpg', '2024-11-12 11:12:18'),
(13, 'Gymnasium', 'Chico', ' Guiwan', '10', 2, '../../assets/uploads/evacuation_centers/front22.png', '2024-11-24 17:02:44'),
(16, 'Grandstand', 'Normal Road', ' Kasanyangan', '100', 8, '', '2024-12-04 18:53:34'),
(25, 'Way Akkal', 'Grandline', ' Guiwan', '8', 2, '', '2024-12-05 12:28:11'),
(26, 'Barangay Hall', 'dss', ' Guiwan', '21', 2, '', '2024-12-05 13:51:04'),
(28, 'TESTEST', 'dss', ' Guiwan', '21', 28, '', '2024-12-05 13:51:04'),
(29, 'TESTEST2', 'dss', ' Guiwan', '21', 28, '', '2024-12-05 13:51:04');

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
(1, 'Mark', 'D.', 'Tabotabo', '', 'Male', 'Owner', 'Flood', 'Guiwan', '1999-02-20', 25, 'Idol', '9092738051', '12223', 'totally', '11111', 'Colet Vergara', 2, 10, 4, '2024-11-12', 'Admit'),
(2, 'Sammy1', 'D.', 'Dragon', '', 'Male', 'Owner', 'Flood', 'Guiwan', '2024-11-10', 22, 'Idol', '9278935682', '20000', 'totally', '10000', 'Colet Vergara', 2, 10, 5, '2024-11-12', 'Transfer'),
(3, 'Sakuragi', 'D.', 'Haruko', '', 'Male', 'Sharer', 'Flood', 'Guiwan', '2024-11-21', 22, 'Idol', '09116464286', '11', 'totally', '111', 'Colet Vergara', 2, 1, 4, '2024-11-12', 'Admitted'),
(4, 'Doffy', 'D.', 'Smile', '', 'Male', 'Sharer', 'Flood', 'Kasanyangan', '2024-11-21', 22, 'Idol', '09745514414', '11', 'totally', '111', 'Colet Vergara', 8, 12, 3, '2024-11-12', 'Transfer'),
(5, 'Samkuragi', 'D.', 'Akagami', '', 'Male', 'Sharer', 'Flood', 'Kasanyangan', '2024-11-21', 22, 'Idol', '09378179246', '11', 'totally', '111', 'Colet Vergara', 8, 16, 3, '2024-11-12', 'Transfer'),
(6, 'Peter', 'D', 'Parker', 'II', 'Male', 'Owner', 'Pandemic', 'Guiwan', '2005-02-02', 12, 'Superhero', '12345696969', '20', 'totally', '69', 'Peter D Parker II', 2, 1, 2, '2024-11-12', 'Transfer'),
(7, 'Tony', 'D', 'Stark', '', 'Male', 'Owner', 'Fire', 'Guiwan', '2018-02-12', 20, 'Billionaire', '123456789', '999999999999', 'totally', '69', 'Tony D Stark', 2, 3, 3, '2024-11-12', 'Transferred'),
(9, 'Micheal', 'D.', 'Jordan', '', 'Female', 'Owner', 'Flood', 'Guiwan', '1999-06-19', 24, 'Small Forward11', '91144', '99999911', 'totally', '12369', 'Micheal D. Jordan', 2, 1, 1, '2024-11-13', 'Moved-out'),
(10, 'John', 'D.', 'Cena', 'III', 'Male', 'Owner', 'Flood', 'Guiwan', '2024-10-30', 22, 'Billionaire', '1234569', '123', 'totally', '69', 'John D. Cena', 2, 5, 2, '2024-11-17', 'Transfer'),
(11, 'Maloi', 'D.', 'Ricalde', '', 'Male', 'Owner', 'Flood', 'Guiwan', '2024-11-29', 22, 'Idol', '123', '12345', 'totally', '33', 'Maloi D. Ricalde', 2, 4, 4, '2024-11-17', 'Transferred'),
(12, 'Kai', 'D', 'Zoku', '', 'Male', 'Owner', 'War', 'Guiwan', '2024-11-21', 22, 'Legend', '12345678', '12345', 'totally', '69', 'Kai D Zoku', 2, 4, 4, '2024-11-17', 'Transfer'),
(13, 'Maloi2222', 'D.', 'Ricalde', '', 'Male', 'Owner', 'Flood', 'Guiwan', '1999-02-20', 25, 'Idol', '9092738051', '12223', 'totally', '11111', 'Colet Vergara', 2, 5, 5, '2024-11-12', 'Admitted'),
(15, 'Jimmy', 'D', 'Butler', '', 'Male', 'Owner', 'weqe', 'Guiwan', '1999-06-23', 25, 'Small Forward', '312312', '21323', 'totally', '400', 'weqwe weqw Cenaqwe', 2, 2, 2, '2024-11-22', 'Transferred'),
(23, 'Derrick', '', 'Rose', '', 'Female', 'Sharer', 'Flood', 'Guiwan', '1999-08-20', 25, 'Point Guard', '123', '20000', 'totally', '12345', 'Dirk Nowitzki', 2, 1, 1, '2024-12-03', 'Transferred'),
(24, 'Lalisa', 'd', 'Manoban', '', 'Female', 'Owner', 'Fire', 'Kasanyangan', '1998-02-04', 26, 'Idol', '12345', '3333333333333', 'totally', '123', 'Lalisa  Manoban', 2, 1, 1, '2024-12-03', 'Transferred'),
(25, 'Odin', '', 'Asgard', '', 'Male', 'Owner', 'Flood', 'Kasanyangan', '2000-02-09', 24, 'King', '32222', '3333333', 'totally', '12312313', 'Odin  Asgard', 8, 16, 3, '2024-12-03', 'Admitted'),
(26, 'Randy', 'D', 'Orton', 'Jr', 'Male', 'Owner', 'Fire', 'Kasanyangan', '2005-02-09', 19, 'Wrestler', '44444444', '4444444', 'totally', '11111', 'Randy D Orton Jr', 8, 11, 11, '2024-12-03', 'Admitted'),
(27, 'Sam', 'D.', 'Ricalde', '', 'Male', 'Owner', 'Flood', 'Guiwan', '2020-06-10', 4, 'Idol', '42342', '323', 'totally', '3242', 'Sam D. Ricalde', 2, 1, 12, '2024-12-03', 'Transfer'),
(28, 'Celso', 'Q', 'Lobregat', '', 'Male', 'Owner', 'Fire', 'Guiwan', '2019-01-08', 5, 'Mayor', '322', '34234', 'totally', '', 'Celso Q Lobregat', 2, 10, 10, '2024-12-03', 'Admitted'),
(29, 'Micheal', 'D', 'Jordan', '', 'Male', 'Owner', 'Flood', 'Kasanyangan', '2023-01-03', 1, 'Small Forward', '3333333', '333333', 'totally', '234234', 'Micheal D Jordan', 8, 11, 11, '2024-12-03', 'Admit'),
(30, 'One', '', 'Zambo', '', 'Male', 'Owner', 'Fire', 'Kasanyangan', '2024-01-17', 0, 'Idol', '21323', '123123', 'totally', '', 'One  Zambo', 8, 12, 12, '2024-12-03', 'Admit'),
(32, 'Micheal', 'D.', 'Cena', '', 'Male', 'Owner', 'Flood', 'Kasanyangan', '2013-02-03', 11, 'Billionaire', '123123', '2312', 'totally', '312312', 'Micheal D. Cena', 8, 16, 2, '2024-12-03', 'Transfer'),
(34, 'Jimmy', 'G', 'Jordan', '', 'Male', 'Owner', 'Fire', 'Guiwan', '2004-01-02', 20, 'Small Forward', '34534534', '45345345', 'totally', '', 'Jimmy G Jordan', 8, 11, 11, '2024-12-03', 'Admitted'),
(35, 'Tony', 'D', 'Stark', '', 'Male', 'Owner', 'Fire', 'Kasanyangan', '2018-02-12', 20, 'Billionaire', '123456789', '999999999999', 'totally', '69', 'Tony D Stark', 8, 12, 2, '2024-11-12', 'Admitted'),
(43, 'John', 'D.', 'Cena', 'III', 'Male', 'Owner', 'Flood', 'Guiwan', '2024-10-30', 22, 'Billionaire', '1234569', '123', 'totally', '69', 'John D. Cena', 2, 1, 5, '2024-11-17', 'Transferred'),
(44, 'Lalisa', 'd', 'Manoban', '', 'Female', 'Owner', 'Fire', 'Kasanyangan', '1998-02-04', 26, 'Idol', '12345', '3333333333333', 'totally', '123', 'Lalisa  Manoban', 2, 3, 1, '2024-12-03', 'Admitted'),
(45, 'John', 'D.', 'Cena', 'III', 'Male', 'Owner', 'Flood', 'Guiwan', '2024-10-30', 22, 'Billionaire', '1234569', '123', 'totally', '69', 'John D. Cena', 2, 10, 5, '2024-11-17', 'Transfer'),
(46, 'Jimmy', 'D', 'Butler', '', 'Male', 'Owner', 'weqe', 'Guiwan', '1999-06-23', 25, 'Small Forward', '312312', '21323', 'totally', '400', 'weqwe weqw Cenaqwe', 2, 1, 1, '2024-11-22', 'Transfer'),
(47, 'Jimmy', 'D', 'Butler', '', 'Male', 'Owner', 'weqe', 'Guiwan', '1999-06-23', 25, 'Small Forward', '312312', '21323', 'totally', '400', 'weqwe weqw Cenaqwe', 2, 3, 1, '2024-11-22', 'Transfer'),
(48, 'Derrick', '', 'Rose', '', 'Female', 'Sharer', 'Flood', 'Guiwan', '1999-08-20', 25, 'Point Guard', '123', '20000', 'totally', '12345', 'Dirk Nowitzki', 2, 2, 1, '2024-12-03', 'Admitted'),
(49, 'Sam', 'D.', 'Ricalde', '', 'Male', 'Owner', 'Flood', 'Guiwan', '2020-06-10', 4, 'Idol', '42342', '323', 'totally', '3242', 'Sam D. Ricalde', 2, 4, 1, '2024-12-03', 'Transfer'),
(50, 'sdas', 'sdas', 'Cena', '', 'Female', 'Owner', 'Flood', 'Guiwan', '2019-05-28', 5, 'sdasd', '123123', '23123', 'totally', '', 'sdas sdas Cena', 2, 1, 1, '2025-01-07', 'Admitted'),
(51, 'sdasd', 'sdasd', 'asdas', 'sdas', 'Male', 'Owner', 'Flood', 'Guiwan', '2019-01-08', 6, 'adasd', '23423', '324324', 'totally', '', 'sdasd sdasd asdas sdas', 2, 4, 4, '2025-01-09', 'Admitted'),
(52, 'dasdasd', 'sdasd', 'asad', '', 'Male', 'Owner', 'Fire', 'Guiwan', '2023-01-16', 1, '12asdas', '12345678901', '23234', 'totally', '34234', 'dasdasd sdasd asad', 2, 3, 3, '2025-01-09', 'Admitted'),
(53, 'xcasc', 'casc', 'Cena', '', 'Male', 'Owner', 'Fire', 'Kasanyangan', '2025-01-09', 0, '4342', '42345', '32423', 'totally', '', 'xcasc casc Cena', 8, 16, 16, '2025-01-09', 'Admitted'),
(54, 'fsdfs', 'dfsd', 'dfsd', 'dfsd', 'Male', 'Owner', 'qwe', 'Guiwan', '2017-01-10', 7, '23423', '3423', '3423', 'totally', '2313', 'fsdfs dfsd dfsd dfsd', 2, 4, 4, '2025-01-09', 'Admitted'),
(55, 'dfsd', 'sdfs', 'fsdfs', '', 'Male', 'Owner', 'Flood', 'Shohoku', '2018-02-07', 6, 'asd', '2332', '3423', 'totally', '3423', 'dfsd sdfs fsdfs', 28, 28, 28, '2025-01-10', 'Admitted');

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
(1, 'Admitted pisut', '2024-11-15 16:35:33', 'notify', 9),
(2, 'Admitted', '2024-11-15 16:35:33', 'notify', 1),
(3, 'Admitted', '2024-11-15 16:35:33', 'notify', 2),
(4, 'Admitted', '2024-11-15 16:35:33', 'notify', 3),
(5, 'Admitted', '2024-11-15 16:35:33', 'notify', 4),
(6, 'Admitted', '2024-11-15 16:35:33', 'notify', 5),
(7, 'Admitted', '2024-11-15 16:35:33', 'notify', 6),
(8, 'Admitted', '2024-11-15 16:35:33', 'notify', 7),
(17, '3 pieces of Chicken Joy have been distributed.', '2024-11-15 17:06:02', 'notify', 3),
(18, '3 pieces of Chicken Joy have been distributed.', '2024-11-15 17:06:02', 'notify', 9),
(21, '4 pieces of Chicken Joy have been distributed.', '2024-11-15 17:06:36', 'notify', 3),
(22, '4 pieces of Chicken Joy have been distributed.', '2024-11-15 17:06:36', 'notify', 4),
(23, '2 pieces of Chicken Joy have been returned.', '2024-11-15 17:06:42', 'notify', 3),
(24, '2 pieces of Chicken Joy have been returned.', '2024-11-15 17:06:42', 'notify', 4),
(25, '2 pieces of Chicken Joy have been returned.', '2024-11-15 17:15:44', 'notify', 3),
(26, '1 piece of Chicken Joy have been returned.', '2024-11-15 17:15:44', 'notify', 4),
(27, '1 piece of Chicken Joy have been returned.', '2024-11-15 17:16:13', 'notify', 4),
(28, 'Moved-out', '2024-11-15 16:35:33', 'notify', 1),
(37, 'Moved-out', '2024-11-15 18:48:17', 'notify', 1),
(38, 'Admitted back', '2024-11-15 18:55:49', 'notify', 1),
(41, 'Requesting transfer to Don Gems', '2024-11-15 19:19:55', 'notify', 9),
(42, 'Transferred', '2024-11-15 19:39:49', 'notify', 9),
(43, 'Requesting transfer to Western Mindanao State University Main Campus', '2024-11-15 19:52:10', 'notify', 9),
(44, 'Transfer approved.', '2024-11-15 19:52:47', 'notify', 9),
(45, 'Updated', '2024-11-15 20:42:11', 'notify', 9),
(58, 'Updated', '2024-11-17 18:30:37', 'notify', 2),
(59, 'Updated', '2024-11-17 18:38:39', 'notify', 1),
(60, 'Admitted', '2024-11-17 20:11:16', 'notify', 10),
(61, 'Requesting transfer to Barangay Hall', '2024-11-17 20:24:07', 'notify', 1),
(62, 'Transfer approved.', '2024-11-17 20:30:36', 'notify', 1),
(63, 'Requesting transfer to Don Gems', '2024-11-17 20:43:01', 'notify', 1),
(64, 'Admitted', '2024-11-17 20:44:54', 'notify', 11),
(65, 'Admitted', '2024-11-17 20:51:24', 'notify', 12),
(66, '33 packs of Magi have been distributed.', '2024-11-17 21:58:25', 'notify', 6),
(67, '33 packs of Magi have been distributed.', '2024-11-17 21:58:25', 'notify', 9),
(68, '33 packs of Magi have been distributed.', '2024-11-17 21:58:25', 'notify', 10),
(69, '33 packs of Magi have been returned.', '2024-11-17 21:58:43', 'notify', 6),
(70, '33 packs of Magi have been returned.', '2024-11-17 21:58:43', 'notify', 9),
(71, '32 packs of Magi have been returned.', '2024-11-17 21:58:43', 'notify', 10),
(72, '1 pack of Magi have been returned.', '2024-11-17 21:58:52', 'notify', 10),
(73, '2 packs of Cat Food have been distributed.', '2024-11-17 22:29:46', 'notify', 6),
(74, '22 packs of Magi have been distributed.', '2024-11-18 02:59:03', 'notify', 6),
(75, '5 packs of Magi have been distributed.', '2024-11-18 09:05:42', 'notify', 6),
(76, '5 packs of Magi have been distributed.', '2024-11-18 09:05:42', 'notify', 9),
(77, '5 packs of Magi have been distributed.', '2024-11-18 09:05:42', 'notify', 10),
(78, '129 packs of Magi have been returned.', '2024-11-18 09:25:42', 'notify', 6),
(79, '23 packs of Magi have been returned.', '2024-11-18 09:25:42', 'notify', 9),
(80, '7 packs of Magi have been returned.', '2024-11-18 09:25:42', 'notify', 10),
(81, '2 packs of Magi have been distributed.', '2024-11-18 09:26:20', 'notify', 6),
(82, '2 packs of Magi have been distributed.', '2024-11-18 09:26:20', 'notify', 9),
(86, '22 packs of Magi have been distributed.', '2024-11-18 09:29:04', 'notify', 6),
(87, '22 packs of Magi have been distributed.', '2024-11-18 09:29:04', 'notify', 9),
(88, '22 packs of Magi have been distributed.', '2024-11-18 09:29:04', 'notify', 10),
(91, 'Updated', '2024-11-18 10:33:57', 'notify', 1),
(92, 'Requesting transfer to Don Gems', '2024-11-18 10:48:11', 'notify', 9),
(93, 'Requesting transfer to Don Gems', '2024-11-18 10:52:48', 'notify', 6),
(94, '2 packs of Magi have been distributed.', '2024-11-18 11:14:12', 'notify', 10),
(96, '2 packs of Cat Food have been distributed.', '2024-11-18 11:41:22', 'notify', 10),
(98, '2 packs of Cat Food have been distributed.', '2024-11-18 11:43:31', 'notify', 10),
(99, '2 packs of Magi have been redistributed.', '2024-11-18 11:49:31', 'notify', 10),
(101, '2 packs of Magi have been redistributed.', '2024-11-18 12:02:52', 'notify', 10),
(103, '20 packs of Magi have been redistributed.', '2024-11-18 12:03:03', 'notify', 10),
(105, 'Updated', '2024-11-18 13:31:04', 'notify', 10),
(109, 'Requesting transfer to Don Gems', '2024-11-18 14:02:02', 'notify', 10),
(110, 'Transfer approved.', '2024-11-18 14:27:15', 'notify', 10),
(111, 'Transfer approved.', '2024-11-18 14:27:19', 'notify', 1),
(112, 'Requesting transfer to Western Mindanao State University Main Campus', '2024-11-18 14:27:38', 'notify', 3),
(113, 'Transfer approved.', '2024-11-18 14:27:43', 'notify', 3),
(115, 'Transfer approved.', '2024-11-21 19:08:24', 'notify', 10),
(116, 'Transfer approved.', '2024-11-21 19:46:36', 'notify', 10),
(117, 'Transfer approved.', '2024-11-21 20:05:59', 'notify', 10),
(118, 'Requesting transfer to Western Mindanao State University Main Campus', '2024-11-21 20:09:14', 'notify', 10),
(119, 'Admitted', '2024-11-22 10:20:34', 'notify', 15),
(121, '1 piece of Shabu have been distributed.', '2024-11-25 01:19:23', 'notify', 2),
(122, '1 pack of Evacuation Kit have been distributed.', '2024-12-02 11:21:19', 'notify', 9),
(123, 'Moved-out', '2024-12-02 11:24:24', 'notify', 9),
(124, 'Requesting transfer to Zamcelco', '2024-12-02 12:32:55', 'notify', 1),
(125, 'Requesting transfer to Basketball Court of Bugguk', '2024-12-02 12:36:00', 'notify', 4),
(127, 'Requesting transfer to Zamcelco', '2024-12-02 12:57:16', 'notify', 2),
(128, 'Requesting transfer to Don Gems', '2024-12-02 13:01:11', 'notify', 3),
(130, 'Updated', '2024-12-03 12:43:19', 'notify', 15),
(131, 'Updated', '2024-12-03 13:45:55', 'notify', 15),
(132, 'Requesting transfer to Don Gems', '2024-12-03 14:00:41', 'notify', 15),
(137, 'Transfer approved.', '2024-12-03 14:22:07', 'notify', 15),
(139, 'Admitted', '2024-12-03 18:53:32', 'notify', 24),
(140, 'Admitted', '2024-12-03 19:40:08', 'notify', 25),
(141, 'Pending for approval', '2024-12-03 19:42:49', 'notify', 26),
(142, 'Pending for approval', '2024-12-03 19:51:36', 'notify', 27),
(143, 'Admitted', '2024-12-03 19:54:23', 'notify', 28),
(144, 'Pending for approval', '2024-12-03 19:55:48', 'notify', 29),
(145, 'Pending for approval', '2024-12-03 19:58:22', 'notify', 30),
(147, 'Admitted', '2024-12-03 20:46:15', 'notify', 32),
(149, 'Pending for approval', '2024-12-03 20:54:30', 'notify', 34),
(150, 'Admission approved.', '2024-12-03 21:34:57', 'notify', 26),
(157, 'Requesting transfer to Grandstand', '2024-12-05 21:10:02', 'notify', 32),
(158, 'Admission approved.', '2024-12-05 21:10:51', 'notify', 27),
(159, 'Requesting transfer to Grandstand', '2024-12-05 21:12:40', 'notify', 25),
(160, 'Transfer approved.', '2024-12-05 21:14:13', 'notify', 25),
(161, 'Requesting transfer to Grandstand', '2024-12-05 21:21:34', 'notify', 24),
(162, 'Requesting transfer to Don Gems', '2024-12-05 21:25:13', 'notify', 27),
(163, 'Requesting transfer to Grandstand', '2024-12-05 21:35:42', 'notify', 5),
(166, 'Requesting transfer to Zamboanga City High School Main', '2024-12-05 22:29:01', 'notify', 7),
(167, 'Requesting transfer to Zamboanga City High School Main', '2024-12-05 22:29:01', 'notify', 35),
(168, 'Transfer approved.', '2024-12-05 22:29:48', 'notify', 35),
(169, 'Transfer approved.', '2024-12-05 22:29:48', 'notify', 7),
(170, 'Requesting transfer to Basketball Court of Bugguk', '2024-12-05 22:41:13', 'notify', 35),
(171, 'Transfer approved.', '2024-12-05 22:42:20', 'notify', 35),
(172, 'Requesting transfer to Grandstand', '2024-12-05 23:07:30', 'notify', 12),
(175, 'Transfer approved.', '2024-12-05 23:11:18', 'notify', 24),
(176, 'Requesting transfer to Basketball Court of Bugguk', '2024-12-05 23:39:01', 'notify', 11),
(177, 'Requesting transfer to Basketball Court of Bugguk', '2024-12-05 23:40:03', 'notify', 11),
(178, 'Requesting transfer to Basketball Court of Bugguk', '2024-12-05 23:43:58', 'notify', 11),
(179, 'Requesting transfer to Basketball Court of Bugguk', '2024-12-05 23:44:06', 'notify', 11),
(180, 'Requesting transfer to Barangay Hall of Bugguk', '2024-12-05 23:44:49', 'notify', 11),
(181, 'Requesting transfer to Barangay Hall of Bugguk', '2024-12-05 23:45:30', 'notify', 11),
(182, 'Requesting transfer to Basketball Court of Bugguk', '2024-12-05 23:47:03', 'notify', 11),
(183, 'Requesting transfer to Basketball Court of Bugguk', '2024-12-05 23:48:59', 'notify', 11),
(186, 'Requesting transfer to Grandstand', '2024-12-06 00:01:34', 'notify', 11),
(189, 'Transfer approved.', '2024-12-06 00:03:08', 'notify', 11),
(190, 'Requesting transfer to Don Gems', '2024-12-06 00:10:40', 'notify', 24),
(193, 'Transfer approved.', '2024-12-06 00:11:05', 'notify', 24),
(194, 'Transfer declined', '2024-12-07 23:49:45', 'notify', 15),
(195, 'Transfer declined', '2024-12-08 11:02:54', 'notify', 27),
(196, 'Transfer declined', '2024-12-08 11:03:04', 'notify', 10),
(201, 'Requesting transfer to Grandstand', '2024-12-08 11:49:39', 'notify', 24),
(211, 'Transfer declined.', '2024-12-08 12:48:20', 'notify', 24),
(212, '1 piece of Beef Loaf have been distributed.', '2024-12-09 20:57:32', 'notify', 24),
(213, '1 piece of Beef Loaf have been distributed.', '2024-12-09 20:57:32', 'notify', 27),
(214, '1 piece of Beef Loaf have been redistributed.', '2024-12-09 20:58:40', 'notify', 24),
(215, '1 piece of Beef Loaf have been redistributed.', '2024-12-09 20:58:40', 'notify', 27),
(216, 'Requesting transfer to Don Gems', '2024-12-09 21:06:16', 'notify', 10),
(217, 'Requesting transfer to Don Gems', '2024-12-09 21:06:16', 'notify', 43),
(218, 'Transfer approved.', '2024-12-09 21:06:29', 'notify', 10),
(219, 'Transfer approved.', '2024-12-09 21:06:29', 'notify', 43),
(220, '1 pack of Evacuation Kit have been redistributed.', '2024-12-09 21:23:33', 'notify', 9),
(222, 'Requesting transfer to Avengers Tower', '2024-12-10 21:10:52', 'notify', 24),
(223, 'Requesting transfer to Avengers Tower', '2024-12-10 21:10:52', 'notify', 44),
(224, 'Requesting transfer to Zamcelco', '2024-12-10 21:25:01', 'notify', 10),
(225, 'Requesting transfer to Zamcelco', '2024-12-10 21:25:01', 'notify', 45),
(231, 'Admitted back', '2024-12-11 20:27:34', 'notify', 23),
(232, 'Transfer approved.', '2024-12-11 21:13:34', 'notify', 44),
(233, 'Transfer approved.', '2024-12-11 21:13:34', 'notify', 24),
(234, 'Transfer approved.', '2024-12-11 21:14:06', 'notify', 3),
(235, 'Requesting transfer to Don Gems', '2024-12-11 22:17:44', 'notify', 15),
(236, 'Requesting transfer to Don Gems', '2024-12-11 22:17:44', 'notify', 46),
(237, 'Transfer approved.', '2024-12-11 22:17:50', 'notify', 46),
(238, 'Transfer approved.', '2024-12-11 22:17:50', 'notify', 15),
(239, 'Requesting transfer to Avengers Tower', '2024-12-11 22:23:04', 'notify', 46),
(240, 'Requesting transfer to Avengers Tower', '2024-12-11 22:23:04', 'notify', 47),
(241, 'Requesting transfer to Zamboanga City High School Main', '2024-12-11 22:31:29', 'notify', 23),
(242, 'Requesting transfer to Zamboanga City High School Main', '2024-12-11 22:31:29', 'notify', 48),
(243, 'Transfer approved.', '2024-12-11 22:31:35', 'notify', 48),
(244, 'Transfer approved.', '2024-12-11 22:31:35', 'notify', 23),
(245, '23 pieces of Mega Sardines have been distributed.', '2024-12-11 22:33:11', 'notify', 3),
(246, '23 pieces of Mega Sardines have been distributed.', '2024-12-11 22:33:11', 'notify', 27),
(247, '23 pieces of Mega Sardines have been distributed.', '2024-12-11 22:33:11', 'notify', 46),
(248, 'Requesting transfer to Barangay Hall', '2024-12-12 00:14:38', 'notify', 27),
(249, 'Requesting transfer to Barangay Hall', '2024-12-12 00:14:38', 'notify', 49),
(250, 'Admitted back', '2024-12-12 00:15:02', 'notify', 13),
(251, '1 piece of Lucky Me Canton have been distributed.', '2024-12-12 01:14:20', 'notify', 48),
(252, '1 piece of Lucky Me Canton have been redistributed.', '2024-12-12 01:15:47', 'notify', 48),
(253, '1 piece of Mag have been distributed.', '2024-12-12 01:21:15', 'notify', 48),
(254, 'Admitted', '2025-01-07 19:33:58', 'notify', 50),
(255, 'Updated', '2025-01-09 11:42:28', 'notify', 50),
(256, 'Pending for approval', '2025-01-09 12:21:18', 'notify', 51),
(257, 'Admitted', '2025-01-09 12:36:08', 'notify', 52),
(258, 'Updated', '2025-01-09 12:46:13', 'notify', 50),
(259, 'Updated', '2025-01-09 12:46:38', 'notify', 50),
(260, 'Updated', '2025-01-09 12:46:50', 'notify', 50),
(261, '45 pieces of Beef Loaf have been distributed.', '2025-01-09 21:21:25', 'notify', 3),
(262, '50 pieces of Beef Loaf have been distributed.', '2025-01-09 21:21:25', 'notify', 46),
(263, '50 pieces of Beef Loaf have been distributed.', '2025-01-09 21:21:25', 'notify', 50),
(264, '1 piece of Beef Loaf have been redistributed.', '2025-01-09 22:05:05', 'notify', 24),
(265, '1 piece of Beef Loaf have been redistributed.', '2025-01-09 22:05:05', 'notify', 27),
(266, '1 piece of Beef Loaf have been redistributed.', '2025-01-09 22:05:05', 'notify', 3),
(267, '1 piece of Beef Loaf have been redistributed.', '2025-01-09 22:05:05', 'notify', 46),
(268, '1 piece of Beef Loaf have been redistributed.', '2025-01-09 22:05:05', 'notify', 50),
(269, '1 pack of Evacuation Kit have been distributed.', '2025-01-09 22:05:28', 'notify', 3),
(270, '1 pack of Evacuation Kit have been distributed.', '2025-01-09 22:05:28', 'notify', 46),
(271, '1 pack of Evacuation Kit have been distributed.', '2025-01-09 22:05:28', 'notify', 50),
(272, 'Admitted', '2025-01-09 22:11:29', 'notify', 53),
(273, 'Admitted', '2025-01-09 22:13:32', 'notify', 54),
(274, 'Admission approved.', '2025-01-09 23:22:28', 'notify', 51),
(275, 'Admitted', '2025-01-10 02:18:32', 'notify', 55);

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
(183, 3, 'admin', 'dfsd sdfs fsdfs admitted to TESTEST.', '2025-01-10 02:18:32', 'notify');

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
(3, 'May', 'D', 'Parker', '', 'Auntie', 'College', 'Female', 33, NULL, 'Auntie Wife', 6),
(4, 'Ben', 'D', 'Parker', '', 'Uncle', 'SHS', 'Male', 44, NULL, 'Plumber', 6),
(106, 'Lebron', 'D', 'James', 'Jr', 'Cousin', 'College', 'Female', 39, NULL, 'Power Forward', 9),
(107, 'Kobe', 'D', 'Bryant', '', 'Brother', 'College', 'Female', 24, NULL, 'Shooting Guard', 9),
(108, 'Aiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Female', 22, NULL, 'Idol', 9),
(111, 'Aiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Male', 33, NULL, 'Idol', 11),
(112, 'Maloi', 'S', 'Ricalde', '', 'Wife', 'SHS', 'Male', 22, NULL, 'Idol', 12),
(113, 'Mikha', 'Bini', 'Lim1', '', 'Brother', 'College', 'Male', 23, NULL, 'Red Hair Badass', 1),
(114, 'Maraiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Male', 22, NULL, 'Power Forward', 10),
(115, 'Aiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Male', 22, NULL, 'Power Forward', 2),
(120, 'Bam', '', 'Adebayo', '', 'Teammate', 'College', 'Male', 34, NULL, 'Power Forward', 15),
(123, 'Alex', '', 'Caruso', '', 'Mate', 'College', 'Male', 23, NULL, 'Shooting Guard', 23),
(124, 'Jennie', '', 'Kim', '', 'Sister', 'College', 'Female', 23, NULL, 'Idol', 24),
(125, 'Rose', '', 'Park', '', 'Sister', 'College', 'Female', 23, NULL, 'Idol', 24),
(126, 'Jisoo', '', 'Kim', '', 'Sister', 'College', 'Female', 25, NULL, 'Idol', 24),
(127, 'CM', '', 'Punk', '', 'Cousin', 'College', 'Male', 22, NULL, 'Wrestler', 26),
(128, 'Aiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Female', 33, NULL, 'Idol', 27),
(129, 'Beng', '', 'Climaco', '', 'Cousin', 'College', 'Female', 33, NULL, 'Zumba Dancer', 28),
(131, 'Lebron', 'D', 'James', '', 'Cousin', 'College', 'Male', 33, NULL, 'Power Forward', 32),
(133, 'Lebron', '', 'James', '', 'Cousin', 'College', 'Male', 22, NULL, 'Power Forward', 34),
(144, 'Maraiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Male', 22, NULL, 'Power Forward', 43),
(145, 'Jennie', '', 'Kim', '', 'Sister', 'College', 'Female', 23, NULL, 'Idol', 44),
(146, 'Rose', '', 'Park', '', 'Sister', 'College', 'Female', 23, NULL, 'Idol', 44),
(147, 'Jisoo', '', 'Kim', '', 'Sister', 'College', 'Female', 25, NULL, 'Idol', 44),
(148, 'Maraiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Male', 22, NULL, 'Power Forward', 45),
(149, 'Bam', '', 'Adebayo', '', 'Teammate', 'College', 'Male', 34, NULL, 'Power Forward', 46),
(150, 'Bam', '', 'Adebayo', '', 'Teammate', 'College', 'Male', 34, NULL, 'Power Forward', 47),
(151, 'Alex', '', 'Caruso', '', 'Mate', 'College', 'Male', 23, NULL, 'Shooting Guard', 48),
(152, 'Aiah', 'Bini', 'Arceta', '', 'Sister', 'College', 'Female', 33, NULL, 'Idol', 49),
(157, '23423', '23434', '34234', '3423', '23423', '23423', 'Male', 5, '2020-01-06', '3423', 51),
(158, '34234', '4234', '34234', '', '32423', '3423', 'Female', 3, '2022-01-03', '23423', 52),
(163, '32123', '231', '2312', '1231', '231', '1231', 'Male', 9, '2015-02-17', '2312', 50),
(164, '2312', '2312', '21312', '23123', '23123', '2312', 'Male', 18, '2006-06-07', '12312', 50),
(165, 'sds', 'fds', 'test', 'sdfd', 'fsdf', 'dfsdf', 'Male', 6, '2019-01-07', 'Idol', 54),
(166, '3423', '3423', '3434', '343', '23243', 'rwere', 'Male', 5, '2020-01-06', '12312', 55);

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
(135, 2, 'admin', 'Evacuation center has been inactive: Way Akkal', '2024-12-15 23:29:08', 'notify'),
(136, 2, 'admin', 'Evacuation center has been inactive: Barangay Hall', '2024-12-15 23:29:08', 'notify'),
(137, 2, 'admin', 'Evacuation center has been inactive: Western Mindanao State University', '2025-01-07 18:57:01', 'notify'),
(138, 2, 'admin', 'Evacuation center has been inactive: Gymnasium', '2025-01-07 18:57:01', 'notify'),
(139, 8, 'admin', 'Evacuation center has been inactive: Grandstand', '2025-01-07 18:57:01', 'notify'),
(140, 2, 'admin', 'Evacuation center has been inactive: Way Akkal', '2025-01-07 18:57:01', 'notify'),
(141, 2, 'admin', 'Evacuation center has been inactive: Barangay Hall', '2025-01-07 18:57:01', 'notify'),
(142, 3, 'admin', 'Evacuation center has been inactive: Don Gems', '2025-01-07 18:57:01', 'notify'),
(143, 2, 'admin', 'New Worker Account Added: aa asa Arceta', '2025-01-09 22:50:26', 'notify'),
(144, 2, 'admin', 'New Worker Account Added: sda sdas asd', '2025-01-09 22:53:51', 'notify'),
(145, 3, 'admin', 'New Worker Account Added: Maloi  Ryota', '2025-01-10 02:19:34', 'notify');

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
(1, '2024-11-06', '06:58:00', 'Kobe Bryant', 0, 10, 'pack', 3, 0),
(2, '2024-11-06', '06:58:00', 'Lebron James', 0, 6, 'pack', 3, 0),
(3, '2024-11-14', '07:21:00', 'Bini', 7, 7, 'piece', 17, 0),
(5, '2024-12-13', '20:13:00', 'Tabotabo', 15, 15, 'pack', 31, 0),
(6, '2024-12-05', '20:14:00', 'Tabotabo', 0, 22, 'piece', 30, 0),
(7, '2024-12-12', '20:19:00', 'John', 0, 15, 'piece', 30, 0),
(8, '2024-12-11', '21:53:00', 'Tabotabo', 22, 22, 'pack', 35, 0),
(9, '2024-12-19', '22:16:00', 'Tabotabo', 323, 323, 'pack', 3, 1),
(10, '2024-12-13', '07:23:00', 'Abdul Jakul', 0, 22, 'piece', 30, 0),
(11, '2024-12-12', '07:28:00', 'Tabotabo', 0, 12, 'piece', 30, 0),
(12, '2024-12-12', '22:37:00', 'Tabotabo', 22, 22, 'piece', 29, 0),
(13, '2025-01-09', '10:04:00', 'Tabotabo', 5, 10, 'piece', 30, 0);

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
(1, 'Mag', '123', 21, 22, 'piece', '', '2024-11-13', '17:47:00', 'Abdul Jakul', 2, 1, 0),
(2, 'Magi', '123', 22, 22, 'piece', '', '2024-11-13', '17:47:00', 'Abdul Jakul', 2, 1, 0),
(3, 'Magi', 'Donated from Singapore', 0, 143, 'pack', '../../assets/uploads/supplies/karimagi.jpg', '2024-11-13', '17:47:00', 'Salsalani', 5, 5, 1),
(4, 'Cat Food', 'Meow', 13, 21, 'pack', '../../assets/uploads/supplies/download (1).jpg', '2024-11-14', '17:53:00', 'Abdul Jakul Salsalani', 5, 5, 0),
(5, 'Chicken', 'Bida ang saya', 22, 22, 'piece', '../../assets/uploads/supplies/download (2).jpg', '2024-11-13', '18:36:00', 'Kentucky Fried Chicken', 5, 6, 0),
(12, 'Shabu', 'Sarap mag Canton', 33, 33, 'piece', '', '2024-11-14', '21:00:00', 'Tabotabo', 2, 1, 0),
(13, 'Lucky Me Canton', 'Sarap mag Canton', 31, 33, 'piece', '', '2024-11-14', '21:00:00', 'Tabotabo', 2, 1, 0),
(14, 'Magi Lucky Me Quickie Sotanghon', 'Sarap mag Canton', 33, 33, 'pack', '', '2024-11-14', '21:00:00', 'Tabotabo', 2, 1, 0),
(17, 'Maloi', 'Bini', 1, 1, 'piece', '../../assets/uploads/supplies/bini_maloi.png', '2024-11-14', '23:05:00', 'Heart', 5, 1, 0),
(20, 'Keso De Balls', 'Bini', 21, 22, 'piece', '', '2024-11-08', '09:40:00', 'Abdul Jakul', 5, 1, 0),
(21, 'Leche Plan', '123', 33, 33, 'piece', '', '2024-11-07', '09:45:00', 'Tabotabo', 5, 3, 0),
(26, 'Chicken Joy', 'Bida ang saya', 22, 22, 'piece', '../../assets/uploads/supplies/download (2).jpg', '2024-11-21', '09:01:00', 'Tabotabo', 3, 1, 0),
(28, 'San Marino', 'Fitness Food', 10, 10, 'pack', '', '2024-11-30', '10:02:00', 'Tabotabo', 1, 1, 1),
(29, 'Mega Sardines', 'Delata', 0, 69, 'piece', '', '2024-11-27', '10:10:00', 'Abdul Jakul', 1, 1, 1),
(30, 'Beef Loaf', 'Donated from Singapore', 0, 82, 'piece', '', '2024-11-28', '10:11:00', 'Abdul Jakul', 1, 1, 1),
(31, 'Evacuation Kit', 'for moving out evacuees', 93, 100, 'pack', '../../assets/uploads/supplies/box.jpg', '2024-12-02', '10:56:00', 'Barangay Captain', 1, 14, 1),
(32, 'Starter Kit', 'for moving out evacuees', 95, 100, 'pack', '../../assets/uploads/supplies/download (4).jpg', '2024-12-02', '10:56:00', 'Barangay Captain', 1, 14, 1),
(35, 'Starter Kit', 'Required before moving out.', 0, 0, 'pack', '', '2024-12-05', '21:51:04', 'Barangay', 26, 14, 1);

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
(1, 'worker@gmail.com', 'Worker123', 'Ryota', 'D.', 'Miyagi', '', '$2y$10$qgHnhQJT1K2D.hEfuCCb0.PNDlgsSwiZJTFzOq7LAthmftb1bHIMe', '../../assets/uploads/profiles/1733368677_ryota.jpg', '2014-11-20', 30, 'Female', 'Technician', 'Zamboanga City', 'Guiwan', '12345678901', '../../assets/uploads/appointments/1733368677_box.jpg', 'inactive', '2024-12-12 03:47:30', NULL, 2),
(4, 'bini@gmail.com', 'RyotaTeamManager', 'Ayako', 'D', 'Ryota', '', '$2y$10$FRKQZtVToUBv4S0.tXAVhuXXgnh2VWV0ejI23KzxMCMQOvUB5yi7a', '../../assets/uploads/profiles/ayako.jpg', '2014-11-17', 22, 'Female', 'Team Manager', 'Zamboanga City', 'Guiwan', '12345555', '../../assets/uploads/appointments/1232342112.jpg', 'inactive', '2024-11-10 20:27:53', NULL, 2),
(5, 'binimaloiworker@gmail.com', 'InomataBadmintonPlayer', 'Taiki', '', 'Inomata', '', '$2y$10$ppSCKg5seNV8IWbL.m9PtuT8ZTX1RVTo8taBg3Gri1NOeN/QZgB06', '../../assets/uploads/profiles/haruko.jpg', '1998-07-06', 26, 'Male', 'Badminton Player', 'Zamboanga City', 'Guiwan', '123456', '../../assets/uploads/appointments/download (2).jpg', 'inactive', '2024-11-25 00:40:20', NULL, 2),
(7, 'binimaloi35@gmail.com', 'HarukoMyLoves', 'Haruko', '', 'Akagi', '', '$2y$10$J.IJfrP6PamNlzSM3ooB6.Us3wt8tkjBOqz1VVinbesta0Hk/GHaK', '../../assets/uploads/profiles/haruko.jpg', '2003-02-13', 21, 'Male', 'Barangay Captain', 'Zamboanga City', 'Guiwan', '1221323', '../../assets/uploads/appointments/haruko.jpg', 'inactive', '2024-12-03 21:51:00', '', 2),
(9, 'binimaloi121312@gmail.com', 'asd21323', 'sda', 'sdas', 'asd', '', '$2y$10$kc23PFfsA/j9vl7Xh/AkduH2Yfy9R7HzKkMM7jWFpG.WpfBCBCwdW', '', '2018-01-08', 7, 'Male', '21323', 'Zamboanga City', 'Guiwan', '121', '../../assets/uploads/appointments/Screenshot 2025-01-09 210107.png', 'active', '2025-01-09 22:54:52', NULL, 2),
(10, 'samcena.902604@gmail.com', 'RyotaBarangayCaptain', 'Maloi', '', 'Ryota', '', '$2y$10$pP1RXl7RgyIVszUOjM1Zs.s/VSEnrIuPEuWPdSwCfERP6.I82s7Vi', '', '2025-01-06', 0, 'Male', 'Barangay Captain', 'Zamboanga City', 'Shohoku', '1111111111', '../../assets/uploads/appointments/Screenshot 2025-01-09 210038.png', 'active', NULL, NULL, 28);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `assigned_worker`
--
ALTER TABLE `assigned_worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `barangay`
--
ALTER TABLE `barangay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `distribute`
--
ALTER TABLE `distribute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `evacuation_center`
--
ALTER TABLE `evacuation_center`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `evacuees`
--
ALTER TABLE `evacuees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `evacuees_log`
--
ALTER TABLE `evacuees_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `supply`
--
ALTER TABLE `supply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
