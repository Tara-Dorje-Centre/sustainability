<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class ProjectLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	
	public function listingAllProjects($showClosed = false){
//		$menu = $this->formatTextItem('AllProjects:');		
		$menu = $this->listingHref('OPEN','Projects');
		if ($showClosed == true){
			$menu .= $this->listingHref('CLOSED','ClosedProjects');	
		}
		return $menu;
	}
	
	public function listingMyProjects($showClosed = false){
		//$menu = $this->formatTextItem('MyProjects:');		
		$menu = $this->listingHref('OPEN','MyProjects','YES');
		if ($showClosed == true){
			$menu .= $this->listingHref('CLOSED','MyClosedProjects','YES');
		}
		return $menu;
	}
	public function listingHref($projectStatus = 'OPEN',$caption = 'Projects',$showMyProjects = 'NO'){
		$link = $this->listing($projectStatus,$showMyProjects);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $projectId = 0, $caption = 'Project'){
		$link = $this->detail($pageAction,$projectId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	public function listing($projectStatus = 'OPEN',$showMyProjects = 'NO'){
		$link = 'pr_Project_List.php'.'?projectStatus='.$projectStatus;
		if ($showMyProjects == 'YES'){
			$link .= '&showMyProjects=YES';
		}
		return $link;
	}
	public function listingPaged($status, $showMyProjects,$found, $resultPage, $perPage){
		$l = $this->listing($status,$showMyProjects).'&resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	public function detail($pageAction, $projectId = 0){
		$link = 'pr_Project_Detail.php?pageAction='.$pageAction;
		if ($projectId != 0){
			$link .= '&projectId='.$projectId;
		}
		return $link;
	}	
	public function detailAddHref($caption = '+Project'){
		$l = $this->detailHref('ADD',0,$caption);
		return $l;	
	}
	public function detailViewHref($projectId,$caption = 'ViewProject'){
		$l = $this->detailHref('VIEW',$projectId,$caption);
		return $l;	
	}
	public function detailEditHref($projectId,$caption = 'EditProject'){
		$l = $this->detailHref('EDIT',$projectId,$caption);
		return $l;	
	}
	public function detailCopyHref($projectId,$caption = 'CopyProject'){
		$l = $this->detailHref('COPY',$projectId,$caption);
		return $l;	
	}
	public function detailViewEditHref($projectId = 0, $viewCaption = 'Project'){
		$links = '';
		if ($projectId != 0){
			$links .= $this->detailViewHref($projectId,$viewCaption);
			$links .= $this->detailEditHref($projectId,'#');
		}
		return $links;
	}	
	
}
class ProjectList{
	public $showMyProjects = 'NO';
	public $status = 'OPEN';
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	private $sql;
	
	public function __construct(){
		$this->sql = new ProjectSQL;	
	}
	public function setDetails($projectStatus, $showMyProjects = 'NO', $resultPage = 1, $resultsPerPage = 10){
		$this->status = $projectStatus;	
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		$this->showMyProjects = $showMyProjects;
		
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		if ($this->showMyProjects == 'YES'){
			$title .= 'My ';
		}
		$title .= $this->status.' Projects';
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projectL = new ProjectLinks($menuType,$menuStyle);
		$activityL = new ActivityLinks($menuType,$menuStyle);

		$menu = $projectL->openMenu('section-heading-links');
		
		if ($this->status == 'CLOSED'){
			$menu .= $projectL->listingAllProjects(true);	
		} else {
			$menu .= $projectL->listingAllProjects();
		}
		$menu .= $projectL->resetMenu();		
		if ($this->status == 'CLOSED'){
			$menu .= $projectL->listingMyProjects(true);
		} else {
			$menu .= $projectL->listingMyProjects();	
		}
		$menu .= $projectL->resetMenu();
		$menu .= $projectL->detailAddHref();					
		$menu .= $projectL->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		if ($this->showMyProjects == 'YES'){
			$sql = $this->sql->countProjectsByDoneBy($_SESSION['login-name'],$this->status);
		} else {
			$sql = $this->sql->countProjectsByStatus($this->status);
		}
		$this->found = dbGetCount($sql, 'total_projects');
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
	
		$pl = new ProjectLinks;		
		$pagingLinks = $pl->listingPaged($this->status,$this->showMyProjects,$this->found,$this->resultPage,$this->perPage);

		$p = new Project;
		$p->setDetails(0,'ADD');
		$quickEdit = $p->editForm();
		$list = openDisplayList('project','Projects',$pagingLinks,$quickEdit);

		$heading = wrapTh('Location');
		$heading .= wrapTh('Priority');
		$heading .= wrapTh('%Done');
		$heading .= wrapTh('Project Name');
		$heading .= wrapTh('Description');
		$list .= wrapTr($heading);


		
		if ($this->showMyProjects == 'YES'){
			$user = $_SESSION['login-name'];
			$sql = $this->sql->listProjectsByDoneBy($user,$this->status,$this->resultPage,$this->perPage);
		} else {
			$sql = $this->sql->listProjectsByStatus($this->status,$this->resultPage,$this->perPage);
		}
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$p = new Project;
			$p->id = $row["id"];
			$p->priority = $row["priority"];
			$p->pctDone = $row["pct_done"];
			$p->name = $row["name"];
			$p->locationName = $row["location_name"];
			$p->description = $row["description"];
			$p->projectType = $row["project_type"];
			$p->highlightStyle = $row["highlight_style"];
			$p->formatForDisplay();

			$detail =  wrapTd($p->locationName);
			$detail .=  wrapTd($p->priority);
			$detail .=  wrapTd($p->pctDone);
			$link = $pl->detailViewEditHref($p->id,$p->name);
			$detail .= wrapTd($link);
			$detail .= wrapTd($p->description);
			$list .=  wrapTr($detail,$p->highlightStyle);
		}
		$result->close();
		}

		$list .= closeDisplayList();		
		return $list;
	}
}


