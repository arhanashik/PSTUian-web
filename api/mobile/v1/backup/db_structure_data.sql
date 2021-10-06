-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 06, 2021 at 10:18 AM
-- Server version: 5.7.35
-- PHP Version: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pstuianc_dev.db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'admin',
  `deleted` tinyint(4) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `role`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', 'admin', 'admin', 0, '2021-10-06 10:47:08', '2021-10-06 10:06:42'),
(2, 'superadmin', 'super_admin', 'super_admin', 0, '2021-10-06 21:33:12', '2021-10-06 23:10:38');

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `session` varchar(20) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `total_student` varchar(255) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `batch`
--

INSERT INTO `batch` (`id`, `name`, `title`, `session`, `faculty_id`, `total_student`, `deleted`, `created_at`, `updated_at`) VALUES
(4, 'CSE 11th Batch', 'Duronto 11', '2013-14', 6, '67', 0, '2021-10-02 06:38:28', '2021-10-02 06:38:28'),
(5, 'CSE 10th Batch', 'Projjolito 10', '2012-13', 6, '53', 0, '2021-10-02 06:38:28', '2021-10-02 06:38:28'),
(6, 'Ag 1st Batch', 'Ag 1st Batch', '2000-01', 5, '39', 0, '2021-10-06 10:10:24', '2021-10-06 10:10:24');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `credit_hour` varchar(255) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course_code`, `course_title`, `credit_hour`, `faculty_id`, `status`, `deleted`, `created_at`, `updated_at`) VALUES
(3, 'EEE-121', 'Electrical and Electronics Engineering', '1.5', 6, 0, 0, '2021-10-02 07:55:31', '2021-10-02 07:55:31'),
(4, 'CSE-111', 'Computer Architecture', '3.0', 6, 0, 0, '2021-10-02 07:55:31', '2021-10-02 07:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `info` varchar(500) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `reference` varchar(150) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `donation`
--

INSERT INTO `donation` (`id`, `name`, `info`, `email`, `reference`, `confirmed`, `deleted`, `created_at`, `updated_at`) VALUES
(5, 'Md. Hasnain', 'CSE 11th batch', 'ashik.pstu.cse@gmail.com', 'cv242424jefr', 1, 0, '2021-10-01 08:15:49', '2021-10-01 08:15:49'),
(6, 'Donor 2 with a huge name and it should work find let\"s see', 'This is the info about the donor. Thanks a lot for the donation. It helps a lot for the development and management of the app. Hope more people will continue supporting us.', 'ashik.pstu.cse@gmail.com', 'sfjslj4rl3j53 ', 1, 0, '2021-10-01 08:18:59', '2021-10-01 08:18:59'),
(7, 'test name', 'test info', 'test email', 'test ref', 1, 0, '2021-10-01 08:46:45', '2021-10-01 08:46:45'),
(8, 'hggg', 'hggg', 'bvjkkj', 'bbjjkj', 0, 0, '2021-10-01 08:52:43', '2021-10-06 10:01:40'),
(9, 'Donor 3', 'tedt', 'testg@g.c', 'dufgvlhhohogoyigfifif', 1, 0, '2021-10-05 00:20:29', '2021-10-05 00:20:29'),
(10, 'test now', 'ss ssf fs', 'ashik.pstu.cse@gmail.com', 'sssfd sfs sfsdf ', 0, 0, '2021-10-06 10:02:01', '2021-10-06 10:02:12');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `faculty_id`, `name`, `designation`, `department`, `phone`, `address`, `image_url`, `deleted`, `created_at`, `updated_at`) VALUES
(4, 6, 'Mr. Employee', 'Officer', 'Mathematics', '0123456789', 'Test address', 'slider1.jpg', 0, '2021-10-03 22:53:27', '2021-10-06 10:03:41'),
(5, 6, 'HASNAIN MD', 'sfddsf', 'sfsdfdf', '08058548514', 'Katsushika City, Shinkoiwa, 4-4-5, DK-House, #344', NULL, 1, '2021-10-06 10:03:49', '2021-10-06 10:03:53');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `short_title` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `short_title`, `title`, `deleted`, `created_at`, `updated_at`) VALUES
(5, 'Ag', 'Agriculture', 0, '2021-10-01 06:56:51', '2021-10-06 10:05:39'),
(6, 'CSE', 'Computer Science and Engineering', 0, '2021-10-01 06:56:51', '2021-10-01 06:56:51'),
(7, 'FS', 'Fisheries', 0, '2021-10-01 06:56:51', '2021-10-01 06:56:51'),
(8, 'BBA', 'Business Administration and Management', 0, '2021-10-01 06:56:51', '2021-10-01 06:56:51'),
(9, 'AH & DVM', 'Animal Science and Veterinary Medicine', 0, '2021-10-01 06:56:51', '2021-10-01 06:56:51'),
(10, 'DM', 'Environmental Science and Disaster Management', 0, '2021-10-01 06:56:51', '2021-10-01 06:56:51'),
(11, 'NFS', 'Nutrition and Food Science', 0, '2021-10-01 06:56:51', '2021-10-01 06:56:51'),
(12, 'LMA', 'Land Management and Administration', 0, '2021-10-01 06:56:51', '2021-10-01 06:56:51'),
(13, 'MS', 'Postgraduate Studies', 0, '2021-10-01 06:56:51', '2021-10-06 10:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `id` int(11) NOT NULL,
  `donation_option` varchar(500) NOT NULL,
  `deleted` tinyint(4) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id`, `donation_option`, `deleted`, `created_at`, `updated_at`) VALUES
