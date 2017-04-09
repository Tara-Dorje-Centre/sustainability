-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 05:33 PM
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
-- Table structure for table 'crop_plantings'
--

CREATE TABLE IF NOT EXISTS crop_plantings (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  crop_id bigint(20) NOT NULL,
  method varchar(100) COLLATE latin1_general_ci NOT NULL,
  crop_plan_id bigint(20) NOT NULL,
  planted timestamp NULL DEFAULT NULL,
  germinated timestamp NULL DEFAULT NULL,
  location_id bigint(20) NOT NULL,
  rows_planted int(11) NOT NULL DEFAULT '0',
  per_row_planted int(11) NOT NULL DEFAULT '0',
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  notes varchar(500) COLLATE latin1_general_ci NOT NULL,
  planted_count int(11) NOT NULL DEFAULT '0',
  germinated_count int(11) NOT NULL DEFAULT '0',
  thinned_count int(11) NOT NULL DEFAULT '0',
  parent_planting_id bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
