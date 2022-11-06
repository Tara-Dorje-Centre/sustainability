<?php 
include_once("_includes.php");

$m = new Measure;
$m->collectPostValues();
$m->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['measureId'] = $m->id;
$_GET['taskId'] = $m->task->id;
include_once("pr_Measure_List.php");
//include_once("pr_Task_Detail.php");
?>