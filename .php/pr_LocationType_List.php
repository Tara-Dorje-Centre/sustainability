<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);

$t = new LocationTypeList();
$t->setPaging($resultPage, $detailsPerPage);
$t->setDetails();
$t->printPage();
?>
