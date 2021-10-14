<?php
namespace application\entities\projects\sql;

interface ItaskChildSQL {
	public function getTaskId($id = 0);
	public function countTask($id = 0);
	public function listTask($id = 0, $page = 1, $rows = 10);
	public function summaryTask($id = 0, $year= 0, $month = 0);
	//public function summaryTaskGroupType($id = 0, $year= 0, $month = 0);
	public function summaryTaskDoneBy($id = 0, $doneBy = 'EVERYONE', $year= 0, $month = 0);
	public function optionsTask($d = 0);
}

abstract class taskChildSQL 
extends \application\sql\entitySQL {
//implements ItaskChildSQL{

protected $fieldTaskId = 'task_id';


protected function whereTask($id = 0,	$year= 0, $month = 0, $approved = 'no', $first = true){
	$w = $this->whereId($id, $first, $this->fieldTaskId);
	//$w .= $this->_equalYearMonth($year, $month);
	//$w .= $this->_equalApproved($approved);
	return $w;
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
	
	$q .= " ORDER BY caption ";
	return $q;	
}

}


?>
