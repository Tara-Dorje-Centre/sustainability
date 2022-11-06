-- phpMyAdmin SQL Dump
-- version 4.6.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 05, 2017 at 02:32 AM
-- Server version: 5.6.19
-- PHP Version: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projects`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
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

CREATE TABLE `activity_types` (
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
-- Dumping data for table `activity_types`
--

INSERT INTO `activity_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES
(1, 'unit testing', 'Another', 'Described clearly', '2017-07-29 21:32:30', '2017-06-30 17:20:08', 1, 'text-green'),
(2, 'preparing', '', '', '2017-07-05 16:36:57', '2017-06-30 17:43:26', 2, 'highlight-turquoise'),
(3, 'Digging', '', '', '2017-07-29 21:32:12', '2017-07-29 21:32:12', 3, 'highlight-khaki'),
(4, 'Carrying Water', '', '', '2017-07-29 21:32:48', '2017-07-29 21:32:48', 4, 'none'),
(5, 'Cutting Wood', '', '', '2017-07-29 21:33:07', '2017-07-29 21:33:07', 5, 'none'),
(6, 'Planning', '', '', '2017-07-29 21:33:25', '2017-07-29 21:33:25', 6, 'none'),
(7, 'Design', '', '', '2017-07-29 21:33:35', '2017-07-29 21:33:35', 7, 'none'),
(8, 'Pruning', '', '', '2017-07-29 21:35:49', '2017-07-29 21:33:55', 8, 'highlight-turquoise'),
(9, 'Watering Plants', '', '', '2017-07-29 21:34:08', '2017-07-29 21:34:08', 9, 'none'),
(10, 'Development', '', '', '2017-07-29 21:34:38', '2017-07-29 21:34:38', 10, 'none'),
(11, 'Setup', '', '', '2017-07-29 21:34:47', '2017-07-29 21:34:47', 11, 'none'),
(12, 'Research', '', '', '2017-07-29 21:34:57', '2017-07-29 21:34:57', 12, 'none'),
(13, 'Documentation', '', '', '2017-07-29 21:35:12', '2017-07-29 21:35:12', 13, 'none'),
(14, 'DataEntry', '', '', '2017-07-29 21:35:22', '2017-07-29 21:35:22', 14, 'none');

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--

