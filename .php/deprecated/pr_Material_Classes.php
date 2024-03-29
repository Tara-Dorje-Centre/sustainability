<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class MaterialLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($taskId,$caption = 'Materials', $projectId = 0, $display = 'TASK', $approved = 'no'){
		$link = $this->listing($taskId, $projectId, $display, $approved);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $materialId = 0, $taskId = 0, $caption = 'Material'){
		$link = $this->detail($pageAction,$materialId,$taskId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	public function listing($taskId, $projectId = 0, $display = 'TASK', $approved = 'no'){
		$link = 'pr_Material_List.php?taskId='.$taskId;
		if ($projectId != 0){
			$link .= '&projectId='.$projectId;
		}
		if ($display != 'TASK'){
			$link .= '&displayProject='.$display;
//			$link .= '&approvedCosts='.$approved;
		}
		return $link;
	}
	public function listingPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&resultsPageMaterial=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	public function detail($pageAction, $materialId, $taskId = 0){
		$link = 'pr_Material_Detail.php?pageAction='.$pageAction;
		if($taskId != 0){
			$link .= '&taskId='.$taskId;			
		}
		if ($materialId != 0){
			$link .= '&materialId='.$materialId;
		}
		return $link;
	}	
	public function detailAddHref($taskId,$caption = '+Material'){
		$l = $this->detailHref('ADD',0,$taskId,$caption);
		return $l;	
	}
	public function detailViewHref($materialId,$caption = 'ViewMaterial'){
		$l = $this->detailHref('VIEW',$materialId,0,$caption);
		return $l;	
	}
	public function detailEditHref($materialId,$caption = 'EditMaterial'){
		$l = $this->detailHref('EDIT',$materialId,0,$caption);
		return $l;	
	}
	public function detailViewEditHref($materialId = 0, $viewCaption = 'Material'){
		
		if ($materialId != 0){
			$links = $this->detailViewHref($materialId,$viewCaption);
			$links .= $this->detailEditHref($materialId,'#');
		}
		return $links;
	}	
	public function linkProjectMaterials($projectId){
		return $this->listingHref(0,'ProjectMaterials',$projectId,'PROJECT','yes');	
	}
	public function linkTaskMaterials($taskId){
		return $this->listingHref($taskId,'TaskMaterials',0,'TASK','no');	
	}
	public function linkProjectMaterialsSummary($projectId){
		return $this->listingHref(0,'ProjectSummary',$projectId,'PROJECT-SUMMARY','yes');	
	}
	public function linkTaskMaterialsSummary($taskId){
		return $this->listingHref($taskId,'TaskSummary',0,'TASK-SUMMARY','no');	
	}
	
}

class MaterialList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $task;
	public $month;
	public $year;
	private $prevCalendarLink = '';
	private $nextCalendarLink = '';	
	private $displayProject = 'TASK';
	private $approved = 'no';
	public $costActual = 0;
	public $costEstimated = 0;
	private $sql;

	public function __construct(){
		$this->task = new Task;
		$this->sql = new MaterialSQL;
	}
	
	public function setDetails($taskId, $resultPage = 1, $resultsPerPage = 10, $display = 'TASK', $approved = 'no',$projectId = 0, $year = 0, $month = 0){
		$this->task->id = $taskId;	
		$this->displayProject = $display;
		$this->approved = $approved;
		$this->task->project->id = $projectId;
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->task->setDetails($this->task->id, $this->task->project->id, 'VIEW');
		$this->setCalendarRange($year,$month);
		$this->setFoundCount();
		$this->setSummary();
	}
	
	private function setCalendarRange($year, $month){

		if ($year != 0 && $year != 0){
			$this->year = $year;
			$this->month = $month;
		} else {
			//dates not set, use current month and year
			global $sessionTime;
			$this->year = getTimestampYear($sessionTime);
			$this->month = getTimestampMonth($sessionTime);
		}
	}
	
		
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		
		$title .= $this->task->project->name.br();

		if ($this->displayProject == 'TASK'){
			$title .= 'Task: '.$this->task->name.br();
		}
		if ($this->year > 0 && $this->month > 0){
			$title .= $this->year.'-'.$this->month.br();
		}

		if ($this->displayProject == 'TASK'){
			if ($this->task->materialsAuthProject == 'yes'){
				$title .= 'Materials Cost Approved = '.$this->costActual;
			} else {
				$title .= 'Materials Cost Estimated = '.$this->costEstimated;
			}
		} elseif ($this->displayProject == 'PROJECT'){
			$title .= 'Materials Cost Approved = '.$this->costActual;
		}
		
		$title .= closeDiv();
		return $title;	
	}	

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projects = new ProjectLinks($menuType,$menuStyle);
		$tasks = new TaskLinks($menuType,$menuStyle);
		$materials = new MaterialLinks($menuType,$menuStyle);
					
		$menu = $projects->openMenu('section-heading-links');
		
		$menu .= $projects->detailViewHref($this->task->project->id);		
		$menu .= $projects->resetMenu();	
			
//		if ($this->task->project->materialsCount > 0){		
			$menu .= $materials->linkProjectMaterialsSummary($this->task->project->id);
			$menu .= $materials->linkProjectMaterials($this->task->project->id);
