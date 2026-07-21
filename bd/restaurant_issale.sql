-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2026 at 10:48 PM
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
-- Database: `restaurant_issale`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `supprimer`) VALUES
(1, 'Entrées', 0),
(2, 'Plats Principaux', 0),
(3, 'Pizzas', 0),
(4, 'Grillades', 0),
(5, 'Desserts', 0),
(6, 'Boissons', 0),
(7, 'Cocktails', 0);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `preparation_time` int(11) DEFAULT 15,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `nom`, `description`, `price`, `image`, `is_available`, `preparation_time`, `stock_quantity`, `display_order`, `created_by`, `created_at`, `supprimer`) VALUES
(1, 1, 'Salade César', 'Salade verte, poulet grillé, parmesan, croûtons', 4500.00, 'assets/uploads/menus/bfb1b60a25d0d82adaf10927.jpg', 1, 15, 0, 0, 1, '2026-07-18 05:52:40', 0),
(2, 2, 'Boeuf Bourguignon', 'Boeuf mijoté aux légumes et vin rouge', 8500.00, 'assets/uploads/menus/b9df9c9d09958c35ea196f6d.jpg', 1, 15, 0, 0, 1, '2026-07-18 05:52:40', 0),
(3, 3, 'Pizza Margherita', 'Sauce tomate, mozzarella, basilic', 6000.00, 'assets/uploads/menus/0df807ce80938e99dee1f40b.webp', 1, 15, 0, 0, 1, '2026-07-18 05:52:40', 0),
(4, 4, 'Brochettes de Poulet', 'Brochettes marinées servies avec frites', 5500.00, 'assets/uploads/menus/1468ecc122e4d46427ab4946.jpg', 1, 15, 0, 0, 1, '2026-07-18 05:52:40', 0),
(5, 5, 'Tarte Tatin', 'Tarte aux pommes caramélisées', 3500.00, 'assets/uploads/menus/3eadc31a5a7a18a1d9726b86.jpg', 1, 15, 0, 0, 1, '2026-07-18 05:52:40', 0),
(6, 6, 'Jus de Fruit', 'Jus frais de saison', 2000.00, 'assets/uploads/menus/0ca6c14fd9cebf47e58052aa.webp', 1, 15, 15, 0, 1, '2026-07-18 05:52:40', 0),
(7, 6, 'Afria', 'Jus pour les dames ba CBCA', 2500.00, 'assets/uploads/menus/23a379d053b6fb898cf1e963.jpg', 1, 15, 12, 0, 1, '2026-07-21 15:47:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `table_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('en attente','confirme','en preparation','servis','payer','annuler') DEFAULT 'en attente',
  `payment_status` enum('en attente','payer','partially_paid') DEFAULT 'en attente',
  `order_type` enum('surplace','a emporter','livraison') DEFAULT 'surplace',
  `special_notes` text DEFAULT NULL,
  `order_time` datetime DEFAULT current_timestamp(),
  `confirmed_at` datetime DEFAULT NULL,
  `ready_at` datetime DEFAULT NULL,
  `served_at` datetime DEFAULT NULL,
  `stock_processed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `table_id`, `user_id`, `server_id`, `total_amount`, `status`, `payment_status`, `order_type`, `special_notes`, `order_time`, `confirmed_at`, `ready_at`, `served_at`, `stock_processed`, `created_at`, `supprimer`) VALUES
(1, 'IS-260718-0215', 1, 1, NULL, 13000.00, 'en attente', 'en attente', 'a emporter', 'bien cuit', '2026-07-18 14:50:10', NULL, NULL, NULL, 0, '2026-07-18 14:50:10', 0),
(2, 'IS-260718-FF44', 2, 1, NULL, 25500.00, 'en attente', 'en attente', 'surplace', NULL, '2026-07-18 15:11:08', NULL, NULL, NULL, 0, '2026-07-18 15:11:08', 0),
(3, 'IS-260721-6337', 2, NULL, NULL, 14000.00, 'payer', 'payer', 'surplace', NULL, '2026-07-21 15:42:58', NULL, NULL, NULL, 0, '2026-07-21 15:42:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `unit_price` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `quantity`, `unit_price`, `notes`, `supprimer`) VALUES
(1, 1, 4, 2, 5500.00, NULL, 0),
(2, 1, 6, 1, 2000.00, NULL, 0),
(3, 2, 2, 3, 8500.00, NULL, 0),
(4, 3, 5, 4, 3500.00, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('especes','mobile_money','carte','virement') NOT NULL DEFAULT 'especes',
  `reference` varchar(100) DEFAULT NULL,
  `status` enum('valide','annule') NOT NULL DEFAULT 'valide',
  `payment_date` datetime DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `amount`, `payment_method`, `reference`, `status`, `payment_date`, `created_at`, `supprimer`) VALUES
(1, 3, 14000.00, 'especes', 'PAI-2026-001', 'valide', '2026-07-21 15:45:16', '2026-07-21 15:45:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `is_visible` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `type` enum('entree','sortie','ajustement','perdus') NOT NULL,
  `quantity` int(11) NOT NULL,
  `raison` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `menu_id`, `type`, `quantity`, `raison`, `created_at`, `supprimer`) VALUES
(1, 7, 'entree', 12, 'approvisionnement', '2026-07-21 15:51:18', 0),
(2, 6, 'entree', 15, 'approvisionnement', '2026-07-21 17:30:27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `number` varchar(10) NOT NULL,
  `capacity` int(11) DEFAULT 4,
  `qr_code` varchar(255) DEFAULT NULL,
  `qr_code_token` varchar(64) DEFAULT NULL,
  `status` enum('available','occupied','reserved','cleaning') DEFAULT 'available',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `number`, `capacity`, `qr_code`, `qr_code_token`, `status`, `is_active`, `created_at`, `supprimer`) VALUES
(1, 'T-01', 4, NULL, '3cd2e9f482af11f1b10154e1adee6204', 'available', 1, '2026-07-18 05:52:40', 0),
(2, 'T-02', 4, NULL, '3cd3034f82af11f1b10154e1adee6204', 'available', 1, '2026-07-18 05:52:40', 0),
(3, 'T-03', 6, NULL, '3cd304e582af11f1b10154e1adee6204', 'available', 1, '2026-07-18 05:52:40', 0),
(4, 'T-04', 2, NULL, '3cd3059682af11f1b10154e1adee6204', 'available', 1, '2026-07-18 05:52:40', 0),
(5, 'T-05', 8, NULL, '3cd3062782af11f1b10154e1adee6204', 'available', 1, '2026-07-18 05:52:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `postnom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','gestionnaire','serveur','cuisinier','client') DEFAULT 'client',
  `created_at` datetime DEFAULT current_timestamp(),
  `supprimer` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `postnom`, `email`, `password`, `phone`, `role`, `created_at`, `supprimer`) VALUES
