<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$page = sessionVariableGET('resultsPage', 1);

$t = new MaterialTypeList(VIEW, 0, 0, $page, $detailsPerPage);
//$t->setPaging($resultPage, $detailsPerPage);
$t->setDetails();
$t->printPage();
?>