//		}

		if ($this->displayProject == 'TASK' or $this->displayProject == 'TASK-SUMMARY'){
			$menu .= $projects->resetMenu();	
			$menu .= $tasks->detailViewHref($this->task->id);
//			if ($this->found > 0){
			$menu .= $projects->resetMenu();	

				$menu .= $materials->linkTaskMaterialsSummary($this->task->id);		
				$menu .= $materials->linkTaskMaterials($this->task->id);		
//			}
			$menu .= $projects->resetMenu();	
			$menu .= $materials->detailAddHref($this->task->id);
		}
		$menu .= $projects->closeMenu();	
		return $menu;			
	}	
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		if ($this->displayProject == 'PROJECT'){
			$sql = $this->sql->countMaterialsByProject($this->task->project->id,$this->year,$this->month);
		} elseif ($this->displayProject == 'PROJECT-SUMMARY'){
			$sql = $this->sql->countMaterialsByProject($this->task->project->id,$this->year,$this->month);
		} elseif ($this->displayProject == 'TASK-SUMMARY'){
			$sql = $this->sql->countMaterialsByTask($this->task->id,$this->year,$this->month);
		} else {
			$sql = $this->sql->countMaterialsByTask($this->task->id,$this->year,$this->month);
		}
		$this->found = getSQLCount($sql, 'total_materials');
	}
	
	private function setSummary(){
		if ($this->displayProject == 'PROJECT'){
			$sql = $this->sql->summarizeMaterialByProject($this->task->project->id);

		} elseif ($this->displayProject == 'PROJECT-SUMMARY'){
			$sql = $this->sql->summarizeMaterialByProject($this->task->project->id, $this->year,$this->month);

		} elseif ($this->displayProject == 'TASK-SUMMARY'){
			$sql = $this->sql->summarizeMaterialByTask($this->task->id, $this->year,$this->month);
		} else {
			$sql = $this->sql->summarizeMaterialByTask($this->task->id,$this->year,$this->month);
		}
		
		$result = dbGetResult($sql);
		$result = $conn->query($sql) or exit($locale.$conn->error);
		if($result){
	  	while ($row = $result->fetch_assoc())
	  	{
			$this->costActual = $row["sum_cost_actual"];
			$this->costEstimated = $row["sum_cost_estimated"];
		}
		$result->close();
		}
	
	}


	private function getCalendarLinks($baseUrl){
		$l = new MaterialLinks('DIV','paged');
//		$baseUrl = $l->listing($this->task->id,$this->task->project->id,$this->displayProject,$this->approved);
		$links = $l->openMenu('calendar-links');
				
		$this->prevCalendarLink = '';
		$this->nextCalendarLink = '';
		$foundCurrent = false;
		$foundNext = false;
				
		if ($this->displayProject == 'PROJECT' or $this->displayProject == 'PROJECT-SUMMARY') {
			$sql = $this->sql->calendarLinksProjectMaterials($this->task->project->id);
		} else {  //if ($this->displayProject == 'TASK' or $this->displayProject == 'TASK-SUMMARY') {
			$sql = $this->sql->calendarLinksTaskMaterials($this->task->project->id, $this->task->id);			
		}

		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$month = $row["month"];
			$year = $row["year"];
			$caption = $year.'-'.$month;
			if ($year == $this->year && $month == $this->month){
				//skip link and show caption if link for current display monthyear
				$link = span($caption,$l->cssItem.'-current');
				$foundCurrent = true;
			} else {
				$link = $l->formatCalendarHref($caption,$baseUrl,$year,$month);
				if ($foundCurrent == false){
					$this->prevCalendarLink = $l->formatCalendarHref('Previous',$baseUrl,$year,$month);
				} else {
					if ($foundNext == false){
						$this->nextCalendarLink = $l->formatCalendarHref('Next',$baseUrl,$year,$month);
						$foundNext = true;
					}
				}
			}
			$links .= $link;
		}
		$result->close();
		}
		
		if ($foundCurrent == false && $this->year > 0 && $this->month > 0){
			$caption = $this->year.'-'.$this->month;
			$link = $l->formatCalendarHref($caption,$baseUrl,$this->year,$this->month);
			$links .= $link;
		}
		$caption = 'All';
		if ($this->year == -1 && $this->month == -1){
			$link = span($caption,$l->cssItem.'-current');
		} else {
			$link = $l->formatCalendarHref($caption,$baseUrl,-1,-1);
		}
		$links .= $link;			

		$links .= $l->closeMenu();
		return $links;
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
	
	private function getBaseUrl($baseUrl = 'USE_LISTING'){

		$materialL = new MaterialLinks;
		
		if ($baseUrl == 'USE_LISTING'){
			$link = $materialL->listing($this->task->id, $this->task->project->id,$this->displayProject,$this->approved);
		} else { 
			$link = $baseUrl;
		}
		return $link;
	}

	private function getSummaryTaskMonthly($pagingBaseLink = 'USE_LISTING'){

		
		$sql = $this->sql->listMaterialSummaryByTask($this->task->id,$this->task->project->id,$this->year,$this->month);		
		
		$result = mysql_query($sql) or die(mysql_error());		

		$base = $this->getBaseUrl($pagingBaseLink);
		$pagingLinks = $this->getCalendarLinks($base);					
		$quickEdit = NULL;
		
		$list = openDisplayList('material','Task Materials Summary',$pagingLinks,$quickEdit);

		$heading = wrapTh('Type');
		$heading .=  wrapTh('Cost Est');
		$heading .=  wrapTh('Cost Actual');
		$list .=  wrapTr($heading);
		
		$sql = $this->sql->listMaterialSummaryByTask($this->task->id,$this->task->project->id,$this->year,$this->month);		
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{
			$type = $row['material_type'];
			$est = $row['sum_cost_estimated'];
			$act = $row['sum_cost_actual'];
			if ($this->costEstimated == 0){
				$estPercent = 0;
			} else {
				$estPercent = round($est / $this->costEstimated,2) * 100;
			}
			if ($this->costActual == 0){
			$actPercent = 0;
			} else {
			$actPercent = round($act / $this->costActual,2) * 100;
			}
			$detail = wrapTd($type.'('.$estPercent.'%)');			

			$detail .= wrapTd($est);
			$detail .= wrapTd($act);
			$cssItem = 'none';
			$list .=  wrapTr($detail, $cssItem);
		}
		$result->close();
		}
		
		$detail = wrapTh('Totals');			
		$detail .= wrapTh($this->costEstimated);
		$detail .= wrapTh($this->costActual);
		$list .=  wrapTr($detail);

		$list .= closeDisplayList();

