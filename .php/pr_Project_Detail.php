<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$projectId = sessionVariableGET('projectId',0);
$resultsPage = sessionVariableGET('resultsPage', 1);
$_SESSION['currentProjectId'] = $projectId;
//$parentId = sessionVariableGET('parentId',0);

$p = new Project;
$p->setDetails($projectId, $pageAction,$resultsPage);

$p->printPage();
?>