<?php 
include_once("_includes.php");

$t = new Task;
$t->collectPostValues();
$t->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['taskId'] = $t->id;
$_GET['projectId'] = $t->project->id;
//include_once("pr_Task_Detail.php");

include_once("pr_Project_Detail.php");
?>