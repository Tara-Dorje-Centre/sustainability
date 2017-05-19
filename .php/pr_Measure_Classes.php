<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class MeasureLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($taskId,$caption = 'Measures'){
		$link = $this->listing($taskId);
		$l = $this->formatHref($caption,$link);
		return $l;	
	}	
	private function detailHref($pageAction = 'VIEW', $measureId = 0, $taskId = 0, $caption = 'Measure'){
		$link = $this->detail($pageAction,$measureId,$taskId);
		$l = $this->formatHref($caption,$link);
		return $l;	
	}
	public function listing($taskId){
		$link = 'pr_Measure_List.php?taskId='.$taskId;
		return $link;
	}
	public function listingPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&resultsPageMeasure=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	public function detail($pageAction, $measureId, $taskId = 0){
		$link = 'pr_Measure_Detail.php?pageAction='.$pageAction;
		if($taskId != 0){
			$link .= '&taskId='.$taskId;			
		}
		if ($measureId != 0){
			$link .= '&measureId='.$measureId;
		}
		return $link;
	}	
	public function detailAddHref($taskId,$caption = '+Measure'){
		$l = $this->detailHref('ADD',0,$taskId,$caption);
		return $l;	
	}
	public function detailViewHref($measureId,$caption = 'ViewMeasure'){
		$l = $this->detailHref('VIEW',$measureId,0,$caption);
		return $l;	
	}
	public function detailEditHref($measureId,$caption = 'EditMeasure'){
		$l = $this->detailHref('EDIT',$measureId,0,$caption);
		return $l;	
	}
	public function detailViewEditHref($measureId = 0, $viewCaption = 'Measure'){	
		if ($measureId != 0){
			$links = $this->detailViewHref($measureId,$viewCaption);
			$links .= $this->detailEditHref($measureId,'#');
		}
		return $links;
	}	

}

class MeasureList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $task;
	private $sql;
	
	public function __construct(){
		$this->task = new Task;
		$this->sql = new MeasureSQL;
	}
	
	public function setDetails($taskId, $resultPage = 1, $resultsPerPage = 10){
		$this->task->id = $taskId;	
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->task->setDetails($this->task->id, 0, 'VIEW');
		$this->setFoundCount();
	}
		
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= $this->task->project->name.br();
		$title .= 'Task: '.$this->task->name;
		$title .= closeDiv();
		return $title;	
	}	

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projects = new ProjectLinks($menuType,$menuStyle);
		$tasks = new TaskLinks($menuType,$menuStyle);
		$measures = new MeasureLinks($menuType,$menuStyle);
					
		$menu = $projects->openMenu('section-heading-links');
		

		$menu .= $tasks->detailViewHref($this->task->id);
		$menu .= $projects->detailViewHref($this->task->project->id);
		
		$menu .= $projects->resetMenu();
		
		$menu .= $measures->detailAddHref($this->task->id);
		$menu .= $measures->listingHref($this->task->id);		
		
		$menu .= $projects->closeMenu();	
		return $menu;			
	}	
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$sql = $this->sql->countMeasuresByTask($this->task->id);
		$this->found = getSQLCount($sql, 'total_measures');
	}
		
		
		
	public function printPage(){
		
		$heading = $this->getPageHeading();
		$details = $this->getPageDetails();

		$site = new _SiteTemplate;
		$site->setSiteTemplateDetails($heading, $details);
		$site->printSite();
		
	}

	public function getPageDetails(){
	
		$details = $this->getListing();
		return $details;
		
	}
		
	public function getListing($pagingBaseLink = 'USE_LISTING'){
		$sql = $this->sql->listMeasuresByTask($this->task->id,$this->resultPage,$this->perPage);
		$result = mysql_query($sql) or die(mysql_error());		
		
		$ml = new MeasureLinks;		
		if ($pagingBaseLink == 'USE_LISTING'){
			$base = $ml->listing($this->task->id);
		} else { 
			$base = $pagingBaseLink;
		}
		$pagingLinks = $ml->listingPaged($base,$this->found,$this->resultPage,$this->perPage);		
		$m = new Measure;
		$m->setDetails(0,$this->task->id,'ADD');
		$quickEdit = $m->editForm();
		$list = openDisplayList('measure','Measures',$pagingLinks,$quickEdit);

		$heading =  wrapTh('Measure Type');
		$heading .=  wrapTh('Value');
		$heading .=  wrapTh('Name');
		$heading .=  wrapTh('Description');
		$heading .=  wrapTh('Date Reported');

		$list .=  wrapTr($heading);

		while($row = mysql_fetch_array($result))
		{	
			$m = new Measure;
			$m->id = $row["id"];
			$m->name = stripslashes($row["name"]);
			$m->measureType = stripslashes($row["measure_type"]);
			$m->unitSymbol = stripslashes($row["unit_symbol"]);
			$m->unitType = stripslashes($row["unit_type"]);
			$m->value = $row["value"];
			$m->dateReported = $row["date_reported"];
			$m->description = stripslashes($row["description"]);
			$m->formatForDisplay();
			
			$link = $ml->detailViewEditHref($m->id,$m->getMeasureTypeAndUnitSymbol());
			$detail =  wrapTd($link);			
			$detail .=  wrapTd($m->value);
			$detail .= wrapTd($m->name);
			$detail .=  wrapTd($m->description);
			$detail .=  wrapTd($m->dateReported);
			
			$list .=  wrapTr($detail);
		}
		mysql_free_result($result);

		$list .= closeDisplayList();
		return $list;
	}
}


