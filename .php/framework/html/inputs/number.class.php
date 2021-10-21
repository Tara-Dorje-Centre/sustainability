<?php
namespace html\inputs;

/*
class _inputNumber extends _inputText{
	public function __construct($name, $value, $caption, $css = 'editing-input-number'){
		parent::__construct($name,$value,$caption, $css);
		$this->setDimensions(12,4);
	}
}
*/

//class _inputNumber extends __inputCaptioned{
class number extends __inputCaptioned{
use tooltip, disabled, dimensionsSize, min, max, step;
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
