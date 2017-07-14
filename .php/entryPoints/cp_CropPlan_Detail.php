<?php 
include_once("_includes.php");

$cropPlanId = sessionVariableGET('cropPlanId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');
$resultsPage = sessionVariableGET('resultsPage', 1);
$sortMethod = sessionVariableGET('sortMethod','CROP');
$_SESSION['currentCropPlanId'] = $cropPlanId;

$c = new CropPlan;
$c->setDetails($cropPlanId,$pageAction, $resultsPage);
$c->sortMethod = $sortMethod;

$c->printPage();
?>