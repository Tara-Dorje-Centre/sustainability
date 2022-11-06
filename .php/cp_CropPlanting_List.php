<?php 
include_once("_includes.php");
$detailsPerPage = 20; 

$resultPage = sessionVariableGET('resultsPage', 1);
$cropPlanId = sessionVariableGET('cropPlanId',0);
$sortMethod = sessionVariableGET('sortMethod','CROP');
$showCalendar = sessionVariableGET('showCalendar','NO');
$plantingMonth = sessionVariableGET('month','-1');
$plantingYear = sessionVariableGET('year','-1');

$c = new CropPlantingList;
$c->setDetails($cropPlanId, $resultPage, $detailsPerPage);
$c->sortMethod = $sortMethod;
$c->showCalendar = $showCalendar;
$c->setCalendarRange($plantingYear, $plantingMonth);

$c->printPage();
?>