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
-- Structure for view 'crop_plantings_v'
--

CREATE OR REPLACE VIEW crop_plantings_v AS 
select cp.id AS id,
cp.crop_id AS crop_id,
c.common_name AS common_name,
c.variety_name AS variety_name,
cp.method AS method,
cp.crop_plan_id AS crop_plan_id,
cp.location_id AS location_id,
l.sort_key AS location_name,
cp.planted AS planted,
cp.germinated AS germinated,
cp.updated AS updated,
cp.planted_count AS planted_count,
c.days_germinate AS days_germinate,
cp.germinated_count AS germinated_count,
cp.thinned_count AS thinned_count,
c.days_mature AS days_mature,
(ifnull(cp.planted,pl.started) + interval c.days_germinate day) AS estimated_germination,
(ifnull(cp.germinated,(ifnull(cp.planted,pl.started) + interval c.days_germinate day)) + interval c.days_mature day) AS estimated_maturity,
(ifnull(cp.germinated,(ifnull(cp.planted,pl.started) + interval c.days_germinate day)) + interval c.days_transplant day) AS estimated_transplant,
cp.rows_planted AS rows_planted,cp.per_row_planted AS per_row_planted,cp.notes AS notes 
from crop_plans pl join crop_plantings cp on pl.id = cp.crop_plan_id
join crops c on cp.crop_id = c.id
left join locations l on cp.location_id = l.id
order by cp.crop_plan_id,c.common_name,c.variety_name;

-- --------------------------------------------------------


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
