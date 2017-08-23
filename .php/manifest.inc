<?php
$pathFramework = './framework/';
$pathHtml = $pathFramework.'htmlLib/';
$pathHtmlInputs = $pathHtml.'inputsLib/';
$pathCss = $pathHtml.'cssLib/';
$pathEntities = $pathFramework.'./entitiesLib/';
//echo 'preloading echo framework class'.PHP_EOL;
include_once($pathFramework."_echo.class");
function showProgress($msg, $show = false){
	$e = new _echo();
	$e->echoPrint($show,$msg,'class libraries','Sustainability 2.0');
}
showProgress('libraries loading....');
include_once($pathHtml."manifest.inc");
include_once($pathHtmlInputs."manifest.inc");
include_once($pathCss."manifest.inc");
include_once($pathFramework."manifest.inc");
include_once($pathEntities."manifest.inc");
showProgress('finished loading libraries.');
?>
