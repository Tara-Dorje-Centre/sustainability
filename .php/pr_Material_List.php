<?php 
include_once("_includes.php");
$detailsPerPage = 10;

$taskId = sessionVariableGET('taskId',0);
$projectId = sessionVariableGET('projectId',0);
$displayProject = sessionVariableGET('displayProject', 'TASK');
$approvedCosts = sessionVariableGET('approvedCosts', 'no');
$resultPage = sessionVariableGET('resultsPageMaterial',1);
$month = sessionVariableGET('month',-1);
$year = sessionVariableGET('year',-1);

$ml = new MaterialList('VIEW', 0, $taskId);
$ml->setPaging($resultPage, $detailsPerPage);
$ml->setCalendar($year, $month);
$ml->setDisplay($displayProject);
$ml->setApproved($approvedCosts);
$ml->setDetails();
$ml->printPage();
?>
