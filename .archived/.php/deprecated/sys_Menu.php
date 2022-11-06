<?php 
include_once("_includes.php");

$displayMode = sessionVariableGET('displayMode','MY_LINKS');
$month = sessionVariableGET('month',0);
$year = sessionVariableGET('year',0);

$m = new Menu;
$m->setDetails($displayMode, $year, $month);

$m->printPage();
?>