<?php
namespace application\system\sql;

class siteSettingsSQL extends \application\sql\entitySQL{
	public function __construct(){
		$this->baseTable = 'site_settings';

	}
	


protected function cols(){
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
public function info($id = 0){
$q = " SELECT ";	
$q .= $this->cols();
$q .= " FROM sitewide_settings sw ";
return $q;
}

public function count(){
		$s = " SELECT COUNT(*) AS count_settings ";
		$s .= " FROM sitewide_settings ";
		return $s;
}
}
?>
