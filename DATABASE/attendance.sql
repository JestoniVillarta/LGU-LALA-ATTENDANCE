-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 03:19 AM
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
  `MORNING_TIME_OUT` varchar(10) NOT NULL,
  `AFTERNOON_TIME_IN` varchar(50) NOT NULL,
  `AFTERNOON_TIME_OUT` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_settings_tbl`
--

INSERT INTO `attendance_settings_tbl` (`id`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`) VALUES
(2, '09:17 AM', '10:22 AM', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_tbl`
--

CREATE TABLE `attendance_tbl` (
  `ATTENDANCE_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(20) DEFAULT NULL,
  `NAME` varchar(100) DEFAULT NULL,
  `GENDER` enum('Male','Female','Other') DEFAULT NULL,
  `DATE` date NOT NULL,
  `MORNING_TIME_IN` time NOT NULL,
  `MORNING_TIME_OUT` time DEFAULT NULL,
  `AFTERNOON_TIME_IN` time DEFAULT NULL,
  `AFTERNOON_TIME_OUT` time DEFAULT NULL,
  `STATUS` enum('Early','On Time','Late') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_tbl`
--

INSERT INTO `attendance_tbl` (`ATTENDANCE_ID`, `EMPLOYEE_ID`, `NAME`, `GENDER`, `DATE`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`, `STATUS`) VALUES
(17, '123', 'TONIX', 'Male', '0000-00-00', '10:01:38', NULL, NULL, NULL, 'Late'),
(18, '123', 'TONIX', 'Male', '0000-00-00', '10:14:25', NULL, NULL, NULL, 'Late'),
(19, '123', 'TONIX', 'Male', '0000-00-00', '10:18:03', NULL, NULL, NULL, 'On Time');

-- --------------------------------------------------------

--
-- Table structure for table `employee_tbl`
--

CREATE TABLE `employee_tbl` (
  `ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `GENDER` varchar(25) NOT NULL,
  `EMAIL` varchar(25) NOT NULL,
  `CONTACT` int(11) NOT NULL,
  `ADDRESS` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_tbl`
--

INSERT INTO `employee_tbl` (`ID`, `EMPLOYEE_ID`, `NAME`, `GENDER`, `EMAIL`, `CONTACT`, `ADDRESS`) VALUES
(4, 123, 'TONIX', 'MALE', 'TON', 987, 'here'),
(5, 234, 'jay ann idol', 'helicopter', 'kas', 9, 'asdfas'),
(6, 444, 'TONIX', 'male', 'asdfsdf', 8967, 'sdfsdfa'),
(7, 888, 'PAUL', 'MALE', 'ADF', 90809, 'ASDFAS'),
(8, 666, 'julius', 'bakbak', 'sds', 809890, 'adsdfs'),
(9, 465564, 'asdfa', 'sdfs', 'sdfas', 0, 'asfsa'),
(10, 786, 'sdfas', 'sdfs', 'asdfa', 0, 'sdasds'),
(11, 0, 'sds', 'dsds', 'sds', 0, 'sds'),
(12, 999, 'asa', 'sada', 'dadad', 0, 'asdas'),
(13, 999, 'asa', 'sada', 'dadad', 0, 'asdas'),
(14, 999, 'asa', 'sada', 'dadad', 0, 'asdas'),
(15, 0, 'dad', 'sdf', 'sdsad', 0, 'sdfsad'),
(16, 0, 'asdfsad', 'sdfsd', 'sdfasd', 0, 'sdfas'),
(17, 0, 'asfa', 'sdfas', 'dfsdf', 0, 'asdfsd'),
(18, 0, 'sdfss', 'sdfs', 'dsdfas', 0, 'sdfsdf'),
(19, 123, 'das', 'sfds', 'fsd', 0, 'dfs'),
(20, 0, 'ASD', 'asda', 'ASD', 0, 'ASD'),
(21, 23423, 'asdsa', 'asda', 'sdfa', 0, 'sadfas'),
(22, 0, 'ID', 'Male', 'sdfasd@gmai.com', 967686766, 'sdfas');

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
  ADD PRIMARY KEY (`ATTENDANCE_ID`);

--
-- Indexes for table `employee_tbl`
--
ALTER TABLE `employee_tbl`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  MODIFY `ATTENDANCE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `employee_tbl`
--
ALTER TABLE `employee_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
