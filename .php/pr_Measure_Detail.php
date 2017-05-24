<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$measureId = sessionVariableGET('measureId',0);
$taskId = sessionVariableGET('taskId',0);

$m = new Measure($pageAction, $measureId, $taskId);
$m->setDetails();

$m->printPage();
?>
