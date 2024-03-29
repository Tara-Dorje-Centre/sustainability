<?php
namespace application\entities\projects\sql;
class materialSQL extends taskChildSQL{
	public function __construct(){
		$this->baseTable = 'materials';

		$this->fieldApproved = 'materials_auth_project';

		$this->fieldDate = 'date_reported';
	}
	




protected function cols(){
$c = $this->select();
$c .= " m.id, ";
$c .= " m.task_id, ";
$c .= " t.task_order, ";
$c .= " t.name task_name, ";
$c .= " m.location_id, ";
$c .= " l.name location_name, ";
$c .= " m.name, ";
$c .= " m.description, ";
$c .= " m.date_reported, ";
$c .= " m.done_by, ";
$c .= " m.paid_to, ";
$c .= " m.updated, ";
$c .= " m.quantity, ";
$c .= " m.qty_unit_measure_id, ";
$c .= " uom.name qty_unit_measure_name, ";
$c .= " m.cost_unit, ";
$c .= " m.cost_estimated, ";
$c .= " m.cost_actual, ";
$c .= " m.type_id, ";
$c .= " mt.name type_name, ";
$c .= " mt.highlight_style, ";
$c .= " m.link_text, ";
$c .= " m.link_url, ";
$c .= " m.notes ";
return $c;	
}

public function colsSummary($first = true){
$c = $this->select($first);
	$c .= " COUNT(*) total_materials, ";
	$c .= " SUM(m.cost_estimated) sum_cost_estimated, ";
	$c .= " SUM(m.cost_actual) sum_cost_actual ";
return $c;
}

public function tables($joinTypes = false){
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " JOIN projects AS p ON t.project_id = p.id ";
$q .= " LEFT OUTER JOIN locations AS l ON m.location_id = l.id ";
if ($joinTypes == true){
$q .= " LEFT OUTER JOIN material_types AS mt ON m.type_id = mt.id ";
$q .= " LEFT OUTER JOIN units_of_measure uom ON m.qty_unit_measure_id = uom.id ";
}
return $q;
}

public function summaryTaskGroupType($id = 0, $year = 0, $month = 0){
$q .= $this->colsSummary();
$q .= ", IFNULL(mt.name, 'Unspecified') material_type, ";
$q .= $this->tables(true);
$q .= $this->whereTask($id, $year, $month);
$q .= " GROUP BY material_type ";
return $q;
}
	
public function summaryProjectGroupType($id = 0, $year = 0, $month = 0){
$q = $this->colsSummary();
$q .= ", IFNULL(mt.name, 'Unspecified') material_type, ";
$q .= $this->tables(true);
$q .= $this->whereProject($id, $year, $month);

$q .= " GROUP BY material_type ";
return $q;
}


}
?>
