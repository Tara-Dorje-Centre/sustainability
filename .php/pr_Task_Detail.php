<?php 
include_once("_includes.php");

$taskId = sessionVariableGET('taskId',0);
$projectId = sessionVariableGET('projectId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');
$pageMeasure = sessionVariableGET('resultsPageMeasure',1);
$pageActivity = sessionVariableGET('resultsPageActivity',1);
$pageMaterial = sessionVariableGET('resultsPageMaterial',1);
$pageReceipt = sessionVariableGET('resultsPageReceipt',1);
$year = sessionVariableGET('year',-1);
$month = sessionVariableGET('month',-1);

$t = new Task;
$t->setDetails($taskId, $projectId, $pageAction, $year, $month);
$t->setPagingState($pageActivity, $pageMaterial, $pageMeasure, $pageReceipt);

$t->printPage();
?>