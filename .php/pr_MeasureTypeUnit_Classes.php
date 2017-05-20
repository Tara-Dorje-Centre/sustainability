<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class MeasureTypeUnitLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($measureTypeId = 0, $caption = 'AllMeasureTypeUnits'){
		$link = $this->listing($measureTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $measureTypeUnitId = 0, $measureTypeId = 0, $caption = 'MeasureTypeUnit'){
		$link = $this->detail($pageAction,$measureTypeUnitId,$measureTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing($measureTypeId = 0){
		$link = 'pr_MeasureTypeUnit_List.php?measureTypeId='.$measureTypeId;
		return $link;
	}
	
	public function listingPaged($measureTypeId, $found, $resultPage, $perPage){
		$l = $this->listing($measureTypeId).'&resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $measureTypeUnitId, $measureTypeId = 0){
		$link = 'pr_MeasureTypeUnit_Detail.php?pageAction='.$pageAction;
		if($measureTypeId != 0){
			$link .= '&measureTypeId='.$measureTypeId;			
		}
		if ($measureTypeUnitId != 0){
			$link .= '&measureTypeUnitId='.$measureTypeUnitId;
		}
		return $link;
	}	
	public function detailAddHref($measureTypeId, $caption = '+MeasureTypeUnit'){
		$l = $this->detailHref('ADD',0,$measureTypeId,$caption);
		return $l;	
	}
	public function detailViewHref($measureTypeUnitId,$caption = 'ViewMeasureTypeUnit'){
		$l = $this->detailHref('VIEW',$measureTypeUnitId,0,$caption);
		return $l;	
	}
	public function detailEditHref($measureTypeUnitId,$caption = 'EditMeasureTypeUnit'){
		$l = $this->detailHref('EDIT',$measureTypeUnitId,0,$caption);
		return $l;	
	}
	public function detailViewEditHref($measureTypeUnitId = 0, $viewCaption = 'MeasureTypeUnit'){
		
		if ($measureTypeUnitId != 0){
			$links = $this->detailViewHref($measureTypeUnitId,$viewCaption);
			$links .= $this->detailEditHref($measureTypeUnitId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	

}

class MeasureTypeUnitList{
	public $measureTypeId = 0;
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $measureType;
	private $sql;
	
	public function __construct(){
		$this->sql = new MeasureTypeUnitSQL;
		$this->measureType = new MeasureType;
	}
	
	public function setDetails($measureTypeId, $resultPage = 1, $resultsPerPage = 10){
		$this->measureTypeId = $measureTypeId;	
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->measureType->setDetails($measureTypeId, 0, 'VIEW');
		$this->setFoundCount();
	}
	
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		if ($this->measureType->id > 0){
			$title .= $this->measureType->name.br();
		} else {
			$title .= 'All Measure Types:'.br();
		}
		$title .= 'Measure Type Units';
		$title .= closeDiv();
		return $title;	
	}	

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$units = new UnitOfMeasureLinks($menuType,$menuStyle);
		$measureTypes = new MeasureTypeLinks($menuType,$menuStyle);
		$measureTypeUnits = new MeasureTypeUnitLinks($menuType,$menuStyle);

		
		$menu = $units->openMenu('section-heading-links');
	
		$mtul = new MeasureTypeUnitLinks;
		$mtl = new MeasureTypeLinks;
		$uml = new UnitOfMeasureLinks;			

		if ($this->measureTypeId > 0){
			$menu .= $measureTypeUnits->detailAddHref($this->measureType->id);
		}
		$menu .= $measureTypeUnits->listingHref($this->measureType->id,'Refresh');
		$menu .= $units->resetMenu();
		
		$menu .= $measureTypeUnits->listingHref(0);
		$menu .= $units->resetMenu();
		$menu .= $measureTypes->listingHref();
		$menu .= $units->listingHref();		
		
		$menu .= $units->closeMenu();	
		return $menu;			
	}	
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$s = new MeasureTypeUnitSQL;
		$sql = $s->countMeasureTypeUnits($this->measureType->id);
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
		$s = new MeasureTypeUnitSQL;
		$sql = $s->listMeasureTypeUnits($this->measureType->id,$this->resultPage,$this->perPage);

		
		$ml = new MeasureTypeUnitLinks;
		$pagingLinks = $ml->listingPaged($this->measureType->id, $this->found,$this->resultPage,$this->perPage);
		if ($this->measureType->id > 0){
			$mtu = new MeasureTypeUnit;
			$mtu->setDetails(0,$this->measureType->id,'ADD');
			$quickEdit = $mtu->editForm();
		} else {
			$quickEdit = NULL;
		}
		$list = openDisplayList('measure-type-unit','Measure Type Units',$pagingLinks,$quickEdit);

		$heading =  wrapTh('Measure Type');
		$heading .=  wrapTh('Unit Type');
		$heading .=  wrapTh('Unit of Measure');
		$heading .=  wrapTh('Unit Symbol');
		$list .=  wrapTr($heading);

		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	
			$m = new MeasureTypeUnit;
			$m->id = $row["id"];
			$m->measureTypeId = $row["measure_type_id"];
			$m->unitOfMeasureId = $row["unit_measure_id"];
			$m->measureTypeName = stripslashes($row["measure_type"]);
			$m->unitType = stripslashes($row["unit_type"]);
			$m->unitOfMeasure = stripslashes($row["unit_of_measure"]);
			$m->unitSymbol = stripslashes($row["unit_symbol"]);
			
			$link = $ml->detailViewEditHref($m->id,$m->getMeasureTypeAndUnitSymbol());
			$detail =  wrapTd($link);
			$detail .=  wrapTd($m->unitType);
			$detail .=  wrapTd($m->unitOfMeasure);
			$detail .=  wrapTd($m->unitSymbol);
			$list .=  wrapTr($detail);
		}
		$result->close();
		}

		$list .=  closeDisplayList();
		return $list;
	}
}


