<?php 
include_once("_includes.php");

$p = new Location;
$p->collectPostValues();
$p->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['locationId'] = $p->id;

include_once("pr_Location_Detail.php");
?>