<?php 
include_once("_includes.php");

$um = new ReceiptType;
$um->collectPostValues();
$um->saveChanges();

$_GET['pageAction'] = 'VIEW';
//$_GET['projectTypeId'] = $um->id;

include_once("pr_ReceiptType_List.php");
?>