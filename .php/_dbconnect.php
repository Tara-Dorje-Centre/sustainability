<?php
// Set Database connection variables
global $db_mysqli;

$dbTime;
$dbServer  = "127.0.0.1";
$dbDatabase  = "projects_dev";
$dbUser  = "dev_usr";
$dbPass  = "dev_pwd";
$dbPort = 3306;
//mysql_* functions deprecated php 7
//
//open a php connection to mysql
//$sqlConnection  = mysql_connect($dbServer, $dbUser, $dbPass)
//or die("Couldn't connect to da tabase server");
//open the database
//$db = mysql_select_db($dbDatabase, $sqlConnection )
//or die("Couldn't connect to database $dbDatabase");

//convert to mysqli for php 7
$db_mysqli = new mysqli($dbServer, $dbUser, $dbPass, $dbDatabase, $dbPort);
if ($db_mysqli->connect_error) {
	$msg = 'Connect Error ['.$db_mysqli->connect_errno.'] '.$db_mysqli->connect_error;
    die($msg);
}


//TEMP TIME SYNCH SOLUTION force session to mountain standard time
//user login should set user time zone preference
//select user time zone, then set for all user sessions
//will need to compensate for daylight savings time and standard time automatically
if (isset($_SESSION['logged-in']) && isset($_SESSION['client-time-zone'])){
	$offset = $_SESSION['client-time-zone'];
} else {
	$offset = '-0:00';
}

$sessionTimeZone = setSessionTimeZone($offset);
$sessionTime = getSessionTime();





?>
