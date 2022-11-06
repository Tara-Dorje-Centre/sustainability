<?php
namespace application\entities\projects\sql;

class taskSQL 
extends \application\sql\entitySQL 
{
	public function __construct(){
		$this->baseTable = 'tasks';
		$this->fieldId = 't.id';

	}



protected function cols(){
$c = $this->select();
$c .= " t.id,  ";
$c .= " t.project_id,  ";
$c .= " p.name project_name, ";
$c .= " pt.name project_type, ";
$c .= " t.location_id,  ";
$c .= " l.name location_name, ";
$c .= " t.type_id,  ";
$c .= " tt.name type_name, ";
$c .= " tt.highlight_style, ";
$c .= " tt.frequency, ";
$c .= " t.task_order,  ";
$c .= " t.name,  ";
$c .= " t.started,  ";
$c .= " t.updated,  ";
$c .= " t.pct_done,  ";
$c .= " t.description,  ";
$c .= " t.summary,  ";
$c .= " t.hours_actual,  ";
$c .= " t.hours_estimated,  ";
$c .= " t.hours_notes, ";
$c .= " t.materials_auth_project, ";
$c .= " t.materials_auth_by, ";
$c .= " t.receipts_auth_project, ";
$c .= " t.receipts_auth_by ";
		return $c;
	}
	
public function tables($joinTypes = true){
	$q = " FROM projects p join tasks t ON p.id = t.project_id ";
	$q .= " LEFT OUTER JOIN locations l on t.location_id = l.id ";
	if ($joinTypes == true){
	$q .= " JOIN project_types pt ON p.type_id = pt.id ";
	$q .= " LEFT OUTER JOIN task_types tt on t.type_id = tt.id ";
	}
	return $q;
}


public function colsSummary($first = true){
$q = $this->select($first);
$q .= " COUNT(*) as total_tasks, ";
$q .= " sum(t.hours_estimated) as sum_hours_estimated, ";
$q .= " sum(t.hours_actual) as sum_hours_actual, ";
$q .= " sum(t.pct_done)/count(*) as overall_pct_done ";
return $q;
}

public function getProjectId($id = 0){
$q = " SELECT t.project_id ";
$q .= " FROM tasks as t ";
$q .= " WHERE t.id = ".$id;
return $q;
}

public function getProjectName($id = 0){
$q = " SELECT p.name ";
$q .= " FROM tasks t ";
$q .= " JOIN projects p ON t.project_id = p.id ";
$q .= " WHERE t.id = ".$id;
return $q;
}

public function getTaskName($id = 0){
$q = " SELECT t.name ";
$q .= " FROM tasks as t ";
$q .= " WHERE t.id = ".$id;
return $q;
}

public function getTaskHoursEstimated($id = 0){
$q = " SELECT t.hours_estimated ";
$q .= " FROM tasks as t ";
$q .= " WHERE t.id = ".$id;
return $q;
}

 public function countProject($id = 0){
	$q = $this->colsCount();
	$q .= " FROM tasks AS t ";
	$q .= ' where t.project_id = '.$id;
	return $q;	
}

protected function colsOption($first = true){
		$c = $this->select($first);
		$c .= " t.id as value, ";
		$c .= " concat_ws(': ',lpad(t.task_order,2,'0'), t.name) as caption ";
		return $c;
}



public function optionsByProject($id = 0){
	$q = $this->colsOption(true);
	$q .= " FROM tasks as t ";
	$q .= " where t.project_id = ".$id;
	//$q .= " AND p.pct_done < 1 ";
	$q .= " ORDER BY caption ";
	return $q;	
}

public function listProject($id = 0, $page = 1, $rows = 10){
$q = $this->cols();
$q .= $this->tables(true);
$q .= ' where t.project_id = '.$id.' ';
$q .= $this->limit($page, $rows);
return $q;
}

public function calendarLinksProject($id){
$q = $this->colsCalendarLinks();
$q .= $this->tables(false);
$q .= ' where t.project_id = '.$id.' ';
  $q .= $this->groupByCalendarLinks();
  return $q;
}

public function listPeriodic($complete = 'NO',$page = 0,$rows = 0){
	
	$q = $this->cols();
	$q .= $this->tables(true);
	$q .= " WHERE t.id IN ";
	$q .= $this->periodicTasksSubquery($complete);
	$q .= " ORDER BY ";
	$q .= " project_type, project_name, t.task_order ";
	$q .= $this->limit($page, $rows);
	return $q;
}

public function countPeriodic($complete = 'NO'){
$q = $this->colsCount();
	//$q .= $this->tables(true);
	$q .= " FROM tasks as t ";
	$q .= " WHERE t.id IN ";
	$q .= $this->periodicTasksSubquery($complete);
	return $q;
}

private function periodicTasksBaseSubquery($frequency, $where){
$q = " ( select a.task_id ";
$q .= " from activities a join tasks t on a.task_id = t.id ";
$q .= " join task_types tt on t.type_id = tt.id ";
$q .= " where tt.frequency = '".$frequency."' ";
$q .= " and a.hours_actual > 0 ";
$q .= $where." ) ";
return $q;	
}

private function periodicTasksBaseSelect($frequency,$subqueryWhere, $complete = 'NO'){
$q = " SELECT t.id ";
$q .= " FROM projects p JOIN tasks t ON p.id = t.project_id ";
$q .= " JOIN task_types tt ON t.type_id = tt.id ";
$q .= " WHERE p.pct_done != 1 AND tt.frequency = '".$frequency."' ";
if ($complete == 'YES'){
	$q .= " AND t.id IN ";
} else {
	$q .= " AND t.id NOT IN ";
}
$q .= $this->periodicTasksBaseSubquery($frequency,$subqueryWhere);
return $q;
}

private function periodicTasksSubquery($complete = 'NO'){
	$q = " (";
	$subqueryWhere = " and date(a.started) = date(CURRENT_TIMESTAMP) ";	
	$q .= $this->periodicTasksBaseSelect('daily', $subqueryWhere,$complete);
	$q .= " UNION ALL ";
	$subqueryWhere = " and yearweek(a.started) = yearweek(CURRENT_TIMESTAMP) ";
	$q .= $this->periodicTasksBaseSelect('weekly', $subqueryWhere,$complete);
	$q .= " UNION ALL ";
	$subqueryWhere = " and year(a.started) = year(CURRENT_TIMESTAMP) ";
	$subqueryWhere .= " and month(a.started) = month(CURRENT_TIMESTAMP) ";
	$q .= $this->periodicTasksBaseSelect('monthly', $subqueryWhere,$complete);
	$q .= " UNION ALL ";
	$subqueryWhere = " and year(a.started) = year(CURRENT_TIMESTAMP) ";
	$subqueryWhere .= " and quarter(a.started) = quarter(CURRENT_TIMESTAMP) ";
	$q .= $this->periodicTasksBaseSelect('quarterly', $subqueryWhere,$complete);
	$q .= " UNION ALL ";
	$subqueryWhere = " and year(a.started) = year(CURRENT_TIMESTAMP) ";
	$q .= $this->periodicTasksBaseSelect('annual',$subqueryWhere,$complete);
	$q .= ") ";
	return $q;
}

	public function updateReceiptsAuth($id, $project, $by){
		$sql = " UPDATE tasks as t ";
		$sql .= " SET ";
		$sql .= "t.updated = CURRENT_TIMESTAMP, ";		
		$sql .= " t.receipts_auth_project = '".$project."', ";
		$sql .= " t.receipts_auth_by = '".$by."' ";
		$sql .= " WHERE t.id = ".$id." ";
		return $sql;

	}
	
	public function updateMaterialsAuth($id, $project, $by){
		$sql = " UPDATE tasks as t ";
		$sql .= " SET ";
		$sql .= "t.updated = CURRENT_TIMESTAMP, ";		
		$sql .= " t.materials_auth_project = '".$project."', ";
		$sql .= " t.materials_auth_by = '".$by."' ";
		$sql .= " WHERE t.id = ".$id." ";
		return $sql;
	}
		

	public function copy($sourceId){
	
			$sql = " INSERT INTO tasks ";
			$sql .= " (name, ";
			$sql .= " project_id, ";
			$sql .= " location_id, ";
			$sql .= " type_id, ";			
			$sql .= " task_order, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			$sql .= " started, ";
			$sql .= " updated, ";
			$sql .= " hours_estimated, ";
			$sql .= " hours_actual, ";
			$sql .= " hours_notes, ";
			$sql .= " materials_auth_project, ";
			$sql .= " materials_auth_by, ";
			$sql .= " receipts_auth_project, ";
			$sql .= " receipts_auth_by) ";
			$sql .= " SELECT ";
			$sql .= " name, ";
			$sql .= " project_id, ";
			$sql .= " location_id, ";
			$sql .= " type_id, ";			
			$sql .= " task_order, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " hours_estimated, ";
			$sql .= " 0, ";
			$sql .= " hours_notes, ";
			$sql .= " 'no', ";
			$sql .= " 'n/a copied task', ";
			$sql .= " 'no', ";
			$sql .= " 'n/a copied task' ";
			$sql .= " FROM tasks t ";
			$sql .= " WHERE t.id = ".$sourceId." ";
			
			return $sql;
			
	}
	
	

}
?>
