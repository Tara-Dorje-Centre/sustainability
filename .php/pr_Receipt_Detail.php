<?php 
include_once("_includes.php");

$pageAction = sessionVariableGET('pageAction','VIEW');
$receiptId = sessionVariableGET('receiptId',0);
$taskId = sessionVariableGET('taskId',0);

$m = new Receipt;
$m->setDetails($receiptId, $taskId, $pageAction);

$m->printPage();
?>