<?php
// Set Database connection variables





global   $conn;
$conn = new _Database();


//$conn is referencable in stand alone functions
//add pseudo var to get pointer   global $conn;
$sessionTimeZone = setSessionTimeZone();
$sessionTime = getSessionTime();



printLine('exiting dbconnect');

?>
