-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2025 at 01:22 AM
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
-- Table structure for table `attendance_tbl`
--

CREATE TABLE `attendance_tbl` (
  `ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `GENDER` varchar(10) NOT NULL,
  `DEPARTMENT` varchar(50) NOT NULL,
  `TIME_IN` timestamp NOT NULL DEFAULT current_timestamp(),
  `TIME_OUT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_tbl`
--

INSERT INTO `attendance_tbl` (`ID`, `EMPLOYEE_ID`, `NAME`, `GENDER`, `DEPARTMENT`, `TIME_IN`, `TIME_OUT`) VALUES
(1, 123, 'TONIX', 'MALE', '', '2025-02-04 03:12:31', '2025-02-04 03:12:31'),
(2, 234, 'jay ann idol', 'helic', '', '2025-02-04 03:14:28', '2025-02-04 03:14:28'),
(3, 234, 'jay ann idol', 'helic', '', '2025-02-04 03:16:21', '2025-02-04 03:16:21'),
(4, 234, 'jay ann idol', 'helicopter', '', '2025-02-04 03:17:41', '2025-02-04 03:17:41'),
(5, 123, 'TONIX', 'MALE', '', '2025-02-04 07:38:07', '2025-02-04 07:38:07');

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
(5, 234, 'jay ann idol', 'helicopter', 'kas', 9, 'asdfas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  ADD PRIMARY KEY (`ID`);

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
-- AUTO_INCREMENT for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_tbl`
--
ALTER TABLE `employee_tbl`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
