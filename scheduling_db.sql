-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2023 at 04:03 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scheduling_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `class_schedule_info`
--

CREATE TABLE `class_schedule_info` (
  `id` int(30) NOT NULL,
  `schedule_id` int(30) NOT NULL,
  `course_id` int(30) NOT NULL,
  `subject` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(30) NOT NULL,
  `course` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `year` varchar(25) NOT NULL,
  `section` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course`, `description`, `year`, `section`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology', '', ''),
(9, 'BEED', 'Bachelor of Elementary Education', '', ''),
(10, 'BSBA', 'Bachelor of Science in Business Administration', '', ''),
(11, 'BSED', 'Bachelor of Secondary Education', '', ''),
(12, 'BSHM', 'Bachelor of Science in Hospitality Management', '', ''),
(17, 'BSIS', 'Bachelor of science in information system', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE `days` (
  `id` int(25) NOT NULL,
  `days` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `days`
--

INSERT INTO `days` (`id`, `days`) VALUES
(1, 'MW'),
(2, 'TTH'),
(3, 'F'),
(4, 'Sat');

-- --------------------------------------------------------

--
-- Table structure for table `daysofweek`
--

CREATE TABLE `daysofweek` (
  `id` int(11) NOT NULL,
  `DATE_b` date NOT NULL,
  `TIME` time(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `daysofweek`
--

INSERT INTO `daysofweek` (`id`, `DATE_b`, `TIME`) VALUES
(1, '2022-11-21', '09:00:00.000000'),
(2, '2022-11-21', '10:00:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(30) NOT NULL,
  `id_no` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `id_no`, `firstname`, `middlename`, `lastname`, `contact`, `gender`, `address`, `email`) VALUES
(1, '06232014', 'Dino', 'I', 'Ilustrisimo', '+18456-5455-55', 'Male', 'Sample Address', 'jsmith@sample.com'),
(2, '37362629', 'Jered', 'C', 'Cueva', '+12345687923', 'Male', 'Sample Address', 'cblake@sample.com'),
(3, '64547179', 'Kurt', 'A', 'Alegre', '09183463622', 'Male', '', 'alegre@gmail.com'),
(4, '57763816', 'Jessica', 'D', 'Alcazar', '09183463622', 'Female', '', 'jj@gmail.com'),
(5, '97243938', 'Jamaica Fe', 'C', 'Carabio', '', 'Female', '', ''),
(6, '88508821', 'Junie', 'Q', 'Mansueto', '', 'Male', '', ''),
(7, '07676628', 'Emily', 'A', 'Ilustrisimo', '', 'Female', '', ''),
(8, '34983010', 'Vangeant', 'C', 'Santillan ', '', 'Male', '', ''),
(9, '09726529', 'Alvin', 'A', 'Billones', '', 'Male', '', ''),
(10, '16838441', 'Richard', 'E', 'Bracero', '', 'Male', '', ''),
(11, '86043052', 'Ma Luisa', 'C', 'Labores', '', 'Female', '', ''),
(12, '14593384', 'Wilfred', 'H', 'Maru', '', 'Male', '', ''),
(13, '27642456', 'J', 'D', 'Dela Cruz', '', 'Male', '', ''),
(14, '41683148', 'A', 'D', 'Desamparado', '', 'Male', '', ''),
(15, '56339954', 'A', 'C', 'Montajes', '', 'Male', '', ''),
(16, '65471560', 'J', 'C', 'Boltron', '', 'Male', '', ''),
(17, '11205564', 'Elmer', 'S', 'Cañete', '', 'Male', '', ''),
(24, '80586101', 'V', 'V', 'VACANT', 'VACANT', 'Male', 'VACANT', 'VACANT'),
(25, '59212362', 'Christy', '', 'Forrosuelo', '', 'Male', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `library` varchar(255) DEFAULT '0',
  `computer` varchar(255) DEFAULT '0',
  `school_id` varchar(255) DEFAULT '0',
  `athletic` varchar(255) DEFAULT '0',
  `admission` varchar(255) DEFAULT '0',
  `development` varchar(255) DEFAULT '0',
  `guidance` varchar(255) DEFAULT '0',
  `handbook` varchar(255) DEFAULT '0',
  `entrance` varchar(255) DEFAULT '0',
  `registration` varchar(255) DEFAULT '0',
  `medical` varchar(255) DEFAULT '0',
  `cultural` varchar(255) DEFAULT '0',
  `year` varchar(255) DEFAULT '0',
  `semester` varchar(255) DEFAULT '0',
  `course` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `library`, `computer`, `school_id`, `athletic`, `admission`, `development`, `guidance`, `handbook`, `entrance`, `registration`, `medical`, `cultural`, `year`, `semester`, `course`) VALUES
(13, '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '1st', '1st', 'BSIT'),
(14, '100', '101', '102', '103', '104', '', '', '', '', '', '', '', '1st Year', '1st', 'BSIT'),
(15, '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '4th', '1st', 'BSIT');

-- --------------------------------------------------------

--
-- Table structure for table `loading`
--

CREATE TABLE `loading` (
  `id` int(50) NOT NULL,
  `timeslot_id` int(25) NOT NULL,
  `timeslot` varchar(100) NOT NULL,
  `rooms` varchar(100) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `faculty` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `subjects` varchar(100) NOT NULL,
  `days` varchar(50) NOT NULL,
  `sub_description` varchar(100) NOT NULL,
  `total_units` int(25) NOT NULL,
  `lec_units` int(25) NOT NULL,
  `lab_units` int(25) NOT NULL,
  `hours` decimal(25,2) NOT NULL,
  `coursedesc` varchar(50) NOT NULL,
  `timeslot_sid` int(25) NOT NULL,
  `semester` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loading`
--

INSERT INTO `loading` (`id`, `timeslot_id`, `timeslot`, `rooms`, `room_name`, `faculty`, `course`, `subjects`, `days`, `sub_description`, `total_units`, `lec_units`, `lab_units`, `hours`, `coursedesc`, `timeslot_sid`, `semester`) VALUES
(130, 14, '12:00 nn-5:00 pm', '1', 'IT-LR1', '16', '2ndNorth', 'ITE220', 'Sat', 'ITE Prof Elect1- Object- Oriented Programming', 3, 2, 1, '5.00', 'BSIT', 4, '2nd'),
(131, 13, '7:00 am-12:00 nn', '1', 'IT-LR1', '10', '2ndEast', 'ITE220', 'Sat', 'ITE Prof Elect1- Object- Oriented Programming', 3, 2, 1, '5.00', 'BSIT', 1, '2nd'),
(138, 2, '8:30 am-11:00 am', '1', 'IT-LR1', '3', '1stEast', 'ITE 121', 'MW', 'Computer Programming 2', 3, 2, 1, '2.50', 'BSIT', 2, '2nd'),
(139, 2, '8:30 am-11:00 am', '2', 'IT-LR2', '4', '1stWest', 'ITE 122', 'MW', 'Human Computer Interaction', 3, 2, 1, '2.50', 'BSIT', 2, '2nd'),
(140, 2, '8:30 am-11:00 am', '3', 'IT-LR3', '2', '1stNorth', 'ITM120', 'MW', 'Web Development', 3, 2, 1, '2.50', 'BSIT', 2, '2nd'),
(142, 3, '11:00 am-1:30 pm', '1', 'IT-LR1', '3', '1stWest', 'ITE 121', 'MW', 'Computer Programming 2', 3, 2, 1, '2.50', 'BSIT', 5, '2nd'),
(143, 3, '11:00 am-1:30 pm', '2', 'IT-LR2', '4', '1stNorth', 'ITE 122', 'MW', 'Human Computer Interaction', 3, 2, 1, '2.50', 'BSIT', 5, '2nd'),
(144, 3, '11:00 am-1:30 pm', '3', 'IT-LR3', '2', '1stNortheast', 'ITM120', 'MW', 'Web Development', 3, 2, 1, '2.50', 'BSIT', 5, '2nd'),
(156, 13, '7:00 am-12:00 nn', '6', 'IT-LR6', '16', '2ndNortheast', 'ITE 211', 'Sat', 'Data Structures and Algorithms', 3, 2, 1, '5.00', 'BSIT', 1, '1st'),
(158, 13, '7:00 am-12:00 nn', '7', 'IT-LR7', '9', '2ndSouth', 'ITE 213', 'Sat', 'Quantitative Method (incl Modelling & Simulation)', 3, 3, 0, '5.00', 'BSIT', 1, '1st'),
(159, 2, '8:30 am-11:00 am', '8', 'IT-LR8', '1', '4thWest', 'ITE 400', 'MW', 'Practicum', 6, 6, 0, '2.50', 'BSIT', 2, '1st');

-- --------------------------------------------------------

--
-- Table structure for table `roomlist`
--

CREATE TABLE `roomlist` (
  `id` int(11) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `room_id` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roomlist`
--

INSERT INTO `roomlist` (`id`, `room_name`, `room_id`) VALUES
(1, 'IT-LR1', 1),
(2, 'IT-LR2', 2),
(3, 'IT-LR3', 3),
(4, 'IT-LR4', 4),
(5, 'IT-LR5', 5),
(25, 'IT-LR6', 6),
(26, 'IT-LR7', 7),
(27, 'IT-LR8', 8);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `time`, `status`) VALUES
(1, 'IT-LR1', '11:00 am-1:30 pm', 1),
(2, 'IT-LR2', '11:00 am-1:30 pm', 1),
(3, 'IT-LR3', '11:00 am-1:30 pm', 1),
(4, 'IT-LR4', '11:00 am-1:30 pm', 1),
(5, 'IT-LR5', '11:00 am-1:30 pm', 1);

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `course` varchar(50) NOT NULL,
  `year` varchar(25) NOT NULL,
  `section` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `course`, `year`, `section`) VALUES
(1, 'BSIT', '1st', 'East'),
(2, 'BSIT', '4th', 'East'),
(3, 'BSIT', '2nd', 'West'),
(4, 'BSIT', '1st', 'Northeast'),
(5, 'BSIT', '1st', 'South'),
(6, 'BSIT', '1st', 'North'),
(7, 'BSIT', '1st', 'West'),
(9, 'BSIT', '4th', 'West'),
(10, 'BSIT', '4th', 'South'),
(11, 'BSIT', '4th', 'North'),
(12, 'BSIT', '3rd', 'East'),
(13, 'BSIT', '3rd', 'West'),
(14, 'BSIT', '3rd', 'South'),
(15, 'BSIT', '3rd', 'North'),
(16, 'BSIT', '3rd', 'Northeast'),
(17, 'BSIT', '2nd', 'East'),
(18, 'BSIT', '2nd', 'South'),
(19, 'BSIT', '2nd', 'Northeast'),
(20, 'BSIT', '2nd', 'North'),
(23, 'BEED', '4th', 'A'),
(24, 'BEED', '4th', 'B'),
(25, 'BSED', '4th', 'A'),
(26, 'BSHM', '1st', 'A'),
(27, 'BSED', '1st', 'B'),
(28, 'BSED', '2nd', 'A'),
(29, 'BSHM', '4th', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id` int(25) NOT NULL,
  `sem` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id`, `sem`) VALUES
(1, '1st'),
(2, '2nd'),
(3, 'Summer');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `Lec_Units` int(50) NOT NULL,
  `Lab_Units` int(50) NOT NULL,
  `total_units` int(3) NOT NULL,
  `hours` int(25) NOT NULL,
  `course` varchar(50) NOT NULL,
  `status` int(25) NOT NULL,
  `year` varchar(50) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `specialization` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject`, `description`, `Lec_Units`, `Lab_Units`, `total_units`, `hours`, `course`, `status`, `year`, `semester`, `specialization`) VALUES
(9, 'ITE 121', 'Computer Programming 2', 2, 1, 3, 5, 'BSIT', -17, '1st', '2nd', 'Major'),
(10, 'ITE 122', 'Human Computer Interaction', 2, 1, 3, 5, 'BSIT', -11, '1st', '2nd', 'Major'),
(11, 'ITM120', 'Web Development', 2, 1, 3, 5, 'BSIT', -8, '1st', '2nd', 'Major'),
(12, 'ITE220', 'ITE Prof Elect1- Object- Oriented Programming', 2, 1, 3, 5, 'BSIT', -20, '2nd', '2nd', 'Major'),
(16, 'ENGL120', 'Purposive Communication', 3, 0, 3, 3, 'BSIT', -1, '1st', '1st', 'Minor'),
(17, 'STS120', 'Science, Technology and Society', 3, 0, 3, 3, 'BSIT', 0, '2nd', '2nd', 'Minor'),
(18, 'RIZAL', 'Life and Works of Rizal', 3, 0, 3, 3, 'BSIT', 0, '2nd', '2nd', 'Minor'),
(22, 'ITE222', 'Database 1', 2, 1, 3, 5, 'BSIT', -4, '2nd', '2nd', 'Major'),
(23, 'ITE221', 'Networking 1', 2, 1, 3, 5, 'BSIT', -3, '2nd', '2nd', 'Major'),
(24, 'ITM220', 'Office Productivity Enhancement', 2, 1, 3, 5, 'BSIT', -3, '2nd', '2nd', 'Major'),
(25, 'ITE224', 'Computer Organization and Architecture', 3, 0, 3, 3, 'BSIT', 0, '2nd', '2nd', 'Major'),
(26, 'Electives in Techn', 'METHODS OF RESEARCH IN COMPUTING', 3, 0, 3, 3, 'BSIT', 0, '3rd', '2nd', 'Minor'),
(27, 'ITM320', 'MOBILE APPLICATIONS DEVELOPMENT', 2, 1, 3, 5, 'BSIT', -3, '3rd', '2nd', 'Major'),
(28, 'ITE321', 'Applications Development and Emerging Technologies', 2, 1, 3, 5, 'BSIT', 0, '3rd', '2nd', 'Major'),
(31, 'ITE 111', 'Introduction to Computing', 2, 1, 3, 5, 'BSIT', -4, '1st', '1st', 'Major'),
(32, 'ITE 112', 'Computer Programming 1', 2, 1, 3, 5, 'BSIT', -4, '1st', '1st', 'Major'),
(33, 'SOCSCI 110', 'Understanding the Self', 3, 0, 3, 3, 'BSIT', 0, '1st', '1st', 'Minor'),
(34, 'HIST 110', 'Readings in the Philippine History', 3, 0, 3, 3, 'BSIT', -1, '1st', '1st', 'Minor'),
(35, 'LIT 110', 'The Contemporary World', 3, 0, 3, 3, 'BSIT', 0, '1st', '1st', 'Minor'),
(36, 'MATH 110', 'Mathematics in Modern World', 3, 0, 3, 3, 'BSIT', 0, '1st', '1st', 'Minor'),
(37, 'FIL 110', 'Komunikasyon sa Akademikong Filipino', 3, 0, 3, 3, 'BSIT', 0, '1st', '1st', 'Minor'),
(38, 'PE 1', 'Movement Enhancement', 2, 0, 2, 3, 'BSIT', -3, '1st', '1st', 'Minor'),
(39, 'NSTP 1', 'National Service Training Program 1', 3, 0, 3, 3, 'BSIT', 0, '1st', '1st', 'Minor'),
(40, 'ITE 123', 'Discrete Mathematics', 3, 0, 3, 3, 'BSIT', 0, '1st', '2nd', 'Major'),
(41, 'HUMS 120', 'Art Appreciation', 3, 0, 3, 3, 'BSIT', 0, '1st', '2nd', 'Minor'),
(42, 'FIL 120', 'Masining na Pagpapahayag', 3, 0, 3, 3, 'BSIT', 0, '1st', '2nd', 'Minor'),
(43, 'PE 2', 'Fitness Exercises', 2, 0, 2, 3, 'BSIT', 0, '1st', '2nd', 'Minor'),
(44, 'NSTP 2', 'National Service Training Program 2', 3, 0, 3, 3, 'BSIT', 0, '1st', '2nd', 'Minor'),
(45, 'ITE 211', 'Data Structures and Algorithms', 2, 1, 3, 5, 'BSIT', -9, '2nd', '1st', 'Major'),
(46, 'ITE 212', 'Multimedia System', 2, 1, 3, 5, 'BSIT', -1, '2nd', '1st', 'Major'),
(47, 'ITE 213', 'Quantitative Method (incl Modelling & Simulation)', 3, 0, 3, 3, 'BSIT', -3, '2nd', '1st', 'Major'),
(48, 'ITM 210', 'Digital Logic Design', 2, 1, 3, 5, 'BSIT', 0, '2nd', '1st', 'Major'),
(49, 'ITM 211', 'Fundamentals of Accounting', 3, 0, 3, 3, 'BSIT', 0, '2nd', '1st', 'Major'),
(50, 'SOCSCI 210', 'Ethics: Moral Valuation', 3, 0, 3, 3, 'BSIT', 0, '2nd', '1st', 'Minor'),
(51, 'SSP 210', 'Gender and Society', 3, 0, 3, 3, 'BSIT', 0, '2nd', '1st', 'Minor'),
(54, 'MST 210', 'People and the Earths Ecosystem', 3, 0, 3, 3, 'BSIT', 0, '2nd', '1st', 'Minor'),
(55, 'PE 3', 'PATH-Fit I(Physical Activities Towards Health and Fitness)', 2, 0, 2, 3, 'BSIT', 0, '2nd', '1st', 'Minor'),
(56, 'PE 4', 'PATH-Fit II(Physical Activities Towards Health and Fitness)', 2, 0, 2, 3, 'BSIT', -1, '2nd', '2nd', 'Minor'),
(57, 'ITE 310', 'ITE Prof Elect2-Platform Technologies', 2, 1, 3, 5, 'BSIT', -2, '3rd', '1st', 'Major'),
(58, 'ITE 311', 'Information Management', 2, 1, 3, 5, 'BSIT', 0, '3rd', '1st', 'Major'),
(59, 'ITE 312', 'System Integration and Architecture', 2, 1, 3, 5, 'BSIT', 0, '3rd', '1st', 'Major'),
(60, 'AH 310', 'Philippine Popular Culture', 3, 0, 3, 3, 'BSIT', 0, '3rd', '1st', 'Minor'),
(61, 'ENGL 310', 'Speech Improvement', 3, 0, 3, 3, 'BSIT', 0, '3rd', '1st', 'Minor'),
(62, 'MATH 310', 'Linear Algebra', 3, 0, 3, 3, 'BSIT', 0, '3rd', '1st', 'Minor'),
(63, 'ITE 320', 'ITE Prof Elect3-Web Systems & Technologies', 2, 1, 3, 5, 'BSIT', -1, '3rd', '2nd', 'Major'),
(64, 'ITE 322', 'Information Assurance and Security 1', 2, 1, 3, 5, 'BSIT', -1, '3rd', '2nd', 'Major'),
(65, 'ENGL 320', 'Technical Writing', 3, 0, 3, 3, 'BSIT', 0, '3rd', '2nd', 'Minor'),
(66, 'ITE 400', 'Practicum', 6, 0, 6, 3, 'BSIT', 2, '4th', '1st', 'Major'),
(67, 'ITE 411', 'Information Assurance and Security 2', 2, 1, 3, 5, 'BSIT', -1, '4th', '1st', 'Major'),
(68, 'ITE 412', 'Capstone Project 1', 3, 0, 3, 3, 'BSIT', 0, '4th', '1st', 'Major'),
(69, 'ITM 410', 'Innovative Participation and Social Engagement', 3, 0, 3, 3, 'BSIT', 0, '4th', '1st', 'Major'),
(70, 'ITE 420', 'ITE Prof Elect4-Human Computer Interaction 2', 2, 1, 3, 5, 'BSIT', 0, '4th', '2nd', 'Major'),
(71, 'ITE 421', 'Capstone Project 2', 3, 0, 3, 3, 'BSIT', 4, '4th', '2nd', 'Major'),
(72, 'ITE 422', 'Systems Administration and Maintenance', 3, 0, 3, 3, 'BSIT', 0, '4th', '2nd', 'Major'),
(73, 'Fil 101', 'Introduksyon sa Pag-aaral ng Wika', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(74, 'Fil 102', 'Panimulang Linggwistika', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(75, 'Fil 110', 'Komunikasyon sa Akademikong Filipino', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(76, 'Lit 101', 'Panitikan ng Rehiyon', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(77, 'Fil 103', 'Ang Filipino sa Kurikulum ng Batayang Edukasyon', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(78, 'Soc Sci 120', 'Ethics', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(79, 'Fil 121', 'Pagbasa at Pagsulat Tungo sa Pananaliksik', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(80, 'Fil 201', 'Estruktura ng Wikang Filipino', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(81, 'Fil 202', 'Pagtuturo at Pagtataya ng Makrong Kasanayang Pangwika', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(82, 'Fil 203', 'Ugnayan ng Wika, Kultura at Lipunan', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(83, 'Fil 204', 'Paghahanda at Ebalwasyon ng Kagamitang Panturo', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(84, 'Fil 205', 'Introduksyon sa Pagsasalin', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(85, 'Educ 210', 'The Child and Adolescent Learner and Learning Principles', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(86, 'Educ 211', 'The Teaching Profession', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(87, 'Lit 201', 'Kulturang Popular', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(88, 'Lit 202', 'Sanaysay at Talumpati', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(89, 'Lit 203', 'Panunuring Pampanitikan', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(90, 'Lit 204', 'Maikling Kwento at Nobelang Filipino', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(91, 'Educ 212', 'Facilitating Learner-Centered Teaching ', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(92, 'Fil 202', 'Barayti at Baryasyon ng Wika', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(93, 'Fil 301', 'Introduksyon sa Pamamahayag', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(94, 'Fil 303', 'Mga Natatanging Diskurso sa Wika at Panitikan', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(95, 'TTL 1', 'Technology for Teaching and Learning 1', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(96, 'Educ 301', 'Foundation of Special and Inclusive Education', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(97, 'Educ 302', 'The Teacher and the Community, School, Culture and Organizational Leadership', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(98, 'Educ 303', 'Assessment in Learning 1', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(99, 'Fil 206', 'Introduksyon sa Pananaliksik - Wika at Panitikan', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(100, 'Lit 301', 'Panulaang Filipino', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(101, 'Lit 302', 'Dulaang Filipino', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(102, 'Educ 304', 'Assessment in Learning 2', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(103, 'Educ 305', 'The Teacher and the School Curriculum', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(104, 'Educ 306', 'Building and Enhancing New Literacies Across the Curriculum', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(105, 'TTL 2', 'Technology for Teaching and Learning 2', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(106, 'Elec', 'Research Statistics', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(107, 'Fil 311', 'Pagsasalin sa Ibat-ibang Disiplina', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(108, 'Fil 312', 'Malikhaing Pagsulat', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(109, 'FS 1', 'Field Study 1', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(110, 'FS 2', 'Field Study 2', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(111, 'Review 1', 'LET Review 1', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(112, 'TI 410', 'Teaching Internship', 6, 0, 6, 0, 'BSED', 0, '', '', ''),
(113, 'Review 2', 'LET Review 2 (Major Subjects)', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(114, 'THM 111', 'Macro Perspective of Tourism and Hospitality', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(115, 'THM 112', 'Risk Management as Applied to Safety, Security and Sanitation', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(116, 'HM 111', 'Kitchen Essentials and Basic Food Preparation (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(117, 'HM 111.1', 'Kitchen Essentials and Basic Food Preparation (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(118, 'THM 121', 'Philippines Tourism, Geography and Culture', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(119, 'THM 122', 'Micro Perspective of Tourism and Hospitality', 3, 0, 3, 3, 'BSHM', -1, '1st', '2nd', 'Major'),
(120, 'HM 121', 'Fundamentals in Lodging Operation (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(121, 'HM 121.1', 'Fundamentals in Lodging Operation (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(122, 'HM 122', 'Fundamental in Foodservice Operation (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(123, 'HM 122.1', 'Fundamental in Foodservice Operation (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(124, 'HME 123', 'Culinary Fundamentals (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(125, 'HME 123.1', 'Culinary Fundamentals (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(126, 'THM 123', 'Professional Development and Applied Ethics', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(127, 'HME 211', 'Hospitality Accounting', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(128, 'HME 212', 'Philippines Regional Cuisine (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(129, 'HME 212.1', 'Philippines Regional Cuisine (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(130, 'HME 213', 'Catering and Banquet Operation (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(131, 'HME 213.1', 'Catering and Banquet Operation (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(132, 'HM 214', 'Supply Chain Management in Hospitality Industry', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(133, 'HME 215', 'Housekeeping Operations  (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(134, 'HME 215.1', 'Housekeeping Operations  (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(135, 'HME 221', 'F and B Cost Control', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(136, 'THM 221', 'Quality Service Management in Tourism and Hospitality ', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(137, 'HME 222', 'Bread and Pastry (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(138, 'HME 222.1', 'Bread and Pastry (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(139, 'HME 223', 'Bar and Beverage Management (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(140, 'HME 223.1', 'Bar and Beverage Management (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(141, 'HM 224', 'PRACTICUM 300 hrs. (Restaurant Phase)', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(142, 'HME 311', 'Asian Cuisine (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(143, 'HME 311.1', 'Asian Cuisine (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(144, 'BM 1', 'Operation Management in Tourism and Hospitality Industry', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(145, 'HME 313', 'Front Office Operation (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(146, 'HME 313.1', 'Front Office Operation (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(147, 'HM 314', 'Trends and Issues in the Hospitality', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(148, 'HM 315', 'Foreign Language 1', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(149, 'PEE 310', 'Peace Education', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(150, 'HME 320', 'Food Styling and Design (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(151, 'HME 320.1', 'Food Styling and Design (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(152, 'HM 321', 'Ergonomics and Facilities Planning for Hospitality Industry', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(153, 'HM 322', 'Introduction to MICE (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(154, 'HM 322.1', 'Introduction to MICE (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(155, 'THM 321', 'Multicultural Diversity in Workplace for the Tourism Professional', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(156, 'THM 322', 'Entrepreneurship in Tourism and Hospitality ', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(157, 'HM 323', 'Foreign Language 2', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(158, 'THM 323', 'Tourism and Hospitality Marketing', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(159, 'BM 2', 'Strategic Management in Hospitality and Tourism', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(160, 'HM 412', 'Applied Business Tools and Technologies (PMS) with (lec)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(161, 'HM 412.1', 'Applied Business Tools and Technologies (PMS) with (lab)', 0, 1, 1, 0, 'BSHM', 0, '', '', ''),
(162, 'THM 411', 'Legal Aspects in Tourism and Hospitality', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(163, 'HM 413', 'Research in Hospitality', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(164, 'HM 420', 'Practicum 600 hrs. (Hotel Phase)', 6, 0, 6, 0, 'BSHM', 0, '', '', ''),
(165, 'Engl 111', 'Purposive Communication', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(166, 'Hist 110', 'Readings in Philippines History', 3, 0, 3, 0, 'BSHM', -1, '', '', ''),
(167, 'Math 110', 'Mathematics in the Modern World', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(168, 'PE 111', 'Movement Enhancement (ME)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(169, 'NSTP 111', 'NSTP 1', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(170, 'PE 122', 'Fitness Exercise (FE)', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(171, 'NSTP 122', 'NSTP 2', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(172, 'Socsci 210', 'Understanding the Self', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(173, 'AH 210', 'Philippine Popular Culture', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(174, 'Fil 211', 'Filipino (KomunikasyonsaAkademikongFilipino)', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(175, 'PE 213', 'PATH-Fit I', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(176, 'Fil 220', 'Filipino (MasiningnaPagpapahayag)', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(177, 'SOCSCI 220', 'Ethics', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(178, 'SSP 220', 'Gender & Society', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(179, 'PE 224', 'PATH-Fit II', 2, 0, 2, 0, 'BSHM', 0, '', '', ''),
(180, 'SocSci 310', 'The Contemporary World', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(181, 'STS 310', 'Science, Technology and Society', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(182, 'Rizal 310', 'Life and Works of Rizal', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(183, 'MST 320', 'People and Earth Ecosystem', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(184, 'HUMS 410', 'Arts Appreciation', 3, 0, 3, 0, 'BSHM', 0, '', '', ''),
(185, 'SocSci 111', 'Understanding the Self', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(186, 'Hist 110', 'Readings in the Philippine History', 3, 0, 3, 0, 'BEED', -1, '', '', ''),
(187, 'Math 110', 'Mathematics in the Modern World', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(188, 'MST 114', 'People and the Earths Ecosystem', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(189, 'SocSci 112', 'The Contemporary World', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(190, 'NSTP 1', 'National Service Training Program 1', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(191, 'SocSci 120', 'Ethics', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(192, 'Engl 120', 'Purposive Communication', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(193, 'AH 123', 'Philippine Popular Culture', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(194, 'STS 120', 'Science, Technology and Society', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(195, 'SSP 114', 'Gender and Society', 3, 0, 0, 0, 'BEED', 0, '', '', ''),
(196, 'PE 2', 'Fitness Exercise (FE)', 2, 0, 2, 0, 'BEED', 0, '', '', ''),
(197, 'NSTP 2', 'National Service Training Program 2', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(198, 'Rizal 210', 'The Life and Works of Rizal', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(199, 'PE 3', 'Physical Activities Towards Health and Fitness (PATH-Fit I)', 2, 0, 2, 0, 'BEED', 0, '', '', ''),
(200, 'Hums 220', 'Art Appreciation', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(201, 'PE 4', 'Physical Activities Towards Health and Fitness (PATH-Fit II)', 2, 0, 2, 0, 'BEED', -1, '', '', ''),
(202, 'SocSci111', 'Understanding the Self', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(203, 'Hist110', 'Readings in the Philippine History', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(204, 'SocSci112', 'The Contemporary World', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(205, 'Math110', 'Mathematics in the Modern World', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(206, 'PE1', 'Movement Enhancement ', 2, 0, 2, 0, 'BSED', 0, '', '', ''),
(207, 'NSTP1', 'National Service Training Program 1', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(208, 'Eng 120', 'Purposive Communication', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(209, 'Humss 120', 'Art Appreciation', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(210, 'STS 120', 'Science, Technology and Society', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(211, 'PE2', 'Fitness Exercise ', 2, 0, 2, 0, 'BSED', 0, '', '', ''),
(212, 'NSTP2', 'National Service Training Program', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(213, 'Rizal210', 'The Life and Works of Rizal', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(214, 'PE3', 'Physical Activities Towards Health and Fitness (PATH-Fit I)', 2, 0, 2, 0, 'BSED', 0, '', '', ''),
(215, 'Engl', 'Speech Improvement', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(216, 'MST213', 'People and the Earths Ecosystems', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(217, 'PE 4', 'Physical Activities Towards Health and Fitness  (PATH-Fit II)', 2, 0, 2, 0, 'BSED', -1, '', '', ''),
(218, 'SSP 311', 'Gender and Society', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(219, 'AH 304', 'Philippine Popular Culture', 3, 0, 3, 0, 'BSED', 0, '', '', ''),
(220, 'Fil110', 'Komunikasyon sa Akademikong Filipino', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(221, 'Educ 111', 'The Child Adolescent Learners and Learning Principles', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(222, 'Fil 121', 'Pagbasa at Pagsulat Tungo sa Pananaliksik', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(223, 'Educ 122', 'Content and Pedagogy for the Mother - Tongue', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(224, 'Educ 211', 'Teaching Math in Primary Grades', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(225, 'TTL 210', 'Technology for Teaching and Learning 1', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(226, 'Educ 212', 'Facilitating Leaner-Centered Teaching', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(227, 'Educ 213', 'Teaching Science in Elementary Grades (Biology and Chemistry)', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(228, 'Educ 214', 'Good Manners and Right Conduct (GMRC)', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(229, 'Educ 216', 'Pagtuturo ng Filipino sa Elementarya (Instruktura at Gamit ng Wikang Filipino)', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(230, 'Educ 217', 'Teaching Social Studies in Elementary Grades (Culture and Geography)', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(231, 'EPP 221', 'Edukasyong Pantahanan at Pangkabuhayan', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(232, 'Educ 222', 'Foundation of Special and Inclusive Education', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(233, 'Educ 223', 'The Teaching Profession', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(234, 'Educ 224', 'Pagtuturo ng Filipino sa Elementarya (Panitikan ng Pilipinas)', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(235, 'Educ 225', 'Teaching Science in Elementary Grades (Physics, Earth and Space Science)', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(236, 'Educ 226', 'Teaching Math in The Intermediate Grades', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(237, 'Educ 227', 'Teaching Social Studies in Elementary Grades (Philippine History and Government)', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(238, 'Educ 310', 'Teaching English in the Elementary Grades (Language Arts)', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(239, 'Educ 312', 'The Teacher and the School Curriculum', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(240, 'Educ 313', 'Assessment of Learning 1', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(241, 'TTL 314', 'Technology for Teaching and Learning in the Elementary Grades ', 3, 0, 0, 0, 'BEED', 0, '', '', ''),
(242, 'Educ 315', 'Teaching Music in the Elementary Grades', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(243, 'Educ 316', 'Teaching Arts in the Elementary Grades', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(244, 'Res 318', 'Elements of Research', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(245, 'Engl 317', 'English Enhancement', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(246, 'Educ 320', 'Assessment of Learning 2', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(247, 'Educ 321', 'Teaching English in the Elementary Grades Through Literature', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(248, 'Educ 322', 'The Teacher and the Community, School Culture and Organizational Leadership', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(249, 'Educ 323', 'Teaching PE and Health in the Elementary Grades', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(250, 'EPP 324', 'Edukasyong Pantahanan at Pangkabuhayan with Entreprenuership', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(251, 'Educ 325', 'Building and Enhancing New Literacies Across the Curriculum', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(252, 'Res 325', 'Research in Education', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(253, 'Educ 410', 'Teaching Multi-Grade Casses', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(254, 'Engl 311', 'Speech Improvement', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(255, 'FS 1', 'Field Study 1', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(256, 'FS 2', 'Field Study 2', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(257, 'Review 1', 'LET Review 1', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(258, 'TI 410', 'Teaching Internship', 6, 0, 6, 0, 'BEED', 0, '', '', ''),
(259, 'Review 2', 'LET Review 2', 3, 0, 3, 0, 'BEED', 0, '', '', ''),
(260, 'SocSci 111', 'Understanding the Self', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(261, 'Hist 110', 'Readings in the Philippines History', 3, 0, 3, 0, 'BSBA', -1, '', '', ''),
(262, 'SocSci 112', 'The Contemporary World', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(263, 'Math 111', 'Mathematics in the Modern World', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(264, 'Fil 111', 'Komunikasyon sa Akademikong Filipino', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(265, 'Econ 111', 'Basic Microeconomics', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(266, 'PE 111', 'Movement Enhancement (ME)', 2, 0, 2, 0, 'BSBA', 0, '', '', ''),
(267, 'NSTP 111', 'National Service Training Program 1', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(268, 'Eng 121', 'Purposive Communication', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(269, 'Hums 120', 'Art Appreciation', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(270, 'STS 120', 'Science, Technology and Society', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(271, 'Soc Sci 120', 'Ethics', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(272, 'Fil 120', 'Masining na Pagpapahayag', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(273, 'Math 122', 'Mathematics of Investment', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(274, 'PE 122', 'Fitness Exercise (FE)', 2, 0, 2, 0, 'BSBA', 0, '', '', ''),
(275, 'NSTP 122', 'National Service Training Program 2', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(276, 'IT 210', 'Electronic Spreadsheet', 2, 1, 3, 0, 'BSBA', 0, '', '', ''),
(277, 'MST 211', 'People and the Earths Ecosystem', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(278, 'SSP 211', 'Gender and Society', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(279, 'Engl 211', 'Business Correspondence', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(280, 'FM 211', 'Financial Management', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(281, 'Math 213', 'Quantitative Analysis of Business', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(282, 'Acctg. 211', 'Intermediate Accounting 1', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(283, 'PE 213', 'Physical Activities Towards Health and Fitness (PATH-Fit I)', 2, 0, 2, 0, 'BSBA', 0, '', '', ''),
(284, 'Rizal', 'Rizals Life and Works', 3, 0, 3, 0, 'BSBA', -1, '', '', ''),
(285, 'AH 221', 'Philippine Popular Culture', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(286, 'LAW 221', 'Obligation and Contracts', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(287, 'Tax 221', 'Income Taxation', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(288, 'FM 222', 'Financial Analysis and Reporting', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(289, 'FM 223', 'Banking and Financial Institution', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(290, 'Acctg. 222', 'Intermediate Accounting II', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(291, 'PE 224', 'Physical Activities Towards Health and Fitness (PATH-Fit II)', 2, 0, 2, 0, 'BSBA', 0, '', '', ''),
(292, 'MGT 311', 'Strategic Management', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(293, 'BA 311', 'Good Governance and Social Responsibilities', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(294, 'BA 312', 'Human Resources Management', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(295, 'BA 313', 'International Business and Trade', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(296, 'BA 314', 'Monetary Policy and Central Banking', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(297, 'LAW 312', 'Law on Negotiable Instrument', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(298, 'LIT 310', 'Asian Literature', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(299, 'MGT 322', 'Operation Management and TQM', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(300, 'FM 325', 'Investment and Portfolio Management', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(301, 'Fm 326', 'Credit and Collection', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(302, 'FM 327', 'Capital Market', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(303, 'FME 321', 'Treasury Management', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(304, 'FME 322', 'Controllership Management', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(305, 'BA 325', 'Thesis or Feasibility Study', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(306, 'FME 413', 'Franchising', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(307, 'FM 418', 'Special Topics In Financial Management', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(308, 'PEE 320', 'Peace Education', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(309, 'FME 424', 'Risk Management', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(310, 'FME 425', 'Global Finance with Electronic Banking', 3, 0, 3, 0, 'BSBA', 0, '', '', ''),
(311, 'FM 429', 'PRACTICUM/Work Integrated Learning', 6, 0, 6, 0, 'BSBA', 0, '', '', ''),
(317, '', '', 0, 0, 0, 0, '', 0, '1st Year', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `timeslot`
--

CREATE TABLE `timeslot` (
  `id` int(11) NOT NULL,
  `timeslot` varchar(50) NOT NULL,
  `schedule` varchar(25) NOT NULL,
  `time_id` int(11) NOT NULL,
  `hours` decimal(25,2) NOT NULL,
  `specialization` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timeslot`
--

INSERT INTO `timeslot` (`id`, `timeslot`, `schedule`, `time_id`, `hours`, `specialization`) VALUES
(1, '4:00 pm-5:30 pm', 'MW', 10, '1.50', 'Minor'),
(2, '8:30 am-11:00 am', 'MW', 2, '2.50', 'Major'),
(3, '11:00 am-1:30 pm', 'MW', 5, '2.50', 'Major'),
(4, '1:30 pm-4:00 pm', 'MW', 9, '2.50', 'Major'),
(5, '4:00 pm-6:30 pm', 'MW', 11, '2.50', 'Major'),
(8, '7:00 am-12:00 nn', 'F', 1, '5.00', 'Major'),
(9, '7:00 am-10:00 am', 'F', 2, '3.00', 'Minor'),
(11, '10:00 am-1:00 pm', 'F', 3, '3.00', 'Minor'),
(12, '12:00 nn-5:00 pm', 'F', 4, '5.00', 'Major'),
(13, '7:00 am-12:00 nn', 'Sat', 1, '5.00', 'Major'),
(14, '12:00 nn-5:00 pm', 'Sat', 4, '5.00', 'Major'),
(15, '1:00 pm-4:00 pm', 'F', 5, '3.00', 'Minor'),
(16, '1:00 pm-4:00 pm', 'Sat', 5, '3.00', 'Minor'),
(17, '4:00 pm-7:00 pm', 'Sat', 6, '3.00', 'Minor'),
(18, '4:00 pm-7:00 pm', 'F', 6, '1.50', 'Minor'),
(20, '2:30 pm-4:00 pm', 'MW', 8, '1.50', 'Minor'),
(21, '7:00 am-8:30 am', 'TTH', 1, '0.00', 'Minor'),
(22, '8:30 am-11:00 am', 'TTH', 2, '0.00', 'Major'),
(24, '7:00 am-10:00am', 'Sat', 1, '0.00', 'Minor');

-- --------------------------------------------------------

--
-- Table structure for table `tthloading`
--

CREATE TABLE `tthloading` (
  `id` int(50) NOT NULL,
  `timeslot_id` int(50) NOT NULL,
  `timeslot` varchar(50) NOT NULL,
  `rooms` varchar(50) NOT NULL,
  `faculty` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `subjects` varchar(100) NOT NULL,
  `days` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tthloading`
--

INSERT INTO `tthloading` (`id`, `timeslot_id`, `timeslot`, `rooms`, `faculty`, `course`, `subjects`, `days`) VALUES
(2, 1, '8:30 am-11:00 am', 'IT-LR1', 'Alcazar, Jessica D', '2ndEast', 'ITM 120', 'TTh'),
(3, 1, '11:00 am-1:30 pm', 'IT-LR2', 'Bracero, Richard E', '2ndNortheast', 'STS120', 'TTh'),
(4, 1, '8:30 am-11:00 am', 'IT-LR4', 'Boltron, J C', '1stEast', 'ENGL120', 'TTh');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin,2=Staff, 3= subscriber'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 1),
(3, 'Coco', 'admincoco', '78e0d5058803a3d6481b946b5e7a2510', 1),
(7, 'Zea', 'babyz', '6ecb036b473231973a96d79b3abf695d', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_schedule_info`
--
ALTER TABLE `class_schedule_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `days`
--
ALTER TABLE `days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daysofweek`
--
ALTER TABLE `daysofweek`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loading`
--
ALTER TABLE `loading`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomlist`
--
ALTER TABLE `roomlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeslot`
--
ALTER TABLE `timeslot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tthloading`
--
ALTER TABLE `tthloading`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_schedule_info`
--
ALTER TABLE `class_schedule_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `days`
--
ALTER TABLE `days`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `daysofweek`
--
ALTER TABLE `daysofweek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `loading`
--
ALTER TABLE `loading`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `roomlist`
--
ALTER TABLE `roomlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=318;

--
-- AUTO_INCREMENT for table `timeslot`
--
ALTER TABLE `timeslot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tthloading`
--
ALTER TABLE `tthloading`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
