<?php 
include_once("_includes.php");
$detailsPerPage = 15;

$taskId = sessionVariableGET('taskId',0);
$resultPage = sessionVariableGET('resultsPageMeasure',1);

$ml = new MeasureList('VIEW', 0, $taskId);
$ml->setPaging($resultPage, $detailsPerPage);
$ml->setDetails();
$ml->printPage();
?>
