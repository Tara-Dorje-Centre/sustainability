<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class CropPlantingLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($cropPlanId,$caption = 'Plantings',$sortMethod = 'CROP',$showCalendar = 'NO'){
		$link = $this->listing($cropPlanId,$sortMethod,$showCalendar);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	private function detailHref($pageAction = 'VIEW', $cropPlantingId = 0, $cropPlanId=0,$caption = 'Planting'){
		$link = $this->detail($pageAction,$cropPlantingId,$cropPlanId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing($cropPlanId, $sortMethod = 'CROP',$showCalendar = 'NO'){
		$link = 'cp_CropPlanting_List.php?cropPlanId='.$cropPlanId;
		$link .= '&sortMethod='.$sortMethod;
		if ($showCalendar == 'YES'){
			$link .= '&showCalendar=YES';	
		}
		return $link;
	}
	
	public function listingPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	
	public function detail($pageAction, $cropPlantingId = 0, $cropPlanId = 0){
		$link = 'cp_CropPlanting_Detail.php?pageAction='.$pageAction;
		if ($cropPlantingId != 0){
			$link .= '&cropPlantingId='.$cropPlantingId;
		}
		if ($cropPlanId != 0){
			$link .= '&cropPlanId='.$cropPlanId;
		}
		return $link;
	}	
	public function detailAddHref($cropPlanId,$caption = '+Planting'){
		$l = $this->detailHref('ADD',0,$cropPlanId,$caption);
		return $l;	
	}
	public function detailViewHref($cropPlantingId,$caption = 'ViewPlanting'){
		$l = $this->detailHref('VIEW',$cropPlantingId,0,$caption);
		return $l;	
	}
	public function detailEditHref($cropPlantingId,$caption = 'EditPlanting'){
		$l = $this->detailHref('EDIT',$cropPlantingId,0,$caption);
		return $l;	
	}
	public function detailViewEditHref($cropPlantingId = 0, $viewCaption = 'Planting Details'){
		
		$links = $this->detailViewHref($cropPlantingId,$viewCaption);
		$links .= $this->detailEditHref($cropPlantingId,'#');
		return $links;
	}	
	
}
//end class CropPlantingLinks
class CropPlantingList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $cropPlanId = 0;
	public $cropPlan;
	public $sortMethod = 'CROP';
	public $showCalendar = 'NO';
	public $year = -1;
	public $month = -1;
	private $sql;
	
	public function __construct(){
		$this->sql = new CropPlantingSQL;
		$this->cropPlan = new CropPlan;	
	}
	
	public function setDetails($cropPlanId, $resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		$this->cropPlanId = $cropPlanId;
		$this->setFoundCount();
		
		$this->cropPlan->setDetails($this->cropPlanId, 'VIEW');
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= $this->cropPlan->planYear.spacer().$this->cropPlan->planName.': Plantings';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$plans = new CropPlanLinks($menuType,$menuStyle);
		$plantings = new CropPlantingLinks($menuType,$menuStyle);
		$crops = new CropLinks($menuType,$menuStyle);
		
		$menu = $plans->openMenu('section-heading-links');
		$menu .= $plans->resetMenu();
		$menu .= $plans->detailViewHref($this->cropPlanId);
		$menu .= $plans->resetMenu();
		$menu .= 'Calendars:'.$plantings->listingHref($this->cropPlanId,'Plant','PLANTED','YES');
		$menu .= $plantings->listingHref($this->cropPlanId,'Transplant','TRANSPLANTED','YES');
		$menu .= $plantings->listingHref($this->cropPlanId,'Harvest','MATURE','YES');
			
		$menu .= $plans->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$sql = $this->sql->countCropPlantings($this->cropPlanId);
		$this->found = getSQLCount($sql, 'total_plantings');
	}
	
	public function updateByPlanType(){
		if ($this->cropPlan->planType != 'LOCKED'){
			$sql = $this->sql->listCropPlantingsPlanTypeUpdate($this->cropPlanId);
			$result = mysql_query($sql) or die(mysql_error());
			while($row = mysql_fetch_array($result))
			{	
				$plantingId = $row["id"];
				$plantedByStart = $row["planted_by_start"];
				$plantedByMature = $row["planted_by_mature"];
				$plantedByTransplant = $row["planted_by_transplant"];
				$method = $this->cropPlan->planType;
			
				if ($this->cropPlan->planType == 'PLANTING'){
					$planted = $plantedByStart;				
				} elseif ($this->cropPlan->planType == 'MATURITY'){
					$planted = $plantedByMature;				
				} elseif ($this->cropPlan->planType == 'TRANSPLANT'){
					$planted = $plantedByTransplant;				
				}
			
				$updateSQL = " UPDATE crop_plantings cp SET cp.planted = '".$planted."', ";
				$updateSQL .= "cp.updated = CURRENT_TIMESTAMP ";
				$updateSQL .= " WHERE cp.id = ".$plantingId." ";
			
				$updateResult = mysql_query($updateSQL) or die(mysql_error());
				mysql_free_result($updateResult);
			}
			mysql_free_result($result);	
		}
	}

	public function setCalendarRange($year, $month){

		if ($year != -1 && $month != -1){
			$this->year = $year;
			$this->month = $month;
		} else {
			$this->setMinCalendarRange();
		}
	}
	
	private function setMinCalendarRange(){
		$sql = $this->sql->calendarRangePlantings($this->cropPlanId,$this->sortMethod);
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{	
			$month = $row["month_item"];
			$year = $row["year_item"];
		}
		mysql_free_result($result);
		$this->year = $year;
		$this->month = $month;				
	}
	
	private function getCalendarLinks(){
		$l = new CropPlantingLinks('DIV','paged');
		$baseUrl = $l->listing($this->cropPlanId,$this->sortMethod,'YES');
		$links = $l->openMenu('calendar-links');
				
		$sql = $this->sql->calendarLinksPlantings($this->cropPlanId,$this->sortMethod);
		$result = mysql_query($sql) or die("Couldn't get planting links list ($sql)");
		while($row = mysql_fetch_array($result))
		{	
			$month = $row["month_item"];
			$year = $row["year_item"];
			$caption = $year.'-'.$month;
			if ($year == $this->year && $month == $this->month){
				//skip link and show caption if link for current display monthyear
				$link = span($caption,$l->cssItem.'-current');
			} else {
				$link = $l->formatCalendarHref($caption,$baseUrl,$year,$month);
			}
			$links .= $link;
		}
		mysql_free_result($result);
		$links .= $l->closeMenu();
		return $links;
	}
	
	public function getCalendar(){	
		$pl = new CropPlantingLinks();
		if ($this->sortMethod == 'CROP' || $this->sortMethod == 'PLANTED'){
			$title = 'Plantings';
		} elseif ($this->sortMethod == 'TRANSPLANTED'){
			$title = 'Transplantings';
		} elseif ($this->sortMethod == 'MATURE'){
			$title = 'Estimated Harvests';
		}
		$cal = new _Calendar($this->year,$this->month,$title);

		$sql = $this->sql->calendarDetailsPlantings($this->cropPlanId,$this->sortMethod,$this->year,$this->month);
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{	
			$commonName = stripslashes($row["common_name"]);
			$varietyName = stripslashes($row["variety_name"]);
			$name = $commonName.'['.$varietyName.']';
			$dateItem = $row["date_item"];
			$plantingId = $row["planting_id"];
			$cropId = $row["crop_id"];
			$plantingLink = $pl->detailViewHref($plantingId,$name);

			$calendarItem = $plantingLink;
			$cal->addItemByTimestamp($dateItem,$calendarItem);
		}
		mysql_free_result($result);

		$content = openDiv('planting-calendar');
		$links = $this->getCalendarLinks();		
		$content .= $links;
		$content .= $cal->buildCalendar();
		$content .= closeDiv();
		return $content;		
	}
	
	public function printPage(){
		
		$heading = $this->getPageHeading();
		$details = $this->getPageDetails();

		$site = new _SiteTemplate;
		$site->setSiteTemplateDetails($heading, $details);
		$site->printSite();
		
	}
	
	public function getPageDetails(){
	
		if ($this->showCalendar == 'YES'){
			$details = $this->getCalendar();
		} else {
			$details = $this->getListing();
		}
		return $details;
		
	}
	
	public function getListing($pagingBaseLink = 'USE_LISTING'){



		$sql = $this->sql->listCropPlantings($this->cropPlanId, $this->sortMethod,$this->resultPage,$this->perPage);
		
		
		$result = mysql_query($sql) or die(mysql_error());
		
		$cl = new CropPlantingLinks;
		if ($pagingBaseLink == 'USE_LISTING'){
			$base = $cl->listing($this->cropPlanId,$this->sortMethod);
		} else { 
			$base = $pagingBaseLink;
		}
		$pagingLinks = $cl->listingPaged($base,$this->found,$this->resultPage,$this->perPage);
		//echo 'safe cropplanid='.$this->cropPlan->id;
		
		$cpl = new CropPlanting;
		$cpl->setDetails(0,$this->cropPlanId,'ADD');
		//echo 'safe after set details for add';
		$quickEdit = $cpl->editform();
		//echo 'safe after edit form';
		$list = openDisplayList('crop-plantings','Crop Plantings:'.$this->sortMethod,$pagingLinks,$quickEdit);
		//echo 'after new edit form';

		
		$heading = wrapTh('Crop');
		$heading .= wrapTh('Location');
		$heading .= wrapTh('Planted (week)');
		$heading .= wrapTh('Count');
		$heading .= wrapTh('Germinates');
		$heading .= wrapTh('Transplant (week)');
		$heading .= wrapTh('Matures (week)');
		$list .= wrapTr($heading);

		while($row = mysql_fetch_array($result))
		{	
			$c = new CropPlanting;
			
			$c->id = $row["id"]; 
			$c->cropId = $row["crop_id"]; 
			$c->commonName = stripslashes($row["common_name"]); 
			$c->varietyName = stripslashes($row["variety_name"]); 
			$c->cropPlanId = stripslashes($row["crop_plan_id"]); 
			$c->locationName = stripslashes($row["location_name"]);
			$c->planted = $row["planted"];
			$c->germinated = $row["germinated"];
			$c->estimatedGermination = $row["estimated_germination"];
			$c->estimatedMaturity = $row["estimated_maturity"];			
			$c->estimatedTransplant = $row["estimated_transplant"];
			$c->plantedCount = $row["planted_count"];
			$c->weekPlant = $row["week_plant"];
			$c->weekTransplant = $row["week_transplant"];
			$c->weekMature = $row["week_mature"];

			$c->formatForDisplay();
			
			$link = $cl->detailViewEditHref($c->id,$c->commonName.'{'.$c->varietyName.'}');
			$detail = wrapTd($link);
			$detail .=  wrapTd($c->locationName);			
			$detail .=  wrapTd($c->planted.'('.$c->weekPlant.')');
			$detail .= wrapTd($c->plantedCount);			
			$detail .=  wrapTd($c->estimatedGermination);
			$detail .=  wrapTd($c->estimatedTransplant.'('.$c->weekTransplant.')');
			$detail .= wrapTd($c->estimatedMaturity.'('.$c->weekMature.')');			

			$list .=  wrapTr($detail);
		}
		mysql_free_result($result);

		$list .= closeDisplayList();
		return $list;
	}
}
//end class CropPlantingList
class CropPlanting {
	public $cropPlan;
	public $crop;
	public $id = 0;
	public $cropId = 0;
	public $method;
	public $cropPlanId = 0;
	public $locationId = 0;
	public $locationName;	
	public $commonName;
	public $varietyName;
	public $planted;
	public $daysMature = 0;
	public $daysGerminate = 0;
	public $germinated;
	public $weekPlant = 0;
	public $weekTransplant = 0;
	public $weekMature = 0;
	public $estimatedGermination;
	public $estimatedTransplant;
	public $estimatedMaturity;
	public $updated;
	public $plantedCount = 0;
	public $germinatedCount = 0;
	public $thinnedCount = 0;			
	public $rowsPlanted = 0;
	public $perRowPlanted = 0;
	public $notes;			
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	
	public function __construct(){
		$this->sql = new CropPlantingSQL;	
		$this->cropPlan = new CropPlan;
	}
	
    // set class properties with record values from database
	public function setDetails($detailCropPlantingId, $cropPlanId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailCropPlantingId;
		$this->cropPlanId = $cropPlanId;

		$sql = $this->sql->infoCropPlanting($this->id);
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
			{	
			$this->id = $row["id"]; 
			$this->cropId = $row["crop_id"]; 
			$this->commonName = stripslashes($row["common_name"]); 
			$this->varietyName = stripslashes($row["variety_name"]); 
			$this->method = stripslashes($row["method"]); 
			$this->cropPlanId = stripslashes($row["crop_plan_id"]); 
			$this->locationId = $row["location_id"];
			$this->locationName = stripslashes($row["location_name"]);
			$this->planted = $row["planted"];
			$this->germinated = $row["germinated"];
			$this->daysMature = $row["days_mature"];
			$this->daysGerminate = $row["days_germinate"];
			$this->estimatedGermination = $row["estimated_germination"];
			$this->estimatedTransplant = $row["estimated_transplant"];
			$this->estimatedMaturity = $row["estimated_maturity"];
			$this->updated = $row["updated"];
			$this->plantedCount = $row["planted_count"];
			$this->germinatedCount = $row["germinated_count"];
			$this->thinnedCount = $row["thinned_count"];
			$this->rowsPlanted = $row["rows_planted"];
			$this->perRowPlanted = $row["per_row_planted"];
			$this->notes = stripslashes($row["notes"]);
		}
		mysql_free_result($result);
				
		$this->cropPlan->setDetails($this->cropPlanId,"VIEW");				
	}	
		
	function pageTitle(){
		$heading = openDiv('section-heading-title');
		$heading .= 'Crop Plan: '.$this->cropPlan->planName.br();
		if ($this->pageMode != 'ADD'){
			$heading .= 'Planting: '.$this->commonName;
		} else {
			$heading .= 'Add New Crop Planting';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$plantings = new CropPlantingLinks($menuType,$menuStyle);		
		$plans = new cropPlanLinks($menuType,$menuStyle);
		
		$menu = $plantings->openMenu('section-heading-links');
		
		if ($this->pageMode != 'ADD'){
			$menu .= $plantings->detailAddHref($this->cropPlanId);
		}
		
		if ($this->pageMode == 'VIEW'){
			$menu .= $plantings->detailEditHref($this->id);
		}
		if ($this->pageMode == 'EDIT'){
			$menu .= $plantings->detailViewHref($this->id);
		}
		$menu .= $plantings->resetMenu();
		$menu .= $plans->detailViewHref($this->cropPlanId);
		$menu .= $plans->listingHref();
		
		$menu .= $plantings->closeMenu();
		return $menu;
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function formatForDisplay(){
		$this->notes = displayLines($this->notes); 
		$this->planted = getTimestampDate($this->planted);
		$this->estimatedGermination = getTimestampDate($this->estimatedGermination);
		$this->estimatedMaturity = getTimestampDate($this->estimatedMaturity);
		$this->estimatedTransplant = getTimestampDate($this->estimatedTransplant);
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
		
		$detail = openDisplayDetails('crop-planting','Crop Planting Details');		
	 	
		$c = new Crop;
		$select = $c->getCropSelectList($this->cropId,'cropId','true');
		$detail .= captionedParagraph('crop', 'Crop', $select);
		
		$l = new Location;
		$select = $l->getLocationSelectList($this->locationId,'locationId','true');
		$detail .= captionedParagraph('location', 'Location', $select);

		$detail .= captionedParagraph('method', 'method', $this->method);
		
		$detail .= captionedParagraph('planted', 'planted', $this->planted);
		$detail .= captionedParagraph('plantedCount', 'plantedCount', $this->plantedCount);
		$detail .= captionedParagraph('rowsPlanted', 'rowsPlanted', $this->rowsPlanted);
		$detail .= captionedParagraph('perRowPlanted', 'perRowPlanted', $this->perRowPlanted);
		$detail .= captionedParagraph('germinated', 'germinated', $this->germinated);
		$detail .= captionedParagraph('estimated-germination', 'estimated germination', $this->estimatedGermination);		
		$detail .= captionedParagraph('germinatedCount', 'germinatedCount', $this->germinatedCount);
		$detail .= captionedParagraph('thinnedCount', 'thinnedCount', $this->thinnedCount);
		$detail .= captionedParagraph('estimated-transplant', 'estimated transplant', $this->estimatedTransplant);
		$detail .= captionedParagraph('estimated-maturity', 'estimated maturity', $this->estimatedMaturity);
		$detail .= captionedParagraph('notes', 'notes', $this->notes);
				
		$detail .= closeDisplayDetails();
		return $detail;
	}
		
	private function setAddRecordDefaults(){	
		$this->planted = $this->cropPlan->started;
		$this->cropId = 0;
		$this->locationId = 0;
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Crop Planting';
		} else {
			$legend = 'Edit Crop Planting';
		}
		$entity = 'crop-planting';
		$c = new cropPlantingLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'cp_CropPlanting_Save.php',$contextMenu);
		//echo 'in cropplanting.editform, getting crop select list';
		$c = new Crop;
		$select = $c->getCropSelectList($this->cropId,'cropId');
		$fields = captionedInput('Crop',$select);
		//echo 'in cropplanting.editform, getting location select list';
		$l = new Location;
		$select = $l->getLocationSelectList($this->locationId,'locationId','false',false);
		$fields .= inputFieldSelect($entity,$select,'Location');


		$fields .= inputFieldNumber($entity,$this->plantedCount,'plantedCount','Planted Count');
		//end formRequired
		$formRequired = $fields;
		//formOptional
		$fields = inputFieldTimestamp($entity, 'planted', $this->planted, 'Date Planted'); 		
		
		$fields .= inputFieldNumber($entity,$this->perRowPlanted,'perRowPlanted','Per Row Planted');
		$fields .= inputFieldNumber($entity,$this->rowsPlanted,'rowsPlanted','Rows Planted');

		$fields .= inputFieldText($entity,$this->method,'method','Planting Method',50);

//		if ($this->pageMode != 'ADD'){
			$fields .= inputFieldTimestamp($entity, 'germinated', $this->germinated, 'Date Germinated'); 		
			$fields .= inputFieldNumber($entity,$this->germinatedCount,'germinatedCount','Germinated Count');
			$fields .= inputFieldNumber($entity,$this->thinnedCount,'thinnedCount','Thinned Count');
//		}

		$fields .= inputFieldComments($entity,$this->notes,'notes','Notes',1000);

		//end formOptional
		$formOptional = $fields;

				
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('cropPlantingId', $this->id);
		$hidden .= getHiddenInput('cropPlanId', $this->cropPlanId);
		
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){
		$this->pageMode = $_POST['mode'];	
		$this->id = $_POST['cropPlantingId'];
		$this->cropPlanId = $_POST['cropPlanId'];
		$this->cropId = $_POST['cropId'];
		$this->locationId = $_POST['locationId'];
		$this->method = $conn>escape_string($_POST['method']); 
		$this->notes = $conn>escape_string($_POST['notes']); 
		$this->planted = getTimestampPostValues('planted');
		
		$this->plantedCount = $_POST['plantedCount'];
		$this->rowsPlanted = $_POST['rowsPlanted'];
		$this->perRowPlanted = $_POST['perRowPlanted'];
		if ($this->pageMode == 'EDIT'){
		$this->germinated = getTimestampPostValues('germinated');		
		$this->germinatedCount = $_POST['germinatedCount'];
		$this->thinnedCount = $_POST['thinnedCount'];
		} else {
			$this->germinatedCount = 0;
			$this->thinnedCount = 0;
		}
	}

	public function saveChanges(){
		
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE crop_plantings AS c ";
			$sql .= " SET ";
			$sql .= " c.method = '".$this->method."', ";
			$sql .= " c.crop_id = ".$this->cropId.", ";
			$sql .= " c.location_id = ".$this->locationId.", ";
			$sql .= " c.planted_count = ".$this->plantedCount.", ";
			$sql .= " c.germinated = '".$this->germinated."', ";
			$sql .= " c.germinated_count = ".$this->germinatedCount.", ";
			$sql .= " c.planted = '".$this->planted."', ";
			$sql .= " c.thinned_count = ".$this->thinnedCount.", ";
			$sql .= " c.rows_planted =  ".$this->rowsPlanted.", ";
			$sql .= " c.per_row_planted = ".$this->perRowPlanted.", ";
			$sql .= " c.updated = CURRENT_TIMESTAMP, ";			
			$sql .= " c.notes = '".$this->notes."' ";
			$sql .= " WHERE c.id = ".$this->id."  ";			
			$result = mysql_query($sql) or die(mysql_error());
			
		} else {

			$sql = " INSERT INTO crop_plantings ";
			$sql .= " (crop_id, ";
			$sql .= " method, ";
			$sql .= " location_id, ";
			$sql .= " crop_plan_id, ";
			$sql .= " updated, ";
			$sql .= " planted, ";
			$sql .= " planted_count, ";
			$sql .= " rows_planted, ";
			$sql .= " per_row_planted, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= " ".$this->cropId.", ";			
			$sql .= " '".$this->method."', ";
			$sql .= " ".$this->locationId.", ";
			$sql .= " ".$this->cropPlanId.", ";			
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " '".$this->planted."', ";
			$sql .= " ".$this->plantedCount.", ";
			$sql .= " ".$this->rowsPlanted.", ";
			$sql .= " ".$this->perRowPlanted.", ";
			$sql .= " '".$this->notes."') ";
			$result = mysql_query($sql) or die(mysql_error());
			
			$this->id = mysql_insert_id();
		}
	
	}
	
} 
//end class CropPlanting
class CropPlantingSQL{
	public function createView_CropPlantings(){
		$s = "CREATE OR REPLACE VIEW crop_plantings_v AS
		SELECT
		cp.id,
		cp.crop_id,
		c.common_name,
		c.variety_name,
		cp.method,
		cp.crop_plan_id,
		cp.location_id,
		l.sort_key location_name,
		cp.planted,
		cp.germinated,
		cp.updated,
		cp.planted_count,
		c.days_germinate,
		cp.germinated_count,
		cp.thinned_count,
		c.days_mature,
		timestampadd(DAY, c.days_germinate, ifnull(cp.planted,pl.started)) estimated_germination,
		timestampadd(DAY, c.days_mature, 
			ifnull(cp.germinated,
			timestampadd(DAY, c.days_germinate, ifnull(cp.planted,pl.started)))) estimated_maturity,
		timestampadd(DAY, c.days_transplant, 
			ifnull(cp.germinated,
			timestampadd(DAY, c.days_germinate, ifnull(cp.planted,pl.started)))) estimated_transplant,
		cp.rows_planted,
		cp.per_row_planted,
		cp.notes
		from
		crop_plans pl
		join crop_plantings cp
		on pl.id = cp.crop_plan_id
		join crops c
		on cp.crop_id = c.id
		left outer join locations l
		on cp.location_id = l.id
		order by
		crop_plan_id, common_name, variety_name ";
		return $s;
	}
	
