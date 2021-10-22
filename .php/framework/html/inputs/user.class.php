<?php
namespace html\inputs;


class user extends text{
	public function __construct($name, $value, $caption){
		$css = 'editing-input-user';
		parent::__construct($name,$value, $caption,$css);
		$this->setDimensions(30, 20);
	}
}

?>
