<?php 
namespace application\sql;

class publicSiteSQL{
use connectionFunctions;
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
