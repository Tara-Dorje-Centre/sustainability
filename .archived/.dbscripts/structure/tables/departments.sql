-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 05:32 PM
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
-- Table structure for table 'departments'
--

CREATE TABLE IF NOT EXISTS departments (
  dept_number int(11) NOT NULL DEFAULT '1',
  dept_title varchar(100) COLLATE latin1_general_ci NOT NULL,
  dept_desc varchar(4000) COLLATE latin1_general_ci NOT NULL,
  dept_goals varchar(4000) COLLATE latin1_general_ci NOT NULL,
  dept_id int(8) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (dept_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
