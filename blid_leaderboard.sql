-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2024 at 09:05 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blid_leaderboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `sub_category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaderboards`
--

CREATE TABLE `leaderboards` (
  `leaderboard_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `sub_category` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `best_time` time NOT NULL,
  `car_used` varchar(100) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `submission_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `reject_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaderboards`
--

INSERT INTO `leaderboards` (`leaderboard_id`, `category`, `sub_category`, `user_id`, `best_time`, `car_used`, `youtube_link`, `approved`, `submission_date`, `description`, `approved_by`, `reject_reason`) VALUES
(1, 'Circuit 1', 'Career Any%', 1, '00:11:11', 'RX-7', 'https://youtu.be/sJXZ9Dok7u8?si=j_IRfzkLHESFRf6F', 1, '2024-07-30', 'asd', 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `social_media` varchar(255) DEFAULT NULL,
  `role` enum('user','moderator','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `password`, `profile_picture`, `social_media`, `role`) VALUES
(1, 'iqbal', 'Iqbal Dwiansyah', 'iqbaldwiansyah@gmail.com', '$2y$10$mTUSWzbk/7MQmvdv.xihHOUbDldmekvkEBcMLkXkO2k5.K4E90VlW', 'uploads/keen.png', '', 'moderator'),
(2, 'test', 'test', 'test@gmail.com', '$2y$10$mmdwkuk.Lwumk7RAVA9a3eL8QqZCjNGjT43aOoQSyCAwy7xBgaa7K', 'uploads/pngwing.com (8).png', NULL, 'user'),
(3, '123', '123', 'gentengbasah@gmail.com', '$2y$10$6okQ1Rr37p8SF6y.DCERoeyCCqE9T/6NJGokuI.BOgitG5PEEU1gy', 'uploads/profile_pictures/keen.jpg', '', 'user'),
(4, 'iqbaldwi', 'Iqbal Dwiansyah', 'iqbaldwi@gmail.com', '$2y$10$.vF56gF5PN2zI/6zvXED1Of/.PAsJa2snfZ2yjq7J6nau6.DdTLEm', '66a886ace8313.jpg', NULL, 'moderator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `leaderboards`
--
ALTER TABLE `leaderboards`
  ADD PRIMARY KEY (`leaderboard_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaderboards`
--
ALTER TABLE `leaderboards`
  MODIFY `leaderboard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leaderboards`
--
ALTER TABLE `leaderboards`
  ADD CONSTRAINT `leaderboards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
