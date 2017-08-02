<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);

$u = new quantityTypeList;
$u->setPaging($resultPage, $detailsPerPage);
$u->setDetails();
$u->printPage();
?>
