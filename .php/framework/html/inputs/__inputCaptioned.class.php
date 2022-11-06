<?php
namespace html\inputs;

abstract class __inputCaptioned extends __input{
use  caption, validation;

	public function __construct(string $type, string $name, string $caption,string $css = 'editing-input'){
		parent::__construct($type, $name, $css);
		$this->setCaption($caption);
	}
	
	public function print(){
		$i = new \html\_div('none','edit-input');
		$i->addContent($this->printCaption());
		$i->addContent($this->printValidation());
		$i->addContent(parent::print());
		return $i->print();
	}
	
	public function printNoCaption(){
		return parent::print();
	}

}
?>
