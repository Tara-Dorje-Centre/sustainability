<?php 
include_once("_includes.php");
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultsPage', 1);
$measureTypeId = sessionVariableGET('measureTypeId',0);

$mtu = new MeasureTypeUnitList('VIEW', 0, $measureTypeId);
$mtu->setPaging($resultPage, $detailsPerPage);
$mtu->setDetails();
$mtu->printPage();
?>
