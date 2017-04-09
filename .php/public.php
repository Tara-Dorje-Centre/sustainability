<?php 
include_once("_includes.php");
include_once("_publicSite_Classes.php");
//include_once("_htmlFunctions.php");
$viewMode = sessionVariableGET('viewMode', 'MAIN');
$viewId = sessionVariableGET('viewId', 0);
$paging = sessionVariableGET('paging', 0);

$site = new PublicWebSite;
$site->setDetails($viewMode,$viewId,$paging);
$site->printSite();
?>