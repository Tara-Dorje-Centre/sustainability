<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class CropPlanLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($caption = 'AllCropPlans'){
		$link = $this->listing();
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	private function detailHref($pageAction = 'VIEW', $cropPlanId = 0, $caption = 'Plan',$sortMethod='CROP'){
		$link = $this->detail($pageAction,$cropPlanId,$sortMethod);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing(){
		$link = 'cp_CropPlan_List.php';
		return $link;
	}	
	public function listingPaged($found, $resultPage, $perPage){
		$l = $this->listing().'?resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	public function detail($pageAction, $cropPlanId = 0, $sortMethod = 'CROP'){
		$link = 'cp_CropPlan_Detail.php?pageAction='.$pageAction;
		if ($cropPlanId != 0){
			$link .= '&cropPlanId='.$cropPlanId;
		}
		$link .= '&sortMethod='.$sortMethod;
		return $link;
	}	
	public function detailAddHref($caption = '+CropPlan'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($cropPlanId,$caption = 'ViewCropPlan',$sortMethod='CROP'){
		$l = $this->detailHref('VIEW',$cropPlanId,$caption,$sortMethod);
		return $l;	
	}
	public function detailEditHref($cropPlanId,$caption = 'EditCropPlan'){
		$l = $this->detailHref('EDIT',$cropPlanId,$caption);
		return $l;	
	}
	
	public function detailViewEditHref($cropPlanId = 0, $viewCaption = 'CropPlan'){
		
		if ($cropPlanId != 0){
			$links = $this->detailViewHref($cropPlanId,$viewCaption);
			$links .= $this->detailEditHref($cropPlanId,'#');
		}
		return $links;
	}	
}
//end class CropPlanLinks
class CropPlanList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new CropPlanSQL;	
	}
	
	public function setDetails($resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'Crop Plans';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';

		$plans = new CropPlanLinks($menuType,$menuStyle);
		$crops = new CropLinks($menuType,$menuStyle);
		
		$menu = $plans->openMenu('section-heading-links');
		$menu .= $plans->detailAddHref();
		$menu .= $plans->listingHref();
		$menu .= $plans->resetMenu();
		$menu .= $crops->listingHref();		
		$menu .= $plans->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$sql = $this->sql->countCropPlans();
		$this->found = dbGetCount($sql, 'total_plans');
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
		$sql = $this->sql->listCropPlans($this->resultPage,$this->perPage);

		$cl = new CropPlanLinks;
		$pagingLinks = $cl->listingPaged($this->found,$this->resultPage,$this->perPage);
		$cp = new CropPlan;
		$cp->setDetails(0,'ADD');
		$quickEdit = $cp->editForm();
		//$quickEdit = NULL;
		$list = openDisplayList('crop-plans','Crop Plans',$pagingLinks,$quickEdit);

		$heading = wrapTh('Plan');
		$heading .= wrapTh('Plan Year');
		$heading .= wrapTh('Plan Number');
		$heading .= wrapTh('Description');		
		$list .= wrapTr($heading);

		$result = dbGetResult($sql);
		if ($result){
		while($row = $result->fetch_assoc())
		{	
			$c = new CropPlan;
			
			$c->id = $row["id"]; 
			$c->planNumber = $row["plan_number"]; 
			$c->planName = stripslashes($row["plan_name"]); 
			$c->planYear = stripslashes($row["plan_year"]); 
			
			$c->description = stripslashes($row["description"]); 
			$c->started = stripslashes($row["started"]); 
			$c->updated = stripslashes($row["updated"]); 

			$c->formatForDisplay();

			$link = $cl->detailViewEditHref($c->id,$c->planName);
			$detail = wrapTd($link);
			$detail .=  wrapTd($c->planYear);						
			$detail .=  wrapTd($c->planNumber);			
			$detail .=  wrapTd($c->description);			

			$list .=  wrapTr($detail);
		}
		$result->close();
		}

		$list .= closeDisplayList();
		return $list;		
	}
}
//end class CropPlanList
class CropPlan {
    public $id = 0; 
    public $planName;
	public $planYear;
    public $planNumber = 0;
    public $description;
	public $started;
	public $updated;
	public $finished;
	public $mature;
	public $transplanted;
	public $planType = 'PLANTING';
	public $sortMethod = 'CROP';
	// property to support edit/view/add mode of calling page
    public $pageMode;
	public $resultPage = 1;
	private $sql;
	
