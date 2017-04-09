<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class ActivityLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($taskId,$caption = 'Activities',$myActivity='NO',$showCalendar = 'NO',$projectId = 0){
		$link = $this->listing($taskId,$myActivity,$showCalendar,$projectId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $activityId = 0, $taskId = 0, $caption = 'Activity'){
		$link = $this->detail($pageAction,$activityId,$taskId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	public function listing($taskId, $myActivity = 'NO',$showCalendar = 'NO',$projectId = 0){
		$link = 'pr_Activity_List.php?taskId='.$taskId;
		if ($myActivity != 'NO'){
			$link .= '&myActivity='.$myActivity;
		}
		if ($showCalendar != 'NO'){
			$link .= '&showCalendar='.$showCalendar;
		}
		if ($projectId != 0){
			$link .= '&projectId='.$projectId;
		}
		return $link;
	} 
	public function listingPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&resultsPageActivity=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}

	public function detail($pageAction, $activityId, $taskId = 0){
		$link = 'pr_Activity_Detail.php?pageAction='.$pageAction;
		if($taskId != 0){
			$link .= '&taskId='.$taskId;			
		}
		if ($activityId != 0){
			$link .= '&activityId='.$activityId;
		}
		return $link;
	}	
	public function detailAddHref($taskId,$caption = '+Activity'){
		$l = $this->detailHref('ADD',0,$taskId,$caption);
		return $l;	
	}
	public function detailViewHref($activityId,$caption = 'ViewActivity'){
		$l = $this->detailHref('VIEW',$activityId,0,$caption);
		return $l;	
	}
	public function detailEditHref($activityId,$caption = 'EditActivity'){
		$l = $this->detailHref('EDIT',$activityId,0,$caption);
		return $l;	
	}
	public function detailViewEditHref($activityId = 0, $viewCaption = 'Activity'){
		
		if ($activityId != 0){
			$links = $this->detailViewHref($activityId,$viewCaption);
			$links .= $this->detailEditHref($activityId,'#');
		}
		return $links;
	}	
	public function linkMyActivities(){
		return $this->listingHref(-1,'My History','YES');		
	}
	public function linkGroupActivities(){
		return $this->listingHref(-1,'Group History','GROUP');
	}
	public function linkMyCalendar(){
		return $this->listingHref(-1,'My Calendar','YES','YES');
	}
	public function linkGroupCalendar(){
		return $this->listingHref(-1,'Group Calendar','GROUP','YES');
	}
	public function linkProjectCalendar($projectId){
		return $this->listingHref(-1,'Calendar','PROJECT','YES',$projectId);		
	}
	public function linkProjectActivities($projectId){
		return $this->listingHref(-1,'Project History','PROJECT','NO',$projectId);		
	}

}
class ActivityList{
	public $myActivity = 'NO';
	public $showCalendar = 'NO';
	public $month;
	public $year;
	private $prevCalendarLink = '';
	private $nextCalendarLink = '';
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $task;
	private $sql;
	
	public function __construct(){
		$this->task = new Task;
		$this->sql = new ActivitySQL;	
	}

	public function setDetails($taskId, $page = 1, $perPage = 10,$myActivity = 'NO',$showCalendar='NO',$projectId = 0){
		$this->task->id = $taskId;
		$this->task->project->id = $projectId;	
		$this->resultPage = $page;
		$this->perPage = $perPage;
		$this->myActivity = $myActivity;
		$this->showCalendar = $showCalendar;
		$this->task->setDetails($this->task->id, $this->task->project->id, 'VIEW');
		$this->setFoundCount();
	}

	public function setCalendarRange($year, $month){

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
		if ($this->myActivity == 'NO'){
			$title .= $this->task->project->name.br();
			$title .= 'Task: '.$this->task->name;
		} elseif ($this->myActivity == 'GROUP') {
			$title .= 'Group Activity History';
		} elseif ($this->myActivity == 'YES') {
			$title .= $_SESSION['login-name'].spacer(4).'Activity History';
		} elseif ($this->myActivity == 'PROJECT') {
			$title .= 'All Project Activity:'.spacer(2).$this->task->project->name;
		}
		$title .= closeDiv();
		return $title;	
	}	

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projectL = new ProjectLinks($menuType,$menuStyle);
		$taskL = new TaskLinks($menuType,$menuStyle);
		$activityL = new ActivityLinks($menuType,$menuStyle);
					
		$menu = $activityL->openMenu('section-heading-links');
		
		if ($this->myActivity == 'NO' && $this->showCalendar == 'NO'){
		//normal task activity list menu
			$menu .= $activityL->detailAddHref($this->task->id);
			$menu .= $activityL->listingHref($this->task->id);
			$menu .= $activityL->resetMenu();	
			$menu .= $taskL->detailViewHref($this->task->id);
			$menu .= $projectL->detailViewHref($this->task->project->id);				

		} elseif ($this->myActivity == 'YES' || $this->myActivity == 'GROUP') {			
			$menu .= $activityL->linkMyCalendar();
			$menu .= $activityL->linkGroupCalendar();
			$menu .= $activityL->resetMenu();
			$menu .= $activityL->linkMyActivities();
			$menu .= $activityL->linkGroupActivities();
		} elseif ($this->myActivity == 'PROJECT') {
			//showing project activities
			$menu .= $projectL->detailViewHref($this->task->project->id);
			$menu .= $projectL->listingHref('OPEN','Projects');
			$menu .= $activityL->resetMenu();				
			$menu .= $activityL->linkProjectCalendar($this->task->project->id);									
			$menu .= $activityL->linkProjectActivities($this->task->project->id);
			$menu .= $activityL->resetMenu();
			$menu .= $taskL->detailAddHref($this->task->project->id);
		}
		
		$menu .= $activityL->closeMenu();	
		return $menu;			
	}	
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	

	private function getActivityDoneBy(){
			if ($this->myActivity == 'YES'){
				$doneBy = $_SESSION['login-name'];
			} elseif ($this->myActivity == 'GROUP') {
				$doneBy = 'EVERYONE';
			}
			return $doneBy;		
	}

	private function setFoundCount(){
		if ($this->myActivity == 'NO'){
			$sql = $this->sql->countActivityByTask($this->task->id);
		} else {
			if ($this->myActivity == 'YES' || $this->myActivity == 'GROUP'){
				$sql = $this->sql->countActivityByDoneBy($this->getActivityDoneBy());
			} elseif ($this->myActivity == 'PROJECT') {
				$sql = $this->sql->countProjectActivity($this->task->project->id);
			}
		}
		$this->found = getSQLCount($sql, 'total_activities');
	}

	private function getCalendarLinks(){
		$l = new ActivityLinks('DIV','paged');
		$baseUrl = $l->listing(-1,$this->myActivity,$this->showCalendar,$this->task->project->id);
		$links = $l->openMenu('calendar-links');
				
		if ($this->myActivity == 'PROJECT') {
			$sql = $this->sql->calendarLinksProjectActivity($this->task->project->id);
		} else {
			$sql = $this->sql->calendarLinksMyActivity($this->getActivityDoneBy());			
		}
		$result = mysql_query($sql) or die(mysql_error());
		$this->prevCalendarLink = '';
		$this->nextCalendarLink = '';
		$foundCurrent = false;
		$foundNext = false;
		while($row = mysql_fetch_array($result))
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
		mysql_free_result($result);
		$links .= $l->closeMenu();
		return $links;
	}

	public function getProjectActivityCalendar(){
		$pl = new ProjectLinks();
		$tl = new TaskLinks();
		$title = 'Project Activities';

		$cal = new _Calendar($this->year,$this->month,$title);
		$sql = $this->sql->calendarSummaryProjectActivity($this->task->project->id,$this->year,$this->month);
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{	
			$sumHours = $row["sum_hours"];
			$started = $row["started"];
			$taskId = $row['id'];
			$taskName = stripslashes($row['name']);
			$doneBy   = stripslashes($row['done_by']);
			//highlight scheduled but incomplete activities
			if ($sumHours == 0){
				$highlightStyle = 'highlight-yellow';
			} else {
				$highlightStyle = $row['highlight_style'];
			}
			$taskLink = $tl->detailViewHref($taskId,$taskName);

			$calendarItem = $doneBy.spacer().$taskLink;
			//	$calendarItem = $sumHours.spacer().$taskLink;
			$cal->addItemByTimestamp($started,$calendarItem,$highlightStyle);
		}
		mysql_free_result($result);

		$content = openDiv('my-calendar');
		$links = $this->getCalendarLinks();	
		$content .= $links;		
		$cal->setLinks($this->nextCalendarLink, $this->prevCalendarLink);			
		$content .= $cal->buildCalendar();
		$content .= closeDiv();
		return $content;		
	}
	
	public function getGroupActivityCalendar(){
		$pl = new ProjectLinks();

		$title = 'Group Activities';

		$cal = new _Calendar($this->year,$this->month,$title);

		$sql = $this->sql->calendarSummaryMyActivity($this->getActivityDoneBy(),$this->year,$this->month);
		$result = mysql_query($sql) or die(mysql_error());

		while($row = mysql_fetch_array($result))
		{	
			$sumHours = $row["sum_hours"];
			$started = $row["started"];
			$projectId = $row['id'];
			$projectName = stripslashes($row['name']);
			$doneBy = $row['done_by'];
			if ($sumHours == 0){
				$highlightStyle = 'highlight-yellow';
			} else {
				$highlightStyle = $row['highlight_style'];
			}
			//projectId = 0 is monthly summary record
			if ($projectId != 0){
				$projectLink = $pl->detailViewHref($projectId,$projectName);
				$calendarItem = $doneBy.spacer().$projectLink;				
			} else {
				$projectLink = $projectName;
				$calendarItem = $sumHours.spacer().$doneBy;				
			}
			
			$cal->addItemByTimestamp($started,$calendarItem,$highlightStyle);

		}
		mysql_free_result($result);

		$content = openDiv('group-calendar');
		$links = $this->getCalendarLinks();		
		$content .= $links;
		$cal->setLinks($this->nextCalendarLink, $this->prevCalendarLink);			
		$content .= $cal->buildCalendar();
		$content .= closeDiv();
		return $content;		
	}

	public function getMyActivityCalendar(){

		$pl = new ProjectLinks();
		$title = 'My Activities'.spacer(4).$this->getActivityDoneBy();
		$cal = new _Calendar($this->year,$this->month,$title);

		$sql = $this->sql->calendarSummaryMyActivity($this->getActivityDoneBy(),$this->year,$this->month);

		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{	
			$sumHours = $row["sum_hours"];
			$started = $row["started"];
			$projectId = $row['id'];
			$projectName = stripslashes($row['name']);
			$doneBy = $row['done_by'];
			if ($sumHours == 0){
				$highlightStyle = 'highlight-yellow';
			} else {
				$highlightStyle = $row['highlight_style'];
			}

			if ($projectId != 0){
				$projectLink = $pl->detailViewHref($projectId,$projectName);
			} else {
				$projectLink = $projectName;
			}
			$calendarItem = $sumHours.spacer().$projectLink;
			$cal->addItemByTimestamp($started,$calendarItem,$highlightStyle);

		}
		mysql_free_result($result);

		$content = openDiv('my-calendar');
		$links = $this->getCalendarLinks();		
		$content .= $links;
		$cal->setLinks($this->nextCalendarLink, $this->prevCalendarLink);					
		$content .= $cal->buildCalendar();
		$content .= closeDiv();
		return $content;		
	}

	public function getCalendar(){
	
		if ($this->myActivity == 'PROJECT'){
			$calendar = $this->getProjectActivityCalendar();
		} elseif ($this->myActivity == 'GROUP'){
			$calendar = $this->getGroupActivityCalendar();
		} else {
			$calendar = $this->getMyActivityCalendar();
		}
		return $calendar;
		
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
			$content = $this->getCalendar();
		} else {
			$content = $this->getListing();
		}
		return $content;
	}

	public function getListing($pagingBaseLink = 'USE_LISTING'){
	
		if ($this->myActivity == 'NO'){
			$sql = $this->sql->listActivityByTask($this->task->id,$this->resultPage,$this->perPage);
		} elseif ($this->myActivity == 'PROJECT'){
			$sql = $this->sql->listProjectActivity($this->task->project->id,$this->resultPage,$this->perPage);	
		} else {
			$sql = $this->sql->listActivityByDoneBy($this->getActivityDoneBy(),$this->resultPage,$this->perPage);			
		}
		$result = mysql_query($sql) or die(mysql_error());
				
		$activityL = new ActivityLinks('DIV','menu');
		$taskL = new TaskLinks('DIV','menu');
		$projectL = new ProjectLinks('DIV','menu');
		
		if ($pagingBaseLink == 'USE_LISTING'){
			$base = $activityL->listing($this->task->id,$this->myActivity,$this->showCalendar,$this->task->project->id);
		} else { 
			$base = $pagingBaseLink;
		}
		$pagingLinks = $activityL->listingPaged($base,$this->found,$this->resultPage,$this->perPage);				
		
			$a = new Activity;
			$a->setDetails(0,$this->task->id,'ADD');
			$a->task->project->setDetails($this->task->project->id,'VIEW');
			$quickEdit = $a->editForm('ActivityList.MyActivity='.$this->myActivity);
		
		$list = openDisplayList('activity','Activities', $pagingLinks, $quickEdit);

		$heading = '';
		if ($this->myActivity != 'NO'){
			if ($this->myActivity != 'PROJECT'){
				$heading .= wrapTh('Project');
			}
			$heading .= wrapTh('Task');
		}
		//$heading .=  wrapTh('Order');
		$heading .=  wrapTh('Done By');
		$heading .=  wrapTh('Started');
		$heading .=  wrapTh('Effort Actual');
		$heading .= wrapTh('Comments');
		$heading .=  wrapTh('Links');
		$list .=  wrapTr($heading);

		while($row = mysql_fetch_array($result))
		{	
			$a = new Activity;
			$a->id = $row["id"];
			$a->task->id = $row["task_id"];
			$a->task->name = stripslashes($row["task_name"]);
			$a->task->project->id = $row["project_id"];
			$a->task->project->name = stripslashes($row["project_name"]);
			//$a->order = $row["activity_order"];
			$a->doneBy = $row["done_by"];
			$a->started = $row["started"];
			$a->updated = $row["updated"];
			$a->hoursActual =$row["hours_actual"]; 			
			$a->comments = stripslashes($row["comments"]);
			$a->linkText = stripslashes($row["link_text"]);
			$a->linkUrl = stripslashes($row["link_url"]);
						
			$a->formatForDisplay();

			$detail = '';
			if ($this->myActivity != 'NO'){
				$taskLink = $taskL->detailViewHref($a->task->id,$a->task->name);
				$detail .=  wrapTd($taskLink);
				
				if ($this->myActivity != 'PROJECT'){
				$projectLink = $projectL->detailViewHref($a->task->project->id,$a->task->project->name);				
				$detail .=  wrapTd($projectLink);
				}
				$cssRow = $row['highlight_style'];
			} else {
				$cssRow = 'none';
			}
			if ($a->hoursActual == 0){
				$cssRow = 'highlight-yellow';
			} 
						
			$link = $activityL->detailViewEditHref($a->id,$a->doneBy);
			$detail .=  wrapTd($link);
//			$detail .=  wrapTd($a->doneBy);
			$detail .=  wrapTd($a->started);
			$detail .=  wrapTd($a->hoursActual);
			$detail .=  wrapTd($a->comments);
			
			if ($a->linkText != '' && $a->linkUrl != ''){
				$link = $activityL->formatHref($a->linkText,$a->linkUrl,'_blank');
				$detail .= wrapTd($link);
			} else {
				$detail .= wrapTd(spacer());
			}

			$list .=  wrapTr($detail,$cssRow);
		}
		mysql_free_result($result);

		$list .= closeDisplayList();
		return $list;		
	}
}

