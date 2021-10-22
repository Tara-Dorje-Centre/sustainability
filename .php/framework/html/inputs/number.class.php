<?php
namespace html\inputs;

class number extends __inputCaptioned{
use dimensionsSize, min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-number'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setDimensions(12, 4);
		$this->setValue($value);

	}
	
}

class id extends number{

}



?>
