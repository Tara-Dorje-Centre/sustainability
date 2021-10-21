<?php
namespace html\inputs;
class notes extends textArea{

	public function __construct($name,$value,$caption){
		$css = 'editing-input-notes';
		parent::__construct($name, $value, $caption, $css);
		$this->setDimensions(1000,4,60);
	}
}

?>
