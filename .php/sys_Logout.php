<?php 
//session_start();
include_once("_includes.php");

	unset($_SESSION['logged-in']);
	unset($_SESSION['client-time-zone']);
	unset($_SESSION['login-name']);
	unset($_SESSION['user-id']);
	unset($_SESSION['is-admin']);
	unset($_SESSION['must-update-pwd']);	
	unset($_SESSION['currentCropPlanId']);
	unset($_SESSION['currentProjectId']);
	unset($_SESSION['currentTaskId']);	
	unset($_SESSION['currentView']);
	$sw = new SiteSettings;
	$sw->unsetSessionDetails();

$site = new _htmlSite();
$site->set();
$site->print();

?>