	public function createView_CropPlantingCalendar(){
		$s = " CREATE OR REPLACE VIEW crop_planting_calendar_v AS
			SELECT 
			'PLANTED' sort_method,
			cp.crop_id,
			cp.common_name,
			cp.variety_name,
			date(cp.planted) date_item,
			year(cp.planted) year_item,
			month(cp.planted) month_item,
			cp.id planting_id,
			cp.crop_plan_id
			FROM crop_plantings_v cp
			UNION ALL
			SELECT 
			'TRANSPLANTED' sort_method,
			cp.crop_id,
			cp.common_name,
			cp.variety_name,
			date(cp.estimated_transplant) date_item,
			year(cp.estimated_transplant) year_item,
			month(cp.estimated_transplant) month_item,
			cp.id planting_id,
			cp.crop_plan_id
			FROM crop_plantings_v cp
			UNION ALL
			SELECT 
			'MATURE' sort_method,
			cp.crop_id,
			cp.common_name,
			cp.variety_name,
			date(cp.estimated_maturity) date_item,
			year(cp.estimated_maturity) year_item,
			month(cp.estimated_maturity) month_item,
			cp.id planting_id,
			cp.crop_plan_id
			FROM crop_plantings_v cp ";
		return $s;
	}
	
	public function columnsCropPlantings(){
		$c = " cp.id, "; 
		$c .= " cp.crop_id, ";
		$c .= " cp.common_name, ";
		$c .= " cp.variety_name, "; 
		$c .= " cp.method, "; 
		$c .= " cp.crop_plan_id, ";
		$c .= " cp.location_id, ";
		$c .= " cp.location_name, ";
		$c .= " cp.planted, "; 
		$c .= " week(cp.planted) week_plant, ";
		$c .= " week(cp.estimated_transplant) week_transplant, ";
		$c .= " week(cp.estimated_maturity) week_mature, ";
		$c .= " cp.germinated, "; 
		$c .= " cp.days_germinate, ";
		$c .= " cp.days_mature, ";
		$c .= " cp.estimated_germination, ";
		$c .= " cp.estimated_transplant, ";
		$c .= " cp.estimated_maturity, ";
		$c .= " cp.updated, "; 
		$c .= " cp.planted_count, ";
		$c .= " cp.germinated_count, ";
		$c .= " cp.thinned_count, "; 
		$c .= " cp.rows_planted, "; 
		$c .= " cp.per_row_planted, "; 
		$c .= " cp.notes ";
		return $c;	
	}

