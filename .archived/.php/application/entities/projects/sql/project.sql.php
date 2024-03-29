<?php
namespace application\entities\projects\sql;

class projectSQL 
extends \application\sql\entitySQL{

	public function __construct(){
		$this->baseTable = 'projects';
		$this->fieldId = 'p.id';

	}

	//projects table fields displayed in listing
	protected function cols(){
		$c = $this->select();
		$c .= " p.id, ";
		$c .= " p.type_id, ";
		$c .= " pt.name type_name, ";
		$c .= " pt.highlight_style, ";
		$c .= " p.parent_id, ";
		$c .= " pp.name parent_name, ";
		$c .= " p.name, ";
		$c .= " p.priority, ";
		$c .= " p.started, ";
		$c .= " p.updated, ";
		$c .= " p.pct_done, ";
		$c .= " p.show_always, ";
		$c .= " p.location_id, ";
		$c .= " l.name location_name, ";
		$c .= " p.description, ";
		$c .= " p.summary, ";
		$c .= " p.purpose, ";
		$c .= " p.goals, ";
		$c .= " p.lessons_learned, ";
		//$c .= " p.hours_actual, ";
		$c .= " p.budget_estimated, ";
		$c .= " p.budget_notes, ";
		$c .= " p.hours_estimated, ";
		$c .= " p.hours_notes ";
		return $c;
	}
	
	
	protected function tables($joinTypes = true){

		$t = " FROM projects p ";
		$t .= " LEFT OUTER JOIN locations l ";
		$t .= " ON p.location_id = l.id ";
		
		$t .= " LEFT OUTER JOIN projects pp ";
		$t .= " ON p.parent_id = pp.id ";
		
		if ($joinTypes == true){
			$t .= " LEFT OUTER JOIN project_types pt ";
			$t .= " ON p.type_id = pt.id ";
		}
		return $t;
	
	}
//projects table fields displayed in detail
public function listByStatus($status, $page = 1, $rows = 10){

$q = $this->cols();
$q .= $this->tables();
$q .= $this->whereStatus($status);

$q .= " ORDER BY l.sort_key, p.priority, p.id ";

$q .= $this->limit($page, $rows);
return $q;
}


private function subqueryActivityDoneBy($doneBy){
$q = " (select t.project_id from ";
$q .= " activities a JOIN tasks t ON a.task_id = t.id ";
$q .= " WHERE UPPER(a.done_by) = UPPER('".$doneBy."') ) ";

return $q;
}

public function listByActivityDoneBy($doneBy, $status, $page, $rows){
$q = $this->cols();
$q .= $this->tables();
$q .= $this->whereStatus($status);
$q .= " AND p.id IN ";
$q .= $this->subqueryActivityDoneBy($doneBy);
$q .= " ORDER BY l.sort_key, p.priority, p.id ";

$q .= $this->limit($page, $rows);
return $q;
}

	public function getProjectName($id){
		$sql = 'select name from projects ';
		$sql .= 'where id = '.$id;
		return $sql;
	}
	
	
	public function getProjectTypeId($id){
		$sql = 'select type_id from projects ';
		$sql .= 'where id = '.$id;
		return $sql;
	}
	
	
	public function getLocationId($id){
		$sql = 'select location_id from projects ';
		$sql .= 'where id = '.$id;
		return $sql;
	}
	
	
	

public function countByActivityDoneBy($doneBy, $status){
$q = " SELECT  count(*) total_projects ";
$q .= " FROM projects p ";
$q .= $this->whereStatus($status);

$q .= " AND p.id IN ";
$q .= $this->subqueryActivityDoneBy($doneBy);
return $q;	
}


protected function whereStatus($status = 'OPEN'){
if ($status =='CLOSED'){
	$w = " WHERE p.pct_done = '1.00' "; 
} else {
	$w = " WHERE p.pct_done != '1.00' "; 
}
return $w;
}

public function countByStatus($status){
$q = " SELECT  ";
$q .= " COUNT(*) total_projects ";
$q .= " FROM projects AS p ";
$q .= $this->whereStatus($status);
return $q;
}

public function listChildProjects($projectId){
$q = " SELECT  ";
$q .= $this->cols();
$q .= $this->tables();

$q .= " WHERE p.parent_id = ".$projectId." "; 
$q .= " ORDER BY p.sort_key, p.priority, p.id ";
//needs paging and limit
return $q;
}

public function options(){
	$q = " SELECT ";
	$q .= " p.id as value, ";
	$q .= " p.name as caption ";
	$q .= " FROM projects p ";
	
	$q .= " WHERE p.pct_done < 1 ";

	$q .= " ORDER BY p.name ";
	return $q;	
}

public function optionsTypesInUse(){
	$q = " SELECT ";
	$q .= " p.type_id as value, ";
	$q .= " pt.name as caption ";
	$q .= " FROM projects p ";
	$q .= " JOIN project_types pt ";
	$q .= " ON p.type_id = pt.id ";
	$q .= " WHERE p.pct_done < 1 ";
	$q .= " GROUP BY pt.name, p.type_id ";
	return $q;	
}

public function optionsByType($typeId = 0){
	$q = " SELECT ";
	$q .= " p.id as value, ";
	$q .= " p.name as caption ";
	$q .= " FROM projects p ";
	$q .= " WHERE p.pct_done < 1 ";
	if ($typeId > 0){
	$q .= " AND p.type_id = ".$typeId." ";
	}
	$q .= " ORDER BY p.name ";
	return $q;	
}


	public function copy($idSource){

			$sql = " INSERT INTO projects ";
			$sql .= " (name, ";
			$sql .= " parent_id, ";
			$sql .= " location_id, ";
			$sql .= " started, ";
			$sql .= " updated, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			//$sql .= " lessons_learned, ";
			$sql .= " show_always, ";
			$sql .= " type_id, ";
			$sql .= " purpose) ";
			$sql .= " SELECT ";
			$sql .= " name, ";
			$sql .= " id, ";
			$sql .= " location_id, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			//$sql .= " lessons_learned, ";
			$sql .= " show_always, ";
			$sql .= " type_id, ";
			$sql .= " purpose ";
			$sql .= " FROM projects p WHERE p.id = ".$idSource." ";
			
			return $sql;
			
	}

	public function copyTasks($idSource, $idCopy){
			//insert all tasks from source project to copy project 
			//substituting the copy project id		
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
			$sql .= " ".$idCopy.", ";
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
			$sql .= " 'n/a copied project', ";
			$sql .= " 'no', ";
			$sql .= " 'n/a copied project' ";
			$sql .= " FROM tasks t WHERE t.project_id = ".$idSource." ";

		return $sql;
	}

/*

public function count(){
*/

}
?>
