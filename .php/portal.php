<?php 
include_once("_includes.php");

$p = new \application\portalRequest();
echo '{'.$p->context.'.'.$p->scope.'}'.PHP_EOL;
$p->loadContext();

?>
