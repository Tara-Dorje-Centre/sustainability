<?php 
include_once("_includes.php");

$um = new ActivityType;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';
include_once("pr_ActivityType_List.php");
?>