class Activity {
    public $id = 0;
	public $typeId = 0;
	public $doneBy;
    public $started;
    public $updated;
    //public $order = 0;	
    //public $hoursEstimated = 0;	
	public $hoursActual = 0;
    public $comments;
	public $linkUrl;
	public $linkText;
	public $task;	
	private $sql;
	
	// property to support edit/view/add mode of calling page
    public $pageMode;
	
	public function __construct(){
		$this->task = new Task;
		$this->sql = new ActivitySQL;
	}
	
	public function setDetails($detailActivityId, $parentTaskId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailActivityId;
		$this->task->id = $parentTaskId;
		
		$sql = $this->sql->infoActivity($this->id);
		$result = mysql_query($sql) or die(mysql_error());

		while($row = mysql_fetch_array($result)){
	
			$this->task->id = $row["task_id"];
			$this->typeId = $row["type_id"];
			$this->doneBy = stripslashes($row["done_by"]);
			$this->started = $row["started"];
			$this->updated = $row["updated"];	
			//$this->order = $row["activity_order"];	
			//$this->hoursEstimated = $row["hours_estimated"];						
			$this->hoursActual = $row["hours_actual"];						
			$this->comments = stripslashes($row["comments"]);
			$this->linkText = stripslashes($row["link_text"]);
			$this->linkUrl = stripslashes($row["link_url"]);
		}
		mysql_free_result($result);

		$this->setParentTask();				
	}	
	
