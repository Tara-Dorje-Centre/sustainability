-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 05:35 PM
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
-- Table structure for table 'crops'
--

CREATE TABLE IF NOT EXISTS crops (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  parent_id bigint(20) NOT NULL DEFAULT '0',
  common_name varchar(100) COLLATE latin1_general_ci NOT NULL,
  variety_name varchar(100) COLLATE latin1_general_ci NOT NULL,
  botanical_name varchar(100) COLLATE latin1_general_ci NOT NULL,
  family_name varchar(100) COLLATE latin1_general_ci NOT NULL,
  days_mature int(11) NOT NULL,
  days_mature_max int(11) NOT NULL,
  days_transplant int(11) NOT NULL,
  days_transplant_max int(11) NOT NULL DEFAULT '0',
  days_germinate int(11) NOT NULL,
  days_germinate_max int(11) NOT NULL,
  seed_depth_inches double(10,2) NOT NULL,
  seed_spacing_inches double(10,2) NOT NULL,
  thinning_height_inches double(10,2) NOT NULL DEFAULT '0.00',
  inrow_spacing_inches double(10,2) NOT NULL,
  row_spacing_inches double(10,2) NOT NULL,
  planting_notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  transplanting_notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  thinning_notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  care_notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  site_notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  certifications varchar(100) COLLATE latin1_general_ci NOT NULL,
  harvest_notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  light_needs varchar(1000) COLLATE latin1_general_ci NOT NULL,
  moisture_needs varchar(1000) COLLATE latin1_general_ci NOT NULL,
  soil_needs varchar(1000) COLLATE latin1_general_ci NOT NULL,
  seeds_on_hand varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
