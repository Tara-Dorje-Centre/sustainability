
CREATE TABLE IF NOT EXISTS users (
  id bigint(12) NOT NULL AUTO_INCREMENT,
  name_first varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  name_last varchar(100) COLLATE latin1_general_ci NOT NULL,
  email varchar(255) COLLATE latin1_general_ci NOT NULL,
  focus varchar(4000) COLLATE latin1_general_ci DEFAULT NULL,
  interests varchar(4000) COLLATE latin1_general_ci NOT NULL,
  login_name varchar(50) COLLATE latin1_general_ci NOT NULL,
  login_pwd varchar(1000) COLLATE latin1_general_ci NOT NULL,
  last_login timestamp NULL DEFAULT NULL,
  created timestamp NULL DEFAULT NULL,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  type_id bigint(20) NOT NULL DEFAULT '0',
  is_admin varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  must_update_pwd varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'no',
  is_active varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (id),
  UNIQUE KEY login_name (login_name),
  UNIQUE KEY email (email)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

