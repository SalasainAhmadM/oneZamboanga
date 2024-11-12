-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2024 at 01:36 PM
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
(1, 'Mitsuyoshi', 'D.', 'Anzai', 'II', 'superadmin@gmail.com', 'superadmin12', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/Mitsuyoshi Anzai.jpg', '', '', '', '', NULL, '0', 'superadmin', '', 'active', '2024-11-12 19:12:40', NULL),
(2, 'Hanamichi', 'D.', 'Sakuragi', '', 'admin@gmail.com', 'admin123', '$2y$10$SvIjuJHT3AGOT6dvh2VG1uuxjsDtIiWoTQ/Lhjp0Ycgtecv.UHAiS', '../../assets/uploads/profiles/sakuragi.png', 'Male', 'Captain', 'Zamboanga', 'Guiwan', NULL, '2147483647', 'admin', '../../assets/uploads/appointments/journal format.png', 'active', '2024-11-12 18:59:33', ''),
(3, 'Kaede', 'D', 'Rukawa', '', 'binimaloi@gmail.com', 'RukawaAdmin', '$2y$10$RPj4ozHDoaXIgjSWtyIyAOConZm4SK0Edbtwtt15FQZVLT7C.qho.', '../../assets/uploads/profiles/rukawa.jpg', 'Male', 'Ace Player', 'Zamboanga City', 'Shohoku', NULL, '2147483647', 'admin', '../../assets/uploads/appointments/bini.jpg', 'inactive', '2024-10-31 21:42:40', ''),
(4, 'Maloi', 'D', 'Ricalde', '', 'kaizoku@gmail.com', 'RicaldeAdmin', '$2y$10$.cQt6NadI2oLMwFn3Ne2T.gvb.fUGwA0A06ICcgeOpvmqvX2s4Obm', '../../assets/uploads/profiles/bini_maloi.png', 'Female', 'Main Vocalist', 'Zamboanga City', 'Kasanyangan', NULL, '09551078233', 'admin', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'active', '2024-11-08 23:02:03', NULL),
(5, 'Aiah', 'D', 'Arceta', '', 'masterjho@gmail.com', 'ArcetaAdmin', '$2y$10$t8XryzbGRzJaEa6PW4Zz6e/fopHBGky8oTRhaN/sbgMI21EdM49q.', '', 'Female', 'Main Visual', 'Zamboanga City', 'Talon-talon', NULL, '123456789', 'admin', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'active', '2024-11-08 23:27:32', NULL),
(6, 'Mikha', 'D', 'Lim', '', 'samcena@gmail.com', 'LimAdmin', '$2y$10$0K8XeBqdvMj0I.iaEF4hSOId39U7kYlZCTgF1LJHOyOUUdhJTAKda', '../../assets/uploads/profiles/bini_mikha.png', 'Female', 'Main Rapper', 'Zamboanga City', 'Sta.Maria', '', '09881078332', 'admin', '../../assets/uploads/appointments/1.png', 'active', '2024-11-08 23:36:17', NULL),
(7, 'Colet', 'D', 'Vergara', '', 'binimaloi2@gmail.com', 'VergaraAdmin', '$2y$10$QcOe1IvngDYKv4TJB6EdQOqgLcYOvfG3C3FBGws.gowDxrdbZLGg.', '../../assets/uploads/profiles/bini_colet.png', 'Female', 'Lead Vocalist', 'Zamboanga City', 'San Roque', '../../assets/uploads/barangay/3.png', '0988106322', 'admin', '../../assets/uploads/appointments/bini.jpg', 'active', NULL, ''),
(8, 'Mark', 'D', 'Tabotabo', '', 'binimaloi352@gmail.com', 'TabotaboAdmin', '$2y$10$YrJvTubDon2uf/MWbVFWsuZFuUihNV3xtCR5wqFDdE3j/w13oKQYO', '../../assets/uploads/profiles/archi.jpg', 'Male', 'Programmer', 'Zamboanga City', 'Kasanyangan', '../../assets/uploads/barangay/recipic.png', '0955107334', 'admin', '../../assets/uploads/appointments/1232342112.jpg', 'active', '2024-11-12 19:11:44', NULL);

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
(14, '2024-11-10 16:46:29', 'assigned', 1, 2);

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
(5, 'WMSU', 'Guiwan Street', '40', 2, '../../assets/uploads/evacuation_centers/Screenshot 2024-10-19 173936.png', '2024-11-10 12:18:59'),
(10, 'Zamcelco', 'Di Makita Street', '22', 2, '../../assets/uploads/evacuation_centers/weathering with you.jpg', '2024-11-12 10:29:54'),
(11, 'Barangay Hall', 'Kasanyangan', '22', 8, '../../assets/uploads/evacuation_centers/the_valley_of_the_wind__nausicaa_by_syntetyc_d5wb09s-fullview.jpg', '2024-11-12 11:12:18');

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
  `date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `evacuees`
--

INSERT INTO `evacuees` (`id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `gender`, `position`, `disaster_type`, `barangay`, `birthday`, `age`, `occupation`, `contact`, `monthly_income`, `damage`, `cost_damage`, `house_owner`, `admin_id`, `evacuation_center_id`, `date`) VALUES
(1, 'Sam', 'D.', 'Cena', '', 'female', 'Owner', 'Flood', 'Guiwan', '2024-11-13', 22, 'Idol', '09092738051', '12223', 'totally', '11111', 'Colet Vergara', 2, 1, '2024-11-12'),
(2, 'Sammy', 'D.', 'Dragon', '', 'Male', 'Owner', 'Flood', 'Guiwan', '2024-11-10', 22, 'Idol', '09278935682', '20000', 'totally', '10000', 'Colet Vergara', 2, 2, '2024-11-12'),
(3, 'Samkuragi', 'D.', 'Smile', '', 'Male', 'Sharer', 'Flood', 'Guiwan', '2024-11-21', 22, 'Idol', '09116464286', '11', 'totally', '111', 'Colet Vergara', 2, 3, '2024-11-12'),
(4, 'Samkuragi', 'D.', 'Smile', '', 'Male', 'Sharer', 'Flood', 'Guiwan', '2024-11-21', 22, 'Idol', '09745514414', '11', 'totally', '111', 'Colet Vergara', 2, 3, '2024-11-12'),
(5, 'Samkuragi', 'D.', 'Smile', '', 'Male', 'Sharer', 'Flood', 'Guiwan', '2024-11-21', 22, 'Idol', '09378179246', '11', 'totally', '111', 'Colet Vergara', 2, 3, '2024-11-12'),
(6, 'Peter', 'D', 'Parker', 'II', 'Male', 'Owner', 'Pandemic', 'Guiwan', '2005-02-02', 12, 'Superhero', '12345696969', '20', 'totally', '69', 'Peter D Parker II', 2, 5, '2024-11-12'),
(7, 'Tony', 'D', 'Stark', '', 'Male', 'Owner', 'Fire', 'Guiwan', '2018-02-12', 20, 'Billionaire', '123456789', '999999999999', 'totally', '69', 'Tony D Stark', 2, 3, '2024-11-12');

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
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('notify','viewed','cleared') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `logged_in_id`, `user_type`, `notification_msg`, `created_at`, `status`) VALUES
(1, 2, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-11-10', 'cleared'),
(2, 2, 'admin', 'New Evacuation Center Added: WMSU', '2024-11-10', 'cleared'),
(3, 2, 'admin', 'New Worker Account Added: Ayako D Ryota', '2024-11-10', 'cleared'),
(4, 1, 'admin', 'New Admin Account Added: Mark D Tabotabo', '2024-11-10', 'notify'),
(5, 2, 'admin', 'Worker Account Has Been Deleted: Takenori Akagi', '2024-11-10', 'cleared'),
(6, 2, 'admin', 'Worker Account Has Been Deleted: Kiminobu Kogure', '2024-11-10', 'cleared'),
(22, 2, 'admin', 'Evacuation center has been inactive: Barangay Hall', '2024-11-12', 'cleared'),
(27, 2, 'admin', 'New Evacuation Center Added: Zamcelco', '2024-11-12', 'cleared'),
(28, 1, 'admin', 'New Evacuation Center Added: Zamcelco at Barangay: Guiwan', '2024-11-12', 'cleared'),
(29, 2, 'admin', 'Evacuation center has been inactive: Zamcelco', '2024-11-12', 'notify'),
(30, 8, 'admin', 'New Evacuation Center Added: Barangay Hall', '2024-11-12', 'notify'),
(31, 1, 'admin', 'New Evacuation Center Added: Barangay Hall at Barangay: Kasanyangan', '2024-11-12', 'notify'),
(32, 8, 'admin', 'Evacuation center has been inactive: Barangay Hall', '2024-11-12', 'notify');

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
(1, 'worker@gmail.com', 'Worker123', 'Ryota', 'D.', 'Miyagi', '', '$2y$10$qgHnhQJT1K2D.hEfuCCb0.PNDlgsSwiZJTFzOq7LAthmftb1bHIMe', '../../assets/uploads/profiles/ryota.jpg', 30, 'Male', 'Technician', 'Zamboanga City', 'Guiwan', '1234567890', '../../assets/uploads/appointments/69ce7c36886481c490338f7465e00bd9.png', 'active', '2024-11-12 17:47:37', NULL, 2),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
-- Constraints for table `worker`
--
ALTER TABLE `worker`
  ADD CONSTRAINT `fk_worker_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
