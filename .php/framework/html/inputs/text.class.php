<?php
namespace html\inputs;

class text extends __inputCaptioned{
use dimensionsSize;
	public function __construct($name, $value, $caption, $css = 'editing-input-text'){
		parent::__construct('text',$name, $caption, $css);
		//setDefaultDimenions
		$this->setDimensions(100, 20);
		$this->setValue($value);

	}
	
}





?>
