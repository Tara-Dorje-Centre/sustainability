<?php 
include_once("_includes.php");

$um = new SiteSettings;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';

include_once("sys_SiteSettings_Detail.php");
?>
