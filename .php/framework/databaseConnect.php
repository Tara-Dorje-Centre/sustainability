<?php
// Set Database connection variables
//echo 'in dbconnect, opening connection';
global   $conn;
$conn = new \framework\sql\_database();
//$conn is referencable in stand alone functions

$sessionTimeZone = $conn->getSessionTimeZone();
$sessionTime = $conn->getSessionTime();

?>
