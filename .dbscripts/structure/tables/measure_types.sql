
CREATE TABLE IF NOT EXISTS measure_types (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  notes varchar(1000) COLLATE latin1_general_ci NOT NULL,
  description varchar(500) COLLATE latin1_general_ci NOT NULL,
  updated timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  created timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

ALTER TABLE `measure_types`  ADD `display_order` INT(4) NULL DEFAULT '0'  AFTER `created`;

ALTER TABLE `measure_types` ADD `highlight_style` VARCHAR(100) NOT NULL DEFAULT 'none' AFTER `display_order`;

