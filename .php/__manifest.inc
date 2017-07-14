<?php

$pathFramework = './framework/';
$pathEntities = './entities/';
include_once($pathFramework."_echo.class");

//
function showProgress($msg, $show = false){
	$e = new _echo();
	$e->echoPrint($show,$msg,'includes','manifest.inc');
}
//

include_once($pathFramework."__manifestFramework.inc");

include_once($pathEntities."__manifestEntities.inc");

showProgress('exit manifest.inc');
?>
