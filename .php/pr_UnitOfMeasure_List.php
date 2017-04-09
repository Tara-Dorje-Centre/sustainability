<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);

$u = new UnitOfMeasureList;
$u->setDetails($resultPage, $detailsPerPage);

$u->printPage();
?>