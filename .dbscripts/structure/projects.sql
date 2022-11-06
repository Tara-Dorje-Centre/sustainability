SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


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

CREATE TABLE `crop_plan_types` (
  `id` bigint(20) NOT NULL,
  `plan_type` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `css_colors` (
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `css_fonts` (
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `css_highlight_styles` (
  `style_name` varchar(100) NOT NULL,
  `background_color` varchar(100) NOT NULL,
  `text_color` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

CREATE TABLE `measure_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_order` int(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `measure_type_units` (
  `id` bigint(20) NOT NULL,
  `measure_type_id` bigint(20) NOT NULL,
  `unit_measure_id` bigint(20) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_order` int(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
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
CREATE TABLE `project_status_v` (
`status` varchar(4)
,`projects` bigint(21)
,`project_tasks` bigint(21)
,`task_hours_estimated` decimal(32,2)
,`task_hours_actual` decimal(32,2)
);
CREATE TABLE `project_task_summary_v` (
`project_id` bigint(20)
,`project_pct_done` decimal(10,2)
,`total_tasks` bigint(21)
,`sum_hours_estimated` decimal(32,2)
,`sum_hours_actual` decimal(32,2)
,`overall_pct_done` decimal(36,6)
);

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

CREATE TABLE `task_type_frequencies` (
  `frequency` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `display_order` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `units_of_measure` (
  `id` bigint(20) NOT NULL,
  `type` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notes` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `symbol` varchar(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

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
<br />
<b>Fatal error</b>:  Call to a member function has() on null in <b>/storage/emulated/legacy/ksweb/tools/phpMyAdmin/libraries/plugins/export/ExportSql.php</b> on line <b>1582</b><br />
