<?php
include_once("_includes.php");

$viewMode = 'MAIN';
if (isset($_GET['viewMode'])){
$viewMode = $_GET['viewMode'];
}

$viewId = 0;
if (isset($_GET['viewId'])){
$viewId = $_GET['viewId'];
}

$paging = 0;
if (isset($_GET['paging'])){
$paging = $_GET['paging'];
}

$site = new \application\publicSite;
$site->setDetails($viewMode,$viewId,$paging);
$site->print();
?>
