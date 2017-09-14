<?php 
include_once("_includes.php");

//$pageAction = sessionVariableGET('pageAction','VIEW');

$sw = new SiteSettings($pageAction);
$sw->setDetails();

$sw->printPage();
?>
