-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 07, 2013 at 01:01 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `taradorj_greenstreet`
--

--
-- Dumping data for table `task_type_frequencies`
--

INSERT INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('no', 'Not Periodic', 1);
INSERT INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('daily', 'Periodic Daily', 2);
INSERT INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('weekly', 'Periodic Weekly', 3);
INSERT INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('monthly', 'Periodic Monthly', 4);
INSERT INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('quarterly', 'Periodic Quarterly', 5);
INSERT INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('annual', 'Periodic Annually', 6);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
