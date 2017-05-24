<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$locationId = sessionVariableGET('locationId',0);
$parentLocationId = sessionVariableGET('parentLocationId',0);

$l = new Location($pageAction, $locationId, $parentLocationId);
$l->setDetails();
$l->printPage();
?>
