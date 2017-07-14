<?php 
include_once("_includes.php");

$um = new ProjectType;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';
//$_GET['projectTypeId'] = $um->id;

include_once("pr_ProjectType_List.php");
?>