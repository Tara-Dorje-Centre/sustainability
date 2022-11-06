<?php
namespace html\inputs;

class hidden extends __input{
	private $name;
	private $value;
	public function __construct($name, $value){
		$css = 'editing-input-hidden';
		parent::__construct('hidden',$name,$css);
		$this->setValue($value);
		
		$this->name = $name;
		$this->value = $value;
	}
	public function print(){
		$input = parent::print();
		//show values during development
		$input .= $this->name.'['.$this->value.']';
		return $input;

	}
}


?>
