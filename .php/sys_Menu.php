<?php 
include_once("_includes.php");

$m = new sysMenu();
$m->getRequestArguments();
$m->printPage();
?>
