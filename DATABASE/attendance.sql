-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2025 at 02:03 PM
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
(1, '06:00 AM', '07:00 AM', '11:00 AM', '11:30 AM', '01:00 PM', '01:30 PM', '05:00 PM', '05:30 PM');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_tbl`
--

CREATE TABLE `attendance_tbl` (
  `ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(50) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `GENDER` enum('Male','Female') NOT NULL,
  `MORNING_TIME_IN` time DEFAULT NULL,
  `MORNING_TIME_OUT` time DEFAULT NULL,
  `AFTERNOON_TIME_IN` time DEFAULT NULL,
  `AFTERNOON_TIME_OUT` time DEFAULT NULL,
  `DUTY_HOURS` varchar(6) DEFAULT NULL,
  `DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `ID` int(11) NOT NULL,
  `STUDENT_ID` int(20) NOT NULL,
  `FIRST_NAME` varchar(20) NOT NULL,
  `LAST_NAME` varchar(20) NOT NULL,
  `GENDER` varchar(10) NOT NULL,
  `EMAIL` varchar(20) NOT NULL,
  `CONTACT` varchar(11) NOT NULL,
  `ADDRESS` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`ID`, `STUDENT_ID`, `FIRST_NAME`, `LAST_NAME`, `GENDER`, `EMAIL`, `CONTACT`, `ADDRESS`) VALUES
(1, 12345, 'Jestoni', 'Villarta', 'Male', 'villartajestoni27@gm', '9306837284', 'Riverside'),
(2, 99999, 'paul', 'Villarta', 'Female', 'villartajestoni27@gm', '9306837284', 'Riverside');

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