//		$base = $this->getBaseUrl($pagingBaseLink);
		$pagingLinks = NULL;
		$quickEdit = NULL;		
		$list .= openDisplayList('material','Done By Summary',$pagingLinks,$quickEdit);

		$heading = wrapTh('Done By');
		$heading .=  wrapTh('Cost Est');
		$heading .=  wrapTh('Cost Actual');
		$list .=  wrapTr($heading);


		$sql = $this->sql->listMaterialSummaryByTaskDoneBy($this->task->id,$this->task->project->id,$this->year,$this->month);		
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{
			$type = $row['done_by'];
			$est = $row['sum_cost_estimated'];
			$act = $row['sum_cost_actual'];
			if ($this->costEstimated == 0){
				$estPercent = 0;
			} else {
				$estPercent = round($est / $this->costEstimated,2) * 100;
			}
			if ($this->costActual == 0){
			$actPercent = 0;
			} else {
			$actPercent = round($act / $this->costActual,2) * 100;
			}
			$detail = wrapTd($type.'('.$estPercent.'%)');			
			$detail .= wrapTd($est);
			$detail .= wrapTd($act);
			$cssItem = 'none';
			$list .=  wrapTr($detail, $cssItem);
		}
		$result->close();
		}
		
		$detail = wrapTh('Totals');			
		$detail .= wrapTh($this->costEstimated);
		$detail .= wrapTh($this->costActual);
		$list .=  wrapTr($detail);

		$list .= closeDisplayList();
		
		return $list;
	}

	private function getSummaryProjectMonthly($pagingBaseLink = 'USE_LISTING'){

		$id = $this->task->project->id;
						
		$base = $this->getBaseUrl($pagingBaseLink);
		$pagingLinks = $this->getCalendarLinks($base);					
		$quickEdit = NULL;

		$list = openDisplayList('material','Project Materials Summary',$pagingLinks,$quickEdit);

		$heading = wrapTh('Type');
		$heading .=  wrapTh('Cost Est');
		$heading .=  wrapTh('Cost Actual');
		$list .=  wrapTr($heading);
		
		$sql = $this->sql->listMaterialSummaryByProject($id, $this->year,$this->month);		
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{
			$type = $row['material_type'];
			$est = $row['sum_cost_estimated'];
			$act = $row['sum_cost_actual'];
			if ($this->costEstimated == 0){
				$estPercent = 0;
			} else {
				$estPercent = round($est / $this->costEstimated,2) * 100;
			}
			if ($this->costActual == 0){
			$actPercent = 0;
			} else {
			$actPercent = round($act / $this->costActual,2) * 100;
			}
			$detail = wrapTd($type.'('.$estPercent.'%)');			

			$detail .= wrapTd($est);
			$detail .= wrapTd($act);
			$cssItem = 'none';
			$list .=  wrapTr($detail, $cssItem);
		}
		$result->close();
		}
		
		$detail = wrapTh('Totals');			
		$detail .= wrapTh($this->costEstimated);
		$detail .= wrapTh($this->costActual);
		$list .=  wrapTr($detail);
		$list .= closeDisplayList();


