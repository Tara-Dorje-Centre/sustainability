<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class LocationLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($parentLocationId = -1, $caption = 'Locations'){
		$link = $this->listing($parentLocationId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $locationId = 0, $parentLocationId = 0, $caption = 'Location'){
		$link = $this->detail($pageAction,$locationId,$parentLocationId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	public function listing($parentLocationId = -1){
		$link = 'pr_Location_List.php'.'?parentLocationId='.$parentLocationId;
		return $link;
	}
	
	public function listingPaged($parentLocationId, $found, $resultPage=1, $perPage=10){
		$l = $this->listing($parentLocationId).'&resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $locationId = 0, $parentLocationId = 0){
		$link = 'pr_Location_Detail.php?pageAction='.$pageAction;
		if ($locationId != 0){
			$link .= '&locationId='.$locationId;
		}
		if ($parentLocationId != 0){
			$link .= '&parentLocationId='.$parentLocationId;
		}

		return $link;
	}	
	public function detailAddHref($parentLocationId = 0,$caption = '+Location'){
		$l = $this->detailHref('ADD',0,$parentLocationId,$caption);
		return $l;	
	}
	public function detailViewHref($locationId,$caption = 'ViewLocation'){
		$l = $this->detailHref('VIEW',$locationId,0,$caption);
		return $l;	
	}
	public function detailEditHref($locationId,$caption = 'EditLocation'){
		$l = $this->detailHref('EDIT',$locationId,0,$caption);
		return $l;	
	}
	public function detailViewEditHref($locationId = 0, $viewCaption = 'Location'){
		
		if ($locationId != 0){
			$links = $this->detailViewHref($locationId,$viewCaption);
			$links .= $this->detailEditHref($locationId,'#');
		} else {
			$links = $this->listingHref(-1,'Locations');
		}
		return $links;
	}	
	
}

class LocationList{
	public $parentId = -1;
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new LocationSQL;	
	}
	
	public function setDetails($parentLocationId=-1, $resultPage = 1, $resultsPerPage = 10){
		$this->parentId = $parentLocationId;	
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		if ($this->parentId <= 0){	
			$title .= 'All Locations';
		} else {
			$l = new Location;
			$title .= 'Sub Locations: '.$l->getSortKey($this->parentId);
		}
		$title .= closeDiv();
		return $title;	
	}
	
	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$locations = new LocationLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);
		
		$menu = $locations->openMenu('section-heading-links');
 		$menu .= $menuL->linkReference();
		$menu .= $locations->resetMenu();
		if ($this->parentId > 0){
			$menu .= $locations->detailViewHref($this->parentId,'Up');	
		} else {
			$menu .= $locations->detailAddHref(0);
		}
		$menu .= $locations->listingHref(-1);
				
		if ($this->parentId > 0){
			$menu .= $locations->resetMenu();
			//showing sub locations for a parent
			if ($this->found != 0){	
				$menu .= $locations->listingHref($this->parentId,'SubLocations('.$this->found.')');
			} else {
				$menu .= 'No Sublocations';
			}
			//add new sublocation to current parent
			$menu .= $locations->detailAddHref($this->parentId);
		}
		
		$menu .= $locations->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		if ($this->parentId <= 0){
			$sql = $this->sql->countLocationsByParent($this->parentId);
		} else {
			$l = new Location;
			$sortKey = $l->getSortKey($this->parentId);
			$sql = $this->sql->countLocationsByParentSortKey($sortKey,$this->parentId);
		}
		$this->found = dbGetCount($sql, 'total_locations');
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
	
	public function getListingRows(){
		$ll = new LocationLinks;
		$list = '';
		if ($this->parentId <= 0){
			$sql = $this->sql->listLocationsByParent($this->parentId,$this->resultPage,$this->perPage);
		} else {
			$l = new Location;
			$sortKey = $l->getSortKey($this->parentId);
			$sql = $this->sql->listLocationsByParentSortKey($sortKey,$this->parentId, $this->resultPage,$this->perPage);
		}
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$l = new Location;
			$l->id = $row["id"];
			$l->name = stripslashes($row["name"]);
			$l->sortKey = stripslashes($row["sort_key"]);
			$l->description = stripslashes($row["description"]);
			$l->formatForDisplay();
			$link = $ll->detailViewEditHref($l->id,$l->sortKey);
			
			$detail = wrapTd($link);
			$detail .= wrapTd($l->description);
			$list .=  wrapTr($detail);
		}
		$result->close();
		}
		
		return $list;
	}

	public function getListing(){		

		if ($this->found > 0){
			$ll = new LocationLinks;
			$pagingLinks = $ll->listingPaged($this->parentId,$this->found,$this->resultPage,$this->perPage);
			$list = openDisplayList('location','Locations',$pagingLinks);

			$heading = wrapTh('Location');
			$heading .= wrapTh('Description');
			$list .= wrapTr($heading);

			$list .= $this->getListingRows();

			$list .= closeDisplayList();
		} else {
			$list = openDiv('location-list','none');
			$list .= 'No Locations Found';
			$list .= closeDiv();
		}
		
		return $list;
		
	}
}

