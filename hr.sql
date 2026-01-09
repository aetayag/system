-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 09, 2026 at 05:25 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hr`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instructor_id` int NOT NULL,
  `department_id` int NOT NULL,
  `year_section` varchar(50) DEFAULT NULL,
  `coursecode_title` varchar(255) NOT NULL,
  `modality_type` varchar(100) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `sy` varchar(50) DEFAULT NULL,
  `unit` int DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `day` varchar(50) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `instructor_id` (`instructor_id`),
  KEY `department_id` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `instructor_id`, `department_id`, `year_section`, `coursecode_title`, `modality_type`, `semester`, `sy`, `unit`, `room`, `day`, `time`) VALUES
(1, 1, 1, 'IS-2A', 'GE-294', 'F2F', '1st', '2026-2027', 3, 'Room', 'Monday', '8:00-11:00'),
(2, 1, 0, 'IS-2A', 'GE-107', 'F2F', '1st', '2026-2027', 3, 'Room106', 'Monday', '8:00 - 10:00'),
(3, 1, 0, 'IS-2A', 'GE-204', 'F2F', '1st', '2026-2027', 3, 'Room101', 'Monday', '8:00-11:00'),
(4, 1, 0, 'IS-2A', 'GE-294', 'F2F', '1st', '2026-2027', 3, 'Room', 'Monday', '8:00-11:00'),
(5, 8, 0, 'IS-2A', 'GE-294', 'F2F', '1st', '2026-2027', 3, 'Room', 'Monday', '8:00-11:00'),
(6, 2, 0, 'IS-2A', 'GE-294', 'F2F', '1st', '2026-2027', 0, 'Room', 'Monday', '8:00-11:00'),
(7, 1, 0, 'IS-2A', 'GE-294', 'F2F', '1st', '2026-2027', 3, '8:00-11:00', 'Monday', '8:00-11:00');

-- --------------------------------------------------------

--
-- Table structure for table `comm_dept`
--

DROP TABLE IF EXISTS `comm_dept`;
CREATE TABLE IF NOT EXISTS `comm_dept` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `requested_by` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `hr_status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `connection_inbox`
--

DROP TABLE IF EXISTS `connection_inbox`;
CREATE TABLE IF NOT EXISTS `connection_inbox` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `dept_id` int DEFAULT NULL,
  `hr_id` int DEFAULT NULL,
  `nont_id` int DEFAULT NULL,
  `pt_id` int DEFAULT NULL,
  `ft_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dept_id` (`dept_id`),
  KEY `hr_id` (`hr_id`),
  KEY `nont_id` (`nont_id`),
  KEY `pt_id` (`pt_id`),
  KEY `ft_id` (`ft_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cred`
--

DROP TABLE IF EXISTS `cred`;
CREATE TABLE IF NOT EXISTS `cred` (
  `id` int NOT NULL AUTO_INCREMENT,
  `doctype` varchar(100) DEFAULT NULL,
  `typeofdoc` varchar(100) DEFAULT NULL,
  `date_issued` date DEFAULT NULL,
  `issued_by` varchar(100) DEFAULT NULL,
  `employee_id` int DEFAULT NULL,
  `file` longblob,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `dept_name`, `password`) VALUES
(1, 'CCSE', 'CCSE'),
(2, 'CBAM', 'CBAM');

-- --------------------------------------------------------

--
-- Table structure for table `draft`
--

DROP TABLE IF EXISTS `draft`;
CREATE TABLE IF NOT EXISTS `draft` (
  `id` int NOT NULL,
  `viewer_from` int DEFAULT NULL,
  `viewer_date` date DEFAULT NULL,
  `viewer_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_201`
--

DROP TABLE IF EXISTS `file_201`;
CREATE TABLE IF NOT EXISTS `file_201` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `pob` varchar(255) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `age` int UNSIGNED NOT NULL,
  `email` varchar(50) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `date_hired` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `emergency_contact` varchar(50) NOT NULL,
  `emergency_contact_no` varchar(30) NOT NULL,
  `position` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `employee_type` varchar(50) NOT NULL,
  `adv_class` varchar(100) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `per_office` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo` varchar(50) DEFAULT NULL,
  `employee_select` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_file201_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `file_201`
--