//		$base = $this->getBaseUrl($pagingBaseLink);
		$pagingLinks = NULL;
		$quickEdit = NULL;		
		$list .= openDisplayList('material','Done By Summary',$pagingLinks,$quickEdit);

		$heading = wrapTh('Done By');
		$heading .=  wrapTh('Cost Est');
		$heading .=  wrapTh('Cost Actual');
		$list .=  wrapTr($heading);
		
		$sql = $this->sql->listMaterialSummaryByProjectDoneBy($this->task->project->id,$this->year,$this->month);		
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{
			$type = $row['done_by'];
			$est = $row['sum_cost_estimated'];
			$act = $row['sum_cost_actual'];
			
			if ($this->costEstimated == 0){
				$estPercent = 0;
			} else {
				$estPercent = round($est / $this->costEstimated,2) * 100;
			}
			if ($this->costActual == 0){
			$actPercent = 0;
			} else {
			$actPercent = round($act / $this->costActual,2) * 100;
			}
			$detail = wrapTd($type.'('.$estPercent.'%)');			
			$detail .= wrapTd($est);
			$detail .= wrapTd($act);
			$cssItem = 'none';
			$list .=  wrapTr($detail, $cssItem);
		}
		$result->close();
		}
		
		$detail = wrapTh('Totals');			
		$detail .= wrapTh($this->costEstimated);
		$detail .= wrapTh($this->costActual);
		$list .=  wrapTr($detail);

		$list .= closeDisplayList();

		return $list;
	}


	private function getListingProject($pagingBaseLink = 'USE_LISTING'){


		
		$materialL = new MaterialLinks;
		$taskL = new TaskLinks;


		$base = $this->getBaseUrl($pagingBaseLink);
		$pagingLinks = $this->getCalendarLinks($base);			
		$base = $materialL->formatCalendarLink($base,$this->year,$this->month);
		
		$pagingLinks .= $materialL->listingPaged($base,$this->found,$this->resultPage,$this->perPage);		
						
		//on project materials report dont show editing form
		$quickEdit = NULL;
		//}
		$list = openDisplayList('material','Materials',$pagingLinks,$quickEdit);

		$heading = '';

		$heading = wrapTh('Task');	
		
		$heading .=  wrapTh('Material');
		$heading .= wrapTh('Type');
		$heading .=  wrapTh('Date Reported');
		$heading .=  wrapTh('Done By');
		$heading .= wrapTh('Qty Units');
		$heading .=  wrapTh('Qty');
		$heading .=  wrapTh('Unit Cost');
		$heading .=  wrapTh('Cost Est');
		$heading .= wrapTh('Paid To');
		//$heading .=  wrapTh('Cost Actual');
		$heading .=  wrapTh('Links');		
		$list .=  wrapTr($heading);
		
		
		$sql = $this->sql->listMaterialsByProject($this->task->project->id,$this->resultPage, $this->perPage,$this->year,$this->month);		
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$m = new Material;
			$m->id = $row["id"];
			$m->task->id = $row["task_id"];
			$m->task->name = $row["task_name"];
			$m->locationId = $row["location_id"];
			$m->name = stripslashes($row["name"]);
			$m->typeName = stripslashes($row["type_name"]);
			//$m->description = stripslashes($row["description"]);
			$m->dateReported = $row["date_reported"];
			$m->doneBy = stripslashes($row["done_by"]);
			$m->paidTo = stripslashes($row["paid_to"]);
			$m->updated = $row["updated"];	
			$m->quantityUnitMeasureName = stripslashes($row["qty_unit_measure_name"]);
			$m->quantity = $row["quantity"];	
			$m->costEstimated = $row["cost_estimated"];						
			$m->costActual = $row["cost_actual"];
			$m->costUnit = $row["cost_unit"];
			//$m->notes = stripslashes($row["notes"]);
			$m->linkText = stripslashes($row["link_text"]);
			$m->linkUrl = stripslashes($row["link_url"]);
			$cssItem = stripslashes($row["highlight_style"]);

			$m->formatForDisplay();
			
			$detail = '';
			//if ($this->displayProject == 'PROJECT'){
			$link = $taskL->detailViewEditHref($m->task->id, $m->task->name);
			$detail = wrapTd($link);
			//}
			$link = $materialL->detailViewEditHref($m->id,$m->name);
			$detail .= wrapTd($link);
			$detail .= wrapTd($m->typeName);			
			$detail .= wrapTd($m->dateReported);
			$detail .= wrapTd($m->doneBy);
			$detail .= wrapTd($m->quantityUnitMeasureName);
			$detail .= wrapTd($m->quantity);
			$detail .= wrapTd($m->costUnit);
			$detail .= wrapTd($m->costEstimated);
			$detail .= wrapTd($m->paidTo);
			//$detail .= wrapTd($m->costActual);
			if ($m->linkText != '' && $m->linkUrl != ''){
				$link = $materialL->formatHref($m->linkText,$m->linkUrl,'_blank');
				$detail .= wrapTd($link);
			} else {
				$detail .= wrapTd(spacer());
			}

			$list .=  wrapTr($detail, $cssItem);
		}
		$result->close();
		}

		$list .= closeDisplayList();



		return $list;
		
	
	
	}

	private function getListingTask($pagingBaseLink = 'USE_LISTING'){
		
		$materialL = new MaterialLinks;
		$taskL = new TaskLinks;

		$base = $this->getBaseUrl($pagingBaseLink);
		$pagingLinks = $this->getCalendarLinks($base);			
		$base = $materialL->formatCalendarLink($base,$this->year,$this->month);

		$pagingLinks .= $materialL->listingPaged($base,$this->found,$this->resultPage,$this->perPage);		

						
		//on task materials list, show quick edit form (only if on materials listing page)
		if ($pagingBaseLink == 'USE_LISTING'){
		$m = new Material;
		$m->setDetails(0,$this->task->id,'ADD');
		$quickEdit = $m->editForm();
		} else {
			$quickEdit = NULL;
		}
		$list = openDisplayList('material','Materials',$pagingLinks,$quickEdit);

		$heading = '';
		$heading .=  wrapTh('Material');
		$heading .= wrapTh('Type');
		$heading .=  wrapTh('Date Reported');
		$heading .=  wrapTh('Done By');
		$heading .= wrapTh('Qty Units');
		$heading .=  wrapTh('Qty');
		$heading .=  wrapTh('Unit Cost');
		$heading .=  wrapTh('Cost Est');
		$heading .= wrapTh('Paid To');
		//$heading .=  wrapTh('Cost Actual');
		$heading .=  wrapTh('Links');		
		$list .=  wrapTr($heading);
		
		
		$sql = $this->sql->listMaterialsByTask($this->task->id,$this->resultPage,$this->perPage,$this->year,$this->month);		
		
		$result = dbGetResult($sql);
		if ($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$m = new Material;
			$m->id = $row["id"];
			$m->task->id = $row["task_id"];
			$m->task->name = $row["task_name"];
			$m->locationId = $row["location_id"];
			$m->name = ($row["name"]);
			$m->typeName = ($row["type_name"]);
			//$m->description = ($row["description"]);
			$m->dateReported = $row["date_reported"];
			$m->doneBy = ($row["done_by"]);
			$m->paidTo = ($row["paid_to"]);
			$m->updated = $row["updated"];	
			$m->quantityUnitMeasureName = ($row["qty_unit_measure_name"]);
			$m->quantity = $row["quantity"];	
			$m->costEstimated = $row["cost_estimated"];						
			$m->costActual = $row["cost_actual"];
			$m->costUnit = $row["cost_unit"];
			//$m->notes = ($row["notes"]);
			$m->linkText = ($row["link_text"]);
			$m->linkUrl = ($row["link_url"]);
			$cssItem = ($row["highlight_style"]);

			$m->formatForDisplay();
			
			$detail = '';
			if ($this->displayProject == 'PROJECT'){
				$link = $taskL->detailViewEditHref($m->task->id, $m->task->name);
				$detail = wrapTd($link);
			}
			$link = $materialL->detailViewEditHref($m->id,$m->name);
			$detail .= wrapTd($link);
			$detail .= wrapTd($m->typeName);			
			$detail .= wrapTd($m->dateReported);
			$detail .= wrapTd($m->doneBy);
			$detail .= wrapTd($m->quantityUnitMeasureName);
			$detail .= wrapTd($m->quantity);
			$detail .= wrapTd($m->costUnit);
			$detail .= wrapTd($m->costEstimated);
			$detail .= wrapTd($m->paidTo);
			//$detail .= wrapTd($m->costActual);
			if ($m->linkText != '' && $m->linkUrl != ''){
				$link = $materialL->formatHref($m->linkText,$m->linkUrl,'_blank');
				$detail .= wrapTd($link);
			} else {
				$detail .= wrapTd(spacer());
			}

			$list .=  wrapTr($detail, $cssItem);
		}
		$result->close();
		}

		$list .= closeDisplayList();
		
		
		return $list;
		
	
	}
	
	
		
	public function getListing($pagingBaseLink = 'USE_LISTING'){
//printLine(this->year.$this->month);

		if ($this->displayProject == 'PROJECT-SUMMARY'){
			$list = $this->getSummaryProjectMonthly($pagingBaseLink);			
		} elseif ($this->displayProject == 'PROJECT'){ 
			$list = $this->getListingProject($pagingBaseLink);		
		} elseif ($this->displayProject == 'TASK-SUMMARY'){ 
			$list = $this->getSummaryTaskMonthly($pagingBaseLink);		
		} else  {  //if ($this->displayProject == 'TASK'){
			$list = $this->getListingTask($pagingBaseLink);
		}
		
		return $list;
	}
}


