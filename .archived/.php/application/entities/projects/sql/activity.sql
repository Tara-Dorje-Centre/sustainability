<?php
namespace application\entities\projects\sql;

class activitySQL extends taskChildSQL{

	public function __construct(){
		$this->baseTable = 'activities';
		$this->typeTable = 'activity_types';
		$this->fieldDate = 'started';
	}



protected function cols($first = true){
$c = $this->select($first);
$c .= " a.id,  ";
$c .= " a.task_id,  ";
$c .= " a.type_id, ";
$c .= " t.name task_name, ";
$c .= " at.name type_name, ";
$c .= " t.project_id, ";
$c .= " p.name project_name, ";
$c .= " a.done_by,  ";
$c .= " a.started,  ";
$c .= " a.updated,  ";
//$c .= " a.activity_order,  ";
$c .= " a.hours_estimated,  ";
$c .= " a.hours_actual,  ";
$c .= " a.comments, ";
$c .= " a.link_url, ";
$c .= " a.link_text, ";
$c .= " pt.highlight_style ";	
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
$q .= " LEFT OUTER JOIN activity_types at ON a.type_id = at.id ";
$q .= " LEFT OUTER JOIN project_types pt ON p.type_id = pt.id ";
}
return $q;
}


public function calendarSummaryMyActivity($doneBy, $year, $month){
$q = "SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " DATE(a.started) started, ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year, ";
$q .= " a.done_by, ";
$q .= " p.id, ";
$q .= " min(p.name) name, ";
$q .= " 1 ordering, ";
$q .= " min(pt.highlight_style) highlight_style ";
$q .= $this->tables();
$q .= $this->_equalDoneBy($doneBy, true);
$q .= $this->_equalYearMonth($year, $month, false);

$q .= " GROUP BY  ";
$q .= " DATE(a.started), ";
$q .= " a.done_by, ";
$q .= " p.id ";
//
$q .= " UNION ALL ";

$q .= " SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " LAST_DAY(a.started) started, ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year, ";
$q .= " 'MonthTotal' done_by, ";
$q .= " 0 id, ";
$q .= " 'MonthTotal' name, ";
$q .= " 100 ordering, ";
$q .= " 'highlight-yellow' highlight_style ";
$q .= $this->tables();
$q .= $this->_equalDoneBy($doneBy, true);
$q .= $this->_equalYearMonth($year, $month, false);

$q .= " GROUP BY ";
$q .= " LAST_DAY(a.started) ";
$q .= " ORDER BY started, ordering, id, done_by ";
return $q;	
}



public function calendarSummaryProject($id = 0, $year, $month){
//show daily tally by task and person
$q = "SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " DATE(a.started) started, ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year, ";
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
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year, ";
$q .= " SUM(a.hours_actual) done_by, ";
$q .= " t.id, ";
$q .= " t.name, ";
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
