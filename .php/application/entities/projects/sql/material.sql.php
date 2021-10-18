<?php
namespace application\entities\projects\sql;

class materialSQL 
extends \application\sql\entitySQL{

	public function __construct(){
		$this->baseTable = 'materials';
		$this->fieldApproved = 'materials_auth_project';
		$this->fieldDate = 'date_reported';
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
		$c .= " concat_ws('.', t.task_order, t.name, lpad(a.activity_order,3,'.')) as activity_name, ";
		$c .= " m.location_id, ";
		$c .= " l.name location_name, ";
		//$c .= " m.name, ";
		$c .= " IFNULL(m.name, 'Unspecified') name, ";
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
		//$c .= " COUNT(*) total_materials, ";
		$c .= " SUM(m.cost_estimated) sum_cost_estimated, ";
		$c .= " SUM(m.cost_actual) sum_cost_actual ";
		return $c;
	}

	public function tables($joinTypes = false){
		$q = " FROM materials AS m ";
		$q .= " JOIN activities AS a ON m.activity_id = a.id ";
		$q .= " JOIN tasks AS t ON a.task_id = t.id ";
		$q .= " JOIN projects AS p ON t.project_id = p.id ";
		$q .= " LEFT OUTER JOIN locations AS l ON m.location_id = l.id ";
		if ($joinTypes == true){
			$q .= " LEFT OUTER JOIN material_types AS mt ON m.type_id = mt.id ";
			$q .= " LEFT OUTER JOIN units_of_measure uom ON m.qty_unit_measure_id = uom.id ";
		}
		return $q;
	}

	public function info($id = 0){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE m.id = ".$id;
		return $q;
	}

	public function getActivityId($id){
		$q = " SELECT m.activity_id ";
		$q .= " FROM measures m ";
		$q .= " WHERE m.id = ".$id;
		return $q;
	}

	public function getActivityName($id){
		$q = "SELECT concat_ws('.', t.task_order, t.name, lpad(a.activity_order,3,'.')) name ";
		$q .= " FROM materials m ";
		$q .= " JOIN activities a ON m.activity_id = a.id ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
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
		$q .= " FROM materials AS m ";
		$q .= " JOIN activities AS a ON m.activity_id = a.id ";
		$q .= "WHERE a.task_id = ".$id;
		return $q;
	}

	public function countProject($id){
		$q = " SELECT count(*) as count_details ";
		$q .= " FROM materials AS m ";
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


	public function whereProjectYearMonth($id, $year, $month){
		$w = " WHERE t.project_id = ".$id." ";
		if ($year > 0){
			$w .= " AND YEAR(a.started) = ".$year." ";
			if ($month > 0){
				$w .= " AND MONTH(a.started) = ".$month." ";
			}
		}
		return $w;
	}

	public function whereTaskYearMonth($id, $year, $month){
		$w = " WHERE a.task_id = ".$id." ";
		if ($year > 0){
			$w .= " AND YEAR(a.started) = ".$year." ";
			if ($month > 0){
				$w .= " AND MONTH(a.started) = ".$month." ";
			}
		}
		return $w;
	}
	
	public function summaryActivity($id = 0, $year = 0, $month = 0){
		$q .= $this->colsSummary();
		$q .= $this->tables(true);
		$q .= " WHERE m.activity_id = ".$id;
		return $q;
	}

	public function summaryTask($id = 0, $year = 0, $month = 0){
		$q .= $this->colsSummary();
		$q .= $this->tables(true);
		$q .= $this->whereTaskYearMonth($id, $year, $month);
		return $q;
	}
	
	public function summaryProject($id = 0, $year = 0, $month = 0){
		$q = $this->colsSummary();
		$q .= $this->tables(true);
		$q .= $this->whereProjectYearMonth($id, $year, $month);
		return $q;
	}

	public function summaryActivityByType($id = 0, $year = 0, $month = 0){
		$q .= $this->colsSummary();
		$q .= ", IFNULL(mt.name, 'Unspecified') material_type, ";
		$q .= $this->tables(true);
		$q .= " WHERE m.activity_id = ".$id;
		$q .= " GROUP BY material_type ";
		return $q;
	}

	public function summaryTaskBType($id = 0, $year = 0, $month = 0){
		$q .= $this->colsSummary();
		$q .= ", IFNULL(mt.name, 'Unspecified') material_type, ";
		$q .= $this->tables(true);
		$q .= $this->whereTaskYearMonth($id, $year, $month);
		$q .= " GROUP BY material_type ";
		return $q;
	}
	
	public function summaryProjectByType($id = 0, $year = 0, $month = 0){
		$q = $this->colsSummary();
		$q .= ", IFNULL(mt.name, 'Unspecified') material_type, ";
		$q .= $this->tables(true);
		$q .= $this->whereProjectYearMonth($id, $year, $month);
		$q .= " GROUP BY material_type ";
		return $q;
	}


}
?>
