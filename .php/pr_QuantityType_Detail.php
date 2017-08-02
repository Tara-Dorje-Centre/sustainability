<?php 
include_once("_includes.php");

$id = sessionVariableGET('id',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$u = quantityType($pageAction, $id);
$u->setDetails();

$u->printPage();
?>
