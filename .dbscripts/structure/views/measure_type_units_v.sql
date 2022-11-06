-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 05, 2013 at 10:35 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Structure for view 'measure_type_units_v'
--

CREATE OR REPLACE VIEW measure_type_units_v AS 
select mtu.id AS id,
mtu.id AS measure_type_unit_id,
mtu.measure_type_id AS measure_type_id,
mtu.unit_measure_id AS unit_measure_id,
mtu.created AS created,
mtu.updated AS updated,
mt.`name` AS measure_type,
u.`name` AS unit_of_measure,
u.`type` AS unit_type,
u.symbol AS unit_symbol 
from 
measure_types mt join measure_type_units mtu on mtu.measure_type_id = mt.id
join units_of_measure u on mtu.unit_measure_id = u.id
order by u.`type`,mt.`name`,u.`name`;

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
