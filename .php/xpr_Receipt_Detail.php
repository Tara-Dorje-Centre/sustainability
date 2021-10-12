<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$receiptId = sessionVariableGET('receiptId',0);
$taskId = sessionVariableGET('taskId',0);

$m = new Receipt($pageAction, $receiptId, $taskId);
$m->setDetails();

$m->printPage();
?>
