<?php 
include_once("_includes.php");

$cropPlantingId = sessionVariableGET('cropPlantingId',0);
$cropPlanId = sessionVariableGET('cropPlanId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$c = new CropPlanting;
$c->setDetails($cropPlantingId,$cropPlanId,$pageAction);

$c->printPage();
?>