(2, 'Bkash - 123456<br>Rocket - 123445<br>Option 3 - 12324', 0, '2021-10-01 07:40:52', '2021-10-01 07:40:52');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `title`, `image_url`, `deleted`, `created_at`, `updated_at`) VALUES
(5, 'slider 1', 'slider1.jpg', 0, '2021-10-01 06:14:03', '2021-10-01 06:14:03'),
(6, 'Slider 2', 'slider2.jpg', 0, '2021-10-01 06:14:03', '2021-10-01 06:14:03'),
(7, 'Slider 3', 'slider3.jpg', 0, '2021-10-01 06:14:03', '2021-10-01 06:14:03'),
(8, 'Slider 4', 'slider4.jpg', 0, '2021-10-01 06:14:03', '2021-10-01 06:14:03'),
(9, 'Slider 5', 'slider5.jpg', 0, '2021-10-01 06:14:03', '2021-10-01 06:14:03'),
(10, 'Slider 6', 'Slider6.jpg', 1, '2021-10-06 10:07:57', '2021-10-06 10:07:59');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `name` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `reg` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `linked_in` varchar(255) DEFAULT NULL,
  `blood` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `batch_id` int(11) NOT NULL,
  `session` varchar(255) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `fb_link` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `cv_link` varchar(500) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`name`, `id`, `reg`, `phone`, `linked_in`, `blood`, `address`, `email`, `batch_id`, `session`, `faculty_id`, `fb_link`, `image_url`, `cv_link`, `deleted`, `created_at`, `updated_at`) VALUES
('Md. Hasnain', 1302018, '04192', '+818058548514', 'https://www.linkedin.com/in/md-hasnain-407444141/', 'B+', 'Tokyo, Japan', 'ashik.pstu.cse@gmail.com', 4, '2013-14', 6, 'https://www.facebook.com/arhan.ashik', 'https://arhanashik.github.io/assets/img/me-profile.jpg', 'https://docs.google.com/document/d/11p-Vh5rJyVln7YrS_RammRpsFgO23coBrAu0ilckjqY/edit?usp=sharing', 0, '2021-10-02 07:30:52', '2021-10-02 07:30:52'),
('Student 2', 1302019, '04193', NULL, NULL, NULL, 'Dhaka, Bangladesh', 'test@gmail.com', 4, '2013-14', 6, NULL, NULL, NULL, 0, '2021-10-02 07:30:52', '2021-10-02 07:30:52'),
('Ag 1st Student', 12013179, '01234', NULL, NULL, NULL, NULL, NULL, 6, '2000-01', 5, NULL, NULL, NULL, 0, '2021-10-06 10:11:01', '2021-10-06 10:11:09'),
('CSE 10 STu', 13013181, '03134', NULL, NULL, NULL, NULL, NULL, 5, '2012-13', 6, NULL, NULL, NULL, 0, '2021-10-06 10:09:47', '2021-10-06 10:09:47');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `linked_in` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `faculty_id` int(11) NOT NULL,
  `fb_link` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `name`, `designation`, `status`, `phone`, `linked_in`, `address`, `email`, `department`, `faculty_id`, `fb_link`, `image_url`, `deleted`, `created_at`, `updated_at`) VALUES
(8, 'Test Teacher', 'Assistance Professor', 'Test Status', NULL, 'Test linkedIn', NULL, 'Test email', 'Mathematics', 6, 'Test fb link', 'slider1.jpg', 0, '2021-10-02 07:54:23', '2021-10-02 07:54:23'),
(9, 'Test Teacher 2', 'Test Designation 2', NULL, NULL, NULL, NULL, NULL, 'Test department 2', 6, NULL, NULL, 0, '2021-10-02 07:54:23', '2021-10-02 07:54:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
