-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2023 at 02:26 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dms_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `U_ID` int(14) NOT NULL,
  `IdentyC` varchar(14) NOT NULL,
  `U_Name` text NOT NULL,
  `U_Contact` varchar(11) NOT NULL,
  `U_Email` varchar(50) NOT NULL,
  `U_Password` text NOT NULL,
  `U_Types` text NOT NULL DEFAULT '\'NULL\'',
  `Confirmation` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`U_ID`, `IdentyC`, `U_Name`, `U_Contact`, `U_Email`, `U_Password`, `U_Types`, `Confirmation`) VALUES
(1, '010110100166', 'user', '0123456789', 'demo123@gmail.com', '$2y$10$gzsa18Uu9YK/10Gv1m62Gup/EQfVxFKKKAOhIelJYhkOfRz4jiyA6', 'Owner', 'Pending'),
(2, '010110100166', 'user', '0198765432', 'sam23@mail.com', '$2y$10$PtRA58NQUK.CyYxlm1v1xuAj131V9CINftxNnZhF2ThGTi0UhAIrC', 'Owner', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`U_ID`),
  ADD UNIQUE KEY `IdentyC` (`IdentyC`,`U_Contact`,`U_Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `U_ID` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
