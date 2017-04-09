-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 05:39 PM
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
-- Table structure for table 'activities'
--

CREATE TABLE IF NOT EXISTS activities (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id bigint(20) NOT NULL,
  done_by varchar(50) COLLATE latin1_general_ci NOT NULL,
  started timestamp NULL DEFAULT '0000-00-00 00:00:00',
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  activity_order decimal(10,2) NOT NULL DEFAULT '1.00',
  hours_estimated decimal(10,2) NOT NULL,
  hours_actual decimal(10,2) NOT NULL,
  comments varchar(500) COLLATE latin1_general_ci NOT NULL,
  link_url varchar(1000) COLLATE latin1_general_ci NOT NULL,
  link_text varchar(100) COLLATE latin1_general_ci NOT NULL,
  type_id bigint(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
