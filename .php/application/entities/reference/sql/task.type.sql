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
}


?>
