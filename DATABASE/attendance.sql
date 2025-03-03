-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 04:08 PM
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
-- Database: `attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `ID` int(11) NOT NULL,
  `ADMIN_USERNAME` varchar(50) NOT NULL,
  `ADMIN_PASSWORD` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`ID`, `ADMIN_USERNAME`, `ADMIN_PASSWORD`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_settings_tbl`
--

CREATE TABLE `attendance_settings_tbl` (
  `id` int(11) NOT NULL,
  `MORNING_TIME_IN` varchar(10) NOT NULL,
  `TIME_IN_END` varchar(10) NOT NULL,
  `MORNING_TIME_OUT` varchar(10) NOT NULL,
  `TIME_OUT_END` varchar(10) NOT NULL,
  `AFTERNOON_TIME_IN` varchar(10) NOT NULL,
  `AFTERNOON_TIME_IN_END` varchar(10) NOT NULL,
  `AFTERNOON_TIME_OUT` varchar(10) NOT NULL,
  `AFTERNOON_TIME_OUT_END` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_settings_tbl`
--

INSERT INTO `attendance_settings_tbl` (`id`, `MORNING_TIME_IN`, `TIME_IN_END`, `MORNING_TIME_OUT`, `TIME_OUT_END`, `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_IN_END`, `AFTERNOON_TIME_OUT`, `AFTERNOON_TIME_OUT_END`) VALUES
(1, '06:00 AM', '11:29 PM', '09:00 AM', '11:29 PM', '01:00 PM', '11:22 PM', '03:00 PM', '11:21 PM');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_tbl`
--

CREATE TABLE `attendance_tbl` (
  `ID` int(11) NOT NULL,
  `STUDENT_ID` varchar(50) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `GENDER` enum('Male','Female') NOT NULL,
  `MORNING_TIME_IN` varchar(10) NOT NULL,
  `MORNING_TIME_OUT` varchar(10) DEFAULT NULL,
  `MORNING_STATUS` varchar(10) NOT NULL,
  `AFTERNOON_TIME_IN` varchar(10) DEFAULT NULL,
  `AFTERNOON_TIME_OUT` varchar(10) DEFAULT NULL,
  `AFTERNOON_STATUS` varchar(10) NOT NULL,
  `DUTY_HOURS` varchar(6) DEFAULT NULL,
  `DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_tbl`
--

INSERT INTO `attendance_tbl` (`ID`, `STUDENT_ID`, `NAME`, `GENDER`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, `MORNING_STATUS`, `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`, `AFTERNOON_STATUS`, `DUTY_HOURS`, `DATE`) VALUES
(29, '123', 'Jestoni Villarta', 'Male', '09:32 PM', '09:32 PM', 'Present', '09:32 PM', '09:32 PM', 'Present', '0.00', '2025-02-22'),
(30, '123', 'Jestoni Villarta', 'Male', '10:20 PM', '10:20 PM', 'Present', '10:20 PM', '10:20 PM', 'Present', '0.00', '2025-02-25'),
(41, '123', 'Jestoni Villarta', 'Male', '', '', 'Absent', '', '', 'Absent', '0', '2025-02-26'),
(42, '123', 'Jestoni Villarta', 'Male', '10:57 PM', '10:57 PM', 'Present', '10:57 PM', '10:57 PM', 'Present', '0.00', '2025-02-27'),
(43, '123', 'Jestoni Villarta', 'Male', '', '', 'Absent', '', '', 'Absent', '0', '2025-02-23'),
(44, '123', 'Jestoni Villarta', 'Male', '', '', 'Absent', '', '', 'Absent', '0', '2025-02-24'),
(45, '123', 'Jestoni Villarta', 'Male', '11:04 PM', '11:05 PM', 'Present', '', '', 'Absent', '0.01', '2025-02-28'),
(46, '123', 'Jestoni Villarta', 'Male', '', '', 'Absent', '', '', 'Absent', '0', '2025-02-01'),
(47, '123', 'Jestoni Villarta', 'Male', '', '', 'Absent', '', '', 'Absent', '0', '2025-03-03'),
(48, '123', 'Jestoni Villarta', 'Male', '', '', 'Absent', '', '', 'Absent', '0', '2025-03-01'),
(49, '123', 'Jestoni Villarta', 'Male', '', '', 'Absent', '', '', 'Absent', '0', '2025-03-02');

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `ID` int(11) NOT NULL,
  `STUDENT_ID` varchar(20) NOT NULL,
  `FIRST_NAME` varchar(50) NOT NULL,
  `LAST_NAME` varchar(50) NOT NULL,
  `GENDER` varchar(50) NOT NULL,
  `EMAIL` varchar(50) NOT NULL,
  `CONTACT` varchar(50) NOT NULL,
  `ADDRESS` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`ID`, `STUDENT_ID`, `FIRST_NAME`, `LAST_NAME`, `GENDER`, `EMAIL`, `CONTACT`, `ADDRESS`) VALUES
(3, '123', 'Jestoni', 'Villarta', 'Male', 'villartajestoni27@gmail.com', '9306837284', 'riverside');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `attendance_settings_tbl`
--
ALTER TABLE `attendance_settings_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance_settings_tbl`
--
ALTER TABLE `attendance_settings_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
