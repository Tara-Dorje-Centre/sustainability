<?php 
include_once("_includes.php");

$m = new application\systemMenu();
$m->getRequestArguments();
$m->printPage();
?>
