<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class ActivityTypeLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($caption = 'AllActivityTypes'){
		$link = $this->listing();
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $activityTypeId = 0, $caption = 'ActivityType'){
		$link = $this->detail($pageAction,$activityTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	public function listing(){
		$link = 'pr_ActivityType_List.php';
		return $link;
	}
	public function listingPaged($found, $resultPage, $perPage){
		$l = $this->listing().'?resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	public function detail($pageAction, $activityTypeId = 0){
		$link = 'pr_ActivityType_Detail.php?pageAction='.$pageAction;
		if ($activityTypeId != 0){
			$link .= '&activityTypeId='.$activityTypeId;
		}
		return $link;
	}	
	public function detailAddHref($caption = '+ActivityType'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($activityTypeId,$caption = 'ViewActivityType'){
		$l = $this->detailHref('VIEW',$activityTypeId,$caption);
		return $l;	
	}
	public function detailEditHref($activityTypeId,$caption = 'EditActivityType'){
		$l = $this->detailHref('EDIT',$activityTypeId,$caption);
		return $l;	
	}
	public function detailViewEditHref($activityTypeId = 0, $viewCaption = 'ViewActivityType'){
		
		if ($activityTypeId != 0){
			$links = $this->detailViewHref($activityTypeId,$viewCaption);
			$links .= $this->detailEditHref($activityTypeId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	
	
}

class ActivityTypeList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new ActivityTypeSQL;	
	}
	
	public function setDetails($resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'Activity Types';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		$typeL = new ActivityTypeLinks($menuType,$menuStyle);		
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
		$sql = $this->sql->countActivityTypes();
		$this->found = dbGetCount($sql, 'total_types');
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
		$typeL = new ActivityTypeLinks;
		$pagingLinks = $typeL->listingPaged($this->found,$this->resultPage,$this->perPage);	
		$at = new ActivityType;
		$at->setDetails(0,'ADD');
		$quickEdit =$at->editForm();			
		$list = openDisplayList('activity-types','Activity Types',$pagingLinks,$quickEdit);

		$heading = wrapTh('Display Order');
		$heading .= wrapTh('Activity Type');
		$heading .= wrapTh('Description');
		$heading .= wrapTh('Notes');
		$heading .= wrapTh('Highlight Style');
		$list .= wrapTr($heading);
		
		$sql = $this->sql->listActivityTypes($this->resultPage,$this->perPage);
				
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$u = new ActivityType;
			$u->id = $row["id"];
			$u->name = ($row["name"]);
			$u->description = ($row["description"]);
			$u->notes = ($row["notes"]);
			$u->highlightStyle = ($row["highlight_style"]);
			$u->displayOrder = $row["display_order"];
			$u->formatForDisplay();

			$detail = wrapTd($u->displayOrder);
			$link = $typeL->detailViewEditHref($u->id,$u->name);
			$detail .= wrapTd($link);
			$detail .=  wrapTd($u->description);			
			$detail .= wrapTd($u->notes);
			$detail .= wrapTd($u->highlightStyle);			
			$list .=  wrapTr($detail,$u->highlightStyle);
		}
		$result->close();
		}

		$list .= closeDisplayList();
		return $list;		
	}
}

class ActivityType {
    public $id = 0;
    public $name;		
    public $description;	
    public $notes;
	public $displayOrder = 0;
	public $highlightStyle = 'none';
    public $created;
    public $updated;
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = New ActivityTypeSQL;
	}

    // set class properties with record values from database
	public function setDetails($detailId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailId;

		$sql = $this->sql->infoActivityType($this->id);

		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
	  	{
			$this->name = ($row["name"]);
			$this->description = ($row["description"]);
			$this->notes = ($row["notes"]);
			$this->displayOrder = $row["display_order"];			
			$this->highlightStyle = ($row["highlight_style"]);
			$this->created = ($row["created"]);			
			$this->updated = ($row["updated"]);			
		}
		$result->close();
		}
				
	}	
		
	function pageTitle(){
		$heading = openDiv('section-heading-title');
		if ($this->pageMode != 'ADD'){
			$heading .= $this->name;
		} else {
			$heading .= 'Add New Activity Type';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		$typesL = new ActivityTypeLinks($menuType,$menuStyle);		
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
		$this->created = getTimestampDate($this->created);
		$this->updated = getTimestampDate($this->updated);
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
		
		$detail = openDisplayDetails('activity-type', 'Activity Type Details');
	 						
		$detail .= captionedParagraph('name', 'Name', $this->name);
		$detail .= captionedParagraph('description', 'Description', $this->description);
		$detail .= captionedParagraph('created', 'Created', $this->created);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);
		$detail .= captionedParagraph('notes', 'Notes', $this->notes);
		$detail .= captionedParagraph('highlight-style', 'Highlight', $this->highlightStyle);
		$detail .= captionedParagraph('display-order', 'Display Order', $this->displayOrder);

		$detail .= closeDisplayDetails();
		return $detail;
	}


	public function getActivityTypeSelectList(
		$selectedValue = '0', 
		$idName = 'activityTypeId', 
		$disabled = 'false',
		$showLink = true,
		$onChangeJS = NULL){

	
		
		$sql = $this->sql->selectOptions_ActivityTypes($selectedValue, $disabled);
		
		$defaultValue = 0;
		$defaultCaption = '-select Activity Type';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
		$select = getSelectList($idName,$allOptions,'none',$disabled,$onChangeJS);
		if ($showLink == true){
			$l = new ActivityTypeLinks;	
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}	
		
	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$legend = 'Add Activity Type';
		} else {
			$legend = 'Edit Activity Type';
		}
		$entity = 'activity-type';
		$c = new ActivityTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm('activity-type',$legend,'pr_ActivityType_Save.php',$contextMenu);

		//formRequired fields		
		$fields = inputFieldName($entity,$this->name,'name','Activity Type');
		$fields .= inputFieldNumber($entity,$this->displayOrder,'displayOrder','Display Order');
		$fields .= inputFieldHighlightStyle($entity,$this->highlightStyle,'highlightStyle');
		$formRequired = $fields;
		
		//formOptional fields
		$fields = inputFieldDescription($entity,$this->description,'description');
		$fields .= inputFieldNotes($entity,$this->notes,'notes');
		$formOptional = $fields;

		//formSubmit fields
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('activityTypeId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
	
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);	
		return $form;
	}
	
	public function collectPostValues(){

		$this->id = $_POST['activityTypeId'];
		$this->name = dbEscapeString($_POST['name']); 
		$this->description = dbEscapeString($_POST['description']); 
		$this->notes = dbEscapeString($_POST['notes']); 		
		$this->highlightStyle = dbEscapeString($_POST['highlightStyle']); 		
		$this->displayOrder = $_POST['displayOrder'];
		$this->pageMode = $_POST['mode'];	
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE activity_types AS p ";
			$sql .= " SET ";
			$sql .= " p.name = '".$this->name."', ";
			$sql .= " p.description = '".$this->description."', ";
			$sql .= " p.updated = CURRENT_TIMESTAMP, ";
			$sql .= " p.highlight_style = '".$this->highlightStyle."', ";
			$sql .= " p.display_order = ".$this->displayOrder.", ";
			$sql .= " p.notes = '".$this->notes."' ";
			$sql .= " WHERE p.id = ".$this->id."  ";		
				
			$result = dbRunSQL($sql);
			
		} else {
			$sql = " INSERT INTO activity_types ";
			$sql .= " (name, ";
			$sql .= " created, ";
			$sql .= " updated, ";
			$sql .= " description, ";
			$sql .= " display_order, ";
			$sql .= " highlight_style, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= "'".$this->description."', ";
			$sql .= $this->displayOrder.", ";
			$sql .= "'".$this->highlightStyle."', ";
			$sql .= "'".$this->notes."') ";
			
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertedId();
		}
	
	}
	
}
 
