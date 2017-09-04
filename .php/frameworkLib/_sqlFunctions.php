<?php
function printLine($msg, $show = true){
	$e = new _echo();
	$e->echoLocale(' ', '_sqlFunctions');
	$e->echoPrint($show,$msg);
}

function dbEscapeString($value){
	global $conn;
	return $conn->real_escape_string($value);
}

function dbInsertedId ($callingFunction = 'dbInsertedId'){
	//printLine($callingFunction);
	global $conn;
	$id = $conn->insert_id;
	//printLine($id);
	return $id;
}

 function sqlLimitClause($resultPage, $rowsPerPage){
	$limitSQL = " LIMIT ";
	$limitOffset = ($resultPage - 1) * $rowsPerPage;
	$limitSQL .= $limitOffset.", ".$rowsPerPage;
	return $limitSQL;	
}

function dbRunSQL($sql, $callingFunction = 'dbRunSQL'){
	//printLine($callingFunction);
	//printLine($sql);
	global $conn;
	$success = $conn->query($sql);
	
	if ($success != true){
		//printLine($conn->error);
		exit($conn->error);
	}
	return true;
}

function dbGetResult($sql, $callingFunction = 'dbGetResult'){
	//printLine($callingFunction);
	//printLine($sql);
	global $conn;
	$result = $conn->query($sql) or exit($conn->error);
	return $result;
}

function dbGetScalar($sql, $field, $default = 0, $callingFunction = 'dbGetScalar'){
	$result = dbGetResult($sql, $callingFunction);
	if($result){
	  	while ($row = $result->fetch_assoc())
	  	{
			$value = $row[$field];
		}
		$result->close();
	} else {
		$value = $default;
	}
	//printLine($value);
	return $value;
}

function dbGetCount($sql, $field, $callingFunction = 'dbGetCount'){
	$i = dbGetScalar($sql, $field, 0, $callingFunction);
	printLine('dbCount['.$i.']');
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
	if ($utcOffset == ''){
		$utcOffset = '-0:00';
	}
	if ($utcOffset == 0){
			$utcOffset = '-0:00';
	}
	$sql = "set @@session.TIME_ZONE='".$utcOffset."'";

	$locale = 'setSessionTimeZone:';
	$result = dbRunSQL($sql, $locale);
	
	//return new client time zone to confirm function
	//return getSessionTimeZone();
}

function addDays($timestamp, $days = 0){
	$sql = "SELECT timestampadd(DAY, ".$days.", '".$timestamp."') as new_time from DUAL";
	$field = 'new_time';
	$newTime = dbGetScalar($sql, $field);
	return $newTime;
}

function getSelectOptionsSQL($sql,$selectedValue = 0, $disabled = false, $defaultValue = 0, $defaultCaption = ''){
	if ($defaultValue === 'NO_DEFAULT_VALUE'){
		//omit default value
		$allOptions = '';
	} else {
	    $allOptions = \html\getSelectOption($defaultValue,$defaultCaption,$selectedValue);
	}

		$locale = 'getSelectOptionsSQL:';
		$result = dbGetResult($sql, $locale);

		if($result){
		
	  	while ($row = $result->fetch_assoc())
			{	
				$optionValue = $row["value"];
				$optionCaption = $row["caption"];
				$option = \html\getSelectOption($optionValue,$optionCaption,$selectedValue);
				$allOptions .= $option;
			}

		// Free result set
		$result->close();
		}
	
	return $allOptions;	
}



?>
