<?php 
namespace framework\sql;


class clauseColumns extends clause{
	protected $hasCols = false;
	protected $cols = array();
	protected $term = 'SELECT';
	protected $colPrefix = ', ';
	protected $printMode = 'SELECT';
	public function __construct($type){
		parent::__construct($type);
	}
	public function addCol(column $c){
		$this->hasCols = true;
		$this->cols[] = $c;
	}
	public function addColumn($name,$qualifier,$alias){
		$c = new column($name,$qualifier,$alias);
		$this->addCol($c);
	}
	public function addEditColumn($name,$value,$qualifier=null){
		$c = new column($name,$qualifier);
		$c->set($value);
		$this->addCol($c);
	}
	protected function printItem($c,$first){
		if ($first == false){
			$this->addContent($this->prefix);
		}
		$this->addContent($c->print($this->printMode));
	
	}
	protected function printCols(){
		$first = true;
		foreach($this->cols as $c){
			$this->printItem($c,$first);
			if($first == true){
				$first = false;
			}
		}
	}
	public function print(){
		if ($this->hasCols == true){
		$this->addContent(' '.$this->term.' ');
		$this->printCols();
		} else {
		$this->resetContent();
		}
		return $this->getContent();
	}
}



?>
