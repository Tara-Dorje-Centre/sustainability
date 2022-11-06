<?php 
namespace framework\sql;


class orderBy extends clauseColumns{
	protected $term = 'ORDER BY';
	protected $prefix = ', ';
	protected $printMode = 'ORDER-BY';
	public function __construct(){
		parent::__construct('ORDER BY');
	}
}



?>
