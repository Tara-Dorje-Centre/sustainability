-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2013 at 04:35 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- Dumping data for table `task_types`
--

INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(1, 'Recurring - Daily', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%.', '2013-01-22 17:51:09', '2013-01-21 16:56:57', 'none', 'daily', 11);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(2, 'Recurring - Weekly', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%', '2013-01-22 17:51:00', '2013-01-21 16:57:14', 'none', 'weekly', 12);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(3, 'Recurring - Monthly', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%.', '2013-01-22 17:51:18', '2013-01-21 16:57:31', 'none', 'monthly', 13);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(4, 'Recipe', 'Multiple recipes can be grouped together under a project to save how to do the recipe and to record activity when the recipe is made.', 'Task details a recipe.', '2013-01-22 17:50:14', '2013-01-21 16:57:54', 'none', 'no', 2);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(5, 'Process', 'Activities under task show each time process was completed.', 'Task details a process.', '2013-01-24 22:12:35', '2013-01-21 16:58:13', 'none', 'no', 3);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(7, 'Activity', 'Use estimated effort to set default value for actual effort on activities reported under the task.', 'Single occurrence task.  When marked complete, task contributes to project overall complete status.', '2013-01-24 22:12:22', '2013-01-21 17:08:12', 'none', 'no', 1);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(6, 'Research', 'Create separate tasks for different topic areas under a general research project.  Activities under a research task can save one link to a website.  To store multiple links use multiple activity records.', 'Research into a topic area.', '2013-01-22 17:50:36', '2013-01-21 17:00:23', 'none', 'no', 4);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(8, 'Recurring - Quarterly', '', '', '2013-01-22 17:51:30', '2013-01-22 17:49:25', 'none', 'quarterly', 14);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(9, 'Recurring - Yearly', '', '', '2013-01-22 18:17:56', '2013-01-22 18:17:56', 'none', 'annual', 15);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(10, 'As Needed', '', 'Task should be done on an as needed basis.  Does not show up in periodic task views.', '2013-01-22 22:09:42', '2013-01-22 22:08:47', 'none', 'no', 5);
INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(11, 'Purchasing', '', '', '2013-01-23 17:39:44', '2013-01-23 17:39:44', 'none', 'no', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