	public function setParentTask(){

		$this->task->setDetails($this->task->id, $this->task->project->id, $this->pageMode);
		
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
		$activities = new ActivityLinks($menuType,$menuStyle);
					
		$menu = $projects->openMenu('section-heading-links');

		$menu .= $tasks->detailViewHref($this->task->id);
		$menu .= $projects->detailViewHref($this->task->project->id);
		
		$menu .= $projects->resetMenu();
		if ($this->pageMode == 'VIEW'){
			$menu .= $activities->detailEditHref($this->id);
		}		
		
		if ($this->pageMode == 'EDIT'){
			$menu .= $activities->detailViewHref($this->id);
		}
		//$menu .= $activities->listingHref($this->task->id);

		$menu .= $projects->closeMenu();
		return $menu;
	}
		
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function formatForDisplay(){
		$this->doneBy = displayLines($this->doneBy);
		$this->comments = displayLines($this->comments);		
		$this->started = getTimestampDate($this->started);
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
		
		$detail = openDisplayDetails('activity','Activity Details');
		
		//$detail .= captionedParagraph('a-order','Order',$this->order);
		$detail .= captionedParagraph('a-started','Started',$this->started);
		$detail .= captionedParagraph('a-updated','Updated',$this->updated);		
		$detail .= captionedParagraph('a-done-by','Done By',$this->doneBy);
		//$detail .= captionedParagraph('a-h-est','Estimated',$this->hoursEstimated);
		$detail .= captionedParagraph('a-h-actual','Actual',$this->hoursActual);
 		$detail .= captionedParagraph('a-comments','Comments',$this->comments);
		if ($this->linkText != '' && $this->linkUrl != ''){
			$l = new ActivityLinks('DIV','menu');
			$link = $l->formatHref($this->linkText,$this->linkUrl,'_blank');
			$detail .= captionedParagraph('a-weblink','Web Link',$link);
		}
		$activityType = new ActivityType;
		$input = $activityType->getActivityTypeSelectList($this->typeId,'typeId','true');
	
		$detail .= closeDisplayDetails();	
		
		return $detail;
	}	
	
