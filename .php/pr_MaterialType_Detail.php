<?php 
include_once("_includes.php");

$typeId = sessionVariableGET('materialTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new MaterialType;
$t->setDetails($typeId, $pageAction);

$t->printPage();
?>