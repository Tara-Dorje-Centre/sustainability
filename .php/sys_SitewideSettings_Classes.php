<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class SitewideSettingsLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	private function detailHref($pageAction = 'VIEW', $caption = 'SitewideSettings'){
		$link = $this->detail($pageAction);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function detail($pageAction){
		$link = 'sys_SitewideSettings_Detail.php?pageAction='.$pageAction;
		return $link;
	}	
	public function detailViewHref($caption = 'ViewSitewideSettings'){
		$l = $this->detailHref('VIEW',$caption);
		return $l;	
	}
	public function detailEditHref($caption = 'EditSitewideSettings'){
		$l = $this->detailHref('EDIT',$caption);
		return $l;	
	}
	public function detailViewEditHref($viewCaption = 'ViewSitewideSettings'){
		$links = $this->detailViewHref($viewCaption);
		$links .= $this->detailEditHref('#');
		return $links;
	}	
	
}

class SitewideSettings {
	public $siteTitle = 'MyProjects';
	public $siteUrl = 'MyUrl';
	public $loginNotice = '';
	public $organizationUrl = 'MyOrganizationUrl';	
	public $organization = 'MyOrganization';
	public $organizationDescription = 'AboutMyOrganization';
	public $contactName = 'MySiteContact';
	public $contactEmail = 'MySiteContact@MyOrganization';
	// property to support edit/view/add mode of calling page
    public $pageMode;
	private $sql;
	private $showPublicSite = 'no';
	private $showCostReports = 'no';
	private $showRevenueReports = 'no';
	private $publicFillColor;
	private $publicSiteColor;
	private $publicPageColor;
	private $publicMenuColor;
	private $publicMenuColorHover;
	private $publicTextColor;
	private $publicTextColorHover;			
	private $publicFontFamily;
	private $publicFontSizeTitle = 20;
	private $publicfontSizeHeading = 16;
	private $publicFontSizeMenu = 12;
	private $publicFontSizeText = 12;
	
	public function __construct(){
		$this->sql = new SitewideSettingsSQL;
	}
	
    // set class properties with record values from database
	public function setDetails($inputMode){
		$this->pageMode = $inputMode;
		$this->verifySettings();

		$sql = $this->sql->infoSitewideSettings();
		
		$locale = 'sitewideSettings->setDetails:';
		$result = dbGetResult($sql, $locale);
		//global $conn;
		//$result = $conn->query($sql) or exit($locale.$conn->error);

		if($result){
		
	  	while ($row = $result->fetch_assoc())
		{		
			$this->siteTitle = ($row["site_title"]);
			$this->siteUrl = ($row["site_url"]);
			$this->loginNotice = ($row["login_notice"]);
			$this->organization = ($row["organization"]);
			$this->organizationUrl = ($row["organization_url"]);
			$this->organizationDescription = ($row["organization_description"]);
			$this->contactName = ($row["contact_name"]);
			$this->contactEmail = ($row["contact_email"]);
			$this->showPublicSite = ($row["show_public_site"]);
			$this->showCostReports = ($row["show_cost_reports"]);
			$this->showRevenueReports = ($row["show_revenue_reports"]);
			$this->publicFillColor = ($row["public_fill_color"]);
			$this->publicSiteColor = ($row["public_site_color"]);
			$this->publicPageColor = ($row["public_page_color"]);
			$this->publicMenuColor = ($row["public_menu_color"]);
			$this->publicMenuColorHover = ($row["public_menu_color_hover"]);
			$this->publicTextColor = ($row["public_text_color"]);
			$this->publicTextColorHover = ($row["public_text_color_hover"]);
			$this->publicFontFamily = ($row["public_font_family"]);
			$this->publicFontSizeTitle = ($row["public_font_size_title"]);
			$this->publicFontSizeHeading = ($row["public_font_size_heading"]);
			$this->publicFontSizeMenu = ($row["public_font_size_menu"]);
			$this->publicFontSizeText = ($row["public_font_size_text"]);
						
		}
		
		// Free result set
		$result->close();
		}
		
		$this->setSessionDetails();	
	}	
	
	public function verifySettings(){
		$sql = " SELECT COUNT(*) AS count_settings ";
		$sql .= " FROM sitewide_settings ";
		$i = dbGetCount($sql, 'count_settings', 0, 'verifySettings');
		if ($i == 0){
			$this->createSettings();	
		}
	}
	
