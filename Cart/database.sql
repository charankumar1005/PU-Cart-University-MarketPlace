-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 01:15 PM
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
-- Database: `c_cart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `username`, `is_admin`, `created_at`) VALUES
(1, '23352094@pondiuni.ac.in', 'Charan@587', 'Charan', 1, '2024-11-04 13:31:12');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_verifications`
--

INSERT INTO `email_verifications` (`id`, `email`, `code`, `created_at`) VALUES
(1, '23352094@pondiuni.ac.in', 112988, '2024-11-04 12:26:47');

-- --------------------------------------------------------

--
-- Table structure for table `issues_table`
--

CREATE TABLE `issues_table` (
  `id` int(6) UNSIGNED NOT NULL,
  `issue_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issues_table`
--

INSERT INTO `issues_table` (`id`, `issue_description`, `created_at`) VALUES
(1, 'the website is somewhat slow ', '2024-10-25 07:44:34'),
(2, 'the website is somewhat slow ', '2024-10-25 07:44:40'),
(3, 'Your website is too slow', '2024-10-25 07:45:59'),
(4, 'Not that much fast ', '2024-10-25 07:47:38'),
(5, 'Not that much fast ', '2024-10-25 07:49:53'),
(6, 'Not fast as that much', '2024-10-25 07:53:46'),
(7, 'Good Ui should be improved', '2024-10-25 07:55:12'),
(8, 'not ', '2024-10-25 07:56:04'),
(9, 'Low performance of an website', '2024-10-25 10:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(1, 25, 33, 'hello', '2024-11-12 16:58:39'),
(2, 25, 34, 'hello', '2024-11-12 16:59:19'),
(3, 34, 25, 'yes', '2024-11-12 17:03:58'),
(4, 25, 34, 'i want to buy your product', '2024-11-12 17:23:16'),
(5, 25, 0, 'hello', '2024-11-12 18:10:55'),
(6, 25, 0, 'hello', '2024-11-12 18:11:09'),
(7, 25, 0, 'hello', '2024-11-12 18:11:42'),
(8, 25, 0, 'hello', '2024-11-12 18:12:15'),
(9, 33, 25, 'Hey Naresh !', '2024-11-13 09:16:51'),
(10, 33, 25, 'How are you', '2024-11-13 09:17:04'),
(11, 33, 25, 'Hey Naresh !', '2024-11-13 09:17:12'),
(12, 33, 25, 'Em chestunnav', '2024-11-13 11:04:07');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Item_Condition` enum('New','Used') NOT NULL DEFAULT 'Used',
  `negotiable` enum('Yes','No') DEFAULT 'No',
  `category` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `name`, `image`, `price`, `description`, `created_at`, `Item_Condition`, `negotiable`, `category`, `category_id`) VALUES
(17, 24, 'Watch', '671b2c6fd3652.jpg', 599.00, 'Water Proof.<br />\r\nGood Looking.', '2024-10-25 05:28:15', 'Used', 'No', '', NULL),
(18, 25, 'Boat Airdops175  Wireless Earbuds', '671b38ea8e583.jpg', 499.00, 'Having good Base.\r\nUpto 45 hours playtime.\r\nJust 10minutes of charge 100minutes of playtime.\r\nUsed for 3 months only.', '2024-10-25 06:21:30', 'Used', 'No', '', NULL),
(19, 26, 'Voyage Perfume Body Spray', '671b3acf70c97.jpg', 149.00, 'Refreshing citrus activities your mind<br />\r\nMade by global experts<br />\r\n24 hours of refreshness', '2024-10-25 06:29:35', 'Used', 'No', '', NULL),
(20, 27, 'HP Laptop Bag', '671b3bf89c342.jpg', 299.00, 'Protect your laptop with a padded laptop compartment<br />\r\nThis backpack is comfortable,duarble<br />\r\nLightweight', '2024-10-25 06:34:32', 'Used', 'No', '', NULL),
(21, 25, 'laptop', '671b6c57c5cac.jpeg', 5000.00, 'Good to use <br />\r\nHigh performnce', '2024-10-25 10:00:55', 'Used', 'No', '', NULL),
(26, 25, 'cycle', '671fa6ae0668d.jpeg', 2500.00, 'Good condition', '2024-10-28 14:58:54', '', 'Yes', '', NULL),
(27, 25, 'bike', '673245946558d.jpeg', 15000.00, 'Good condition', '2024-11-11 17:57:40', 'Used', 'Yes', 'Bikes', NULL),
(28, 25, 'laptop', '67324923271f6.jpeg', 15000.00, 'Good to use', '2024-11-11 18:12:51', 'Used', 'Yes', 'Electronics', NULL),
(29, 25, 'Books', '67336777c7a86.jpeg', 100.00, 'Haven&#039;t opened for one day.<br />\r\nUsed for the Competetive exams.', '2024-11-12 14:34:31', 'Used', 'Yes', 'Textbooks', NULL),
(30, 33, 'cycle', '6734878bbc74b.jpeg', 2500.00, 'Good condition', '2024-11-13 11:03:39', 'Used', 'Yes', 'Bikes', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products_images`
--

CREATE TABLE `products_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_seller` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `notifications_enabled` tinyint(1) DEFAULT 1,
  `dark_mode` tinyint(1) DEFAULT 0,
  `language` varchar(20) DEFAULT 'en',
  `timezone` varchar(50) DEFAULT 'UTC'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `mobile`, `password`, `created_at`, `is_seller`, `is_active`, `notifications_enabled`, `dark_mode`, `language`, `timezone`) VALUES
(24, 'Gokul S', '23352092@pondiuni.ac.in', '9600484309', '$2y$10$2nNtQ.OgHs8Bzit9qPXjfuY9HcGa/E6jS.BWp32D5TT7SGNTOxOQO', '2024-10-25 05:23:37', 0, 1, 1, 0, 'en', 'UTC'),
(25, 'Naresh Kireedula', '23352100@gmail.com', '9603833861', '$2y$10$9waZhf0yGIp1AZ8NQJajoO/Y3uByEfyyflsctrQ7m7.B4OdxNByHO', '2024-10-25 06:17:57', 0, 1, 1, 1, 'en', 'UTC'),
(26, 'Madhukar', '23352093@pondiuni.ac.in', '9505410760', '$2y$10$F/C65Xfz8OcxAsFnfSFSKOBuKt2A98UhZE0m9u0e4sQ2oTq3bzJgm', '2024-10-25 06:22:48', 0, 1, 1, 0, 'en', 'UTC'),
(27, 'Raghav Raju', '23352052@pondiuni.ac.in', '8918458244', '$2y$10$aq/2KpQJqCUdNymkqNakTehlHu8GeVlopVIr2zusZM8eUJ2Rf7D4i', '2024-10-25 06:31:22', 0, 1, 1, 0, 'en', 'UTC'),
(29, 'Rajkumar', '23352047@pondiuni.ac.in', '6969696969', '$2y$10$vmvf.6PZzyxPnueNY9DNYeNRoNbil7io0mVpqXEwLkMIMCRg6CHma', '2024-11-11 15:35:37', 0, 1, 1, 0, 'en', 'UTC'),
(33, 'charan', '23352094@pondiuni.ac.in', '9392493153', '$2y$10$ulYyUj6U.nd6jtDyGcxuuOksOp/gcYzmyNo1NAxQdbGfUaKbGN94G', '2024-11-12 12:59:09', 0, 1, 1, 1, 'en', 'UTC'),
(34, '23352094', '23352115@pondiuni.ac.in', '9392493153', '$2y$10$2rgSGK1oewX1y.5EulpyvuzpMAuuQHoypH0FxiQ7WRa2uXAfBMUZW', '2024-11-12 13:09:29', 0, 1, 1, 0, 'en', 'UTC');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `issues_table`
--
ALTER TABLE `issues_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `products_images`
--
ALTER TABLE `products_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `issues_table`
--
ALTER TABLE `issues_table`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `products_images`
--
ALTER TABLE `products_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products_images`
--
ALTER TABLE `products_images`
  ADD CONSTRAINT `products_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
