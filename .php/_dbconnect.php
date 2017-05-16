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


public function __construct(){

	printLine('in db->__construct, creating connection');
	parent::__construct($dbServer, $dbUser, $dbPass, $dbDatabase, $dbPort);

	if ($this->connect_error) {
		$msg = 'Connect Error ['.$this->connect_errno.'] ';
		$msg .= $this->connect_error;
    	exit($msg);
	} 

}

//overload close method
public function close(){

	printLine('in db->close, releasing connection');
	parent::close();

}

public function query($sql){

	printLine('in db->query');
	printLine($sql);
	return parent::query($sql);
	
}


}
//end class db










global $conn;
$conn = new db;

$sessionTimeZone = setSessionTimeZone();
$sessionTime = getSessionTime();



printLine('exiting dbconnect');

?>