class Material {
    public $id = 0;
    public $locationId = 0;	
	public $typeId = 0;
	public $typeName = '';
	public $name;
    public $description;
    public $dateReported;
	public $doneBy;
	public $paidTo;
    public $updated;
    public $quantity = 1;	
	public $quantityUnitMeasureId = 0;
	public $quantityUnitMeasureName = '';
    public $costUnit = 0;	
	public $costEstimated = 0;
	public $costActual = 0;
    public $notes;
	public $linkUrl;
	public $linkText;
	public $task;
	private $sql;
	// property to support edit/view/add mode of calling page
    public $pageMode;

	public function __construct(){
		$this->task = new Task;
		$this->sql = new MaterialSQL;
	}


	public function setDetails($detailMaterialId, $parentTaskId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailMaterialId;
		$this->task->id = $parentTaskId;
		
		$sql = $this->sql->infoMaterial($this->id);

		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$this->task->id = $row["task_id"];
			$this->locationId = $row["location_id"];
			$this->typeId = $row["type_id"];
			$this->typeName = ($row["type_name"]);
			$this->name = ($row["name"]);
			$this->description = ($row["description"]);
			$this->dateReported = $row["date_reported"];
			$this->doneBy = stripslashes($row["done_by"]);
			$this->paidTo = stripslashes($row["paid_to"]);
			$this->updated = $row["updated"];	
			$this->quantity = $row["quantity"];	
			$this->quantityUnitMeasureId = $row["qty_unit_measure_id"];
			$this->quantityUnitMeasureName = stripslashes($row["qty_unit_measure_name"]);
			$this->costEstimated = $row["cost_estimated"];						
			$this->costActual = $row["cost_actual"];
			$this->costUnit = $row["cost_unit"];
			$this->notes = ($row["notes"]);
			$this->linkText = ($row["link_text"]);
			$this->linkUrl = ($row["link_url"]);
			
		}
		$result->close();
		}

		$this->setParentTask();				
	}	
	
	public function setParentTask(){
		$this->task->setDetails($this->task->id, 0, $this->pageMode);
	}
		
	function pageTitle(){	
		$title = openDiv('section-heading-title','none');
		//show page mode for debugging
		//$heading .= '['.$this->pageMode.']';
		$title .= $this->task->project->name.br();
		$title .= 'Task: '.$this->task->name;		
		$title .= closeDiv();
		return $title;
		
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projects = new ProjectLinks($menuType,$menuStyle);
		$tasks = new TaskLinks($menuType,$menuStyle);
		$materials = new MaterialLinks($menuType,$menuStyle);
					
		$menu = $projects->openMenu('section-heading-links');

		$menu .= $projects->detailViewHref($this->task->project->id);
		if ($this->task->project->materialsCount > 0){
//			$menu .= $projects->resetMenu();
			$menu .= $materials->listingHref(0,'ProjectMaterials',$this->task->project->id,'PROJECT','YES');
		}
		
		$menu .= $projects->resetMenu();
		$menu .= $tasks->detailViewHref($this->task->id);
		if ($this->task->materialCount > 0){
			$menu .= $materials->listingHref($this->task->id,'TaskMaterials',0,'TASK','NO');
		}
		$menu .= $projects->resetMenu();
		
		if ($this->pageMode == 'VIEW'){
			$menu .= $materials->detailEditHref($this->id);
		} elseif ($this->pageMode == 'EDIT'){
			
			$menu .= $materials->detailViewHref($this->id);
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
		$this->notes = displayLines($this->notes);
		$this->dateReported = getTimestampDate($this->dateReported);
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
		
		$detail = openDisplayDetails('material','Material Details');

		$detail .= captionedParagraph('name','Material',$this->name);
		$detail .= captionedParagraph('type-name','Type',$this->typeName);
		$detail .= captionedParagraph('description','Description',$this->description);
		
		$detail .= captionedParagraph('reported','Reported',$this->dateReported);
		$detail .= captionedParagraph('doneBy','Done By', $this->doneBy);
		$detail .= captionedParagraph('paidTo','Paid To', $this->paidTo);
		$detail .= captionedParagraph('updated','Updated',$this->updated);	
		$detail .= captionedParagraph('quantity-units','Quantity Units',$this->quantityUnitMeasureName);	
		$detail .= captionedParagraph('quantity','Quantity',$this->quantity);
		$detail .= captionedParagraph('cost-unit','Cost Unit',$this->costUnit);
		$detail .= captionedParagraph('cost-estimated','Cost Estimated',$this->costEstimated);
		$detail .= captionedParagraph('cost-actual','Cost Actual',$this->costActual);
		if ($this->linkText != '' && $this->linkUrl != ''){
			$l = new MaterialLinks('DIV','menu');
			$link = $l->formatHref($this->linkText,$this->linkUrl,'_blank');
			$detail .= captionedParagraph('a-weblink','Web Link',$link);
		}		
 		$detail .= captionedParagraph('notes','Notes',$this->notes);

		$detail .= closeDisplayDetails();

		
		return $detail;
	}	
	
	private function setAddRecordDefaults(){
		$this->locationId = $this->task->locationId;
		$this->quantity = 1;
		$this->quantityUnitMeasureId = 0;
		$this->costUnit = 0;
		$this->costEstimated = 0;
		$this->costActual = 0;		

		if (isset($_SESSION['last-material-date'])){
			 $this->dateReported = $_SESSION['last-material-date'];
		} else {
			global $sessionTime;
			$this->dateReported = $sessionTime;				
		}

		if (isset($_SESSION['last-material-paid-to'])){
			 $this->paidTo = $_SESSION['last-material-paid-to'];
		}
		
		if (isset($_SESSION['last-material-done-by'])){
			 $this->doneBy = $_SESSION['last-material-done-by'];
		} else {
			$this->doneBy = $_SESSION['login-name'];
		}

	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
		}
		
		if ($this->pageMode == 'ADD'){
			$legendText = 'Add Material';
		} else if ($this->pageMode == 'EDIT'){
			$legendText =  'Edit Material';
		} else {
			$legendText = 'Material Details';
		}
		
		$entity = 'material';
		$c = new MaterialLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legendText,'pr_Material_Save.php',$contextMenu);

		$fields = inputFieldName($entity,$this->name,'name','Material');

		//done by user
		$fields .= inputFieldUser($entity,$this->doneBy,'doneBy','Done By');

		//paid to
		$fields .= inputFieldUser($entity,$this->paidTo,'paidTo','Paid To');

		

		
		//quantity
		$fields .= inputFieldNumber($entity,$this->quantity,'quantity','Quantity');
		
		//unit cost
		$fields .= inputFieldNumber($entity,$this->costUnit,'costUnit','Unit Cost');

		//quantity unit type
		$u = new UnitOfMeasure;
		$select = $u->getUnitOfMeasureSelectList($this->quantityUnitMeasureId,'quantityUnitMeasureId','false',false);
		$fields .= captionedInput('Quantity Units',$select);

		$m = new MaterialType;
		$select = $m->getMaterialTypeSelectList($this->typeId,'typeId','false',false);
		$fields .= captionedInput('Material Type',$select);


		//date of materials purchase
		$fields .= inputFieldTimestamp($entity, 'dateReported', $this->dateReported, 'Reported'); 		
		
		
		$formRequired = $fields;
		
		//estimated cost
		$tooltip = 'Based on unit cost and quantity';
		$fields = inputFieldNumber($entity,$this->costEstimated,'costEstimated','Estimate',$tooltip,'true');

		//estimated cost
		$tooltip = 'Set when task costs are approved';
		$fields .= inputFieldNumber($entity,$this->costActual,'costActual','Actual',$tooltip,'true');

		$fields .= inputGroupWebLink($entity,$this->linkText,$this->linkUrl);
		
		$fields .= inputFieldDescription($entity,$this->description,'description');
		
		$fields .= inputFieldNotes($entity,$this->notes,'notes');

		$formOptional = $fields;

		//hidden fields and submit,reset buttons
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('taskId', $this->task->id);
		$hidden .= getHiddenInput('materialId', $this->id);
		$hidden .= getHiddenInput('locationId', $this->locationId);

		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
			
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);	

		return $form;
	}
	
	private function setEstimatedCost(){
			$this->costEstimated = $this->costUnit * $this->quantity;
	}
	
	public function collectPostValues(){
		//called by save form prior to running adds/updates
		$this->pageMode = $_POST['mode'];
		
		$this->task->id = $_POST['taskId'];
		$this->id = $_POST['materialId'];
		$this->locationId = $_POST['locationId'];
		$this->typeId = $_POST['typeId'];
		$this->name = dbEscapeString($_POST['name']);
		$this->description = dbEscapeString($_POST['description']); 
		$this->notes = dbEscapeString($_POST['notes']); 
		$this->dateReported = getTimestampPostValues('dateReported');
		$this->doneBy = dbEscapeString($_POST['doneBy']);
		$this->paidTo = dbEscapeString($_POST['paidTo']);
		$_SESSION['last-material-paid-to'] = $this->paidTo;
		$_SESSION['last-material-done-by'] = $this->doneBy;
		$_SESSION['last-material-date'] = $this->dateReported;
		$this->quantity = $_POST['quantity']; 
		$this->quantityUnitMeasureId = $_POST['quantityUnitMeasureId'];
		$this->costUnit = $_POST['costUnit']; 
		//$this->costEstimated = $_POST['costEstimated']; 
//		$this->costActual = $_POST['costActual']; 
		$this->linkText = dbEscapeString($_POST['linkText']);
		$this->linkUrl = dbEscapeString($_POST['linkUrl']);
		
		$this->setEstimatedCost();

		$this->setParentTask();
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			
			$sql = " UPDATE materials m ";
			$sql .= " SET ";
			$sql .= " m.name = '".$this->name."', ";
			$sql .= " m.description = '".$this->description."', ";
			$sql .= " m.notes = '".$this->notes."', ";
			$sql .= " m.updated = CURRENT_TIMESTAMP, ";
			$sql .= " m.date_reported = '".$this->dateReported."', ";
			$sql .= " m.done_by = '".$this->doneBy."', ";
			$sql .= " m.paid_to = '".$this->paidTo."', ";			
			$sql .= " m.link_text = '".$this->linkText."', ";
			$sql .= " m.link_url = '".$this->linkUrl."', ";
			$sql .= " m.type_id = ".$this->typeId.", ";
			$sql .= " m.location_id = ".$this->locationId.", ";
			$sql .= " m.quantity = ".$this->quantity.", ";
			$sql .= " m.qty_unit_measure_id = ".$this->quantityUnitMeasureId.", ";
			$sql .= " m.cost_unit = ".$this->costUnit.", ";
			$sql .= " m.cost_estimated = ".$this->costEstimated." ";
			//$sql .= " m.cost_actual = ".$this->costActual." ";
			$sql .= " WHERE m.id = ".$this->id." ";

			$result = dbRunSQL($sql);
			
			$this->task->resetMaterialsAuthorization();
			
		} else {
	
			$sql = " INSERT INTO materials ";
			$sql .= " (name, ";
			$sql .= " description, ";
			$sql .= " task_id, ";
			$sql .= " location_id, ";
			$sql .= " date_reported, ";
			$sql .= " done_by, ";
			$sql .= " paid_to, ";
			$sql .= " updated, ";
			$sql .= " link_url, ";
			$sql .= " link_text, ";			
			$sql .= " quantity, ";
			$sql .= " cost_unit, ";
			$sql .= " cost_estimated, ";
			//$sql .= " cost_actual, ";
			$sql .= " type_id, ";
			$sql .= " qty_unit_measure_id, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= "'".$this->description."', ";
			$sql .= "".$this->task->id.", ";
			$sql .= "".$this->locationId.", ";
			$sql .= " '".$this->dateReported."', ";
			$sql .= " '".$this->doneBy."', ";
			$sql .= " '".$this->paidTo."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= " '".$this->linkUrl."', ";
			$sql .= " '".$this->linkText."', ";						
			$sql .= "".$this->quantity.", ";
			$sql .= "".$this->costUnit.", ";
			$sql .= "".$this->costEstimated.", ";
			//$sql .= "".$this->costActual.", ";
			$sql .= " ".$this->typeId.", ";
			$sql .= " ".$this->quantityUnitMeasureId.", ";
			$sql .= "'".$this->notes."') ";
			
			$result = dbRunSQL($sql);
			
			$this->id = dbInsertedId();
			$this->task->resetMaterialsAuthorization();
		}
	
	}
	
} 

