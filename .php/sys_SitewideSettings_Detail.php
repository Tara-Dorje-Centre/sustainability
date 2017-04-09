<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');

$sw = new SitewideSettings;
$sw->setDetails($pageAction);

$sw->printPage();
?>