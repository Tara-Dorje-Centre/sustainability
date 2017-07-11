
CREATE TABLE IF NOT EXISTS measures (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id bigint(20) NOT NULL,
  location_id bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  description varchar(1000) COLLATE latin1_general_ci NOT NULL,
  date_reported timestamp NULL DEFAULT '0000-00-00 00:00:00',
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `value` float NOT NULL,
  measure_type_unit_id bigint(20) NOT NULL,
  notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

