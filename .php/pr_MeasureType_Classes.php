<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class MeasureTypeLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($caption = 'AllMeasureTypes'){
		$link = $this->listing();
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $measureTypeId = 0, $caption = 'MeasureType'){
		$link = $this->detail($pageAction,$measureTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing(){
		$link = 'pr_MeasureType_List.php';
		return $link;
	}
	
	public function listingPaged($found, $resultPage, $perPage){
		$l = $this->listing().'?resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $measureTypeId = 0){
		$link = 'pr_MeasureType_Detail.php?pageAction='.$pageAction;
		if ($measureTypeId != 0){
			$link .= '&measureTypeId='.$measureTypeId;
		}
		return $link;
	}	
	public function detailAddHref($caption = '+MeasureType'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($measureTypeId,$caption = 'ViewMeasureType'){
		$l = $this->detailHref('VIEW',$measureTypeId,$caption);
		return $l;	
	}
	public function detailEditHref($measureTypeId,$caption = 'EditMeasureType'){
		$l = $this->detailHref('EDIT',$measureTypeId,$caption);
		return $l;	
	}
	public function detailViewEditHref($measureTypeId = 0, $viewCaption = 'MeasureType'){
		
		if ($measureTypeId != 0){
			$links = $this->detailViewHref($measureTypeId,$viewCaption);
			$links .= $this->detailEditHref($measureTypeId,'#');
		}
		return $links;
	}	
	
}

class MeasureTypeList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new MeasureTypeSQL;
	}
	
	public function setDetails($resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'Measure Types';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		//$units = new UnitOfMeasureLinks($menuType,$menuStyle);
		$measureTypesL = new MeasureTypeLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);
		
		
		$menu = $measureTypesL->openMenu('section-heading-links');
		$menu .= $menuL->linkReference();
		$menu .= $measureTypesL->resetMenu();
		$menu .= $measureTypesL->detailAddHref();
		$menu .= $measureTypesL->listingHref();
		//$menu .= $units->resetMenu();
		//$menu .= $units->listingHref();

		$menu .= $measureTypesL->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$sql = $this->sql->countMeasureTypes();
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
		$mtl = new MeasureTypeLinks;
		$pagingLinks = $mtl->listingPaged($this->found,$this->resultPage,$this->perPage);
		
		$mt = new MeasureType;
		$mt->setDetails(0,'ADD');
		$quickEdit = $mt->editForm();
		$list = openDisplayList('measure-type','Measure Types',$pagingLinks,$quickEdit);
		
		$heading = wrapTh('Name');
		$heading .= wrapTh('Description');
		$heading .= wrapTh('Notes');
		$list .= wrapTr($heading);

		$sql = $this->sql->listMeasureTypes($this->resultPage,$this->perPage);

		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	
			$mt = new MeasureType;
			$mt->id = $row["id"];
			$mt->name = stripslashes($row["name"]);
			$mt->description = stripslashes($row["description"]);
			$mt->notes = stripslashes($row["notes"]);
			$mt->formatForDisplay();

			//$detail =  wrapTd($u->type);
			$link = $mtl->detailViewEditHref($mt->id,$mt->name);
			$detail = wrapTd($link);
			$detail .=  wrapTd($mt->description);			
			$detail .= wrapTd($mt->notes);
			$list .=  wrapTr($detail);
		}
		$result->close();
		}
		
		$list .= closeDisplayList();
		return $list;
	}
}

