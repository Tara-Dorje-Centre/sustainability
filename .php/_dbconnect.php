<?php
// Set Database connection variables



class db extends mysqli
{


protected $dbTime;
protected $dbServer  = "127.0.0.1";
protected $dbDatabase  = "projects_dev";
protected $dbUser  = "dev_usr";
protected $dbPass  = "dev_pwd";
protected $dbPort = 3306;
//mysql_* functions deprecated php 7
//
//open a php connection to mysql
//$sqlConnection  = mysql_connect($dbServer, $dbUser, $dbPass)
//or die("Couldn't connect to database server");
//open the database
//$db = mysql_select_db($dbDatabase, $sqlConnection )
//or die("Couldn't connect to database $dbDatabase");

//convert to mysqli for php 7

public function __construct(){
	parent::__construct($dbServer, $dbUser, $dbPass, $dbDatabase, $dbPort);
if ($this->connect_error) {
	$msg = 'Connect Error ['.$this->connect_errno.'] '.$this->connect_error;
    die($msg);
} 

}

}
//end class db










global $conn;
$conn = new db;

$sessionTimeZone = setSessionTimeZone();
$sessionTime = getSessionTime();

 



?>
