<?php
namespace html\inputs;


class selectRange extends _select{
	
	public function __construct($name, $value, $caption, $onchangeJS = null){
		parent::__construct($name,$value, $caption,$onChangeJS);
		
	}
	public function setRange($start = 1,$max = 1,$step = 1,$pad = 0){
		$i = $start;
		while ($i <= $max){
			$value = str_pad($i,$pad,'0',STR_PAD_LEFT);
			$this->makeOption($value, $value);
			$i += $step;
		}
	}

}




?>
