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

$t = new Task($pageAction, $taskId, $projectId);
$t->setCalendar($year, $month);
$t->setPagingDetails($pageActivity, $pageMaterial, $pageMeasure, $pageReceipt);
$t->setDetails();
$t->printPage();
?>
