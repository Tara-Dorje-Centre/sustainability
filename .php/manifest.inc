<?php


$pathFramework = './framework/';
$pathHtml = $pathFramework.'html/';
$pathEntities = './entities/';
include_once($pathFramework."_echo.class");

//
function showProgress($msg, $show = false){
	$e = new _echo();
	$e->echoPrint($show,$msg,'includes','manifest.inc');
}
//
include_once($pathHtml."manifestHtml.inc");
include_once($pathFramework."manifestFramework.inc");

include_once($pathEntities."manifestEntities.inc");



showProgress('exit manifest.inc');
?>
