<?php 
include_once("_includes.php");

$typeId = sessionVariableGET('projectTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new ProjectType($pageAction, $typeId);
$t->setDetails();

$t->printPage();
?>
