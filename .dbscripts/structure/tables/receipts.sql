
CREATE TABLE IF NOT EXISTS receipts (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id bigint(20) NOT NULL,
  activity_id bigint(20) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  description varchar(1000) COLLATE latin1_general_ci NOT NULL,
  date_reported timestamp NULL DEFAULT '0000-00-00 00:00:00',
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  quantity double(10,2) NOT NULL DEFAULT '1.00',
  qty_unit_measure_id bigint(20) NOT NULL DEFAULT '0',
  cost_unit double(10,2) NOT NULL DEFAULT '0.00',
  cost_actual double(10,2) NOT NULL DEFAULT '0.00',
  notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  received_by varchar(100) COLLATE latin1_general_ci NOT NULL,
  type_id bigint(20) NOT NULL DEFAULT '0',
  received_from varchar(100) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;
