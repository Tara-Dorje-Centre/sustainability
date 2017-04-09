<?php 
include_once("_includes.php");

$typeId = sessionVariableGET('userTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new UserType;
$t->setDetails($typeId, $pageAction);

$t->printPage();
?>