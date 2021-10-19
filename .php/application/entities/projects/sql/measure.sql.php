<?php
namespace application\entities\projects\sql;

class measureSQL 
extends \application\sql\entitySQL{

	public function __construct(){
		$this->baseTable = 'measures';
		//$this->typeTable = 'measure_type_units_v';
	}
	
	protected function cols(){
		$c = $this->select();
		$c .= " m.id, ";
		$c .= " t.project_id, ";
		$c .= " p.name project_name, ";
		$c .= " a.task_id, ";
		$c .= " t.task_order, ";
		$c .= " t.name task_name, ";
		$c .= " m.activity_id, ";
		$c .= " a.activity_order, ";
		$c .= " a.name as activity_name, ";
		$c .= " m.location_id, ";
		$c .= " l.sort_key location_name, ";
		//$c .= " m.name, ";
		$c .= " IFNULL(m.name, 'Unspecified') name, ";
		$c .= " m.description, ";
		$c .= " m.date_reported, ";
		$c .= " m.done_by, ";
		$c .= " m.updated, ";
		$c .= " m.value, ";
		$c .= " m.notes, ";
		$c .= " m.measure_type_unit_id, ";
		$c .= " mtu.unit_of_measure type_name, ";
		$c .= " 'none' highlight_style, ";
		$c .= " mtu.unit_type, ";
		$c .= " mtu.unit_symbol ";
		return $c;	
	}

	protected function tables($joinTypes = true){
		$q = " FROM measures AS m ";
		$q .= " JOIN activities AS a ON m.activity_id = a.id ";
		$q .= " JOIN tasks AS t ON a.task_id = t.id ";
		$q .= " JOIN projects AS p ON t.project_id = p.id ";
		$q .= " LEFT OUTER JOIN locations AS l ON m.location_id = l.id ";
		if ($joinTypes == true){
			$q .= " LEFT OUTER JOIN measure_type_units_v as mtu ";
			$q .= " ON m.measure_type_unit_id = mtu.id ";
		}
		return $q;
	}

	public function getActivityId($id){
		$q = " SELECT m.activity_id ";
		$q .= " FROM measures m ";
		$q .= " WHERE m.id = ".$id;
		return $q;
	}
	
	public function getActivityName($id){
		//$q = "SELECT concat_ws('.', t.task_order, t.name, lpad(a.activity_order,3,'.')) name ";
		$q = "SELECT a.name, ";
		$q .= " FROM measures m ";
		$q .= " JOIN activities a ON m.activity_id = a.id ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
		$q .= " WHERE m.id = ".$id;
		return $q;
	}

	public function info($id = 0){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE m.id = ".$id;
		return $q;
	}

	public function countActivity($id){
		$q = "SELECT count(*) as count_details ";
		$q .= "FROM materials AS m ";
		$q .= "WHERE m.activity_id = ".$id;
		return $q;
	}

	public function countTask($id){
		$q = "SELECT count(*) as count_details ";
		$q .= " FROM measures AS m ";
		$q .= " JOIN activities AS a ON m.activity_id = a.id ";
		$q .= "WHERE a.task_id = ".$id;
		return $q;
	}

	public function countProject($id){
		$q = " SELECT count(*) as count_details ";
		$q .= " FROM measures AS m ";
		$q .= " JOIN activities AS a ON m.activity_id = a.id ";
		$q .= " JOIN tasks AS t ON a.task_id = t.id ";
		$q .= " WHERE t.project_id = ".$id;
		return $q;
	}

	public function listActivity($id = 0, $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE m.activity_id = ".$id;
		$q .= " ORDER BY t.task_order, a.activity_order, m.id ";
		$q .= $this->limit($page, $rows);
		return $q;
	}

	public function listTask($id = 0, $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE a.task_id = ".$id;
		$q .= " ORDER BY t.task_order, a.activity_order, m.id ";
		$q .= $this->limit($page, $rows);
		return $q;
	}

	public function listProject($id = 0, $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE t.project_id = ".$id;
		$q .= " ORDER BY t.task_order, a.activity_order, m.id ";
		$q .= $this->limit($page, $rows);
		return $q;
	}

}
?>
