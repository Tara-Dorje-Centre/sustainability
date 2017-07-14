<?php 
include_once("_includes.php");

$m = new Receipt;
$m->collectPostValues();
$m->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['receiptId'] = $m->id;
$_GET['taskId'] = $m->task->id;
include_once("pr_Receipt_List.php");
//include_once("pr_Task_Detail.php");
?>