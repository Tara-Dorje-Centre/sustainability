<?php
namespace application\entities\projects\sql;

class receiptSQL extends taskChildSQL{
	public function __construct(){
		$this->baseTable = 'receipts';
		$this->typeTable = 'receipt_types';
$this->fieldApproved = 'receipts_auth_project';
$this->fieldDate = 'date_reported';
	}
	



protected function cols(){

$c = $this->select();

$c .= " r.id, ";
$c .= " r.task_id, ";
$c .= " t.task_order, ";
$c .= " t.name task_name, ";
//$c .= " r.activity_id, ";
$c .= " r.name, ";
$c .= " r.description, ";
$c .= " r.date_reported, ";
$c .= " r.received_by, ";
$c .= " r.received_from, ";
$c .= " r.updated, ";
$c .= " r.quantity, ";
$c .= " r.qty_unit_measure_id, ";
$c .= " uom.name qty_unit_measure_name, ";
$c .= " r.cost_unit, ";
$c .= " r.cost_actual, ";
$c .= " r.cost_actual cost_estimated, ";
$c .= " r.type_id, ";
$c .= " rt.name type_name, ";
$c .= " rt.highlight_style, ";
$c .= " r.notes ";
return $c;	
}

protected function tables($joinTypes = true){
$q = " FROM receipts AS r ";
$q .= " JOIN tasks AS t ON r.task_id = t.id ";
$q .= " JOIN projects AS p ON t.project_id = p.id ";
if ($joinTypes == true){
$q .= " LEFT OUTER JOIN receipt_types AS rt ON r.type_id = rt.id ";
$q .= " LEFT OUTER JOIN units_of_measure uom ON r.qty_unit_measure_id = uom.id ";
}
return $q;
}

protected function colsSummary($first = true){
$q = $this->select($first);
$q .= " COUNT(*) total_receipts, ";
$q .= " SUM(r.cost_actual) sum_cost_actual, ";
	$q .= " SUM(r.cost_actual) sum_cost_estimated ";
return $q;
}
 
public function summaryProjectGroupType($id = 0, $approved = 'yes'){
	$q = $this->colsSummary();
$q .= ", IFNULL(rt.name, 'Unspecified') receipt_type ";/*
//$q .= " COUNT(*) total_receipts, ";
//q .= " SUM(m.cost_actual) sum_cost_actual ";*/
$q .= $this->tables(true);
$q .= " WHERE  ";
$q .= $this->whereProject($id);
$q .= $this->_equalApproved($approved);
// .= " AND t.receipts_auth_project = '".$approved."' ";
$q .= " GROUP BY receipt_type ";
return $q;
}




}
?>
