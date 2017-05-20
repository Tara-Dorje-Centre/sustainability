<?php
// Set Database connection variables



class db extends mysqli
{

protected const _server = "127.0.0.1";
protected const _database  = "projects_dev";
protected const _user = "dev_usr";
protected const _pwd = "dev_pwd";
protected const _port = 3306;


public function __construct(){

	printLine('in db->__construct, creating connection');

	parent::__construct($this::_server, $this::_user, $this::_pwd, $this::_database, $this::_port);

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

	
//	printLine($sql);
	return parent::query($sql);
	
}





}
//end class db










global   $conn;
$conn = new db;


//$conn is referencable in stand alone functions
//add pseudo var to get pointer   global $conn;
$sessionTimeZone = setSessionTimeZone();
$sessionTime = getSessionTime();



printLine('exiting dbconnect');

?>
