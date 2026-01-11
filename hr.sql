-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Jan 11, 2026 at 02:28 PM
-- Server version: 10.6.22-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40865498_hr`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `year_section` varchar(50) DEFAULT NULL,
  `coursecode_title` varchar(255) NOT NULL,
  `modality_type` varchar(100) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `sy` varchar(50) DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `day` varchar(50) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `requested_by` int(11) DEFAULT NULL,
  `request_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `connection_inbox`
--

CREATE TABLE `connection_inbox` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `hr_id` int(11) DEFAULT NULL,
  `nont_id` int(11) DEFAULT NULL,
  `pt_id` int(11) DEFAULT NULL,
  `ft_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `request_type` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `connection_inbox`
--

INSERT INTO `connection_inbox` (`id`, `date`, `time`, `dept_id`, `hr_id`, `nont_id`, `pt_id`, `ft_id`, `user_id`, `request_type`) VALUES
(1, '2026-01-10', '01:46:18', NULL, NULL, NULL, NULL, NULL, 2, 'Edit Request');

-- --------------------------------------------------------

--
-- Table structure for table `cred`
--

CREATE TABLE `cred` (
  `id` int(11) NOT NULL,
  `doctype` varchar(100) DEFAULT NULL,
  `typeofdoc` varchar(100) DEFAULT NULL,
  `date_issued` date DEFAULT NULL,
  `issued_by` varchar(100) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `file` longblob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `dept_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `dept_name`, `password`) VALUES
(3, 'CCSE', 'CCSE'),
(4, 'CBAM', 'CBAM');

-- --------------------------------------------------------

--
-- Table structure for table `draft`
--

