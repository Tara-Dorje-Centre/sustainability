<?php
namespace html\inputs;


class email extends __inputCaptioned{
use tooltip, disabled, dimensionsSize;
	public function __construct($name, $value, $caption, $css = 'editing-input-email'){
		parent::__construct('email',$name, $caption, $css);
		//setDefaultDimenions
		$this->setDimensions(150, 50);
		$this->setValue($value);

	}
	
}

?>
