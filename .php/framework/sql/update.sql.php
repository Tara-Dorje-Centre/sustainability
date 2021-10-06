<?php 
namespace framework\sql;


class update extends clauseColumns{
	protected $term = '';
	protected $prefix = ', ';
	protected $printMode = 'UPDATE';
	public function __construct(){
		parent::__construct('UPDATE');
	}
	public function print(){
		//$this->addContent(' (');
		//$this->printMode = 'UPDATE';
		$this->printCols();
		//$this->addContent(') ');
		return $this->getContent();
	}
}



?>
