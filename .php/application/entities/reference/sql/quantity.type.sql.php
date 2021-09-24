<?php
namespace application\entities\reference\sql;




class quantityTypeSQL extends \application\sql\entityTypeSQL{
	public function __construct(){
		$this->baseTable = 'units_of_measure';
	}
	protected function cols(){
		$c = parent::cols();
		$c .= ", et.symbol ";
		$c .= ", et.type ";
		return $c;	
	}

	public function options($selectedId = 0, $disabled = 'false'){
	$q = " SELECT ";
	$q .= " um.type, ";
	$q .= " um.id as value, ";
	$q .= " concat(um.name,'(',um.symbol,')') as caption ";
	$q .= " FROM units_of_measure um ";
	if ($disabled == 'true'){
		$q .= " WHERE um.id = ".$selectedId." ";	
	}
	$q .= " ORDER BY type, caption ";
	return $q;	
	}
}



?>
