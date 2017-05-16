<?php

function printLine($msg){
	echo $msg.'<br />';
}


function runQuery($sql){


	//mysqli_* library implemented for php7
	//redirect$conn reference to global in _dbconnect.php
	global $conn;
	$result = $conn->query($sql) or exit($conn->error);
	
	return $result;

}


 function getScalarQueryResult($sql, $field){

	//mysqli_* library implemented for php7
	//redirect$conn reference to global in _dbconnect.php
	//global $conn;
	//$result = $conn->query($sql) or exit($conn->error);
	$result = runQuery($sql);
	
	if($result){
	  	while ($row = $result->fetch_row())
	  	{
			$value = $row[$field];
		}
	// Free result set
	$result->close();
	}
	
	return $value;
}

function getSQLCount($sqlCount, $fieldName){
	$i = 0;
	$i = getScalarQueryResult($sqlCount, $fieldName);
	return $i;
}

function getSessionYear(){
	
	$sql = "select year(CURRENT_TIMESTAMP) as db_year from DUAL";
	$field = 'db_year';
	$currentYear = getScalarQueryResult($sql, $field);
	return $currentYear;
}

function getSessionTime(){
	
	$sql = "select CURRENT_TIMESTAMP as db_time from DUAL";
	$field = 'db_time';
	$currentTimestamp = getScalarQueryResult($sql, $field);
	return $currentTimestamp;
}

function getSessionTimeZone(){
	$sql = "select @@session.TIME_ZONE as db_time_zone from DUAL";
	$field = 'db_time_zone';
	$currentTimeZone = getScalarQueryResult($sql, $field);
	return $currentTimeZone;
}

function setSessionTimeZone(){

//user login should set user time zone preference
//select user time zone, then set for all user sessions
//will need to compensate for daylight savings time and standard time automatically
if (isset($_SESSION['logged-in']) && isset($_SESSION['client-time-zone'])){
	$utcOffset = $_SESSION['client-time-zone'];
} else {
	$utcOffset = '-0:00';
}

	//example call setSessionTimeZone();

	$sql = "set @@session.TIME_ZONE='".$utcOffset."'";

	
	//redirect$conn reference to global in _dbconnect.php
	//global $conn;
	//$result = $conn->query($sql) or exit($conn->error);
	
	$result = runQuery($sql);
	
	//return new client time zone to confirm function
	return getSessionTimeZone();
}

function addDays($timestamp, $days = 0){
	$sql = "SELECT timestampadd(DAY, ".$days.", '".$timestamp."') as new_time from DUAL";
	$field = 'new_time';
	$newTime = getScalarQueryResult($sql, $field);
	return $newTime;
}

function getSelectOptionsSQL($sql,$selectedValue, $disabled, $defaultValue,$defaultCaption){
	if ($defaultValue === 'NO_DEFAULT_VALUE'){
		//omit default value
		$allOptions = '';
		//echo 'skipping default value['.$defaultValue.']';
	} else {
		//echo 'printing default value';
	    $allOptions = getSelectOption($defaultValue,$defaultCaption,$selectedValue);
	}
	
	
	//redirect$conn reference to global in _dbconnect.php
//	global $conn;
//	$result = $conn->query($sql) or exit($conn->error);
	
	$result = runQuery($sql);
	
	if ($result){
	
	  	while ($row = $result->fetch_row())
		{	
			$optionValue = $row["value"];
			$optionCaption = $row["caption"];
			$option = getSelectOption($optionValue,$optionCaption,$selectedValue);
			$allOptions .= $option;
		}

	$result->close();
	}
	
	return $allOptions;	
}

 function sqlLimitClause($resultPage, $rowsPerPage){
	$limitSQL = " LIMIT ";
	$limitOffset = ($resultPage - 1) * $rowsPerPage;
	$limitSQL .= $limitOffset.", ".$rowsPerPage;
	return $limitSQL;	
}

?>
