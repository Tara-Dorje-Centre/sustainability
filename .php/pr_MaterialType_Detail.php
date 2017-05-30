<?php 
include_once("_includes.php");

$id = sessionVariableGET('materialTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new MaterialType($pageAction, $id);
$t->setDetails();

$t->printPage();
?>
