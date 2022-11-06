<?php 
include_once("_includes.php");

$mt = new MeasureType;
$mt->collectPostValues();
$mt->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['measureTypeId'] = $mt->id;
include_once("pr_MeasureType_List.php");
?>