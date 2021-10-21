<?php
namespace html\inputs;

/*
class url extends _inputText{
	public function __construct($name, $value, $caption){
		$css = 'editing-input-url';
		parent::__construct($name, $value, $caption, $css);
		$this->setDimensions(500, 50);
	}
}
*/


class url extends __inputCaptioned{
use tooltip, disabled, dimensionsSize;
	public function __construct($name, $value, $caption, $css = 'editing-input-url'){
		parent::__construct('url',$name, $caption, $css);
		//setDefaultDimenions
		$this->setDimensions(500, 50);
		$this->setValue($value);

	}
	
}





?>
