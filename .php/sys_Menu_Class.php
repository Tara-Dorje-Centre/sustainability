<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class MenuLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function detailHref($displayMode = 'MY_LINKS',$caption = 'MyLinks', $year = 0, $month = 0){
		$link = $this->detail($displayMode, $year, $month);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	public function logoutHref(){
		$link = 'sys_Logout.php';
		$href = $this->formatHref('Logout',$link);	
		return $href;
	}
	public function detail($displayMode = 'MODULES', $year = 0, $month = 0){
		$baseUrl = 'sys_Menu.php?displayMode='.$displayMode;
		if ($year != 0 && $month != 0){
			$link = $this->formatCalendarLink($baseUrl, $year, $month);
		} else {
			$link = $baseUrl;
		}
		return $link;
	}
	
	public function linkReference(){	
			return $this->detailHref('REFERENCE', 'Reference');		
	}
	public function linkMain(){	
			return $this->detailHref('MODULES', 'Main');		
	}
	public function linkMyLinks(){	
			return $this->detailHref('MY_LINKS', 'My Links');		
	}
}
class Menu{
	public $displayMode;
	public $year = 0;
	public $month = 0;
	
	public function setDetails($displayMode = 'REFERENCE', $year = 0, $month = 0){
		$this->displayMode = $displayMode;
		$this->year = $year;
		$this->month = $month;
	}
	
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		if ($this->displayMode == 'REFERENCE'){
			$title .= 'Reference';			
		} elseif ($this->displayMode == 'CALENDAR'){
			$title .= 'Calendar';
		} elseif ($this->displayMode == 'SYSTEM-UPDATES'){
			$title .= 'System Updates';

		} elseif ($this->displayMode == 'TESTING'){
			$title .= 'Testing';
		} elseif ($this->displayMode == 'MY_LINKS') {
			$title .= 'My Links';
		} else {
			$title .= 'Sustainable Planning Tools';						
		}
		$title .= closeDiv();
		return $title;	
	}

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';

		$l = new MenuLinks($menuType,$menuStyle);
		
		$menu = $l->openMenu('section-heading-links');
		
		$menu .= $l->linkMain();
		$menu .= $l->resetMenu();
