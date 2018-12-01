-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 08, 2018 at 06:33 AM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `db_arabic`
--

-- --------------------------------------------------------

--
-- Table structure for table `arabic`
--

CREATE TABLE `arabic` (
  `id` int(11) NOT NULL,
  `idIndo` int(11) NOT NULL,
  `arabic` text NOT NULL,
  `ano1` text NOT NULL,
  `ano2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `arabic`
--

INSERT INTO `arabic` (`id`, `idIndo`, `arabic`, `ano1`, `ano2`) VALUES
(5, 5, 'أنا', '', ''),
(6, 6, 'مدرسة', '', ''),
(7, 7, 'منزل', '', ''),
(8, 8, 'الإنترنت', '', ''),
(9, 9, 'كتاب', '', ''),
(10, 10, 'طاولة', '', ''),
(24, 24, 'قميص', '', ''),
(25, 25, 'دجاج', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `indonesia`
--

CREATE TABLE `indonesia` (
  `id` int(11) NOT NULL,
  `indo` text NOT NULL,
  `ano1` text NOT NULL,
  `ano2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `indonesia`
--

INSERT INTO `indonesia` (`id`, `indo`, `ano1`, `ano2`) VALUES
(5, 'saya', '', ''),
(6, 'sekolah', '', ''),
(7, 'rumah', '', ''),
(8, 'internet', '', ''),
(9, 'buku', '', ''),
(10, 'meja', '', ''),
(24, 'kemeja', '', ''),
(25, 'Ayam', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `temp_file`
--

CREATE TABLE `temp_file` (
  `id` int(11) NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arabic`
--
ALTER TABLE `arabic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `indonesia`
--
ALTER TABLE `indonesia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_file`
--
ALTER TABLE `temp_file`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arabic`
--
ALTER TABLE `arabic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `indonesia`
--
ALTER TABLE `indonesia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `temp_file`
--
ALTER TABLE `temp_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
