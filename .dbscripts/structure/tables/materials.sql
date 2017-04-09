-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 05:50 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'taradorj_demo'
--

-- --------------------------------------------------------

--
-- Table structure for table 'materials'
--

CREATE TABLE IF NOT EXISTS materials (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id bigint(20) NOT NULL,
  location_id bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  description varchar(1000) COLLATE latin1_general_ci NOT NULL,
  date_reported timestamp NULL DEFAULT '0000-00-00 00:00:00',
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  quantity double(10,2) NOT NULL DEFAULT '1.00',
  qty_unit_measure_id bigint(20) NOT NULL DEFAULT '0',
  cost_unit double(10,2) NOT NULL DEFAULT '0.00',
  cost_estimated double(10,2) NOT NULL DEFAULT '0.00',
  cost_actual double(10,2) NOT NULL DEFAULT '0.00',
  notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  link_url varchar(1000) COLLATE latin1_general_ci NOT NULL,
  link_text varchar(100) COLLATE latin1_general_ci NOT NULL,
  done_by varchar(100) COLLATE latin1_general_ci NOT NULL,
  type_id bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
