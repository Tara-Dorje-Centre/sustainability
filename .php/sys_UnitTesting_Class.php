<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");



class _UnitTest{
	public $displayMode;
	public $year = 0;
	public $month = 0;
	
	public function setDetails($displayMode = 'TESTING', $year = 0, $month = 0){
		$this->displayMode = $displayMode;
		$this->year = $year;
		$this->month = $month;
	}
	




	private function getTestMenu($menuType,$menuStyle,$menuName){
		
		
		$l = new MenuLinks($menuType,$menuStyle);
		
		$menu = openDiv('test-menu-formats');

		$legend = $menuType.'..'.$menuStyle.'..'.$menuName;
		//$menu = openFieldset($legend);
		$menu .= captionedInput('getTestMenu:',$legend);
		
		$menu .= openDiv('test-menu-details','testing-frame');
		$menu .= $l->openMenu($menuName);

		$menu .= $l->formatHref('1a','#1a');
		$menu .= $l->formatHref('1b','#1b');
		$menu .= $l->formatHref('1c','#1c');		
		$menu .= $l->resetMenu();
		$menu .= $l->formatHref('2a','#2a');
		$menu .= '[not a link]';
		$menu .= $l->formatHref('2b','#2b');
		$menu .= $l->resetMenu();
		$menu .= $l->formatHref('3a or 3z','#3a');
		$menu .= $l->formatHref('3b','#3b');
		$menu .= $l->formatHref('3c','#3c');
		
		$menu .= $l->closeMenu();

		$menu .= closeDiv();		
		$menu .= closeDiv();
		//$menu .= closeFieldset();
		
		return $menu;			
		
	}
	private function getTestMenuSet($menuName){
		$menuType = 'DIV';
		$menu = $this->getTestMenu($menuType,'menu',$menuName);
		$menu .= $this->getTestMenu($menuType,'button',$menuName);		
		$menu .= $this->getTestMenu($menuType,'paged',$menuName);
		$menuType = 'LIST';
		$menu .= $this->getTestMenu($menuType,'menu',$menuName);
		$menu .= $this->getTestMenu($menuType,'button',$menuName);		
		$menu .= $this->getTestMenu($menuType,'paged',$menuName);
		return $menu;
		
	}
	private function displayTestMenus(){
		$menu = openDiv('test-menu-styles','testing-frame');
		$menu .= $this->getTestMenuSet('site-heading-links');		
		$menu .= $this->getTestMenuSet('section-heading-links');
		$menu .= $this->getTestMenuSet('section-content-links');
		$menu .= $this->getTestMenuSet('paged-linkset');

		$menu .= closeDiv();
		return $menu;		
		
	}
//	private function displayCalendar(){
//		if ($this->month == 0 || $this->year == 0){
//			global $sessionTime;
//			$this->year = getTimestampYear($sessionTime);
//			$this->month = getTimestampMonth($sessionTime,'YES');
//		}
//		if ($this->month == 12){
//			$nextMonth = 1;
//			$nextYear = $this->year + 1;
//		} else {
//			$nextMonth = $this->month + 1;
//			$nextYear = $this->year;
//		}
//		if ($this->month == 1){
//			$prevMonth = 12;
//			$prevYear = $this->year - 1;
//		} else {
//			$prevMonth = $this->month - 1;
//			$prevYear = $this->year;
//		}
//
//		$menuL = new MenuLinks('LIST','menu');
//		$prevMonthLink = $menuL->detailHref('CALENDAR', 'Previous', $prevYear, $prevMonth);
//		$nextMonthLink = $menuL->detailHref('CALENDAR', 'Next', $nextYear, $nextMonth);
//
//		$c = new _Calendar($this->year,$this->month,'Calendar');	
//		$c->setLinks($nextMonthLink, $prevMonthLink);
//		return $c->buildCalendar();
//	}
	
//	private function runUpdates(){
//		
////$sql = "set @@session.time_zone='-7:00'";
//$content = 'add activity to materials'.br();
//$sql = "ALTER TABLE `materials`  ADD `activity_id` BIGINT(20) NOT NULL DEFAULT '0'";
//mysql_query( $sql );// or exit(
//$content .= 'errors:'.mysql_error().br();
//
//$content .= 'add activity to measures'.br();
//$sql = "ALTER TABLE `measures`  ADD `activity_id` BIGINT(20) NOT NULL DEFAULT '0'";
//mysql_query( $sql );// or exit(mysql_error());
//$content .= 'errors:'.mysql_error().br();//);
//
//$content .= 'add activity to receipts'.br();
//$sql = "ALTER TABLE `receipts`  ADD `activity_id` BIGINT(20) NOT NULL DEFAULT '0'";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//$content .= 'add highlight style to measure types'.br();
//$sql = "ALTER TABLE `measure_types`  ADD `highlight_style` VARCHAR(100) NOT NULL";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//$content .= 'add display order to measure types'.br();
//$sql = "ALTER TABLE `measure_types`  ADD `display_order` INT(4) NOT NULL DEFAULT '0'";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//
//$content .= 'add highlight style to activity types'.br();
//$sql = "ALTER TABLE `activity_types`  ADD `highlight_style` VARCHAR(100) NOT NULL";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//$content .= 'add display order to activity types'.br();
//$sql = "ALTER TABLE `activity_types`  ADD `display_order` INT(4) NOT NULL DEFAULT '0'";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//$content .= 'Modify activity comments to store up to 4000 characters'.br();
//$sql = "ALTER TABLE `activities` CHANGE `comments` `comments` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//$content .= 'Modify task description to store up to 4000 characters'.br();
//$sql = "ALTER TABLE `tasks` CHANGE `description` `description` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//$content .= 'Modify task summary to store up to 4000 characters'.br();
//$sql = "ALTER TABLE `tasks` CHANGE `summary` `summary` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//
//$content .= 'Modify project description to store up to 4000 characters'.br();
//$sql = "ALTER TABLE `projects` CHANGE `description` `description` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//$content .= 'Modify project summary to store up to 4000 characters'.br();
//$sql = "ALTER TABLE `projects` CHANGE `summary` `summary` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
//mysql_query( $sql );
//$content .= 'errors:'.mysql_error().br();//);
//
//	
//	return $content;
//	}
	private function displayTesting(){
		$entity = 'unit-test';
		$legend = 'unit tests';
		$c = new ProjectLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$content = openEditForm($entity,$legend,'sys_Menu.php?displayMode=TESTING', $contextMenu);
		$formRequired = NULL; //$this->runUpdates();

		$fields = $this->displayTestMenus();
		//$formRequired = $fields;
		$formOptional = $fields;
		$formSubmit = 'no submit buttons';
		$content .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $content;
	
	}

	public function display(){

		
		if ($this->displayMode == 'TESTING'){
			$content = $this->displayTesting();
		} else {
			$content = NULL;
		}
		return $content;
	}


}


?>