class Measure {
    public $id = 0;
    public $locationId = 0;	
	public $name;
    public $description;
    public $dateReported;
    public $updated;
    public $value;	
    public $measureTypeUnitId = 0;	
	public $measureType;
	public $unitType;
	public $unitSymbol;
    public $notes;
	public $task;	
	private $sql;
	// property to support edit/view/add mode of calling page
    public $pageMode;
	
	public function __construct(){
		$this->task = new Task;
		$this->sql = new MeasureSQL;
	}
	

	public function setDetails($detailMeasureId, $parentTaskId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailMeasureId;
		$this->task->id = $parentTaskId;
		
		$sql = $this->sql->infoMeasure($this->id);
		$result = mysql_query($sql) or die(mysql_error());

		while($row = mysql_fetch_array($result))
			{	
			$this->task->id = $row["task_id"];
			$this->locationId = $row["location_id"];
			$this->name = stripslashes($row["name"]);
			$this->description = stripslashes($row["description"]);
			$this->dateReported = $row["date_reported"];
			$this->updated = $row["updated"];	
			$this->value = $row["value"];	
			$this->measureTypeUnitId = $row["measure_type_unit_id"];						
			$this->measureType = stripslashes($row["measure_type"]);
			$this->unitSymbol = stripslashes($row["unit_symbol"]);
			$this->unitType = stripslashes($row["unit_type"]);
			$this->notes = stripslashes($row["notes"]);
		}
		mysql_free_result($result);

		$this->setParentTask();				
	}	
	
	public function setParentTask(){
		$this->task->setDetails($this->task->id, 0, $this->pageMode);
	}
		
