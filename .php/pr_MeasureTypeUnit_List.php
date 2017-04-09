<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);
$measureTypeId = sessionVariableGET('measureTypeId',0);

$mtu = new MeasureTypeUnitList;
$mtu->setDetails($measureTypeId,$resultPage, $detailsPerPage);

$mtu->printPage();
?>