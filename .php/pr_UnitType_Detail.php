<?php 
include_once("_includes.php");

$id = sessionVariableGET('id',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$u = new unitType($pageAction, $id);
$u->setDetails();

$u->printPage();
?>
