<?php

global $conn;


class WebSiteLinks extends _Links{
	public function __construct($menuType = 'LIST',$styleBase = 'site'){
		parent::__construct($menuType,$styleBase);
	}
	
	public function menuHref($caption, $viewMode = 'MAIN', $viewId = 0,$cssSuffix = ''){
		$link = $this->menu($viewMode, $viewId);
		$href = $this->formatHref($caption,$link,'_self',$cssSuffix);
		return $href;	
	}
	public function menu($viewMode = 'MAIN', $viewId = 0){
		$link = 'public.php';
		$link .= '?viewMode='.$viewMode;
		$link .= '&viewId='.$viewId;
		return $link;
	}
	public function menuPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&paging=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	
	
}

class PublicSite extends _TemplatePublic{
	private $links;
	private $sql;
	private $viewMode = 'MAIN';
    private $viewId = 0;
	private $paging = 0;
	private $found = 0;
	
	public function __construct(){
		$this->links = new WebSiteLinks;
		$this->sql = new WebSiteSQL;	
		
	}

	public function setDetails($viewMode = 'MAIN', $viewId = 0, $paging = 0){

		$this->viewMode = $viewMode;
		$this->viewId = $viewId;
		$this->paging = $paging;
	
		$this->getStyleDetails();
		$this->getSiteContents();	
		$this->getMenuItems();
		$this->getPageContents();
		$this->getPageDetails();
	}

	protected function getStyleDetails(){
		$sql = $this->sql->siteStyles();

		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	
			$this->fillColor = $row["public_fill_color"];
			$this->siteColor = $row["public_site_color"];
			$this->pageColor = $row["public_page_color"];
			$this->menuColor = $row["public_menu_color"];
			$this->menuColorHover = $row["public_menu_color_hover"];
			$this->textColor = $row["public_text_color"];
			$this->textColorHover = $row["public_text_color_hover"];
			$this->fontFamily = $row["public_font_family"];
			$this->fontSizeTitle = $row["public_font_size_title"];
			$this->fontSizeHeading = $row["public_font_size_heading"];
			$this->fontSizeMenu = $row["public_font_size_menu"];
			$this->fontSizeText = $row["public_font_size_text"];
					
		}
		$result->close;
		}

	}

	private function getSiteContents(){
		$sql = $this->sql->siteContent();
		
		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	
			$org = $row['organization'];
			$orgUrl = $row['organization_url'];
			$contactName = $row['contact_name'];
			$contactEmail = $row['contact_email'];
			$showPublicSite = $row['show_public_site'];
		}
		$result->close;
		}
		
		if ($showPublicSite == 'no'){
			include('sys_Login.php');
			die;
		}
		
		$link = getHref($orgUrl,$org,'none','_blank');
		$mailto = getHref('mailto:'.$contactEmail,$contactName,'none');

		$year = getSessionYear();
		
		$this->mainTitle = $org;
		//$this->mainImage = image('images/logo.gif','current site image',150,75,0,'public-site-image');
		$this->mainFooter = '&copy;'.$year.spacer().$link.spacer(4);
		$this->mainFooter .= 'Please contact '.spacer().$mailto.' for more information.';
		$this->mainFooter .= spacer().getHref('sys_Login.php', 'Project Planning Site','none','_self');
	}
	
	private function getMenuItems(){
		$menu = $this->links->openMenu('site-menu-content');
		$menu .= $this->links->menuHref('Main Menu');
		
		$sql = $this->sql->menuItems($this->viewMode, $this->viewId);
		
		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	

//		view_id
//		view_mode
//		caption
//		sort_order

			$caption = $row['caption'];
			$viewMode = $row['view_mode'];
			$viewId = $row['view_id'];

			/**
			if ($viewMode == 'PROJECT_TYPE'){
			} elseif ($viewMode == 'PROJECT'){
			} elseif ($viewMode == 'TASK'){
			} else {
			}
			**/
			
			if ($this->viewMode == $viewMode && $this->viewId == $viewId){
				//menu item is for current page
				$cssSuffix = '-current';	
			} else {
				$cssSuffix = '';
			}
				
			$menu .= $this->links->menuHref($caption,$viewMode,$viewId,$cssSuffix);
		}		
		$result->close;
		}
		
		$menu .= $this->links->closeMenu();
		
		$this->menuItems = $menu;
	}

	private function getPageContents(){
	
		$sql = $this->sql->pageContent($this->viewMode, $this->viewId);
	
		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{			
			$this->pageTitle = $row['title'];
			$this->pageContents = displaylines($row['content']);	
		}
		$result->close;
		}
		
	}
	
	private function getPageDetails(){
		$allDetails = br();
		$sql = $this->sql->detailContent($this->viewMode, $this->viewId);

		$result = dbGetResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{			
			
			$heading = displaylines($row["heading"]);
			if (strlen($heading) > 0){
				$item = paragraph($heading,'detail-heading','public-detail-title');
				$allDetails .= $item;
			}
			
			$contents = displaylines($row["content"]);
			if (strlen($contents) > 0){
				$item = paragraph($contents,'detail-item','public-detail-item');
				$allDetails .= $item;
			}

			$linkText = $row["link_text"];
			$linkUrl = $row["link_url"];
			if (strlen($linkText) > 0 && strlen($linkUrl) > 0){
				$link = getHref($linkUrl, $linkText, 'none', '_blank');
				$item = paragraph($link,'detail-link','public-detail-item');	
				$allDetails .= $item;
			}
			//$item = paragraph($contents,'detail-item','public-detail-item');	
			//$allDetails .= $item;
		}
		$result->close;
		}

		$this->pageDetails = $allDetails;	
	}

}

