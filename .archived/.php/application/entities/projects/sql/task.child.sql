<?php
namespace application\entities\projects\sql;

interface ItaskChildSQL extends IprojectChildSQL{
	public function getTaskId($id = 0);
	public function countTask($id = 0);
	public function listTask($id = 0, $page = 1, $rows = 10);
	public function summaryTask($id = 0, $year= 0, $month = 0);
	//public function summaryTaskGroupType($id = 0, $year= 0, $month = 0);
	public function summaryTaskDoneBy($id = 0, $doneBy = 'EVERYONE', $year= 0, $month = 0);
	public function optionsTask($d = 0);
}

class taskChildSQL extends projectChildSQL implements ItaskChildSQL{

protected $fieldTaskId = 'task_id';

protected function whereTask($id = 0,	$year= 0, $month = 0, $approved = 'no', $first = true){
	$w = $this->_equalNumber($id, $this->fieldTaskId, $first);
	$w .= $this->_equalYearMonth($year, $month);
	$w .= $this->_equalApproved($approved);
	return $w;
}

public function getTaskId($id = 0){
$q = " SELECT  ";
$q .= $this->fieldTaskId;
$q .= $this->tables(false);
$q .= $this->where($id);
return $q;
}

public function countTask($id = 0, $year= 0, $month = 0, $approved = 'no'){
	$q = $this->colsCount();
	$q .= $this->tables(false);
	$q .= $this->whereTask($id, $year, $month, $approved);
	return $q;	
}


public function listTask($id = 0, $page = 1, $rows =10, $year= 0, $month = 0, $approved = 'no'){
$q = $this->cols();
$q .= $this->tables(false);
$q .= $this->whereTask($id, $year, $month, $approved);
	$q .= sqlLimitClause($page, $rows);
return $q;
}



public function summaryTask($id = 0,$year= 0, $month = 0, $approved = 'no'){
$q = $this->colsSummary();
$q .= $this->tables(false);
$q .= $this->whereTask($id, $year, $month, $approved);
return $q;
}


public function summaryTaskDoneBy($id = 0, $doneBy = 'EVERYONE', $year = 0, $month = 0, $approved = 'no'){
$q = $this->colsSummary();
$q .= $this->colsDoneBy(false);
$q .= $this->tables(false);
$q .= $this->whereTask($id, $year, $month, $approved);
$q .= $this->_equalDoneBy($doneBy);
if ($doneBy == 'EVERYONE'){
$q .= " GROUP BY ".$this->fieldDoneBy." ";
}
return $q;
}



public function calendarLinksTask($id){
	$q = $this->colsCalendarLinks();
$q .= $this->tables(false);
	$q .= $this->whereTask($id);
  	$q .= $this->groupByCalendarLinks();
	return $q;
}

public function optionsTask($id = 0){
//$selectedId = 0,$disabled = 'false'){
	$q = " SELECT ";
	$q .= " p.id as value, ";
	$q .= " concat_ws(' ',t.task_order,p.name) as caption ";
$q .= $this->tables();
$q .= $this->whereTask($id);
	//$q .= " AND p.pct_done < 1 ";
	//if ($disabled == 'true'){
	//	$q .= " AND t.id = ".$selectedId." ";	
	//}
	$q .= " ORDER BY caption ";
	return $q;	
}

}


?>