class MeasureTypeUnit {
    public $id = 0;
    public $measureTypeId = 0;
    public $unitOfMeasureId = 0;	
	public $measureType;
	public $measureTypeName;
    public $unitType;
    public $unitOfMeasure;
    public $unitSymbol;
    public $created;	
    public $updated;

	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new MeasureTypeUnitSQL;
		$this->measureType = new MeasureType;
	}
	
	public function setDetails($detailMeasureTypeUnitId, $parentMeasureTypeId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailMeasureTypeUnitId;
		$this->measureTypeId = $parentMeasureTypeId;
		
		$sql = $this->sql->infoMeasureTypeUnit($this->id);

		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	
			$this->measureTypeId = $row["measure_type_id"];
			$this->unitOfMeasureId = $row["unit_measure_id"];
			$this->created = $row["created"];
			$this->updated = $row["updated"];	
			$this->measureTypeName = ($row["measure_type"]);
			$this->unitOfMeasure = ($row["unit_of_measure"]);
			$this->unitSymbol = ($row["unit_symbol"]);
			$this->unitType = ($row["unit_type"]);
		}
		$result->close();
		}

		$this->measureType->setDetails($this->measureTypeId, 'VIEW');
	}	
			
	function pageTitle(){	
		$title = openDiv('section-heading-title','none');
		//show page mode for debugging
		//$heading .= '['.$this->pageMode.']';
		$title .= 'Measure Type:&nbsp;'.$this->measureType->name.br();
		$title .= 'Units:'.$this->unitOfMeasure;		
		$title .= closeDiv();
		return $title;
	}
	
	
	function pageMenu(){
		
		$mtul = new MeasureTypeUnitLinks;
		
		$menu = openDiv('section-heading-links','menu-links-list');
		if ($this->pageMode == 'VIEW'){
			$menu .= $mtul->detailEditHref($this->id,'Edit');
		}
		if ($this->pageMode == 'EDIT'){
			$menu .= $mtul->detailViewHref($this->id,'View');
		}
		if ($this->pageMode != 'ADD'){
			$menu .= $mtul->detailAddHref($this->measureType->id);
		}
		$menu .= $mtul->listingHref($this->measureType->id,'MeasureType Units');
		$menu .= $mtul->listingHref(0,'All MeasureType Units');

		$menu .= closeDiv();
		return $menu;
	}
		
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function getMeasureTypeAndUnitSymbol(){
		$text = $this->measureTypeName.'('.$this->unitSymbol.')';
		return $text;
	}
	
	public function formatForDisplay(){
//		$this->name = displayLines($this->name);
	//	$this->description = displayLines($this->description);
		//$this->notes = displayLines($this->notes);
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
		
		$detail = openDisplayDetails('measure-type-unit','Measure Type Unit Details');		

		$detail .= captionedParagraph('measure-type','Measure Type',$this->getMeasureTypeAndUnitSymbol());
		$u = new UnitOfMeasure;
		$select = $u->getUnitOfMeasureSelectList($this->unitOfMeasureId,'unitOfMeasureId','true');
		$detail .= captionedParagraph('units','Units',$select);		
		$detail .= captionedParagraph('unit-type','Unit Type',$this->unitType);
		$detail .= captionedParagraph('updated','Updated',$this->updated);		
		$detail .= captionedParagraph('created','Created',$this->created);

		$detail .= closeDisplayDetails();
		
		return $detail;
	}

	public function getMeasureTypeUnitSelectList(
		$selectedValue = 0, 
		$idName = 'measureTypeUnitId', 
		$disabled = 'false',
		$showLink = false,
		$changeJS = NULL){
		
		$sql = $this->sql->selectOptions_MeasureTypeUnits($selectedValue,$disabled);
		
		$defaultValue = '0';
		$defaultCaption = '-select Measure Type Units';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
		$select = getSelectList($idName,$allOptions,'none',$disabled,$changeJS);
		if ($showLink === true){
			$l = new MeasureTypeUnitLinks;
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}
		
	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$legend = 'Add Measure Type Unit';
		} else {
			$legend = 'Edit Measure Type Unit';
		}

		$entity = 'measure-type-unit';
		$c = new ProjectTypeLinks;
		//$contextMenu = $c->formatToggleLink('formOptional','+Measure Type Unit Options');
		$contextMenu = NULL;
		$form = openEditForm($entity,$legend,'pr_MeasureTypeUnit_Save.php',$contextMenu);

		$u = new UnitOfMeasure;
		$select = $u->getUnitOfMeasureSelectList($this->unitOfMeasureId,'unitOfMeasureId','false');
		$fields =inputFieldSelect($entity, $select,'Unit Type');
		
		$formRequired = $fields;
		
		$formOptional = NULL;
		
		//hidden fields and submit,reset buttons
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('measureTypeId', $this->measureTypeId);
		$hidden .= getHiddenInput('measureTypeUnitId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
			
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){
		//called by save form prior to running adds/updates
		$this->pageMode = $_POST['mode'];
		
		$this->measureTypeId = $_POST['measureTypeId'];
		$this->id = $_POST['measureTypeUnitId'];
		$this->unitOfMeasureId = $_POST['unitOfMeasureId']; 

	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			
			$sql = " UPDATE measure_type_units m ";
			$sql .= " SET ";
			$sql .= " m.measure_type_id = ".$this->measureTypeId.", ";
			$sql .= " m.unit_measure_id = ".$this->unitOfMeasureId.", ";
			$sql .= " m.updated = CURRENT_TIMESTAMP ";
			$sql .= " WHERE m.id = ".$this->id." ";

			$result = dbRunSQL($sql);
			
		} else {
	
			$sql = " INSERT INTO measure_type_units ";
			$sql .= " (measure_type_id, ";
			$sql .= " unit_measure_id, ";
			$sql .= " updated, ";
			$sql .= " created) ";
			$sql .= " VALUES (";
			$sql .= " ".$this->measureTypeId.", ";
			$sql .= " ".$this->unitOfMeasureId.", ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP) ";
			
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertedId();
		}
	
	}
	
} 

