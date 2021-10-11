<?php
namespace application\entities\projects\sql;

interface IprojectChildSQL{ 
	public function getProjectId($id = 0);
	public function countProject($id = 0);
	public function listProject($id = 0, $page = 1, $rows = 10);
	public function summaryProject($id = 0,$year= 0, $month = 0);
	public function summaryProjectDoneBy($id = 0, $doneBy = 'EVERYONE', $year= 0, $month = 0);
	public function optionsProject($id = 0);
}

class projectChildSQL 
extends \application\sql\entitySQL 
implements IprojectChildSQL{

protected $fieldProjectId = 'project_id';

protected function whereProject($id = 0,	$year= 0, $month = 0, $approved = 'no', $first = true){
	$w = $this->whereNumber($id, $this->fieldProjectId, true);
	$w .= $this->whereYearMonth($year, $month);
	$w .= $this->whereApproved($approved);
	return $w;
}


public function getProjectId($id = 0){
$q = " SELECT  ";
$q .= $this->fieldProjectId;
$q .= $this->tables(false);
$q .= $this->whereId($id);
return $q;
}


public function countProject($id = 0, $year= 0, $month = 0, $approved = 'no'){
	$q = $this->colsCount();
	$q .= $this->tables(false);
	$q .= ' where t.project_id = '.$id.' ';
	//$q .= $this->whereProject($id, $year, $month, $approved);
	return $q;	
}
	

public function listProject($id = 0, $page = 1, $rows = 10, $year= 0, $month = 0, $approved = 'no'){
$q = $this->cols();
$q .= $this->tables(true);
$q .= ' where t.project_id = '.$id.' ';
//$q .= $this->whereProject($id);
$q .= $this->limit($page, $rows);
return $q;
}

public function summaryProject($id = 0,$year= 0, $month = 0, $approved = 'no'){
$q = $this->colsSummary();
$q .= $this->tables(false);
$q .= $this->whereProject($id, $year, $month, $approved);
return $q;
}


public function summaryProjectDoneBy($id = 0, $doneBy = 'EVERYONE', $year = 0, $month = 0, $approved = 'no'){
$q = $this->colsSummary();
$q .= $this->colsDoneBy(false);
$q .= $this->tables(false);
$q .= $this->whereProject($id, $year, $month, $approved);
$q .= $this->whereDoneBy($doneBy);
if ($doneBy == 'EVERYONE'){
$q .= " GROUP BY ".$this->fieldDoneBy." ";
}
return $q;
}



public function calendarLinksProject($id){
$q = $this->colsCalendarLinks();
$q .= $this->tables(false);
  $q .= $this->whereProject($id);
  $q .= $this->groupByCalendarLinks();
  return $q;
}

public function optionsProject($id = 0){
	$q = " SELECT ";
	$q .= " t.id as value, ";
	$q .= " concat_ws(' ',t.task_order,t.name) as caption ";
$q .= $this->tables();
//$q .= $this->whereProject($id);
$q .= " where t.project_id = ".$id;
	$q .= " AND p.pct_done < 1 ";
	$q .= " ORDER BY caption ";
	return $q;	
}


}

?>
