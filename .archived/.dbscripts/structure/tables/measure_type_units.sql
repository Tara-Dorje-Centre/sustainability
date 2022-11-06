
CREATE TABLE IF NOT EXISTS measure_type_units (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  measure_type_id bigint(20) NOT NULL,
  unit_measure_id bigint(20) NOT NULL,
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  created timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id),
  UNIQUE KEY mtu_idx_01 (measure_type_id,unit_measure_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

ALTER TABLE `measure_type_units`  ADD `display_order` INT(4) NULL DEFAULT '0'  AFTER `created`;
