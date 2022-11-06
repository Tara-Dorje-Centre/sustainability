<?php 
include_once("_includes.php");

$um = new UserType;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';
//$_GET['projectTypeId'] = $um->id;

include_once("sys_UserType_List.php");
?>