INSERT INTO `file_201` (`id`, `employee_id`, `name`, `dob`, `pob`, `sex`, `age`, `email`, `civil_status`, `address`, `date_hired`, `emergency_contact`, `emergency_contact_no`, `position`, `department`, `employee_type`, `adv_class`, `program`, `per_office`, `password`, `profile_photo`, `employee_select`) VALUES
(27, 'EMP1767905476697', 'nonteaching', '2026-01-09', '', 'Male', 1, 'non@gmail.com', 'single', 'san jose', '2026-01-09 04:51:16', 'mama', '0994 464 4948', 'staff', '', '', NULL, NULL, '', '$2y$10$VIBuS2xOyuVdgehdCgvRRumO7aInTI7Cb18XYj8t.20CslBapdbf6', 'default.png', 'Non-Teaching'),
(28, 'EMP1767905814894', 'fulltime', '2026-01-09', '', 'Male', 1, 'fulltime@gmail.com', 'single', 'san jose', '2026-01-09 04:56:54', 'mama', '0994 464 4948', 'prof', 'CCSE', 'Full-Time', NULL, NULL, 'non', '$2y$10$hxxWU/vGg69auE9AoIwFI.TQT7ytgCovrK8Yc0wtr7qV0H.LYIo/O', 'default.png', 'Teaching'),
(29, 'EMP1767905955101', 'parttime', '2026-01-09', '', 'Male', 2, 'parttime@gmail.com', 'single', 'san jose', '2026-01-09 04:59:15', 'mama', '0994 464 4948', 'prof', 'CCSE', 'Part-Time', NULL, NULL, 'non', '$2y$10$t.Ns060nUvBuZ6aANwtYXOFwbI3hv9CS8ILTByuEB7NqP/vt5CzGG', 'default.png', 'Teaching'),
(30, '22-0-8564', 'aki', '2026-01-09', '', 'Male', 1, 'aki@gmail.com', 'single', 'san jose', '2026-01-09 05:29:48', 'mama', '0994 464 4948', 'prof', 'CCSE', 'Full-Time', 'Teaching', '', 'non', '$2y$10$6enyQhVAfqkOzy7.xaoLz.PPMDIsPS4W/EuJvW/cW.DP80UE5AiG6', 'default.png', 'Teaching'),
(32, '23-11223', 'yuki', '2026-01-09', '', 'Female', 26, 'yuki@gmail.com', 'single', '', '2026-01-09 05:43:51', 'mama', '09661235', 'prof', 'CCSE', 'Full-Time', 'Teaching', '', 'non', '$2y$10$IqBhKja/TGmhfX6lt2vUYeFq02Vc2QXwuh5iC1p7pusW6Gk.0F3T.', 'profile_1767908631.png', 'Teaching'),
(33, '22-08564', 'ae', '2026-01-13', 'san pablo', 'Female', 26, 'ae@gmail.com', 'single', 'san pablo', '2026-01-09 09:11:37', 'mama', '09661235', 'staff', 'CCSE', 'Full-Time', 'Teaching', 'IS-3A', 'non', '$2y$10$HERv91pllhvtAwjUcxAX8OYgxNgooLwjR1iavobNMe9i6djLmQXKe', 'default.png', 'Teaching'),
(34, '22-08564', 'aira', '2026-01-13', 'san pablo', 'Female', 26, 'aira@gmail.com', 'single', 'san pablo', '2026-01-09 09:14:23', 'mama', '0965432', 'prof', 'CCSE', 'Part-Time', 'Teaching', 'IS-3B', 'non', '$2y$10$9CEigpn5pw/wZQeHIYjwqeP.tlwRdLkt2Ocsu5zHZrbhyPaJAzJ6i', 'default.png', 'Teaching'),
(35, '22-08564', 'gine', '2026-01-13', 'san pablo', 'Female', 26, 'gine@gmail.com', 'single', 'san pablo', '2026-01-09 09:16:57', 'mama', '097654334', 'secretary', 'CCSE', 'Full-Time', 'Non-Teaching', 'IS-3c', '', '$2y$10$TGQVUTkgX6HPaXd/iJoBVuKB72fdbFYdo5fPae3QOJNgYQ8cblM8G', 'default.png', 'Non-Teaching'),
(36, '22-08564', 'tayag', '2026-01-20', 'san pablo', 'Female', 26, 'tayag@gmail.com', 'single', 'san pablo', '2026-01-09 11:16:50', 'mama', '876769', 'secretary', 'CCSE', 'Full-Time', '', 'IS-3D', '', '$2y$10$u4jfV7A0zw5EMrlhkUg9ve0wU8hkA/fieiizn1PCfQbkJOuSAIbZK', 'default.png', ''),
(37, '', 'jake', '2026-01-02', 'san pablo', 'Female', 26, 'jake@gmail.com', 'single', 'san pablo', '2026-01-09 12:16:27', 'mama', '09765467', 'staff', 'CCSE', 'Full-Time', NULL, NULL, 'non', '$2y$10$koJQnY46JTp9Wq3F2H/CheIMXFt7a9hh0.vm0OFNigrgUQeeKq7ba', 'default.png', 'Teaching'),
(38, '', 'kim', '2025-11-24', 'san pablo', 'Female', 26, 'kim@gmail.com', 'single', 'san pablo', '2026-01-09 12:24:43', 'mama', '09661235', 'staff', 'CCSE', 'Full-Time', NULL, NULL, 'non', '$2y$10$Z0/LSYi4FEJ7gKzofyyjoexH7iuT0wc9c2f9s8Ag.ULJhy6nRqA9m', 'profile_6960830be1153.png', 'Teaching'),
(39, '', 'luke', '2025-11-24', 'san pablo', 'Female', 26, 'luke@gmail.com', 'single', 'san pablo', '2026-01-09 13:15:59', 'mama', '09661235', 'staff', 'CCSE', 'Full-Time', NULL, NULL, 'Sinag', '$2y$10$fQSedKY0m/d4zecCoft7hO2nWv7YR22CpGPASafnmzV2b5NtEpebC', 'default.png', 'Non-Teaching');

