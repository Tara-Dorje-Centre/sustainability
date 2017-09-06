<?php
// Set Database connection variables
echo 'in dbconnect, opening connection';
global   $conn;
$conn = new _Database();
//$conn is referencable in stand alone functions
//add pseudo var to get pointer   global $conn;
$sessionTimeZone = setSessionTimeZone();
$sessionTime = getSessionTime();
//printLine('exiting dbconnect');
?>
