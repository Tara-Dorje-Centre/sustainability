<?php 
namespace framework\sql;

class column{
	public $name;
	public $qualifier;
	public $alias;
	public $value;
	public $operator = '=';
	public function __construct($name,$qualifier,$alias){
		$this->name = $name;
		$this->qualifier = $qualifier;
		$this->alias = $alias;
	}
	public function set($value,$operator = '='){
		$this->value = $value;
		$this->operator = $operator;
	}
	public function parentheses($content){
		return '('.$content.')';
	}
	public function qualified(bool $showAlias = false){
	
		if (!is_null($this->qualifier)){
			$name = $this->qualifier.'.'.$this->name;
		} else {
			$name = $this->name;
		}
		if ((!is_null($this->alias)) and ($showAlias == true)){
			$name .= ' as '.$this->alias;
		}
		return $name;
	}
	public function select(){
		return $this->qualified(true);
	}
	public function update(){

		$set = $this->qualified(false);
		$set .= $this->operator;
		$set .= $this->value;
		return $set;
		
	}
	protected function insertColumn(){
		return $this->name;
	}
	protected function insertValue(){
		return $this->value;
	}
	protected function where(){
	//$operator = '='){
		//$this->operator = $operator;
		$compare = $this>qualified(false);
		$compare .= $this->operator;
		$compare .= $this->value;
		return $this->parentheses($compare);
	}
	protected function orderBy(){
		return $this->qualified(false);
	}
	protected function groupBy(){
		return $this->qualified(false);
	}
	public function print($type = 'SELECT'){
		switch($type){
			case 'WHERE':
				$result = $this->where();
				break;
			case 'UPDATE':
				$result = $this->update();
				break;
			case 'INSERT-VALUE':
				$result = $this->insertValue();
				break;
			case 'INSERT-COLUMN':
				$result = $this->insertColumn();
				break;
			case 'SELECT':
				$result = $this->select();
				break;
			case 'ORDER-BY':
				$result = $this->orderBy();
				break;
			case 'GROUP-BY':
				$result = $this->groupBy();
				break;
			default:
				
		}
		return $result;
	}
	
}

 
?>
