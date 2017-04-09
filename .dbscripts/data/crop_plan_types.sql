-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2013 at 04:31 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Dumping data for table `crop_plan_types`
--

INSERT INTO `crop_plan_types` (`id`, `plan_type`, `description`) 
VALUES(1, 'PLANTING', 'sets planting dates by Start Date.');

INSERT INTO `crop_plan_types` (`id`, `plan_type`, `description`) 
VALUES(2, 'MATURITY', 'sets planting dates to Mature Date minus Mature Days.');

INSERT INTO `crop_plan_types` (`id`, `plan_type`, `description`) 
VALUES(3, 'TRANSPLANT', 'sets planting dates to Transplant Date minus Transplant Days.');

INSERT INTO `crop_plan_types` (`id`, `plan_type`, `description`) 
VALUES(4, 'UNSPECIFIED', 'allows manually setting planting date for each planting.');

INSERT INTO `crop_plan_types` (`id`, `plan_type`, `description`) 
VALUES(5, 'LOCKED', 'Planting dates will not be updated.');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
