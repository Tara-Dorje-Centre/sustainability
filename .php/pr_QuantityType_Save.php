<?php 
include_once("_includes.php");

$um = new QuantityType();
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['unitOfMeasureId'] = $um->id;

include_once("pr_QuantityType_Detail.php");
?>
