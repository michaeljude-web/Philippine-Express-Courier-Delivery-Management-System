-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 01:33 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_im`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parcel_id` int(11) DEFAULT NULL,
  `new_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_code` varchar(50) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_code`, `street`, `city`, `zip_code`, `country`, `contact`) VALUES
(1, '', 'Rizal BAnga', 'Koronadal', '9511', 'Philipines', '09069191923'),
(3, '', 'Bo.1 Liwanay Banga', 'South Cotabato', '9511', 'Philipines', '09069191929'),
(4, '', 'Rizal BAnga', 'South Cotabato', '9511', 'Philipines', '09069191928'),
(7, '12', 'Bo.1 Liwanay Banga', 'Koronadal', '9511', 'd', '09069191929'),
(8, '12', 'Rizal BAnga', 'Philippines', '9511', 'Philippines', '09069191923'),
(9, '12', 'd', 'Philippines', 'd', 'Philippines', '09069191929'),
(11, '', 's', 'a', '2', 'Philippines', 's');

-- --------------------------------------------------------

--
-- Table structure for table `parcels`
--

CREATE TABLE `parcels` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_address` varchar(255) NOT NULL,
  `sender_contact` varchar(15) NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `recipient_address` varchar(255) NOT NULL,
  `recipient_contact` varchar(15) NOT NULL,
  `type` enum('1','2') NOT NULL,
  `from_branch_id` int(11) NOT NULL,
  `to_branch_id` int(11) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `height` decimal(10,2) NOT NULL,
  `width` decimal(10,2) NOT NULL,
  `length` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parcels`
--

INSERT INTO `parcels` (`id`, `reference_number`, `sender_name`, `sender_address`, `sender_contact`, `recipient_name`, `recipient_address`, `recipient_contact`, `type`, `from_branch_id`, `to_branch_id`, `weight`, `height`, `width`, `length`, `price`, `status`, `date_created`) VALUES
(1, 'REF-672934C3D657F', 'Michael', 'Banga South Cotabato', '09069191922', 'Jude', 'Tupi South cotabato', '09351577810', '1', 1, 1, '2.00', '2.00', '3.00', '3.00', '3.00', 'Arrived at Destination', '2024-11-04 20:55:31'),
(2, 'REF-672937F71B174', 'Michael', 'Banga South Cotabato', '09069191922', 'Jude', 'Tupi South cotabato', '303012032013021', '1', 1, 1, '1.00', '1.00', '1.00', '1.00', '2.00', 'Unsuccessful Delivery Attempt', '2024-11-04 21:09:11'),
(5, 'REF-6729CEAED5DD0', 'K', 'Banga South Cotabato', '09069191922', 'na', 'Tupi South cotabato', '09069191922', '1', 1, 3, '1.00', '1.00', '1.00', '1.00', '1.00', 'Shipped', '2024-11-05 07:52:14'),
(6, 'REF-6729CEEA59C3D', 'ki', 'Banga South Cotabato', '09069191922', 'na', 'Tupi South cotabato', '09351577810', '1', 3, 1, '123.00', '123.00', '123.00', '123.00', '123.00', 'Unsuccessful Delivery Attempt', '2024-11-05 07:53:14'),
(7, 'REF-6730AB3E7BE7C', 'Michael', 'Banga South Cotabato', '09069191922', 'Jude', 'Tupi South cotabato', '09069191922', '1', 4, 7, '2.00', '2.00', '2.00', '2.00', '2.00', 'Ready to Picked Up', '2024-11-10 12:46:54'),
(10, 'REF-6731ECC303295', 'Michael', 'Banga South Cotabato', '09069191922', 'Jude', 'Tupi South cotabato', '09351577810', '1', 3, 7, '2.00', '2.00', '2.00', '2.00', '2.00', 'Ready to Picked Up', '2024-11-11 11:38:43'),
(11, 'REF-6731ECDDBF28D', 'Michael', 'Banga South Cotabato', '09069191922', 'Jude', 'Tupi South cotabato', '09351577810', '1', 3, 7, '1.00', '2.00', '2.00', '2.00', '3.00', 'In Transit', '2024-11-11 11:39:09'),
(12, 'REF-6731ECFF110D1', 'Michael', 'Banga South Cotabato', '09069191922', 'Jude', 'e', '09351577810', '1', 3, 3, '212.00', '23.00', '1.00', '3.00', '3.00', 'Pending', '2024-11-11 11:39:43'),
(13, 'REF-6732CE3FA4FB7', 'GArde', 'Banga South Cotabato', '09069191922', 'Jude', 'Tupi South cotabato', '09351577810', '1', 1, 3, '5.00', '5.00', '2.00', '2.00', '2.00', 'In Transit', '2024-11-12 03:40:47');

-- --------------------------------------------------------

--
-- Table structure for table `parcel_status_history`
--

CREATE TABLE `parcel_status_history` (
  `id` int(11) NOT NULL,
  `parcel_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parcel_status_history`
--

INSERT INTO `parcel_status_history` (`id`, `parcel_id`, `status`, `changed_at`, `staff_id`) VALUES
(1, 5, 'Item Accepted by Courier', '2024-11-05 12:39:56', NULL),
(2, 5, 'Shipped', '2024-11-05 22:43:48', NULL),
(3, 6, 'Item Accepted by Courier', '2024-11-11 21:08:58', NULL),
(4, 13, 'Item Accepted by Courier', '2024-11-12 03:58:32', NULL),
(5, 13, 'Shipped', '2024-11-12 03:59:16', NULL),
(6, 13, 'In Transit', '2024-11-12 03:59:24', NULL),
(7, 11, 'Item Accepted by Courier', '2024-11-12 21:44:37', NULL),
(8, 11, 'Item Accepted by Courier', '2024-11-12 21:51:53', NULL),
(9, 11, 'Shipped', '2024-11-12 21:52:13', NULL),
(10, 11, 'Item Accepted by Courier', '2024-11-12 21:58:04', NULL),
(11, 11, 'Item Accepted by Courier', '2024-11-12 21:58:34', NULL),
(12, 11, 'Item Accepted by Courier', '2024-11-12 22:01:39', NULL),
(13, 11, 'Item Accepted by Courier', '2024-11-13 00:34:50', NULL),
(14, 11, 'Item Accepted by Courier', '2024-11-13 00:35:44', NULL),
(15, 11, 'Item Accepted by Courier', '2024-11-13 00:36:02', NULL),
(16, 11, 'Shipped', '2024-11-13 00:36:14', NULL),
(17, 11, 'In Transit', '2024-11-20 12:10:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `star_rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `star_rating`, `comment`, `created_at`) VALUES
(2, 4, 5, 'hi', '2024-11-13 03:07:12'),
(3, 4, 3, 'g', '2024-11-13 04:49:15'),
(4, 5, 1, 'Cute Sa Rider hehe..', '2024-11-17 08:06:08'),
(5, 5, 4, 's', '2024-11-19 06:48:57'),
(6, 5, 2, 'g', '2024-11-19 06:49:18'),
(7, 5, 4, 'y', '2024-11-19 06:50:19');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `firstname`, `lastname`, `email`, `password`, `branch_id`) VALUES
(3, 'Mike', 'Jude', 'admin@admin.com', '$2y$10$pXQ3Ih3JHcn5ZpOrIZKpXeldd3iJRcV2TL4VU7F3hYRtxoBnZfAwi', 1),
(4, 'Kerby', 'cx', 'c@gmail.com', '$2y$10$gqqGIpmuZHuTz1DwWQV19uNVFgESZw6yedKXvf6mpsCnYbbQ5PwDq', 7),
(6, 'juan', 'dela', 'garde@gmail.com', '12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`) VALUES
(1, 'Michael', 'Michael', 'gardes@gmail.com', '1234'),
(4, 'Mike', 'Garde', 'jay@gmail.com', '1234'),
(5, 'Michael', 'Garde', 'garde@gmail.com', '1234'),
(6, 'Alen', 'GArde', 'admin@gmail.com', '1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_branch_id` (`from_branch_id`),
  ADD KEY `to_branch_id` (`to_branch_id`);

--
-- Indexes for table `parcel_status_history`
--
ALTER TABLE `parcel_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parcel_id` (`parcel_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `parcels`
--
ALTER TABLE `parcels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `parcel_status_history`
--
ALTER TABLE `parcel_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `parcels`
--
ALTER TABLE `parcels`
  ADD CONSTRAINT `parcels_ibfk_1` FOREIGN KEY (`from_branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `parcels_ibfk_2` FOREIGN KEY (`to_branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `parcel_status_history`
--
ALTER TABLE `parcel_status_history`
  ADD CONSTRAINT `parcel_status_history_ibfk_1` FOREIGN KEY (`parcel_id`) REFERENCES `parcels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
