<?php
namespace html\inputs;

class timestamp extends \html\_div{
use caption, validation, tooltip;
	protected $_ts;
	protected $year;
	protected $currentYear;
	protected $month;
	protected $date;
	protected $hour;
	protected $minute;
	public function __construct($name,$value,$caption){
		$css = 'editing-input-timestamp';
		parent::__construct($name,$css);
		$this->setCaption($caption);
		$this->setParts($value);
		$this->makeSelectParts($name);
	}
	protected function setParts($value){
		$this->_ts = new \framework\_timestamp($value);
		
		if ($this->_ts->getYear() == '0000'){
			global $sessionTime;
			$current = new \framework\_timestamp($sessionTime);
			$this->currentYear = $current->getYear();
			
		} else {
			$this->currentYear = $this->_ts->getYear();
		}
	}
	protected function makeSelectParts($name){
		$year = new selectYear($name.'_YYYY',$this->_ts->year,$this->currentYear);
		$month = new selectMonth($name.'_MM',$this->_ts->month);
		$date = new selectDay($name.'_DD',$this->_ts->date);
		$hour = new selectHour($name.'_HH',$this->_ts->hour);
		$minute = new selectMinute($name.'_mm',$this->_ts->minute);
		$this->addContent($year->print());
		$this->addContent($month->print());
		$this->addContent($date->print());
		$this->addContent($hour->print());
		$this->addContent($minute->print());
	}
	public function print(){
		$d= new \html\_div('none','edit-input');
		$d->addContent($this->printCaption());
		$d->addContent($this->printValidation());
		$d->addContent(parent::print());
		return $d->print();
	}

}


?>