	public function setSessionDetails(){
		//$this->formatForDisplay();
		$_SESSION['site-title'] = $this->siteTitle;	
		$_SESSION['site-url'] = $this->siteUrl;
		$_SESSION['site-login-notice'] = displayLines($this->loginNotice);
		$_SESSION['site-org'] = $this->organization;
		$_SESSION['site-org-url'] = $this->organizationUrl;
		$_SESSION['site-contact-name'] = $this->contactName;
		$_SESSION['site-contact-email'] = $this->contactEmail;
		$_SESSION['show-public-site'] = $this->showPublicSite;
		$_SESSION['show-cost-reports'] = $this->showCostReports;
		$_SESSION['show-revenue-reports'] = $this->showRevenueReports;
	}
	
	public function unsetSessionDetails(){
		unset($_SESSION['site-title']);
		unset($_SESSION['site-url']);
		unset($_SESSION['site-login-notice']);
		unset($_SESSION['site-org']);
		unset($_SESSION['site-org-url']);
		unset($_SESSION['site-contact-name']);
		unset($_SESSION['site-contact-email']);
		unset($_SESSION['show-public-site']);
		unset($_SESSION['show-cost-reports']);
		unset($_SESSION['show-revenue-reports']);
	}
	
		
	function pageTitle(){
		$heading = openDiv('section-heading-title');
		$heading .= 'Sitewide Settings';
		$heading .= closeDiv();		
		return $heading;
	}
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';		
		$settingsL = new SitewideSettingsLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);

		$menu = $settingsL->openMenu('section-heading-links');
		
		$menu .= $menuL->linkReference();
		$menu .= $settingsL->resetMenu();
		if ($this->pageMode == 'VIEW'){
			$menu .= $settingsL->detailEditHref();
		} elseif ($this->pageMode == 'EDIT'){
			$menu .= $settingsL->detailViewHref();
		}
		$menu .= $settingsL->closeMenu();
		return $menu;
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function formatForDisplay(){
		$this->siteTitle = displayLines($this->siteTitle);
		$this->organization = displayLines($this->organization);
		$this->loginNotice = displayLines($this->loginNotice);
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
				
		$detail = openDisplayDetails('sitewide-settings','Sitewide Settings');
	 						
		$detail .= captionedParagraph('siteTitle', 'Projects Title', $this->siteTitle);
		$detail .= captionedParagraph('siteUrl', 'Projects URL', $this->siteUrl);
		$detail .= captionedParagraph('login-notice', 'Project Login Notice', $this->loginNotice);
		$detail .= captionedParagraph('org', 'Organization', $this->organization);
		$detail .= captionedParagraph('org-url', 'Org. URL', $this->organizationUrl);
		$detail .= captionedParagraph('org-description','Org. Description', $this->organizationDescription);
		$detail .= captionedParagraph('contact-name', 'Contact Name', $this->contactName);
		$detail .= captionedParagraph('contact-email', 'Contact Email', $this->contactEmail);
		
		$detail .= $this->getPublicSiteStylesFieldset('true');
		
		$detail .= closeDisplayDetails();
		return $detail;
	}
	
	private function getPublicSiteStylesFieldset($disabled = 'false'){

		$styles = openFieldset('Public Website Presentation Styles');
		$styles .= openDiv('public-styles','editing-table');
		$input = getColorSelectList($this->publicFillColor, 'publicFillColor',$disabled);
		$styles .= captionedInput('Fill Color', $input);		

		$input = getColorSelectList($this->publicSiteColor, 'publicSiteColor',$disabled);
		$styles .= captionedInput('Site Color', $input);		

		$input = getColorSelectList($this->publicPageColor, 'publicPageColor',$disabled);
		$styles .= captionedInput('Page Color', $input);		

		$input = getColorSelectList($this->publicMenuColor, 'publicMenuColor',$disabled);
		$styles .= captionedInput('Menu Color', $input);		

		$input = getColorSelectList($this->publicMenuColorHover, 'publicMenuColorHover',$disabled);
		$styles .= captionedInput('Menu Color Hover', $input);		

		$input = getColorSelectList($this->publicTextColor, 'publicTextColor',$disabled);
		$styles .= captionedInput('Text Color', $input);		

		$input = getColorSelectList($this->publicTextColorHover, 'publicTextColorHover',$disabled);
		$styles .= captionedInput('Text Color Menu Hover', $input);		
		
		$input = getFontSizeSelectList($this->publicFontSizeTitle, 'publicFontSizeTitle',$disabled);
		$styles .= captionedInput('Font Size Title px', $input);	

		$input = getFontSizeSelectList($this->publicFontSizeHeading, 'publicFontSizeHeading',$disabled);
		$styles .= captionedInput('Font Size Heading px', $input);	
		
		$input = getFontSizeSelectList($this->publicFontSizeMenu, 'publicFontSizeMenu',$disabled);
		$styles .= captionedInput('Font Size Menu px', $input);	
		
			
		$input = getFontSizeSelectList($this->publicFontSizeText, 'publicFontSizeText',$disabled);
		$styles .= captionedInput('Font Size Text px', $input);	

		$input = getFontFamilySelectList($this->publicFontFamily, 'publicFontFamily',$disabled);
		$styles .= captionedInput('FontFamily', $input);	
			
		//add the sample region after the first color select
		$styles .= wrapDiv($this->publicSiteSample(),'public-site-sample','site-sample');

		$styles .= closeDiv();
		$styles .= closeFieldset();
		return $styles;
	}
	
	public function publicSiteSample(){
		include_once("_cssFunctions.php");
		//include_once("_htmlFunctions.php");
		//sample is inserted into the editing td just after the first color select list
		//force a close on the td and reopen a td with rowspan set to all seven selects
		//this inserts the sample neatly beside all select lists
		$x = closeTag('td');
		$a = attribute('width', '50%');
		$a .= attribute('rowspan',11);
		$a .= attribute('class','editing-td');
		$x .= openTag('td',$a);
		$style = minHeightPixels(200).setPadding(10).setFontFamily($this->publicFontFamily);
		$style .= setFont($this->publicFontSizeText,'normal','normal').colorBackground($this->publicFillColor);
		$x .= openTag('div',attribute('style',$style));
		$style = setPadding(10).colorBackground($this->publicSiteColor);
		$x .= openTag('div',attribute('style',$style));
		$style = setPadding(10).widthPixels(75).setFloat('left');
		$style .= colorBackground($this->publicMenuColor).setFont($this->publicFontSizeMenu,'normal','normal');
		$x .= openTag('div',attribute('style',$style));
		$style = clearListStyle().clearMarginPadding();
		$x .= openTag('ul',attribute('style',$style));
		$style = setDisplay('block').colorText($this->publicTextColor);
		$x .= wrapTag('li',attribute('style',$style),'Menu Item');
		$style = setDisplay('block').colorText($this->publicTextColorHover);
		$style .= colorBackground($this->publicMenuColorHover);
		$x .= wrapTag('li',attribute('style',$style),'Menu Item');
		$x .= closeTag('ul');
		$x .= closeDiv();
		$style = setPadding(10).setMarginCustom('left',100);
		$style .= colorBackground($this->publicPageColor).colorText($this->publicTextColor);
		$x .= openTag('div',attribute('style',$style));
		$x .= spanStyled('Titles',setFont($this->publicFontSizeTitle,'normal','bold')).br();
		$x .= spanStyled('Headings',setFont($this->publicFontSizeHeading,'normal','bold')).br();
		$x .= spanStyled('Content Text'.br().'...',setFont($this->publicFontSizeText,'normal','normal')).br();
		$x .= closeDiv();		
		$x .= closeDiv();		
		$x .= closeDiv();

		return $x;		
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Sitewide Settings';
		} else {
			$legend = 'Edit Sitewide Settings';	
		}

		$entity = 'sitewide-settings';
		$c = new ProjectLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');


		$form = openEditForm($entity,$legend,'sys_SitewideSettings_Save.php',$contextMenu);
		
		//start required fields
		$fields = inputFieldName($entity,$this->siteTitle,'siteTitle','Projects Title');

		$fields .= inputFieldText($entity,$this->siteUrl,'siteUrl','Projects URL',50,255);
				
		$fields .= inputFieldName($entity,$this->contactName,'contactName','Contact Name');

		$fields .= inputFieldText($entity,$this->contactEmail,'contactEmail','Contact Email',40,100);

		//end required fields
		$formRequired = $fields;

		//start optional fields		
		$fields = inputFieldName($entity,$this->organization,'organization','Organization');

		$fields .= inputFieldText($entity,$this->organizationUrl,'organizationUrl','Organization URL',50,255);

		$fields .= inputFieldComments($entity,$this->organizationDescription,'organizationDescription','Organization Description');


		$input = getSelectYesNo('showCostReports',$this->showCostReports);
		$fields .= captionedInput('Show Cost Reports',$input);		

		$input = getSelectYesNo('showRevenueReports',$this->showRevenueReports);
		$fields .= captionedInput('Show Revenue Reports',$input);		

		$input = getSelectYesNo('showPublicSite',$this->showPublicSite);
		$fields .= captionedInput('Show Public Site',$input);				
		
		$fields .= $this->getPublicSiteStylesFieldset('false');

		$fields .= inputFieldComments($entity,$this->loginNotice,'loginNotice','Projects Login Notice',1000);


		//end optional fields (hidden by default)
		$formOptional = $fields;

		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
		
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $form;
	}
	
	public function collectPostValues(){
global $conn;

		$this->siteTitle = $conn>escape_string($_POST['siteTitle']);
		$this->siteUrl = $conn>escape_string($_POST['siteUrl']); 
		$this->loginNotice = $conn>escape_string($_POST['loginNotice']);
		$this->organization = $conn>escape_string($_POST['organization']); 
		$this->organizationUrl = $conn>escape_string($_POST['organizationUrl']);
		$this->organizationDescription = $conn>escape_string($_POST['organizationDescription']);
		$this->contactName = $conn>escape_string($_POST['contactName']); 		
		$this->contactEmail = $conn>escape_string($_POST['contactEmail']);
		$this->showPublicSite = $conn>escape_string($_POST["showPublicSite"]);
		$this->showCostReports = $conn>escape_string($_POST["showCostReports"]);
		$this->showRevenueReports = $conn>escape_string($_POST["showRevenueReports"]); 		
		$this->publicFillColor = $conn>escape_string($_POST["publicFillColor"]);
		$this->publicSiteColor = $conn>escape_string($_POST["publicSiteColor"]);
		$this->publicPageColor = $conn>escape_string($_POST["publicPageColor"]);
		$this->publicMenuColor = $conn>escape_string($_POST["publicMenuColor"]);
		$this->publicMenuColorHover = $conn>escape_string($_POST["publicMenuColorHover"]);
		$this->publicTextColor = $conn>escape_string($_POST["publicTextColor"]);
		$this->publicTextColorHover = $conn>escape_string($_POST["publicTextColorHover"]);
		$this->publicFontFamily = $conn>escape_string($_POST["publicFontFamily"]);
		$this->publicFontSizeTitle = $conn>escape_string($_POST["publicFontSizeTitle"]);
		$this->publicFontSizeHeading = $conn>escape_string($_POST["publicFontSizeHeading"]);
		$this->publicFontSizeMenu = $conn>escape_string($_POST["publicFontSizeMenu"]);
		$this->publicFontSizeText = $conn>escape_string($_POST["publicFontSizeText"]);
		$this->pageMode = $_POST['mode'];	
	}

	private function createSettings(){

		
			$sql = " INSERT INTO sitewide_settings ";
			$sql .= " (site_title, ";
			$sql .= " organization, ";
			$sql .= " organization_description, ";
			$sql .= " site_url, ";
			$sql .= " contact_name, ";
			$sql .= " contact_email, ";
			$sql .= " public_fill_color, ";
			$sql .= " public_site_color, ";
			$sql .= " public_page_color, ";
			$sql .= " public_menu_color, ";
			$sql .= " public_menu_color_hover, ";
			$sql .= " public_text_color, ";
			$sql .= " public_text_color_hover, ";
			$sql .= " public_font_family, ";
			$sql .= " public_font_size_title, ";
			$sql .= " public_font_size_heading, ";
			$sql .= " public_font_size_menu, ";
			$sql .= " public_font_size_text, ";
			$sql .= " show_public_site, ";
			$sql .= " show_cost_reports, ";
			$sql .= " show_revenue_reports ";
			$sql .= " ) ";
			$sql .= " VALUES (";
			$sql .= " '".$this->siteTitle."', ";
			$sql .= " '".$this->organization."', ";
			$sql .= " '".$this->organizationDescription."', ";
			$sql .= " '".$this->siteUrl."', ";
			$sql .= " '".$this->contactName."', ";
			$sql .= " '".$this->contactEmail."', ";
			$sql .= " '".$this->publicFillColor."', ";
			$sql .= " '".$this->publicSiteColor."', ";
			$sql .= " '".$this->publicPageColor."', ";
			$sql .= " '".$this->publicMenuColor."', ";
			$sql .= " '".$this->publicMenuColorHover."', ";
			$sql .= " '".$this->publicTextColor."', ";
			$sql .= " '".$this->publicTextColorHover."', ";
			$sql .= " '".$this->publicFontFamily."', ";			
			$sql .= " ".$this->publicFontSizeTitle.", ";
			$sql .= " ".$this->publicFontSizeHeading.", ";
			$sql .= " ".$this->publicFontSizeMenu.", ";
			$sql .= " ".$this->publicFontSizeText.", ";			
			$sql .= " '".$this->showPublicSite."', ";			
			$sql .= " '".$this->showCostReports."', ";			
			$sql .= " '".$this->showRevenueReports."' ";			
			$sql .= " ) ";
			
		$locale = 'sitewideSettings->createSettings:';
		//$result = dbRunSQL($sql, $locale);
		global $conn;
		$result = $conn->query($sql) or exit($locale.$conn->error);

	}
	
	public function saveChanges(){
			
		if ($this->pageMode == 'EDIT'){
			$sql = " UPDATE sitewide_settings AS sw ";
			$sql .= " SET ";
			$sql .= " sw.site_title = '".$this->siteTitle."', ";
			$sql .= " sw.site_url = '".$this->siteUrl."', ";
			$sql .= " sw.login_notice = '".$this->loginNotice."', ";
			$sql .= " sw.organization = '".$this->organization."', ";
			$sql .= " sw.organization_url = '".$this->organizationUrl."', ";
			$sql .= " sw.organization_description = '".$this->organizationDescription."', ";
			$sql .= " sw.contact_name = '".$this->contactName."', ";
			$sql .= " sw.contact_email = '".$this->contactEmail."', ";
			$sql .= " sw.public_fill_color = '".$this->publicFillColor."', ";
			$sql .= " sw.public_site_color = '".$this->publicSiteColor."', ";
			$sql .= " sw.public_page_color = '".$this->publicPageColor."', ";
			$sql .= " sw.public_menu_color = '".$this->publicMenuColor."', ";
			$sql .= " sw.public_menu_color_hover = '".$this->publicMenuColorHover."', ";
			$sql .= " sw.public_text_color = '".$this->publicTextColor."', ";
			$sql .= " sw.public_text_color_hover = '".$this->publicTextColorHover."', ";
			$sql .= " sw.public_font_family = '".$this->publicFontFamily."', ";			
			$sql .= " sw.public_font_size_title = ".$this->publicFontSizeTitle.", ";			
			$sql .= " sw.public_font_size_heading = ".$this->publicFontSizeHeading.", ";			
			$sql .= " sw.public_font_size_menu = ".$this->publicFontSizeMenu.", ";			
			$sql .= " sw.public_font_size_text = ".$this->publicFontSizeText.", ";			
			$sql .= " sw.show_public_site = '".$this->showPublicSite."', ";
			$sql .= " sw.show_cost_reports = '".$this->showCostReports."', ";
			$sql .= " sw.show_revenue_reports = '".$this->showRevenueReports."' ";

		$locale = 'sitewideSettings->createSettings:';
		//$result = dbRunSQL($sql, $locale);
		global $conn;
		$result = $conn->query($sql) or exit($locale.$conn->error);
		
			$this->setSessionDetails();
		}
	
	}
	
} 