	public function infoCropPlanting($cropPlantingId){
		$q = " SELECT ";	
		$q .= $this->columnsCropPlantings();
		$q .= " FROM crop_plantings_v cp ";
		$q .= " WHERE  ";
		$q .= " cp.id = ".$cropPlantingId." ";
		return $q;
	}

	public function listCropPlantings($cropPlanId,$sortMethod,$resultPage, $rowsPerPage){
		$q = " SELECT ";	
		$q .= $this->columnsCropPlantings();
		$q .= " FROM crop_plantings_v cp ";
		$q .= " WHERE  ";
		$q .= " cp.crop_plan_id = ".$cropPlanId." ";
		if ($sortMethod == 'CROP'){
			$q .= " ORDER BY common_name, variety_name ";
		} else if ($sortMethod == 'PLANTED'){
			$q .= " ORDER BY week_plant, common_name, variety_name ";
		} else if ($sortMethod == 'TRANSPLANTED'){
			$q .= " ORDER BY week_transplant, common_name, variety_name ";
		} else if ($sortMethod == 'MATURE'){
			$q .= " ORDER BY week_mature, common_name, variety_name ";
		}
		$q .= sqlLimitClause($resultPage, $rowsPerPage);
		
		//echo $q;
		
		return $q;	
	}

	public function listCropPlantingsPlanTypeUpdate($cropPlanId){
		$q = " SELECT ";
		$q .= " cp.id, ";
		$q .= " pl.started planted_by_start, ";
		$q .= " timestampadd(DAY, -1 * (c.days_mature + c.days_germinate), pl.mature) planted_by_mature, ";
		$q .= " timestampadd(DAY, -1 * (c.days_transplant + c.days_germinate), pl.transplanted) planted_by_transplant ";
		$q .= " from ";
		$q .= " crop_plans pl ";
		$q .= " join crop_plantings cp ";
		$q .= " on pl.id = cp.crop_plan_id ";
		$q .= " join crops c ";
		$q .= " on cp.crop_id = c.id ";
		$q .= " WHERE  ";
		$q .= " pl.id = ".$cropPlanId." ";
		return $q;		
	}

