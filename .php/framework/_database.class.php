<?php 
namespace framework\sql;

class _database extends \mysqli{
	
protected const _server = "127.0.0.1";
protected const _database  = "projects";
protected const _user = "dev_usr";
protected const _pwd = "dev_pwd";
protected const _port = 3306;

private $echo;

public function __construct(){

	$this->echo = new \framework\_echo();
	$this->echo->echoPrint(true, 'creating connection to db='.$this::_database, '__construct', '_Database', '');

	parent::__construct($this::_server, $this::_user, $this::_pwd, $this::_database, $this::_port);

    //parent::__construct($this->_server, $this->_user, $this->_pwd, $this->_database, $this->_port);



	if ($this->connect_error) {
		$msg = 'Connect Error ['.$this->connect_errno.'] ';
		$msg .= $this->connect_error;
    	exit($msg);
	} 
}

//overload close method
	public function close(){
		$this->echo->echoPrint(true, ' --releasing connection', 'close');
		parent::close();
	}

	public function getEscapeString($value){
		return $this->real_escape_string($value);
	}

	public function getInsertedId (){
		return  $this->insert_id;
	}
	
	public function query($sql, $resultmode = NULL){
		$this->echo->echoValue(true, 'sql', $sql, 'query');
		return parent::query($sql,$resultmode);
	}
	
	public function runStatement($sql){
		$success = $this->query($sql);
		if ($success != true){
			exit($this->error);
		}
		return true;
	}
	
	public function getResult($sql){
		$result = $this->query($sql) or exit($this->error);
		return $result;
	}
	
	public function getScalar($sql, $field, $default = 0){
		$value = $default;
		$result = $this->getResult($sql);
		if($result){
	  		while ($row = $result->fetch_assoc()){
	  			
				$value = $row[$field];
			}
			$result->close();
		}
		$this->echo->echoValue(true, $field, $value, 'getScalar');
		return $value;
	}
	
	public function getCount($sql, $field){
		return $this->getScalar($sql, $field, 0);
	}
	
	public function getSessionTime(){
		$sql = "select CURRENT_TIMESTAMP as db_time from DUAL";
		$field = 'db_time';
		return $this->getScalar($sql, $field,'');
	}
	
	public function getSessionYear(){
		$sql = "select year(CURRENT_TIMESTAMP) as db_year from DUAL";
		$field = 'db_year';
		return $this->getScalar($sql, $field,0);
	}
	
	public function getSessionTimeZone(){
		$sql = "select @@session.TIME_ZONE as db_time_zone from DUAL";
		$field = 'db_time_zone';
		return $this->getScalar($sql, $field);
	}
	
	public function setSessionTimeZone(){
		if (isset($_SESSION['logged-in']) && isset($_SESSION['client-time-zone'])){
			$utcOffset = $_SESSION['client-time-zone'];
		} else {
			$utcOffset = '-0:00';
		}
		if ($utcOffset == ''){
			$utcOffset = '-0:00';
		}
		if ($utcOffset == 0){
			$utcOffset = '-0:00';
		}
		$sql = "set @@session.TIME_ZONE='".$utcOffset."'";
		$result = $this->runStatement($sql);
	}
	
	public function addDays($time, $days = 0){
		$sql = "SELECT timestampadd(DAY, ".$days.", '".$time."') as new_time from DUAL";
		$field = 'new_time';
		return $this->getScalar($sql, $field);
	}
	public function limit($page, $perPage){
		$limitSQL = '';
		if (is_null($perPage)){
	    	$perPage = 0;
		}
	
	
	
		if ($perPage > 0 AND $page > 0){
	     	$limitSQL = " LIMIT ";
	     	$limitOffset = ($page - 1) * $perPage;
	     	$limitSQL .= $limitOffset.", ".$perPage;
		}
	
		return $limitSQL;	
	}
}
?>
