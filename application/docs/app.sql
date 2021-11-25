-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2021 at 03:42 PM
-- Server version: 5.7.26
-- PHP Version: 7.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `app_notes`
--
CREATE DATABASE IF NOT EXISTS `app_notes` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `app_notes`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notes`
--

CREATE TABLE `tbl_notes` (
  `note_id` int(11) NOT NULL,
  `note_title` varchar(250) DEFAULT NULL,
  `note_description` text,
  `is_active` tinyint(4) DEFAULT '1',
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_notes`
--

INSERT INTO `tbl_notes` (`note_id`, `note_title`, `note_description`, `is_active`, `created_on`) VALUES
(1, 'TEST001', 'Google and Facebook buttons are available featuring each company\'s respective brand color. They are used on the user login and registration pages.', 1, '2021-11-25 19:54:46'),
(2, 'TEST002', 'Google and Facebook buttons are available featuring each company\'s respective brand color. They are used on the user login and registration pages.', 1, '2021-11-25 19:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_note_tags`
--

CREATE TABLE `tbl_note_tags` (
  `note_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_note_tags`
--

INSERT INTO `tbl_note_tags` (`note_id`, `tag_id`) VALUES
(1, 1),
(1, 3),
(2, 1),
(2, 3),
(2, 4),
(4, 1),
(4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tags`
--

CREATE TABLE `tbl_tags` (
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(100) NOT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_tags`
--

INSERT INTO `tbl_tags` (`tag_id`, `tag_name`, `is_active`, `created_on`) VALUES
(1, 'TEST001', 1, '2021-11-25 16:00:10'),
(3, 'TEST002', 1, '2021-11-25 16:04:40'),
(4, 'TEST003', 1, '2021-11-25 16:11:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `tbl_note_tags`
--
ALTER TABLE `tbl_note_tags`
  ADD PRIMARY KEY (`note_id`,`tag_id`);

--
-- Indexes for table `tbl_tags`
--
ALTER TABLE `tbl_tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `tag_name` (`tag_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_tags`
--
ALTER TABLE `tbl_tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
