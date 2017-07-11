

CREATE TABLE IF NOT EXISTS units_of_measure (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  created timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  symbol varchar(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