	public function __construct(){
		$this->sql	= new CropPlanSQL;
	}
	
    // set class properties with record values from database
	public function setDetails($detailCropPlanId, $inputMode,$resultPage = 1){
		$this->pageMode = $inputMode;
		$this->resultPage = $resultPage;
		$this->id = $detailCropPlanId;

		$sql = $this->sql->infoCropPlan($this->id);
		//echo 'in cropPlan.setDetails '.$sql;
		
		$result = dbGetResult($sql);
		if ($result){
		while($row = $result->fetch_assoc())
			{	
			$this->id = $row["id"]; 
			$this->planNumber = $row["plan_number"]; 
			$this->planName = stripslashes($row["plan_name"]); 
			$this->planYear = $row["plan_year"];
			$this->description = stripslashes($row["description"]); 
			$this->started = $row["started"];
			$this->updated = $row["updated"];
			$this->finished = $row["finished"]; 
			$this->mature = $row["mature"];
			$this->transplanted = $row["transplanted"];
			$this->planType = $row["plan_type"];
		}
		$result->close();
		}
				
	}	
		
	function pageTitle(){
		$heading = openDiv('section-heading-title');
		if ($this->pageMode != 'ADD'){
			$heading .= 'Crop Plan: '.$this->planName;
		} else {
			$heading .= 'Add New Crop Plan';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$plan = new CropPlanLinks($menuType,$menuStyle);		
		$planting = new CropPlantingLinks($menuType,$menuStyle);
		
		$menu = $plan->openMenu('section-heading-links');
		if ($this->pageMode == 'VIEW'){
			$menu .= $plan->detailEditHref($this->id);
		}
		if ($this->pageMode == 'EDIT'){
			$menu .= $plan->detailViewHref($this->id,'ViewPlan',$this->sortMethod);
		}

		$menu .= $plan->listingHref();
		if ($this->pageMode != 'ADD' && $this->pageMode != 'EDIT'){
			$menu .= $plan->resetMenu();
			$menu .= $planting->detailAddHref($this->id);
			$menu .= $plan->resetMenu();
			$menu .= 'Lists:'.$plan->detailViewHref($this->id,'ByCrop','CROP');
			$menu .= $plan->detailViewHref($this->id,'ByPlant','PLANTED');
			$menu .= $plan->detailViewHref($this->id,'ByTransplant','TRANSPLANTED');
			$menu .= $plan->detailViewHref($this->id,'ByHarvest','MATURE');
			$menu .= $plan->resetMenu();
			$menu .= 'Calendars:'.$planting->listingHref($this->id,'Plant','PLANTED','YES');
			$menu .= $planting->listingHref($this->id,'Transplant','TRANSPLANTED','YES');
			$menu .= $planting->listingHref($this->id,'Harvest','MATURE','YES');
			
		}

		$menu .= $plan->closeMenu();
		return $menu;
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function formatForDisplay(){
		$this->description = displayLines($this->description); 
		$this->started = getTimestampDate($this->started);
		$this->updated = getTimestampDate($this->updated);
		$this->finished = getTimestampDate($this->finished);
		$this->mature = getTimestampDate($this->mature);
		$this->transplanted = getTimestampDate($this->transplanted);
		
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
				
		$detail = openDisplayDetails('crop-plan','Crop Plan Details');

		$detail .= captionedParagraph('planYear', 'Plan Year', $this->planYear);	 						
		$detail .= captionedParagraph('planName', 'Plan Name', $this->planName);
		$detail .= captionedParagraph('planNumber', 'Plan Number', $this->planNumber);
		$detail .= captionedParagraph('planType', 'Plan Type', $this->planType);
		
		$detail .= captionedParagraph('description', 'description', $this->description);
		$detail .= captionedParagraph('started', 'started', $this->started);
		$detail .= captionedParagraph('updated', 'updated', $this->updated);
		$detail .= captionedParagraph('transplanted', 'transplanted', $this->transplanted);
		$detail .= captionedParagraph('mature', 'mature', $this->mature);

		$detail .= captionedParagraph('finished', 'finished', $this->finished);

		$plantings = new CropPlantingList;
		$l = new CropPlanLinks();
		$link = $l->detail('VIEW',$this->id,$this->sortMethod);
		$plantings->setDetails($this->id, $this->resultPage, 15);
		$plantings->sortMethod = $this->sortMethod;
		$detail .= $plantings->getListing($link);
		
		$detail .= closeDisplayDetails();
		return $detail;
	}
	
	private function setAddRecordDefaults(){
		global $sessionTime;
		$this->started = $sessionTime;	
		$this->finished = addDays($sessionTime,120);
		$this->planType = 'PLANTING';
	}
	
	public function getCropPlanTypeSelectList(
	$selectedValue = 'PLANTING', $idName = 'planType',$disabled = 'false'){
		
		$sql = $this->sql->selectOptions_CropPlanTypes($selectedValue,$disabled);
		
		$defaultValue = 'NO_DEFAULT_VALUE';
		$defaultCaption = '-select crop plan type';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		$select = getSelectList($idName,$allOptions);
		return $select;		
	}

	
	public function getCropPlanTypeHelp(){
		$sql = $this->sql->listCropPlanTypes();

		$help = br().'Change plan type to update planting dates:';
		$result = dbGetResult($sql);
		if ($result){
		while($row = $result->fetch_assoc())
		{	
			$planType = $row["plan_type"];
			$description = ($row["description"]);
			$help .= br().$planType.' '.$description;
		}
		$result->close();
		}
		
		return $help;
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Crop Plan';
		} else {
			$legend = 'Edit Crop Plan';
		}
		$entity = 'crop-plan';
		$c = new ProjectTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');		
		$form = openEditForm($entity,$legend,'cp_CropPlan_Save.php',$contextMenu);

		$fields = inputFieldName($entity, 'planName', $this->planName, 'Plan Name'); 				

		$fields .= inputFieldNumber($entity,$this->planYear,'planYear','Plan Year');
		
		$fields .= inputFieldNumber($entity,$this->planNumber,'planNumber','Plan Number');		
				

		//end required fields
		$formRequired = $fields;

		$fields = inputFieldDescription($entity, 'description', $this->description); 		


				
		$caption = 'Start Date: ';
		$caption .= 'Required for plan type PLANTING';		
		$fields .= inputFieldTimestamp($entity, 'started', $this->started, $caption); 		

		$caption = 'Mature Date: ';
		$caption .= 'Required for plan type MATURITY';
		$fields .= inputFieldTimestamp($entity, 'mature', $this->mature, $caption); 		

		$caption = 'Transplant Date: ';
		$caption .= 'Required for plan type TRANSPLANT';
		$fields .= inputFieldTimestamp($entity, 'transplanted', $this->transplanted, $caption); 		
		
		$fields .= inputFieldTimestamp($entity, 'finished', $this->finished, 'Finish Date'); 		

		$select = $this->getCropPlanTypeSelectList($this->planType,'planType','false');
		$text = $this->getCropPlanTypeHelp();
		$fields .= captionedInput('Plan Type',$select.$text);


		//end optional fields
		$formOptional = $fields;
		
		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('cropPlanId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){
		$this->id = $_POST['cropPlanId'];
		$this->planNumber = $_POST['planNumber'];
		$this->planYear = $_POST['planYear'];
		$this->planName = dbEscapeString($_POST['planName']); 
		$this->planType = dbEscapeString($_POST['planType']);
		$this->description = dbEscapeString($_POST['description']); 
		$this->started = dbEscapeString('started');
		$this->finished = dbEscapeString('finished');
		$this->mature = getTimestampPostValues('mature');
		$this->transplanted = getTimestampPostValues('transplanted');

		$this->pageMode = $_POST['mode'];	
	}

	public function updatePlantingDates(){
		
		if ($this->planType != 'UNSPECIFIED'){
			$cpl = new CropPlantingList;
			$cpl->setDetails($this->id);
			$cpl->updateByPlanType();	
		}
		
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE crop_plans AS c ";
			$sql .= " SET ";
			$sql .= " c.plan_year = ".$this->planYear.", ";			
			$sql .= " c.plan_number = ".$this->planNumber.", ";
			$sql .= " c.plan_name = '".$this->planName."', ";
			$sql .= " c.plan_type = '".$this->planType."', ";
			$sql .= " c.transplanted = '".$this->transplanted."', ";
			$sql .= " c.mature = '".$this->mature."', ";
			$sql .= " c.started = '".$this->started."', ";
			$sql .= " c.finished = '".$this->finished."', ";
			$sql .= " c.updated = CURRENT_TIMESTAMP, ";			
			$sql .= " c.description = '".$this->description."' ";
			$sql .= " WHERE c.id = ".$this->id."  ";			
			$result = dbRunSQL(sql);
			
			$this->updatePlantingDates();
		} else {
	
			$sql = " INSERT INTO crop_plans ";
			$sql .= " (plan_name, ";
			$sql .= " plan_year, ";
			$sql .= " plan_number, ";
			$sql .= " plan_type, ";
			$sql .= " started, ";
			$sql .= " transplanted, ";
			$sql .= " mature, ";
			$sql .= " finished, ";
			$sql .= " updated, ";
			$sql .= " description) ";
			$sql .= " VALUES (";
			$sql .= " '".$this->planName."', ";
			$sql .= " ".$this->planYear.", ";
			$sql .= " ".$this->planNumber.", ";
			$sql .= " '".$this->planType.", ";
			$sql .= " '".$this->started."', ";
			$sql .= " '".$this->transplanted."', ";
			$sql .= " '".$this->mature."', ";
			$sql .= " '".$this->finished."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " '".$this->description."') ";
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertId();
		}
	
	}
	
} 
//end class CropPlan
class CropPlanSQL{
	public function columnsCropPlans(){
		$c = " c.id, "; 
		$c .= " c.plan_name, "; 
		$c .= " c.plan_year, ";
		$c .= " c.plan_number, "; 
		$c .= " c.description, ";
		$c .= " c.started, "; 
		$c .= " c.updated, "; 
		$c .= " c.mature, "; 
		$c .= " c.transplanted, "; 
		$c .= " c.plan_type, "; 
		$c .= " c.finished "; 
		return $c;	
	}