class ActivityTypeSQL{
public function columnsActivityType(){
	$c = "pt.id, ";
	$c .= " pt.description, ";
	$c .= " pt.name, ";
	$c .= " pt.updated, ";
	$c .= " pt.created, ";
	$c .= " pt.display_order, ";
	$c .= " pt.highlight_style, ";
	$c .= " pt.notes ";
	return $c;	
}

public function infoActivityType($typeId){
	$q = " SELECT ";	
	$q .= $this->columnsActivityType();
	$q .= " FROM activity_types pt ";
	$q .= " WHERE  ";
	$q .= " pt.id = '".$typeId."' ";
	return $q;
}

public function listActivityTypes($resultPage, $rowsPerPage){
	$q = " SELECT ";	
	$q .= $this->columnsActivityType();
	$q .= " FROM activity_types pt ";
	$q .= " ORDER BY display_order, name ";
	$q .= sqlLimitClause($resultPage, $rowsPerPage);
	return $q;	
}

public function countActivityTypes(){
	$q = " SELECT ";	
	$q .= " COUNT(*) total_types ";
	$q .= " FROM activity_types pt ";
	return $q;	
}

public function selectOptions_ActivityTypes($selectedValue, $disabled){
	$q = " SELECT ";
	$q .= " pt.id as value, ";
	$q .= " pt.name as caption, ";
	$q .= " pt.display_order ";
	$q .= " FROM activity_types pt ";
	if ($disabled == 'true'){
		$q .= " WHERE pt.id = ".$selectedValue." ";
	}
	$q .= " ORDER BY display_order, caption ";
	return $q;	
}
}
?>
