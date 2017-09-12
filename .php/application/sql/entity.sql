<?php
namespace application\sql;

interface IentitySQL{
	
	public function info($id);
	public function count();
	public function list($page = 1, $rows = 10);
	public function summary();
	public function options();
	
}

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


class baseEntitySQL {


	protected function alias($object, $alias, $useComma = false){
		$a = $object." AS ".$alias;
		if ($useComma == true){
			$a .= ", ";
		}
		return $a;
	}
	protected function clause($first = true, $term = 'SELECT', $connector = ","){
		if ($first == true){
			$c = $term;
		} else {
			$c = $connector;
		}
		return $c;
	}
	
	protected function select($first = true){
	 	return $this->clause($first, ' SELECT ', ',');
	}
	 
	protected function from($first = true){
	 	return $this->clause($first, ' FROM ', ',');
	}
	
	protected function where($first = true){
	 	return $this->clause($first, ' WHERE ', ' AND ');
	}
	 
 	protected function orderBy($first = true){
	 	return $this->clause($first, ' ORDER BY ', ',');
	}
	 
 	protected function groupBy($first = true){
	 	return $this->clause($first, ' GROUP BY ', ',');
 	}
	
	protected function equalTo($field, $value, $quoteValue = false){
		if ($quoteValue == true){
			$v = $this->quote($value);
		} else {
			$v = $value;
		}
		return $field." = ".$v." ";
	}
	
	protected function notEqualTo($field, $value, $quoteValue = false){
		if ($quoteValue == true){
			$v = $this->quote($value);
		} else {
			$v = $value;
		}
		return $field." != ".$v." ";
	}
	
 	protected function whereNumber($id, $field, $first = false){
		$q = $this->where($first);
		$q .= $this->equalTo($field, $id);
		return $q;
 	}
	protected function quote($value){
		return "'".$value."'";
	}
	protected function whereString($value, $field, $useUpper = false, $first = false){
		$w = $this->where($first);
	 	if ($useUpper == false){
	 		$v = $this->quote($value);
	 		$f = $field;
	 	} else {
	 		$f = $this->upper($field);
	 		$v = $this->upper($value, true);
	 	}
	 	$w = $this->equalTo($f, $v);
	 	return $w;
	}
	 
	protected function nullReplace($field, $nullReplace = "'unspecified'"){
		return " IFNULL(".$field.", ".$nullReplace.") ";
	}
	
	protected function dateYear($field){
		return " YEAR(".$field.") ";
	}
	
	protected function dateMonth($field){
		return " MONTH(".$field.") ";
	}
	
	protected function upper($fieldOrValue, $quote = false){
		if ($quote == true){
			$f = $this->quote($fieldOrValue);
		} else {
			$f = $fieldOrValue;
		}
		return " UPPER(".$f.") ";
	}
	
	
	protected function sum($field){
		return " SUM(".$field.") ";
	}
	
	protected function dateDay($field){
		return " DAY(".$field.") ";
	}
	
}

?>
