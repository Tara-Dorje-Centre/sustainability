<?php 
include_once("_includes.php");

$userId = sessionVariableGET('userId',0);
$userTypeId = sessionVariableGET('userTypeId', 0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$u = new User($pageAction, $userId);
$u->= setUserType($userTypeId);
$u->setDetails();

$u->printPage();
?>
