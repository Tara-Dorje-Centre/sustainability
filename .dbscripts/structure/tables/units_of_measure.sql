

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
ALTER TABLE `units_of_measure`  ADD `display_order` INT(4) NULL DEFAULT '0'  AFTER `created`;
ALTER TABLE `units_of_measure` ADD `description` VARCHAR(500) NULL AFTER `symbol`;
ALTER TABLE `units_of_measure` ADD `highlight_style` VARCHAR(100) NOT NULL DEFAULT 'none' AFTER `display_order`;
