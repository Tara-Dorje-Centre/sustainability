<?php 
include_once("_includes.php");

$typeId = sessionVariableGET('taskTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new TaskType($pageAction, $typeId);
$t->setDetails();

$t->printPage();
?>
