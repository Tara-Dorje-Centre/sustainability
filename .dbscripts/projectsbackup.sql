-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2018 at 02:54 AM
-- Server version: 5.6.19
-- PHP Version: 7.1.5

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projects`
--
CREATE DATABASE IF NOT EXISTS `projects` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `projects`;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` bigint(20) NOT NULL,
  `task_id` bigint(20) NOT NULL,
  `done_by` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `started` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activity_order` decimal(10,2) NOT NULL DEFAULT '1.00',
  `hours_estimated` decimal(10,2) NOT NULL,
  `hours_actual` decimal(10,2) NOT NULL,
  `comments` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `link_url` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `link_text` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `type_id` bigint(12) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `activity_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_order` int(4) DEFAULT '0',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `activity_types`
--

TRUNCATE TABLE `activity_types`;
--
-- Dumping data for table `activity_types`
--

INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(1, 'unit testing', 'Another', 'Described clearly', '2017-07-29 21:32:30', '2017-06-30 17:20:08', 1, 'text-green');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(2, 'preparing', '', '', '2017-07-05 16:36:57', '2017-06-30 17:43:26', 2, 'highlight-turquoise');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(3, 'Digging', '', '', '2017-07-29 21:32:12', '2017-07-29 21:32:12', 3, 'highlight-khaki');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(4, 'Carrying Water', '', '', '2017-07-29 21:32:48', '2017-07-29 21:32:48', 4, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(5, 'Cutting Wood', '', '', '2017-07-29 21:33:07', '2017-07-29 21:33:07', 5, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(6, 'Planning', '', '', '2017-07-29 21:33:25', '2017-07-29 21:33:25', 6, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(7, 'Design', '', '', '2017-07-29 21:33:35', '2017-07-29 21:33:35', 7, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(8, 'Pruning', '', '', '2017-07-29 21:35:49', '2017-07-29 21:33:55', 8, 'highlight-turquoise');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(9, 'Watering Plants', '', '', '2017-07-29 21:34:08', '2017-07-29 21:34:08', 9, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(10, 'Development', '', '', '2017-07-29 21:34:38', '2017-07-29 21:34:38', 10, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(11, 'Setup', '', '', '2017-07-29 21:34:47', '2017-07-29 21:34:47', 11, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(12, 'Research', '', '', '2017-07-29 21:34:57', '2017-07-29 21:34:57', 12, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(13, 'Documentation', '', '', '2017-07-29 21:35:12', '2017-07-29 21:35:12', 13, 'none');
INSERT IGNORE INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(14, 'DataEntry', '', '', '2017-07-29 21:35:22', '2017-07-29 21:35:22', 14, 'none');

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `crops` (
  `id` bigint(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL DEFAULT '0',
  `common_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `variety_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `botanical_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `family_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `days_mature` int(11) NOT NULL,
  `days_mature_max` int(11) NOT NULL,
  `days_transplant` int(11) NOT NULL,
  `days_transplant_max` int(11) NOT NULL DEFAULT '0',
  `days_germinate` int(11) NOT NULL,
  `days_germinate_max` int(11) NOT NULL,
  `seed_depth_inches` double(10,2) NOT NULL,
  `seed_spacing_inches` double(10,2) NOT NULL,
  `thinning_height_inches` double(10,2) NOT NULL DEFAULT '0.00',
  `inrow_spacing_inches` double(10,2) NOT NULL,
  `row_spacing_inches` double(10,2) NOT NULL,
  `planting_notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `transplanting_notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `thinning_notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `care_notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `site_notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `certifications` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `harvest_notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `light_needs` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `moisture_needs` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `soil_needs` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `seeds_on_hand` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crop_plans`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `crop_plans` (
  `id` bigint(20) NOT NULL,
  `plan_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `plan_year` int(11) NOT NULL,
  `plan_number` int(11) NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `started` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `finished` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transplanted` timestamp NULL DEFAULT NULL,
  `mature` timestamp NULL DEFAULT NULL,
  `plan_type` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT 'PLANTING'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crop_plantings`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `crop_plantings` (
  `id` bigint(20) NOT NULL,
  `crop_id` bigint(20) NOT NULL,
  `method` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `crop_plan_id` bigint(20) NOT NULL,
  `planted` timestamp NULL DEFAULT NULL,
  `germinated` timestamp NULL DEFAULT NULL,
  `location_id` bigint(20) NOT NULL,
  `rows_planted` int(11) NOT NULL DEFAULT '0',
  `per_row_planted` int(11) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notes` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `planted_count` int(11) NOT NULL DEFAULT '0',
  `germinated_count` int(11) NOT NULL DEFAULT '0',
  `thinned_count` int(11) NOT NULL DEFAULT '0',
  `parent_planting_id` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crop_plantings_v`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `crop_plantings_v` (
  `id` bigint(20) DEFAULT NULL,
  `crop_id` bigint(20) DEFAULT NULL,
  `common_name` varchar(100) DEFAULT NULL,
  `variety_name` varchar(100) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `crop_plan_id` bigint(20) DEFAULT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `location_name` varchar(1000) DEFAULT NULL,
  `planted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `germinated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `planted_count` int(11) DEFAULT NULL,
  `days_germinate` int(11) DEFAULT NULL,
  `germinated_count` int(11) DEFAULT NULL,
  `thinned_count` int(11) DEFAULT NULL,
  `days_mature` int(11) DEFAULT NULL,
  `estimated_germination` datetime DEFAULT NULL,
  `estimated_maturity` datetime DEFAULT NULL,
  `estimated_transplant` datetime DEFAULT NULL,
  `rows_planted` int(11) DEFAULT NULL,
  `per_row_planted` int(11) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `crop_planting_calendar_v`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `crop_planting_calendar_v` (
  `sort_method` varchar(12) DEFAULT NULL,
  `crop_id` bigint(20) DEFAULT NULL,
  `common_name` varchar(100) DEFAULT NULL,
  `variety_name` varchar(100) DEFAULT NULL,
  `date_item` date DEFAULT NULL,
  `year_item` bigint(20) DEFAULT NULL,
  `month_item` bigint(20) DEFAULT NULL,
  `planting_id` bigint(20) DEFAULT NULL,
  `crop_plan_id` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `crop_plan_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `crop_plan_types` (
  `id` bigint(20) NOT NULL,
  `plan_type` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `css_colors`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `css_colors` (
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `css_colors`
--

TRUNCATE TABLE `css_colors`;
--
-- Dumping data for table `css_colors`
--

INSERT IGNORE INTO `css_colors` (`name`) VALUES('AliceBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('AntiqueWhite');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Aqua');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Aquamarine');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Azure');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Beige');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Bisque');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Black');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('BlanchedAlmond');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Blue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('BlueViolet');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Brown');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Burlywood');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('CadetBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Chartreuse');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Chocolate');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Coral');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('CornflowerBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Cornsilk');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Crimson');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Cyan');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkCyan');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkGoldenRod');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkGray');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkGrey');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkKhaki');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkMagenta');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkOliveGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkOrange');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkOrchid');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkRed');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkSalmon');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkSeaGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkSlateBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkSlateGray');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkSlateGrey');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkTurquoise');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DarkViolet');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DeepPink');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DeepSkyBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DimGray');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DimGrey');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('DodgerBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('FireBrick');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('FloralWhite');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('ForestGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Fuschia');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Gainsboro');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('GhostWhite');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Gold');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('GoldenRod');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Gray');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Green');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('GreenYellow');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('HoneyDew');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('HotPink');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('IndianRed');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Indigo');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Ivory');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Khaki');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Lavender');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LavenderBlush');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LawnGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LemonChiffon');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightCoral');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightCyan');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightGoldenRodYellow');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightGray');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightGrey');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightPink');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightSalmon');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightSeaGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightSkyBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightSlateGray');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightSteelBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LightYellow');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Lime');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('LimeGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Linen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Magenta');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Maroon');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumAquaMarine');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumOrchid');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumPurple');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumSeaGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumSlateBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumSpringGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumTurquoise');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MediumVioletRed');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MidnightBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MintCream');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('MistyRose');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Moccasin');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('NavajoWhite');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Navy');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('OldLace');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Olive');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('OliveDrab');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Orange');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('OrangeRed');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Orchid');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('PaleGoldenRod');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('PaleGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('PaleTurquoise');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('PaleVioletRed');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('PapayaWhip');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('PeachPuff');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Peru');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Pink');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Plum');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('PowderBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Purple');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Red');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('RosyBrown');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('RoyalBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SaddleBrown');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Salmon');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SandyBrown');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SeaGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SeaShell');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Sienna');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Silver');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SkyBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SlateBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SlateGray');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Snow');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SpringGreen');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('SteelBlue');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Tan');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Teal');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Thistle');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Tomato');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Turquoise');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Violet');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Wheat');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('White');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('WhiteSmoke');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('Yellow');
INSERT IGNORE INTO `css_colors` (`name`) VALUES('YellowGreen');

