-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2021 at 07:25 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `product_order_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(3, 'Category 01', '2021-04-27 06:07:25'),
(4, 'Category 02', '2021-04-27 06:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `ordered_products`
--

CREATE TABLE `ordered_products` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `unit_price` double DEFAULT '0',
  `quantity` int(11) DEFAULT '0',
  `final_price` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ordered_products`
--

INSERT INTO `ordered_products` (`id`, `order_id`, `product_id`, `unit_price`, `quantity`, `final_price`, `created_at`, `updated_at`) VALUES
(3, 33, 6, 10, 5, 50, '2021-04-30 02:16:45', NULL),
(4, 33, 7, 10, 5, 50, '2021-04-30 02:16:45', NULL),
(5, 35, 8, 20, 5, 100, '2021-04-30 02:35:00', NULL),
(6, 35, 9, 30, 5, 150, '2021-04-30 02:35:00', NULL),
(7, 36, 4, 12.36, 2, 24.72, '2021-04-30 03:06:55', NULL),
(8, 37, 7, 10, 3, 30, '2021-04-30 03:45:52', NULL),
(9, 37, 12, 250.3, 2, 500.6, '2021-04-30 03:45:52', NULL),
(10, 37, 16, 100, 5, 500, '2021-04-30 03:45:52', NULL),
(11, 38, 12, 250.3, 8, 2002.4, '2021-04-30 03:48:05', NULL),
(12, 38, 16, 100, 6, 600, '2021-04-30 03:48:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `total_price` double NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `status`, `total_price`, `created_at`, `updated_at`) VALUES
(33, 1, 2, 100, '2021-04-30 02:16:45', '2021-04-30 06:01:57'),
(35, 1, 1, 250, '2021-04-30 02:35:00', NULL),
(36, 1, 2, 24.72, '2021-04-30 03:06:55', '2021-04-30 06:02:29'),
(37, 2, 1, 1030.6, '2021-04-30 03:45:52', NULL),
(38, 2, 3, 2602.4, '2021-04-30 03:48:05', '2021-04-30 06:02:06');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `sku` varchar(150) DEFAULT NULL,
  `description` text,
  `category` int(11) DEFAULT NULL,
  `price` double DEFAULT '0',
  `image` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `description`, `category`, `price`, `image`, `created_at`) VALUES
(2, 'admin@example.com', '55', '', 3, 12.36, NULL, '2021-04-27 08:44:11'),
(3, 'admin@example.com', '559', '', 3, 12.36, NULL, '2021-04-27 08:47:11'),
(4, 'admin@example.com', '553', '', 3, 12.36, NULL, '2021-04-27 08:48:09'),
(5, 'admin@example.com', '853', '', 3, 12.36, NULL, '2021-04-27 08:49:48'),
(6, 'admin@example.com', '953', '', 3, 10, NULL, '2021-04-27 08:50:04'),
(7, 'admin@example.com', '96', '', 3, 10, NULL, '2021-04-27 09:08:49'),
(8, 'Product 01', '6087d4c2ef0fb', '', 4, 20, NULL, '2021-04-27 09:09:49'),
(9, 'Product 02', '608813a725a81', '', 4, 30, NULL, '2021-04-27 13:38:11'),
(10, 'Product 04', '6088145ac0c14', '', 3, 100, NULL, '2021-04-27 13:41:07'),
(11, 'Product 05', '6088153f3f4cb', '', 3, 200.5, NULL, '2021-04-27 13:45:03'),
(12, 'Product 06', '608816179953a', '', 4, 250.3, '608816179953a.PNG', '2021-04-27 13:48:20'),
(14, 'Product 08', '608a0e3fce3a6', '', 4, 123, '', '2021-04-29 01:39:34'),
(15, 'fggdgdfg', '987', '', 3, 63, '', '2021-04-29 01:45:59'),
(16, 'hello', '85', 'Some des', 3, 10, '85.jpg', '2021-04-29 01:46:58'),
(17, 'fggdgdfg', '888', '', 3, 63, '888.jpg', '2021-04-30 10:36:39'),
(18, 'fggdgdfg', '525', 'Some des', 3, 63, '525.jpg', '2021-04-30 10:40:38');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Processing'),
(2, 'Shipped'),
(3, 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL DEFAULT '1',
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `name`, `email`, `password`, `created_at`) VALUES
(1, 2, 'Admin', 'admin@example.com', '$2y$10$TF8CEggrGvH47QPiRoAWeez1.B0k6zj7S4oc24I2ihWRawXZaOvMi', '2021-04-24 01:00:41'),
(2, 1, 'Customer 01', 'customer01@example.com', '$2y$10$TF8CEggrGvH47QPiRoAWeez1.B0k6zj7S4oc24I2ihWRawXZaOvMi', '2021-04-30 03:22:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `type` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `type`) VALUES
(1, 'Customer'),
(2, 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ordered_products`
--
ALTER TABLE `ordered_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_ordered_products_orders` (`order_id`),
  ADD KEY `FK_ordered_products_products` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_orders_status` (`status`),
  ADD KEY `FK_orders_users` (`customer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `FK_products_categories` (`category`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FK_users_user_type` (`user_type`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ordered_products`
--
ALTER TABLE `ordered_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ordered_products`
--
ALTER TABLE `ordered_products`
  ADD CONSTRAINT `FK_ordered_products_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_ordered_products_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_orders_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `FK_orders_users` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products_categories` FOREIGN KEY (`category`) REFERENCES `categories` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_user_type` FOREIGN KEY (`user_type`) REFERENCES `user_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
