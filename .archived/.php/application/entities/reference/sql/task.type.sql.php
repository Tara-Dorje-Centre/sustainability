<?php
namespace application\entities\reference\sql;



class taskTypeSQL extends \application\sql\entityTypeSQL{
	public function __construct(){
		$this->baseTable = 'task_types';
	}
	
	protected function cols(){
		$c = parent::cols();
		$c .= ", et.frequency ";
		return $c;	
	}
	
	public function optionsFrequency(){
		$q = "SELECT ";
		$q .= " f.frequency value, ";
		$q .= " f.description caption ";
		$q .= " FROM task_type_frequencies AS f ";
		$q .= " ORDER BY caption ";
		return $q;
	}
}


?>
