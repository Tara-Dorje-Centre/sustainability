<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$showMyProjects = sessionVariableGET('showMyProjects','NO');
$projectStatus = sessionVariableGET('projectStatus', 'OPEN');
$resultPage = sessionVariableGET('resultsPage', 1);

$pl = new ProjectList;
$pl->setDetails($projectStatus, $showMyProjects,$resultPage, $detailsPerPage);

$pl->printPage();
?>