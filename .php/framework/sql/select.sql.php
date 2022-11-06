<?php 
namespace framework\sql;


class select extends clauseColumns{
	protected $term = 'SELECT';
	protected $prefix = ', ';
	protected $printMode = 'SELECT';
	public function __construct(){
		parent::__construct('SELECT');
	}
	
}



?>