	function pageTitle(){	
		$title = openDiv('section-heading-title','none');
		//show page mode for debugging
		//$heading .= '['.$this->pageMode.']';
		$title .= $this->task->project->name.br();
		$title .= 'Task: '.$this->task->name;		
		$title .= closeDiv();
		return $title;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projects = new ProjectLinks($menuType,$menuStyle);
		$tasks = new TaskLinks($menuType,$menuStyle);
		$measures = new MeasureLinks($menuType,$menuStyle);
					
		$menu = $projects->openMenu('section-heading-links');

		$menu .= $tasks->detailViewHref($this->task->id);
		$menu .= $projects->detailViewHref($this->task->project->id);			
		//$menu .= $measures->listingHref($this->task->id);
		$menu .= $projects->resetMenu();
		if ($this->pageMode == 'VIEW'){
			$menu .= $measures->detailEditHref($this->id);
		}
		if ($this->pageMode == 'EDIT'){
			$menu .= $measures->detailViewHref($this->id);
		}
		
		$menu .= $projects->closeMenu();
		return $menu;
	}
		
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}

	public function getMeasureTypeAndUnitSymbol(){
		$t = $this->measureType.'('.$this->unitSymbol.')';
		return $t;
	}

	
	public function formatForDisplay(){
		$this->name = displayLines($this->name);
		$this->description = displayLines($this->description);
		$this->notes = displayLines($this->notes);
	}

	public function printPage(){
		
		$heading = $this->getPageHeading();
		$details = $this->getPageDetails();

		$site = new _SiteTemplate;
		$site->setSiteTemplateDetails($heading, $details);
		$site->printSite();
		
	}
	
	public function getPageDetails(){			

		if ($this->pageMode == 'EDIT' or $this->pageMode == 'ADD'){
			$details = $this->editForm();
		} else {
			$details = $this->display();
		}
		return $details;
		
	}
	
	public function display(){
		$this->formatForDisplay();
		
		$detail = openDisplayDetails('measure','Measure Details');
		$detail .= captionedParagraph('measure-reported','Reported',$this->dateReported);
		$detail .= captionedParagraph('measure-updated','Updated',$this->updated);		
		$detail .= captionedParagraph('measure-value','Value',$this->value);
		$m = new MeasureTypeUnit;
		$select = $m->getMeasureTypeUnitSelectList($this->measureTypeUnitId,'measureTypeUnitId','true');
		$detail .= captionedParagraph('measure-type-units','Measure Type', $select);
		$detail .= captionedParagraph('unit-of-measure','Unit Type',$this->unitType);
		$detail .= captionedParagraph('measure-name','Measurement',$this->name);
		$detail .= captionedParagraph('measure-desc','Description',$this->description);		
 		$detail .= captionedParagraph('measure-notes','Notes',$this->notes);
		
		$detail .= closeDisplayDetails();
		
		return $detail;
	}
	

	private function setAddRecordDefaults(){	
		global $sessionTime;
		$this->dateReported = $sessionTime;	
		$this->measureTypeUnitId = 0;
		$this->locationId = $this->task->locationId;
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Measure';
		} else {
			$legend = 'Edit Measure';
		}
		$entity = 'measure';
		$c = new ProjectTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'pr_Measure_Save.php',$contextMenu);


		
		$fields = inputFieldNumber($entity,$this->value,'value','Value');

		$m = new MeasureTypeUnit;
		$select = $m->getMeasureTypeUnitSelectList($this->measureTypeUnitId,'measureTypeUnitId','false',false);
		$fields .= captionedInput('Measure Type',$select);

		$fields .= inputFieldTimestamp($entity, 'dateReported', $this->dateReported, 'Date Reported'); 		
		
		//end formRequired
		$formRequired = $fields;
		
		//formOptional
		$fields = inputFieldName($entity,$this->name,'name','Measure');

		$fields .= inputFieldDescription($entity,$this->description,'description');

		$fields .= inputFieldNotes($entity,$this->notes,'notes');

		$formOptional = $fields;

		//hidden fields and submit,reset buttons
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('taskId', $this->task->id);
		$hidden .= getHiddenInput('locationId', $this->locationId);

		$hidden .= getHiddenInput('measureId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
	
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);		
		return $form;
	}
	
	public function collectPostValues(){
		//called by save form prior to running adds/updates
		$this->pageMode = $_POST['mode'];
		
		$this->task->id = $_POST['taskId'];
		$this->id = $_POST['measureId'];
		$this->locationId = $_POST['locationId'];
		$this->description = $conn>escape_string($_POST['description']);
		$this->name = $conn>escape_string($_POST['name']);
		$this->value = $conn>escape_string($_POST['value']); 
		$this->notes = $conn>escape_string($_POST['notes']); 
		$this->dateReported = getTimestampPostValues('dateReported');
		$this->measureTypeUnitId = $_POST['measureTypeUnitId']; 

		$this->setParentTask();
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			
			$sql = " UPDATE measures m ";
			$sql .= " SET ";
			$sql .= " m.name = '".$this->name."', ";
			$sql .= " m.description = '".$this->description."', ";
			$sql .= " m.notes = '".$this->notes."', ";
			$sql .= " m.updated = CURRENT_TIMESTAMP, ";
			$sql .= " m.value = ".$this->value.", ";
			$sql .= " m.date_reported = '".$this->dateReported."', ";
			$sql .= " m.location_id = ".$this->locationId.", ";
			$sql .= " m.measure_type_unit_id = ".$this->measureTypeUnitId." ";
			$sql .= " WHERE m.id = ".$this->id." ";

			$result = mysql_query($sql) or die(mysql_error());
			
		} else {
	
			$sql = " INSERT INTO measures ";
			$sql .= " (name, ";
			$sql .= " task_id, ";
			$sql .= " location_id, ";
			$sql .= " measure_type_unit_id, ";
			$sql .= " date_reported, ";
			$sql .= " value, ";
			$sql .= " description, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= " ".$this->task->id.", ";
			$sql .= " ".$this->locationId.", ";
			$sql .= "".$this->measureTypeUnitId.", ";
			$sql .= " '".$this->dateReported."', ";
			$sql .= "".$this->value.", ";
			$sql .= "'".$this->description."', ";
			$sql .= "'".$this->notes."') ";
			
			$result = mysql_query($sql) or die(mysql_error());
			$this->id = mysql_insert_id();
		}
	
	}
	
} 

class MeasureSQL{
function columnsMeasures(){
$c = " m.id, ";
$c .= " m.task_id, ";
$c .= " m.location_id, ";
$c .= " m.name, ";
$c .= " m.description, ";
$c .= " m.date_reported, ";
$c .= " m.updated, ";
$c .= " m.value, ";
$c .= " m.measure_type_unit_id, ";
$c .= " m.notes, ";
$c .= " mtu.measure_type, ";
$c .= " mtu.unit_type, ";
$c .= " mtu.unit_symbol ";
return $c;	
}
public function infoMeasure($measureId){
$q = " SELECT ";	
$q .= $this->columnsMeasures();
$q .= " FROM measures AS m JOIN measure_type_units_v as mtu ";
$q .= " ON m.measure_type_unit_id = mtu.measure_type_unit_id ";
$q .= " WHERE  ";
$q .= " m.id = '".$measureId."' ";
return $q;
}
public function listMeasuresByTask($selectedTaskId, $resultPage, $rowsPerPage){
$q = " SELECT  ";
$q .= $this->columnsMeasures();
$q .= " FROM measures AS m JOIN measure_type_units_v as mtu ";
$q .= " ON m.measure_type_unit_id = mtu.measure_type_unit_id ";
$q .= " WHERE  ";
$q .= " m.task_id = ".$selectedTaskId." ";
$q .= " ORDER BY ";
$q .= " m.date_reported, m.measure_type_unit_id, m.id ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}
public function countMeasuresByTask($selectedTaskId){
$q = " SELECT  ";
$q .= " COUNT(*) total_measures";
$q .= " FROM measures AS m ";
$q .= " WHERE  ";
$q .= " m.task_id = ".$selectedTaskId." ";
return $q;
}

}
?>
