<?php 
include_once("_includes.php");

$p = new Project;
$p->collectPostValues();
$p->saveChanges();

$_GET['pageAction'] = 'VIEW';
$_GET['id'] = $p->id;

include_once("pr_Project_Detail.php");
?>
