<?php 
namespace framework\sql;


class where extends clauseColumns{
	protected $term = 'WHERE';
	protected $prefix = ' AND ';
	protected $printMode = 'WHERE';
	public function __construct(){
		parent::__construct('WHERE');
	}
	public function compare($col,$value,$operator = '='){
		$c = new column($col);
		$c->set($value,$operator);
		$this->addCol($c);
	}
	public function compareColumns($colLeft, $colRight, $operator = '='){
		$c =new column($colLeft);
		$c->set($colRight,$operator);
		$this->addCol($c);
	}
	
}


?>
