<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class UnitOfMeasureLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($caption = 'AllUnits'){
		$link = $this->listing();
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $unitOfMeasureId = 0, $caption = 'Unit'){
		$link = $this->detail($pageAction,$unitOfMeasureId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing(){
		$link = 'pr_UnitOfMeasure_List.php';
		return $link;
	}
		
	public function listingPaged($found, $resultPage, $perPage){
		$l = $this->listing().'?resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $unitOfMeasureId = 0){
		$link = 'pr_UnitOfMeasure_Detail.php?pageAction='.$pageAction;
		if ($unitOfMeasureId != 0){
			$link .= '&unitOfMeasureId='.$unitOfMeasureId;
		}
		return $link;
	}	
	public function detailAddHref($caption = '+Unit'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($unitOfMeasureId,$caption = 'ViewUnit'){
		$l = $this->detailHref('VIEW',$unitOfMeasureId,$caption);
		return $l;	
	}
	public function detailEditHref($unitOfMeasureId,$caption = 'EditUnit'){
		$l = $this->detailHref('EDIT',$unitOfMeasureId,$caption);
		return $l;	
	}
	public function detailViewEditHref($unitOfMeasureId = 0, $viewCaption = 'ViewUnit'){
		
		if ($unitOfMeasureId != 0){
			$links = $this->detailViewHref($unitOfMeasureId,$viewCaption);
			$links .= $this->detailEditHref($unitOfMeasureId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	
	
}
class UnitOfMeasureList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new UnitOfMeasureSQL;
	}
	
	public function setDetails($resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'Units of Measure';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$units = new UnitOfMeasureLinks($menuType,$menuStyle);
		//$measureTypes = new MeasureTypeLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);
		
		
		$menu = $units->openMenu('section-heading-links');
		$menu .= $menuL->linkReference();
		$menu .= $units->resetMenu();
		$menu .= $units->detailAddHref();
		$menu .= $units->listingHref();
		//$menu .= $units->resetMenu();
		//$menu .= $measureTypes->listingHref();
		
		$menu .= $units->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$sql = $this->sql->countUnitsOfMeasure();
		$this->found = dbGetCount($sql, 'total_units');
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
		$sql = $this->sql->listUnitsOfMeasure($this->resultPage,$this->perPage);


		$uml = new UnitOfMeasureLinks;
		$pagingLinks = $uml->listingPaged($this->found,$this->resultPage,$this->perPage);
		$uom = new UnitOfMeasure;
		$uom->setDetails(0,'ADD');
		$quickEdit = $uom->editForm();
		$list = openDisplayList('unit-of-measure','Units',$pagingLinks,$quickEdit);

		$heading = wrapTh('Type');
		$heading .= wrapTh('Unit');
		$heading .= wrapTh('Symbol');
		$heading .= wrapTh('Notes');
		$list .= wrapTr($heading);

		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	
			$u = new UnitOfMeasure;
			$u->id = $row["id"];
			$u->name = stripslashes($row["name"]);
			$u->type = stripslashes($row["type"]);
			$u->symbol = stripslashes($row["symbol"]);
			$u->notes = stripslashes($row["notes"]);
			$u->formatForDisplay();

			$detail =  wrapTd($u->type);
			$link = $uml->detailViewEditHref($u->id,$u->name);
			$detail .= wrapTd($link);
			$detail .=  wrapTd($u->symbol);			
			$detail .= wrapTd($u->notes);
			$list .=  wrapTr($detail);
		}
		$result->close();
		}

		$list .= closeDisplayList();
		return $list;
	}
}

class UnitOfMeasure {
    public $id = 0;
    public $type;	
    public $name;	
    public $symbol;
    public $notes;
    public $created;
    public $updated;
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new UnitOfMeasureSQL;
	}
	
    // set class properties with record values from database
	public function setDetails($detailUnitId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailUnitId;

		$sql = $this->sql->infoUnitOfMeasure($this->id);

		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
			{	
			$this->name = ($row["name"]);
			$this->type = ($row["type"]);
			$this->notes = ($row["notes"]);
			$this->symbol = ($row["symbol"]);	
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
			$heading .= 'Add New Unit Of Measure';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$units = new UnitOfMeasureLinks($menuType,$menuStyle);
		$measureTypes = new MeasureTypeLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);
		

		
		$menu = $units->openMenu('section-heading-links');
		$menu .= $menuL->linkReference();
		$menu .= $units->resetMenu();
		if ($this->pageMode == 'VIEW'){
			$menu .= $units->detailEditHref($this->id);
		} elseif ($this->pageMode == 'EDIT'){
			$menu .= $units->detailViewHref($this->id);
		}
		$menu .= $units->listingHref();
		//$menu .= $measureTypes->listingHref();
		
		$menu .= $units->closeMenu();
		return $menu;
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function formatForDisplay(){
		$this->name = displayLines($this->name);
		$this->type = displayLines($this->type);
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
		$detail = openDisplayDetails('unit-of-measure','Unit of Measure Details');
					 						
		$detail .= captionedParagraph('name', 'Name', $this->name);
		$detail .= captionedParagraph('type', 'Type', $this->type);
		$detail .= captionedParagraph('symbol', 'Symbol', $this->symbol);
		$detail .= captionedParagraph('created', 'Created', $this->created);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);
		$detail .= captionedParagraph('notes', 'Notes', $this->notes);

		$detail .= closeDisplayDetails();
		return $detail;
	}
		
	public function getUnitOfMeasureSelectList(
		$selectedValue = 0, 
		$idName = 'unitOfMeasureId', 
		$disabled = 'false', 
		$showLink = true,
		$changeJS = NULL){
	
		$sql = $this->sql->selectOptions_UnitsOfMeasure($selectedValue,$disabled);
		
		$defaultValue = 0;
		$defaultCaption = '-select Unit Type';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
				
		$select = getSelectList($idName,$allOptions,'none',$disabled,$changeJS);
		
		if ($showLink == true){
			$l = new UnitOfMeasureLinks;			
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}	
	
	private function setAddRecordDefaults(){
		
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Unit of Measure';
		} else {
			$legend = 'Edit Unit of Measure';	
		}

		$entity = 'unit-of-measure';
		$c = new ProjectLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'pr_UnitOfMeasure_Save.php',$contextMenu);
		
		//begin required fields
		$fields = inputFieldName($entity,$this->name,'name','Name',50);
	
		//end required fields
		$formRequired = $fields;


		//start optional fields
		$fields = inputFieldName($entity,$this->type,'type','Type',50);
		
		$fields .= inputFieldName($entity,$this->symbol,'symbol','Symbol',10);


		$fields .= inputFieldComments($entity,$this->notes,'notes','Notes',1000);

		//end optional fields (hidden by default)
		$formOptional = $fields;

		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('unitOfMeasureId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;

		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);		
		return $form;
	}
	
	public function collectPostValues(){
		$this->id = $_POST['unitOfMeasureId'];
		$this->name = dbEscapeString($_POST['name']); 
		$this->type = dbEscapeString($_POST['type']); 
		$this->symbol = dbEscapeString($_POST['symbol']); 
		$this->notes = dbEscapeString($_POST['notes']); 		
		$this->pageMode = $_POST['mode'];	
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE units_of_measure AS p ";
			$sql .= " SET ";
			$sql .= " p.name = '".$this->name."', ";
			$sql .= " p.type = '".$this->type."', ";
			$sql .= " p.symbol = '".$this->symbol."', ";
			$sql .= " p.updated = CURRENT_TIMESTAMP, ";
			$sql .= " p.notes = '".$this->notes."' ";
			$sql .= " WHERE p.id = ".$this->id."  ";			
			$result = dbRunSQL($sql);

		} else {
	
			$sql = " INSERT INTO units_of_measure ";
			$sql .= " (name, ";
			$sql .= " created, ";
			$sql .= " updated, ";
			$sql .= " type, ";
			$sql .= " symbol, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= "'".$this->type."', ";
			$sql .= "'".$this->symbol."', ";
			$sql .= "'".$this->notes."') ";
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertedId();
		}
	
	}
	
} 
class UnitOfMeasureSQL{
public function columnsUnitOfMeasure(){
$query = "um.id, ";
$query .= " um.type, ";
$query .= " um.name, ";
$query .= " um.updated, ";
$query .= " um.created, ";
$query .= " um.notes, ";
$query .= " um.symbol ";
return $query;	
}
public function infoUnitOfMeasure($unitId){
$query = " SELECT ";	
$query .= $this->columnsUnitOfMeasure();
$query .= " FROM units_of_measure um ";
$query .= " WHERE  ";
$query .= " um.id = '".$unitId."' ";
return $query;
}
public function listUnitsOfMeasure($resultPage, $rowsPerPage){
$query = " SELECT ";	
$query .= $this->columnsUnitOfMeasure();
$query .= " FROM units_of_measure um ";
$query .= " ORDER BY type, name ";
$query .= sqlLimitClause($resultPage, $rowsPerPage);
return $query;	
}
public function countUnitsOfMeasure(){
$query = " SELECT ";	
$query .= " COUNT(*) total_units ";
$query .= " FROM units_of_measure um ";
return $query;	
}
public function selectOptions_UnitsOfMeasure($selectedValue,$disabled){
	$q = " SELECT ";
	$q .= " um.type, ";
	$q .= " um.id as value, ";
	$q .= " concat(um.name,'(',um.symbol,')') as caption ";
	$q .= " FROM units_of_measure um ";
	if ($disabled == 'true'){
		$q .= " WHERE um.id = ".$selectedValue." ";	
	}
	$q .= " ORDER BY type, caption ";
	return $q;	
}
}
?>
