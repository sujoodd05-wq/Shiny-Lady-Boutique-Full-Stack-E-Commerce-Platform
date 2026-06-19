-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 17 مايو 2026 الساعة 13:22
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boutique_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `admin_name` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `activity_log`
--

INSERT INTO `activity_log` (`id`, `admin_id`, `admin_name`, `action`, `details`, `created_at`) VALUES
(1, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-14 13:54:39'),
(2, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-14 14:37:44'),
(3, 2, 'Sujood Admin', 'Management', 'Added new staff: yasmeenstaff', '2026-05-14 14:39:42'),
(4, 4, 'yasmeen', 'Login', 'Admin successfully logged into the dashboard', '2026-05-14 14:42:58'),
(5, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-14 15:57:10'),
(6, 2, 'Sujood Admin', 'Settings', 'Updated global store information', '2026-05-14 15:57:55'),
(7, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-15 10:21:51'),
(8, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-15 11:52:44'),
(9, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-16 21:21:19'),
(10, 2, 'Sujood Admin', 'Inventory', 'Updated product: Double Necklace (New Price: 55 NIS, New Stock: 18)', '2026-05-16 21:25:41'),
(11, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-16 23:42:01'),
(12, 2, 'Sujood Admin', 'Inventory', 'Added: Heart Necklace | Price: 30 NIS | Stock: 7 pieces', '2026-05-17 08:36:18'),
(13, 2, 'Sujood Admin', 'Inventory', 'Added: Pink Flower | Price: 35 NIS | Stock: 5 pieces', '2026-05-17 08:38:25'),
(14, 2, 'Sujood Admin', 'Inventory', 'Added: White Pearl | Price: 45 NIS | Stock: 16 pieces', '2026-05-17 08:39:48'),
(15, 2, 'Sujood Admin', 'Inventory', 'Added: Deer | Price: 20 NIS | Stock: 30 pieces', '2026-05-17 08:41:25'),
(16, 2, 'Sujood Admin', 'Inventory', 'Added: Sun | Price: 45 NIS | Stock: 20 pieces', '2026-05-17 08:42:39'),
(17, 2, 'Sujood Admin', 'Inventory', 'Added: Love | Price: 90 NIS | Stock: 20 pieces', '2026-05-17 08:43:56'),
(18, 2, 'Sujood Admin', 'Inventory', 'Added: luxury necklace stack | Price: 220 NIS | Stock: 10 pieces', '2026-05-17 08:46:29'),
(19, 2, 'Sujood Admin', 'Inventory', 'Added: Silver Stack | Price: 120 NIS | Stock: 15 pieces', '2026-05-17 08:48:01'),
(20, 2, 'Sujood Admin', 'Inventory', 'Added: Cartier set | Price: 100 NIS | Stock: 13 pieces', '2026-05-17 08:51:12'),
(21, 2, 'Sujood Admin', 'Inventory', 'Added: Double Rose | Price: 60 NIS | Stock: 13 pieces', '2026-05-17 08:53:19'),
(22, 2, 'Sujood Admin', 'Inventory', 'Added: Heart earing | Price: 35 NIS | Stock: 12 pieces', '2026-05-17 09:05:06'),
(23, 2, 'Sujood Admin', 'Inventory', 'Added: Cartier earing | Price: 55 NIS | Stock: 6 pieces', '2026-05-17 09:07:18'),
(24, 2, 'Sujood Admin', 'Inventory', 'Added: Gold Stars | Price: 20 NIS | Stock: 10 pieces', '2026-05-17 09:09:31'),
(25, 2, 'Sujood Admin', 'Inventory', 'Added: White Flower | Price: 15 NIS | Stock: 13 pieces', '2026-05-17 09:11:16'),
(26, 2, 'Sujood Admin', 'Inventory', 'Added: Finger bracelet | Price: 25 NIS | Stock: 15 pieces', '2026-05-17 09:15:00'),
(27, 2, 'Sujood Admin', 'Inventory', 'Added: Set of gold rings | Price: 55 NIS | Stock: 10 pieces', '2026-05-17 09:19:02'),
(28, 2, 'Sujood Admin', 'Inventory', 'Added: Infinity ring | Price: 75 NIS | Stock: 6 pieces', '2026-05-17 09:20:14'),
(29, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-17 09:54:10'),
(30, 2, 'Sujood Admin', 'Login', 'Admin successfully logged into the dashboard', '2026-05-17 11:04:44'),
(31, 2, 'Sujood Admin', 'Inventory', 'Deleted product: gold (ID: 2)', '2026-05-17 11:05:10');

-- --------------------------------------------------------

--
-- بنية الجدول `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default_admin.png',
  `role` enum('admin','staff') DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admins`
--

INSERT INTO `admins` (`id`, `full_name`, `username`, `email`, `password`, `profile_pic`, `role`) VALUES
(2, 'Sujood Admin', 'admin', 'admin@boutique.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default_admin.png', 'admin'),
(4, 'yasmeen', 'yasmeenstaff', 'sojeeishere@gmail.com', '$2y$10$3t.EKKGGqA19xsTTcjpn7eKxTXf08t9JPnBHm6V.gkEWjcN19z7ra', 'default_admin.png', 'staff');

-- --------------------------------------------------------

--
-- بنية الجدول `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'necklaces'),
(2, 'rings'),
(3, 'bracelets'),
(4, 'earrings');

-- --------------------------------------------------------

--
-- بنية الجدول `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_items` text NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `location`, `city`, `address`, `total_amount`, `order_items`, `status`, `created_at`) VALUES
(1, 'Sujood Daghlas', NULL, '0569681262', 'West Bank', 'nablus', 'uni street', 46.00, '[{\"name\":\"nec\",\"price\":13,\"image\":\"../imgs/꒰ 💌 ꒱.jpg\",\"qty\":2}]', 'Pending', '2026-05-13 10:00:44'),
(2, 'sujood ziad', NULL, '0569681232', 'West Bank', 'Nablus', 'Sufian', 595.00, '[{\"name\":\"Double Necklace\",\"price\":55,\"image\":\"../images/nec1.webp\",\"qty\":2},{\"name\":\"Infinity ring\",\"price\":75,\"image\":\"../images/ring2.jpg\",\"qty\":1},{\"name\":\"White Flower\",\"price\":15,\"image\":\"../images/ear4.webp\",\"qty\":1},{\"name\":\"Cartier earing\",\"price\":55,\"image\":\"../images/ear2.webp\",\"qty\":1},{\"name\":\"Cartier set\",\"price\":100,\"image\":\"../images/brac1.webp\",\"qty\":1},{\"name\":\"luxury necklace stack\",\"price\":220,\"image\":\"../images/nec8.jpg\",\"qty\":1}]', 'Pending', '2026-05-17 09:51:26');

-- --------------------------------------------------------

--
-- بنية الجدول `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 10,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `category_id`, `image`, `description`, `created_at`) VALUES
(1, 'Double Necklace', 55.00, 18, 1, 'nec1.webp', 'gold with crystals', '2026-05-10 20:26:58'),
(3, 'Heart Necklace', 30.00, 7, 1, 'nec2.jpg', 'Heart shaped short gold', '2026-05-17 08:36:18'),
(4, 'Pink Flower', 35.00, 5, 1, 'nec3.webp', 'Gold rose short', '2026-05-17 08:38:25'),
(5, 'White Pearl', 45.00, 16, 1, 'nec4.webp', 'gold short ', '2026-05-17 08:39:48'),
(6, 'Deer', 20.00, 30, 1, 'nec5.webp', 'simple gold short', '2026-05-17 08:41:25'),
(7, 'Sun', 45.00, 20, 1, 'nec6.jpg', 'cute gold sun necklace', '2026-05-17 08:42:39'),
(8, 'Love', 90.00, 20, 1, 'nec7.webp', 'Swan silver love necklace', '2026-05-17 08:43:56'),
(9, 'luxury necklace stack', 220.00, 10, 1, 'nec8.jpg', 'gold and silver very special', '2026-05-17 08:46:29'),
(10, 'Silver Stack', 120.00, 15, 1, 'nec9.webp', 'short silver stack', '2026-05-17 08:48:01'),
(11, 'Cartier set', 100.00, 13, 3, 'brac1.webp', 'High quality gold set', '2026-05-17 08:51:11'),
(12, 'Double Rose', 60.00, 13, 3, 'brac2.jpg', 'gold', '2026-05-17 08:53:18'),
(13, 'Heart earing', 35.00, 12, 4, 'ear1.webp', 'crystal heart gold heart shaped', '2026-05-17 09:05:06'),
(14, 'Cartier earing', 55.00, 6, 4, 'ear2.webp', 'Special gold', '2026-05-17 09:07:18'),
(15, 'Gold Stars', 20.00, 10, 4, 'rae3.jpg', 'two gold earrings', '2026-05-17 09:09:31'),
(16, 'White Flower', 15.00, 13, 4, 'ear4.webp', 'simple gold', '2026-05-17 09:11:16'),
(17, 'Finger bracelet', 25.00, 15, 3, 'brac3.webp', 'gold simple', '2026-05-17 09:15:00'),
(18, 'Set of gold rings', 55.00, 10, 2, 'ring1.webp', 'simple unique', '2026-05-17 09:19:02'),
(19, 'Infinity ring', 75.00, 6, 2, 'ring2.jpg', 'silver & gold love ring', '2026-05-17 09:20:14');

-- --------------------------------------------------------

--
-- بنية الجدول `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) DEFAULT NULL,
  `shop_email` varchar(255) DEFAULT NULL,
  `shop_phone` varchar(100) DEFAULT NULL,
  `shop_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'Sujood Daghlas', 'sujoodd05@gmail.com', NULL, '$2y$10$7NA2FJYmmomAoHuIB5592.YKpXawpaRlCDef3sdE/JHv2S1g.XrcO', '2026-05-13 09:59:31'),
(2, 'sujood ziad', 's12323525@stu.najah.edu', NULL, '$2y$10$.0t5u59I.KI0Me1.2b687uQTgHAYaHLmTcZ1QbCa4Stor1hZKADr.', '2026-05-17 09:50:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
