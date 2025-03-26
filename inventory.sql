-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 02:14 AM
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
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventorymasterfile`
--

CREATE TABLE `inventorymasterfile` (
  `Product Code` char(5) NOT NULL,
  `Item Description` char(35) NOT NULL,
  `Packaging` char(3) NOT NULL,
  `Cost` decimal(12,0) NOT NULL,
  `Selling price` decimal(12,0) NOT NULL,
  `Balance` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventorymasterfile`
--

INSERT INTO `inventorymasterfile` (`Product Code`, `Item Description`, `Packaging`, `Cost`, `Selling price`, `Balance`) VALUES
('00001', 'Coca Cola', 'Box', '120', '130', '24'),
('00002', 'robux', 'Box', '10', '11', '10'),
('00003', 'pepsi', 'Box', '120', '150', '1');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `username` char(50) NOT NULL,
  `password` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`username`, `password`) VALUES
('a', '$2y$10$LpK5DGTZeARn//bqAp9igOHz3M6BrS597UaV20WRyNuyf1lcessa.'),
('b', '$2y$10$KxiHEJPjoDMfMMhBgacko.SyayIBV7Gcbq5G5MKpJfNAKqNyhHpNK'),
('c', '$2y$10$tL5jucNLQF7LebNhgRxGOehT6vS8SGRqiAPcHIaGrX.oCxZtz/bRG'),
('n', '$2y$10$9ObS/wHEGRE5Ph6DgJJ0Q.TeGzOesT/naLCbVZVrloJuneQWHNHMi');

-- --------------------------------------------------------

--
-- Table structure for table `po_trans`
--

CREATE TABLE `po_trans` (
  `PO_No` char(6) NOT NULL,
  `Product_Code` char(5) NOT NULL,
  `Item_Description` char(35) NOT NULL,
  `Packaging` char(3) NOT NULL,
  `Cost` decimal(12,0) NOT NULL,
  `Quantity` decimal(12,0) NOT NULL,
  `Amount` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `po_trans`
--

INSERT INTO `po_trans` (`PO_No`, `Product_Code`, `Item_Description`, `Packaging`, `Cost`, `Quantity`, `Amount`) VALUES
('00001', '00001', 'Coca Cola', 'Box', '120', '9', '1080'),
('00001', '00002', 'robux', 'Box', '10', '4', '40'),
('00001', '00003', 'pepsi', 'Box', '120', '2', '240');

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorder`
--

CREATE TABLE `purchaseorder` (
  `PO_No` char(5) NOT NULL,
  `Suppliers_name` char(60) NOT NULL,
  `Date` date NOT NULL,
  `Total_amount` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchaseorder`
--

INSERT INTO `purchaseorder` (`PO_No`, `Suppliers_name`, `Date`, `Total_amount`) VALUES
('00001', 'alec', '2025-03-24', '1360');

-- --------------------------------------------------------

--
-- Table structure for table `salesorder`
--

CREATE TABLE `salesorder` (
  `SO_No` char(6) NOT NULL,
  `Customers_name` char(50) NOT NULL,
  `Date` date NOT NULL,
  `Total_amount` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `salesorder`
--

INSERT INTO `salesorder` (`SO_No`, `Customers_name`, `Date`, `Total_amount`) VALUES
('', '', '2025-03-24', '0'),
('', '', '2025-03-24', '0'),
('', '', '2025-03-24', '0'),
('00001', 'angel mae', '2025-03-24', '540');

-- --------------------------------------------------------

--
-- Table structure for table `so transactions`
--

CREATE TABLE `so transactions` (
  `SO_No` char(6) NOT NULL,
  `Product_Code` char(5) NOT NULL,
  `Item_Description` char(35) NOT NULL,
  `Packaging` char(3) NOT NULL,
  `Price` decimal(12,0) NOT NULL,
  `Quantity` decimal(12,0) NOT NULL,
  `Amount` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `so transactions`
--

INSERT INTO `so transactions` (`SO_No`, `Product_Code`, `Item_Description`, `Packaging`, `Price`, `Quantity`, `Amount`) VALUES
('00001', '00001', 'Coca Cola', 'Box', '130', '3', '390'),
('00001', '00003', 'pepsi', 'Box', '150', '1', '150');

-- --------------------------------------------------------

--
-- Table structure for table `stock_card`
--

CREATE TABLE `stock_card` (
  `Product_Code` char(5) NOT NULL,
  `Item_Description` char(35) NOT NULL,
  `Date` date NOT NULL,
  `Reference_No` char(10) NOT NULL,
  `Cost&Price` decimal(12,0) NOT NULL,
  `Quantity_In` decimal(12,0) NOT NULL,
  `Quantity_Out` decimal(12,0) NOT NULL,
  `Quantity_Balance` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_card`
--

INSERT INTO `stock_card` (`Product_Code`, `Item_Description`, `Date`, `Reference_No`, `Cost&Price`, `Quantity_In`, `Quantity_Out`, `Quantity_Balance`) VALUES
('00001', 'Coca Cola', '2025-03-24', 'PO#00001', '120', '10', '0', '24'),
('00001', 'Coca Cola', '2025-03-24', 'SO#00001', '130', '0', '2', '24'),
('00001', 'Coca Cola', '2025-03-24', 'PO#00001', '120', '10', '0', '24'),
('00002', 'robux', '2025-03-24', 'PO#00001', '10', '5', '0', '10'),
('00001', 'Coca Cola', '2025-03-24', 'SO#00001', '130', '0', '2', '24'),
('00001', 'Coca Cola', '2025-03-24', 'PO#00001', '120', '10', '0', '24'),
('00002', 'robux', '2025-03-24', 'PO#00001', '10', '5', '0', '10'),
('00003', 'pepsi', '2025-03-24', 'PO#00001', '120', '2', '0', '1'),
('00001', 'Coca Cola', '2025-03-24', 'SO#00001', '130', '0', '2', '24'),
('00003', 'pepsi', '2025-03-24', 'SO#00001', '150', '0', '1', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventorymasterfile`
--
ALTER TABLE `inventorymasterfile`
  ADD PRIMARY KEY (`Product Code`),
  ADD KEY `Item Des` (`Item Description`);

--
-- Indexes for table `po_trans`
--
ALTER TABLE `po_trans`
  ADD KEY `PO` (`PO_No`),
  ADD KEY `Product` (`Product_Code`),
  ADD KEY `Item` (`Item_Description`);

--
-- Indexes for table `salesorder`
--
ALTER TABLE `salesorder`
  ADD KEY `SO No` (`SO_No`);

--
-- Indexes for table `so transactions`
--
ALTER TABLE `so transactions`
  ADD KEY `SO No` (`SO_No`),
  ADD KEY `Item Description` (`Item_Description`),
  ADD KEY `Product Code` (`Product_Code`);

--
-- Indexes for table `stock_card`
--
ALTER TABLE `stock_card`
  ADD KEY `Product Code` (`Product_Code`,`Item_Description`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
