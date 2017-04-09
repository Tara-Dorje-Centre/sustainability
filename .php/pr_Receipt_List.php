<?php 
include_once("_includes.php");
$detailsPerPage = 25;

$taskId = sessionVariableGET('taskId',0);
$projectId = sessionVariableGET('projectId',0);
$displayProject = sessionVariableGET('displayProject', 'TASK');
$approvedReceipts = sessionVariableGET('approvedReceipts', 'no');
$resultPage = sessionVariableGET('resultsPageReceipt',1);

$ml = new ReceiptList;
$ml->setDetails($taskId, $resultPage, $detailsPerPage, $displayProject, $approvedReceipts, $projectId);

$ml->printPage();
?>