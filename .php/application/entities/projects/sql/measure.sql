<?php
namespace application\entities\projects\sql;

class measureSQL extends taskChildSQL{
	public function __construct(){
		$this->baseTable = 'measures';

$this->typeTable = 'measure_types';
	}
	



protected function cols(){
$c = $this->select();
$c = " m.id, ";
$c .= " m.task_id, ";
$c .= " t.name task_name, ";
$c .= " m.location_id, ";
$c .= " l.sort_key location_name, ";
$c .= " m.name, ";
$c .= " m.description, ";
$c .= " m.date_reported, ";
$c .= " m.updated, ";
$c .= " m.value, ";
$c .= " m.measure_type_unit_id type_id, ";
$c .= " m.notes, ";
$c .= " mtu.measure_type type_name, ";
$c .= " mtu.unit_type, ";
$c .= " mtu.unit_symbol ";
return $c;	
}

protected function tables($joinTypes = true){
	$t = " FROM measures AS m JOIN measure_type_units_v as mtu ";
	$t .= " ON m.measure_type_unit_id = mtu.measure_type_unit_id ";
	$q .= " LEFT OUTER JOIN locations AS l ON m.location_id = l.id ";
	return $t;
}


}
?>
