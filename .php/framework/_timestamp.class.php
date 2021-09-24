<?php
namespace framework;

class _timestamp{
	public $timestamp;
	public $year;
	public $month;
	public $date;
	public $hour;
	public $minute;
	public function __construct($value = NULL){
		if(!is_null($value)){
			$this->set($value);
		}
	}
	public function set($value){
		$this->timestamp = $value;
		//timestamp format 
		//0000-00-00 00:00:00
		//0123456789012345678
		//0000-55-88-11-44-77
		$this->year = substr($value,0,4);
		$this->month = substr($value,5,2);
		$this->date = substr($value,8,2);
		$this->hour = substr($value,11,2);
		$this->minute = substr($value,14,2);
	}
	public function setParts($year = '0000',$month = '00',$date = '00',$hour = '00', $minute = '00'){
	$this->year = $year;
	$this->month = $month;
	$this->date = $date;
	$this->hour = $hour;		
	$this->minute = $minute;
	$this->makeTimestamp();
	}		
	public function makeTimestamp(){
		$this->timestamp = $this->year.'-'.$this->month.'-'.$this->date.' '.$this->hour.':'.$this->minute.':00';
	}
	public function getTimestamp(){
		$this->makeTimestamp();
		return $this->timestamp;
	}
	protected function trimValue($value,bool $trim){
		if ($trim == true){
			$value = ltrim($value,'0');
		}
		return $value;
	}
	public function getYear(bool $trim = false){
		$value = $this->year;
		return $this->trimValue($value,$trim);	
	}
	public function getMonth(bool $trim = false){
		$value = $this->month;
		return $this->trimValue($value,$trim);	
	}
	public function getDate(bool $trim = false){
		$value = $this->date;
		return $this->trimValue($value,$trim);	
	}
	public function getHour(bool $trim = false){
		$value = $this->hour;
		return $this->trimValue($value,$trim);	
	}
	public function getMinute(bool $trim = false){
		$value = $this->minute;
		return $this->trimValue($value,$trim);	
	}
}

class timestamp extends _timestamp{
}
	
?>
