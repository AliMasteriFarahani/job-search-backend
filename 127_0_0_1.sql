-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 29, 2021 at 07:10 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toplearn`
--
CREATE DATABASE IF NOT EXISTS `toplearn` DEFAULT CHARACTER SET utf8 COLLATE utf8_persian_ci;
USE `toplearn`;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `product_descriptions` text COLLATE utf8_persian_ci NOT NULL,
  `product_image` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `price` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_descriptions`, `product_image`, `price`) VALUES
(1, 'زودپز نوع سه ', 'این یک متن ساختگی است  این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است ', '1.jpg', 250000),
(2, 'ساعت مدل 32323', 'این یک متن ساختگی است  این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است ', '2.jpg', 300000),
(3, 'یخچال ساید بای ساید ', 'این یک متن ساختگی است  این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است این یک متن ساختگی است ', '3.jpg', 4500000);

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

DROP TABLE IF EXISTS `slider`;
CREATE TABLE IF NOT EXISTS `slider` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `descriptions` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `image_name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `title`, `descriptions`, `image_name`) VALUES
(2, 'ساعت دیواری ', 'ساعت دیواری مدل smartwatch 2101A', 'slider-1.jpg'),
(3, 'بخارپز ', 'این یک بخارپز آمریکایی اصل است ', 'slider-2.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
