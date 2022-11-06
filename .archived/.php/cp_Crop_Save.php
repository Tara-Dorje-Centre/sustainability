<?php 
include_once("_includes.php");

$c = new Crop;
$c->collectPostValues();
$c->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['cropId'] = $c->id;

include_once("cp_Crop_Detail.php");
?>