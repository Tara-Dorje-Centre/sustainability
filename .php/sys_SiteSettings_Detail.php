<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');

$sw = new SiteSettings;
$sw->setDetails($pageAction);

$sw->printPage();
?>
