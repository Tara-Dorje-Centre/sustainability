<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class TaskLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($projectId,$caption = 'ProjectTasks',$periodicTasks = 'NO'){
		$link = $this->listing($projectId,$periodicTasks);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	private function detailHref($pageAction = 'VIEW', $taskId = 0, $projectId = 0, $caption = 'Task'){
		$link = $this->detail($pageAction,$taskId,$projectId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	public function listing($projectId,$periodicTasks = 'NO'){
		$link = 'pr_Task_List.php'.'?projectId='.$projectId;
		if ($periodicTasks != 'NO'){
			$link .= '&periodicTasks='.$periodicTasks;
		}
		return $link;
	}
	public function listingPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&resultsPage=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	public function detail($pageAction, $taskId, $projectId = 0){
		$link = 'pr_Task_Detail.php?pageAction='.$pageAction;
		if ($projectId != 0){
			$link .= '&projectId='.$projectId;
		} 
		if ($taskId != 0){
			$link .= '&taskId='.$taskId;
		}
		return $link;
	}
	public function detailAddHref($projectId,$caption = '+Task'){
		$l = $this->detailHref('ADD',0,$projectId,$caption);
		return $l;	
	}
	public function detailViewHref($taskId,$caption = 'ViewTask'){
		$l = $this->detailHref('VIEW',$taskId,0,$caption);
		return $l;	
	}
	public function detailEditHref($taskId,$caption = 'EditTask'){
		$l = $this->detailHref('EDIT',$taskId,0,$caption);
		return $l;	
	}
	public function detailCopyHref($taskId,$caption = 'CopyTask'){
		$l = $this->detailHref('COPY',$taskId,0,$caption);
		return $l;	
	}

	public function detailViewEditHref($taskId = 0, $viewCaption = 'Task'){
		$links = '';
		if ($taskId != 0){
			$links .= $this->detailViewHref($taskId,$viewCaption);
			$links .= $this->detailEditHref($taskId,'#'); 
			//$links .= $this->detailCopyHref($taskId);
		}
		return $links;
	}	
	public function linkPeriodicTasksOpen(){
		return $this->listingHref(-1,'To Do','INCOMPLETE');
	}
	public function linkPeriodicTasksClosed(){
		return $this->listingHref(-1,'Done','COMPLETE');		
	}
	public function linksPeriodicTasks($showClosed = false){
//		$menu = $this->formatTextItem('PeriodicTasks:');
		$menu = $this->linkPeriodicTasksOpen();
		if ($showClosed == true){
			$menu .= $this->linkPeriodicTasksClosed();
		}
		return $menu;	
	}
}
class TaskList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $periodicTasks = 'NO';
	public $project;
	private $sql;
	
	public function __construct(){
		$this->project = new Project;
		$this->sql = new TaskSQL;	
	}
	public function setDetails($projectId, $resultPage = 1, $resultsPerPage = 10, $periodicTasks = 'NO'){
		$this->project->id = $projectId;	
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		$this->periodicTasks = $periodicTasks;
		
		$this->project->setDetails($this->project->id, 'VIEW');
		$this->setFoundCount();
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		if ($this->periodicTasks == 'NO'){
			$title .= $this->project->name.br();
			$title .= 'Project Tasks';
		} else {
			//$title .= $this->periodicTasks.spacer().' Periodic Tasks';
		}
		$title .= closeDiv();
		return $title;	
	}
	
	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projectL = new ProjectLinks($menuType,$menuStyle);
		$taskL = new TaskLinks($menuType,$menuStyle);
		
		$menu = $taskL->openMenu('section-heading-links');
		if ($this->periodicTasks == 'NO'){
			$menu .= $taskL->detailAddHref($this->project->id);
			$menu .= $taskL->listingHref($this->project->id);
			$menu .= $projectL->detailViewHref($this->project->id);	
		} else {
			
			//$menu .= $taskL->linksPeriodicTasks();
			//$menu .= $taskL->formatOptionsLink();
			//$menu .= $projectL->listingAllProjects();
							
		}
		$menu .= $taskL->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	private function getPeriodicTasksComplete(){
		if ($this->periodicTasks == 'COMPLETE'){
			$complete = 'YES';
		} elseif ($this->periodicTasks == 'INCOMPLETE'){
			$complete = 'NO';
		}
		return $complete;		
	}
	private function setFoundCount(){
		if ($this->periodicTasks == 'NO'){
			$this->found = $this->project->tasksCount;
		} else {
			$sql = $this->sql->countPeriodicTasks($this->getPeriodicTasksComplete());
			$this->found = dbGetCount($sql, 'total_tasks');			
		}
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

		$taskL = new TaskLinks;	
		$projectL = new ProjectLinks;					
		if ($pagingBaseLink == 'USE_LISTING'){
			$base = $taskL->listing($this->project->id,$this->periodicTasks);
		} else { 
			$base = $pagingBaseLink;
		 }
		$pagingLinks = $taskL->listingPaged($base,$this->found,$this->resultPage,$this->perPage);		
		

			$a = new Activity;
			$a->setDetails(0,0,'ADD');
			$a->task->project->setDetails($this->project->id,'VIEW');
			$quickEdit = $a->editForm();

				
		$list = openDisplayList('task','Tasks',$pagingLinks,$quickEdit);

		$heading =  wrapTh('Order');
		$heading .=  wrapTh('Task Name');
		$heading .=  wrapTh('Task Type');		
		$heading .=  wrapTh('Description');
		$heading .=  wrapTh('% Done');
		$heading .=  wrapTh('Hours<br/>Estimated');
		$heading .=  wrapTh('Hours<br/>Actual');
		$heading .= wrapTh('Hotlinks');
		if ($this->periodicTasks != 'NO'){
			$heading .= wrapTh('Project');			
		}
		$list .=  wrapTr($heading);

		$activitiesL = new ActivityLinks('DIV','button');
		$materialsL = new MaterialLinks('DIV','button');
		$measuresL = new MeasureLinks('DIV','button');
		$receiptsL = new ReceiptLinks('DIV','button');

		if ($this->periodicTasks == 'NO'){
			$sql = $this->sql->listTasksByProject($this->project->id,$this->resultPage,$this->perPage);
		} else {
			$sql = $this->sql->listPeriodicTasks($this->getPeriodicTasksComplete(),$this->resultPage,$this->perPage);	
		}
		//$result = mysql_query($sql) or die("Couldn't get task list ($sql)");
		
		$result = dbGetResult($sql);
		if ($result){

		while($row = $result->fetch_assoc())
		{	
			$t = new Task;		
			$t->id = $row["id"];
			$t->order = $row["task_order"];
			$t->pctDone = $row["pct_done"];
			$t->name = stripslashes($row["name"]);
			$taskType = stripslashes($row["task_type"]);
			$t->description = stripslashes($row["description"]);
//			$t->summary = stripslashes($row["summary"]);
			$t->hoursNotes = stripslashes($row["hours_notes"]);
			$t->hoursEstimated = $row["hours_estimated"];
			$t->hoursActual = $row["hours_actual"];

			if ($this->periodicTasks != 'NO'){
				$t->project->id = $row["project_id"];
				$t->project->name = $row["project_name"];
				$cssItem = $row["project_highlight_style"];
			} else {
				//highlight based on reported activity
				if ($t->hoursActual == 0){
					$cssItem = 'highlight-yellow';
				} else {
					$cssItem = 'none';
				}
			}


			$t->formatForDisplay();
			//for each row, format the table cells

			$detail =  wrapTd($t->order,0);
			$link = $taskL->detailViewEditHref($t->id,$t->name);
			$detail .=  wrapTd($link,0);
			
			$detail .= wrapTd($taskType);
			$detail .=  wrapTd($t->description,0);
			$detail .=  wrapTd($t->pctDone,0);
			$detail .=  wrapTd($t->hoursEstimated,0);
			$detail .=  wrapTd($t->hoursActual,0);

			$menu = $activitiesL->openMenu('hotlinks-list');
			$menu .= $activitiesL->detailAddHref($t->id);
			$menu .= $measuresL->detailAddHref($t->id);
			$menu .= $materialsL->detailAddHref($t->id);
			$menu .= $receiptsL->detailAddHref($t->id);
			$menu .= $activitiesL->closemenu();
			$detail .=  wrapTd($menu);

			if ($this->periodicTasks != 'NO'){
				$link = $projectL->detailViewHref($t->project->id,$t->project->name);
				$detail .= wrapTd($link);
			}
			$list .=  wrapTr($detail,$cssItem);
		}
		
		$result->close();
		}

		$list .= closeDisplayList();
		return $list;
	}
}

class Task {
	public $locationId = 0;
	public $typeId = 0;
	public $highlightStyle = 'none';
    public $id = 0;
	public $order = 0;
    public $name;	
    public $description;
    public $summary;
    public $started;
	public $updated;
    public $pctDone = 0;	
    public $hoursEstimated = 0;
    public $hoursActual = 0;
    public $hoursNotes;	
	public $materialsAuthProject = 'no';
	public $materialsAuthBy = '';
	public $receiptsAuthProject = 'no';
	public $receiptsAuthBy = '';
	public $project;
	private $sql;
    public $pageMode;
	
	public $measureCount = 0;
	public $activityCount = 0;
	public $materialCount = 0;
	public $receiptCount = 0;
	public $costEstimated = 0;
	public $costActual = 0;	
	public $receiptCostActual = 0;
	public $resultsPageActivity = 1;
	public $resultsPageMeasure = 1;
	public $resultsPageMaterial = 1;
	public $resultsPageReceipt = 1;
	public $year = -1;
	public $month = -1;
	
	
	public function __construct(){
		$this->project = new Project;
		$this->sql = new TaskSQL;
	}
	
	public function setDetails($detailTaskId, $detailProjectId, $inputMode,$year = -1, $month = -1){
		$this->pageMode = $inputMode;
		$this->id = $detailTaskId;
		$this->project->id = $detailProjectId;
		$this->year = $year;
		$this->month = $month;
		
		$sql = $this->sql->infoTask($this->id);
		$result = dbGetResult($sql);
		//$result = mysql_query($sql) or die(mysql_error());
		if ($result){
		
		while($row = $result->fetch_assoc())
		{	
			$this->project->id = $row["project_id"];
			$this->locationId = $row["location_id"];
			$this->typeId = $row["type_id"];
			$this->name = ($row["name"]);
			$this->description = ($row["description"]);
			$this->summary = ($row["summary"]);
			$this->started = $row["started"];	
			$this->updated = $row["updated"];
			$this->pctDone = $row["pct_done"];	
			$this->order = $row["task_order"];						
			$this->hoursEstimated = $row["hours_estimated"];
			$this->hoursActual = $row["hours_actual"];
			$this->hoursNotes = ($row["hours_notes"]);			
			$this->materialsAuthProject = $row["materials_auth_project"];
			$this->materialsAuthBy = ($row["materials_auth_by"]);
			$this->receiptsAuthProject = $row["receipts_auth_project"];
			$this->receiptsAuthBy = ($row["receipts_auth_by"]);

		}
		
		$result->close();
		}
		
		$this->setParentProject();
		$this->setMeasureCount();
		$this->summarizeActivity();
		$this->summarizeMaterial();
		$this->summarizeReceipt();
	}	
	
	public function setPagingState(
		$pageActivity = 1, 
		$pageMaterial = 1, 
		$pageMeasure = 1, 
		$pageReceipt = 1){

		$this->resultsPageMeasure = $pageMeasure;
		$this->resultsPageActivity = $pageActivity;
		$this->resultsPageMaterial = $pageMaterial;
		$this->resultsPageReceipt = $pageReceipt;
	
	}

	function setParentProject(){
		$this->project->setDetails($this->project->id, $this->pageMode);		
	}
	
	function setMeasureCount(){
		$s = new MeasureSQL;
		$sql = $s->countMeasuresByTask($this->id);
		$this->measureCount = dbGetCount($sql, 'total_measures', 0);
	}
	
	function summarizeActivity(){
		$s = new ActivitySQL;
		$sql = $s->summarizeActivityByTask($this->id);
		
		$result = dbGetResult($sql);
		if ($result){
		while ($row = $result->fetch_assoc())
		{
			$this->activityCount = $row["total_activities"];	
			$this->hoursActual = $row["total_hours_actual"];
		}
		$result->close();
		}
		
		//if activity records with hours exist
		//update estimated effort to reflect current average of actual hours
		if ($this->activityCount > 0 && $this->hoursActual > 0){
			$this->hoursEstimated = round($this->hoursActual / $this->activityCount,2);
		}
	}

	function summarizeMaterial(){		
		$s = new MaterialSQL;
		$sql = $s->summarizeMaterialByTask($this->id);
		
		$result = dbGetResult($sql);
		if ($result){
		while ($row = $result->fetch_assoc())
		{
			$this->materialCount = $row["total_materials"];	
			$this->costActual = $row["sum_cost_actual"];
			$this->costEstimated = $row["sum_cost_estimated"];
		}
		$result->close();
		}
	}

	function summarizeReceipt(){		
		$s = new ReceiptSQL;
		$sql = $s->summarizeReceiptsByTask($this->id);
		
		$result = dbGetResult($sql);
		if ($result){
		while ($row = $result->fetch_assoc())
		{
			$this->receiptCount = $row["total_receipts"];	
			$this->receiptCostActual = $row["sum_cost_actual"];
		}
		$result->close();
		}
	}

	
	function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= $this->project->name.br();
		if ($this->pageMode != 'ADD'){
			$title .= 'Task: '.$this->name;
		} else {
			$title .= 'Add New Task.';
		}
		$title .= closeDiv();	
		return $title;	
	}
	
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projects = new ProjectLinks($menuType,$menuStyle);
		$tasks = new TaskLinks($menuType,$menuStyle);
		$measures = new MeasureLinks($menuType,$menuStyle);
		$materials = new MaterialLinks($menuType,$menuStyle);
		$activities = new ActivityLinks($menuType,$menuStyle);	
		$receipts = new ReceiptLinks($menuType,$menuStyle);
		
		$menu = $projects->openMenu('section-heading-links');
		$menu .= $projects->detailViewHref($this->project->id);
		$menu .= $projects->resetMenu();

		if ($this->project->materialsCount > 0){
			$menu .= $materials->listingHref($this->id,'ProjectMaterials',$this->project->id,'PROJECT','yes');
			$menu .= $projects->resetMenu();
		}
		if ($this->project->receiptsCount > 0){
			$menu .= $receipts->listingHref($this->id,'ProjectReceipts',$this->project->id,'PROJECT','yes');
			$menu .= $projects->resetMenu();
		}
		
		if ($this->pageMode == 'VIEW'){
			$menu .= $tasks->detailEditHref($this->id);
			$menu .= $tasks->detailCopyHref($this->id);
		} elseif ($this->pageMode == 'EDIT') {
			//$menu .= $tasks->formatOptionsLink();
			$menu .= $tasks->detailViewHref($this->id);
		}		
		$menu .= $projects->resetMenu();

		if ($this->materialCount > 0){
			$menu .= $materials->listingHref($this->id,'TaskMaterials',0,'TASK');
			$menu .= $projects->resetMenu();
		}
		if ($this->receiptCount > 0){
			$menu .= $receipts->listingHref($this->id,'TaskReceipts',0,'TASK');
			$menu .= $projects->resetMenu();
		}

		if ($this->pageMode != 'ADD'){
			$menuStyle = 'button';
			$projects->setStyle($menuStyle);
			$activities->setStyle($menuStyle);
			$measures->setStyle($menuStyle);
			$materials->setStyle($menuStyle); 
			$receipts->setStyle($menuStyle);
			$menu .= $projects->resetMenu();
			$menu .= $activities->detailAddHref($this->id);
			$menu .= $measures->detailAddHref($this->id);
			$menu .= $materials->detailAddHref($this->id);
			$menu .= $receipts->detailAddHref($this->id);
		}
				
		$menu .= $projects->closeMenu();
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
		$this->hoursNotes = displayLines($this->hoursNotes);
		
		$this->started = getTimestampDate($this->started);
		$this->updated = getTimestampDate($this->updated);
		
	}
		
	public function printPage(){
		
		
		if ($this->pageMode == 'COPY'){
			$this->copyTask();
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

		$detail = openDisplayDetails('task','Task Details');

		$detail .= captionedParagraph('order', 'Order', $this->order);
		$detail .= captionedParagraph('name', 'Name', $this->name);
		
		$l = new Location;
		$select = $l->getLocationSelectList($this->locationId,'locationId','true');
		$detail .= captionedParagraph('location','Location',$select);

		$tt = new TaskType;
		$select = $tt->getTaskTypeSelectList($this->typeId,'typeId','true');
		$detail .= captionedParagraph('type','Task Type',$select);

		$detail .= captionedParagraph('description', 'Description', $this->description);
		$detail .= captionedParagraph('summary', 'Summary', $this->summary);	
		$detail .= captionedParagraph('effort-notes','Effort Notes', $this->hoursNotes);
		//$detail .= hr();
		$detail .= captionedParagraph('started', 'Started', $this->started);
		$detail .= captionedParagraph('updated', 'Updated', $this->updated);
		//$detail .= openDiv('summary-info');
		if ($this->activityCount > 0){
			$effort = openTable('hours-info','displayListTable');
			$heading = wrapTh('Estimated Hours');
			$heading .= wrapTh('Actual Hours');
			$heading .= wrapTh('% Done');
			$effort .= wrapTr($heading);
			$row = wrapTd($this->hoursEstimated);
			$row .= wrapTd($this->hoursActual);
			$row .= wrapTd($this->pctDone);
			$effort .= wrapTr($row);
			$effort .= closeTable();
		} else {
			$effort = openTable('hours-info','displayListTable');
			$effort .= wrapTr(wrapTh('No Activity History.'));	
			$effort .= closeTable();
		}
		if ($this->materialCount > 0){
			$costs = openTable('cost-info','displayListTable');
			$heading = wrapTh('Estimated Cost');
			$heading .= wrapTh('Actual Cost');
			$heading .= wrapTh('Approved');
			$costs .= wrapTr($heading);
			$row = wrapTd($this->costEstimated);
			$row .= wrapTd($this->costActual);			
			if ($this->materialsAuthProject == 'yes'){
				$value = $this->materialsAuthBy;
			} else {
				$value = $this->materialsAuthProject;
			}
			$row .= wrapTd($value);
			$costs .= wrapTr($row);
			$costs .= closeTable();
		} else {
			$costs = openTable('cost-info','displayListTable');
			$costs .= wrapTr(wrapTh('No Materials Costs.'));
			$costs .= closeTable();
		}
		if ($this->receiptCount > 0){
			$income = openTable('receipts-info','displayListTable');
			$heading = wrapTh('Received');
			$heading .= wrapTh('Approved');
			$income .= wrapTr($heading);
			
			$row = wrapTd($this->receiptCostActual);
			if ($this->receiptsAuthProject == 'yes'){
				$value = $this->receiptsAuthBy;
			} else {
				$value = $this->receiptsAuthProject;
			}
			$row .= wrapTd($value);
			$income .= wrapTr($row);
			$income .= closeTable();
		} else {
			$income = openTable('receipts-info','displayListTable');
			$income .= wrapTr(wrapTh('No Income Received.'));
			$income .= closeTable();
		}
		
		$timeAndEffort = openTable('effort-and-cost','displayListTable');
		$row = wrapTd($effort,30);
		$row .= wrapTd($costs,40);
		$row .= wrapTd($income,25);
		
		$timeAndEffort .= wrapTr($row);
		$timeAndEffort .= closeTable();
		
		$detail .= captionedParagraph('time-and-effort','Time, Materials and Receipts',$timeAndEffort);

		$l = new TaskLinks();
		$baseLink = $l->detail('VIEW',$this->id);

		if ($this->activityCount > 0){
			$activities = new ActivityList;
			$activities->setDetails($this->id,$this->resultsPageActivity,10);
			$detail .= $activities->getListing($baseLink);
		}

		if ($this->measureCount > 0){
			$measures = new MeasureList;
			$measures->setDetails($this->id, $this->resultsPageMeasure, 10);
			$detail .= $measures->getListing($baseLink);
		}
		
		if ($this->materialCount > 0){
			$materials = new MaterialList;
			$materials->setDetails($this->id, $this->resultsPageMaterial, 10,'TASK','no',$this->project->id,$this->year,$this->month);
			$detail .= $materials->getListing($baseLink);
		}
		if ($this->receiptCount > 0){
			$receipts = new ReceiptList;
			$receipts->setDetails($this->id, $this->resultsPageReceipt, 10);
			$detail .= $receipts->getListing($baseLink);
		}

		$detail .= closeDisplayDetails();
		return $detail;
	}
	
	public function getTaskSelectList(
		$selectedValue = '0', 
		$idName = 'taskId', 
		$disabled = 'false',
		$showLink = true,
		$onChangeJS = NULL,
		$ajaxEvent = NULL,
		$ajaxEventType = NULL){
	
		
		$sql = $this->sql->selectOptions_Tasks($this->project->id, $selectedValue, $disabled);
		
		$defaultValue = 0;
		$defaultCaption = '-select Task';
		$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
		$select = getSelectList($idName,$allOptions,'none',$disabled,$onChangeJS, $ajaxEvent, $ajaxEventType);
		if ($showLink === true){
			$l = new TaskLinks;	
			$links =$l->detailViewEditHref($selectedValue);
			$select .= $links;
		}
		return $select;
	}	
	
	
	private function setAddRecordDefaults(){
		global $sessionTime;
		$this->started = $sessionTime;	
		$this->order = $this->project->tasksCount + 1;
		$this->hoursActual = 0;
		$this->locationId = $this->project->locationId;		
		$this->materialsAuthBy = 'Not Applicable';
		$this->materialsAuthProject = 'no';
		$this->receiptsAuthBy = 'Not Applicable';
		$this->receiptsAuthProject = 'no';

	}	
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Task';
		} else {
			$legend = 'Edit Task';
		}
		$entity = 'task';
		$c = new TaskLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'pr_Task_Save.php',$contextMenu);

		$fields = inputFieldNumber($entity,$this->order,'order','Order');

		$fields .= inputFieldName($entity,$this->name,'name','Task Name');
		
		$l = new TaskType;
		$select = $l->getTaskTypeSelectList($this->typeId,'taskTypeId');
		$fields .= captionedInput('Task Type', $select);


		$formRequired = $fields;




		//if ($_SESSION['is-admin'] == 'yes' && $this->pageMode == 'EDIT'){
		$input = $this->project->getProjectSelectList($this->project->id,'projectId','false',false);
		$fields = captionedInput('Task Project', $input);
		//}
		
		$l = new Location;
		$select = $l->getLocationSelectList($this->locationId,'locationId','false',false);
		$fields .= captionedInput('Location', $select);

		$fields .= inputFieldDescription($entity,$this->description,'description');


		$fields .= inputFieldComments($entity,$this->summary,'summary','Summary');
		
		$fields .= inputFieldTimestamp($entity, 'started', $this->started, 'Start Date'); 		
				
		$fields .= inputFieldNumber($entity,$this->hoursEstimated,'hoursEstimated','Estimated');
		
		$tooltip = 'Add activity details to record hours';
		$fields .= inputFieldNumber($entity,$this->hoursActual,'hoursActual','Actual');
		
		$fields .= inputFieldNumber($entity,$this->pctDone,'pctDone','% Done');
		
		$fields .= inputFieldComments($entity,$this->hoursNotes,'hoursNotes','Effort Notes',4000);


		if ($this->materialCount > 0) {
			if ($_SESSION['is-admin'] == 'yes'){
				//current user is admin, allow editing approvals
				$input = 'Costs Approved:'.getSelectYesNo('materialsAuthProject', $this->materialsAuthProject);
				if ($this->materialsAuthProject == 'no'){
					$this->materialsAuthBy = $_SESSION['login-name'];
				}
				$input .= spacer().'By:'.getTextInput('materialsAuthBy', $this->materialsAuthBy, 30, 50);
			} else {
				//materials present but user cannot edit approvals
				$input = 'Costs Approved:'.$this->materialsAuthProject;
				$input .= getHiddenInput('materialsAuthProject', $this->materialsAuthProject);
				$input .= spacer().'By:'.$this->materialsAuthBy;
				$input .= getHiddenInput('materialsAuthBy', $this->materialsAuthBy);	
			}
		} else {
			//no materials under task, dont show authorization fields
			$input = getHiddenInput('materialsAuthProject', $this->materialsAuthProject);
			$input .= getHiddenInput('materialsAuthBy', $this->materialsAuthBy);	
			$input .= 'No Task Costs';
		}
		$fields .= captionedInput('Costs', $input);


		if ($this->receiptCount > 0) {
			if ($_SESSION['is-admin'] == 'yes'){
				//current user is admin, allow editing approvals
				$input = 'Receipts Approved:'.getSelectYesNo('receiptsAuthProject', $this->receiptsAuthProject);
				if ($this->receiptsAuthProject == 'no'){
					$this->receiptsAuthBy = $_SESSION['login-name'];
				}
				$input .= spacer().'By:'.getTextInput('receiptsAuthBy', $this->receiptsAuthBy, 30, 50);
			} else {
				//materials present but user cannot edit approvals
				$input = 'Receipts Approved:'.$this->receiptsAuthProject;
				$input .= getHiddenInput('receiptsAuthProject', $this->receiptsAuthProject);
				$input .= spacer().'By:'.$this->receiptsAuthBy;
				$input .= getHiddenInput('receiptsAuthBy', $this->receiptsAuthBy);	
			}
		} else {
			//no materials under task, dont show authorization fields
			$input = getHiddenInput('receiptsAuthProject', $this->receiptsAuthProject);
			$input .= getHiddenInput('receiptsAuthBy', $this->receiptsAuthBy);	
			$input .= 'No Task Income';
		}
		$fields .= captionedInput('Income',$input);		
		
		$formOptional = $fields;
		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('taskId', $this->id);

		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;

		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
		
	public function collectPostValues(){
		//called by save form prior to running adds/updates
		$this->pageMode = $_POST['mode'];

		$this->id = $_POST['taskId'];
		$this->project->id = $_POST['projectId'];
		$this->locationId = $_POST['locationId'];
		$this->typeId = $_POST['taskTypeId'];
		$this->setParentProject();
		
		$this->order = $_POST['order']; 
		$this->started = getTimestampPostValues('started');
		$this->name = $conn>escape_string($_POST['name']); 
		$this->description = $conn>escape_string($_POST['description']); 
		$this->summary = $conn>escape_string($_POST['summary']); 
		$this->pctDone = $_POST['pctDone']; 
		$this->hoursEstimated = $_POST['hoursEstimated']; 
		//$this->hoursActual = $_POST['hoursActual']; 
		$this->hoursNotes = $conn>escape_string($_POST['hoursNotes']); 
		$this->materialsAuthProject = $conn>escape_string($_POST['materialsAuthProject']);
		if ($this->materialsAuthProject == 'yes'){
			$this->materialsAuthBy = $conn>escape_string($_POST['materialsAuthBy']);
		} else {
			$this->materialsAuthBy = 'Not Approved';			
		}
		$this->receiptsAuthProject = $conn>escape_string($_POST['receiptsAuthProject']);
		if ($this->receiptsAuthProject == 'yes'){
			$this->receiptsAuthBy = $conn>escape_string($_POST['receiptsAuthBy']);
		} else {
			$this->receiptsAuthBy = 'Not Approved';			
		}

	}
	
	public function updateActivitySummary(){	
		
		$this->summarizeActivity();
				
		$sql = " UPDATE tasks as t ";
		$sql .= " SET ";
		$sql .= " t.updated = CURRENT_TIMESTAMP, ";		
		$sql .= " t.hours_actual = 	".$this->hoursActual.", ";
		$sql .= " t.hours_estimated = ".$this->hoursEstimated." ";
		$sql .= " WHERE t.id = ".$this->id." ";
		
		$result = dbRunSQL($sql);

		$this->project->UpdateTaskSummary();

	}

	public function resetMaterialsAuthorization(){	
		$this->materialsAuthProject = 'no';
		$this->materialsAuthBy = 'Not Approved';
		$sql = " UPDATE tasks as t ";
		$sql .= " SET ";
		$sql .= "t.updated = CURRENT_TIMESTAMP, ";		
		$sql .= " t.materials_auth_project = '".$this->materialsAuthProject."', ";
		$sql .= " t.materials_auth_by = '".$this->materialsAuthBy."' ";
		$sql .= " WHERE t.id = ".$this->id." ";
		
		$result = dbRunSQL($sql);
		
		$this->setMaterialsActualCost();
	}
	
	private function setMaterialsActualCost(){
		if ($this->materialsAuthProject == 'yes'){
			$sql = " UPDATE materials as m ";
			$sql .= " SET m.cost_actual = m.cost_estimated ";
			$sql .= " WHERE m.task_id = ".$this->id." ";
		} else {
			$sql = " UPDATE materials as m ";
			$sql .= " SET m.cost_actual = 0 ";
			$sql .= " WHERE m.task_id = ".$this->id." ";			
		}
		$result = dbRunSQL($sql);
	}

	public function resetReceiptsAuthorization(){	
		$this->receiptsAuthProject = 'no';
		$this->receiptsAuthBy = 'Not Approved';
		$sql = " UPDATE tasks as t ";
		$sql .= " SET ";
		$sql .= "t.updated = CURRENT_TIMESTAMP, ";		
		$sql .= " t.receipts_auth_project = '".$this->receiptsAuthProject."', ";
		$sql .= " t.receipts_auth_by = '".$this->receiptsAuthBy."' ";
		$sql .= " WHERE t.id = ".$this->id." ";
		
		$result = dbRunSQL($sql);
	}

	private function copyTask(){

		if ($this->pageMode == 'COPY'){
		

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
			$sql .= " project_id, ";
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
			$sql .= " FROM tasks t WHERE t.id = ".$this->id." ";
			
		$result = dbRunSQL($sql);
		
			$this->id = dbInsertedId();
		
			//reset page mode from COPY to EDIT and set details using the copied id
			$this->setDetails($this->id,$this->project->id,'EDIT');
			$this->name = 'COPY OF '.$this->name;
			
		}

		
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){

			$sql = " UPDATE tasks AS t ";
			$sql .= " SET ";
			$sql .= " t.project_id = ".$this->project->id.", ";
			$sql .= " t.task_order = ".$this->order.", ";
			$sql .= " t.location_id = ".$this->locationId.", ";
			$sql .= " t.started = '".$this->started."', ";
			$sql .= " t.name = '".$this->name."', ";
			$sql .= " t.description = '".$this->description."', ";
			$sql .= " t.summary = '".$this->summary."', ";
			$sql .= " t.updated = CURRENT_TIMESTAMP, ";
			$sql .= " t.type_id = ".$this->typeId.", ";
			$sql .= " t.hours_estimated = ".$this->hoursEstimated.", ";
			$sql .= " t.pct_done = ".$this->pctDone.", ";
			$sql .= " t.hours_notes = '".$this->hoursNotes."', ";
			$sql .= " t.materials_auth_project = '".$this->materialsAuthProject."', ";
			$sql .= " t.materials_auth_by = '".$this->materialsAuthBy."', ";
			$sql .= " t.receipts_auth_project = '".$this->receiptsAuthProject."', ";
			$sql .= " t.receipts_auth_by = '".$this->receiptsAuthBy."' ";

			$sql .= " WHERE t.id = ".$this->id." ";	
		
		$result = dbRunSQL($sql);
			
			$this->setMaterialsActualCost();			
			$this->project->UpdateTaskSummary();
		} else {
	
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

			$sql .= " VALUES (";
			$sql .= " '".$this->name."', ";
			$sql .= " ".$this->project->id.", ";
			$sql .= " ".$this->locationId.", ";		
			$sql .= " ".$this->typeId.", ";			
			$sql .= " ".$this->order.", ";
			$sql .= " '".$this->description."', ";
			$sql .= " '".$this->summary."', ";
			$sql .= " '".$this->started."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " ".$this->hoursEstimated.", ";
			$sql .= " 0, ";
			$sql .= " '".$this->hoursNotes."', ";
			$sql .= " '".$this->materialsAuthProject."', ";
			$sql .= " '".$this->materialsAuthBy."', ";
			$sql .= " '".$this->receiptsAuthProject."', ";
			$sql .= " '".$this->receiptsAuthBy."') ";
			
		$result = dbRunSQL($sql);
			$this->id = dbInsertedId();

			$this->project->UpdateTaskSummary();

		}
	
	}
	
} 

