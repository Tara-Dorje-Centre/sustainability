﻿<?php 
include_once("_includes.php");

$measureTypeId = sessionVariableGET('measureTypeId',0);
$measureTypeUnitId = sessionVariableGET('measureTypeUnitId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$mtu = new MeasureTypeUnit;
$mtu->setDetails($measureTypeUnitId, $measureTypeId,$pageAction);

$mtu->printPage();
?>