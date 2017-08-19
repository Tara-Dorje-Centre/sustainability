<?php 
session_start(); 
//echo 'started session from _includes.php<br />';
//session_regenerate_id(TRUE);
include_once("manifest.inc");
//finish standard include process by opening active db connection
$pathFramework = './framework/';
$pathEntities = './entities/';
include_once($pathFramework."_dbconnect.php");
?>
