<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);

$t = new LocationTypeList;
$t->setDetails($resultPage, $detailsPerPage);

$t->printPage();
?>