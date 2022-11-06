<?php 
include_once("_includes.php");

$um = new UnitOfMeasure;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['unitOfMeasureId'] = $um->id;

include_once("pr_UnitOfMeasure_Detail.php");
?>