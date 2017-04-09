<?php 
include_once("_includes.php");

$measureTypeId = sessionVariableGET('measureTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new MeasureType;
$t->setDetails($measureTypeId, $pageAction);

$t->printPage();
?>