class MaterialSQL{
function columnsMaterials(){
$c = " m.id, ";
$c .= " m.task_id, ";
$c .= " t.task_order, ";
$c .= " t.name task_name, ";
$c .= " m.location_id, ";
$c .= " m.name, ";
$c .= " m.description, ";
$c .= " m.date_reported, ";
$c .= " m.done_by, ";
$c .= " m.paid_to, ";
$c .= " m.updated, ";
$c .= " m.quantity, ";
$c .= " m.qty_unit_measure_id, ";
$c .= " uom.name qty_unit_measure_name, ";
$c .= " m.cost_unit, ";
$c .= " m.cost_estimated, ";
$c .= " m.cost_actual, ";
$c .= " m.type_id, ";
$c .= " mt.name type_name, ";
$c .= " mt.highlight_style, ";
$c .= " m.link_text, ";
$c .= " m.link_url, ";
$c .= " m.notes ";
return $c;	
}
public function infoMaterial($materialId){
$q = " SELECT ";	
$q .= $this->columnsMaterials();
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN material_types AS mt ON m.type_id = mt.id ";
$q .= " LEFT OUTER JOIN units_of_measure uom ON m.qty_unit_measure_id = uom.id ";
$q .= " WHERE  ";
$q .= " m.id = ".$materialId." ";
return $q;
}
public function listMaterialsByTask($selectedTaskId,$resultPage, $rowsPerPage,$year = 0,$month = 0){
$q = " SELECT ";	
$q .= $this->columnsMaterials();
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN material_types AS mt ON m.type_id = mt.id ";
$q .= " LEFT OUTER JOIN units_of_measure uom ON m.qty_unit_measure_id = uom.id ";
$q .= " WHERE  ";
$q .= " m.task_id = ".$selectedTaskId." ";
if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}
$q .= " ORDER BY ";
$q .= " date(m.date_reported) desc, m.name ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}
public function countMaterialsByTask($selectedTaskId,$year = 0,$month = 0){
$q = " SELECT  ";
$q .= " COUNT(*) total_materials";
$q .= " FROM materials AS m ";
$q .= " WHERE  ";
$q .= " m.task_id = ".$selectedTaskId." ";
if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}

return $q;
}
public function summarizeMaterialByTask($selectedTaskId,$year = 0,$month = 0){
$q = " SELECT  ";
$q .= " COUNT(*) total_materials, ";
$q .= " SUM(m.cost_estimated) sum_cost_estimated, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM materials AS m ";
$q .= " WHERE  ";
$q .= " m.task_id = ".$selectedTaskId." ";
if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}

