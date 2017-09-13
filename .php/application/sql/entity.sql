<?php
namespace application\sql;

class entitySQL extends  baseEntitySQL implements IentitySQL{
	protected $baseTable = 'baseTable';
	protected $fieldDate = 'date_reported';
	protected $fieldApproved = 'approved';
	protected $fieldDoneBy = 'done_by';
	protected $fieldId = 'id';
	protected $fieldTypeId = 'type_id';
	protected $fieldParentId = 'parent_id';
	protected $fieldCount = 'count_details';
	protected $fieldOptionCaption = 'caption';
	protected $fieldOptionValue = 'value';

	public function getBaseTable(){
		return $this->baseTable;
	}

	protected function cols(){
		$c = $this->select();
		$c .= " * ";
		return $c;
	}

	protected function colsCount($first = true){
		$c = $this->select($first);
		$c .= $this->alias('COUNT(*)', $this->fieldCount);
		return $c;
	}

	protected function colsSummary($first = true){
		$c = $this->colsCount($first);
		return $c;
	}
	
	protected function colsDoneBy($first = true){
		$c = $this->select($first);
		$c .= $this->nullReplace($this->fieldDoneBy)." done_by ";
		return $c;
	}
	protected function colsCalendarLinks($first = true){
		$c = $this->select($first);
		$c .= $this->dateYear($this->fieldDate)." AS year, ";
		$c .= $this->dateMonth($this->fieldDate)." AS month ";
		return $c;
	}
	
	protected function groupByCalendarLinks($first = true){
		$g = $this->groupBy($first);
		$g .= $this->dateYear($this->fieldDate).", ";
		$g .= $this->dateMonth($this->fieldDate)." ";
		return $g;
	}

	protected function groupByType($first = true){
		$g = $this->groupBy($first);
		$g .= " ".$this->fieldTypeId." ";
		return $g;
	}

	protected function groupByParent($first = true){
		$g = $this->groupBy($first);
		$q .= $this->fieldParentId." ";
		return $q;
	}

	protected function colsOption($first = true){
		$c = $this->select($first);
		$c .= " id as value, ";
		$c .= " name as caption ";
		return $c;
	}

	protected function tables($joinTypes = false){
		$f = " FROM ".$this->baseTable." ";
		return $f;
	}

	protected function whereYearMonth($year = 0, $month = 0, $first = false){
		if ($year > 0){
			$w = $this->where($first);
			$f = $this->dateYear($this->fieldDate);
			$w .= $this->equalTo($f, $year);	

			if ($month > 0){
				$w .= $this->where(false);
				$f = $this->dateMonth($this->fieldDate);
				$w .= $this->equalTo($f, $month);	
			}
		}
		return $w;
	}

	protected function whereApproved($approved = 'no', $first = false){
		if ($approved == 'yes'){
		$w = $this->whereString($approved, $this->fieldApproved, true, $first);
	}
	return $w;
	}

	protected function whereDoneBy($doneBy = 'EVERYONE', $first = false){
		if ($doneBy != 'EVERYONE'){
		$w = $this->whereString($doneBy, $this->fieldDoneBy, true, $first);
		$w .= " AND show_always != 'no' ";
	} else {
		$w = '';
	}
	return $w;
	}

	protected function whereId($id, $first = true){
		$w = $this->whereNumber($id, $this->fieldId, $first);
		return $w;
	}

	public function count(){
		$q = $this->colsCount();
		$q .= $this->tables(false);
		//$q .= $this->whereId($id);
		return $q;	
	}

	public function countDoneBy($doneBy = 'EVERYONE'){
	$q = $this->colsCount();
	$q .= $this->tables(false);
	$q .= $this->whereDoneBy($doneBy, true);
	return $q;
	}


	public function info($id = 0){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= $this->whereId($id);
		return $q;
	}

	public function list($page = 1, $rows = 10){
		$q = $this->cols();
		$q .= $this->tables();
		$q .= sqlLimitClause($page, $rows);
		return $q;
	}

	public function listDoneBy($doneBy, $page = 1, $rows = 10){
		$q = $this->cols();
		$q .= $this->tables();
		$q .= $this->whereDoneBy($doneBy, true);
		$q .= sqlLimitClause($page, $rows);
		return $q;
	}

	public function summary(){
		$q = $this->colsSummary();
		$q .= $this->tables(true);
		return $q;
	} 
	
	public function summaryByType(){
	
		$q = $this->colsSummary();
		$q .= $this->tables(false);
		$q .= $this->groupByType();
		
		return $q;
	} 

	public function calendarLinksDoneBy($doneBy = 'EVERYONE'){
	$q = $this->colsCalendarLinks();
	$q .= $this->tables(false);
	$q .= $this->whereDoneBy($doneBy);
	$q .= $this->groupByCalendarLinks();
	
	return $q;
	}

	public function options(){
	//$selectedId = 0, $disabled = 'false'){
	$q = $this->colsOption(true);
	$q .= $this->tables(true);
	/*if (($selectedId > 0) && ($disabled != 'false')){
		$q .= $this->whereId($selectedId);
	}*/
	$q .= $this->orderBy()." caption ";
	return $q;	
	}


}

?>
