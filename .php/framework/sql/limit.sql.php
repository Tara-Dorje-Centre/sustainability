<?php 
namespace framework\sql;


class limit extends clause{
	protected $page = 1;
	protected $rows = 10;

	public function __construct($page, $rows){
		parent::__construct('LIMIT');
		$this->set($page, $rows);
	}
	public function set($page, $rows){
		$this->page = $page;
		$this->rows = $rows;
	}
	public function print(){
		$limitSQL = " LIMIT ";
		$limitOffset = ($this->page - 1) * $this->perPage;
		$limitSQL .= $limitOffset.", ".$this->perPage;
		return $limitSQL;	
	}
}



?>