class MeasureType {
    public $id = 0;
    public $name;	
    public $description;
    public $notes;
    public $created;
    public $updated;
	public $highlightStyle = 'none';
	public $displayOrder = 0;
	
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new MeasureTypeSQL;
	}
	
    // set class properties with record values from database
	public function setDetails($detailMeasureTypeId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailMeasureTypeId;

		$sql = $this->sql->infoMeasureType($this->id);

		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
			{	
			$this->name = ($row["name"]);
			$this->type = ($row["description"]);
			$this->notes = ($row["notes"]);
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
			$heading .= 'Add New Measure Type';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		//$units = new UnitOfMeasureLinks($menuType,$menuStyle);
		$measureTypesL = new MeasureTypeLinks($menuType,$menuStyle);
		$measureTypeUnitsL = new MeasureTypeUnitLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);
		
		$menu = $measureTypesL->openMenu('section-heading-links');
		$menu .= $menuL->linkReference();
		if ($this->pageMode == 'VIEW'){
			$menu .= $measureTypesL->detailEditHref($this->id);
		} elseif ($this->pageMode == 'EDIT'){
			$menu .= $measureTypesL->detailViewHref($this->id);
		}
		if ($this->pageMode != 'ADD'){
			$menu .= $measureTypeUnitsL->listingHref($this->id);
		}
		$menu .= $measureTypesL->resetMenu();
		$menu .= $measureTypesL->listingHref();	
	
		$menu .= $measureTypesL->closeMenu();
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
		$detail = openDisplayDetails('measure-type','Measure Type Details');		
	 						
		$detail .= captionedParagraph('name', 'Name', $this->name);
		$detail .= captionedParagraph('description', 'Description', $this->description);
		$detail .= captionedParagraph('notes', 'Notes', $this->notes);
		$detail .= captionedParagraph('created', 'Created', $this->created);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);
		$detail .= captionedParagraph('highlight-style', 'Highlight', $this->highlightStyle);
		$detail .= captionedParagraph('display-order-style', 'Display Order', $this->displayOrder);


		$measureunits = new MeasureTypeUnitList;
		$measureunits->setDetails($this->id);
		$detail .= $measureunits->getListing();
		
		$detail .= closeDisplayDetails();
		return $detail;
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$legend = 'Add Measure Type';
		} else {
			$legend = 'Edit Measure Type';
		}

		$entity = 'measure-type';
		$c = new ProjectTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend, 'pr_MeasureType_Save.php',$contextMenu);
		
		//formRequired fields	
		$fields = inputFieldName($entity,$this->name,'name','Measure Type');
 		
		//end formRequired
		$formRequired = $fields;
		
		//formOptional
		$fields = inputFieldDescription($entity,$this->description,'description');
		
		$fields .= inputFieldNumber($entity,$this->displayOrder,'displayOrder','Display Order');

		$fields .= inputFieldHighlightStyle($entity,$this->highlightStyle,'highlightStyle');
		

		$fields = inputFieldNotes($entity,$this->notes,'notes');
		
		//end optional fields
		$formOptional = $fields;
		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('measureTypeId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
			
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){

		$this->id = $_POST['measureTypeId'];
		$this->name = dbEscapeString($_POST['name']); 
		$this->description = dbEscapeString($_POST['description']); 
		$this->highlightStyle = dbEscapeString($_POST['highlightStyle']); 		
		$this->displayOrder = dbEscapeString($_POST['displayOrder']);
		
		$this->notes = dbEscapeString($_POST['notes']); 		
		$this->pageMode = $_POST['mode'];	
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE measure_types AS mt ";
			$sql .= " SET ";
			$sql .= " mt.name = '".$this->name."', ";
			$sql .= " mt.description = '".$this->description."', ";
			$sql .= " mt.updated = CURRENT_TIMESTAMP, ";
			$sql .= " mt.notes = '".$this->notes."' ";
			$sql .= " WHERE mt.id = ".$this->id."  ";		
				
			$result = dbRunSQL($sql);
		} else {
	
			$sql = " INSERT INTO measure_types ";
			$sql .= " (name, ";
			$sql .= " created, ";
			$sql .= " updated, ";
			$sql .= " description, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= "'".$this->description."', ";
			$sql .= "'".$this->notes."') ";
			
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertedId();
		}
	
	}
	
} 
class MeasureTypeSQL{
public function columnsMeasureTypes(){
$query = " mt.id, ";
$query .= " mt.name, ";
$query .= " mt.notes, ";
$query .= " mt.description, ";
$query .= " mt.updated, ";
$query .= " mt.created ";
return $query;	
}
public function infoMeasureType($measureTypeId){
$query = " SELECT ";	
$query .= $this->columnsMeasureTypes();
$query .= " FROM measure_types mt ";
$query .= " WHERE  ";
$query .= " mt.id = ".$measureTypeId." ";
return $query;
}
public function listMeasureTypes($resultPage, $rowsPerPage){
$query = " SELECT ";	
$query .= $this->columnsMeasureTypes();
$query .= " FROM measure_types mt ";
$query .= " ORDER BY name ";
$query .= sqlLimitClause($resultPage, $rowsPerPage);
return $query;	
}
public function countMeasureTypes(){
$query = " SELECT ";	
$query .= " COUNT(*) total_types ";
$query .= " FROM measure_types mt ";
return $query;	
}

}
?>
