<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);

$t = new MeasureTypeList;
$t->setDetails($resultPage, $detailsPerPage);

$t->printPage();
?>