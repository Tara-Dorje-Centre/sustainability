<?php 
include_once("_includes.php");
$resultPage = 1;
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);

$t = new UserTypeList;
$t->setPaging($resultPage, $detailsPerPage);
$t->setDetails();
$t->printPage();
?>