return $q;
}

public function listMaterialSummaryByProject($projectId, $year=0, $month=0){
$q = " SELECT  ";
$q .= " IFNULL(mt.name, 'Unspecified') material_type, ";
$q .= " COUNT(*) total_materials, ";
$q .= " SUM(m.cost_estimated) sum_cost_estimated, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN material_types AS mt ON m.type_id = mt.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";

if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}
$q .= " AND t.materials_auth_project = 'yes' ";

$q .= " GROUP BY material_type ";

return $q;
}

public function listMaterialSummaryByTask($taskId,$projectId,$year=0, $month=0){
$q = " SELECT  ";
$q .= " IFNULL(mt.name, 'Unspecified') material_type, ";
$q .= " COUNT(*) total_materials, ";
$q .= " SUM(m.cost_estimated) sum_cost_estimated, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN material_types AS mt ON m.type_id = mt.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";
$q .= " AND t.id = ".$taskId." ";
if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}

$q .= " GROUP BY material_type ";

return $q;
}

public function listMaterialSummaryByTaskDoneBy($taskId,$projectId,$year=0, $month=0){
$q = " SELECT  ";
$q .= " IF(m.done_by='', 'Unspecified', m.done_by) done_by, ";
$q .= " COUNT(*) total_materials, ";
$q .= " SUM(m.cost_estimated) sum_cost_estimated, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN material_types AS mt ON m.type_id = mt.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";
$q .= " AND t.id = ".$taskId." ";
if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}