CREATE TABLE `crops` (
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

CREATE TABLE `crop_plans` (
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

CREATE TABLE `crop_plantings` (
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
-- Stand-in structure for view `crop_plantings_v`
-- (See below for the actual view)
--
CREATE TABLE `crop_plantings_v` (
`id` bigint(20)
,`crop_id` bigint(20)
,`common_name` varchar(100)
,`variety_name` varchar(100)
,`method` varchar(100)
,`crop_plan_id` bigint(20)
,`location_id` bigint(20)
,`location_name` varchar(1000)
,`planted` timestamp
,`germinated` timestamp
,`updated` timestamp
,`planted_count` int(11)
,`days_germinate` int(11)
,`germinated_count` int(11)
,`thinned_count` int(11)
,`days_mature` int(11)
,`estimated_germination` datetime
,`estimated_maturity` datetime
,`estimated_transplant` datetime
,`rows_planted` int(11)
,`per_row_planted` int(11)
,`notes` varchar(500)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `crop_planting_calendar_v`
-- (See below for the actual view)
--
CREATE TABLE `crop_planting_calendar_v` (
`sort_method` varchar(12)
,`crop_id` bigint(20)
,`common_name` varchar(100)
,`variety_name` varchar(100)
,`date_item` date
,`year_item` bigint(20)
,`month_item` bigint(20)
,`planting_id` bigint(20)
,`crop_plan_id` bigint(20)
);

-- --------------------------------------------------------

--
-- Table structure for table `crop_plan_types`
--

CREATE TABLE `crop_plan_types` (
  `id` bigint(20) NOT NULL,
  `plan_type` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `css_colors`
--

CREATE TABLE `css_colors` (
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `css_colors`
--

INSERT INTO `css_colors` (`name`) VALUES
('AliceBlue'),
('AntiqueWhite'),
('Aqua'),
('Aquamarine'),
('Azure'),
('Beige'),
('Bisque'),
('Black'),
('BlanchedAlmond'),
('Blue'),
('BlueViolet'),
('Brown'),
('Burlywood'),
('CadetBlue'),
('Chartreuse'),
('Chocolate'),
('Coral'),
('CornflowerBlue'),
('Cornsilk'),
('Crimson'),
('Cyan'),
('DarkBlue'),
('DarkCyan'),
('DarkGoldenRod'),
('DarkGray'),
('DarkGrey'),
('DarkGreen'),
('DarkKhaki'),
('DarkMagenta'),
('DarkOliveGreen'),
('DarkOrange'),
('DarkOrchid'),
('DarkRed'),
('DarkSalmon'),
('DarkSeaGreen'),
('DarkSlateBlue'),
('DarkSlateGray'),
('DarkSlateGrey'),
('DarkTurquoise'),
('DarkViolet'),
('DeepPink'),
('DeepSkyBlue'),
('DimGray'),
('DimGrey'),
('DodgerBlue'),
('FireBrick'),
('FloralWhite'),
('ForestGreen'),
('Fuschia'),
('Gainsboro'),
('GhostWhite'),
('Gold'),
('GoldenRod'),
('Gray'),
('Green'),
('GreenYellow'),
('HoneyDew'),
('HotPink'),
('IndianRed'),
('Indigo'),
('Ivory'),
('Khaki'),
('Lavender'),
('LavenderBlush'),
('LawnGreen'),
('LemonChiffon'),
('LightBlue'),
('LightCoral'),
('LightCyan'),
('LightGoldenRodYellow'),
('LightGray'),
('LightGrey'),
('LightGreen'),
('LightPink'),
('LightSalmon'),
('LightSeaGreen'),
('LightSkyBlue'),
('LightSlateGray'),
('LightSteelBlue'),
('LightYellow'),
('Lime'),
('LimeGreen'),
('Linen'),
('Magenta'),
('Maroon'),
('MediumAquaMarine'),
('MediumBlue'),
('MediumOrchid'),
('MediumPurple'),
('MediumSeaGreen'),
('MediumSlateBlue'),
('MediumSpringGreen'),
('MediumTurquoise'),
('MediumVioletRed'),
('MidnightBlue'),
('MintCream'),
('MistyRose'),
('Moccasin'),
('NavajoWhite'),
('Navy'),
('OldLace'),
('Olive'),
('OliveDrab'),
('Orange'),
('OrangeRed'),
('Orchid'),
('PaleGoldenRod'),
('PaleGreen'),
('PaleTurquoise'),
('PaleVioletRed'),
('PapayaWhip'),
('PeachPuff'),
('Peru'),
('Pink'),
('Plum'),
('PowderBlue'),
('Purple'),
('Red'),
('RosyBrown'),
('RoyalBlue'),
('SaddleBrown'),
('Salmon'),
('SandyBrown'),
('SeaGreen'),
('SeaShell'),
('Sienna'),
('Silver'),
('SkyBlue'),
('SlateBlue'),
('SlateGray'),
('Snow'),
('SpringGreen'),
('SteelBlue'),
('Tan'),
('Teal'),
('Thistle'),
('Tomato'),
('Turquoise'),
('Violet'),
('Wheat'),
('White'),
('WhiteSmoke'),
('Yellow'),
('YellowGreen');

-- --------------------------------------------------------

--
-- Table structure for table `css_fonts`
--

CREATE TABLE `css_fonts` (
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `css_fonts`
--

INSERT INTO `css_fonts` (`name`) VALUES
('Lucida Console, Monaco, monospace'),
('Courier New, Courier, monospace'),
('Verdana, Geneva, sans-serif'),
('Trebuchet MS, Helvetica, sans-serif'),
('Tahoma, Geneva, sans-serif'),
('Lucida Sans Unicode, Lucida Grande, sans-serif'),
('Impact, Charcoal, sans-serif'),
('Comic Sans MS, cursive, sans-serif'),
('Arial Black, Gadget, sans-serif'),
('Arial, Helvetica, sans-serif'),
('Times New Roman, Times, serif'),
('Palatino Linotype, Book Antiqua, Palatino, serif'),
('Georgia, serif');

-- --------------------------------------------------------

--
-- Table structure for table `css_highlight_styles`
--

CREATE TABLE `css_highlight_styles` (
  `style_name` varchar(100) NOT NULL,
  `background_color` varchar(100) NOT NULL,
  `text_color` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `css_highlight_styles`
--

INSERT INTO `css_highlight_styles` (`style_name`, `background_color`, `text_color`) VALUES
('highlight-blue', 'lightblue', 'blue'),
('highlight-green', 'lightgreen', 'blue'),
('highlight-yellow', 'yellow', 'black'),
('highlight-grey', 'lightgrey', 'black'),
('highlight-red', 'red', 'white'),
('highlight-purple', 'thistle', 'black'),
('highlight-pink', 'lightpink', 'black'),
('text-purple', 'white', 'purple'),
('text-green', 'white', 'green'),
('text-blue', 'white', 'blue'),
('text-red', 'white', 'red'),
('text-grey', 'white', 'darkgrey'),
('highlight-turquoise', 'turquoise', 'black'),
('highlight-peach', 'peach', 'black'),
('highlight-khaki', 'khaki', 'black');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
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

CREATE TABLE `location_types` (
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
-- Dumping data for table `location_types`
--

INSERT INTO `location_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES
(1, 'real', 'Noted', 'Desc...', '2017-07-29 21:24:46', '2017-06-30 17:58:54', 'highlight-red', 1),
(2, 'imaginary', '', '', '2017-07-05 05:22:31', '2017-06-30 17:59:10', 'none', 2),
(3, 'dreamstate', '', 'Dreamscape', '2017-08-01 18:26:40', '2017-07-01 02:27:21', 'highlight-purple', 3),
(4, 'work', '', '', '2017-07-05 05:25:23', '2017-07-01 02:27:35', 'none', 4),
(5, 'home', 'Think about where', 'The place you call.', '2017-07-05 16:33:49', '2017-07-01 02:27:46', 'highlight-green', 5),
(6, 'workshop', '', 'Place to build things', '2017-07-31 16:47:20', '2017-07-01 02:28:06', 'none', 6),
(7, 'garden', '', '', '2017-07-01 02:28:20', '2017-07-01 02:28:20', '-no highlighting', 7),
(8, 'classroom', '', '', '2017-07-01 02:28:34', '2017-07-01 02:28:34', '-no highlighting', 8),
(9, 'studio', '', '', '2017-07-01 02:28:48', '2017-07-01 02:28:48', '-no highlighting', 9),
(10, 'library', '', '', '2017-07-01 02:29:00', '2017-07-01 02:29:00', '-no highlighting', 10),
(11, 'theater', '', 'Stage type of setting', '2017-07-31 16:45:49', '2017-07-01 02:29:17', 'highlight-pink', 11),
(12, 'stage', '', '', '2017-07-01 02:29:27', '2017-07-01 02:29:27', '-no highlighting', 12),
(13, 'process point', '', '', '2017-07-01 02:29:44', '2017-07-01 02:29:44', '-no highlighting', 13),
(14, 'storage', '', '', '2017-07-05 04:35:53', '2017-07-05 04:35:53', 'none', 14),
(15, 'vision art state', '', '', '2017-07-31 16:47:45', '2017-07-29 21:27:28', 'text-blue', 15);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
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

CREATE TABLE `material_types` (
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
-- Dumping data for table `material_types`
--

INSERT INTO `material_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES
(1, 'stuff', '', '', '2017-06-30 18:00:36', '2017-06-30 18:00:36', '-no highlighting', 1),
(2, 'things', '', '', '2017-06-30 18:00:47', '2017-06-30 18:00:47', '-no highlighting', 2),
(3, 'ideas', '', '', '2017-06-30 18:01:07', '2017-06-30 18:01:07', '-no highlighting', 3),
(4, 'food', '', '', '2017-07-29 23:25:26', '2017-07-29 23:25:26', 'none', 4),
(5, 'water', '', '', '2017-07-31 17:00:20', '2017-07-29 23:25:35', 'text-blue', 5),
(6, 'lumber', '', '', '2017-07-29 23:25:46', '2017-07-29 23:25:46', 'none', 6),
(7, 'hardware', '', '', '2017-07-29 23:25:59', '2017-07-29 23:25:59', 'none', 7),
(8, 'plumbingbsupplies', '', '', '2017-07-29 23:26:13', '2017-07-29 23:26:13', 'none', 8),
(9, 'tools', '', '', '2017-07-29 23:26:23', '2017-07-29 23:26:23', 'none', 9);

-- --------------------------------------------------------

--
-- Table structure for table `measures`
--

CREATE TABLE `measures` (
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

-- --------------------------------------------------------

--
-- Table structure for table `measure_types`
--

CREATE TABLE `measure_types` (
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
-- Dumping data for table `measure_types`
--

INSERT INTO `measure_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `display_order`, `highlight_style`) VALUES
(1, 'test', '', '', '2017-07-31 23:07:19', '2017-07-31 23:07:19', 1, 'none'),
(2, 'mass', '', '', '2017-07-31 23:17:25', '2017-07-31 23:07:30', 2, 'highlight-green'),
(3, 'volume', '', '', '2017-07-31 23:07:44', '2017-07-31 23:07:44', 3, 'none'),
(4, 'Temperature', '', '', '2017-07-31 23:14:06', '2017-07-31 23:07:56', 4, 'none'),
(5, 'density', '', '', '2017-07-31 23:11:34', '2017-07-31 23:11:34', 4, 'none'),
(6, 'distance', '', '', '2017-07-31 23:18:10', '2017-07-31 23:17:40', 6, 'highlight-turquoise'),
(7, 'quantity', '', '', '2017-07-31 23:17:53', '2017-07-31 23:17:53', 7, 'none');

-- --------------------------------------------------------

--
-- Table structure for table `measure_type_units`
--

CREATE TABLE `measure_type_units` (
  `id` bigint(20) NOT NULL,
  `measure_type_id` bigint(20) NOT NULL,
  `unit_measure_id` bigint(20) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_order` int(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `measure_type_units_v`
-- (See below for the actual view)
--
CREATE TABLE `measure_type_units_v` (
`id` bigint(20)
,`measure_type_unit_id` bigint(20)
,`measure_type_id` bigint(20)
,`unit_measure_id` bigint(20)
,`created` timestamp
,`updated` timestamp
,`measure_type` varchar(100)
,`unit_of_measure` varchar(100)
,`unit_type` varchar(100)
,`unit_symbol` varchar(20)
);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
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
-- Stand-in structure for view `project_status_v`
-- (See below for the actual view)
--
CREATE TABLE `project_status_v` (
`status` varchar(4)
,`projects` bigint(21)
,`project_tasks` bigint(21)
,`task_hours_estimated` decimal(32,2)
,`task_hours_actual` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `project_task_summary_v`
-- (See below for the actual view)
--
CREATE TABLE `project_task_summary_v` (
`project_id` bigint(20)
,`project_pct_done` decimal(10,2)
,`total_tasks` bigint(21)
,`sum_hours_estimated` decimal(32,2)
,`sum_hours_actual` decimal(32,2)
,`overall_pct_done` decimal(36,6)
);

-- --------------------------------------------------------

--
-- Table structure for table `project_types`
--

CREATE TABLE `project_types` (
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
-- Dumping data for table `project_types`
--

INSERT INTO `project_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES
(1, 'Gardening', '', 'Gardening project', '2017-07-29 15:05:03', '2013-01-20 17:31:03', 'text-green', 0),
(2, 'Construction', '', '', '2017-07-29 15:58:46', '2013-01-20 17:56:18', 'text-blue', 5),
(3, 'Repairs', '', '', '2017-07-29 15:59:20', '2013-01-20 17:56:30', 'text-red', 7),
(4, 'Maintenance/Cleanup', '', '', '2017-07-29 15:43:59', '2013-01-20 17:56:56', 'text-red', 7),
(5, 'Sustainability', '', 'Practical applications of sustainability concepts', '2017-07-29 15:57:47', '2013-01-20 17:57:47', 'text-green', 1),
(6, 'Food Preparation', '', 'Preparing food, meals, food supplies.', '2017-07-31 16:48:34', '2013-01-20 19:06:30', 'highlight-turquoise', 3),
(7, 'Research', '', '', '2017-07-29 15:48:41', '2013-01-20 19:17:26', 'text-blue', 2),
(8, 'Organization', '', '', '2017-07-29 15:59:42', '2013-01-20 19:51:46', 'text-blue', 4),
(9, 'Development', '', '', '2017-07-29 15:46:36', '2017-07-29 15:46:36', 'none', 9),
(10, 'Analysis and Review', '', '', '2017-07-29 15:49:26', '2017-07-29 15:49:26', 'none', 10),
(11, 'Analysis and Review', '', '', '2017-07-29 15:50:20', '2017-07-29 15:50:20', 'none', 10),
(12, 'Editing and Review', '', '', '2017-07-29 15:51:24', '2017-07-29 15:51:24', 'text-red', 12);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
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

CREATE TABLE `receipt_types` (
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
-- Dumping data for table `receipt_types`
--

INSERT INTO `receipt_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES
(1, 'donation', 'Notes\r\nTwo lines', 'Desc', '2017-07-31 17:01:10', '2017-06-30 18:01:33', 'highlight-yellow', 1),
(2, 'sale', '', '', '2017-07-31 17:01:40', '2017-06-30 18:01:44', 'highlight-red', 2),
(3, 'Loan', '', '', '2017-07-31 17:01:59', '2017-07-29 23:24:24', 'highlight-green', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sitewide_settings`
--

CREATE TABLE `sitewide_settings` (
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
-- Dumping data for table `sitewide_settings`
--

INSERT INTO `sitewide_settings` (`site_title`, `organization`, `contact_email`, `contact_name`, `site_url`, `organization_url`, `login_notice`, `organization_description`, `public_fill_color`, `public_site_color`, `public_page_color`, `public_menu_color`, `public_menu_color_hover`, `public_text_color`, `public_text_color_hover`, `public_font_family`, `public_font_size_title`, `public_font_size_menu`, `public_font_size_text`, `public_font_size_heading`, `show_public_site`, `show_cost_reports`, `show_revenue_reports`) VALUES
('Dev projects', 'Taradorje', 'Me@mine', 'That\'s me', 'Site.org', 'Mine.org', 'Yo mofo', 'Described', '', '', 'red', '', '', 'black', '', 'Verdana, sans-serif', 18, 12, 14, 16, 'yes', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
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

CREATE TABLE `task_types` (
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
-- Dumping data for table `task_types`
--

INSERT INTO `task_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `frequency`, `display_order`) VALUES
(1, 'Recurring - Daily', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%.', '2017-07-31 16:58:44', '2013-01-21 16:56:57', 'highlight-green', 'daily', 11),
(2, 'Recurring - Weekly', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%', '2013-01-22 17:51:00', '2013-01-21 16:57:14', 'none', 'weekly', 12),
(3, 'Recurring - Monthly', 'Default actual hours for a new activity under the task is determined by the task estimated hours.  If a task should take six minutes, set the estimated effort to 0.10 hours on the task...then everytime an activity is added to the task it is set to 0.10 hours.', 'Recurring task.  Task Percent Complete never set to 100%.', '2013-01-22 17:51:18', '2013-01-21 16:57:31', 'none', 'monthly', 13),
(4, 'Recipe', 'Multiple recipes can be grouped together under a project to save how to do the recipe and to record activity when the recipe is made.', 'Task details a recipe.', '2017-07-29 21:36:41', '2013-01-21 16:57:54', 'BlueViolet', 'no', 2),
(5, 'Process', 'Activities under task show each time process was completed.', 'Task details a process.', '2013-01-24 22:12:35', '2013-01-21 16:58:13', 'none', 'no', 3),
(7, 'Activity', 'Use estimated effort to set default value for actual effort on activities reported under the task.', 'Single occurrence task.  When marked complete, task contributes to project overall complete status.', '2017-07-31 16:58:04', '2013-01-21 17:08:12', 'highlight-purple', 'none', 1),
(6, 'Research', 'Create separate tasks for different topic areas under a general research project.  Activities under a research task can save one link to a website.  To store multiple links use multiple activity records.', 'Research into a topic area.', '2013-01-22 17:50:36', '2013-01-21 17:00:23', 'none', 'no', 4),
(8, 'Recurring - Quarterly', '', '', '2013-01-22 17:51:30', '2013-01-22 17:49:25', 'none', 'quarterly', 14),
(9, 'Recurring - Yearly', '', '', '2013-01-22 18:17:56', '2013-01-22 18:17:56', 'none', 'annual', 15),
(10, 'As Needed', '', 'Task should be done on an as needed basis.  Does not show up in periodic task views.', '2013-01-22 22:09:42', '2013-01-22 22:08:47', 'none', 'no', 5),
(11, 'Purchasing', '', '', '2013-01-23 17:39:44', '2013-01-23 17:39:44', 'none', 'no', 0),
(12, 'Baking', '', '', '2017-07-29 21:37:57', '2017-07-29 21:37:07', 'CornflowerBlue', 'weekly', 12),
(13, 'Budget Planning', '', '', '2017-07-29 21:39:17', '2017-07-29 21:39:17', 'Aquamarine', 'monthly', 13);

-- --------------------------------------------------------

--
-- Table structure for table `task_type_frequencies`
--

CREATE TABLE `task_type_frequencies` (
  `frequency` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `display_order` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task_type_frequencies`
--

INSERT INTO `task_type_frequencies` (`frequency`, `description`, `display_order`) VALUES
('no', 'Not Periodic', 1),
('daily', 'Periodic Daily', 2),
('weekly', 'Periodic Weekly', 3),
('monthly', 'Periodic Monthly', 4),
('quarterly', 'Periodic Quarterly', 5),
('annual', 'Periodic Annually', 6);

-- --------------------------------------------------------

--
-- Table structure for table `units_of_measure`
--

CREATE TABLE `units_of_measure` (
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
-- Dumping data for table `units_of_measure`
--

INSERT INTO `units_of_measure` (`id`, `type`, `name`, `updated`, `created`, `notes`, `symbol`, `description`, `display_order`, `highlight_style`) VALUES
(1, 'temperature', 'degrees celcius', '2012-12-08 23:09:34', '0000-00-00 00:00:00', '', 'Â°C', NULL, 0, 'none'),
(2, 'temperature', 'degrees fahrenheit', '2012-12-08 23:10:12', '0000-00-00 00:00:00', '', 'Â°F', NULL, 0, 'none'),
(3, 'length', 'meter', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'm', NULL, 0, 'none'),
(4, 'length', 'feet', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'ft', NULL, 0, 'none'),
(5, 'concentration', 'ppm', '2012-11-24 00:32:25', '0000-00-00 00:00:00', '', 'ppm', NULL, 0, 'none'),
(6, 'pH', 'pH', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'pH', NULL, 0, 'none'),
(7, 'concentration', 'percent', '2013-01-07 20:55:14', '0000-00-00 00:00:00', '', '%', NULL, 0, 'none'),
(8, 'length', 'centimeter', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'cm', NULL, 0, 'none'),
(9, 'time', 'hours', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 'h', NULL, 0, 'none'),
(10, 'time', 'seconds', '2012-11-24 00:33:44', '0000-00-00 00:00:00', '', 's', NULL, 0, 'none'),
(11, 'time', 'days', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'd', NULL, 0, 'none'),
(12, 'mass', 'kilogram', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'kg', NULL, 0, 'none'),
(13, 'mass', 'pound', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'lb', NULL, 0, 'none'),
(14, 'mass', 'ounce', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'oz', NULL, 0, 'none'),
(15, 'mass', 'gram', '2012-11-24 00:35:17', '0000-00-00 00:00:00', '', 'gm', NULL, 0, 'none'),
(16, 'length', 'inches', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'in', NULL, 0, 'none'),
(17, 'frequency', 'Hertz', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'Hz', NULL, 0, 'none'),
(18, 'light', 'lumens', '2012-11-24 00:37:13', '0000-00-00 00:00:00', '', 'lu', NULL, 0, 'none'),
(19, 'scalar', 'Scale', '2012-12-08 23:11:50', '2012-12-08 23:04:34', '', '1-10', NULL, 0, 'none'),
(20, 'volume', 'cup', '2013-01-21 04:01:15', '2013-01-21 03:59:12', 'One Cup = 8 oz', 'c', NULL, 0, 'none'),
(21, 'Volume', 'Pint', '2013-01-21 03:59:45', '2013-01-21 03:59:45', '', 'pt', NULL, 0, 'none'),
(22, 'Volume', 'Quart', '2013-01-21 04:00:25', '2013-01-21 04:00:25', '', 'qt', NULL, 0, 'none'),
(23, 'volume', 'gallon', '2013-01-21 04:00:46', '2013-01-21 04:00:46', '', 'ga', NULL, 0, 'none'),
(24, 'volume', 'tablespoon', '2013-01-21 04:01:37', '2013-01-21 04:01:37', '', 'tbl', NULL, 0, 'none');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
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
  `is_active` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'yes'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name_first`, `name_last`, `email`, `focus`, `interests`, `login_name`, `login_pwd`, `last_login`, `created`, `updated`, `type_id`, `is_admin`, `must_update_pwd`, `is_active`) VALUES
(1, 'anthony', 'harper', 'gaiansentience@gmail.com', 'technical support', '', 'aharper', '', '2017-08-01 18:09:27', NULL, '2017-08-01 18:09:27', 0, 'yes', 'no', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
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
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `name`, `notes`, `description`, `updated`, `created`, `highlight_style`, `display_order`) VALUES
(1, 'basic', '', '', '2017-06-30 18:03:01', '2017-06-30 18:03:01', '-no highlighting', 1),
(2, 'advanced', '', '', '2017-06-30 18:03:17', '2017-06-30 18:03:17', '-no highlighting', 2),
(3, 'honorary', '', '', '2017-07-30 00:09:56', '2017-07-30 00:09:56', 'none', 3),
(4, 'Staff', '', '', '2017-07-30 00:10:32', '2017-07-30 00:10:06', 'highlight-peach', 4);
<br />
<b>Fatal error</b>:  Uncaught Error: Call to a member function has() on null in /storage/emulated/legacy/ksweb/tools/phpMyAdmin/libraries/plugins/export/ExportSql.php:1582
Stack trace:
#0 /storage/emulated/legacy/ksweb/tools/phpMyAdmin/libraries/plugins/export/ExportSql.php(2073): PMA\libraries\plugins\export\ExportSql-&gt;getTableDef('projects', 'crop_plantings_...', '\n', 'db_export.php?d...', false, true, true, true, Array)
#1 /storage/emulated/legacy/ksweb/tools/phpMyAdmin/libraries/export.lib.php(730): PMA\libraries\plugins\export\ExportSql-&gt;exportStructure('projects', 'crop_plantings_...', '\n', 'db_export.php?d...', 'create_view', 'database', false, true, false, false, Array)
#2 /storage/emulated/legacy/ksweb/tools/phpMyAdmin/export.php(462): PMA_exportDatabase('projects', Array, 'structure_and_d...', Array, Array, Object(PMA\libraries\plugins\export\ExportSql), '\n', 'db_export.php?d...', 'database', false, true, false, false, Array, '')
#3 {main}
  thrown in <b>/storage/emulated/legacy/ksweb/tools/phpMyAdmin/libraries/plugins/export/ExportSql.php</b> on line <b>1582</b><br />
