<?php
namespace application\entities\projects\sql;

class receiptSQL 
extends \application\sql\entitySQL{

	public function __construct(){
		$this->baseTable = 'receipts';
		$this->typeTable = 'receipt_types';
		$this->fieldApproved = 'receipts_auth_project';
		$this->fieldDate = 'date_reported';
	}
	
	protected function cols(){
		$c = $this->select();
		$c .= " r.id, ";
		$c .= " t.project_id, ";
		$c .= " p.name project_name, ";
		$c .= " a.task_id, ";
		$c .= " t.task_order, ";
		$c .= " t.name task_name, ";
		$c .= " r.activity_id, ";
		$c .= " a.activity_order, ";
		$c .= " a.name activity_name, ";
		$c .= " IFNULL(r.name, 'Unspecified') name, ";
		$c .= " r.description, ";
		$c .= " r.date_reported, ";
		$c .= " r.received_by, ";
		$c .= " r.received_from, ";
		$c .= " r.updated, ";
		$c .= " r.quantity, ";
		$c .= " r.qty_unit_measure_id, ";
		$c .= " uom.unit_of_measure qty_unit_measure_name, ";
		$c .= " r.cost_unit, ";
		$c .= " r.cost_actual, ";
		//$c .= " r.cost_actual cost_estimated, ";
		$c .= " r.type_id, ";
		$c .= " rt.name type_name, ";
		$c .= " rt.highlight_style, ";
		$c .= " r.notes ";
		return $c;	
	}

	protected function colsSummary($first = true){
		$q = $this->select($first);
		$q .= " COUNT(*) total_items, ";
		$q .= " SUM(r.cost_actual) sum_actual ";
		//$q .= " SUM(r.cost_actual) sum_cost_estimated ";
		return $q;
	}
 
	protected function tables($joinTypes = true){
		$q = " FROM receipts AS r ";
		$q .= " JOIN activities AS a ON r.activity_id = a.id ";
		$q .= " JOIN tasks AS t ON a.task_id = t.id ";
		$q .= " JOIN projects AS p ON t.project_id = p.id ";
		if ($joinTypes == true){
			$q .= " LEFT OUTER JOIN receipt_types AS rt ON r.type_id = rt.id ";
			$q .= " LEFT OUTER JOIN measure_type_units_v uom ON r.qty_unit_measure_id = uom.id ";
		}
		return $q;
	}

	public function info($id = 0){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE r.id = ".$id;
		return $q;
	}

	public function getActivityId($id){
		$q = " SELECT r.activity_id ";
		$q .= " FROM receipts r ";
		$q .= " WHERE r.id = ".$id;
		return $q;
	}

	public function getActivityName($id){
		//$q = "SELECT concat_ws('.', t.task_order, t.name, lpad(a.activity_order,3,'.')) name ";
		$q = "SELECT a.name, ";
		$q .= " FROM receipts r ";
		$q .= " JOIN activities a ON r.activity_id = a.id ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
		$q .= " WHERE r.id = ".$id;
		return $q;
	}



	public function countActivity($id){
		$q = "SELECT count(*) as count_details ";
		$q .= "FROM receipts AS r ";
		$q .= "WHERE r.activity_id = ".$id;
		return $q;
	}

	public function countTask($id){
		$q = "SELECT count(*) as count_details ";
		$q .= " FROM receipts AS r ";
		$q .= " JOIN activities AS a ON r.activity_id = a.id ";
		$q .= "WHERE a.task_id = ".$id;
		return $q;
	}

	public function countProject($id){
		$q = " SELECT count(*) as count_details ";
		$q .= " FROM receipts AS r ";
		$q .= " JOIN activities AS a ON r.activity_id = a.id ";
		$q .= " JOIN tasks AS t ON a.task_id = t.id ";
		$q .= " WHERE t.project_id = ".$id;
		return $q;
	}

	public function listActivity($id = 0, $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE r.activity_id = ".$id;
		$q .= " ORDER BY t.task_order, a.activity_order, r.id ";
		$q .= $this->limit($page, $rows);
		return $q;
	}


	public function listTask($id = 0, $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE a.task_id = ".$id;
		$q .= " ORDER BY t.task_order, a.activity_order, r.id ";
		$q .= $this->limit($page, $rows);
		return $q;
	}

	public function listProject($id = 0, $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE t.project_id = ".$id;
		$q .= " ORDER BY t.task_order, a.activity_order, r.id ";
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
		$q = $this->colsSummary();
		$q .= $this->tables(true);
		$q .= " WHERE r.activity_id = ".$id;
		return $q;
	}

	public function summaryTask($id = 0, $year = 0, $month = 0){
		$q = $this->colsSummary();
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
		$q .= $this->tables(true);
		$q .= " WHERE r.activity_id = ".$id;
		return $q;
	}

	public function summaryTaskByType($id = 0, $year = 0, $month = 0){
		$q .= $this->colsSummary();
		$q .= ", IFNULL(rt.name, 'Unspecified') receipt_type, ";
		$q .= $this->tables(true);
		$q .= $this->whereTaskYearMonth($id, $year, $month);
		$q .= " GROUP BY receipt_type ";
		return $q;
	}
	
	public function summaryProjectByType($id = 0, $year = 0, $month = 0){
		$q = $this->colsSummary();
		$q .= ", IFNULL(rt.name, 'Unspecified') receipt_type, ";
		$q .= $this->tables(true);
		$q .= $this->whereProjectYearMonth($id, $year, $month);
		$q .= " GROUP BY receipt_type ";
		return $q;
	}





}
?>
