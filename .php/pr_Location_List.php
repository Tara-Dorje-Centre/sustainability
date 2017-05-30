<?php 
include_once("_includes.php");
$rows = 10; 
//default of parent = -1 to fetch all locations
//parent = 0 only gets all with parent of 0
$parentId = sessionVariableGET('parentLocationId', -1);
$page = sessionVariableGET('resultsPage', 1);

$l = new LocationList('VIEW', 0, $parentId, $page, $rows);

$l->setDetails();
$l->printPage();
?>