	public function countCropPlantings($cropPlanId){
		$q = " SELECT ";	
		$q .= " COUNT(*) total_plantings ";
		$q .= " FROM crop_plantings_v c ";
		$q .= " WHERE  ";
		$q .= " c.crop_plan_id = ".$cropPlanId." ";

		return $q;	
	}

	public function calendarDetailsPlantings($cropPlanId,$sortMethod, $year, $month){
		if ($sortMethod == 'CROP'){
			$sort = 'PLANTED';
		} else {
			$sort = $sortMethod;
		}
		$q = " SELECT "; 
		$q .= " cpc.common_name, ";
		$q .= " cpc.variety_name, ";
		$q .= " cpc.date_item, ";
		$q .= " cpc.year_item, ";
		$q .= " cpc.month_item, ";
		$q .= " cpc.planting_id, ";
		$q .= " cpc.crop_plan_id, ";
		$q .= " cpc.crop_id ";
		$q .= " FROM crop_planting_calendar_v cpc ";
 		$q .= " WHERE cpc.crop_plan_id = ".$cropPlanId." ";
 		$q .= " AND cpc.sort_method = '".$sort."' ";
 		$q .= " AND cpc.month_item = '".$month."' ";
 		$q .= " AND cpc.year_item = '".$year."' ";
		return $q;	
	}

