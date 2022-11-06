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
-- Structure for view 'project_status_v'
--

CREATE OR REPLACE VIEW project_status_v AS 
select 'OPEN' AS `status`,
count(distinct p.id) AS projects,
count(0) AS project_tasks,
sum(t.hours_estimated) AS task_hours_estimated,
sum(t.hours_actual) AS task_hours_actual 
from (projects p join tasks t on((p.id = t.project_id))) 
where (p.pct_done < 1) 
union 
select 'DONE' AS `status`,
count(distinct p.id) AS projects,
count(0) AS project_tasks,
sum(t.hours_estimated) AS task_hours_estimated,
sum(t.hours_actual) AS task_hours_actual 
from projects p join tasks t on p.id = t.project_id
where (p.pct_done = 1);

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
