

CREATE TABLE IF NOT EXISTS css_highlight_styles (
  style_name varchar(100) NOT NULL,
  background_color varchar(100) NOT NULL,
  text_color varchar(100) NOT NULL,
  PRIMARY KEY (style_name)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

