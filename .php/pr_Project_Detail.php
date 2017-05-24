<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$projectId = sessionVariableGET('projectId',0);
$resultsPage = sessionVariableGET('resultsPage', 1);
$_SESSION['currentProjectId'] = $projectId;

//$parentId = sessionVariableGET('parentId',0);

$p = new Project($pageAction, $projectId);
$p->setPaging($resultsPage);
$p->setDetails();
$p->printPage();
?>