//		$menu .= $l->linkMyLinks();
//		$menu .= $l->resetMenu();		
		$menu .= $l->linkReference();
		
		if ($_SESSION['is-admin'] == 'yes'){
			//only show testing to admins
		
		$menu .= $l->resetMenu();		
		$menu .= $l->detailHref('SYSTEM-UPDATES', 'System Updates');
		$menu .= $l->resetMenu();				
		$menu .= $l->detailHref('TESTING', 'Testing');
		}
		
		$menu .= $l->closeMenu();
		
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	
	private function displayReference(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		$menuL = new MenuLinks($menuType,$menuStyle);

		$locationTypeL = new LocationTypeLinks($menuType,$menuStyle);

		$locationL = new LocationLinks($menuType,$menuStyle);
		$unitL = new UnitOfMeasureLinks($menuType,$menuStyle);
		$measureTypeL = new MeasureTypeLinks($menuType,$menuStyle);
		$cropL = new CropLinks($menuType,$menuStyle);
		$projectTypeL = new ProjectTypeLinks($menuType,$menuStyle);
		$taskTypeL = new TaskTypeLinks($menuType,$menuStyle);
		$activityTypeL = new ActivityTypeLinks($menuType,$menuStyle);
		$materialTypeL = new MaterialTypeLinks($menuType,$menuStyle);
		$receiptTypeL = new ReceiptTypeLinks($menuType,$menuStyle);
		
		$userL = new UserLinks($menuType, $menuStyle);
		$userTypeL = new UserTypeLinks($menuType, $menuStyle);
		$sitewideSettingsL = new SitewideSettingsLinks($menuType, $menuStyle);
				
		$menu = $menuL->openMenu('section-content-links');

		$menu .= $locationL->listingHref();
		$menu .= $locationTypeL->listingHref();
		$menu .= $menuL->resetMenu();
		$menu .= $projectTypeL->listingHref();
		$menu .= $taskTypeL->listingHref();
		$menu .= $activityTypeL->listingHref();
		$menu .= $menuL->resetMenu();		
		$menu .= $materialTypeL->listingHref();
		$menu .= $receiptTypeL->listingHref();
		
		$menu .= $menuL->resetMenu();
		$menu .= $unitL->listingHref();
		$menu .= $measureTypeL->listingHref();
		$menu .= $menuL->resetMenu();
		$menu .= $cropL->listingHref();
		if ($_SESSION['is-admin'] == 'yes'){
			//only show userlist to admins
			$menu .= $menuL->resetMenu();
			$menu .= $userL->listingHref();
			$menu .= $userTypeL->listingHref();
			$menu .= $menuL->resetMenu();
			$menu .= $sitewideSettingsL->detailViewHref();
		}
		$menu .= $menuL->resetMenu();
		$menu .= $menuL->detailHref('CALENDAR', 'Calendars');
		
		$menu .= $menuL->closeMenu();
		return $menu;			
		
	}
		
	private function displayMyLinks(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projectL = new ProjectLinks($menuType,$menuStyle);
		$activityL = new ActivityLinks($menuType,$menuStyle);
		$taskL = new TaskLinks($menuType, $menuStyle);
		$userL = new UserLinks($menuType, $menuStyle);
				
		$menu = $projectL->openMenu('section-content-links');
		
		$menu .= $taskL->linksPeriodicTasks();
		$menu .= $projectL->resetMenu();		
		$menu .= $projectL->listingMyProjects();
		$menu .= $projectL->resetMenu();
		$menu .= $projectL->listingAllProjects();
		$menu .= $projectL->resetMenu();
//		$menu .= $taskL->formatTextItem('Calendars:');
		$menu .= $activityL->linkMyCalendar();
		$menu .= $projectL->resetMenu();
//		$menu .= $taskL->formatTextItem('History:');
		$menu .= $activityL->linkMyActivities();
		$menu .= $projectL->resetMenu();
		if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true){
			if ($_SESSION['must-update-pwd'] == 'yes'){
				$menu .= $userL->formatTextItem('Please update your profile password.');
			}
			$menu .= $userL->detailViewHref($_SESSION['user-id'], 'My Profile');
		}
		$menu .= $projectL->closeMenu();
		
		return $menu;
		
	}
	private function displayModules(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		$projectL = new ProjectLinks($menuType,$menuStyle);
		$taskL = new TaskLinks($menuType,$menuStyle);
		$activityL = new ActivityLinks($menuType,$menuStyle);
		$cropPlanL = new CropPlanLinks($menuType,$menuStyle);
		
		$menu = $projectL->openMenu('section-content-links');
		$menu .= $taskL->linksPeriodicTasks(true);
		$menu .= $projectL->resetMenu();
		$menu .= $projectL->listingAllProjects(true);		
		$menu .= $projectL->resetMenu();
//		$menu .= $taskL->formatTextItem('Calendars:');
		$menu .= $activityL->linkGroupCalendar();
		$menu .= $projectL->resetMenu();		
//		$menu .= $taskL->formatTextItem('History:');
		$menu .= $activityL->linkGroupActivities();
		$menu .= $projectL->resetMenu();
		//$menu .= $taskL->formatTextItem('Crop Planning:');
		$menu .= $cropPlanL->listingHref();

		$menu .= $projectL->closeMenu();
		return $menu;					
	}
	
	public function printPage(){
		
		$heading = $this->getPageHeading();
		$details = $this->getPageDetails();

		$site = new _SiteTemplate;
		$site->setSiteTemplateDetails($heading, $details);
		$site->printSite();
		
	}

	public function getPageDetails(){
	
		$details = $this->display();
		return $details;
		
	}
	
	
	public function display(){

		
		if ($this->displayMode == 'REFERENCE'){
			$content = $this->displayReference();
		} elseif ($this->displayMode == 'CALENDAR'){
			$content = $this->displayCalendar();
		} elseif ($this->displayMode == 'MY_LINKS') {
			$content = $this->displayMyLinks();
		} elseif ($this->displayMode == 'SYSTEM-UPDATES'){
			$content = $this->displaySystemUpdates();
		} elseif ($this->displayMode == 'TESTING'){
			$content = $this->displayTesting();						
		} else {
			$content = $this->displayModules();
		}
		return $content;
		
	}

	private function displayCalendar(){
		if ($this->month == 0 || $this->year == 0){
			global $sessionTime;
			$this->year = getTimestampYear($sessionTime);
			$this->month = getTimestampMonth($sessionTime,'YES');
		}
		if ($this->month == 12){
			$nextMonth = 1;
			$nextYear = $this->year + 1;
		} else {
			$nextMonth = $this->month + 1;
			$nextYear = $this->year;
		}
		if ($this->month == 1){
			$prevMonth = 12;
			$prevYear = $this->year - 1;
		} else {
			$prevMonth = $this->month - 1;
			$prevYear = $this->year;
		}

		$menuL = new MenuLinks('LIST','menu');
		$prevMonthLink = $menuL->detailHref('CALENDAR', 'Previous', $prevYear, $prevMonth);
		$nextMonthLink = $menuL->detailHref('CALENDAR', 'Next', $nextYear, $nextMonth);

		$c = new _Calendar($this->year,$this->month,'Calendar');	
		$c->setLinks($nextMonthLink, $prevMonthLink);
		return $c->buildCalendar();
	}

	private function displayTesting(){
	
		include_once("sys_UnitTesting_Class.php");
		$tests = new _UnitTest;
		$tests->setDetails($this->displayMode,$this->year,$this->month);
		$content = $tests->display();
		
		return $content;
	
	}

	private function displaySystemUpdates(){
	
		include_once("sys_SystemUpdates.php");
		$update = new _SystemUpdate;
		$update->setDetails($this->displayMode,$this->year,$this->month);
		$content = $update->display();
		
		return $content;
	
		
	}

}


?>