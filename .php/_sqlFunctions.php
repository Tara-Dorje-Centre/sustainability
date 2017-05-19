<?php
function printLine($msg){
	echo $msg.'<br />';
}

function dbEscapeString($value){
	//mysqli_* library implemented for php7
	//redirect$conn reference to global in _dbconnect.php
	global $conn;
	return $conn->escape_string($value);
}

function dbInsertedId ($callingFunction = 'dbInsertedId'){
	printLine($callingFunction);
	global $conn;
	return $conn->insert_id;
}

 function sqlLimitClause($resultPage, $rowsPerPage){
	$limitSQL = " LIMIT ";
	$limitOffset = ($resultPage - 1) * $rowsPerPage;
	$limitSQL .= $limitOffset.", ".$rowsPerPage;
	return $limitSQL;	
}

function dbRunSQL($sql, $callingFunction = 'dbRunSQL'){
	printLine($callingFunction);
	global $conn;
	$conn->query($sql);
	
	return true;
}

function dbGetResult($sql, $callingFunction = 'dbGetResult'){
	printLine($callingFunction);
	global $conn;
	$result = $conn->query($sql) or exit($conn->error);
	return $result;
}

function dbGetScalar($sql, $field, $default = '', $callingFunction = 'dbGetScalar'){
	
	$result = dbGetResult($sql, $callingFunction);
	if($result){
	  	while ($row = $result->fetch_assoc())
	  	{
			$value = $row[$field];
		}
		// Free result set
		$result->close();
	} else {
		$value = $default;
	}
	//printLine($value);
	return $value;
}

function dbGetCount($sql, $field, $callingFunction = 'dbGetCount'){
	$i = dbGetScalar($sql, $field, '0', $callingFunction);
	return $i;
}

function getSessionYear(){
	$sql = "select year(CURRENT_TIMESTAMP) as db_year from DUAL";
	$field = 'db_year';
	$currentYear = dbGetScalar($sql, $field);
	return $currentYear;
}

function getSessionTime(){
	$sql = "select CURRENT_TIMESTAMP as db_time from DUAL";
	$field = 'db_time';
	$currentTimestamp = dbGetScalar($sql, $field);
	return $currentTimestamp;
}

function getSessionTimeZone(){
	$sql = "select @@session.TIME_ZONE as db_time_zone from DUAL";
	$field = 'db_time_zone';
	$currentTimeZone = dbGetScalar($sql, $field);
	return $currentTimeZone;
}

function setSessionTimeZone(){
	if (isset($_SESSION['logged-in']) && isset($_SESSION['client-time-zone'])){
		$utcOffset = $_SESSION['client-time-zone'];
	} else {
		$utcOffset = '-0:00';
	}
	$sql = "set @@session.TIME_ZONE='".$utcOffset."'";

	$locale = 'setSessionTimeZone:';
	$result = dbRunSQL($sql, $locale);
	
	//return new client time zone to confirm function
	return getSessionTimeZone();
}

function addDays($timestamp, $days = 0){
	$sql = "SELECT timestampadd(DAY, ".$days.", '".$timestamp."') as new_time from DUAL";
	$field = 'new_time';
	$newTime = dbGetScalar($sql, $field);
	return $newTime;
}

function getSelectOptionsSQL($sql,$selectedValue, $disabled, $defaultValue,$defaultCaption){
	if ($defaultValue === 'NO_DEFAULT_VALUE'){
		//omit default value
		$allOptions = '';
	} else {
	    $allOptions = getSelectOption($defaultValue,$defaultCaption,$selectedValue);
	}

		$locale = 'getSelectOptionsSQL:';
		$result = dbGetResult($sql, $locale);

		if($result){
		
	  	while ($row = $result->fetch_assoc())
			{	
				$optionValue = $row["value"];
				$optionCaption = $row["caption"];
				$option = getSelectOption($optionValue,$optionCaption,$selectedValue);
				$allOptions .= $option;
			}

		// Free result set
		$result->close();
		}
	
	return $allOptions;	
}



?>