class Project {
    public $id = 0;
    public $parentId = 0;
	public $locationId = 0;
    public $name;	
	public $projectType;
	public $highlightStyle = 'none';
    public $description;
    public $summary;
    public $started;
    public $updated;
    public $pctDone = 0;	
    public $priority = 1;	
    public $goals;
    public $lessons;	
    public $locationName;
	public $purpose;	
	public $showAlways = 'yes';
	public $typeId = 0;
	public $tasksCount = 0;
	public $tasksHoursEstimated = 0;
	public $tasksHoursActual = 0;
	public $tasksPctDone = 0;
	public $tasksSummary;
	public $materialsCount = 0;
	public $materialsCost = 0;
	public $materialsSummary = '';
	public $receiptsCount = 0;
	public $receiptsCost = 0;
	public $receiptsSummary = '';

	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	public $resultPage = 1;
	

	public function __construct(){
		$this->sql = new ProjectSQL;
	}

    // set class properties with record values from database
	public function setDetails($detailProjectId, $inputMode, $resultPage = 1){
		$this->pageMode = $inputMode;
		$this->resultPage = $resultPage;
		$this->id = $detailProjectId;
		
		if ($detailProjectId > 0){

		$sql = $this->sql->infoProject($this->id);
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$this->parentId = $row["parent_id"];
			$this->locationId = $row["location_id"];
			$this->name = ($row["name"]);
			$this->description = stripslashes($row["description"]);
			$this->summary = ($row["summary"]);
			$this->started = ($row["started"]);	
			$this->updated = ($row["updated"]);			
			$this->pctDone = $row["pct_done"];	
			$this->priority = $row["priority"];						
			$this->goals = ($row["goals"]);
			$this->lessons = ($row["lessons_learned"]);	
			$this->typeId = $row["type_id"];
			$this->locationName = ($row['location_name']);
			$this->purpose = ($row["purpose"]);		
			$this->showAlways = ($row["show_always"]);			
		}
		$result->close();
		}
		
		}
		
		
		$this->setTaskSummary();
		$this->setMaterialSummary();
		$this->setReceiptSummary();		
	}	
	
	
	private function setMaterialSummary(){
		$s = new MaterialSQL;
		$sql = $s->summarizeMaterialByProject($this->id, 'yes');

		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$this->materialsCount = $row["total_materials"];
			$this->materialsCost = $row["sum_cost_actual"];
		}
		$result->close();
		}

		$this->setMaterialSummaryByType();	
	}
	
	private function setMaterialSummaryByType(){

		$costs = openTable('cost-info','displayListTable');
		
		if ($this->materialsCount > 0){

			$item = wrapTh('Approved Cost');
			$item .= wrapTd($this->materialsCost);
			$costs .= wrapTr($item);
		} else {
			$costs .= wrapTr(wrapTh('No Materials Costs'));
		}
		$costs .= closeTable();
		$this->materialsSummary = $costs;
		
	}

	private function setReceiptSummary(){
		$s = new ReceiptSQL;
		$sql = $s->summarizeReceiptsByProject($this->id, 'yes');

		$result = dbGetResult($sql);

		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$this->receiptsCount = $row["total_receipts"];
			$this->receiptsCost = $row["sum_cost_actual"];
		}
		$result->close();
		}

		$this->setReceiptSummaryByType();	
	}
	
	private function setReceiptSummaryByType(){

		$costs = openTable('receipt-info','displayListTable');
		
		if ($this->receiptsCount > 0){
			$heading = wrapTh('Receipt Type');
			$heading .= wrapTh('Received');
			$costs .= wrapTr($heading);
		
			$s = new ReceiptSQL;
			$sql = $s->summarizeReceiptsByProjectAndType($this->id, 'yes');
			
			$result = dbGetResult($sql);
			if($result){
		  	while ($row = $result->fetch_assoc())
			{	
				$type = ($row["receipt_type"]);
				$costActual = $row["sum_cost_actual"];
				$item = wrapTd($type);
				$item .= wrapTd($costActual);
				$costs .= wrapTr($item);
			}
			$result->close();
			}
			
			$item = wrapTh('Totals');
			$item .= wrapTd($this->receiptsCost);
			$costs .= wrapTr($item);
		} else {
			$costs .= wrapTr(wrapTh('No Income Received'));
		}
		$costs .= closeTable();
		$this->receiptsSummary = $costs;
		
	}


	
	private function setTaskSummary(){
		$s = new TaskSQL;
		$sql = $s->infoTaskSummaryByProject($this->id);
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$this->tasksHoursEstimated = $row["sum_hours_estimated"];
			$this->tasksHoursActual = $row["sum_hours_actual"];	
			$this->tasksPctDone = $row["overall_pct_done"];			
			$this->tasksCount = $row["total_tasks"];	
		}
		$result->close();
		}

		$effort = openTable('hours-info','displayListTable');
		if ($this->tasksCount == '0'){
			$item = wrapTh('No Tasks Created');
			$effort .= wrapTr($item);
		} else {
			if ($this->tasksHoursActual == 0){
				$item = wrapTh('No Activity History');
				$effort .= wrapTr($item);				
			} else {
				$heading = wrapTh('Tasks');
				$heading .= wrapTh('Estimated Hours');
				$heading .= wrapTh('Actual Hours');
				$heading .= wrapTh('% Done');
				$effort .= wrapTr($heading);
				$row = wrapTd($this->tasksCount);
				$row .= wrapTd($this->tasksHoursEstimated);
				$row .= wrapTd($this->tasksHoursActual);
				$row .= wrapTd($this->tasksPctDone);
				$effort .= wrapTr($row);
			}
		}
		$effort .= closeTable();
		$this->tasksSummary = $effort;		
	}
	
	function pageTitle(){
		$heading = openDiv('section-heading-title');
		if ($this->pageMode != 'ADD'){
			$heading .= $this->name;
		} else {
			$heading .= 'Add New Project';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projectL = new ProjectLinks($menuType,$menuStyle);
		$taskL = new TaskLinks($menuType,$menuStyle);		
		$activityL = new ActivityLinks($menuType, $menuStyle);
		$materialL = new MaterialLinks($menuType, $menuStyle);
		$receiptL = new ReceiptLinks($menuType, $menuStyle);
		
		$menu = $projectL->openMenu('section-heading-links');
		$menu .= $projectL->listingHref();
		$menu .= $projectL->resetMenu();

		if ($this->pageMode == 'VIEW'){
			$menu .= $projectL->detailEditHref($this->id);
			$menu .= $projectL->detailCopyHref($this->id);

		} elseif ($this->pageMode == 'EDIT'){
			$menu .= $projectL->detailViewHref($this->id);
		}

		if ($this->pageMode != 'ADD'){	
			$menu .= $projectL->resetMenu();
			$menu .= $activityL->linkProjectCalendar($this->id);
			$menu .= $activityL->linkProjectActivities($this->id);		
			if ($this->materialsCount > 0){
				$menu .= $projectL->resetMenu();		
				$menu .= $materialL->listingHref(-1,'ProjectMaterials',$this->id,'PROJECT','yes');
			}				
			if ($this->receiptsCount > 0){
				$menu .= $projectL->resetMenu();		
				$menu .= $receiptL->listingHref(-1,'ProjectReceipts',$this->id,'PROJECT','yes');
			}				

			$menu .= $projectL->resetMenu();
			$menu .= $taskL->detailAddHref($this->id);
		}
		$menu .= $projectL->closeMenu();
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
		$this->summary = displayLines($this->summary);
		$this->goals = displayLines($this->goals);
		$this->lessons = displayLines($this->lessons);		
		$this->purpose = displayLines($this->purpose);		
		$this->started = getTimestampDate($this->started);
		$this->updated = getTimestampDate($this->updated);
		if ($this->highlightStyle == ''){
			$this->highlightStyle = 'none';
		}
	}

	public function printPage(){
		
		
		if ($this->pageMode == 'COPY'){
			$this->copyProject();
			$_SESSION['currentProjectId'] = $this->id;
		}
		
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
				
		$detail = openDisplayDetails('project','Project Details');		

		$detail .= captionedParagraph('name', 'Name', $this->name);

		$l = new ProjectType;
		$select = $l->getProjectTypeSelectList($this->typeId,'typeId','true');
		$detail .= captionedParagraph('project-type','Project Type',$select);

		$l = new Location;
		$select = $l->getLocationSelectList($this->locationId,'locationId','true');
		$detail .= captionedParagraph('location','Location',$select);
		$detail .= captionedParagraph('priority', 'Priority', $this->priority);
		$detail .= captionedParagraph('started', 'Started', $this->started);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);
		$detail .= captionedParagraph('pct-done', '% Done', $this->pctDone);
		$detail .= captionedParagraph('purpose', 'Purpose', $this->purpose);
		$detail .= captionedParagraph('desc', 'Description', $this->description);
		$detail .= captionedParagraph('summary', 'Summary', $this->summary);
		//$detail .= captionedParagraph('goals', 'Goals', $this->goals);
		//$detail .= captionedParagraph('lessons', 'Lessons Learned', $this->lessons);
		$effortAndCost = openTable('effort-and-cost','displayListTable');
		$row = wrapTd($this->tasksSummary,30);
		$row .= wrapTd($this->materialsSummary,30);
		$row .= wrapTd($this->receiptsSummary,30);

		$effortAndCost .= wrapTr($row);
		$effortAndCost .= closeTable();
		$detail .= captionedParagraph('effort-and-cost','Time, Materials and Receipts',$effortAndCost);			

		$tasks = new TaskList;
		$tasks->setDetails($this->id,$this->resultPage,10);
		$pl = new ProjectLinks();
		$link = $pl->detail('VIEW',$this->id);
		$detail .= $tasks->getListing($link);
		
		$detail .= closeDisplayDetails();
		return $detail;
	}
	
	public function getProjectSelectList(
		$selectedValue = '0', 
		$idName = 'projectId', 
		$disabled = 'false',
		$showLink = true,
		$onChangeJS = NULL){
	
		$sql = $this->sql->selectOptions_Projects($selectedValue, $disabled);	
		$defaultValue = 0;
		$defaultCaption = '-select Project';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		$select = getSelectList($idName,$allOptions,'none',$disabled,$onChangeJS);
		if ($showLink === true){
			$l = new ProjectLinks;	
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}	
	
	public function getProjectSelectListByTypeId(
		$projectTypeId = '0',
		$selectedValue = '0', 
		$idName = 'projectId', 
		$disabled = 'false',
		$showLink = true,
		$onChangeJS = NULL){
	
		$sql = $this->sql->selectOptions_ProjectsByTypeId($projectTypeId);
		$defaultValue = 0;
		$defaultCaption = '-select Project';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);			
		$select = getSelectList($idName,$allOptions,'none',$disabled,$onChangeJS);
		if ($showLink === true){
			$l = new ProjectLinks;	
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}	
	
	private function setAddRecordDefaults(){	
		global $sessionTime;
		$this->started = $sessionTime;	
		$this->locationId = 0;
		$this->typeId = 0;
		$this->priority = 1;
		$this->pctDone = 0;
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Project';
		} else {
			$legend = 'Edit Project';	
		}
		$entity = 'project';
		$c = new ProjectLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'pr_Project_Save.php', $contextMenu);
		
		
		//start required fields
		$fields = inputFieldName($entity,$this->name,'name','Project');
		
		
		$pt = new ProjectType;
		$select = $pt->getProjectTypeSelectList($this->typeId,'projectTypeId','false',false);
		$fields .= captionedInput('Project Type', $select);
		


		//end required fields
		$formRequired = $fields;

		//start optional fields
		$fields = inputFieldDescription($entity,$this->description,'description');

		$l = new Location;
		$select = $l->getLocationSelectList($this->locationId,'locationId','false',false);
		$fields .= captionedInput('Location', $select);


		$fields .= inputFieldTimestamp($entity, 'started', $this->started, 'Start Date'); 		

		$fields .= inputFieldNumber($entity,$this->priority,'priority','Priority');

		$fields .= inputFieldNumber($entity,$this->pctDone,'pctDone','% Done');

						
		$input = getSelectYesNo('showAlways', $this->showAlways);
		$fields .= captionedInput('Show in calendar', $input);


		$fields .= inputFieldDescription($entity,$this->purpose,'purpose','Purpose');
					
		$fields .= inputFieldComments($entity,$this->summary,'summary','Summary');
				
		
		
		//$input = getTextArea('projectGoals', $this->goals, 1000);
		//$form .= formInputRow($input, 'Goals');
		
		//$input = getTextArea('projectLessonsLearned', $this->lessons, 1000);
		//$form .= formInputRow($input, 'Lessons Learned');
		
		//end optional fields (hidden by default)
		$formOptional = $fields;
		
		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('parentId', $this->parentId);
		$hidden .= getHiddenInput('projectId', $this->id);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;

		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);		
		return $form;
	}
	
	public function collectPostValues(){
		$this->id = $_POST['projectId'];
		$this->parentId = $_POST['parentId'];
		$this->typeId = $_POST['projectTypeId'];
		$this->locationId = $_POST['locationId'];		
		$this->name = dbEscapeString($_POST['name']); 
		$this->description = dbEscapeString($_POST['description']); 
		//$this->goals = dbEscapeString($_POST['projectGoals']); 
		$this->summary = dbEscapeString($_POST['summary']); 
		//$this->lessons = dbEscapeString($_POST['projectLessonsLearned']); 
		$this->started = getTimestampPostValues('started');
		$this->priority = $_POST['priority']; 
		$this->pctDone = $_POST['pctDone']; 
		$this->purpose = dbEscapeString($_POST['purpose']); 
		$this->showAlways = $_POST['showAlways']; 
		
		$this->pageMode = $_POST['mode'];	
	}

    public function UpdateTaskSummary(){
		
		//refresh current project task summary values
		$this->setTaskSummary();
		if ($this->tasksCount > 0){
			$sql = " update projects p ";
			$sql .= " set p.pct_done = ".$this->tasksPctDone.", ";
			$sql .= " p.updated = CURRENT_TIMESTAMP, ";
			$sql .= " p.hours_estimated = ".$this->tasksHoursEstimated.",  ";
			$sql .= " p.hours_actual = ".$this->tasksHoursActual." ";
			$sql .= " where p.id = ".$this->id." ";
			
			$result = dbRunSQL($sql);
	
		}
	}


	private function copyProject(){
		$sourceProjectId = $this->id;
		$copyProjectId = 0;


		if ($this->pageMode == 'COPY'){
		
			$this->name = 'COPY OF '.substr($this->name,0,90);

			$sql = " INSERT INTO projects ";
			$sql .= " (name, ";
			$sql .= " parent_id, ";
			$sql .= " location_id, ";
			$sql .= " started, ";
			$sql .= " updated, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			//$sql .= " lessons_learned, ";
			$sql .= " show_always, ";
			$sql .= " type_id, ";
			$sql .= " purpose) ";
			$sql .= " SELECT ";
			$sql .= " '".$this->name."', ";
			$sql .= " id, ";
			$sql .= " location_id, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			//$sql .= " lessons_learned, ";
			$sql .= " show_always, ";
			$sql .= " type_id, ";
			$sql .= " purpose ";
			$sql .= " FROM projects p WHERE p.id = ".$sourceProjectId." ";
			
			$result = dbRunSQL($sql);
			
			$copyProjectId = dbInsertedId();
		
			//insert all tasks from source project to copy project substituting the copy project id		
			$sql = " INSERT INTO tasks ";
			$sql .= " (name, ";
			$sql .= " project_id, ";
			$sql .= " location_id, ";
			$sql .= " type_id, ";			
			$sql .= " task_order, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			$sql .= " started, ";
			$sql .= " updated, ";
			$sql .= " hours_estimated, ";
			$sql .= " hours_actual, ";
			$sql .= " hours_notes, ";
			$sql .= " materials_auth_project, ";
			$sql .= " materials_auth_by, ";
			$sql .= " receipts_auth_project, ";
			$sql .= " receipts_auth_by) ";
			$sql .= " SELECT ";
			$sql .= " name, ";
			$sql .= " ".$copyProjectId.", ";
			$sql .= " location_id, ";
			$sql .= " type_id, ";			
			$sql .= " task_order, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " hours_estimated, ";
			$sql .= " 0, ";
			$sql .= " hours_notes, ";
			$sql .= " 'no', ";
			$sql .= " 'n/a copied project', ";
			$sql .= " 'no', ";
			$sql .= " 'n/a copied project' ";
			$sql .= " FROM tasks t WHERE t.project_id = ".$sourceProjectId." ";

			$result = dbRunSQL($sql);
		
			//reset page mode from COPY to EDIT and set details using the copied id
			$this->id = $copyProjectId;
			$this->setDetails($this->id,'EDIT');
						
		}

		
	}


	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE projects AS p ";
			$sql .= " SET ";
			$sql .= " p.name = '".$this->name."', ";
			$sql .= " p.location_id = ".$this->locationId.", ";
			$sql .= " p.priority = ".$this->priority.", ";
			$sql .= " p.pct_done = ".$this->pctDone.", ";
			$sql .= " p.started = '".$this->started."', ";			
			$sql .= " p.updated = CURRENT_TIMESTAMP, ";
			$sql .= " p.description = '".$this->description."', ";
			$sql .= " p.show_always = '".$this->showAlways."', ";
			$sql .= " p.type_id = ".$this->typeId.", ";
			$sql .= " p.purpose = '".$this->purpose."', ";
			//$sql .= "`p`.`lessons_learned = '".$this->lessons."', ";
			$sql .= " p.summary = '".$this->summary."' ";
			$sql .= " WHERE p.id = ".$this->id."  ";			
			
			$result = dbRunSQL($sql);
			
		} else {
	
			$sql = " INSERT INTO projects ";
			$sql .= " (name, ";
			//$sql .= " parent_id, ";
			$sql .= " location_id, ";
			$sql .= " started, ";
			$sql .= " updated, ";
			$sql .= " description, ";
			$sql .= " summary, ";
			//$sql .= " lessons_learned, ";
			$sql .= " show_always, ";
			$sql .= " type_id, ";
			$sql .= " purpose) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			//$sql .= " ".$this->parentId.", ";
			$sql .= " ".$this->locationId.", ";
			$sql .= " '".$this->started."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= "'".$this->description."', ";
			$sql .= "'".$this->summary."', ";
			//$sql .= "'".$this->lessons."', ";
			$sql .= "'".$this->showAlways."', ";
			$sql .= " ".$this->typeId.", ";
			$sql .= "'".$this->purpose."') ";
			
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertedId();
		}
	
	}
	
} 
class ProjectSQL{
//projects table fields displayed in listing
function columnsProjects(){
$cols = " p.id, ";
$cols .= " p.type_id, ";
$cols .= " pt.name project_type, ";
$cols .= " pt.highlight_style, ";
$cols .= " p.parent_id, ";
$cols .= " p.name, ";
$cols .= " p.description, ";
$cols .= " p.summary, ";
$cols .= " p.priority, ";
$cols .= " p.started, ";
$cols .= " p.updated, ";
$cols .= " p.pct_done, ";
$cols .= " p.goals, ";
$cols .= " p.lessons_learned, ";
$cols .= " p.show_always, ";
$cols .= " p.location_id, ";
$cols .= " l.sort_key location_name, ";
$cols .= " p.purpose, ";
$cols .= " p.hours_estimated, ";
$cols .= " p.hours_actual, ";
$cols .= " p.budget_estimated, ";
$cols .= " p.budget_notes, ";
$cols .= " p.hours_notes ";
return $cols;
}
//projects table fields displayed in detail
public function listProjectsByStatus($projectStatus, $resultPage, $rowsPerPage){
$q = " SELECT  ";
$q .= $this->columnsProjects();
$q .= " FROM projects p ";
$q .= " LEFT OUTER JOIN locations l ";
$q .= " ON p.location_id = l.id ";
$q .= " LEFT OUTER JOIN project_types pt ";
$q .= " ON p.type_id = pt.id ";
if ($projectStatus=='CLOSED'){
	$q .= " WHERE p.pct_done = '1.00' "; 
} else {
	$q .= " WHERE p.pct_done != '1.00' "; 
}
$q .= " ORDER BY l.sort_key, p.priority, p.id ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}
public function listProjectsByDoneBy($activityDoneBy, $projectStatus, $resultPage, $rowsPerPage){
$q = " SELECT  ";
$q .= $this->columnsProjects();
$q .= " FROM projects p ";
$q .= " LEFT OUTER JOIN locations l ";
$q .= " ON p.location_id = l.id ";
$q .= " LEFT OUTER JOIN project_types pt ";
$q .= " ON p.type_id = pt.id ";

if ($projectStatus=='CLOSED'){
	$q .= " WHERE p.pct_done = '1.00' "; 
} else {
	
	$q .= " WHERE p.pct_done != '1.00' "; 
}
$q .= " AND p.id IN ";
$q .= " (select t.project_id from ";
$q .= " activities a JOIN tasks t ON a.task_id = t.id ";
$q .= " WHERE UPPER(a.done_by) = UPPER('".$activityDoneBy."') ) ";
$q .= " ORDER BY l.sort_key, p.priority, p.id ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}
public function countProjectsByDoneBy($activityDoneBy, $projectStatus){
$q = " SELECT  count(*) total_projects ";
$q .= " FROM projects p ";
if ($projectStatus=='CLOSED'){
	$q .= " WHERE p.pct_done = '1.00' "; 
} else {
	
	$q .= " WHERE p.pct_done != '1.00' "; 
}
$q .= " AND p.id IN ";
$q .= " (select t.project_id from ";
$q .= " activities a JOIN tasks t ON a.task_id = t.id ";
$q .= " WHERE UPPER(a.done_by) = UPPER('".$activityDoneBy."') ) ";
return $q;	
}

public function countProjectsByStatus($projectStatus){
$q = " SELECT  ";
$q .= " COUNT(*) total_projects ";
$q .= " FROM projects AS p ";
if ($projectStatus=='CLOSED'){
	$q .= " WHERE p.pct_done = '1.00' "; 
} else {
	$q .= " WHERE p.pct_done != '1.00' "; 
}
return $q;
}
function listChildProjects($selectedProjectId){
$q = " SELECT  ";
$q .= $this->columnsProjects();
$q .= " FROM projects p ";
$q .= " LEFT OUTER JOIN locations l ";
$q .= " ON p.location_id = l.id ";
$q .= " LEFT OUTER JOIN project_types pt ";
$q .= " ON p.type_id = pt.id ";

$q .= " WHERE p.parent_id = ".$selectedProjectId." "; 
$q .= " ORDER BY p.sort_key, p.priority, p.id ";
return $q;
}
public function infoProject($selectedProjectId){
$q = " SELECT  ";
$q .= $this->columnsProjects();
$q .= " FROM projects p ";
$q .= " LEFT OUTER JOIN locations l ";
$q .= " ON p.location_id = l.id ";
$q .= " LEFT OUTER JOIN project_types pt ";
$q .= " ON p.type_id = pt.id ";

$q .= " WHERE p.id = ".$selectedProjectId." "; 
return $q;
}
public function createViewProjectStatus(){
$q = "CREATE OR REPLACE VIEW project_status_v AS 
SELECT 'OPEN' AS status,
count(distinct p.id) AS projects,
count(*) AS project_tasks,
sum(t.hours_estimated) AS task_hours_estimated,
sum(t.hours_actual) AS task_hours_actual
FROM (projects p join tasks t 
on((p.id = t.project_id))) 
where (p.pct_done < 1) 
union 
select 'DONE' AS status,
count(distinct p.id) AS projects,
count(0) AS project_tasks,
sum(t.hours_estimated) AS task_hours_estimated,
sum(t.hours_actual) AS task_hours_actual 
from (projects p join tasks t 
on((p.id = t.project_id))) where (p.pct_done = 1) ";

return $q;
	
}
public function createViewProjectSummary(){
$q = "CREATE OR REPLACE VIEW project_task_summary_v AS 
select p.id AS project_id,
min(p.pct_done) AS project_pct_done,
count(*) AS total_tasks,
sum(t.hours_estimated) AS sum_hours_estimated,
sum(t.hours_actual) AS sum_hours_actual,
(sum(t.pct_done) / count(0)) AS overall_pct_done 
from (projects p join tasks t on((p.id = t.project_id))) 
group by p.id ";
return $q;	
	
}
//project summary base query
//SELECT 
//p.id, p.name, count(t.id) tasks, 
//sum(ifnull(t.hours_actual,0)) hours_actual,
//sum(ifnull(t.hours_estimated,0)) hours_estimated,
//count(a.id) activity_details,
//sum(ifnull(a.hours_actual,0)) activity_hours_actual,
//count(m.id) measure_details
// FROM projects p left outer join tasks t on p.id = t.project_id
//left outer join activities a on t.id = a.task_id`1
//left outer join measures m on t.id = m.task_id
//group by p.id, p.name

public function selectOptions_Projects($selectedValue,$disabled){
	$q = " SELECT ";
	$q .= " p.id as value, ";
	$q .= " p.name as caption ";
	$q .= " FROM projects p ";
	$q .= " WHERE p.pct_done < 1 ";
	if ($disabled == 'true'){
		$q .= " AND p.id = ".$selectedValue." ";	
	}
	$q .= " ORDER BY name ";
	return $q;	
}
public function selectOptions_ProjectsByTypeId($typeId){
	$q = " SELECT ";
	$q .= " p.id as value, ";
	$q .= " p.name as caption ";
	$q .= " FROM projects p ";
	$q .= " WHERE p.pct_done < 1 ";
	$q .= " AND (p.type_id > 0 AND p.type_id = ".$typeId.") ";
//	if ($disabled == 'true'){
//		$q .= " AND p.id = ".$selectedValue." ";	
//	}
	$q .= " ORDER BY name ";
	return $q;	
}


}
?>
