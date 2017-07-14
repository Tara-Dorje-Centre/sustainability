<?php 
include_once("_includes.php");

$mtu = new MeasureTypeUnit;
$mtu->collectPostValues();
$mtu->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['measureTypeUnitId'] = $mtu->id;

include_once("pr_MeasureTypeUnit_List.php");
?>