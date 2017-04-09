<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class UserTypeLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($caption = 'AllUserTypes'){
		$link = $this->listing();
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $userTypeId = 0, $caption = 'UserType'){
		$link = $this->detail($pageAction,$userTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing(){
		$link = 'sys_UserType_List.php';
		return $link;
	}
		
	public function listingPaged($found, $resultPage, $perPage){
		$l = $this->listing().'?resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $userTypeId = 0){
		$link = 'sys_UserType_Detail.php?pageAction='.$pageAction;
		if ($userTypeId != 0){
			$link .= '&userTypeId='.$userTypeId;
		}
		return $link;
	}	
	public function detailAddHref($caption = '+UserType'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($userTypeId,$caption = 'ViewUserType'){
		$l = $this->detailHref('VIEW',$userTypeId,$caption);
		return $l;	
	}
	public function detailEditHref($userTypeId,$caption = 'EditUserType'){
		$l = $this->detailHref('EDIT',$userTypeId,$caption);
		return $l;	
	}
	public function detailViewEditHref($userTypeId = 0, $viewCaption = 'ViewUserType'){
		
		if ($userTypeId != 0){
			$links = $this->detailViewHref($userTypeId,$viewCaption);
			$links .= $this->detailEditHref($userTypeId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	
	
}

class UserTypeList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new UserTypeSQL;
	}
	
	public function setDetails($resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'User Types';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$typeL = new UserTypeLinks($menuType,$menuStyle);		
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
		$s = new UserTypeSQL;
		$sql = $s->countUserTypes();
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
		
		$s = new UserTypeSQL;
		$sql = $s->listUserTypes($this->resultPage,$this->perPage);
		$result = mysql_query($sql) or die(mysql_error());

		$typeL = new UserTypeLinks;
		$pagingLinks = $typeL->listingPaged($this->found,$this->resultPage,$this->perPage);
		$ut = new UserType;
		$ut->setDetails(0,'ADD');
		$quickEdit = $ut->editForm();
		$list = openDisplayList('user-types','User Profile Types',$pagingLinks,$quickEdit);
		
		$heading = wrapTh('User Type');
		$heading .= wrapTh('Description');
		$heading .= wrapTh('Notes');
		$heading .= wrapTh('Highlight Style');
		$list .= wrapTr($heading);

		while($row = mysql_fetch_array($result))
		{	
			$u = new UserType;
			$u->id = $row["id"];
			$u->name = stripslashes($row["name"]);
			$u->description = stripslashes($row["description"]);
			$u->notes = stripslashes($row["notes"]);
			$u->highlightStyle = stripslashes($row["highlight_style"]);
			$u->formatForDisplay();

			$link = $typeL->detailViewEditHref($u->id,$u->name);
			$detail = wrapTd($link);
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

class UserType {
    public $id = 0;
    public $name;		
    public $description;	
    public $notes;
	public $highlightStyle = 'none';
    public $created;
    public $updated;
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new UserTypeSQL;
	}
	
    // set class properties with record values from database
	public function setDetails($detailId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailId;

		$sql = $this->sql->infoUserType($this->id);
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
			{	
			$this->name = stripslashes($row["name"]);
			$this->description = stripslashes($row["description"]);
			$this->notes = stripslashes($row["notes"]);
			$this->highlightStyle = stripslashes($row["highlight_style"]);
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
			$heading .= 'Add New User Type';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$typesL = new UserTypeLinks($menuType,$menuStyle);
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
				
		$detail = openDisplayDetails('user-type','User Profile Type Details');
	 						
		$detail .= captionedParagraph('name', 'Name', $this->name);
		$detail .= captionedParagraph('description', 'Description', $this->description);
		$detail .= captionedParagraph('created', 'Created', $this->created);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);
		$detail .= captionedParagraph('notes', 'Notes', $this->notes);
		$detail .= captionedParagraph('highlight-style', 'Highlight', $this->highlightStyle);

		$detail .= closeDisplayDetails();
		return $detail;
	}

	public function getUserTypeSelectList(
		$selectedValue = '0', 
		$idName = 'userTypeId', 
		$disabled = 'false',
		$showLink = false,
		$changeJS = NULL){
	
		$sql = $this->sql->selectOptions_UserTypes($selectedValue,$disabled);
		
		$defaultValue = 0;
		$defaultCaption = '-select User Type';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
				
		$select = getSelectList($idName,$allOptions,'none',$disabled,$changeJS);
		if ($showLink == true){
			$l = new UserTypeLinks;			
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}	
	
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$legend = 'Add User Type';
		} else {
			$legend = 'Edit User Type';
		}
		$entity = 'user-type';
		$c = new ProjectTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'sys_UserType_Save.php',$contextMenu);
		
		//formRequired fields	
		$fields = inputFieldName($entity,$this->name,'name','User Type');

		//end required fields		
		$formRequired = $fields;
		
		//formOptional fields
		$fields = inputFieldDescription($entity,$this->description,'description');
				
		//$fields .= inputFieldNumber($entity,$this->displayOrder,'displayOrder','Display Order');

		$fields .= inputFieldHighlightStyle($entity,$this->highlightStyle,'highlightStyle');

		$fields .= inputFieldNotes($entity,$this->notes,'notes');

		//end optional fields
		$formOptional = $fields;
		
		//formsubmit
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('userTypeId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){

		$this->id = $_POST['userTypeId'];
		$this->name = mysql_real_escape_string($_POST['name']); 
		$this->description = mysql_real_escape_string($_POST['description']); 
		$this->notes = mysql_real_escape_string($_POST['notes']); 		
		$this->highlightStyle = mysql_real_escape_string($_POST['highlightStyle']); 		
		
		$this->pageMode = $_POST['mode'];	
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE user_types AS p ";
			$sql .= " SET ";
			$sql .= " p.name = '".$this->name."', ";
			$sql .= " p.description = '".$this->description."', ";
			$sql .= " p.updated = CURRENT_TIMESTAMP, ";
			$sql .= " p.highlight_style = '".$this->highlightStyle."', ";
			$sql .= " p.notes = '".$this->notes."' ";
			$sql .= " WHERE p.id = ".$this->id."  ";			
			$result = mysql_query($sql) or die(mysql_error());
		} else {
			$sql = " INSERT INTO user_types ";
			$sql .= " (name, ";
			$sql .= " created, ";
			$sql .= " updated, ";
			$sql .= " description, ";
			$sql .= " highlight_style, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= "'".$this->description."', ";
			$sql .= "'".$this->highlightStyle."', ";
			$sql .= "'".$this->notes."') ";
			$result = mysql_query($sql) or die(mysql_error());
			
			$this->id = mysql_insert_id();
		}
	
	}
	
} 
class UserTypeSQL{
public function columnsUserType(){
$c = "pt.id, ";
$c .= " pt.description, ";
$c .= " pt.name, ";
$c .= " pt.updated, ";
$c .= " pt.created, ";
$c .= " pt.highlight_style, ";
$c .= " pt.notes ";
return $c;	
}
public function infoUserType($typeId){
$q = " SELECT ";	
$q .= $this->columnsUserType();
$q .= " FROM user_types pt ";
$q .= " WHERE  ";
$q .= " pt.id = '".$typeId."' ";
return $q;
}
public function listUserTypes($resultPage, $rowsPerPage){
$q = " SELECT ";	
$q .= $this->columnsUserType();
$q .= " FROM user_types pt ";
$q .= " ORDER BY name ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;	
}
public function countUserTypes(){
$q = " SELECT ";	
$q .= " COUNT(*) total_types ";
$q .= " FROM user_types pt ";
return $q;	
}
public function selectOptions_UserTypes($selectedValue,$disabled){
	$q = " SELECT ";
	$q .= " pt.id as value, ";
	$q .= " pt.name as caption ";
	$q .= " FROM user_types pt ";
	if ($disabled == 'true'){
		$q .= " WHERE pt.id = ".$selectedValue." ";	
	}
	$q .= " ORDER BY name ";
	return $q;	
}
}
?>