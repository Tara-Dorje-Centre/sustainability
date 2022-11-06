<?php 
include_once("_includes.php");
$resultPage = 1;
$detailsPerPage = 20; 
$parentCropId = -1;

$parentCropId = sessionVariableGET('parentCropId',-1);
$resultPageCrops = sessionVariableGET('resultPageCrops', 1);

$c = new CropList;
$c->setDetails($parentCropId, $resultPageCrops, $detailsPerPage);

$c->printPage();
?>