-- --------------------------------------------------------

--
-- Table structure for table `css_fonts`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `css_fonts` (
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `css_fonts`
--

TRUNCATE TABLE `css_fonts`;
--
-- Dumping data for table `css_fonts`
--

INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Lucida Console, Monaco, monospace');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Courier New, Courier, monospace');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Verdana, Geneva, sans-serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Trebuchet MS, Helvetica, sans-serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Tahoma, Geneva, sans-serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Lucida Sans Unicode, Lucida Grande, sans-serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Impact, Charcoal, sans-serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Comic Sans MS, cursive, sans-serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Arial Black, Gadget, sans-serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Arial, Helvetica, sans-serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Times New Roman, Times, serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Palatino Linotype, Book Antiqua, Palatino, serif');
INSERT IGNORE INTO `css_fonts` (`name`) VALUES('Georgia, serif');

-- --------------------------------------------------------

--
-- Table structure for table `css_highlight_styles`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `css_highlight_styles` (
  `style_name` varchar(100) NOT NULL,
  `background_color` varchar(100) NOT NULL,
  `text_color` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `css_highlight_styles`
--

TRUNCATE TABLE `css_highlight_styles`;
--
-- Dumping data for table `css_highlight_styles`
--

INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-blue', 'lightblue', 'blue');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-green', 'lightgreen', 'blue');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-yellow', 'yellow', 'black');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-grey', 'lightgrey', 'black');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-red', 'red', 'white');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-purple', 'thistle', 'black');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-pink', 'lightpink', 'black');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('text-purple', 'white', 'purple');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('text-green', 'white', 'green');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('text-blue', 'white', 'blue');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('text-red', 'white', 'red');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('text-grey', 'white', 'darkgrey');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-turquoise', 'turquoise', 'black');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-peach', 'peach', 'black');
INSERT IGNORE INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES('highlight-khaki', 'khaki', 'black');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` bigint(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort_key` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `type_id` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 06, 2017 at 01:47 AM
--