$q .= " GROUP BY done_by ";

return $q;
}
public function listMaterialSummaryByProjectDoneBy($projectId,$year=0, $month=0){
$q = " SELECT  ";
$q .= " IF(m.done_by='', 'Unspecified', m.done_by) done_by, ";
$q .= " COUNT(*) total_materials, ";
$q .= " SUM(m.cost_estimated) sum_cost_estimated, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";
if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}
$q .= " AND t.materials_auth_project = 'yes' ";
$q .= " GROUP BY done_by ";

return $q;
}


public function listMaterialsByProject($projectId,$resultPage, $rowsPerPage,$year = 0,$month = 0){
$q = " SELECT ";	
$q .= $this->columnsMaterials();
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN material_types AS mt ON m.type_id = mt.id ";
$q .= " LEFT OUTER JOIN units_of_measure uom ON m.qty_unit_measure_id = uom.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";

if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}

$q .= " AND t.materials_auth_project = 'yes' ";
$q .= " ORDER BY ";
$q .= " date(m.date_reported) desc, t.task_order, m.name ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}
public function countMaterialsByProject($projectId, $year = 0,$month = 0){
$q = " SELECT  ";
$q .= " COUNT(*) total_materials";
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";

$q .= " AND t.materials_auth_project = 'yes'";

if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}

return $q;
}
public function summarizeMaterialByProject($projectId,$year = 0,$month = 0){
$q = " SELECT  ";
$q .= " COUNT(*) total_materials, ";
$q .= " SUM(m.cost_estimated) sum_cost_estimated, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM materials AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";
$q .= " AND t.materials_auth_project = 'yes'";

if ($year > 0){
	$q .= " AND year(m.date_reported)  = ".$year." ";	
}
if ($month > 0){
	$q .= " AND MONTH(m.date_reported)  = ".$month." ";	
}


return $q;
}

public function calendarLinksProjectMaterials($projectId){
  $q = "SELECT ";
  $q .= " MONTH(a.date_reported) month, ";
  $q .= " YEAR(a.date_reported) year ";
  $q .= " FROM projects p JOIN tasks t ON p.id = t.project_id  ";
  $q .= " JOIN materials a ON t.id = a.task_id  ";
  $q .= " WHERE p.id = ".$projectId." ";
  $q .= " AND t.materials_auth_project = 'yes'";
  $q .= " GROUP BY  ";
  $q .= " YEAR(a.date_reported), ";
  $q .= " MONTH(a.date_reported) ";
  return $q;
}
public function calendarLinksTaskMaterials($projectId,$taskId){
  $q = "SELECT ";
  $q .= " MONTH(m.date_reported) month, ";
  $q .= " YEAR(m.date_reported) year ";
  $q .= " FROM projects p JOIN tasks t ON p.id = t.project_id  ";
  $q .= " JOIN materials m ON t.id = m.task_id  ";
  $q .= " WHERE p.id = ".$projectId." ";
  $q .= " AND t.id = ".$taskId." ";
  $q .= " GROUP BY  ";
  $q .= " YEAR(m.date_reported), ";
  $q .= " MONTH(m.date_reported) ";
  return $q;
}


}
?>
