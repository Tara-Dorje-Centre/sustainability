<?php
namespace html\inputs;

class selectYear extends selectRange{
	public function __construct($name, $value, $year, $caption = 'YYYY'){
		parent::__construct($name,$value, $caption);
		$min = $year - 5;
		$max = $year + 5;
		$this->setRange($min,$max,1,4);
	}
}

class selectMonth extends selectRange{
	public function __construct($name, $value, $caption = 'MM'){
		parent::__construct($name,$value, $caption);
		$min = 1;
		$max = 12;
		$this->setRange($min,$max,1,2);
	}
}

class selectDay extends selectRange{
	public function __construct($name, $value, $caption = 'DD'){
		parent::__construct($name,$value, $caption);
		$min = 1;
		$max = 31;
		$this->setRange($min,$max,1,2);
	}
}

class selectHour extends selectRange{
	public function __construct($name, $value, $caption = 'hh'){
		parent::__construct($name,$value, $caption);
		$min = 0;
		$max = 23;
		$this->setRange($min,$max,1,2);
	}
}

class selectMinute extends selectRange{
	public function __construct($name, $value, $caption = 'mm'){
		parent::__construct($name,$value, $caption);
		$min = 0;
		$max = 59;
		$this->setRange($min,$max,1,2);
	}
}

?>
