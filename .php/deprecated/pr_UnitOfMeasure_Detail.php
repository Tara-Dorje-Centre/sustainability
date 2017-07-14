<?php 
include_once("_includes.php");

$unitOfMeasureId = sessionVariableGET('id',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$u = new UnitOfMeasure($pageAction, $unitOfMeasureId);
$u->setDetails();

$u->printPage();
?>
