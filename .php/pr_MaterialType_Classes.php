<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class MaterialTypeLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($caption = 'AllMaterialTypes'){
		$link = $this->listing();
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $materialTypeId = 0, $caption = 'MaterialType'){
		$link = $this->detail($pageAction,$materialTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing(){
		$link = 'pr_MaterialType_List.php';
		return $link;
	}
		
	public function listingPaged($found, $resultPage, $perPage){
		$l = $this->listing().'?resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $materialTypeId = 0){
		$link = 'pr_MaterialType_Detail.php?pageAction='.$pageAction;
		if ($materialTypeId != 0){
			$link .= '&materialTypeId='.$materialTypeId;
		}
		return $link;
	}	
	public function detailAddHref($caption = '+MaterialType'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($materialTypeId,$caption = 'ViewMaterialType'){
		$l = $this->detailHref('VIEW',$materialTypeId,$caption);
		return $l;	
	}
	public function detailEditHref($materialTypeId,$caption = 'EditMaterialType'){
		$l = $this->detailHref('EDIT',$materialTypeId,$caption);
		return $l;	
	}
	public function detailViewEditHref($materialTypeId = 0, $viewCaption = 'ViewMaterialType'){
		
		if ($materialTypeId != 0){
			$links = $this->detailViewHref($materialTypeId,$viewCaption);
			$links .= $this->detailEditHref($materialTypeId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	
	
}

class MaterialTypeList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new MaterialTypeSQL;
	}
	
	public function setDetails($resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'Material Types';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		$typeL = new MaterialTypeLinks($menuType,$menuStyle);		
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
		$sql = $this->sql->countMaterialTypes();
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
		$sql = $this->sql->listMaterialTypes($this->resultPage,$this->perPage);
		$result = mysql_query($sql) or die(mysql_error());

		$typeL = new MaterialTypeLinks;
		$pagingLinks = $typeL->listingPaged($this->found,$this->resultPage,$this->perPage);
		$mt = new MaterialType;
		$mt->setDetails(0,'ADD');
		$quickEdit = $mt->editForm();
		$list = openDisplayList('material-types','Material Types',$pagingLinks,$quickEdit);

		$heading = wrapTh('Display Order');
		$heading .= wrapTh('Material Type');
		$heading .= wrapTh('Description');
		$heading .= wrapTh('Notes');
		$heading .= wrapTh('Highlight Style');		
		$list .= wrapTr($heading);

		while($row = mysql_fetch_array($result))
		{	
			$u = new MaterialType;
			$u->id = $row["id"];
			$u->name = stripslashes($row["name"]);
			$u->description = stripslashes($row["description"]);
			$u->notes = stripslashes($row["notes"]);
			$u->displayOrder = $row["display_order"];
			$u->highlightStyle = stripslashes($row["highlight_style"]);
			$u->formatForDisplay();

			$detail =  wrapTd($u->displayOrder);			
			$link = $typeL->detailViewEditHref($u->id,$u->name);
			$detail .= wrapTd($link);
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

class MaterialType {
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
		$this->sql = new MaterialTypeSQL;
	}

    // set class properties with record values from database
	public function setDetails($detailId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailId;

		$sql = $this->sql->infoMaterialType($this->id);
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
			{	
			$this->name = stripslashes($row["name"]);
			$this->description = stripslashes($row["description"]);
			$this->notes = stripslashes($row["notes"]);
			$this->displayOrder = $row["display_order"];			
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
			$heading .= 'Add New Material Type';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		$typesL = new MaterialTypeLinks($menuType,$menuStyle);
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

		$detail = openDisplayDetails('material-type','Material Type Details');				
	 						
		$detail .= captionedParagraph('name', 'Name', $this->name);
		$detail .= captionedParagraph('description', 'Description', $this->description);
		$detail .= captionedParagraph('created', 'Created', $this->created);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);
		$detail .= captionedParagraph('notes', 'Notes', $this->notes);
		$detail .= captionedParagraph('display-order', 'displayOrder', $this->displayOrder);	
		$detail .= captionedParagraph('highlight-style', 'Highlight', $this->highlightStyle);

		$detail .= closeDisplayDetails();
		return $detail;
	}
	
	public function getMaterialTypeSelectList(
		$selectedValue = '0', 
		$idName = 'materialTypeId', 
		$disabled = 'false', 
		$showLink = true){
	
		
		$sql = $this->sql->selectOptions_MaterialTypes($selectedValue, $disabled);
		
		$defaultValue = 0;
		$defaultCaption = '-select Material Type';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
		$select = getSelectList($idName,$allOptions,'none',$disabled );
		if ($showLink == true){
			$l = new MaterialTypeLinks;	
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}	
	
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$legend = 'Add Material Type';
		} else {
			$legend = 'Edit Material Type';
		}
		$entity = 'material-type';
		$c = new MaterialTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'pr_MaterialType_Save.php',$contextMenu);

		//formRequired fields	
		$fields = inputFieldName($entity,$this->name,'name','Material Type');
		
		$fields .= inputFieldNumber($entity,$this->displayOrder,'displayOrder','Display Order');

		$fields .= inputFieldHighlightStyle($entity,$this->highlightStyle,'highlightStyle');
		
		
		$formRequired = $fields;
			
		//formOptional fields
		$fields = inputFieldDescription($entity,$this->description,'description');
		
		
		$fields .= inputFieldNotes($entity,$this->notes,'notes');

		$formOptional = $fields;
		
		//formSubmit fields		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('materialTypeId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){

		$this->id = $_POST['materialTypeId'];
		$this->name = mysql_real_escape_string($_POST['name']); 
		$this->description = mysql_real_escape_string($_POST['description']); 
		$this->notes = mysql_real_escape_string($_POST['notes']); 		
		$this->highlightStyle = mysql_real_escape_string($_POST['highlightStyle']); 		
		$this->displayOrder = mysql_real_escape_string($_POST['displayOrder']); 		
		
		$this->pageMode = $_POST['mode'];	
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE material_types AS p ";
			$sql .= " SET ";
			$sql .= " p.name = '".$this->name."', ";
			$sql .= " p.description = '".$this->description."', ";
			$sql .= " p.updated = CURRENT_TIMESTAMP, ";
			$sql .= " p.display_order = ".$this->displayOrder.", ";			
			$sql .= " p.highlight_style = '".$this->highlightStyle."', ";
			$sql .= " p.notes = '".$this->notes."' ";
			$sql .= " WHERE p.id = ".$this->id."  ";			
			$result = mysql_query($sql) or die(mysql_error());
		} else {
			$sql = " INSERT INTO material_types ";
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
			$sql .= " ".$this->displayOrder.", ";			
			$sql .= "'".$this->highlightStyle."', ";
			$sql .= "'".$this->notes."') ";
			$result = mysql_query($sql) or die(mysql_error());
			
			$this->id = mysql_insert_id();
		}
	
	}
	
} 
class MaterialTypeSQL{
public function columnsMaterialType(){
$c = "pt.id, ";
$c .= " pt.description, ";
$c .= " pt.name, ";
$c .= " pt.updated, ";
$c .= " pt.created, ";
$c .= " pt.highlight_style, ";
$c .= " pt.display_order, ";
$c .= " pt.notes ";
return $c;	
}
public function infoMaterialType($typeId){
$q = " SELECT ";	
$q .= $this->columnsMaterialType();
$q .= " FROM material_types pt ";
$q .= " WHERE  ";
$q .= " pt.id = '".$typeId."' ";
return $q;
}
public function listMaterialTypes($resultPage, $rowsPerPage){
$q = " SELECT ";	
$q .= $this->columnsMaterialType();
$q .= " FROM material_types pt ";
$q .= " ORDER BY display_order, name ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;	
}
public function countMaterialTypes(){
$q = " SELECT ";	
$q .= " COUNT(*) total_types ";
$q .= " FROM material_types pt ";
return $q;	
}
public function selectOptions_MaterialTypes($selectedValue, $disabled){
	$q = " SELECT ";
	$q .= " pt.id as value, ";
	$q .= " pt.name as caption ";
	$q .= " FROM material_types pt ";
	if ($disabled == 'true'){
		$q .= " WHERE pt.id = ".$selectedValue." ";
	}
	$q .= " ORDER BY display_order, name ";
	return $q;	
}
}
?>