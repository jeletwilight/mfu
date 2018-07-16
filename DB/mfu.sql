-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2018 at 11:18 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mfu`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `subdistrict` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `district` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` int(6) NOT NULL,
  `telephone` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `current` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `user_id`, `location`, `subdistrict`, `district`, `province`, `zipcode`, `telephone`, `current`) VALUES
(1, 1, '888 Nimman.rd soi 13', 'Suthep', 'Mueng', 'Chiang Mai', 57000, '0801234567', 1),
(4, 7, 'tttt', 'tttt', 'tttt', 'tttt', 12345, '1231231231', 1),
(5, 2, '444', '-', 'Hwuai Khwang', 'BKK', 10000, '0897654321', 0),
(6, 2, '444', '-', 'Mae Rim', 'Chiang Mai', 57010, '0897654321', 1);

-- --------------------------------------------------------

--
-- Table structure for table `creditcard`
--

CREATE TABLE `creditcard` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `holder_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cardnumber` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `exp` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `expyear` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `expmonth` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `pin` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `current` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `creditcard`
--

INSERT INTO `creditcard` (`id`, `user_id`, `holder_name`, `cardnumber`, `exp`, `expyear`, `expmonth`, `pin`, `current`) VALUES
(1, 1, 'Tester Testing', '0102030405060708', '12/13', '2019', '12', '141', 1),
(2, 7, 'holder is tester', '1111111111111111', '01/13', '2018', '04', '123', 1),
(3, 2, 'Admin isHolder', '4685431358587474', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lineitems`
--

CREATE TABLE `lineitems` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(3) NOT NULL,
  `lineprice` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lineitems`
--

INSERT INTO `lineitems` (`id`, `receipt_id`, `product_id`, `quantity`, `lineprice`) VALUES
(16, 11, 2, 1, 300),
(28, 16, 2, 2, 600);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `other` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `type`, `name`, `gender`, `birthdate`, `telephone`, `email`, `other`) VALUES
(1, 'test', 'test', 1, 'Mynameis Tester', 'F', '0000-00-00', '0801010101', 'tester@test.com', 'LINE: Tester'),
(2, 'admin', 'admin', 9, 'mynameis admin', '', '0000-00-00', '0897654321', '', 'Line:Admin'),
(3, 'GUEST', '', 0, '', '', '0000-00-00', '', '', ''),
(4, 'manager', 'manager', 8, '', '', '0000-00-00', '', '', ''),
(7, 'customer', 'customer', 1, 'Somebody AsCustomer', 'M', '2018-06-06', '0812345678', 'somebody@gmail.com', 'Line:SomeOne');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instock` int(11) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `sale` float DEFAULT NULL,
  `currentprice` float NOT NULL,
  `information` text COLLATE utf8_unicode_ci,
  `imgname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `image` longblob,
  `released` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `instock`, `price`, `sale`, `currentprice`, `information`, `imgname`, `image`, `released`) VALUES
(2, 'Perfume', 90, 300, 0, 300, 'test', 'PF1.png', 0x433a5c78616d70705c746d705c706870363134382e746d70, '2018-06-04 06:09:05'),
(32, 'PF2', 2, 20000, 14997, 14997, '', 'PF2.jpg', 0x433a5c78616d70705c746d705c706870443839302e746d70, '2018-06-24 17:00:00'),
(35, 'PF3', 10, 1000, 500, 500, '', 'PF3.jpg', 0x433a5c78616d70705c746d705c706870344338372e746d70, '2018-06-24 17:00:00'),
(36, 'PF4', 7, 7000, 0, 7000, '', 'PF4.jpg', 0x433a5c78616d70705c746d705c706870343038442e746d70, '2018-06-24 17:00:00'),
(37, 'PF5', 4, 4000, 0, 4000, '', 'PF5.jpg', 0x433a5c78616d70705c746d705c706870374530342e746d70, '2018-06-24 17:00:00'),
(38, 'PF6', 50, 20000, 0, 20000, '', 'PF6.jpg', 0x433a5c78616d70705c746d705c706870363238392e746d70, '2018-06-24 17:00:00'),
(39, 'PF7', 50, 50, 0, 50, '', 'PF7.jpg', 0x433a5c78616d70705c746d705c706870423841342e746d70, '2018-06-24 17:00:00'),
(40, 'PF8', 50, 300, 0, 300, '', 'PF8.jpg', 0x433a5c78616d70705c746d705c706870443144352e746d70, '2018-06-24 17:00:00'),
(41, 'PF9', 0, 900, 0, 900, '', 'PF9.jpg', 0x433a5c78616d70705c746d705c7068703834382e746d70, '2018-06-24 17:00:00'),
(42, 'PF10', 50, 100, 0, 100, '', 'PF10.jpg', 0x433a5c78616d70705c746d705c706870354135312e746d70, '2018-06-24 17:00:00'),
(43, 'PF11', 50, 77777, 0, 77777, '', 'PF11.jpg', 0x433a5c78616d70705c746d705c706870413233382e746d70, '2018-06-24 17:00:00'),
(44, 'PF12', 50, 6500, 0, 6500, '', 'PF12.jpg', 0x433a5c78616d70705c746d705c706870453046382e746d70, '2018-06-24 17:00:00'),
(45, 'PF13', 50, 900, 0, 900, '', 'PF13.jpg', 0x433a5c78616d70705c746d705c706870313645442e746d70, '2018-06-24 17:00:00'),
(46, 'PF14', 50, 700, 0, 700, '', 'PF14.jpg', 0x433a5c78616d70705c746d705c706870353632412e746d70, '2018-06-24 17:00:00'),
(47, 'Pf15', 50, 5000, 0, 5000, '', 'PF15.jpg', 0x433a5c78616d70705c746d705c706870383935302e746d70, '2018-06-24 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `subtotal` float NOT NULL,
  `note` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `producted` tinyint(1) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`id`, `user_id`, `address_id`, `payment_id`, `subtotal`, `note`, `producted`, `status`) VALUES
(11, 1, 1, 1, 25300, '', 1, 1),
(14, 7, 4, 2, 50000, '', 1, 0),
(16, 1, 1, 1, 144725, '', 1, 1),
(18, 1, 1, 1, 278025, '', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `useraddresskey` (`user_id`);

--
-- Indexes for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usercreditkey` (`user_id`);

--
-- Indexes for table `lineitems`
--
ALTER TABLE `lineitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productlinekey` (`product_id`),
  ADD KEY `receiptlinekey` (`receipt_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userreceiptkey` (`user_id`),
  ADD KEY `addressreceiptkey` (`address_id`),
  ADD KEY `payreceiptkey` (`payment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `creditcard`
--
ALTER TABLE `creditcard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lineitems`
--
ALTER TABLE `lineitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `useraddresskey` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD CONSTRAINT `usercreditkey` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lineitems`
--
ALTER TABLE `lineitems`
  ADD CONSTRAINT `productlinekey` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receiptlinekey` FOREIGN KEY (`receipt_id`) REFERENCES `receipt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `addressreceiptkey` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `payreceiptkey` FOREIGN KEY (`payment_id`) REFERENCES `creditcard` (`id`),
  ADD CONSTRAINT `userreceiptkey` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