	public function setAddRecordDefaults(){
		global $sessionTime;
		$this->started = $sessionTime;	
		$this->order = $this->task->activityCount + 1;
		//$this->hoursEstimated = $this->task->hoursEstimated;
		$this->hoursActual = $this->task->hoursEstimated;
		$this->doneBy = $_SESSION['login-name'];
		$this->typeId = 0;
	}
	
	public function editForm($editContext = 'ActivityDetail'){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Activity';	
		} else if ($this->pageMode == 'EDIT'){
			$legend =  'Edit Activity';
		} else {
			$legend = 'View Activity';
		}
		$entity = 'activity';
		$c = new ActivityLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'pr_Activity_Save.php',$contextMenu);
		
		$_ptId = $this->task->project->typeId;
		$_pId = $this->task->project->id;
		$_tId = $this->task->id;		
		//$form .= $_ptId.'.'.$_pId.'.'.$_tId;

		//project type
		$pt = new ProjectType;
		$pt->setDetails($_ptId,'VIEW');
		$changeJs = "ajaxRefresh('PROJECTS_BY_TYPE_SELECT',this,'projectId');";
		$input = $pt->getProjectTypeSelectList($_ptId,'projectTypeId','false',false,$changeJs);
		$fields = captionedInput('Project Type',$input);
				
		//project
		$changeJs = "ajaxRefresh('PROJECT_TASKS_SELECT',this,'taskId');";
		$input = $this->task->project->getProjectSelectListByTypeId($_ptId,$_pId,'projectId','false',false,$changeJs);
		$fields .= captionedInput('Project',$input);

		$changeJs = "ajaxRefresh('TASK_EST_EFFORT',this,'activityHoursActual');";		
		$input = $this->task->getTaskSelectList($_tId,'taskId','false',false,$changeJs);
		$fields .= captionedInput('Task',$input);
		
		$tooltip = 'Hint: Set actual hours to 0 to create a scheduled activity';
		$fields .= inputFieldNumber($entity,$this->hoursActual,'activityHoursActual','Actual',$tooltip);