class MeasureTypeUnitSQL{
	public function createView_MeasureTypeUnits(){
		$s = "CREATE OR REPLACE VIEW measure_type_units_v AS
		SELECT
		mtu.id,
		mtu.id measure_type_unit_id,
		mtu.measure_type_id,
		mtu.unit_measure_id,
		mtu.created,
		mtu.updated,
		mt.name measure_type,
		u.name unit_of_measure,
		u.type unit_type,
		u.symbol unit_symbol
		from
		measure_types mt
		join measure_type_units mtu
		on mtu.measure_type_id = mt.id
		join units_of_measure u
		on mtu.unit_measure_id = u.id
		order by
		unit_type, measure_type, unit_of_measure ";
		return $s;
	}
public function columnsMeasureTypeUnit(){
	$c = " mtu.id, ";
	$c .= " mtu.measure_type_id, ";
	$c .= " mtu.unit_measure_id, ";
	$c .= " mtu.updated, ";
	$c .= " mtu.created, ";
	$c .= " mtu.measure_type, ";
	$c .= " mtu.unit_type, ";
	$c .= " mtu.unit_of_measure, ";
	$c .= " mtu.unit_symbol ";
	return $c;	
}
public function infoMeasureTypeUnit($measureTypeUnitId){
	$q = " SELECT ";	
	$q .= $this->columnsMeasureTypeUnit();
	$q .= " FROM measure_type_units_v mtu ";
	$q .= " WHERE mtu.id = '".$measureTypeUnitId."' ";
	return $q;
}
public function listMeasureTypeUnits($measureTypeId,$resultPage, $rowsPerPage){
	$q = " SELECT ";	
	$q .= $this->columnsMeasureTypeUnit();
	$q .= " FROM measure_type_units_v mtu ";
	if ($measureTypeId > 0){
		$q .= " WHERE mtu.measure_type_id = ".$measureTypeId." ";
	}
	$q .= " ORDER BY unit_type, measure_type, unit_of_measure ";
	$q .= sqlLimitClause($resultPage, $rowsPerPage);
	return $q;	
}
public function countMeasureTypeUnits($measureTypeId){
	$q = " SELECT ";	
	$q .= " COUNT(*) total_units ";
	$q .= " FROM measure_type_units_v mtu ";
	if ($measureTypeId > 0){
		$q .= " WHERE mtu.measure_type_id = ".$measureTypeId." ";
	}
	return $q;	
}
public function selectOptions_MeasureTypeUnits($selectedValue,$disabled){
	$q = " SELECT ";
	$q .= " mtu.unit_type, ";
	$q .= " mtu.id as value, ";
	$q .= " concat(mtu.measure_type,'(',mtu.unit_symbol,')') as caption ";
	$q .= " FROM measure_type_units_v mtu ";
	if ($disabled == 'true'){
		$q .= " WHERE mtu.id = ".$selectedValue." ";	
	}
	$q .= " ORDER BY unit_type, caption ";
	return $q;	
}
}

?>
