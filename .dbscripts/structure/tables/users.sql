-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2013 at 04:55 PM
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
-- Table structure for table 'users'
--

CREATE TABLE IF NOT EXISTS users (
  id bigint(12) NOT NULL AUTO_INCREMENT,
  name_first varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  name_last varchar(100) COLLATE latin1_general_ci NOT NULL,
  email varchar(255) COLLATE latin1_general_ci NOT NULL,
  focus varchar(4000) COLLATE latin1_general_ci DEFAULT NULL,
  interests varchar(4000) COLLATE latin1_general_ci NOT NULL,
  login_name varchar(50) COLLATE latin1_general_ci NOT NULL,
  login_pwd varchar(1000) COLLATE latin1_general_ci NOT NULL,
  last_login timestamp NULL DEFAULT NULL,
  created timestamp NULL DEFAULT NULL,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  type_id bigint(20) NOT NULL DEFAULT '0',
  is_admin varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  must_update_pwd varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  is_active varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (id),
  UNIQUE KEY login_name (login_name),
  UNIQUE KEY email (email)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
