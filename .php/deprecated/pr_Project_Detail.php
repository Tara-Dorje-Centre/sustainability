<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$id = sessionVariableGET('id',0);
$resultsPage = sessionVariableGET('resultsPage', 1);
$_SESSION['currentProjectId'] = $id;

//$parentId = sessionVariableGET('parentId',0);

$p = new Project($pageAction, $id);
$p->setPaging($resultsPage);
$p->setDetails();
$p->printPage();
?>
