-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2013 at 04:36 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Dumping data for table `units_of_measure`
--

INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(1, 'temperature', 'degrees celcius', '2012-12-08 23:09:34', '0000-00-00 00:00:00', '', 'Â°C');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(2, 'temperature', 'degrees fahrenheit', '2012-12-08 23:10:12', '0000-00-00 00:00:00', '', 'Â°F');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(3, 'length', 'meter', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'm');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(4, 'length', 'feet', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'ft');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(5, 'concentration', 'ppm', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'ppm');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(6, 'pH', 'pH', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'pH');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(7, 'concentration', 'percent', '2013-01-07 20:55:14', '0000-00-00 00:00:00', '', '%');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(8, 'length', 'centimeter', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'cm');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(9, 'time', 'hours', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'h');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(10, 'time', 'seconds', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 's');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(11, 'time', 'days', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'd');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(12, 'mass', 'kilogram', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'kg');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(13, 'mass', 'pound', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'lb');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(14, 'mass', 'ounce', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'oz');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(15, 'mass', 'gram', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'gm');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(16, 'length', 'inches', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'in');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(17, 'frequency', 'Hertz', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'Hz');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(18, 'light', 'lumens', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'lu');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(19, 'scalar', 'Scale', '2012-12-08 23:11:50', '2012-12-08 23:04:34', '', '1-10');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(20, 'volume', 'cup', '2013-01-21 04:01:15', '2013-01-21 03:59:12', 'One Cup = 8 oz', 'c');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(21, 'Volume', 'Pint', '2013-01-21 03:59:45', '2013-01-21 03:59:45', '', 'pt');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(22, 'Volume', 'Quart', '2013-01-21 04:00:25', '2013-01-21 04:00:25', '', 'qt');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(23, 'volume', 'gallon', '2013-01-21 04:00:46', '2013-01-21 04:00:46', '', 'ga');
INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`) VALUES(24, 'volume', 'tablespoon', '2013-01-21 04:01:37', '2013-01-21 04:01:37', '', 'tbl');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
