<?php
namespace html\inputs;


class name extends text{
	public function __construct($name, $value, $caption){
		$css = 'editing-input-name';
		parent::__construct($name, $value, $caption, $css);
		$this->setDimensions(100, 25);
	}
}


?>
