<?php 
include_once("_includes.php");
$detailsPerPage = 15;

$taskId = sessionVariableGET('taskId',0);
$resultPage = sessionVariableGET('resultsPageMeasure',1);

$ml = new MeasureList;
$ml->setDetails($taskId, $resultPage, $detailsPerPage);

$ml->printPage();
?>