CREATE TABLE IF NOT EXISTS `location_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none',
  `display_order` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `location_types`
--

TRUNCATE TABLE `location_types`;
--
-- Dumping data for table `location_types`
--

INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(1, 'real', 'Noted', 'Desc...', '2017-07-29 21:24:46', '2017-06-30 17:58:54', 'highlight-red', 1);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(2, 'imaginary', '', '', '2017-07-05 05:22:31', '2017-06-30 17:59:10', 'none', 2);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(3, 'dreamstate', '', 'Dreamscape', '2017-08-01 18:26:40', '2017-07-01 02:27:21', 'highlight-purple', 3);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(4, 'work', '', '', '2017-07-05 05:25:23', '2017-07-01 02:27:35', 'none', 4);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(5, 'home', 'Think about where', 'The place you call.', '2017-07-05 16:33:49', '2017-07-01 02:27:46', 'highlight-green', 5);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(6, 'workshop', '', 'Place to build things', '2017-07-31 16:47:20', '2017-07-01 02:28:06', 'none', 6);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(7, 'garden', '', '', '2017-07-01 02:28:20', '2017-07-01 02:28:20', '-no highlighting', 7);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(8, 'classroom', '', '', '2017-07-01 02:28:34', '2017-07-01 02:28:34', '-no highlighting', 8);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(9, 'studio', '', '', '2017-07-01 02:28:48', '2017-07-01 02:28:48', '-no highlighting', 9);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(10, 'library', '', '', '2017-07-01 02:29:00', '2017-07-01 02:29:00', '-no highlighting', 10);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(11, 'theater', '', 'Stage type of setting', '2017-07-31 16:45:49', '2017-07-01 02:29:17', 'highlight-pink', 11);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(12, 'stage', '', '', '2017-08-06 01:47:46', '2017-07-01 02:29:27', 'highlight-purple', 12);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(13, 'process point', '', '', '2017-07-01 02:29:44', '2017-07-01 02:29:44', '-no highlighting', 13);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(14, 'storage', '', '', '2017-07-05 04:35:53', '2017-07-05 04:35:53', 'none', 14);
INSERT IGNORE INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(15, 'vision art state', '', '', '2017-07-31 16:47:45', '2017-07-29 21:27:28', 'text-blue', 15);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `materials` (
  `id` bigint(20) NOT NULL,
  `task_id` bigint(20) NOT NULL,
  `location_id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `date_reported` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `quantity` double(10,2) NOT NULL DEFAULT '1.00',
  `qty_unit_measure_id` bigint(20) NOT NULL DEFAULT '0',
  `cost_unit` double(10,2) NOT NULL DEFAULT '0.00',
  `cost_estimated` double(10,2) NOT NULL DEFAULT '0.00',
  `cost_actual` double(10,2) NOT NULL DEFAULT '0.00',
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `link_url` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `link_text` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `done_by` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `type_id` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `material_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none',
  `display_order` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `material_types`
--

TRUNCATE TABLE `material_types`;
--
-- Dumping data for table `material_types`
--

INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(1, 'stuff', '', '', '2017-06-30 18:00:36', '2017-06-30 18:00:36', '-no highlighting', 1);
INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(2, 'things', '', '', '2017-06-30 18:00:47', '2017-06-30 18:00:47', '-no highlighting', 2);
INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(3, 'ideas', '', '', '2017-06-30 18:01:07', '2017-06-30 18:01:07', '-no highlighting', 3);
INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(4, 'food', '', '', '2017-07-29 23:25:26', '2017-07-29 23:25:26', 'none', 4);
INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(5, 'water', '', '', '2017-07-31 17:00:20', '2017-07-29 23:25:35', 'text-blue', 5);
INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(6, 'lumber', '', '', '2017-07-29 23:25:46', '2017-07-29 23:25:46', 'none', 6);
INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(7, 'hardware', '', '', '2017-07-29 23:25:59', '2017-07-29 23:25:59', 'none', 7);
INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(8, 'plumbingbsupplies', '', '', '2017-07-29 23:26:13', '2017-07-29 23:26:13', 'none', 8);
INSERT IGNORE INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(9, 'tools', '', '', '2017-07-29 23:26:23', '2017-07-29 23:26:23', 'none', 9);

-- --------------------------------------------------------

--
-- Table structure for table `measures`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `measures` (
  `id` bigint(20) NOT NULL,
  `task_id` bigint(20) NOT NULL,
  `location_id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `date_reported` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `value` float NOT NULL,
  `measure_type_unit_id` bigint(20) NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `measures`
