<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$locationId = sessionVariableGET('id',0);
$parentLocationId = sessionVariableGET('idParent',0);

$l = new Location($pageAction, $locationId, $parentLocationId);
$l->setDetails();
$l->printPage();
?>
