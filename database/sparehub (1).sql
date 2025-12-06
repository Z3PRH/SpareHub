-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 07:02 AM
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
-- Database: `sparehub`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `tracking_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `buyer_id`, `order_date`, `status`, `total_amount`, `shipping_address`, `tracking_number`) VALUES
(1, 201, '2025-10-14 07:32:01', 'pending', 2500.00, '123 Main St, Mumbai', 'TESTTRACK123'),
(2, 201, '2025-10-12 05:00:45', 'delivered', 5200.00, '123 Main St, Mumbai', 'TESTTRACK124');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `part_id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 1,
  `brand` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `status` enum('new','used','refurbished') NOT NULL,
  `oem_number` varchar(50) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`part_id`, `seller_id`, `name`, `description`, `price`, `stock`, `brand`, `model`, `year`, `category`, `status`, `oem_number`, `images`, `created_at`, `updated_at`) VALUES
(1, 301, 'Airfilter', 'standard air filter', 230.00, 40, 'OEM', 'universal', 2022, 'engine', 'used', '6L3Z-3280-B', '../images/airfilter.png', '2025-11-20 00:24:15', '2025-11-20 01:41:18'),
(2, 301, 'Brakepad', 'standard brake pads', 800.00, 30, 'aftermarket', 'universal', 2022, 'Brake System', 'refurbished', '86511C7000', '../images/brakepad.png', '2025-11-20 00:22:10', '2025-11-20 01:41:53'),
(3, 301, 'Clutchplate', 'Standard Clutchplate', 2000.00, 15, 'OEM', 'Universal', 2022, 'Engine', 'used', '540770100104', '../images/clutchplate.png', '2022-04-04 09:42:55', '2025-11-20 01:42:01'),
(4, 301, 'SparkPlug', 'Standard Spark Plug', 2500.00, 25, 'OEM', 'Universal', 2025, 'Engine', 'used', 'VW 06B 903 023', '../images/sparkplug.png', '2025-08-19 07:38:18', '2025-11-20 01:42:11'),
(5, 301, 'Headlight Assembly', 'Complete headlight assembly with LED daytime running', 1500.50, 25, 'OEM Parts', 'Universal', 2018, 'Lighting', 'new', '805938424', '../images/headlight.png', '2025-08-19 07:38:18', '2025-11-20 01:42:30'),
(6, 301, 'Tail Light', ': Modern Volkswagen headlight design featuring clear glass and internal reflector ', 14000.00, 150, 'AfterMarket', 'Volkwagen', 2023, 'Rear Light', 'used', '703402-B23', 'uploads/taillight.png', '2025-11-20 02:05:37', '2025-11-20 02:10:30'),
(7, 300, 'Hood', 'Grey hood', 1200.00, 2, 'Aftermarket', 'Alto', 2023, 'body', 'used', '5647-6967-c', 'uploads/hoodann.jpg', '2025-11-20 05:52:04', '2025-11-20 06:01:38');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT NULL,
  `payment_method` enum('credit_card','paypal','bank_transfer','cash_on_delivery') DEFAULT NULL,
  `gateway_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_requests`
--

CREATE TABLE `seller_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `credential_filename` varchar(255) NOT NULL,
  `credential_original_name` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','denied') DEFAULT 'pending',
  `denial_reason` text DEFAULT NULL,
  `request_time` datetime DEFAULT current_timestamp(),
  `reviewed_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_requests`
--

INSERT INTO `seller_requests` (`request_id`, `user_id`, `credential_filename`, `credential_original_name`, `status`, `denial_reason`, `request_time`, `reviewed_time`) VALUES
(15, 300, '1763617682_201.jpg', 'Sparkle_mask.jpg', 'approved', NULL, '2025-11-20 11:18:02', '2025-11-20 11:18:22'),
(16, 301, '1763618316_201.png', 'upi.png', 'approved', NULL, '2025-11-20 11:28:36', '2025-11-20 11:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('seller','buyer','admin') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `password`, `role`, `email`, `phone`, `created_at`) VALUES
(101, 'Ryan', 'adminjisan', 'admin', 'buyer123@gmail.com', '9087658453', '2025-10-16'),
(300, 'Ryan', 'buyerryan', 'seller', 'buyer123@gmail.com', '9087658454', '2025-10-16'),
(301, 'swarag', 'buyerswarag', 'seller', 'swaragsnr@gmail.com', '9876543212', '2025-11-20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`part_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `seller_requests`
--
ALTER TABLE `seller_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_requests`
--
ALTER TABLE `seller_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=303;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `seller_requests`
--
ALTER TABLE `seller_requests`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `seller_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