--

TRUNCATE TABLE `measures`;
-- --------------------------------------------------------

--
-- Table structure for table `measure_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 06, 2017 at 03:16 AM
--

CREATE TABLE IF NOT EXISTS `measure_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_order` int(4) DEFAULT '0',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `measure_types`
--

TRUNCATE TABLE `measure_types`;
--
-- Dumping data for table `measure_types`
--

INSERT IGNORE INTO `measure_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(1, 'test', '', '', '2017-07-31 23:07:19', '2017-07-31 23:07:19', 1, 'none');
INSERT IGNORE INTO `measure_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(2, 'mass', '', '', '2017-07-31 23:17:25', '2017-07-31 23:07:30', 2, 'highlight-green');
INSERT IGNORE INTO `measure_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(3, 'volume', '', '', '2017-08-06 03:16:56', '2017-07-31 23:07:44', 3, 'text-blue');
INSERT IGNORE INTO `measure_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(4, 'Temperature', '', '', '2017-07-31 23:14:06', '2017-07-31 23:07:56', 4, 'none');
INSERT IGNORE INTO `measure_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(5, 'density', '', '', '2017-07-31 23:11:34', '2017-07-31 23:11:34', 4, 'none');
INSERT IGNORE INTO `measure_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(6, 'distance', '', '', '2017-07-31 23:18:10', '2017-07-31 23:17:40', 6, 'highlight-turquoise');
INSERT IGNORE INTO `measure_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES(7, 'quantity', '', '', '2017-07-31 23:17:53', '2017-07-31 23:17:53', 7, 'none');

