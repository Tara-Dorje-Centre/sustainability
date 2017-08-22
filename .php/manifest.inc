<?php
$pathFramework = './framework/';
$pathHtml = $pathFramework.'html/';
$pathEntities = './entities/';
//echo 'preloading echo framework class'.PHP_EOL;
include_once($pathFramework."_echo.class");
function showProgress($msg, $show = false){
	$e = new _echo();
	$e->echoPrint($show,$msg,'class libraries','Sustainability 2.0');
}
showProgress('libraries loading....');
showProgress('html formatting objects....');
include_once($pathHtml."manifestHtml.inc");
showProgress('framework objects....');
include_once($pathFramework."manifestFramework.inc");
showProgress('entity objects');
include_once($pathEntities."manifestEntities.inc");
showProgress('finished loading libraries.');
?>
