<?php
session_start(); 
//echo 'started session from _includes.php<br />';
//session_regenerate_id(TRUE);
include_once("systemManifest.inc");

	global $manifest;
	$manifest = new systemManifest();
	$manifest->initialize();




 function sessionVariableGET($id,$default){
if (isset($_GET[$id])){		
$v = $_GET[$id];
} else {
$v = $default;
}
return $v;
}

?>
