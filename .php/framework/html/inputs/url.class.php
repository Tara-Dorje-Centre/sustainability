<?php
namespace html\inputs;

class url extends __inputCaptioned{
use dimensionsSize;
	public function __construct($name, $value, $caption, $css = 'editing-input-url'){
		parent::__construct('url',$name, $caption, $css);
		//setDefaultDimenions
		$this->setDimensions(255, 50);
		$this->setValue($value);

	}
	
}





?>