	public function infoCropPlan($cropPlanId){
		$q = " SELECT ";	
		$q .= $this->columnsCropPlans();
		$q .= " FROM crop_plans c ";
		$q .= " WHERE  ";
		$q .= " c.id = ".$cropPlanId." ";
		return $q;
	}

	public function listCropPlans($resultPage, $rowsPerPage){
		$q = " SELECT ";	
		$q .= $this->columnsCropPlans();
		$q .= " FROM crop_plans c ";
		$q .= " ORDER BY plan_year, plan_number ";
		$q .= sqlLimitClause($resultPage, $rowsPerPage);
		return $q;	
	}

	public function countCropPlans(){
		$q = " SELECT ";	
		$q .= " COUNT(*) total_plans ";
		$q .= " FROM crop_plans c ";
		return $q;	
	}

	public function selectOptions_CropPlanTypes(){
		$q = " SELECT ";
		$q .= " cpt.plan_type value, ";
		$q .= " cpt.plan_type caption ";
		$q .= " FROM crop_plan_types cpt ";
		$q .= " ORDER BY cpt.plan_type ";
		return $q;
	}

	public function listCropPlanTypes(){
		$q = " SELECT ";
		$q .= " cpt.plan_type, ";
		$q .= " cpt.description ";
		$q .= " FROM crop_plan_types cpt ";
		$q .= " ORDER BY cpt.plan_type ";
		return $q;	
	}
}
//end class CropPlanSQL
?>
