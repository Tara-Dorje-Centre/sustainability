<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$locationId = sessionVariableGET('locationId',0);
$parentLocationId = sessionVariableGET('parentLocationId',0);

$l = new Location;
$l->setDetails($locationId, $parentLocationId, $pageAction);

$l->printPage();
?>