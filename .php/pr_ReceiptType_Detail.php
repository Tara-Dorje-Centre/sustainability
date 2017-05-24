<?php 
include_once("_includes.php");

$typeId = sessionVariableGET('receiptTypeId',0);
$pageAction = sessionVariableGET('pageAction','VIEW');

$t = new ReceiptType($pageAction, $typeId);
$t->setDetails();

$t->printPage();
?>
