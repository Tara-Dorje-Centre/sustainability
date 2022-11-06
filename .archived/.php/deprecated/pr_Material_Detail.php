<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$materialId = sessionVariableGET('materialId',0);
$taskId = sessionVariableGET('taskId',0);

$m = new Material($pageAction, $materialId, $taskId);
$m->setDetails();

$m->printPage();
?>
