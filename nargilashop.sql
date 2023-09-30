-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2023 at 03:08 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nargilashop`
--

-- --------------------------------------------------------

--
-- Table structure for table `proizvod`
--

CREATE TABLE `proizvod` (
  `id` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `brand` varchar(30) NOT NULL,
  `category` varchar(20) NOT NULL,
  `price` varchar(5) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `proizvod`
--

INSERT INTO `proizvod` (`id`, `name`, `brand`, `category`, `price`, `description`, `image`) VALUES
(1, 'Amy Deluxe', 'Amy Deluxe', 'Nargila', '14000', 'Kratak opis o ovoj nargili. Amy deluxe jedna od najboljih nargila na tržištu, visok kvalitet!', '1679735772_amydeluxe.png'),
(2, 'Mambo Passion', 'Jibiar', 'Ukus', '1200', 'Mambo passion jedan od najboljih ukusa na trzistu!', '1679837585_mambo.jpg'),
(3, 'Zarevi', 'Cocco', 'Ostalo', '800', 'Najbolji zarevi na trzistu, ne gube oblik kocke!', '1679837599_coal.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `proizvod`
--
ALTER TABLE `proizvod`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `proizvod`
--
ALTER TABLE `proizvod`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
