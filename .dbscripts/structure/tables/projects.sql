-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 05:07 PM
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
-- Table structure for table 'projects'
--

CREATE TABLE IF NOT EXISTS projects (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  description varchar(1000) COLLATE latin1_general_ci NOT NULL,
  summary varchar(1000) COLLATE latin1_general_ci NOT NULL,
  started timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  pct_done decimal(10,2) NOT NULL DEFAULT '0.00',
  priority decimal(10,2) NOT NULL DEFAULT '1.00',
  parent_id bigint(20) NOT NULL,
  goals varchar(100) COLLATE latin1_general_ci NOT NULL,
  lessons_learned varchar(1000) COLLATE latin1_general_ci NOT NULL,
  location_id bigint(20) NOT NULL DEFAULT '0',
  hours_estimated decimal(10,2) NOT NULL DEFAULT '0.00',
  hours_actual decimal(10,2) NOT NULL DEFAULT '0.00',
  budget_estimated decimal(10,2) NOT NULL DEFAULT '0.00',
  budget_notes varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
  hours_notes varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
  venue varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  purpose varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  show_always varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'yes',
  type_id bigint(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
