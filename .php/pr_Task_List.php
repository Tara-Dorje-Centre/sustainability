<?php 
include_once("_includes.php");

$detailsPerPage = 10;

$projectId = sessionVariableGET('projectId', -1);
$resultPage = sessionVariableGET('resultsPage', 1);
$periodicTasks = sessionVariableGET('periodicTasks','INCOMPLETE');
if ($periodicTasks == 'INCOMPLETE' || $periodicTasks == 'COMPLETE'){
	$_SESSION['currentView'] = 'PERIODIC_TASKS';
}

$tl = new TaskList;
$tl->setDetails($projectId, $resultPage, $detailsPerPage,$periodicTasks);

$tl->printPage();
?>