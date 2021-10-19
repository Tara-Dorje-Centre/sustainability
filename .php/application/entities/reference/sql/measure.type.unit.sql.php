<?php
namespace application\entities\reference\sql;

class measureTypeUnitSQL extends \application\sql\entitySQL{
	public function __construct(){
		$this->baseTable = 'measure_type_units_v mtu ';

	}
	


	public function createView_MeasureTypeUnits(){
		$s = "CREATE OR REPLACE VIEW projecs.measure_type_units_v AS
		SELECT
		mtu.id,
		mtu.id measure_type_unit_id,
		mtu.measure_type_id,
		mtu.unit_measure_id,
		mtu.created,
		mtu.updated,
		mt.name measure_type,
		mt.highlight_style,
		u.name unit_of_measure,
		u.display_order,
		u.type unit_type,
		u.symbol unit_symbol
		from
		measure_types mt
		join measure_type_units mtu
		on mtu.measure_type_id = mt.id
		join units_of_measure u
		on mtu.unit_measure_id = u.id
		order by
		measure_type, display_order ";
		return $s;
	}
	
protected function cols(){
	$c = " mtu.id, ";
	$c .= " mtu.measure_type_id, ";
	$c .= " mtu.unit_measure_id, ";
	$c .= " mtu.display_order, ";
	$c .= " mtu.highlight_style, ";
	$c .= " mtu.updated, ";
	$c .= " mtu.created, ";
	$c .= " concat(mtu.measure_type,'(',mtu.unit_symbol,')') as name, ";
	$c .= " mtu.measure_type, ";
	$c .= " mtu.unit_type, ";
	$c .= " mtu.unit_of_measure, ";
	$c .= " mtu.unit_symbol ";
	return $c;	
}

public function info($id = 0){
	$q = " SELECT ";	
	$q .= $this->cols();
	$q .= " FROM measure_type_units_v mtu ";
	$q .= " WHERE mtu.id = '".$id."' ";
	return $q;
}

public function list($page = 1, $rows = 10){
	$q = " SELECT ";	
	$q .= $this->cols();
	$q .= " FROM measure_type_units_v mtu ";
	$q .= " ORDER BY measure_type, display_order, unit_of_measure ";
	$q .= $this->limit($page, $rows);
	return $q;	
}

public function listMeasureType($id,$page = 1, $rows = 10){
	$q = " SELECT ";	
	$q .= $this->cols();
	$q .= " FROM measure_type_units_v mtu ";
	if ($measureTypeId > 0){
		$q .= " WHERE mtu.measure_type_id = ".$id." ";
	}
	$q .= " ORDER BY unit_type, measure_type, unit_of_measure ";
	$q .= $this->limit($page, $rows);
	return $q;	
}

public function count(){
	$q = " SELECT ";	
	$q .= " COUNT(*) count_details ";
	$q .= " FROM measure_type_units_v mtu ";
	//if ($measureTypeId > 0){
	//	$q .= " WHERE mtu.measure_type_id = ".$id." ";
	//}
	return $q;	
}

public function countMeasureType($id){
	$q = " SELECT ";	
	$q .= " COUNT(*) total_units ";
	$q .= " FROM measure_type_units_v mtu ";
	if ($id > 0){
		$q .= " WHERE mtu.measure_type_id = ".$id." ";
	}
	return $q;	
}
public function options($selectedId = 0, $disabled = 'false'){
	$q = " SELECT ";
	$q .= " mtu.unit_type, ";
	$q .= " mtu.id as value, ";
	$q .= " concat(mtu.measure_type,'(',mtu.unit_symbol,')') as caption ";
	$q .= " FROM measure_type_units_v mtu ";
	if ($disabled == 'true'){
		$q .= " WHERE mtu.id = ".$selectedId." ";	
	}
	$q .= " ORDER BY caption ";
	return $q;	
}


}
?>
