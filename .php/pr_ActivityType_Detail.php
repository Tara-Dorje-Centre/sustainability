﻿<?php 
include_once("_includes.php");

$typeId = sessionVariableGET('activityTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new ActivityType;
$t->setDetails($typeId, $pageAction);

$t->printPage();
?>