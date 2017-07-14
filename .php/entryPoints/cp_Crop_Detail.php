<?php 
include_once("_includes.php");

$cropId = sessionVariableGET('cropId',0);
$parentCropId = sessionVariableGET('parentCropId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');
$resultPageCrops = sessionVariableGET('resultPageCrops',1);

$c = new Crop;
$c->setDetails($cropId, $parentCropId,$pageAction);
$c->resultPageCrops = $resultPageCrops;

$c->printPage();
?>