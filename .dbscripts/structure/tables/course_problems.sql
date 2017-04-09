-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 05:37 PM
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
-- Table structure for table 'course_problems'
--

CREATE TABLE IF NOT EXISTS course_problems (
  problem_id int(11) NOT NULL AUTO_INCREMENT,
  section_id int(11) NOT NULL,
  problem_order int(11) NOT NULL DEFAULT '1',
  problem_title varchar(100) COLLATE latin1_general_ci NOT NULL,
  problem_overview varchar(100) COLLATE latin1_general_ci NOT NULL,
  problem_text varchar(4000) COLLATE latin1_general_ci NOT NULL,
  problem_purpose varchar(4000) COLLATE latin1_general_ci NOT NULL,
  problem_elements varchar(4000) COLLATE latin1_general_ci NOT NULL,
  problem_solution varchar(4000) COLLATE latin1_general_ci NOT NULL,
  problem_notes varchar(4000) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (problem_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
