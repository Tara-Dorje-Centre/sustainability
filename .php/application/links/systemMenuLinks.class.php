<?php
namespace application\links;

class systemMenuLinks extends linkMenu {


	public function __construct($name = 'system-menu',$type='LIST', $css='menu'){
		parent::__construct($name,$type,$css);
	}
	protected function detailLink($displayMode = 'MY_LINKS',$caption = 'MyLinks', $year = 0, $month = 0){
		$u = $this->menuUrl($displayMode, $year, $month);
		return $this->getLink($u, $caption);
		

	}
	public function library(){
		$u = new url('public.php');
		return $this->getLink($u, 'Library');
		

	}
	public function logout(){
		$u = new url('portal.php');
		$context = 'system';
		$scope = 'logout';
		$u->makeParameter('context',$context);
		$u->makeParameter('scope',$scope);
		//$this->setBaseParameters($u,$scope,$context);
		return $this->getLink($u,'Logout');
	}
	public function menuUrl(string $displayMode, $year = 0, $month = 0){
		$u = new url('portal.php');
		$context = 'system-menu';
		$scope = 'default-view';
		$u->makeParameter('context',$context);
		$u->makeParameter('scope',$scope);
		
		$u->makeParameter('year',$year);
		$u->makeParameter('month',$month);
		
	//	$this->setCalendarParameters($u,$year,$month);	
		
		$u->makeParameter('displayMode', $displayMode);

		return $u;
	}
	
	public function reference(){	
		return $this->detailLink('REFERENCE', 'Reference');		
	}
	public function modules(){	
		return $this->detailLink('MODULES', 'Main');		
	}
	public function myLinks(){	
		return $this->detailLink('MY_LINKS', 'My Links');		
	}
	public function updates(){
		return $this->detailLink('SYSTEM-UPDATES', 'System Updates');
	}
	public function testing(){
		return $this->detailLink('TESTING', 'Testing');
	}
	public function calendars($caption = 'Calendar', $year = 0, $month = 0){
		return $this->detailLink('CALENDAR', $caption,$year,$month);
	}
	public function getPageMenu(){
		$this->addLink($this->modules());
		$this->addLink($this->reference());
		$this->addLink($this->myLinks());
		$this->addLink($this->calendars());
		
		if ($_SESSION['is-admin'] == 'yes'){
			$this->addLink($this->updates());
			$this->addLink($this->testing());
		}
		return $this->print();			
	}
}
?>
