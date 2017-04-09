SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- Table structure for table 'tasks'

CREATE TABLE IF NOT EXISTS tasks (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  project_id bigint(20) NOT NULL,
  location_id bigint(20) NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  description varchar(1000) COLLATE latin1_general_ci NOT NULL,
  summary varchar(1000) COLLATE latin1_general_ci NOT NULL,
  started timestamp NULL DEFAULT NULL,
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  pct_done decimal(10,2) NOT NULL DEFAULT '0.00',
  task_order decimal(10,2) NOT NULL DEFAULT '1.00',
  parent_id bigint(20) NOT NULL DEFAULT '0',
  hours_estimated decimal(10,2) NOT NULL DEFAULT '0.00',
  hours_actual decimal(10,2) NOT NULL DEFAULT '0.00',
  hours_notes varchar(500) COLLATE latin1_general_ci NOT NULL,
  type_id bigint(12) NOT NULL DEFAULT '0',
  materials_auth_project varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  materials_auth_by varchar(50) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

ALTER TABLE tasks  
ADD receipts_auth_project VARCHAR(10) NOT NULL DEFAULT 'no',  
ADD receipts_auth_by VARCHAR(50) NOT NULL DEFAULT 'Not Authorized';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