class WebSiteSQL{

public function menuItems($viewMode = 'MAIN', $viewId = 0){

//echo 'viewMode='.$viewMode;

	if ($viewMode == 'MAIN'){
		
			//get list of all project types sorted by project type display order
		$q = " SELECT 'PROJECT_TYPE' as view_mode, pt.id as view_id, ";
		$q .= " pt.name as caption, pt.display_order as sort_key ";
		$q .= " FROM project_types as pt ";
		//only show project types with open projects
		$q .= " WHERE pt.id IN ";
		$q .= "       (SELECT DISTINCT p.type_id FROM projects as p ";
		$q .= "       WHERE p.show_always != 'no' AND p.pct_done < 1) "; 
		$q .= " ORDER BY sort_key, caption ";	
		
	} elseif ($viewMode == 'PROJECT_TYPE'){
		//show project type
		$q = " SELECT 'PROJECT_TYPE' as view_mode, pt.id as view_id, ";
		$q .= " pt.name as caption, -1 as sort_key ";
		$q .= " FROM project_types as pt ";
		$q .= " WHERE pt.id = ".$viewId." UNION ALL ";
		//show projects for this project type sorted by location, priority and name
		$q .= " SELECT 'PROJECT' as view_mode, p.id as view_id, ";
		$q .= " p.name as caption, l.sort_key as sort_key ";
		$q .= " FROM projects as p INNER JOIN locations as l ON p.location_id = l.id ";
		$q .= " LEFT OUTER JOIN location_types lt ON l.type_id = lt.id ";
		$q .= " WHERE p.show_always != 'no' AND p.type_id = ".$viewId." AND p.pct_done < 1 ";
		$q .= " ORDER BY sort_key, caption ";	

	} elseif ($viewMode == 'PROJECT'){
		$q = " SELECT 'PROJECT_TYPE' as view_mode, pt.id as view_id, ";
		$q .= " pt.name as caption, -2 as sort_key ";
		$q .= " FROM projects as p INNER JOIN project_types as pt ON p.type_id = pt.id ";
		$q .= " WHERE p.show_always != 'no' AND p.id = ".$viewId." UNION ALL ";
		//get project as top item, union with tasks for this project
		$q .= " SELECT 'PROJECT' as view_mode, p.id as view_id, ";
		$q .= " p.name as caption, -1 as sort_key ";
		$q .= " FROM projects as p ";
		$q .= " WHERE p.show_always != 'no' AND p.id = ".$viewId." UNION ALL ";			
		//get list of tasks	for a project	
		$q .= " SELECT 'TASK' as view_mode, t.id as view_id, ";
		$q .= " t.name as caption, t.task_order as sort_key ";
		$q .= " FROM tasks as t ";
		$q .= " WHERE t.project_id = ".$viewId;
		$q .= " ORDER BY sort_key ";
			
	} elseif ($viewMode == 'TASK'){
		$q = " SELECT 'PROJECT_TYPE' as view_mode, pt.id as view_id, ";
		$q .= " pt.name as caption, -2 as sort_key ";
		$q .= " FROM tasks as t INNER JOIN projects as p ON t.project_id = p.id ";
		$q .= " INNER JOIN project_types as pt ON p.type_id = pt.id ";
		$q .= " WHERE p.show_always != 'no' AND t.id = ".$viewId." UNION ALL ";
			//get project as top item, union with tasks for this project
		$q .= " SELECT 'PROJECT' as view_mode, p.id as view_id, ";
		$q .= " p.name as caption, -1 as sort_key ";
		$q .= " FROM tasks as t INNER JOIN projects as p ON t.project_id = p.id ";
		$q .= " WHERE p.show_always != 'no' AND t.id = ".$viewId." UNION ALL ";			
		//get list of tasks	for a project	
		$q .= " SELECT 'TASK' as view_mode, t.id as view_id, ";
		$q .= " t.name as caption, t.task_order as sort_key ";
		$q .= " FROM tasks as t INNER JOIN tasks as filter ON t.project_id = filter.project_id ";
		$q .= " WHERE filter.id = ".$viewId;
		$q .= " ORDER BY sort_key ";

			
	}

	return $q;
	
}
	
public function pageContent($viewMode = 'MAIN', $viewId = 0){
	
	if ($viewMode == 'MAIN'){
		//viewing MAIN menu option
		$q = " SELECT sw.organization as title, ";
		$q .= " sw.organization_description as content ";
		$q .= " FROM sitewide_settings as sw ";
	} elseif ($viewMode == 'PROJECT_TYPE'){
		//viewing a project type
		$q = " SELECT pt.name as title, ";
		$q .= " pt.description as content ";
		$q .= " FROM project_types as pt ";
		$q .= " WHERE pt.id = ".$viewId." ";
	} elseif ($viewMode == 'PROJECT'){
		//viewing a project
		$q = " SELECT p.name as title, ";
		$q .= " concat_ws(' ', p.description, p.purpose, p.summary) as content ";
		$q .= " FROM projects as p JOIN project_types as pt ON p.type_id = pt.id ";
		$q .= " WHERE p.show_always != 'no' AND p.id = ".$viewId." ";
		//$q .= " ORDER BY pt.display_order ";	
	} else {
		//viewing a task
		$q = " SELECT t.name as title, ";
		$q .= "concat_ws(' ', t.description, t.summary) as content ";
		$q .= " FROM projects p JOIN tasks as t ON p.id = t.project_id ";
		$q .= " WHERE p.show_always != 'no' AND t.id = ".$viewId." ";
		//$q .= " ORDER BY t.task_order ";
	}
	//echo $q;
	return $q;
}

public function detailContent($viewMode = 'MAIN', $viewId = 0){

	if ($viewMode == 'TASK'){
		//page content displays a task
		//include activities union all materials union all measures?
		$q = " SELECT date(a.started) as sort_key, '1' as subsort_key, ";
		$q .= " null as heading, a.comments AS content, ";
		$q .= " at.name AS type, a.link_text, a.link_url ";
		$q .= " FROM tasks as t JOIN activities AS a ON t.id = a.task_id ";
		$q .= " LEFT OUTER JOIN activity_types AS at ON a.type_id = at.id ";
		$q .= " WHERE t.id = ".$viewId;
		$q .= " AND ";
		$q .= " ((CHAR_LENGTH(a.comments) > 0)";
		$q .= " OR ";
		$q .= " ((CHAR_LENGTH(a.link_text) > 0) AND (CHAR_LENGTH(a.link_url) > 0))) ";
		$q .= " ORDER BY sort_key, subsort_key ";
	} elseif ($viewMode == 'PROJECT'){
		//page content displays a project
		//display tasks as details
		$q = " SELECT t.task_order as sort_key, t.name as heading, ";
		$q .= " concat_ws(' ', t.description, t.summary) as content, ";
		$q .= " tt.name as type, null as link_text, null as link_url ";
		$q .= " FROM tasks as t LEFT OUTER JOIN task_types as tt ON t.type_id = tt.id ";
		$q .= " WHERE t.project_id = ".$viewId." ";
		$q .= " ORDER BY sort_key ";
	} elseif ($viewMode == 'PROJECT_TYPE'){
		
		$q = " SELECT l.sort_key as sort_key, p.name as heading, p.description as content, ";
		$q .= " 'Project' as type, null as link_text, null as link_url ";
		$q .= " FROM projects p	LEFT OUTER JOIN locations l ON p.location_id = l.id ";
		$q .= " WHERE p.show_always != 'no' AND p.pct_done < 1 AND p.type_id = ".$viewId." ORDER BY sort_key ";
		
	} else {
		//viewing main, project id and task id are set to 0
		$q = " SELECT '-3' as sort_key, null as heading, ";
		$q .= " null as content, ";
		$q .= " 'Organization' as type, sw.organization as link_text, ";
		$q .= " sw.organization_url as link_url ";
		$q .= " FROM sitewide_settings as sw ";		
		$q .= " UNION ALL ";
		$q .= " SELECT '-2' as sort_key, null as heading, ";
		$q .= " null as content, ";
		$q .= " 'Contact Name' as type, sw.contact_name as link_text, ";
		$q .= " concat_ws(':','mailto',sw.contact_email) as link_url ";		
		$q .= " FROM sitewide_settings as sw ";	
		$q .= " UNION ALL ";		
		$q = " SELECT pt.display_order as sort_key, pt.name as heading, ";
		$q .= " pt.description as content, ";
		$q .= " 'Project Type' as type, null as link_text, null as link_url ";
		$q .= " FROM project_types as pt ";
		//only show project types with open projects
		$q .= " WHERE pt.id IN ";
		$q .= "         (SELECT DISTINCT p.type_id FROM projects as p ";
		$q .= "          WHERE p.show_always != 'no' AND p.pct_done < 1) "; 		
		$q .= " ORDER BY sort_key ";
		//$q = " SELECT null as content, null as heading, null as type, ";
		//$q .= " null as link_text, null as link_url FROM dual ";
	}
	//echo $q;
	return $q;

}

public function detailCount($viewMode = 'MAIN', $projectTypeId = 0, $projectId = 0, $taskId = 0){

	if ($projectId > 0 && $taskId > 0){
		$q = " SELECT count(*) as detail_count ";
		$q .= " FROM activities AS a left outer join activity_types AS at on a.type_id = at.id ";
		$q .= " WHERE a.task_id = ".$taskId;
		$q .= " AND ";
		$q .= " ((CHAR_LENGTH(a.comments) > 0)";
		$q .= " OR ";
		$q .= "((CHAR_LENGTH(a.link_text) > 0) AND (CHAR_LENGTH(a.link_url) > 0))) ";
		//$q .= " and at.name like 'Web%' ";	
	} else {
		$q = " SELECT 0 as detail_count FROM dual ";
	}
	//echo $q;
	return $q;

}

public function siteStyles(){
	$q = " SELECT ";
	$q .= " sw.public_fill_color, ";
	$q .= " sw.public_site_color, ";
	$q .= " sw.public_page_color, ";
	$q .= " sw.public_menu_color, ";
	$q .= " sw.public_menu_color_hover, ";
	$q .= " sw.public_text_color, ";
	$q .= " sw.public_text_color_hover, ";
	$q .= " sw.public_font_family, ";
	$q .= " sw.public_font_size_title, ";
	$q .= " sw.public_font_size_heading, ";
	$q .= " sw.public_font_size_menu, ";
	$q .= " sw.public_font_size_text ";
	$q .= " FROM sitewide_settings sw ";
	return $q;
}

public function siteContent(){
	
	$q = " SELECT sw.organization, sw.organization_url, sw.contact_name, sw.contact_email, sw.show_public_site ";
	$q .= " FROM sitewide_settings sw ";
	return $q;	
		
}

	
}


 
?>
