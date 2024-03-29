<?php


class CropLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($parentCropId = -1, $caption = 'AllCrops'){
		$link = $this->listing($parentCropId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	private function detailHref($pageAction = 'VIEW', $cropId = 0, $caption = 'Crop',$parentCropId = 0){
		$link = $this->detail($pageAction,$cropId,$parentCropId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	public function listing($parentCropId = -1){
		$link = 'cp_Crop_List.php';
		$link .= '?parentCropId='.$parentCropId;
		return $link;
	}
	public function listingPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&resultPageCrops=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	public function detail($pageAction = 'VIEW', $cropId = 0, $parentCropId = 0){
		$link = 'cp_Crop_Detail.php?pageAction='.$pageAction;
		if ($cropId != 0){
			$link .= '&cropId='.$cropId;
		}
		if ($parentCropId != 0){
			$link .= '&parentCropId='.$parentCropId;
		}
		return $link;
	}
	public function detailAddHref($caption = '+Crop',$parentCropId = 0){
		$l = $this->detailHref('ADD',0,$caption,$parentCropId);
		return $l;	
	}
	public function detailViewHref($cropId,$caption = 'ViewCrop'){
		$l = $this->detailHref('VIEW',$cropId,$caption);
		return $l;	
	}
	public function detailEditHref($cropId,$caption = 'EditCrop'){
		$l = $this->detailHref('EDIT',$cropId,$caption);
		return $l;	
	}
	public function detailViewEditHref($cropId = 0, $viewCaption = 'ViewCrop'){
		
		if ($cropId != 0){
			$links = $this->detailViewHref($cropId,$viewCaption);
			$links .= $this->detailEditHref($cropId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	
}
//end class CropLinks
class CropList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $parentCropId = 0;
	private $sql;
	
	public function __construct(){
		$this->sql = new CropSQL;	
	}
	
	public function setDetails($parentCropId = 0, $resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		$this->parentCropId = $parentCropId;
		$this->setFoundCount();
	}
	
	private function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'Crops';
		$title .= closeDiv();
		return $title;	
	}

	private function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$crops = new CropLinks($menuType,$menuStyle);
		$plans = new CropPlanLinks($menuType,$menuStyle);
		
		$menu = $crops->openMenu('section-heading-links');

		$menu .= $crops->detailAddHref();
		$menu .= $crops->listingHref();
		
		$menu .= $crops->resetMenu();

		if (!isset($_SESSION['currentCropPlanId'])){
			$menu .= $plans->listingHref();
		} else {
			$menu .= $plans->detailViewHref($_SESSION['currentCropPlanId']);
		}

		$menu .= $crops->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$sql = $this->sql->countCrops($this->parentCropId);
		$this->found = dbGetCount($sql, 'total_crops');
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
		$sql = $this->sql->listCrops($this->parentCropId,$this->resultPage,$this->perPage);


		$cl = new CropLinks;		
		if ($pagingBaseLink == 'USE_LISTING'){
			$base = $cl->listing($this->parentCropId);
		} else { 
			$base = $pagingBaseLink;
		}		
		$pagingLinks = $cl->listingPaged($base, $this->found,$this->resultPage,$this->perPage);
		
		$list = openDisplayList('crops','Crops',$pagingLinks);

		$heading = wrapTh('Common Name[Variety]');
		$heading .= wrapTh('Botanical Name[Family]');
		$heading .= wrapTh('Days Mature');
		$heading .= wrapTh('Days Germinate');
		$heading .= wrapTh('Days Transplant');
		$heading .= wrapTh('Seeds On Hand');
		$list .= wrapTr($heading);

		$result = dbGetResult($sql);
		if ($result){
		while($row = $result->fetch_assoc())
		{	
			$c = new Crop;
			
			$c->id = $row["id"]; 
			$c->parentId = $row["parent_id"]; 
			$c->commonName = stripslashes($row["common_name"]); 
			$c->varietyName = stripslashes($row["variety_name"]); 
			$c->botanicalName = stripslashes($row["botanical_name"]); 
			$c->familyName = stripslashes($row["family_name"]); 
			$c->daysMature = $row["days_mature"];
			$c->daysMatureMax = $row["days_mature_max"];
			$c->daysGerminate = $row["days_germinate"];
			$c->daysGerminateMax = $row["days_germinate_max"];
			$c->daysTransplant = $row["days_transplant"];
			$c->daysTransplantMax = $row["days_transplant_max"];
			$c->seedsOnHand = $row["seeds_on_hand"];
			
			$c->formatForDisplay();
			
			$link = $cl->detailViewEditHref($c->id,$c->getCommonNameAndVarietyName());
			$detail = wrapTd($link);
			$detail .=  wrapTd($c->getBotanicalNameAndFamilyName());			
			$detail .=  wrapTd($c->daysMatureMinMax);		
			$detail .=  wrapTd($c->daysGerminateMinMax);		
			$detail .=  wrapTd($c->daysTransplantMinMax);		
			$detail .=  wrapTd($c->seedsOnHand);		
			
			if ($c->seedsOnHand == 'yes'){
				$cssRow = 'highlight-green';
			} else {
				$cssRow = 'none';
			}
			$list .=  wrapTr($detail,$cssRow);
		}
		$result->close();
		}

		$list .= closeDisplayList();
		return $list;
	}
}
//end class CropList
class Crop {
    public $id = 0; 
    public $parentId = 0; 
    public $commonName;
    public $varietyName;
    public $botanicalName;
	public $familyName;
	public $certifications;
	public $daysMature = 0;
	public $daysMatureMax = 0;
	public $daysMatureMinMax = 0;
    public $daysTransplant = 0;
	public $daysTransplantMax = 0;
	public $daysTransplantMinMax = 0;
    public $daysGerminate = 0;
	public $daysGerminateMax = 0;
	public $daysGerminateMinMax = 0;
    public $seedDepthInches = 0;
    public $seedSpacingInches = 0;
	public $thinningHeightInches = 0;
    public $inrowSpacingInches = 0;
    public $rowSpacingInches = 0;
	public $siteNotes;
    public $plantingNotes;
    public $transplantingNotes;
    public $thinningNotes;
    public $careNotes;
	public $seedsOnHand = 'no';
	public $resultPageCrops = 1;
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new CropSQL;	
	}
	
    // set class properties with record values from database
	public function setDetails($detailCropId, $parentCropId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailCropId;
		$this->parentId = $parentCropId;

		if ($this->pageMode == 'ADD' && $this->parentId <> 0){
			$sql = $this->sql->infoCrop($this->parentId);
		} else {
			$sql = $this->sql->infoCrop($this->id);
		}
		$result = dbGetResult($sql);
		if ($result){
		while($row = $result->fetch_assoc())
			{
			if ($this->pageMode <> 'ADD'){	
				$this->id = $row["id"]; 
				$this->parentId = $row["parent_id"]; 
			}
			$this->commonName = ($row["common_name"]); 
			$this->varietyName = ($row["variety_name"]); 
			$this->botanicalName = ($row["botanical_name"]); 
			$this->familyName = ($row["family_name"]);
			$this->daysMature = $row["days_mature"];
			$this->daysMatureMax = $row["days_mature_max"];
			$this->daysTransplant = $row["days_transplant"]; 
			$this->daysTransplantMax = $row["days_transplant_max"]; 
			$this->daysGerminate = $row["days_germinate"]; 
			$this->daysGerminateMax = $row["days_germinate_max"];
			$this->seedDepthInches = $row["seed_depth_inches"]; 
			$this->seedSpacingInches = $row["seed_spacing_inches"]; 
			$this->thinningHeightInches = $row["thinning_height_inches"];
			$this->inrowSpacingInches = $row["inrow_spacing_inches"]; 
			$this->rowSpacingInches = $row["row_spacing_inches"];
			$this->siteNotes = ($row["site_notes"]); 
			$this->plantingNotes = ($row["planting_notes"]); 
			$this->transplantingNotes = ($row["transplanting_notes"]);
			$this->thinningNotes = ($row["thinning_notes"]);
			$this->careNotes = ($row["care_notes"]);
			$this->certifications = ($row["certifications"]);
			$this->seedsOnHand = ($row["seeds_on_hand"]);
		}
		$result->close();
		}	
	}	
		
	private function pageTitle(){
		$heading = openDiv('section-heading-title');
		if ($this->pageMode != 'ADD'){
			$heading .= $this->commonName;
		} else {
			$heading .= 'Add New Crop';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	private function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';

		$crops = new CropLinks($menuType,$menuStyle);		
        $plans = New CropPlanLinks($menuType,$menuStyle);
		
		$menu = $crops->openMenu('section-heading-links');

		if ($this->pageMode != 'ADD'){
			if ($this->parentId == 0){
				$menu .= $crops->detailAddHref('+Variety',$this->id);
			} else {
				$menu .= $crops->detailAddHref('+Variety',$this->parentId);
			}
		}
		if ($this->pageMode == 'VIEW'){
			$menu .= $crops->detailEditHref($this->id);
		}
		if ($this->pageMode == 'EDIT'){
			$menu .= $crops->detailViewHref($this->id);
		}
		$menu .= $crops->listingHref();
		
		$menu .= $crops->resetMenu();

		if (!isset($_SESSION['currentCropPlanId'])){
			$menu .= $plans->listingHref();
		} else {
			$menu .= $plans->detailViewHref($_SESSION['currentCropPlanId']);
		}

		$menu .= $crops->closeMenu();
		
		return $menu;
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function getCommonNameAndVarietyName(){
		
		$name = $this->commonName.'['.$this->varietyName.']';
		return $name;	
	}
	
	public function getBotanicalNameAndFamilyName(){
		$name = $this->botanicalName.'['.$this->familyName.']';
		return $name;	
		
	}
	
	public function formatForDisplay(){
		$this->siteNotes = displayLines($this->siteNotes); 		
		$this->plantingNotes = displayLines($this->plantingNotes); 
		$this->transplantingNotes = displayLines($this->transplantingNotes);
		$this->thinningNotes = displayLines($this->thinningNotes);
		$this->careNotes = displayLines($this->careNotes);
		$this->daysGerminateMinMax = $this->formatMinMax($this->daysGerminate,$this->daysGerminateMax);
		$this->daysMatureMinMax = $this->formatMinMax($this->daysMature,$this->daysMatureMax);
		$this->daysTransplantMinMax = $this->formatMinMax($this->daysTransplant,$this->daysTransplantMax);		
	}
	
	private function formatMinMax($minValue = 0, $maxValue = 0){
		$text = $minValue;
		if ($maxValue > $minValue){
			$text .= ' - '.$maxValue;
		}
		return $text;
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
				
		$detail = openDisplayDetails('crop','Crop Details');

		$cropL = new CropLinks;		

		$tableNaming = openTable('naming-details','listing-table');
		$heading = wrapTh('Common');
		$heading .= wrapTh('Variety');
		$heading .= wrapTh('Botanical');
		$heading .= wrapTh('Family');
		$tableNaming .= wrapTr($heading);
		if ($this->parentId != 0){
			$link = $cropL->detailViewHref($this->parentId,$this->commonName);
		} else {
			$link = $this->commonName;
		}
		$row = wrapTd($link);
		$row .= wrapTd($this->varietyName);
		$row .= wrapTd($this->botanicalName);
		$row .= wrapTd($this->familyName);
		$tableNaming .= wrapTr($row);
		$tableNaming .= closeTable();
		$detail .= $tableNaming;



		$tableGrowing = openTable('growing-details','listing-table');
		$heading = wrapTh('Germinates');
		$heading .= wrapTh('Transplant');
		$heading .= wrapTh('Matures');
		$heading .= wrapTh('Seed Depth');
		$heading .= wrapTh('Seed Spacing');
		$heading .= wrapTh('Thin Height');
		$heading .= wrapTh('In Row Spacing');
		$heading .= wrapTh('Row Spacing');
		$tableGrowing .= wrapTr($heading);
		$row = wrapTd($this->daysGerminateMinMax);
		$row .= wrapTd($this->daysTransplantMinMax);
		$row .= wrapTd($this->daysMatureMinMax);
		$row .= wrapTd($this->seedDepthInches);
		$row .= wrapTd($this->seedSpacingInches);
		$row .= wrapTd($this->thinningHeightInches);
		$row .= wrapTd($this->inrowSpacingInches);
		$row .= wrapTd($this->rowSpacingInches);
		$tableGrowing .= wrapTr($row);
		$tableGrowing .= closeTable();
		$detail .= $tableGrowing;

		$detail .= captionedParagraph('certifications', 'Certifications', $this->certifications);
		$detail .= captionedParagraph('siteNotes', 'Site Notes', $this->siteNotes);
		$detail .= captionedParagraph('plantingNotes', 'Planting Notes', $this->plantingNotes);
		$detail .= captionedParagraph('transplantingNotes', 'Transplanting Notes', $this->transplantingNotes);
		$detail .= captionedParagraph('thinningNotes', 'Thinning Notes', $this->thinningNotes);
		$detail .= captionedParagraph('careNotes', 'Care Notes', $this->careNotes);
		$detail .= captionedParagraph('seedsOnHand', 'Seeds On Hand', $this->seedsOnHand);

//		$detail .= closeFieldset();
		
		$baseLink = $cropL->detail('VIEW',$this->id);
		$list = new CropList;
				
		if ($this->parentId == 0){
			$list->setDetails($this->id,$this->resultPageCrops,10);
		} else {
			$list->setDetails($this->parentId,$this->resultPageCrops,10);
		}
		$detail .= $list->getListing($baseLink);	
		
//		$detail .= closeDiv();
//		$detail .= closeDiv();
		$detail .= closeDisplayDetails();
		return $detail;
	}
	

	public function getCropSelectList(
		$selectedValue = 0, 
		$idName = 'cropId', 
		$disabled = 'false', 
		$parentCropId = -1,
		$filterSeedsOnHand = 'no',
		$showLink = true){

		$sql = $this->sql->selectOptions_Crops($parentCropId,$filterSeedsOnHand,$selectedValue,$disabled);
		
		$defaultValue = 0;
		$defaultCaption = '-select crop';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		

		$select = getSelectList($idName,$allOptions,'none',$disabled );

		if ($showLink == true){
			$cl = new CropLinks;					
			$link = $cl->detailViewEditHref($selectedValue);
			$select .= $link;
		}
		return $select;
	}
	
	private function setAddRecordDefaults(){
		$this->daysGerminate = 7;
		$this->daysGerminateMax = 10;
		$this->daysTransplant = 28;
		$this->daysTransplantMax = 0;
		$this->daysMature = 0;
		$this->daysMatureMax = 0;
		$this->seedsOnHand = 'no';
	}

	private function getInputMinMax($minName,$minValue,$maxName,$maxValue){
		$inputMin = getTextInput($minName, $minValue, 2, 4,'min');
		$inputMax = getTextInput($maxName, $maxValue, 2, 4,'max');
		$input = $inputMin.$inputMax;
		return $input;
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$this->setAddRecordDefaults();
		}
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Crop';
		} else {
			$legend = 'Edit Crop';
		}
		$entity = 'crop';
		$c = new ProjectTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');		
		$form = openEditForm($entity,$legend, 'cp_Crop_Save.php',$contextMenu);

		
		$table = openTable('naming-details','listing-table');
		$heading = wrapTh('Common');
		$heading .= wrapTh('Variety');
		$heading .= wrapTh('Botanical');
		$heading .= wrapTh('Family');
		$table .= wrapTr($heading);
		$row = wrapTd(getTextInput('commonName', $this->commonName, 20, 100));
		$row .= wrapTd(getTextInput('varietyName', $this->varietyName, 20, 100));
		$row .= wrapTd(getTextInput('botanicalName', $this->botanicalName, 20, 100));
		$row .= wrapTd(getTextInput('familyName', $this->familyName, 20, 100));
		$table .= wrapTr($row);
		$table .= closeTable();
		$fields = captionedInput('Naming',$table);
		 
		
		$table = openTable('growing-details','listing-table');
		$heading = wrapTh('Germinates');
		$heading .= wrapTh('Transplant');
		$heading .= wrapTh('Matures');
		$heading .= wrapTh('Seed Depth');
		$heading .= wrapTh('Seed Spacing');
		$heading .= wrapTh('Thin Height');
		$heading .= wrapTh('In Row Spacing');
		$heading .= wrapTh('Row Spacing');
		$table .= wrapTr($heading);
		$idMin = 'daysGerminate';
		$idMax = 'daysGerminateMax';
		$min = $this->daysGerminate;
		$max = $this->daysGerminateMax;
		$row = wrapTd($this->getInputMinMax($idMin,$min,$idMax,$max));
		$idMin = 'daysTransplant';
		$idMax = 'daysTransplantMax';
		$min = $this->daysTransplant;
		$max = $this->daysTransplantMax;
		$row .= wrapTd($this->getInputMinMax($idMin,$min,$idMax,$max));
		$idMin = 'daysMature';
		$idMax = 'daysMatureMax';
		$min = $this->daysMature;
		$max = $this->daysMatureMax;
		$row .= wrapTd($this->getInputMinMax($idMin,$min,$idMax,$max));
		$row .= wrapTd(getTextInput('seedDepthInches', $this->seedDepthInches, 4, 4));
		$row .= wrapTd(getTextInput('seedSpacingInches', $this->seedSpacingInches, 4, 4));
		$row .= wrapTd(getTextInput('thinningHeightInches',$this->thinningHeightInches, 4, 4));
		$row .= wrapTd(getTextInput('inrowSpacingInches', $this->inrowSpacingInches, 4, 4));		
		$row .= wrapTd(getTextInput('rowSpacingInches', $this->rowSpacingInches, 4, 4));
		$table .= wrapTr($row);
		$table .= closeTable();
		$fields .= captionedInput('Growing',$table);

		$input = $this->getCropSelectList($this->parentId,'parentCropId','false',0);
		$fields .= captionedInput('Parent Crop',$input);



		$input = getSelectYesNo('seedsOnHand', $this->seedsOnHand);
		$fields .= captionedInput('Seeds On Hand',$input);
				
		$formRequired = $fields;
		
		$fields .= inputFieldText($entity,$this->certifications,'certifications','certifications Notes',100);		

		$fields = inputFieldComments($entity,$this->siteNotes,'siteNotes','site Notes');		

		$fields .= inputFieldComments($entity,$this->plantingNotes,'plantingNotes','planting Notes');		
		
		$fields .= inputFieldComments($entity,$this->transplantingNotes,'transplantingNotes','transplant Notes');

		$fields .= inputFieldComments($entity,$this->thinningNotes,'thinningNotes','thinning Notes');
		
		$fields .= inputFieldComments($entity,$this->careNotes,'careNotes','care Notes');
		$formOptional = $fields;
		
		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('cropId', $this->id);

		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){
	
		$this->id = $_POST['cropId'];
		$this->parentId = $_POST['parentCropId'];
		$this->commonName = dbEscapeString($_POST['commonName']); 
		$this->varietyName = dbEscapeString($_POST['varietyName']); 
		$this->botanicalName = dbEscapeString($_POST['botanicalName']); 
		$this->familyName = dbEscapeString($_POST['familyName']); 
		$this->certifications = dbEscapeString($_POST['certifications']);
		$this->daysMature = $_POST['daysMature'];
		$this->daysMatureMax = $_POST['daysMatureMax'];
		$this->daysTransplant = $_POST['daysTransplant'];
		$this->daysTransplantMax = $_POST['daysTransplantMax'];
		$this->daysGerminate = $_POST['daysGerminate'];
		$this->daysGerminateMax = $_POST['daysGerminateMax'];
		$this->seedDepthInches = $_POST['seedDepthInches'];
		$this->seedSpacingInches = $_POST['seedSpacingInches'];
		$this->thinningHeightInches = $_POST['thinningHeightInches'];
		$this->inrowSpacingInches = $_POST['inrowSpacingInches'];
		$this->rowSpacingInches = $_POST['rowSpacingInches'];
		$this->plantingNotes = dbEscapeString($_POST['plantingNotes']); 
		$this->transplantingNotes = dbEscapeString($_POST['transplantingNotes']); 
		$this->thinningNotes = dbEscapeString($_POST['thinningNotes']); 
		$this->careNotes = dbEscapeString($_POST['careNotes']); 
		$this->siteNotes = dbEscapeString($_POST['siteNotes']); 
		if ($_POST['seedsOnHand'] != 'no'){
			$this->seedsOnHand = 'yes';
		} else {
			$this->seedsOnHand = 'no';
		}
		$this->pageMode = $_POST['mode'];	
	}

	public function saveChanges(){
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE crops AS c ";
			$sql .= " SET ";
			$sql .= " c.parent_id = ".$this->parentId.", ";
			$sql .= " c.common_name = '".$this->commonName."', ";
			$sql .= " c.variety_name = '".$this->varietyName."', ";
			$sql .= " c.botanical_name = '".$this->botanicalName."', ";
			$sql .= " c.family_name = '".$this->familyName."', ";
			$sql .= " c.certifications = '".$this->certifications."', ";
			$sql .= " c.days_mature = ".$this->daysMature.", ";
			$sql .= " c.days_mature_max = ".$this->daysMatureMax.", ";
			$sql .= " c.days_transplant = ".$this->daysTransplant.", ";
			$sql .= " c.days_transplant_max = ".$this->daysTransplantMax.", ";
			$sql .= " c.days_germinate = ".$this->daysGerminate.", ";
			$sql .= " c.days_germinate_max = ".$this->daysGerminateMax.", ";
			$sql .= " c.seed_depth_inches = ".$this->seedDepthInches.", ";
			$sql .= " c.seed_spacing_inches = ".$this->seedSpacingInches.", ";
			$sql .= " c.thinning_height_inches = ".$this->thinningHeightInches.", ";
			$sql .= " c.inrow_spacing_inches = ".$this->inrowSpacingInches.", ";
			$sql .= " c.row_spacing_inches = ".$this->rowSpacingInches.", ";
			$sql .= " c.site_notes = '".$this->siteNotes."', ";
			$sql .= " c.planting_notes = '".$this->plantingNotes."', ";
			$sql .= " c.transplanting_notes = '".$this->transplantingNotes."', ";
			$sql .= " c.thinning_notes = '".$this->thinningNotes."', ";
			$sql .= " c.care_notes = '".$this->careNotes."', ";
			$sql .= " c.seeds_on_hand = '".$this->seedsOnHand."' ";
			$sql .= " WHERE c.id = ".$this->id."  ";			
			$result = dbRunSQL($sql);

			//if updating a parent crop (planting information applies to all varieties)
			//update all variety records after updating crop			
			if ($this->parentId == 0 && $this->varietyName == ''){
	
				$sql = " UPDATE crops c set c.parent_id = ".$this->id." ";
				$sql .= " WHERE c.common_name = '".$this->commonName."' ";
				$sql .= " AND c.id <> ".$this->id;
				$result = dbRunSQL($sql);

				$sql = " UPDATE crops c set ";
				$sql .= " c.family_name = '".$this->familyName."', ";
				$sql .= " c.botanical_name = '".$this->botanicalName."', ";
				$sql .= " c.seed_depth_inches = ".$this->seedDepthInches.", ";
				$sql .= " c.seed_spacing_inches = ".$this->seedSpacingInches.", ";
				$sql .= " c.thinning_height_inches = ".$this->thinningHeightInches.", ";
				$sql .= " c.inrow_spacing_inches = ".$this->inrowSpacingInches.", ";
				$sql .= " c.row_spacing_inches = ".$this->rowSpacingInches.", ";
				$sql .= " c.days_transplant = ".$this->daysTransplant.", ";
				$sql .= " c.days_transplant_max = ".$this->daysTransplantMax." ";
				$sql .= " WHERE c.parent_id = ".$this->id." ";
				$result = dbRunSQL($sql);
				
				//if days germinate not set for a variety, update it now
				$sql = " UPDATE crops c set ";
				$sql .= " c.days_germinate = ".$this->daysGerminate.", ";
				$sql .= " c.days_germinate_max = ".$this->daysGerminateMax." ";
				$sql .= " WHERE c.parent_id = ".$this->id." ";
				$sql .= " AND c.days_germinate = 0 ";
				$result = dbRunSQL($sql);
				
				//if days mature not set for a variety, update it now
				$sql = " UPDATE crops c set ";
				$sql .= " c.days_mature = ".$this->daysMature.", ";
				$sql .= " c.days_mature_max = ".$this->daysMatureMax." ";
				$sql .= " WHERE c.parent_id = ".$this->id." ";
				$sql .= " AND c.days_mature = 0 ";
				$result = dbRunSQL($sql);
			}
		} else {
	
			$sql = " INSERT INTO crops ";
			$sql .= " (common_name, ";
			$sql .= " parent_id, ";
			$sql .= " variety_name, ";
			$sql .= " botanical_name, ";
			$sql .= " family_name, ";
			$sql .= " certifications, ";
			$sql .= " days_mature, ";
			$sql .= " days_mature_max, ";
			$sql .= " days_transplant, ";
			$sql .= " days_transplant_max, ";
			$sql .= " days_germinate, ";
			$sql .= " days_germinate_max, ";
			$sql .= " seed_depth_inches, ";
			$sql .= " seed_spacing_inches, ";
			$sql .= " thinning_height_inches, ";
			$sql .= " inrow_spacing_inches, ";
			$sql .= " row_spacing_inches, ";
			$sql .= " site_notes, ";
			$sql .= " planting_notes, ";
			$sql .= " transplanting_notes, ";
			$sql .= " thinning_notes, ";
			$sql .= " seeds_on_hand, ";
			$sql .= " care_notes) ";
			$sql .= " VALUES (";
			$sql .= " '".$this->commonName."', ";
			$sql .= " ".$this->parentId.", ";
			$sql .= " '".$this->varietyName."', ";
			$sql .= " '".$this->botanicalName."', ";
			$sql .= " '".$this->familyName."', ";
			$sql .= " '".$this->certifications."', ";
			$sql .= " ".$this->daysMature.", ";
			$sql .= " ".$this->daysMatureMax.", ";
			$sql .= " ".$this->daysTransplant.", ";
			$sql .= " ".$this->daysTransplantMax.", ";
			$sql .= " ".$this->daysGerminate.", ";
			$sql .= " ".$this->daysGerminateMax.", ";
			$sql .= " ".$this->seedDepthInches.", ";
			$sql .= " ".$this->seedSpacingInches.", ";
			$sql .= " ".$this->thinningHeightInches.", ";
			$sql .= " ".$this->inrowSpacingInches.", ";
			$sql .= " ".$this->rowSpacingInches.", ";
			$sql .= " '".$this->siteNotes."', ";
			$sql .= " '".$this->plantingNotes."', ";
			$sql .= " '".$this->transplantingNotes."', ";
			$sql .= " '".$this->thinningNotes."', ";
			$sql .= " '".$this->seedsOnHand."', ";			
			$sql .= " '".$this->careNotes."') ";
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertId();
		}
	
	}
	
} 
//end class Crop
class CropSQL{
	public function columnsCrop(){
		$c = " c.id, "; 
		$c .= " c.parent_id, "; 
		$c .= " c.common_name, "; 
		$c .= " c.variety_name, "; 
		$c .= " c.botanical_name, ";
		$c .= " c.family_name, "; 
		$c .= " c.certifications, ";
		$c .= " c.seeds_on_hand, ";
		$c .= " c.days_mature, "; 
		$c .= " c.days_mature_max, "; 
		$c .= " c.days_transplant, "; 
		$c .= " c.days_transplant_max, "; 
		$c .= " c.days_germinate, "; 
		$c .= " c.days_germinate_max, "; 
		$c .= " c.seed_depth_inches, "; 
		$c .= " c.seed_spacing_inches, "; 
		$c .= " c.thinning_height_inches, ";
		$c .= " c.inrow_spacing_inches, "; 
		$c .= " c.row_spacing_inches, "; 
		$c .= " c.site_notes, "; 
		$c .= " c.planting_notes, "; 
		$c .= " c.transplanting_notes, "; 
		$c .= " c.thinning_notes, "; 
		$c .= " c.care_notes ";
		return $c;	
	}

