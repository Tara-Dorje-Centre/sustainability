<?php 
include_once("_formFunctions.php");
include_once("_htmlFunctions.php");
include_once("_cssFunctions.php");
include_once("_sqlFunctions.php");

include_once("pr_Activity_Classes.php");
include_once("pr_Task_Classes.php");

class _SiteTemplate{
	private $htmlPageTitle = 'myPage';
	private $siteTitle;
	private $siteLinksItems;
	
	private $footerLogoImage;
	private $footerNoticesItems;
	private $footerLinksItems;

	private $pageHeading;
//	public $pageLinksItems;
	private $pageContents;
	private $pageSectionHeading;
	private $pageSectionDetails;


public function setSiteTemplateDetails($contentHeading = 'page heading', $contentDetails = 'page details'){
	$this->pageSectionDetails = $contentDetails;
	$this->pageSectionHeading = $contentHeading;

	$this->getSitewideSettings();
	$this->setSiteTitle();
	$this->setSiteLinksItems();
	$this->setPageContents();	
	$this->setFooterNoticesItems();
	$this->setFooterLinksItems();	
}

protected function getSitewideSettings(){
	$settings = new SitewideSettings;
	$settings->setDetails('VIEW');
}


protected function setSiteTitle(){
	$title = $_SESSION['site-title'];
	$this->htmlPageTitle = $title;		
	$this->siteTitle = $title;	
}


protected function setSiteLinksItems(){

	//login check goes here to show content or login screen
	if (!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] == false){
		//user is not logged in, siteContent will display login form, display no links
		$this->siteLinksItems = '';
	} else {
		$menuType = 'LIST';
		$menuStyle = 'button';
		$m = new MenuLinks($menuType, $menuStyle);
		$p = new ProjectLinks($menuType, $menuStyle);
		$t = new TaskLinks($menuType, $menuStyle);
		$a = new ActivityLinks($menuType, $menuStyle);
		
		$content = $m->openMenu('site');
		if (isset($_SESSION['show-public-site']) && $_SESSION['show-public-site'] == 'yes'){
			$content .= $m->formatHref('Library','public.php');	
		}
		
		if (isset($_SESSION['currentView'])){
			if ($_SESSION['currentView'] == 'PERIODIC_TASKS'){
				//user just viewed current open tasks;
				//$content .= $t->linkPeriodicTasksOpen();
			} elseif ($_SESSION['currentView'] == 'MY_CALENDAR'){
				//user just viewed myCalendar;
				$content .= $a->linkMyCalendar();
			} elseif ($_SESSION['currentView'] == 'GROUP_CALENDAR'){
				//user just viewed groupCalendar;
				$content .= $a->linkGroupCalendar();
			} elseif ($_SESSION['currentView'] == 'MY_ACTIVITY'){
				//user just viewed myActivity;
				$content .= $a->linkMyActivities();
			} elseif ($_SESSION['currentView'] == 'GROUP_ACTIVITY'){
				//user just viewed groupActivity;
				$content .= $a->linkGroupActivities();
			}
		}
include_once("pr_Project_Classes.php");
		//always show current open tasks;
		$content .= $t->linkPeriodicTasksOpen();		
		//always show projects link
		$content .= $p->listingAllProjects(false);
		$content .= $m->detailHref();	
		$content .= $m->logoutHref();
		$content .= $m->closeMenu();
		
		$this->siteLinksItems = $content;
		
	}
	
}


