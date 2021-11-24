-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 22, 2021 at 01:18 AM
-- Server version: 5.7.26
-- PHP Version: 7.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `app_poll`
--
CREATE DATABASE IF NOT EXISTS `app_poll` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `app_poll`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_polls`
--

CREATE TABLE IF NOT EXISTS `tbl_polls` (
  `poll_id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_title` varchar(1000) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`poll_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_polls`
--

INSERT INTO `tbl_polls` (`poll_id`, `poll_title`, `is_active`, `created_on`) VALUES
(2, 'TEST002', 1, '2021-11-22 04:20:26'),
(3, 'TEST003a', 1, '2021-11-22 04:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_poll_votes`
--

CREATE TABLE IF NOT EXISTS `tbl_poll_votes` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) DEFAULT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `vote` int(11) DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_poll_votes`
--

INSERT INTO `tbl_poll_votes` (`vote_id`, `ip`, `poll_id`, `vote`, `created_on`) VALUES
(1, '127.0.0.1', 2, 1, '2021-11-22 05:33:32'),
(2, '127.0.0.1', 2, 1, '2021-11-22 05:33:35'),
(3, '127.0.0.1', 2, 0, '2021-11-22 05:47:39'),
(4, '127.0.0.1', 2, 0, '2021-11-22 05:47:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` enum('USER','SUPERADMIN') NOT NULL DEFAULT 'USER',
  `email` varchar(200) NOT NULL,
  `pwd` varchar(200) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `role`, `email`, `pwd`, `first_name`, `last_name`, `is_active`, `created_on`) VALUES
(1, 'SUPERADMIN', 'admin@poll.com', 'Admin@123', 'Admin', '', 1, '2021-11-22 02:46:27'),
(2, 'USER', 'sastrylal@gmail.com', 'Admin@123', 'L B Sastry', 'CH', 1, '2021-11-22 02:47:07');