-- --------------------------------------------------------

--
-- Table structure for table `measure_type_units`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `measure_type_units` (
  `id` bigint(20) NOT NULL,
  `measure_type_id` bigint(20) NOT NULL,
  `unit_measure_id` bigint(20) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_order` int(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `measure_type_units`
--

TRUNCATE TABLE `measure_type_units`;
-- --------------------------------------------------------

--
-- Table structure for table `measure_type_units_v`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `measure_type_units_v` (
  `id` bigint(20) DEFAULT NULL,
  `measure_type_unit_id` bigint(20) DEFAULT NULL,
  `measure_type_id` bigint(20) DEFAULT NULL,
  `unit_measure_id` bigint(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `measure_type` varchar(100) DEFAULT NULL,
  `unit_of_measure` varchar(100) DEFAULT NULL,
  `unit_type` varchar(100) DEFAULT NULL,
  `unit_symbol` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `summary` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `started` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pct_done` decimal(10,2) NOT NULL DEFAULT '0.00',
  `priority` decimal(10,2) NOT NULL DEFAULT '1.00',
  `parent_id` bigint(20) NOT NULL,
  `goals` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `lessons_learned` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `location_id` bigint(20) NOT NULL DEFAULT '0',
  `hours_estimated` decimal(10,2) NOT NULL DEFAULT '0.00',
  `hours_actual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `budget_estimated` decimal(10,2) NOT NULL DEFAULT '0.00',
  `budget_notes` varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
  `hours_notes` varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
  `venue` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `purpose` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `show_always` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'yes',
  `type_id` bigint(12) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_status_v`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `project_status_v` (
  `status` varchar(4) DEFAULT NULL,
  `projects` bigint(21) DEFAULT NULL,
  `project_tasks` bigint(21) DEFAULT NULL,
  `task_hours_estimated` decimal(32,2) DEFAULT NULL,
  `task_hours_actual` decimal(32,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_task_summary_v`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `project_task_summary_v` (
  `project_id` bigint(20) DEFAULT NULL,
  `project_pct_done` decimal(10,2) DEFAULT NULL,
  `total_tasks` bigint(21) DEFAULT NULL,
  `sum_hours_estimated` decimal(32,2) DEFAULT NULL,
  `sum_hours_actual` decimal(32,2) DEFAULT NULL,
  `overall_pct_done` decimal(36,6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 06, 2017 at 03:15 AM
--

CREATE TABLE IF NOT EXISTS `project_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none',
  `display_order` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `project_types`
--

TRUNCATE TABLE `project_types`;
--
-- Dumping data for table `project_types`
--

INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(1, 'Gardening', '', 'Gardening project', '2017-08-06 03:15:04', '2013-01-20 17:31:03', 'highlight-purple', 0);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(2, 'Construction', '', '', '2017-07-29 15:58:46', '2013-01-20 17:56:18', 'text-blue', 5);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(3, 'Repairs', '', '', '2017-08-06 03:15:37', '2013-01-20 17:56:30', 'text-red', 8);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(4, 'Maintenance/Cleanup', '', '', '2017-07-29 15:43:59', '2013-01-20 17:56:56', 'text-red', 7);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(5, 'Sustainability', '', 'Practical applications of sustainability concepts', '2017-07-29 15:57:47', '2013-01-20 17:57:47', 'text-green', 1);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(6, 'Food Preparation', '', 'Preparing food, meals, food supplies.', '2017-07-31 16:48:34', '2013-01-20 19:06:30', 'highlight-turquoise', 3);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(7, 'Research', '', '', '2017-07-29 15:48:41', '2013-01-20 19:17:26', 'text-blue', 2);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(8, 'Organization', '', '', '2017-07-29 15:59:42', '2013-01-20 19:51:46', 'text-blue', 4);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(9, 'Development', '', '', '2017-07-29 15:46:36', '2017-07-29 15:46:36', 'none', 9);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(10, 'Analysis and Review', '', '', '2017-07-29 15:49:26', '2017-07-29 15:49:26', 'none', 10);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(11, 'Analysis and Review', '', '', '2017-07-29 15:50:20', '2017-07-29 15:50:20', 'none', 10);
INSERT IGNORE INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(12, 'Editing and Review', '', '', '2017-07-29 15:51:24', '2017-07-29 15:51:24', 'text-red', 12);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `receipts` (
  `id` bigint(20) NOT NULL,
  `task_id` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `date_reported` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `quantity` double(10,2) NOT NULL DEFAULT '1.00',
  `qty_unit_measure_id` bigint(20) NOT NULL DEFAULT '0',
  `cost_unit` double(10,2) NOT NULL DEFAULT '0.00',
  `cost_actual` double(10,2) NOT NULL DEFAULT '0.00',
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `received_by` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `type_id` bigint(20) NOT NULL DEFAULT '0',
  `received_from` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `receipt_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none',
  `display_order` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `receipt_types`
--

TRUNCATE TABLE `receipt_types`;
--
-- Dumping data for table `receipt_types`
--

INSERT IGNORE INTO `receipt_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(1, 'donation', 'Notes\r\nTwo lines', 'Desc', '2017-07-31 17:01:10', '2017-06-30 18:01:33', 'highlight-yellow', 1);
INSERT IGNORE INTO `receipt_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(2, 'sale', '', '', '2017-07-31 17:01:40', '2017-06-30 18:01:44', 'highlight-red', 2);
INSERT IGNORE INTO `receipt_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(3, 'Loan', '', '', '2017-07-31 17:01:59', '2017-07-29 23:24:24', 'highlight-green', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sitewide_settings`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `sitewide_settings` (
  `site_title` varchar(100) NOT NULL,
  `organization` varchar(1000) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `site_url` varchar(1000) NOT NULL,
  `organization_url` varchar(255) NOT NULL,
  `login_notice` varchar(1000) NOT NULL DEFAULT '',
  `organization_description` varchar(4000) NOT NULL,
  `public_fill_color` varchar(50) NOT NULL,
  `public_site_color` varchar(50) NOT NULL,
  `public_page_color` varchar(50) NOT NULL,
  `public_menu_color` varchar(50) NOT NULL,
  `public_menu_color_hover` varchar(50) NOT NULL,
  `public_text_color` varchar(50) NOT NULL,
  `public_text_color_hover` varchar(50) NOT NULL,
  `public_font_family` varchar(100) NOT NULL DEFAULT 'Verdana, sans-serif',
  `public_font_size_title` int(2) NOT NULL DEFAULT '18',
  `public_font_size_menu` int(2) NOT NULL DEFAULT '12',
  `public_font_size_text` int(2) NOT NULL DEFAULT '14',
  `public_font_size_heading` int(2) NOT NULL DEFAULT '16',
  `show_public_site` varchar(10) NOT NULL DEFAULT 'no',
  `show_cost_reports` varchar(10) NOT NULL DEFAULT 'no',
  `show_revenue_reports` varchar(10) NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `sitewide_settings`
--

TRUNCATE TABLE `sitewide_settings`;
--
-- Dumping data for table `sitewide_settings`
--

INSERT IGNORE INTO `sitewide_settings` (`site_title`, `organization`, `contact_email`, `contact_name`, `site_url`, `organization_url`, `login_notice`, `organization_description`, `public_fill_color`, `public_site_color`, `public_page_color`, `public_menu_color`, `public_menu_color_hover`, `public_text_color`, `public_text_color_hover`, `public_font_family`, `public_font_size_title`, `public_font_size_menu`, `public_font_size_text`, `public_font_size_heading`, `show_public_site`, `show_cost_reports`, `show_revenue_reports`) VALUES('Dev projects', 'Taradorje', 'Me@mine', 'That\'s me', 'Site.org', 'Mine.org', 'Yo mofo', 'Described', '', '', 'red', '', '', 'black', '', 'Verdana, sans-serif', 18, 12, 14, 16, 'yes', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` bigint(20) NOT NULL,
  `project_id` bigint(20) NOT NULL,
  `location_id` bigint(20) NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `summary` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `started` timestamp NULL DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pct_done` decimal(10,2) NOT NULL DEFAULT '0.00',
  `task_order` decimal(10,2) NOT NULL DEFAULT '1.00',
  `parent_id` bigint(20) NOT NULL DEFAULT '0',
  `hours_estimated` decimal(10,2) NOT NULL DEFAULT '0.00',
  `hours_actual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `hours_notes` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `type_id` bigint(12) NOT NULL DEFAULT '0',
  `materials_auth_project` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  `materials_auth_by` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `receipts_auth_project` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  `receipts_auth_by` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Authorized'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `task_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none',
  `frequency` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  `display_order` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `task_types`
--

TRUNCATE TABLE `task_types`;
--
-- Dumping data for table `task_types`
--

INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(1, 'Recurring - Daily', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%.', '2017-07-31 16:58:44', '2013-01-21 16:56:57', 'highlight-green', 'daily', 11);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(2, 'Recurring - Weekly', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%', '2013-01-22 17:51:00', '2013-01-21 16:57:14', 'none', 'weekly', 12);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(3, 'Recurring - Monthly', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%.', '2013-01-22 17:51:18', '2013-01-21 16:57:31', 'none', 'monthly', 13);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(4, 'Recipe', 'Multiple recipes can be grouped together under a project to save how to do the recipe and to record activity when the recipe is made.', 'Task details a recipe.', '2017-07-29 21:36:41', '2013-01-21 16:57:54', 'BlueViolet', 'no', 2);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(5, 'Process', 'Activities under task show each time process was completed.', 'Task details a process.', '2013-01-24 22:12:35', '2013-01-21 16:58:13', 'none', 'no', 3);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(7, 'Activity', 'Use estimated effort to set default value for actual effort on activities reported under the task.', 'Single occurrence task.  When marked complete, task contributes to project overall complete status.', '2017-07-31 16:58:04', '2013-01-21 17:08:12', 'highlight-purple', 'none', 1);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(6, 'Research', 'Create separate tasks for different topic areas under a general research project.  Activities under a research task can save one link to a website.  To store multiple links use multiple activity records.', 'Research into a topic area.', '2013-01-22 17:50:36', '2013-01-21 17:00:23', 'none', 'no', 4);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(8, 'Recurring - Quarterly', '', '', '2013-01-22 17:51:30', '2013-01-22 17:49:25', 'none', 'quarterly', 14);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(9, 'Recurring - Yearly', '', '', '2013-01-22 18:17:56', '2013-01-22 18:17:56', 'none', 'annual', 15);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(10, 'As Needed', '', 'Task should be done on an as needed basis.  Does not show up in periodic task views.', '2013-01-22 22:09:42', '2013-01-22 22:08:47', 'none', 'no', 5);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(11, 'Purchasing', '', '', '2013-01-23 17:39:44', '2013-01-23 17:39:44', 'none', 'no', 0);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(12, 'Baking', '', '', '2017-07-29 21:37:57', '2017-07-29 21:37:07', 'CornflowerBlue', 'weekly', 12);
INSERT IGNORE INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES(13, 'Budget Planning', '', '', '2017-07-29 21:39:17', '2017-07-29 21:39:17', 'Aquamarine', 'monthly', 13);

-- --------------------------------------------------------

--
-- Table structure for table `task_type_frequencies`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `task_type_frequencies` (
  `frequency` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `display_order` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `task_type_frequencies`
--

TRUNCATE TABLE `task_type_frequencies`;
--
-- Dumping data for table `task_type_frequencies`
--

INSERT IGNORE INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('no', 'Not Periodic', 1);
INSERT IGNORE INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('daily', 'Periodic Daily', 2);
INSERT IGNORE INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('weekly', 'Periodic Weekly', 3);
INSERT IGNORE INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('monthly', 'Periodic Monthly', 4);
INSERT IGNORE INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('quarterly', 'Periodic Quarterly', 5);
INSERT IGNORE INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES('annual', 'Periodic Annually', 6);

-- --------------------------------------------------------

--
-- Table structure for table `units_of_measure`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `units_of_measure` (
  `id` bigint(20) NOT NULL,
  `type` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `symbol` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
  `display_order` int(4) NOT NULL DEFAULT '0',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `units_of_measure`
--

TRUNCATE TABLE `units_of_measure`;
--
-- Dumping data for table `units_of_measure`
--

INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(1, 'temperature', 'degrees celcius', '2012-12-08 23:09:34', '0000-00-00 00:00:00', '', 'Â°C', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(2, 'temperature', 'degrees fahrenheit', '2012-12-08 23:10:12', '0000-00-00 00:00:00', '', 'Â°F', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(3, 'length', 'meter', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'm', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(4, 'length', 'feet', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'ft', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(5, 'concentration', 'ppm', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'ppm', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(6, 'pH', 'pH', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'pH', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(7, 'concentration', 'percent', '2013-01-07 20:55:14', '0000-00-00 00:00:00', '', '%', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(8, 'length', 'centimeter', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'cm', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(9, 'time', 'hours', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'h', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(10, 'time', 'seconds', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 's', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(11, 'time', 'days', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'd', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(12, 'mass', 'kilogram', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'kg', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(13, 'mass', 'pound', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'lb', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(14, 'mass', 'ounce', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'oz', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(15, 'mass', 'gram', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'gm', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(16, 'length', 'inches', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'in', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(17, 'frequency', 'Hertz', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'Hz', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(18, 'light', 'lumens', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'lu', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(19, 'scalar', 'Scale', '2012-12-08 23:11:50', '2012-12-08 23:04:34', '', '1-10', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(20, 'volume', 'cup', '2013-01-21 04:01:15', '2013-01-21 03:59:12', 'One Cup = 8 oz', 'c', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(21, 'Volume', 'Pint', '2013-01-21 03:59:45', '2013-01-21 03:59:45', '', 'pt', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(22, 'Volume', 'Quart', '2013-01-21 04:00:25', '2013-01-21 04:00:25', '', 'qt', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(23, 'volume', 'gallon', '2013-01-21 04:00:46', '2013-01-21 04:00:46', '', 'ga', NULL, 0, 'none');
INSERT IGNORE INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES(24, 'volume', 'tablespoon', '2013-01-21 04:01:37', '2013-01-21 04:01:37', '', 'tbl', NULL, 0, 'none');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Oct 08, 2017 at 03:07 AM
-- Last update: Jan 01, 2010 at 08:47 PM
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(12) NOT NULL,
  `name_first` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `name_last` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `focus` varchar(4000) COLLATE latin1_general_ci DEFAULT NULL,
  `interests` varchar(4000) COLLATE latin1_general_ci NOT NULL,
  `login_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `login_pwd` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type_id` bigint(20) NOT NULL DEFAULT '0',
  `is_admin` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  `must_update_pwd` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  `is_active` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT IGNORE INTO `users` (`id`, `name_first`, `name_last`, `email`, `focus`, `interests`, `login_name`, `login_pwd`, `last_login`, `created`, `updated`, `type_id`, `is_admin`, `must_update_pwd`, `is_active`) VALUES(1, 'anthony', 'harper', 'gaiansentience@gmail.com', 'technical support', '', 'aharper', '9318d2a4ff3d67c7f0c1ad8e20843caf', '2010-01-01 20:47:46', NULL, '2010-01-01 20:47:46', 0, 'yes', 'no', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--
-- Creation: Aug 05, 2017 at 12:35 AM
-- Last update: Aug 05, 2017 at 12:35 AM
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `highlight_style` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none',
  `display_order` int(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Truncate table before insert `user_types`
--

TRUNCATE TABLE `user_types`;
--
-- Dumping data for table `user_types`
--

INSERT IGNORE INTO `user_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(1, 'basic', '', '', '2017-06-30 18:03:01', '2017-06-30 18:03:01', '-no highlighting', 1);
INSERT IGNORE INTO `user_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(2, 'advanced', '', '', '2017-06-30 18:03:17', '2017-06-30 18:03:17', '-no highlighting', 2);
INSERT IGNORE INTO `user_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(3, 'honorary', '', '', '2017-07-30 00:09:56', '2017-07-30 00:09:56', 'none', 3);
INSERT IGNORE INTO `user_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES(4, 'Staff', '', '', '2017-07-30 00:10:32', '2017-07-30 00:10:06', 'highlight-peach', 4);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