class Location {
    public $id = 0;
    public $parentId = 0;
	public $typeId = 0;
    public $name;	
	public $parentSortKey;
	public $sortKey;
    public $description;
    public $created;
    public $updated;
	public $childLocationsFound = 0;
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new LocationSQL;
	}
	
    // set class properties with record values from database
	public function setDetails($detailLocationId, $detailParentLocationId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailLocationId;
		$this->parentId = $detailParentLocationId;
		
		if ($inputMode == 'ADD'){
			//if add came from all locations default, reset to 0 for no parent
			if ($this->parentId == -1){
				$this->parentId = 0;
			}
		} else {
			//EDIT OR VIEW
			$sql = $this->sql->infoLocation($this->id);
			
			$result = dbGetResult($sql);
			if($result){
	  		while ($row = $result->fetch_assoc())
				{	
				$this->parentId = $row["parent_id"];
				$this->typeId = $row["type_id"];
				$this->name = ($row["name"]);
				$this->sortKey = ($row["sort_key"]);
				$this->description = ($row["description"]);
				$this->created = ($row["created"]);	
				$this->updated = ($row["updated"]);			
			}
			$result->close();
			}
			
			$this->setChildLocationsCount();
		}
	}	
	
	private function setChildLocationsCount(){
		$list = new LocationList;
		$list->setDetails($this->id);
		$this->childLocationsFound = $list->found;	
	}
	
	function pageTitle(){
		$heading = openDiv('section-heading-title');
		if ($this->pageMode != 'ADD'){
			$heading .= $this->sortKey;
		} else {
			if ($this->parentId == 0){
				$heading .= 'Add New Location';
			} else {
				$heading .= $this->getSortKey($this->parentId).' Add Sub Location';	
			}
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$locationL = new LocationLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);
		
		$menu = $locationL->openMenu('section-heading-links');		
		$menu .= $menuL->linkReference();
		$menu .= $locationL->resetMenu();
		
		if ($this->parentId > 0){
			$menu .= $locationL->detailViewHref($this->parentId,'Up');
		}
		if ($this->pageMode == 'VIEW'){
			$menu .= $locationL->detailEditHref($this->id);
		} elseif ($this->pageMode == 'EDIT'){
			$menu .= $locationL->detailViewHref($this->id);
		}
		$menu .= $locationL->listingHref(-1);	

		if ($this->pageMode != 'ADD'){
			$menu .= $locationL->resetMenu();
			
			$i = $this->childLocationsFound;
			if ($i != 0){
				$menu .= $locationL->listingHref($this->id,'Sublocations('.$i.')');
			} else {
				$menu .= 'No Sublocations';	
			}
			$menu .= $locationL->detailAddHref($this->id);
		}
	
		$menu .= $locationL->closeMenu();
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
		
		$detail = openDisplayDetails('location','Location Details');		

//	 	$detail .= 'parent='.$this->parentId;

		$detail .= captionedParagraph('name', 'Name', $this->name);

		$type = new LocationType;
		$select = $type->getLocationTypeSelectList($this->typeId,'typeId','true');
		$detail .= captionedParagraph('type', 'Location Type', $select);
		$detail .= captionedParagraph('desc', 'Description', $this->description);		
		$detail .= captionedParagraph('created', 'Created', $this->created);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);

		$detail .= closeDisplayDetails();
		return $detail;
	}
	
	public function getLocationSelectList($selectedValue = 0, $idName = 'locationId', $disabled = 'false',
		$showLink = true,
		$onChangeJS = NULL){

		
		$sql = $this->sql->selectOptions_Locations($selectedValue,$disabled);
		
		$defaultValue = 0;
		$defaultCaption = '-select Location';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
		$select = getSelectList($idName,$allOptions,'none',$disabled );	

		if ($showLink == true){
			$ll = new LocationLinks;	
			$links =$ll->detailViewEditHref($selectedValue);
			$select .= $links;	
		}
		return $select;

	}

	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$legend = 'Add Location';
		} else {
			$legend = 'Edit Location';
		}
		$entity = 'location';
		$c = new LocationLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Location Options');		
		$form = openEditForm($entity,$legend,'pr_Location_Save.php', $contentMenu);
		
		//formRequired fields	
		$formRequired = inputFieldName($entity,$this->name,'locationName','Location');

		$type = new LocationType;
		$select = $type->getLocationTypeSelectList($this->typeId,'typeId','false',false);
		$formOptional = captionedInput('Location Type',$select);
					
		$formOptional .= inputFieldText($entity,$this->description,'locationDescription','Description');

						
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('parentLocationId', $this->parentId);
		$hidden .= getHiddenInput('locationId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = formInputRow($input, $hidden);
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){
		$this->id = $_POST['locationId'];
		$this->typeId = $_POST['typeId'];
		$this->parentId = $_POST['parentLocationId'];
		$this->name = dbEscapeString($_POST['locationName']); 
		$this->description = dbEscapeString($_POST['locationDescription']); 		
		$this->pageMode = $_POST['mode'];	
	}
 	
	public function getSortKey($locationId){
		$sql = " SELECT l.sort_key FROM locations l WHERE l.id = ".$locationId;

		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$sortKey = stripslashes($row["sort_key"]);
		}
		$result->close();
		}
		
		return $sortKey;		
	}

	private function updateSortKey(){
		$sql = " UPDATE locations as l	";
		if ($this->parentId == 0){
			$sortKey = $this->name;
		} else {
			$sortKey = $this->getSortKey($this->parentId).'.'.$this->name;
		}
		$sql .= " SET l.sort_key = '".$sortKey."' ";	
		$sql .= " WHERE l.id = ".$this->id." ";
		$result = dbRunSQL($sql);
	
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE locations AS l ";
			$sql .= " SET ";
			$sql .= " l.name = '".$this->name."', ";
			$sql .= " l.type_id = ".$this->typeId.", ";
			$sql .= " l.updated = CURRENT_TIMESTAMP, ";
			$sql .= " l.description = '".$this->description."' ";
			$sql .= " WHERE l.id = ".$this->id."  ";			
			
		$result = dbRunSQL($sql);
			
			$this->updateSortKey();
		} else {
	
			$sql = " INSERT INTO locations ";
			$sql .= " (name, ";
			$sql .= " parent_id, ";
			$sql .= " type_id, ";
//			$sql .= " sort_key, ";
			$sql .= " created, ";
			$sql .= " updated, ";
			$sql .= " description) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= " ".$this->parentId.", ";
			$sql .= " ".$this->typeId.", ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= "'".$this->description."') ";
		$result = dbRunSQL($sql);
			
			$this->id = dbInsertedId();
			
			$this->updateSortKey();
		}
	
	}
	
} 