(1, 'Glad', 'Kombi', 'admin@restaurant-issale.com', '$2y$10$xe3qSDF1fmVFWkljrk/ppefcsMO28QyDn7kNApYOnF5lRTP0yYMOm', NULL, 'admin', '2026-07-18 05:52:40', 0),
(2, 'Mbuyi', 'Kasonga', 'admin.test@issale.com', '$2y$10$xe3qSDF1fmVFWkljrk/ppefcsMO28QyDn7kNApYOnF5lRTP0yYMOm', '+243810000001', 'admin', '2026-07-21 17:26:09', 0),
(3, 'Mukendi', 'Ilunga', 'gestionnaire@issale.com', '$2y$10$xe3qSDF1fmVFWkljrk/ppefcsMO28QyDn7kNApYOnF5lRTP0yYMOm', '+243810000002', 'gestionnaire', '2026-07-21 17:26:09', 0),
(4, 'Kabila', 'Ngalula', 'serveur1@issale.com', '$2y$10$xe3qSDF1fmVFWkljrk/ppefcsMO28QyDn7kNApYOnF5lRTP0yYMOm', '+243810000003', 'serveur', '2026-07-21 17:26:09', 0),
(5, 'Tshimanga', 'Kalonji', 'chef.cuisine@issale.com', '$2y$10$xe3qSDF1fmVFWkljrk/ppefcsMO28QyDn7kNApYOnF5lRTP0yYMOm', '+243810000004', 'cuisinier', '2026-07-21 17:26:09', 0),
(6, 'Nzuzi', 'Mavungu', 'client.test@gmail.com', '$2y$10$xe3qSDF1fmVFWkljrk/ppefcsMO28QyDn7kNApYOnF5lRTP0yYMOm', '+243810000005', 'client', '2026-07-21 17:26:09', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD UNIQUE KEY `qr_code` (`qr_code`),
  ADD UNIQUE KEY `qr_code_token` (`qr_code_token`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
