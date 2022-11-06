<?php 
include_once("_includes.php");

$m = new Material;
$m->collectPostValues();
$m->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['materialId'] = $m->id;
$_GET['taskId'] = $m->task->id;
//include_once("pr_Material_Detail.php");
//include_once("pr_Task_Detail.php");
include_once("pr_Material_List.php");
?>