	public function calendarLinksPlantings($cropPlanId, $sortMethod){
		if ($sortMethod == 'CROP'){
			$sort = 'PLANTED';
		} else {
			$sort = $sortMethod;
		}
		$q = " SELECT ";
		$q .= " cpc.year_item, ";
		$q .= " cpc.month_item ";
		$q .= " FROM crop_planting_calendar_v cpc ";
		$q .= " WHERE cpc.crop_plan_id = ".$cropPlanId." ";
		$q .= " AND cpc.sort_method = '".$sort."' ";
		$q .= " GROUP BY ";
		$q .= " cpc.year_item, ";
		$q .= " cpc.month_item ";
		$q .= " ORDER BY ";
		$q .= " year_item, ";
		$q .= " month_item ";
		return $q;
	}

	public function calendarRangePlantings($cropPlanId,$sortMethod){
		if ($sortMethod == 'CROP'){
			$sort = 'PLANTED'; 
		} else {
			$sort = $sortMethod;
		}
		$q = " SELECT "; 
		$q .= " cpc.crop_plan_id, ";
		$q .= " year(min(cpc.date_item)) year_item, ";
		$q .= " month(min(cpc.date_item)) month_item ";
		$q .= " FROM crop_planting_calendar_v cpc ";
		$q .= " WHERE cpc.crop_plan_id = ".$cropPlanId." ";
		$q .= " AND cpc.sort_method = '".$sort."' ";
		$q .= " GROUP BY ";
		$q .= " cpc.crop_plan_id ";
		return $q;
	}
}
//end class CropPlantingSQL
?>
