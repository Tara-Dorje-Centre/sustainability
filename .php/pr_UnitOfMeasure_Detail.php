<?php 
include_once("_includes.php");

$unitOfMeasureId = sessionVariableGET('unitOfMeasureId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$u = new UnitOfMeasure;
$u->setDetails($unitOfMeasureId, $pageAction);

$u->printPage();
?>