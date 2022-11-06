CREATE TABLE IF NOT EXISTS activities (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id bigint(20) NOT NULL,
  done_by varchar(50) COLLATE latin1_general_ci NOT NULL,
  started timestamp NULL DEFAULT '0000-00-00 00:00:00',
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  activity_order decimal(10,2) NOT NULL DEFAULT '1.00',
  hours_estimated decimal(10,2) NOT NULL,
  hours_actual decimal(10,2) NOT NULL,
  comments varchar(500) COLLATE latin1_general_ci NOT NULL,
  link_url varchar(1000) COLLATE latin1_general_ci NOT NULL,
  link_text varchar(100) COLLATE latin1_general_ci NOT NULL,
  type_id bigint(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;
