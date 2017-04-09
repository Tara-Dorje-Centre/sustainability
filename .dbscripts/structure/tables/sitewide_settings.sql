SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS sitewide_settings (
  site_title varchar(100) NOT NULL,
  organization varchar(1000) NOT NULL,
  contact_email varchar(100) NOT NULL,
  contact_name varchar(100) NOT NULL,
  site_url varchar(1000) NOT NULL,
  organization_url varchar(255) NOT NULL,
  login_notice varchar(1000) NOT NULL DEFAULT '',
  organization_description varchar(4000) NOT NULL,
  public_fill_color varchar(50) NOT NULL,
  public_site_color varchar(50) NOT NULL,
  public_page_color varchar(50) NOT NULL,
  public_menu_color varchar(50) NOT NULL,
  public_menu_color_hover varchar(50) NOT NULL,
  public_text_color varchar(50) NOT NULL,
  public_text_color_hover varchar(50) NOT NULL,
  public_font_family varchar(100) NOT NULL DEFAULT 'Verdana, sans-serif',
  public_font_size_title int(2) NOT NULL DEFAULT '18',
  public_font_size_menu int(2) NOT NULL DEFAULT '12',
  public_font_size_text int(2) NOT NULL DEFAULT '14',
  public_font_size_heading int(2) NOT NULL DEFAULT '16',
  show_public_site varchar(10) NOT NULL DEFAULT 'no',
  show_cost_reports varchar(10) NOT NULL DEFAULT 'no',
  show_revenue_reports varchar(10) NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