class LocationSQL{
function columnsLocations(){
	$c = " l.id, ";
	$c .= " l.parent_id, ";
	$c .= " l.type_id, ";
	$c .= " l.name, ";
	$c .= " l.sort_key, ";
	$c .= " l.description, ";
	$c .= " l.created, ";
	$c .= " l.updated ";
	return $c;
}

public function listLocationsByParent($parentLocationId, $resultPage, $rowsPerPage){
	$q = " SELECT  ";
	$q .= $this->columnsLocations();
	$q .= " FROM locations AS l ";
	if ($parentLocationId != -1){
		$q .= " WHERE l.parent_id = ".$parentLocationId." "; 
	} 
	$q .= " ORDER BY l.sort_key ";

	$q .= sqlLimitClause($resultPage, $rowsPerPage);
	return $q;
}

public function countLocationsByParent($parentLocationId = 0){
	$q = " SELECT  ";
	$q .= " COUNT(*) total_locations ";
	$q .= " FROM locations AS l ";
	if ($parentLocationId != -1){
		$q .= " WHERE l.parent_id = ".$parentLocationId." "; 
	} 
	return $q;
}

public function listLocationsByParentSortKey($parentSortKey, $parentLocationId, $resultPage, $rowsPerPage){
	$q = " SELECT  ";
	$q .= $this->columnsLocations();
	$q .= " FROM locations AS l ";
	$q .= " WHERE l.sort_key LIKE '".$parentSortKey."%' "; 
	$q .= " AND l.id != ".$parentLocationId." ";
	$q .= " ORDER BY l.sort_key ";
	$q .= sqlLimitClause($resultPage, $rowsPerPage);
	return $q;
}

public function countLocationsByParentSortKey($parentSortKey, $parentLocationId){
	$q = " SELECT  ";
	$q .= " COUNT(*) total_locations ";
	$q .= " FROM locations AS l ";
	$q .= " WHERE l.sort_key LIKE '".$parentSortKey."%' ";  
	$q .= " AND l.id != ".$parentLocationId." ";	
	return $q;
}

public function infoLocation($selectedLocationId){
	$q = " SELECT  ";
	$q .= $this->columnsLocations();
	$q .= " FROM locations AS l ";
	$q .= " WHERE l.id = ".$selectedLocationId." "; 
	return $q;
}

public function selectOptions_Locations($selectedValue,$disabled){
	$q = " SELECT ";
	$q .= " l.id as value, ";
	$q .= " l.sort_key as caption ";
	$q .= " FROM locations l ";
	if ($disabled == 'true'){
		$q .= " WHERE l.id = ".$selectedValue." ";
	}
	$q .= " ORDER BY caption ";
	return $q;	
}

}
?> 
