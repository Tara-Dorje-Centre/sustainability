<?php 
include_once("_includes.php");

$um = new SitewideSettings;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';

include_once("sys_SitewideSettings_Detail.php");
?>