class SitewideSettingsSQL{
public function columnsSitewideSettings(){
	$c = " sw.site_title, ";
	$c .= " sw.site_url, ";
	$c .= " sw.organization, ";
	$c .= " sw.organization_url, ";
	$c .= " sw.organization_description, ";
	$c .= " sw.contact_name, ";
	$c .= " sw.contact_email, ";
	$c .= " sw.login_notice, ";
	$c .= " sw.public_fill_color, ";
	$c .= " sw.public_site_color, ";
	$c .= " sw.public_page_color, ";
	$c .= " sw.public_menu_color, ";
	$c .= " sw.public_menu_color_hover, ";
	$c .= " sw.public_text_color, ";
	$c .= " sw.public_text_color_hover, ";
	$c .= " sw.public_font_family, ";
	$c .= " sw.public_font_size_title, ";
	$c .= " sw.public_font_size_heading, ";
	$c .= " sw.public_font_size_menu, ";
	$c .= " sw.public_font_size_text, ";
	$c .= " sw.show_public_site, ";
	$c .= " sw.show_cost_reports, ";
	$c .= " sw.show_revenue_reports ";
	
	return $c;	
}
public function infoSitewideSettings(){
$q = " SELECT ";	
$q .= $this->columnsSitewideSettings();
$q .= " FROM sitewide_settings sw ";
return $q;
}
}
?>
