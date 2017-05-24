<?php 
include_once("_includes.php");

$typeId = sessionVariableGET('locationTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new LocationType($pageAction, $typeId);
$t->setDetails();
$t->printPage();
?>
