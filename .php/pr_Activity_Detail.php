<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$activityId = sessionVariableGET('activityId',0);
$taskId = sessionVariableGET('taskId',0);

$a = new Activity;
$a->setDetails($activityId, $taskId, $pageAction);

$a->printPage();
?>