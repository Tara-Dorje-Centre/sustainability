<?php 
include_once("_includes.php");

$p = new \application\portalRequest();

//echo 'loading……………………{'.$p->entity->value().'.'.$p->scope->value().'}'.PHP_EOL;

$p->load();

?>
