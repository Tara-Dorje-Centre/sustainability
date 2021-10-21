<?php
namespace html\inputs;
class comments extends textArea{

	public function __construct($name,$value,$caption){
		$css = 'editing-input-comments';
		parent::__construct($name, $value, $caption, $css);
		$this->setDimensions(1000,4,60);
	}
}

?>
