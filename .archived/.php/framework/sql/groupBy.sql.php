<?php 
namespace framework\sql;



class groupBy extends clauseColumns{
	protected $term = 'GROUP BY';
	protected $prefix = ', ';
	protected $printMode = 'GROUP-BY';
	public function __construct(){
		parent::__construct('GROUP BY');
		

	}
}


?>
