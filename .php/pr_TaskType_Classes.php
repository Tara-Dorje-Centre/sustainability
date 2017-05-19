<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class TaskTypeLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($caption = 'AllTaskTypes'){
		$link = $this->listing();
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $taskTypeId = 0, $caption = 'TaskType'){
		$link = $this->detail($pageAction,$taskTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing(){
		$link = 'pr_TaskType_List.php';
		return $link;
	}
		
	public function listingPaged($found, $resultPage, $perPage){
		$l = $this->listing().'?resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $taskTypeId = 0){
		$link = 'pr_TaskType_Detail.php?pageAction='.$pageAction;
		if ($taskTypeId != 0){
			$link .= '&taskTypeId='.$taskTypeId;
		}
		return $link;
	}	
	public function detailAddHref($caption = '+TaskType'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($taskTypeId,$caption = 'ViewTaskType'){
		$l = $this->detailHref('VIEW',$taskTypeId,$caption);
		return $l;	
	}
	public function detailEditHref($taskTypeId,$caption = 'EditTaskType'){
		$l = $this->detailHref('EDIT',$taskTypeId,$caption);
		return $l;	
	}
	public function detailViewEditHref($taskTypeId = 0, $viewCaption = 'ViewTaskType'){
		
		if ($taskTypeId != 0){
			$links = $this->detailViewHref($taskTypeId,$viewCaption);
			$links .= $this->detailEditHref($taskTypeId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	
	
}

class TaskTypeList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new TaskTypeSQL;
	}
	
	public function setDetails($resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'Task Types';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$typeL = new TaskTypeLinks($menuType,$menuStyle);		
		$menuL = new MenuLinks($menuType,$menuStyle);
		
		
		$menu = $typeL->openMenu('section-heading-links');
		$menu .= $menuL->linkReference();
		$menu .= $typeL->resetMenu();
		$menu .= $typeL->detailAddHref();
		$menu .= $typeL->listingHref();		
		$menu .= $typeL->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$sql = $this->sql->countTaskTypes();
		$this->found = getSQLCount($sql, 'total_types');
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
	
	public function getListing(){
		$sql = $this->sql->listTaskTypes($this->resultPage,$this->perPage);
		$result = mysql_query($sql) or die(mysql_error());

		$typeL = new TaskTypeLinks;
		$pagingLinks = $typeL->listingPaged($this->found,$this->resultPage,$this->perPage);
		$tt = new TaskType;
		$tt->setDetails(0,'ADD');
		$quickEdit = $tt->editForm();
		$list = openDisplayList('task-types','Task Types',$pagingLinks,$quickEdit);

		$heading = wrapTh('Display Order');		
		$heading .= wrapTh('Task Type');
		$heading .= wrapTh('Frequency');
		$heading .= wrapTh('Description');
		$heading .= wrapTh('Notes');
		$heading .= wrapTh('Highlight Style');
		$list .= wrapTr($heading);

		while($row = mysql_fetch_array($result))
		{	
			$u = new TaskType;
			$u->id = $row["id"];
			$u->name = stripslashes($row["name"]);
			$u->description = stripslashes($row["description"]);
			$u->notes = stripslashes($row["notes"]);
			$u->highlightStyle = stripslashes($row["highlight_style"]);
			$u->frequency = stripslashes($row["frequency"]);
			$u->displayOrder = $row["display_order"];

			$u->formatForDisplay();

			$detail = wrapTd($u->displayOrder);						
			$link = $typeL->detailViewEditHref($u->id,$u->name);
			$detail .= wrapTd($link);
			$detail .= wrapTd($u->frequency);
			$detail .=  wrapTd($u->description);			
			$detail .= wrapTd($u->notes);
			$detail .= wrapTd($u->highlightStyle);			
			$list .=  wrapTr($detail,$u->highlightStyle);
		}
		mysql_free_result($result);

		$list .= closeDisplayList();
		return $list;
	}
}

class TaskType {
    public $id = 0;
    public $name;		
    public $description;	
    public $notes;
	public $highlightStyle = 'none';
	public $frequency = 'none';
	public $displayOrder = 0;
    public $created;
    public $updated;
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new TaskTypeSQL;
	}
	
    // set class properties with record values from database
	public function setDetails($detailId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailId;

		$sql = $this->sql->infoTaskType($this->id);
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
			{	
			$this->name = stripslashes($row["name"]);
			$this->description = stripslashes($row["description"]);
			$this->notes = stripslashes($row["notes"]);
			$this->highlightStyle = stripslashes($row["highlight_style"]);
			$this->frequency = stripslashes($row["frequency"]);
			$this->displayOrder = $row["display_order"];
			$this->created = stripslashes($row["created"]);			
			$this->updated = stripslashes($row["updated"]);			
		}
		mysql_free_result($result);
				
	}	
		
	function pageTitle(){
		$heading = openDiv('section-heading-title');
		if ($this->pageMode != 'ADD'){
			$heading .= $this->name;
		} else {
			$heading .= 'Add New Task Type';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';		
		$typesL = new TaskTypeLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);

		$menu = $typesL->openMenu('section-heading-links');
		$menu .= $menuL->linkReference();
		$menu .= $typesL->resetMenu();
		if ($this->pageMode == 'VIEW'){
			$menu .= $typesL->detailEditHref($this->id);
		} elseif ($this->pageMode == 'EDIT'){
			$menu .= $typesL->detailViewHref($this->id);
		}
		$menu .= $typesL->listingHref();		
		$menu .= $typesL->closeMenu();
		return $menu;
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
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
		$detail = openDisplayDetails('project-type','Project Type Details');
	 						
		$detail .= captionedParagraph('name', 'Task Type', $this->name);
		$detail .= captionedParagraph('frequency', 'Frequency', $this->frequency);
		$detail .= captionedParagraph('description', 'Description', $this->description);
		$detail .= captionedParagraph('created', 'Created', $this->created);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);
		$detail .= captionedParagraph('notes', 'Notes', $this->notes);
		$detail .= captionedParagraph('highlight-style', 'Highlight', $this->highlightStyle);
		$detail .= captionedParagraph('highlight-style', 'Display Order', $this->displayOrder);

		$detail .= closeDisplayDetails();
		return $detail;
	}
	
	public function getFrequencySelectList($selectedValue = 'none', $idName = 'frequency', $disabled = 'false'){
		$sql = "SELECT f.frequency value, f.description caption ";
		$sql .= "FROM task_type_frequencies f ";
		if ($disabled == 'true'){
			$sql = " WHERE f.frequency = '".$selectedValue."' ";	
		}
		$sql .= " ORDER BY f.display_order ";
		
		$defaultValue = 'none';
		$defaultCaption = '-not set';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
		$select = getSelectList($idName,$allOptions,'none',$disabled );
		return $select;
	}	
	
	public function getTaskTypeSelectList(
		$selectedValue = '0', 
		$idName = 'taskTypeId', 
		$disabled = 'false',
		$showLink = false,
		$changeJS = NULL){
		$sql = $this->sql->selectOptions_TaskTypes($selectedValue,$disabled);
		
		$defaultValue = 0;
		$defaultCaption = '-select Task Type';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
		$select = getSelectList($idName,$allOptions,'none',$disabled,$changeJS);
		if ($showLink == true){
			$l = new TaskTypeLinks;	
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}	
	
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$legend = 'Add Task Type';
		} else {
			$legend = 'Edit Task Type';
		}
		$entity = 'task-type';
		$c = new ProjectTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'pr_TaskType_Save.php',$contextMenu);
		
		//formRequired fields	
		$fields = inputFieldName($entity,$this->name,'name','Task Type');

		$fields .= inputFieldNumber($entity,$this->displayOrder,'displayOrder','Display Order');

		$fields .= inputFieldHighlightStyle($entity,$this->highlightStyle,'highlightStyle');

		

		$formRequired = $fields;
			
		//formOptional fields
		$fields = inputFieldDescription($entity,$this->description,'description');
		
		
		$select = $this->getFrequencySelectList($this->frequency,'frequency');
		$fields .=inputFieldSelect($entity, $select,'Frequency');

		
		$fields .= inputFieldNotes($entity,$this->notes,'notes');
		//end formOptional
		$formOptional = $fields;

				
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('taskTypeId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){

		$this->id = $_POST['taskTypeId'];
		$this->name = $conn>escape_string($_POST['name']); 
		$this->description = $conn>escape_string($_POST['description']); 
		$this->notes = $conn>escape_string($_POST['notes']); 		
		$this->highlightStyle = $conn>escape_string($_POST['highlightStyle']); 		
		$this->frequency = $conn>escape_string($_POST['frequency']);
		$this->displayOrder = $conn>escape_string($_POST['displayOrder']);
		$this->pageMode = $_POST['mode'];	
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE task_types AS p ";
			$sql .= " SET ";
			$sql .= " p.name = '".$this->name."', ";
			$sql .= " p.frequency = '".$this->frequency."', ";
			$sql .= " p.display_order = ".$this->displayOrder.", ";
			$sql .= " p.description = '".$this->description."', ";
			$sql .= " p.updated = CURRENT_TIMESTAMP, ";
			$sql .= " p.highlight_style = '".$this->highlightStyle."', ";
			$sql .= " p.notes = '".$this->notes."' ";
			$sql .= " WHERE p.id = ".$this->id."  ";			
			$result = mysql_query($sql) or die(mysql_error());
		} else {
			$sql = " INSERT INTO task_types ";
			$sql .= " (name, ";
			$sql .= " frequency, ";
			$sql .= " display_order, ";
			$sql .= " created, ";
			$sql .= " updated, ";
			$sql .= " description, ";
			$sql .= " highlight_style, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= " '".$this->name."', ";
			$sql .= " '".$this->frequency."', ";
			$sql .= " ".$this->displayOrder.", ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " '".$this->description."', ";
			$sql .= " '".$this->highlightStyle."', ";
			$sql .= " '".$this->notes."') ";
			$result = mysql_query($sql) or die(mysql_error());
			
			$this->id = mysql_insert_id();
		}
	
	}
	
} 
class TaskTypeSQL{
public function columnsTaskType(){
$c = " pt.id, ";
$c .= " pt.description, ";
$c .= " pt.name, ";
$c .= " pt.updated, ";
$c .= " pt.created, ";
$c .= " pt.highlight_style, ";
$c .= " pt.frequency, ";
$c .= " pt.display_order, ";
$c .= " pt.notes ";
return $c;	
}
public function infoTaskType($typeId){
$q = " SELECT ";	
$q .= $this->columnsTaskType();
$q .= " FROM task_types pt ";
$q .= " WHERE  ";
$q .= " pt.id = '".$typeId."' ";
return $q;
}
public function listTaskTypes($resultPage, $rowsPerPage){
$q = " SELECT ";	
$q .= $this->columnsTaskType();
$q .= " FROM task_types pt ";
$q .= " ORDER BY display_order ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;	
}
public function countTaskTypes(){
$q = " SELECT ";	
$q .= " COUNT(*) total_types ";
$q .= " FROM task_types pt ";
return $q;	
}
public function selectOptions_TaskTypes($selectedValue,$disabled){
	$q = " SELECT ";
	$q .= " pt.id as value, ";
	$q .= " pt.name as caption ";
	$q .= " FROM task_types pt ";
	if ($disabled == 'true'){
		$q .= " WHERE pt.id = ".$selectedValue." ";	
	}
	$q .= " ORDER BY display_order ";
	return $q;	
}
}
?>
