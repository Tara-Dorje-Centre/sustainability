<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class LocationTypeLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($caption = 'AllLocationTypes'){
		$link = $this->listing();
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $locationTypeId = 0, $caption = 'LocationType'){
		$link = $this->detail($pageAction,$locationTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing(){
		$link = 'pr_LocationType_List.php';
		return $link;
	}
		
	public function listingPaged($found, $resultPage, $perPage){
		$l = $this->listing().'?resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $locationTypeId = 0){
		$link = 'pr_LocationType_Detail.php?pageAction='.$pageAction;
		if ($locationTypeId != 0){
			$link .= '&locationTypeId='.$locationTypeId;
		}
		return $link;
	}	
	public function detailAddHref($caption = '+LocationType'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($locationTypeId,$caption = 'ViewLocationType'){
		$l = $this->detailHref('VIEW',$locationTypeId,$caption);
		return $l;	
	}
	public function detailEditHref($locationTypeId,$caption = 'EditLocationType'){
		$l = $this->detailHref('EDIT',$locationTypeId,$caption);
		return $l;	
	}
	public function detailViewEditHref($locationTypeId = 0, $viewCaption = 'ViewLocationType'){
		
		if ($locationTypeId != 0){
			$links = $this->detailViewHref($locationTypeId,$viewCaption);
			$links .= $this->detailEditHref($locationTypeId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	
	
}

class LocationTypeList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new LocationTypeSQL;	
	}
	
	public function setDetails($resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'Location Types';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$typeL = new LocationTypeLinks($menuType,$menuStyle);	
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
		$sql = $this->sql->countLocationTypes();
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
		$typeL = new LocationTypeLinks;
		$pagingLinks = $typeL->listingPaged($this->found,$this->resultPage,$this->perPage);
		$lt = new LocationType;
		$lt->setDetails(0,'ADD');
		$quickEdit = $lt->editForm();
		$list = openDisplayList('location-types','Location Types',$pagingLinks,$quickEdit);

		$heading = wrapTh('Display Order');
		$heading .= wrapTh('Location Type');
		$heading .= wrapTh('Description');
		$heading .= wrapTh('Notes');
		$heading .= wrapTh('Highlight Style');
		
		$list .= wrapTr($heading);
		
		$sql = $this->sql->listLocationTypes($this->resultPage,$this->perPage);

		$result = dbGetResult($sql);	
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$u = new LocationType;
			$u->id = $row["id"];
			$u->name = stripslashes($row["name"]);
			$u->description = stripslashes($row["description"]);
			$u->notes = stripslashes($row["notes"]);
			$u->highlightStyle = stripslashes($row["highlight_style"]);
			$u->displayOrder = $row["display_order"];
			$u->formatForDisplay();

			$detail =  wrapTd($u->displayOrder);			
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

class LocationType {
    public $id = 0;
    public $name;		
    public $description;	
    public $notes;
	public $highlightStyle = 'none';
	public $displayOrder = 0;
    public $created;
    public $updated;
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new LocationTypeSQL;
	}
    // set class properties with record values from database
	public function setDetails($detailId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailId;

		$sql = $this->sql->infoLocationType($this->id);
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{
			$this->name = ($row["name"]);
			$this->description = ($row["description"]);
			$this->notes = ($row["notes"]);
			$this->highlightStyle = ($row["highlight_style"]);
			$this->displayOrder = $row["display_order"];
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
			$heading .= 'Add New Location Type';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		$typesL = new LocationTypeLinks($menuType,$menuStyle);
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
		
		$detail = openDisplayDetails('location-type','Location Type Details');		
	 						
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
	
	public function getLocationTypeSelectList(
		$selectedValue = '0', 
		$idName = 'locationTypeId', 
		$disabled = 'false',
		$showLink = true,
		$onChangeJS = NULL){

		
		$sql = $this->sql->selectOptions_LocationTypes($selectedValue, $disabled);
		
		$defaultValue = '0';
		$defaultCaption = '-select Location Type';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
		$select = getSelectList($idName,$allOptions,'none',$disabled,$onChangeJS);
		if ($showLink == true){
			$l = new LocationTypeLinks;	
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$legend = 'Add Location Type';
		} else {
			$legend = 'Edit Location Type';
		}
		$entity = 'location-type';
		$c = new LocationTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend, 'pr_LocationType_Save.php',$contextMenu);
		
		
		//formRequired fields	
		$fields = inputFieldName($entity,$this->name,'name','Location Type');

		$fields .= inputFieldNumber($entity,$this->displayOrder,'displayOrder','Display Order');

		$fields .= inputFieldHighlightStyle($entity,$this->highlightStyle,'highlightStyle');


		$formRequired = $fields;
		
		//formOptional fields
		$fields = inputFieldDescription($entity,$this->description,'description');
		
		$fields .= inputFieldNotes($entity,$this->notes,'notes');


		$formOptional = $fields;
		
		//formSubmit fields
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('locationTypeId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){

		$this->id = $_POST['locationTypeId'];
		$this->name = dbEscapeString($_POST['name']); 
		$this->description = dbEscapeString($_POST['description']); 
		$this->notes = dbEscapeString($_POST['notes']); 		
		$this->highlightStyle = dbEscapeString($_POST['highlightStyle']); 		
		$this->displayOrder = $_POST['displayOrder']; 		
		
		$this->pageMode = $_POST['mode'];	
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE location_types AS p ";
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
			$sql = " INSERT INTO location_types ";
			$sql .= " (name, ";
			$sql .= " created, ";
			$sql .= " updated, ";
			$sql .= " description, ";
			$sql .= " highlight_style, ";
			$sql .= " display_order, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= "'".$this->description."', ";
			$sql .= "'".$this->highlightStyle."', ";
			$sql .= " ".$this->displayOrder.", ";
			$sql .= "'".$this->notes."') ";
			
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertedId();
		}
	
	}
	
} 

class LocationTypeSQL{
	public function columnsLocationType(){
		$c = "lt.id, ";
		$c .= " lt.description, ";
		$c .= " lt.name, ";
		$c .= " lt.updated, ";
		$c .= " lt.created, ";
		$c .= " lt.highlight_style, ";
		$c .= " lt.display_order, ";
		$c .= " lt.notes ";
		return $c;	
	}

	public function infoLocationType($typeId){
		$q = " SELECT ";	
		$q .= $this->columnsLocationType();
		$q .= " FROM location_types lt ";
		$q .= " WHERE  ";
		$q .= " lt.id = ".$typeId." ";
		return $q;
	}

	public function listLocationTypes($resultPage, $rowsPerPage){
		$q = " SELECT ";	
		$q .= $this->columnsLocationType();
		$q .= " FROM location_types lt ";
		$q .= " ORDER BY display_order, name ";
		$q .= sqlLimitClause($resultPage, $rowsPerPage);
		return $q;	
	}

	public function countLocationTypes(){
		$q = " SELECT ";	
		$q .= " COUNT(*) total_types ";
		$q .= " FROM location_types lt ";
		return $q;	
	}

	public function selectOptions_LocationTypes($selectedValue, $disabled){
		$q = " SELECT ";
		$q .= " lt.id as value, ";
		$q .= " lt.name as caption, ";
		$q .= " lt.display_order as sort_key ";
		$q .= " FROM location_types lt ";
		if ($disabled == 'true'){
			$q .= " WHERE lt.id = ".$selectedValue." ";
		}
		$q .= " ORDER BY sort_key, name ";
		return $q;	
	}
}
?>