protected function setPageContents(){ 

	if (!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] == false){
		$user = new User;
		$content = $user->loginForm();
	} else {
		//section-heading div commented out to allow section-links to float closer to section details
		//  pageHeading contains section-title and section-links div containers
		//  wrap title and links in section-heading div
		//	$s .= openDiv('section-heading');
		$content = $this->pageSectionHeading;
		//	$s .= closeDiv(); //close section heading

		$content .= openDiv('section-details');
		$content .= $this->pageSectionDetails;
		$content .= closeDiv(); //close section details
	}
	$this->pageContents = $content;
}


	protected function setFooterNoticesItems(){


		$content = openDiv('login-notices');

		global $sessionTime, $sessionTimeZone;
		$timing = 'Request Time: '.$sessionTime.'(UTC'.$sessionTimeZone.')';
		$content .= wrapDiv($timing, 'display-request-time');
		
		
		if (isset($_SESSION['login-name'])){
			$loginName = $_SESSION['login-name'];
		} else {
			$loginName = 'Not logged in.';	
		}
		if (isset($_SESSION['client-time-zone'])){
			$timeZone = '@UTC'.$_SESSION['client-time-zone'];	
		} else {
			$timeZone = ' Time Zone set to UTC';	
		}
		$loginName .= $timeZone;	
		$content .= wrapDiv($loginName,'loginName','loginName');
	
		$content .= closeDiv();
		
		
		$content .= openDiv('site-organization');
		$org = $_SESSION['site-org'];
		$orgUrl = $_SESSION['site-org-url'];
		if ($orgUrl == '' || is_null($orgUrl)){
			$content .= $org;
		} else {
			$content .= getHref($orgUrl, $org, 'menu-links-item', '_blank');
		}
		$content .= closeDiv();


		$this->footerNoticesItems = $content;
	
	}

	protected function setFooterLinksItems(){

		$content = getHref('https://sourceforge.net/projects/sustainability','Sustainability','menu-links-item','_blank');

		$this->footerLinksItems = $content;	
	}

	public function printSite(){		
		$content = $this->getSiteTemplate();
		echo $content;	

//close db conection
		mysql_close();
	}

	protected function getSiteTemplate(){		
		$content = $this->siteContainerOpen();
		$content .= $this->siteHeading();
		$content .= $this->siteContent();	
		$content .= $this->siteFooter();
		$content .= $this->siteContainerClose();
		return $content;		
	}

	protected function siteStyles(){
		//use internal stylesheet
		//$content = $this->buildInternalStyles();

		//use external stylesheet
		$content = stylesheet('css/styles.css');
		$content .= stylesheet('css/override.css');
		
		return $content;
		
	}

	protected function siteScripts(){
		$scripts = openScript();	
		//start oputput buffering
		ob_start();
		//include scripts to buffer
		include('_siteJavascriptFunctions.php');		
		//clear buffer to local variable
		$scripts .= ob_get_clean();  
		$scripts .= closeScript();
		return $scripts;
	}

	protected function siteContainerOpen(){
		$content = doctypeHtml();
		$content .= openTag('html');
		$content .= openTag('head');
		$content .= metaHttpEquivs();		
		$content .= $this->siteStyles();
		$content .= wrapTag('title',null,$this->htmlPageTitle);
		$content .= $this->siteScripts();
		$content .= closeTag('head');

		if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true){	
			$content .= '<body>';
		} else {
			$content .= '<body onload="displayClientTime();">';
		}
		$content .= openDiv('site-container');
		return $content;	
	}

	protected function siteHeading(){
		$content = openDiv('site-heading');
		$content .= $this->siteTitle();
		$content .= $this->siteLinks();
		$content .= closeDiv();
		return $content;	
	}

	protected function siteTitle(){
		$content = openDiv('site-heading-title');
		$content .= $this->siteTitle;
		$content .= closeDiv();
		return $content;	
	}

	protected function siteLinks(){
		$content = openDiv('site-heading-links');
		$content .= $this->siteLinksItems;
		$content .= closeDiv(); //close site links
		return $content;	
	}

	protected function siteContent(){
		$content = openDiv('site-content');
		$content .= $this->pageContents;
		$content .= closeDiv(); // close site content
		return $content;
	}

	protected function siteFooter(){
		$content = openDiv('site-footer');
		$content .= $this->siteFooterLogos();
		$content .= $this->siteFooterNotices();
		$content .= $this->siteFooterLinks();
		$content .= closeDiv(); //close site footer
		return $content;
	}

	protected function siteFooterLogos(){
		$content = openDiv('site-footer-logos');
		$content .= $this->footerLogoImage;
		$content .= closeDiv();
		return $content;	
	}

	protected function siteFooterNotices(){
		$content = openDiv('site-footer-notices');
		$content .= $this->footerNoticesItems;
		$content .= closeDiv();
		return $content;	
	}

	protected function siteFooterLinks(){	
		$content = openDiv('site-footer-links');
		$content .= $this->footerLinksItems;
		$content .= closeDiv();
		return $content;		
	}

	protected function siteContainerClose(){
		//close site page container
		$content = closeDiv();
		$content .= closeTag('body');
		$content .= closeTag('html');
		return $content;
	}
	
}

?>
