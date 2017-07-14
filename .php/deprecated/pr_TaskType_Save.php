<?php 
include_once("_includes.php");

$um = new TaskType;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';
//$_GET['taskTypeId'] = $um->id;

include_once("pr_TaskType_List.php");
?>