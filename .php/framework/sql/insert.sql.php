<?php 
namespace framework\sql;


class insert extends clauseColumns{
	protected $term = '(';
	protected $prefix = ', ';
	protected $printMode = 'switched';
	public function __construct(){
		parent::__construct('UPDATE');
	}
	public function print(){
		//$this->addContent(' (');
		$this->printMode = 'INSERT-COLUMNS';
		$this->printCols();
	//	$this->addContent(') ');
		$this->term = ') VALUES (';
		$this->printMode = 'INSERT-VALUES';
		$this->printCols();
		$this->addContent(') ');
		return $this->getContent();
	}
}



?>
