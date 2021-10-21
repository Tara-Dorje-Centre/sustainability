<?php
namespace html\inputs;

class description extends textArea{

	public function __construct($name,$value,$caption){
		$css = 'editing-input-description';
		parent::__construct($name, $value, $caption, $css);
		$this->setDimensions(1000,4,60);
	}
}

?>
