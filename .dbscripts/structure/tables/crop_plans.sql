-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 05:34 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'taradorj_utahrobotics'
--

-- --------------------------------------------------------

--
-- Table structure for table 'crop_plans'
--

CREATE TABLE IF NOT EXISTS crop_plans (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  plan_name varchar(100) COLLATE latin1_general_ci NOT NULL,
  plan_year int(11) NOT NULL,
  plan_number int(11) NOT NULL,
  description varchar(500) COLLATE latin1_general_ci NOT NULL,
  started timestamp NULL DEFAULT '0000-00-00 00:00:00',
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  finished timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  transplanted timestamp NULL DEFAULT NULL,
  mature timestamp NULL DEFAULT NULL,
  plan_type varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT 'PLANTING',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
