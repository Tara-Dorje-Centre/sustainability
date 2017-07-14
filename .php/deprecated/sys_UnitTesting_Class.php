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
