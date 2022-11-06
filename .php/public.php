<?php
include_once("_includes.php");

$viewMode = sessionVariableGET('viewMode', 'MAIN');
$viewId = sessionVariableGET('viewId', 0);
$paging = sessionVariableGET('paging', 0);

$site = new _publicSite;
$site->setDetails($viewMode,$viewId,$paging);
$site->print();
?>