	public function infoCrop($cropId){
		$q = " SELECT ";	
		$q .= $this->columnsCrop();
		$q .= " FROM crops c ";
		$q .= " WHERE  ";
		$q .= " c.id = ".$cropId." ";
		return $q;
	}

	public function listCrops($parentCropId,$resultPage, $rowsPerPage, $filterSeedsOnHand = 'no'){
		$q = " SELECT ";	
		$q .= $this->columnsCrop();
		$q .= " FROM crops c ";
		if ($parentCropId >= 0){
			$q .= " WHERE c.parent_id = ".$parentCropId." ";
		}
		$q .= " ORDER BY common_name, variety_name ";
		$q .= sqlLimitClause($resultPage, $rowsPerPage);
		return $q;	
	}

	public function countCrops($parentCropId){
		$q = " SELECT ";	
		$q .= " COUNT(*) total_crops ";
		$q .= " FROM crops c ";
		if ($parentCropId >= 0){
			$q .= " WHERE c.parent_id = ".$parentCropId." ";
		}
		return $q;	
	}

	public function selectOptions_Crops($parentCropId,$filterSeedsOnHand,$selectedValue,$disabled){
		$q = " SELECT ";
		$q .= " c.id as value, ";
		$q .= " concat(
			common_name,
			if(variety_name!='',concat(':',variety_name),''),
			if(seeds_on_hand='yes','--SEEDS ON HAND','')
			) as caption ";
		$q .= " FROM crops c ";

		$filterCount = 0;
		$w = '';
		if ($parentCropId != -1){
			$w = " c.parent_id = ".$parentCropId." ";
			$filterCount++;
		}
		if ($filterSeedsOnHand	== 'yes'){
			if ($filterCount > 0){
				$w .= " AND ";
			}
			$w .= " seeds_on_hand = 'yes' ";
			$filterCount++;
		}
		if ($disabled == 'true'){
			if ($filterCount > 0){
				$w .= " AND ";
			}
			$w .= " c.id = ".$selectedValue." ";
			$filterCount++;
		}
		if ($filterCount > 0){	  
			$q .= " WHERE ".$w;
		}
		$q .= " ORDER BY caption ";
		
		//echo $q;
		return $q;	
	}
}
//end class CropSQL
?>
