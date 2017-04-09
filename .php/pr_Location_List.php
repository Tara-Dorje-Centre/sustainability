<?php 
include_once("_includes.php");
$detailsPerPage = 10; 
//default of parent = -1 to fetch all locations
//parent = 0 only gets all with parent of 0
$parentLocationId = sessionVariableGET('parentLocationId', -1);
$resultPage = sessionVariableGET('resultsPage', 1);

$l = new LocationList;
$l->setDetails($parentLocationId, $resultPage, $detailsPerPage);

$l->printPage();
?>