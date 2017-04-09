<?php 
include_once("_includes.php");

$um = new LocationType;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';
include_once("pr_LocationType_List.php");
?>