<?php
namespace html;

class _attribute{
	public $name;
	public $value;
	protected const DQ = '"';
	protected const EQ = '=';
	protected const SP = ' ';
	public function __construct(string $name,string $value){
		$this->set($name,$value);
	}
	protected function set(string $name,string $value){
		$this->name = $name;
		$this->value = $value;
	}
	public function isValid(){
		$valid = true;
		//dont allow attributes named none
		if ($this->name == 'none'){
			$valid = false;
		}
		//dont allow css class attribute named none
		if (($this->name == 'class') and ($this->value == 'none')){
			$valid = false;
		}
		return $valid;
	}
	public function print(){
		$a = '';
		if ($this->isValid() == true){
			$a = self::SP.$this->name.self::EQ;
			$a .= self::DQ.$this->value.self::DQ;
		}
		return $a;
	}
}
?>
