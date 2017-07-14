<?php
include_once('_echo.class');
include_once("_contentWriter.class");
//
function showProgress($msg, $show = false){
	$e = new _echo();
	$e->echoPrint($show,$msg,'includes','manifest.inc');
}
//
$pathFramework = './framework/';
include_once($frameworkPath."__manifestFramework.inc");
$pathEntities = './entities/';
include_once($frameworkPath."__manifestEntities.inc");

showProgress('exit manifest.inc');
?>
