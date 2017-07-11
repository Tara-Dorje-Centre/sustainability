
CREATE TABLE IF NOT EXISTS user_types (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  description varchar(500) COLLATE latin1_general_ci NOT NULL,
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  created timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  highlight_style varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'none',
  display_order int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

