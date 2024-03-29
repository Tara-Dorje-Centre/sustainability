<?php
namespace html\inputs;

class textarea extends \html\_element{
use valueContent, caption, validation, tooltip, disabled, dimensionsRows;
	public function __construct($name, $value, $caption,$css = 'editing-input-textarea'){
		parent::__construct('textarea',$name, $css);
		//set default dimensions
		$this->setDimensions(500,4,60);
		$this->setValue($value);
		$this->setCaption($caption);
	}
	
	public function print(){
		$d= new \html\_div('none','edit-input');
		$d->addContent($this->printCaption());
		$d->addContent($this->printValidation());
		$d->addContent(parent::print());
		return $d->print();
	}
	
	public function printNoCaption(){
		return parent::print();
	}
	
}
?>
