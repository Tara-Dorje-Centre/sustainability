﻿<?php 
include_once("_includes.php");
$resultPage = 1;
$detailsPerPage = 10; 

$resultPage = sessionVariableGET('resultPageUsers', 1);
$userTypeId = sessionVariableGET('userTypeId',0);

$u = new UserList;
$u->setPaging($resultPage, $detailsPerPage);
$u->setDetails();
$u->printPage();
?>
