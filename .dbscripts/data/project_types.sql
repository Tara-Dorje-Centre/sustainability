-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2013 at 04:38 PM
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
-- Dumping data for table `project_types`
--

INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(1, 'Gardening', '', 'Gardening project', '2013-01-20 19:56:24', '2013-01-20 17:31:03', 'highlight-green', 0);
INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(2, 'Construction', '', '', '2013-01-20 18:21:34', '2013-01-20 17:56:18', 'highlight-grey', 0);
INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(3, 'Repairs', '', '', '2013-01-20 19:55:07', '2013-01-20 17:56:30', 'highlight-red', 0);
INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(4, 'Maintenance/Cleanup', '', '', '2013-01-25 22:36:14', '2013-01-20 17:56:56', 'highlight-turquoise', 0);
INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(5, 'Sustainability', '', '', '2013-01-20 21:57:57', '2013-01-20 17:57:47', 'highlight-purple', 0);
INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(6, 'Food Preparation', '', 'Preparing food, meals, food supplies.', '2013-01-20 19:56:04', '2013-01-20 19:06:30', 'highlight-blue', 0);
INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(7, 'Beautification', '', '', '2013-01-21 04:48:03', '2013-01-20 19:17:26', 'highlight-pink', 0);
INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(8, 'Organization', '', '', '2013-01-21 04:50:42', '2013-01-20 19:51:46', 'none', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
