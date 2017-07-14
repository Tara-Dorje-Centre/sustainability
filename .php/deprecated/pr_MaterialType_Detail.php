<?php 
include_once("_includes.php");

$id = sessionVariableGET('id',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new MaterialType($pageAction, $id);
$t->setDetails();

$t->printPage();
?>
