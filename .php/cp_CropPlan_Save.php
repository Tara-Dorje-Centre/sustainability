<?php 
include_once("_includes.php");

$c = new CropPlan;
$c->collectPostValues();
$c->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['cropPlanId'] = $c->id;

include_once("cp_CropPlan_Detail.php");
?>