<?php 
include_once("_includes.php");

$measureTypeId = sessionVariableGET('id',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new MeasureType($pageAction, $measureTypeId);
$t->setDetails();

$t->printPage();
?>
