<?php 
include_once("_includes.php");
$detailsPerPage = 25;

$taskId = sessionVariableGET('taskId',0);
$projectId = sessionVariableGET('projectId',0);
$displayProject = sessionVariableGET('displayProject', 'TASK');
$approvedReceipts = sessionVariableGET('approvedReceipts', 'no');
$resultPage = sessionVariableGET('resultsPageReceipt',1);

$ml = new ReceiptList('VIEW', 0, $taskId);
$ml->setPaging($resultPage, $detailsPerPage);
$ml->setDisplay($displayProject);
$ml->setApproved(approvedReceipts);
$ml->setDetails();

$ml->printPage();
?>