class TaskSQL{
	
function columnsTasks(){
$c = " t.id,  ";
$c .= " t.project_id,  ";
$c .= " p.name project_name, ";
$c .= " pt.name project_type, ";
$c .= " pt.highlight_style project_highlight_style, ";
$c .= " t.location_id,  ";
$c .= " t.type_id,  ";
$c .= " tt.highlight_style, ";
$c .= " tt.name task_type, ";
$c .= " tt.frequency, ";
$c .= " t.task_order,  ";
$c .= " t.name,  ";
$c .= " t.description,  ";
$c .= " t.summary,  ";
$c .= " t.started,  ";
$c .= " t.updated,  ";
$c .= " t.pct_done,  ";
$c .= " t.hours_estimated,  ";
$c .= " t.hours_actual,  ";
$c .= " t.hours_notes, ";
$c .= " t.materials_auth_project, ";
$c .= " t.materials_auth_by, ";
$c .= " t.receipts_auth_project, ";
$c .= " t.receipts_auth_by ";
return $c;
}
function listTasksByProject($selectedProjectId, $resultPage, $rowsPerPage){
$q = " SELECT  ";
$q .= $this->columnsTasks();
	$q .= " FROM projects p join tasks t ON p.id = t.project_id ";
	$q .= " LEFT OUTER JOIN project_types pt on p.type_id = pt.id ";
	$q .= " LEFT OUTER JOIN task_types tt on t.type_id = tt.id ";
$q .= " WHERE  ";
$q .= " p.id = ".$selectedProjectId." ";
$q .= " ORDER BY ";
$q .= " t.task_order, t.started, t.id ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}

function infoTask($selectedTaskId){
$q = " SELECT  ";
$q .= $this->columnsTasks();
	$q .= " FROM projects p join tasks t ON p.id = t.project_id ";
	$q .= " LEFT OUTER JOIN project_types pt on p.type_id = pt.id ";
	$q .= " LEFT OUTER JOIN task_types tt on t.type_id = tt.id ";
$q .= " WHERE  ";
$q .= " t.id = ".$selectedTaskId." ";
return $q;
}

function infoTaskSummaryByProject($selectedProjectId){
$q = " SELECT  ";
$q .= " COUNT(*) as total_tasks, ";
$q .= " sum(t.hours_estimated) as sum_hours_estimated, ";
$q .= " sum(t.hours_actual) as sum_hours_actual, ";
$q .= " sum(t.pct_done)/count(*) as overall_pct_done ";
$q .= " FROM tasks AS t ";
$q .= " WHERE t.project_id = ".$selectedProjectId." ";
return $q;
}
function countTasksByProject($selectedProjectId){
$q = " SELECT  ";
$q .= " COUNT(*) total_tasks ";
$q .= " FROM tasks AS t ";
$q .= " WHERE t.project_id = ".$selectedProjectId." ";
return $q;	
}
function countTasksByProjectByStatus($selectedProjectId, $taskStatus = 'OPEN'){
$q = " SELECT  ";
$q .= " COUNT(*) AS total_tasks ";
$q .= " FROM tasks AS t ";
$q .= " WHERE t.project_id = ".$selectedProjectId." ";
if ($taskStatus == 'OPEN'){
	$q .= " AND t.pct_done < 1 "; 
}
if ($taskStatus == 'CLOSED'){
	$q .= " AND t.pct_done = 1 ";
}
return $q;
}

public function listPeriodicTasks($complete = 'NO',$resultPage,$rowsPerPage){
	$q = " SELECT ";
	$q .= $this->columnsTasks();
	$q .= " FROM projects p join tasks t ON p.id = t.project_id ";
	$q .= " JOIN project_types pt on p.type_id = pt.id ";
	$q .= " JOIN task_types tt on t.type_id = tt.id ";
	$q .= " WHERE t.id IN ";
	$q .= $this->periodicTasksSubquery($complete);
	$q .= " ORDER BY ";
	$q .= " project_type, project_name, t.task_order ";
	$q .= sqlLimitClause($resultPage, $rowsPerPage);
	return $q;
}
public function countPeriodicTasks($complete = 'NO'){
	$q = " SELECT count(*) AS total_tasks ";
	$q .= " FROM projects p join tasks t ON p.id = t.project_id ";
	$q .= " JOIN project_types pt on p.type_id = pt.id ";
	$q .= " JOIN task_types tt on t.type_id = tt.id ";
	$q .= " WHERE t.id IN ";
	$q .= $this->periodicTasksSubquery($complete);
	return $q;
}
private function periodicTasksBaseSubquery($frequency, $where){
$q = " ( select a.task_id ";
$q .= " from activities a join tasks t on a.task_id = t.id ";
$q .= " join task_types tt on t.type_id = tt.id ";
$q .= " where tt.frequency = '".$frequency."' ";
$q .= " and a.hours_actual > 0 ";
$q .= $where." ) ";
return $q;	
}
private function periodicTasksBaseSelect($frequency,$subqueryWhere, $complete = 'NO'){
$q = " SELECT t.id ";
$q .= " FROM projects p JOIN tasks t ON p.id = t.project_id ";
$q .= " JOIN task_types tt ON t.type_id = tt.id ";
$q .= " WHERE p.pct_done != 1 AND tt.frequency = '".$frequency."' ";
if ($complete == 'YES'){
	$q .= " AND t.id IN ";
} else {
	$q .= " AND t.id NOT IN ";
}
$q .= $this->periodicTasksBaseSubquery($frequency,$subqueryWhere);
return $q;
}
private function periodicTasksSubquery($complete = 'NO'){
	$q = " (";
	$subqueryWhere = " and date(a.started) = date(CURRENT_TIMESTAMP) ";	
	$q .= $this->periodicTasksBaseSelect('daily', $subqueryWhere,$complete);
	$q .= " UNION ALL ";
	$subqueryWhere = " and yearweek(a.started) = yearweek(CURRENT_TIMESTAMP) ";
	$q .= $this->periodicTasksBaseSelect('weekly', $subqueryWhere,$complete);
	$q .= " UNION ALL ";
	$subqueryWhere = " and year(a.started) = year(CURRENT_TIMESTAMP) ";
	$subqueryWhere .= " and month(a.started) = month(CURRENT_TIMESTAMP) ";
	$q .= $this->periodicTasksBaseSelect('monthly', $subqueryWhere,$complete);
	$q .= " UNION ALL ";
	$subqueryWhere = " and year(a.started) = year(CURRENT_TIMESTAMP) ";
	$subqueryWhere .= " and quarter(a.started) = quarter(CURRENT_TIMESTAMP) ";
	$q .= $this->periodicTasksBaseSelect('quarterly', $subqueryWhere,$complete);
	$q .= " UNION ALL ";
	$subqueryWhere = " and year(a.started) = year(CURRENT_TIMESTAMP) ";
	$q .= $this->periodicTasksBaseSelect('annual',$subqueryWhere,$complete);
	$q .= ") ";
	return $q;
}
public function selectOptions_Tasks($projectId,$selectedTaskId,$disabled){
	$q = " SELECT ";
	$q .= " p.id as value, ";
	$q .= " concat_ws(' ',p.task_order,p.name) as caption ";
	$q .= " FROM tasks p ";
	$q .= " WHERE p.project_id = ".$projectId." AND p.pct_done < 1 ";
	if ($disabled == 'true'){
		$q .= " AND p.id = ".$selectedTaskId." ";	
	}
	$q .= " ORDER BY caption ";
	return $q;	
}

}
?>
