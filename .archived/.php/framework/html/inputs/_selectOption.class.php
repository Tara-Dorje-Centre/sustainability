<?php
namespace html\inputs;

class _selectOption extends \html\_element{
	protected $value;
	protected $display;
	public function __construct($value,string $display,bool $selected = false){
		parent::__construct('option');
		$this->value = $value;
		$this->makeAttribute('value',$this->value);
		$this->setContent($display);
		$this->setSelected($selected);
	}
	public function setSelected(bool $selected = false){
		if ($selected == true){
			$this->makeAttribute('selected','selected');
		}
	}
	public function isSelected($selectedValue){
		if ($selectedValue == $this->value){
			$this->setSelected(true);
		}
	
	}
}
?>
