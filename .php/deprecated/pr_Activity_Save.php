<?php 
include_once("_includes.php");

$a = new Activity;
$a->collectPostValues();
$a->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['activityId'] = $a->id;
$_GET['taskId'] = $a->task->id;
//include_once("pr_Activity_Detail.php");
//include_once("pr_Task_Detail.php");

$_GET['projectId'] = '-1';
$_GET['periodicTasks'] = 'INCOMPLETE';
include_once("pr_Task_List.php");
?>