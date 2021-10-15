<?php
namespace application\entities\projects\sql;

class activitySQL 
extends \application\sql\entitySQL{


	public function __construct(){
		$this->baseTable = 'activities';
		$this->typeTable = 'activity_types';
		$this->fieldDate = 'started';
	}


	protected function cols($first = true){
		$c = $this->select($first);
		$c .= " a.id,  ";
		$c .= " concat_ws('.', t.task_order, t.name, lpad(a.activity_order,3,'.')) as name, ";
		$c .= " a.task_id,  ";
		$c .= " t.name task_name, ";
		$c .= " a.type_id, ";
		$c .= " tt.name type_name, ";
		$c .= " tt.highlight_style, ";	
		$c .= " t.project_id, ";
		$c .= " p.name project_name, ";
		$c .= " a.done_by,  ";
		$c .= " a.started,  ";
		$c .= " DATE(a.started) start_date, ";
		$c .= " a.updated,  ";
		$c .= " a.activity_order,  ";
		$c .= " t.task_order, ";
		$c .= " a.hours_estimated,  ";
		$c .= " a.hours_actual,  ";
		$c .= " a.comments, ";
		$c .= " a.link_url, ";
		$c .= " a.link_text ";

		return $c;	
	}

	protected function colsSummary($first = true){
		$q = $this->select($first);
		$q .= " COUNT(*) total_activities, ";
		$q .= " SUM(a.hours_estimated) total_hours_estimated, ";
		$q .= " SUM(a.hours_actual) total_hours_actual ";
		return $q;
	}



	public function tables($joinTypes = true){
		$q = " FROM activities a ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
		$q .= " JOIN projects p ON t.project_id = p.id ";
		if ($joinTypes == true){
			$q .= " LEFT OUTER JOIN task_types tt ON t.type_id = tt.id ";
			$q .= " LEFT OUTER JOIN activity_types at ON a.type_id = at.id ";
		}
		return $q;
	}

	public function getActivityName($id){
		$q = "SELECT concat_ws('.', t.task_order, t.name, lpad(a.activity_order,3,'.')) name ";
		$q .= " FROM activities a ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
		$q .= " WHERE a.id = ".$id;
		return $q;
	}

	public function getTaskId($id){
		$q = "SELECT a.task_id ";
		$q .= " FROM activities a ";
		$q .= " WHERE a.id = ".$id;
		return $q;
	}


	public function getTaskName($id){
		$q = "SELECT t.name ";
		$q .= " FROM activities a ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
		$q .= " WHERE a.id = ".$id;
		return $q;
	}

	public function getProjectId($id){
		$q = "SELECT t.project_id ";
		$q .= " FROM activities a ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
		$q .= " WHERE a.id = ".$id;
		return $q;
	}
	
	public function getProjectName($id){
		$q = "SELECT p.name ";
		$q .= " FROM activities a ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
		$q .= " JOIN projects p ON t.project_id = p.id ";
		$q .= " WHERE a.id = ".$id;
		return $q;
	}


	public function countTask($id){
		$q = "SELECT count(*) as count_details ";
		$q .= "FROM activities a ";
		$q .= "WHERE a.task_id = ".$id;
		return $q;
	}

	public function countProject($id){
		$q = "SELECT count(*) as count_details ";
		$q .= "FROM activities a ";
		$q .= " JOIN tasks t ON a.task_id = t.id ";
		$q .= "WHERE t.project_id = ".$id;
		return $q;
	}


	public function countDoneBy($doneBy = 'EVERYONE'){
		$q = "SELECT count(*) as count_details ";
		$q .= "FROM activities a ";
		if ($doneBy <> 'EVERYONE'){
		$q .= "WHERE UPPER(a.done_by) = UPPER('".$doneBy."') ";
		}
		return $q;
	}


	public function info($id = 0){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE a.id = ".$id;
		return $q;
	}

	
	public function listTask($id = 0, $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE a.task_id = ".$id;
		$q .= " ORDER BY t.task_order, a.activity_order ";
		$q .= $this->limit($page, $rows);
		return $q;
	}

	public function listProject($id = 0, $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= " WHERE t.project_id = ".$id;
		$q .= " ORDER BY t.task_order, a.activity_order ";
		$q .= $this->limit($page, $rows);
		return $q;
	}

public function listDoneBy($doneBy = 'EVERYONE', $page = 1, $rows =10){
		$q = $this->cols();
		$q .= $this->tables(true);
		if ($doneBy <> 'EVERYONE'){
		$q .= "WHERE UPPER(a.done_by) = UPPER('".$doneBy."') ";
		}
		$q .= " ORDER BY t.task_order, a.activity_order ";
		$q .= $this->limit($page, $rows);
		return $q;
	}
	
	public function summaryTask($id = 0){
		$q = $this->colsSummary();
		$q .= $this->tables(false);
		$q .= " WHERE a.task_id = ".$id;
		return $q;
	}
	
	public function summaryTaskDoneBy($id = 0, $doneBy = 'EVERYONE'){
		$q = $this->colsSummary();
		$q .= $this->colsDoneBy(false);
		$q .= $this->tables(false);
		$q .= " WHERE a.task_id = ".$id;
		if ($doneBy != 'EVERYONE'){
			$q .= $this->_equalDoneBy($doneBy);
		} else {
			$q .= " GROUP BY ".$this->fieldDoneBy." ";
		}
		return $q;
	}
	
	protected function whereDoneByYearMonth($doneBy, $year,$month){
		$w = "";
		if ($doneBy <> 'EVERYONE'){
			$w = "WHERE UPPER(a.done_by) = UPPER('".$doneBy."') ";
			if ($year > 0){
				$w .= " AND YEAR(a.started) = ".$year." ";
				if ($month > 0){
					$w .= " AND MONTH(a.started) = ".$month." ";
				}
			}
		} else {
			if ($year > 0){
				$w = " WHERE YEAR(a.started) = ".$year." ";
				if ($month > 0){
					$w .= " AND MONTH(a.started) = ".$month." ";
				}
			}
		}
		
		return $w;
	}
	
	
	public function calendarLinksDoneBy($doneBy = 'EVERYONE'){
$q = "SELECT  ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year ";
$q .= " FROM activities a ";

$q .= $this->whereDoneByYearMonth($doneBy,0,0);
$q .= " GROUP BY  ";
$q .= " MONTH(a.started), ";
$q .= " YEAR(a.started) ";
		return $q;
	}

public function calendarSummaryDoneBy($doneBy, $year, $month){
$q = "SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " DATE(a.started) started, ";
$q .= " a.done_by, ";
$q .= " p.id, ";
$q .= " min(p.name) name, ";
$q .= " 1 ordering, ";
$q .= " min(tt.highlight_style) highlight_style ";
$q .= $this->tables();
$q .= $this->whereDoneByYearMonth($doneBy,$year,$month);
$q .= " GROUP BY  ";
$q .= " DATE(a.started), ";
$q .= " a.done_by, ";
$q .= " p.id ";
$q .= " UNION ALL ";
$q .= " SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " LAST_DAY(a.started) started, ";
$q .= " 'MonthTotal' done_by, ";
$q .= " 0 id, ";
$q .= " 'MonthTotal' name, ";
$q .= " 100 ordering, ";
$q .= " 'highlight-yellow' highlight_style ";
$q .= $this->tables();
$q .= $this->whereDoneByYearMonth($doneBy,$year,$month);
$q .= " GROUP BY ";
$q .= " LAST_DAY(a.started) ";
$q .= " ORDER BY started, ordering, id, done_by ";
return $q;	
}

	public function whereProject($id, $year, $month){
		$w = " WHERE t.project_id = ".$id." ";
		if ($year > 0){
			$w .= " AND YEAR(a.started) = ".$year." ";
			if ($month > 0){
				$w .= " AND MONTH(a.started) = ".$month." ";
			}
		}

		return $w;
	}


public function calendarLinksProject($id){

$q = "SELECT  ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year ";
$q .= $this->tables();
$q .= $this->whereProject($id, 0, 0);
$q .= " GROUP BY ";
$q .= " YEAR(a.started), ";
$q .= " MONTH(a.started) ";
$q .= " ORDER BY year, month ";
return $q;	
}
			
public function calendarSummaryProject($id = 0, $year = 0, $month = 0){
//show daily tally by task and person
$q = "SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " DATE(a.started) started, ";
$q .= " a.done_by, ";
$q .= " t.id, ";
$q .= " min(t.name) name, ";
$q .= " min(t.task_order) ordering, ";
$q .= " min(tt.highlight_style) highlight_style ";
$q .= $this->tables();
$q .= $this->whereProject($id, $year, $month);
$q .= " GROUP BY  ";
$q .= " DATE(a.started), ";
$q .= " a.done_by, ";
$q .= " t.id ";
$q .= " UNION ALL ";
//show monthly tally for project across all tasks
$q .= " SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " LAST_DAY(a.started) started, ";
$q .= " SUM(a.hours_actual) done_by, ";
$q .= " t.id, ";
$q .= " min(t.name) name, ";
$q .= " 1000 + min(t.task_order) ordering, ";
$q .= " 'highlight-yellow' highlight_style ";
$q .= $this->tables();
$q .= $this->whereProject($id, $year, $month);
$q .= " GROUP BY ";
$q .= " LAST_DAY(a.started), ";
$q .= " t.id ";
$q .= " ORDER BY started, ordering, done_by ";
return $q;	
}

}
?>
