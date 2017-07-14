<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);

$u = new UnitOfMeasureList;
$u->setPaging($resultPage, $detailsPerPage);
$u->setDetails();
$u->printPage();
?>
