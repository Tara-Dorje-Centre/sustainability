<?php
namespace html\inputs;

class selectFontSize extends selectRange{
	public function __construct($name, $value, $caption, $onChangeJS = null){
		parent::__construct($name,$value, $caption, $onChangeJS);
		$this->defaultOption(0,'-no font size');
		$this->setRange(7,40,1,2);
	}
}

?>
