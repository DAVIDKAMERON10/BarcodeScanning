-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2024 at 04:48 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `barca_scanner`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `barcode`, `name`, `price`) VALUES
(1, '123456789012', 'Cadburry Dairy Milk', '152.99'),
(2, '987654321098', 'Refillable Butane Lighter', '15.49'),
(3, '555555555555', 'Tortillos', '7.99'),
(4, '123456789123', 'David Delicious Meat', '20.87'),
(5, '231296842369', 'Universal serial bus version: patient 0', '56.25'),
(6, '641296842369', 'FACEBOOK version: metaverse', '46.25'),
(7, '1234ABcdEF5$9', 'Infinix note 30 5G', '276.00'),
(13, '50382', 'kangaroo8', '152.99');

-- --------------------------------------------------------

--
-- Table structure for table `scan_history`
--

CREATE TABLE `scan_history` (
  `id` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `scan_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `scan_history`
--

INSERT INTO `scan_history` (`id`, `barcode`, `name`, `price`, `scan_time`) VALUES
(9, '555555555555', 'Tortillos', '7.99', '2024-10-03 13:21:32'),
(10, '555555555555', 'Tortillos', '7.99', '2024-10-05 12:25:49'),
(11, '555555555555', 'Tortillos', '7.99', '2024-10-05 12:30:13'),
(12, '555555555555', 'Tortillos', '7.99', '2024-10-05 12:54:04'),
(13, '641296842369', 'FACEBOOK version: metaverse', '46.25', '2024-10-05 13:08:17'),
(14, '2391273123', 'lando', '12.00', '2024-10-05 13:14:54'),
(15, '641296842369', 'FACEBOOK version: metaverse', '46.25', '2024-10-05 14:39:15'),
(16, '123456789123', 'David Delicious Meat', '20.87', '2024-10-05 14:39:22'),
(17, '50382', 'kangaroo8', '152.99', '2024-10-05 14:39:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indexes for table `scan_history`
--
ALTER TABLE `scan_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `scan_history`
--
ALTER TABLE `scan_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
