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

-- Structure for view 'crop_planting_calendar_v'
--

CREATE OR REPLACE VIEW crop_planting_calendar_v AS 
select 'PLANTED' AS sort_method,cp.crop_id AS crop_id,cp.common_name AS common_name,cp.variety_name AS variety_name,cast(cp.planted as date) AS date_item,year(cp.planted) AS year_item,month(cp.planted) AS month_item,cp.id AS planting_id,cp.crop_plan_id AS crop_plan_id 
from crop_plantings_v cp 
union all 
select 'TRANSPLANTED' AS sort_method,cp.crop_id AS crop_id,cp.common_name AS common_name,cp.variety_name AS variety_name,cast(cp.estimated_transplant as date) AS date_item,year(cp.estimated_transplant) AS year_item,month(cp.estimated_transplant) AS month_item,cp.id AS planting_id,cp.crop_plan_id AS crop_plan_id 
from crop_plantings_v cp 
union all 
select 'MATURE' AS sort_method,cp.crop_id AS crop_id,cp.common_name AS common_name,cp.variety_name AS variety_name,cast(cp.estimated_maturity as date) AS date_item,year(cp.estimated_maturity) AS year_item,month(cp.estimated_maturity) AS month_item,cp.id AS planting_id,cp.crop_plan_id AS crop_plan_id 
from crop_plantings_v cp;

-- --------------------------------------------------------


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