-- --------------------------------------------------------

--
-- Table structure for table `finance_admin`
--

DROP TABLE IF EXISTS `finance_admin`;
CREATE TABLE IF NOT EXISTS `finance_admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `office_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finance_admin`
--

INSERT INTO `finance_admin` (`id`, `office_name`, `password`) VALUES
(1, 'Finance', 'fina');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `id` int NOT NULL,
  `date_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `source` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_role` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_admin`
--

DROP TABLE IF EXISTS `hr_admin`;
CREATE TABLE IF NOT EXISTS `hr_admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `office_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_admin`
--

INSERT INTO `hr_admin` (`id`, `office_name`, `password`) VALUES
(1, 'HRadmin', 'HR12');

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

DROP TABLE IF EXISTS `inbox`;
CREATE TABLE IF NOT EXISTS `inbox` (
  `id` int NOT NULL,
  `viewer_from` int DEFAULT NULL,
  `viewer_date` date DEFAULT NULL,
  `viewer_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_dept`
--

DROP TABLE IF EXISTS `inbox_dept`;
CREATE TABLE IF NOT EXISTS `inbox_dept` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `connector_id` (`connector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_ft`
--

DROP TABLE IF EXISTS `inbox_ft`;
CREATE TABLE IF NOT EXISTS `inbox_ft` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `connector_id` (`connector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_hr`
--

DROP TABLE IF EXISTS `inbox_hr`;
CREATE TABLE IF NOT EXISTS `inbox_hr` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `connector_id` (`connector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_nont`
--

DROP TABLE IF EXISTS `inbox_nont`;
CREATE TABLE IF NOT EXISTS `inbox_nont` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `connector_id` (`connector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_pt`
--

DROP TABLE IF EXISTS `inbox_pt`;
CREATE TABLE IF NOT EXISTS `inbox_pt` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `connector_id` (`connector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posting`
--

DROP TABLE IF EXISTS `posting`;
CREATE TABLE IF NOT EXISTS `posting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `audience` enum('all','department') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `department` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posting`
--

INSERT INTO `posting` (`id`, `title`, `message`, `audience`, `department`, `created_at`, `image`) VALUES
(26, 'wertdgh', 'ewfds', 'all', 'CCSE', '2026-01-05 21:52:14', 'announce_695c328edbd53.png'),
(27, 'wertdgh', 'ewfds', 'all', 'CCSE', '2026-01-05 21:52:15', 'announce_695c328f1e12f.png'),
(28, '12', '12weqdsa', 'department', 'CAS', '2026-01-05 21:53:50', 'announce_695c32ee9361f.png'),
(29, 'wqsa', 'qwsdax', 'all', 'CCSE', '2026-01-05 22:03:36', 'announce_695c35381f764.png'),
(30, 'dsa', 'qsa', 'department', 'CCSE', '2026-01-05 23:42:11', 'announce_695c4c5327b1b.png'),
(31, 'mlkjh', 'jh ', 'all', NULL, '2026-01-06 03:57:19', NULL),
(32, '7RU7', 'GXS', NULL, NULL, '2026-01-07 08:26:11', NULL),
(33, 'new memo', 'ovu9p2g6b82', 'all', NULL, '2026-01-07 08:35:56', NULL),
(34, 'nn ', 'xcvbn', 'all', NULL, '2026-01-07 11:59:48', NULL),
(35, 'hakdog', 'jghybuvrt', 'all', NULL, '2026-01-09 05:06:46', 'announce_69608ce6dffce.png');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
