<?php 
include_once("_includes.php");

$userId = sessionVariableGET('userId',0);
$userTypeId = sessionVariableGET('userTypeId', 0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$u = new User;
$u->setDetails($userId, $userTypeId, $pageAction);

$u->printPage();
?>