CREATE TABLE `draft` (
  `id` int(11) NOT NULL,
  `viewer_from` int(11) DEFAULT NULL,
  `viewer_date` date DEFAULT NULL,
  `viewer_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `department` varchar(100) NOT NULL,
  `faculty_name` varchar(150) NOT NULL,
  `supervisor_rate` decimal(4,2) DEFAULT NULL,
  `peer_rate` decimal(4,2) DEFAULT NULL,
  `self_rate` decimal(4,2) DEFAULT NULL,
  `student_rate` decimal(4,2) DEFAULT NULL,
  `final_avg` decimal(4,2) DEFAULT NULL,
  `final_adjective` varchar(50) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_201`
--

CREATE TABLE `file_201` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `pob` varchar(255) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `age` int(10) UNSIGNED NOT NULL,
  `email` varchar(50) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `date_hired` datetime NOT NULL DEFAULT current_timestamp(),
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
  `employee_select` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_201`
--

INSERT INTO `file_201` (`id`, `employee_id`, `name`, `dob`, `pob`, `sex`, `age`, `email`, `civil_status`, `address`, `date_hired`, `emergency_contact`, `emergency_contact_no`, `position`, `department`, `employee_type`, `adv_class`, `program`, `per_office`, `password`, `profile_photo`, `employee_select`) VALUES
(1, '', 'abba tayag esteron', '2020-02-10', 'san pablo', 'Female', 26, 'aetayag@gmail.com', 'single', 'san pablo', '2026-01-10 00:45:24', 'abba tayag esteron', '09859876024', 'nurse', 'CCSE', 'Full-Time', NULL, NULL, 'Clinic', '$2y$10$YFRVqd06FJnNGtVduAk4TelvJvPyihG/Xlhuky4iGM6j5x87IJCWe', 'default.png', 'Non-Teaching'),
(2, '', 'ariel esteron', '2020-02-10', 'san pablo', 'Female', 26, 'arielg@gmail.com', 'single', 'san pablo', '2026-01-10 00:50:59', 'abba tayag esteron', '09859876024', 'nurse', 'CCSE', 'Full-Time', NULL, NULL, 'Clinic', '$2y$10$Xvl7W7hQaOJWOvq8lhQjQ.C9n..TzHBhzZr8dMNTAZSZw1RoTf/b6', 'default.png', 'Non-Teaching'),
(3, '', 'jake', '2003-07-01', 'san pablo', 'Female', 26, 'jake@gmail.com', 'single', 'san pablo', '2026-01-11 01:46:55', 'abba tayag esteron', '09859876024', 'nurse', 'CCSE', 'Part-Time', NULL, NULL, 'Clinic', '$2y$10$3qwL2cMVMoRGGIbbu8egROSK1KB/e.OOxUIZYSuF5OZOpnYm3BTEK', 'default.png', 'Teaching');

-- --------------------------------------------------------

--
-- Table structure for table `finance_admin`
--

CREATE TABLE `finance_admin` (
  `id` int(11) NOT NULL,
  `office_name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finance_admin`
--

INSERT INTO `finance_admin` (`id`, `office_name`, `password`) VALUES
(1, 'Finance', 'fina');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `date_time` datetime DEFAULT current_timestamp(),
  `source` varchar(150) DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `user_role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_admin`
--

CREATE TABLE `hr_admin` (
  `id` int(11) NOT NULL,
  `office_name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_admin`
--

INSERT INTO `hr_admin` (`id`, `office_name`, `password`) VALUES
(1, 'HRadmin', 'HR12');

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `id` int(11) NOT NULL,
  `viewer_from` int(11) DEFAULT NULL,
  `viewer_date` date DEFAULT NULL,
  `viewer_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_dept`
--

CREATE TABLE `inbox_dept` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_ft`
--

CREATE TABLE `inbox_ft` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_hr`
--

CREATE TABLE `inbox_hr` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_nont`
--

CREATE TABLE `inbox_nont` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_pt`
--

CREATE TABLE `inbox_pt` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `connector_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posting`
--

CREATE TABLE `posting` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `audience` enum('all','department') DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(35, 'hakdog', 'jghybuvrt', 'all', NULL, '2026-01-09 05:06:46', 'announce_69608ce6dffce.png'),
(36, 'kk', 'wsedrfgbh', 'all', NULL, '2026-01-09 05:50:00', 'announce_6960970849892.png'),
(37, 'walang pasok', 'bhasghsakHDHAS', 'all', NULL, '2026-01-09 06:06:01', 'announce_69609ac91e2eb.png'),
(38, 'hakdig', 'rt yk okok', 'all', NULL, '2026-01-09 13:45:08', 'announce_696106648eeb5.png');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_requests`
--

CREATE TABLE `schedule_requests` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `schedule_json` longtext DEFAULT NULL,
  `status` enum('PENDING','APPROVED','DECLINED') DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `schedule_requests`
--

INSERT INTO `schedule_requests` (`id`, `instructor_id`, `department`, `schedule_json`, `status`, `created_at`, `reviewed_at`) VALUES
(1, 3, 'CCSE', '[{\"course\":\"GE107-Lite\",\"time\":\"8:00-9:00\",\"day\":\"Monday\",\"room\":\"Rm-203\",\"modality\":\"F2F\",\"year_section\":\"IS-\\n3A\",\"semester\":\"1st\",\"sy\":\"2026-2027\",\"unit\":\"3\"},{\"course\":\"\",\"time\":\"\",\"day\":\"Monday\",\"room\":\"\",\"modality\":\"F2F\",\"year_section\":\"\",\"semester\":\"1st\",\"sy\":\"\",\"unit\":\"\"},{\"course\":\"\",\"time\":\"\",\"day\":\"Monday\",\"room\":\"\",\"modality\":\"F2F\",\"year_section\":\"\",\"semester\":\"1st\",\"sy\":\"\",\"unit\":\"\"},{\"course\":\"\",\"time\":\"\",\"day\":\"Monday\",\"room\":\"\",\"modality\":\"F2F\",\"year_section\":\"\",\"semester\":\"1st\",\"sy\":\"\",\"unit\":\"\"},{\"course\":\"\",\"time\":\"\",\"day\":\"Monday\",\"room\":\"\",\"modality\":\"F2F\",\"year_section\":\"\",\"semester\":\"1st\",\"sy\":\"\",\"unit\":\"\"}]', 'PENDING', '2026-01-11 17:44:24', NULL),
(2, 3, 'CCSE', '[{\"course\":\"GE107-Lite\",\"time\":\"8:00-9:00\",\"day\":\"Monday\",\"room\":\"Rm\",\"modality\":\"F2F\",\"year_section\":\"IS-3A\",\"semester\":\"1st\",\"sy\":\"2026-2027\",\"unit\":\"3\"}]', 'PENDING', '2026-01-11 17:47:52', NULL),
(3, 3, 'CCSE', '[{\"course\":\"GE107-Lite\",\"time\":\"8:00-9:00\",\"day\":\"Monday\",\"room\":\"Rm-203\",\"modality\":\"F2F\",\"year_section\":\"IS-3A\",\"semester\":\"1st\",\"sy\":\"2026-2027\",\"unit\":\"3\"}]', 'PENDING', '2026-01-11 17:55:09', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `connection_inbox`
--
ALTER TABLE `connection_inbox`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `hr_id` (`hr_id`),
  ADD KEY `nont_id` (`nont_id`),
  ADD KEY `pt_id` (`pt_id`),
  ADD KEY `ft_id` (`ft_id`),
  ADD KEY `fk_connection_user` (`user_id`);

--
-- Indexes for table `cred`
--
ALTER TABLE `cred`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_201`
--
ALTER TABLE `file_201`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_file201_email` (`email`);

--
-- Indexes for table `finance_admin`
--
ALTER TABLE `finance_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_admin`
--
ALTER TABLE `hr_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inbox_dept`
--
ALTER TABLE `inbox_dept`
  ADD PRIMARY KEY (`id`),
  ADD KEY `connector_id` (`connector_id`);

--
-- Indexes for table `inbox_ft`
--
ALTER TABLE `inbox_ft`
  ADD PRIMARY KEY (`id`),
  ADD KEY `connector_id` (`connector_id`);

--
-- Indexes for table `inbox_hr`
--
ALTER TABLE `inbox_hr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `connector_id` (`connector_id`);

--
-- Indexes for table `inbox_nont`
--
ALTER TABLE `inbox_nont`
  ADD PRIMARY KEY (`id`),
  ADD KEY `connector_id` (`connector_id`);

--
-- Indexes for table `inbox_pt`
--
ALTER TABLE `inbox_pt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `connector_id` (`connector_id`);

--
-- Indexes for table `posting`
--
ALTER TABLE `posting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_requests`
--
ALTER TABLE `schedule_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `connection_inbox`
--
ALTER TABLE `connection_inbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cred`
--
ALTER TABLE `cred`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_201`
--
ALTER TABLE `file_201`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `finance_admin`
--
ALTER TABLE `finance_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_admin`
--
ALTER TABLE `hr_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inbox_dept`
--
ALTER TABLE `inbox_dept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbox_ft`
--
ALTER TABLE `inbox_ft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbox_hr`
--
ALTER TABLE `inbox_hr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbox_nont`
--
ALTER TABLE `inbox_nont`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbox_pt`
--
ALTER TABLE `inbox_pt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posting`
--
ALTER TABLE `posting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `schedule_requests`
--
ALTER TABLE `schedule_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