//$fields .= br();

		$fields .= inputFieldTimestamp($entity,'started',$this->started,'Started');

		$fields .= inputFieldUser($entity,$this->doneBy,'activityDoneBy','Done By');


		
		//activity weblink
		$fields .= inputGroupWebLink($entity,$this->linkText,$this->linkUrl);		

		$fields .= inputFieldComments($entity,$this->comments,'activityComments');

		$formRequired = $fields;



	
		$activityType = new ActivityType;
		$input = $activityType->getActivityTypeSelectList($this->typeId,'typeId','false',false);
		$fields = captionedInput('Activity Type',$input);

		//$tooltip = 'Task Estimated Hours Per Activity';
		//$fields .= inputFieldNumber($entity,$this->hoursEstimated,'activityHoursEstimated','Estimated',$tooltip,'true');

		//$fields .= inputFieldNumber($entity,$this->order,'activityOrder','Order');


		$formOptional = $fields;

		//hidden fields and submit,reset buttons
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('activityId', $this->id);
		$hidden .= getHiddenInput('editContext',$editContext);
		
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
			
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){
		//called by save form prior to running adds/updates
		$this->pageMode = $_POST['mode'];
		
		$this->task->id = $_POST['taskId'];
		$this->id = $_POST['activityId'];
		$this->typeId = $_POST['typeId'];
		//$this->order = $_POST['activityOrder']; 
		$this->started = getTimestampPostValues('started');
		
		$this->doneBy = mysql_real_escape_string($_POST['activityDoneBy']); 
		$this->comments = mysql_real_escape_string($_POST['activityComments']); 
		$this->hoursActual = $_POST['activityHoursActual']; 
		$this->linkText = mysql_real_escape_string($_POST['linkText']);
		$this->linkUrl = mysql_real_escape_string($_POST['linkUrl']);
		
		$this->setParentTask();
		$this->hoursEstimated = $this->task->hoursEstimated;
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){

			$sql = " UPDATE activities a ";
			$sql .= " SET ";
			$sql .= " a.done_by = '".$this->doneBy."', ";
			$sql .= " a.comments = '".$this->comments."', ";
			$sql .= " a.updated = CURRENT_TIMESTAMP, ";
			$sql .= " a.started = '".$this->started."', ";
			$sql .= " a.link_text = '".$this->linkText."', ";
			$sql .= " a.link_url = '".$this->linkUrl."', ";
			$sql .= " a.hours_actual = ".$this->hoursActual.", ";			
			//$sql .= " a.activity_order = ".$this->order.", ";
			$sql .= " a.type_id = ".$this->typeId.", ";
			$sql .= " a.task_id = ".$this->task->id." ";
			$sql .= " WHERE a.id = ".$this->id." ";

			$result = mysql_query($sql) or die(mysql_error());
			
			$this->task->updateActivitySummary();
			
		} else {
		
			$sql = " INSERT INTO activities ";
			$sql .= " ( ";
			//$sql .= " activity_order, ";
			$sql .= " task_id, ";
			$sql .= " type_id, ";
			$sql .= " done_by, ";
			$sql .= " started, ";
			$sql .= " updated, ";
			//$sql .= " hours_estimated, ";
			$sql .= " hours_actual, ";
			$sql .= " link_url, ";
			$sql .= " link_text, ";
			$sql .= " comments) ";
			$sql .= " VALUES (";
			//$sql .= " ".$this->order.", ";
			$sql .= " ".$this->task->id.", ";
			$sql .= " ".$this->typeId.", ";
			$sql .= " '".$this->doneBy."', ";
			$sql .= " '".$this->started."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			//$sql .= " ".$this->hoursEstimated.", ";
			$sql .= " ".$this->hoursActual.", ";
			$sql .= " '".$this->linkUrl."', ";
			$sql .= " '".$this->linkText."', ";			
			$sql .= " '".$this->comments."') ";
			
			$result = mysql_query($sql) or die(mysql_error());
			$this->id = mysql_insert_id();

			$this->task->updateActivitySummary();

		}
	
	}
	
} 
class ActivitySQL{
function columnsActivity(){
$c = " a.id,  ";
$c .= " a.task_id,  ";
$c .= " a.type_id, ";
$c .= " t.name task_name, ";
$c .= " t.project_id, ";
$c .= " p.name project_name, ";
$c .= " a.done_by,  ";
$c .= " a.started,  ";
$c .= " a.updated,  ";
//$c .= " a.activity_order,  ";
//$c .= " a.hours_estimated,  ";
$c .= " a.hours_actual,  ";
$c .= " a.comments, ";
$c .= " a.link_url, ";
$c .= " a.link_text, ";
$c .= " pt.highlight_style ";	
return $c;	
}

public function infoActivity($selectedActivityId){
$q = " SELECT  ";
$q .= $this->columnsActivity();
$q .= " FROM activities a JOIN tasks t ON a.task_id = t.id ";
$q .= " JOIN projects p ON t.project_id = p.id ";
$q .= " LEFT OUTER JOIN project_types pt ON p.type_id = pt.id ";
$q .= " WHERE  ";
$q .= " a.id = '".$selectedActivityId."' ";
return $q;
}

public function listActivityByTask($selectedTaskId, $resultPage, $rowsPerPage){
$q = " SELECT  ";
$q .= $this->columnsActivity();
$q .= " FROM activities a JOIN tasks t ON a.task_id = t.id ";
$q .= " JOIN projects p ON t.project_id = p.id ";
$q .= " LEFT OUTER JOIN project_types pt ON p.type_id = pt.id ";
$q .= " WHERE  ";
$q .= " a.task_id = '".$selectedTaskId."' ";
$q .= " ORDER BY ";
$q .= " a.started desc, a.activity_order, a.id ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}

public function countActivityByTask($selectedTaskId){
$q = " SELECT  ";
$q .= " COUNT(*) total_activities";
$q .= " FROM activities AS a ";
$q .= " WHERE  ";
$q .= " a.task_id = '".$selectedTaskId."' ";
return $q;
}

public function summarizeActivityByTask($selectedTaskId){
$q = " SELECT  ";
$q .= " COUNT(*) total_activities, ";
$q .= " SUM(a.hours_estimated) total_hours_estimated, ";
$q .= " SUM(a.hours_actual) total_hours_actual ";
$q .= " FROM activities AS a ";
$q .= " WHERE  ";
$q .= " a.task_id = '".$selectedTaskId."' ";
return $q;
}

public function listActivityByDoneBy($doneBy, $resultPage, $rowsPerPage){
$q = " SELECT  ";
$q .= $this->columnsActivity();
$q .= " FROM activities a JOIN tasks t on a.task_id = t.id ";
$q .= " JOIN projects p on t.project_id = p.id  ";
$q .= " LEFT OUTER JOIN project_types pt ON p.type_id = pt.id ";
$q .= " WHERE p.show_always != 'no' ";
if ($doneBy != 'EVERYONE'){
	$q .= " AND UPPER(a.done_by) = UPPER('".$doneBy."') ";	
} 
$q .= " ORDER BY ";
$q .= " a.started desc, a.id ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}

public function countActivityByDoneBy($doneBy){
$q = " SELECT  ";
$q .= " COUNT(*) total_activities  ";
$q .= " FROM activities a JOIN tasks t ON a.task_id = t.id  ";
$q .= " JOIN projects p ON t.project_id = p.id  ";
$q .= " WHERE p.show_always != 'no' ";
if ($doneBy != 'EVERYONE'){
	$q .= " AND UPPER(a.done_by) = UPPER('".$doneBy."') ";
}
return $q;
}

public function calendarSummaryMyActivity($doneBy, $year, $month){
$q = "SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " DATE(a.started) started, ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year, ";
$q .= " a.done_by, ";
$q .= " p.id, ";
$q .= " min(p.name) name, ";
$q .= " 1 ordering, ";
$q .= " min(pt.highlight_style) highlight_style ";
$q .= " FROM projects p JOIN tasks t ON p.id = t.project_id ";
$q .= " JOIN activities a ON t.id = a.task_id ";
$q .= " LEFT OUTER JOIN project_types pt ON p.type_id = pt.id ";
$q .= " WHERE p.show_always != 'no'  ";
if ($doneBy != 'EVERYONE'){
	$q .= " AND UPPER(a.done_by) = UPPER('".$doneBy."') ";
}
$q .= " AND MONTH(a.started) = '".$month."'  ";
$q .= " AND YEAR(a.started) = '".$year."'  ";
$q .= " GROUP BY  ";
$q .= " DATE(a.started), ";
$q .= " a.done_by, ";
$q .= " p.id ";
$q .= " UNION ALL ";
$q .= " SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " LAST_DAY(a.started) started, ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year, ";
$q .= " 'MonthTotal' done_by, ";
$q .= " 0 id, ";
$q .= " 'MonthTotal' name, ";
$q .= " 100 ordering, ";
$q .= " 'highlight-yellow' highlight_style ";
$q .= " FROM projects p JOIN tasks t ON p.id = t.project_id  ";
$q .= " JOIN activities a ON t.id = a.task_id  ";
$q .= " WHERE  ";
$q .= " p.show_always != 'no'  ";
if ($doneBy != 'EVERYONE'){
	$q .= " AND UPPER(a.done_by) = UPPER('".$doneBy."') ";
}
$q .= " AND MONTH(a.started) = '".$month."'  ";
$q .= " AND YEAR(a.started) = '".$year."'  ";
$q .= " GROUP BY ";
$q .= " LAST_DAY(a.started) ";
//$q .= " a.done_by ";
$q .= " ORDER BY started, ordering, id, done_by ";
return $q;	
}

public function calendarLinksMyActivity($doneBy){
$q = "SELECT ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year ";
$q .= " FROM projects p JOIN tasks t ON p.id = t.project_id  ";
$q .= " JOIN activities a ON t.id = a.task_id  ";
$q .= " WHERE p.show_always != 'no'  ";
if ($doneBy != 'EVERYONE'){
	$q .= " AND UPPER(a.done_by) = UPPER('".$doneBy."') ";
}
$q .= " GROUP BY  ";
$q .= " YEAR(a.started), ";
$q .= " MONTH(a.started) ";
return $q;
}

public function listProjectActivity($projectId, $resultPage, $rowsPerPage){
$q = " SELECT  ";
$q .= $this->columnsActivity();
$q .= " FROM activities a JOIN tasks t on a.task_id = t.id ";
$q .= " JOIN projects p on t.project_id = p.id  ";
$q .= " LEFT OUTER JOIN project_types pt ON p.type_id = pt.id ";
$q .= " WHERE p.id = ".$projectId." ";
$q .= " ORDER BY ";
$q .= " a.started desc, a.id ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}

public function countProjectActivity($projectId){
$q = " SELECT  ";
$q .= " COUNT(*) total_activities  ";
$q .= " FROM activities a JOIN tasks t ON a.task_id = t.id  ";
$q .= " JOIN projects p ON t.project_id = p.id  ";
$q .= " WHERE p.id = ".$projectId." ";
return $q;
}

public function calendarSummaryProjectActivity($projectId, $year, $month){
//show daily tally by task and person
$q = "SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " DATE(a.started) started, ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year, ";
$q .= " a.done_by, ";
$q .= " t.id, ";
$q .= " min(t.name) name, ";
$q .= " min(t.task_order) ordering, ";
$q .= " min(tt.highlight_style) highlight_style ";
$q .= " FROM projects p JOIN tasks t ON p.id = t.project_id ";
$q .= " JOIN activities a ON t.id = a.task_id ";
$q .= " LEFT OUTER JOIN task_types tt ON t.type_id = tt.id ";
$q .= " WHERE p.id = ".$projectId." ";
$q .= " AND MONTH(a.started) = '".$month."'  ";
$q .= " AND YEAR(a.started) = '".$year."'  ";
$q .= " GROUP BY  ";
$q .= " DATE(a.started), ";
$q .= " a.done_by, ";
$q .= " t.id ";
$q .= " UNION ALL ";
//show monthly tally for project across all tasks
$q .= " SELECT  ";
$q .= " SUM(a.hours_actual) sum_hours, ";
$q .= " LAST_DAY(a.started) started, ";
$q .= " MONTH(a.started) month, ";
$q .= " YEAR(a.started) year, ";
$q .= " SUM(a.hours_actual) done_by, ";
$q .= " t.id, ";
$q .= " t.name, ";
$q .= " 1000 + min(t.task_order) ordering, ";
$q .= " 'highlight-yellow' highlight_style ";
$q .= " FROM projects p JOIN tasks t ON p.id = t.project_id  ";
$q .= " JOIN activities a ON t.id = a.task_id  ";
$q .= " WHERE p.id = ".$projectId." ";
$q .= " AND MONTH(a.started) = '".$month."'  ";
$q .= " AND YEAR(a.started) = '".$year."'  ";
$q .= " GROUP BY ";
$q .= " LAST_DAY(a.started), ";
$q .= " t.id ";
$q .= " ORDER BY started, ordering, done_by ";
return $q;	
}

public function calendarLinksProjectActivity($projectId){
  $q = "SELECT ";
  $q .= " MONTH(a.started) month, ";
  $q .= " YEAR(a.started) year ";
  $q .= " FROM projects p JOIN tasks t ON p.id = t.project_id  ";
  $q .= " JOIN activities a ON t.id = a.task_id  ";
  $q .= " WHERE p.id = ".$projectId." ";
  $q .= " GROUP BY  ";
  $q .= " YEAR(a.started), ";
  $q .= " MONTH(a.started) ";
  return $q;
}
}
?>