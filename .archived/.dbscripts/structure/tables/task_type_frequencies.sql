
CREATE TABLE IF NOT EXISTS task_type_frequencies (
  frequency varchar(100) NOT NULL,
  description varchar(100) NOT NULL,
  display_order int(4) NOT NULL,
  PRIMARY KEY (frequency)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

