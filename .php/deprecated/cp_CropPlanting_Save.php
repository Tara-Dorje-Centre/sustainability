<?php 
include_once("_includes.php");

$c = new CropPlanting;
$c->collectPostValues();
$c->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['cropPlantingId'] = $c->id;

include_once("cp_CropPlanting_Detail.php");
?>