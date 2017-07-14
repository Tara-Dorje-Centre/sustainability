<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);

$c = new CropPlanList;
$c->setDetails($resultPage, $detailsPerPage);

$c->printPage();
?>