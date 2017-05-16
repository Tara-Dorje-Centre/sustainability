<?php




function sqlLimitClause($resultPage, $rowsPerPage){
	$limitSQL = " LIMIT ";
	$limitOffset = ($resultPage - 1) * $rowsPerPage;
	$limitSQL .= $limitOffset.", ".$rowsPerPage;
	return $limitSQL;	
}

function getScalarQueryResult($sql, $field){

	//mysql_* library deprecated php 7
	//$result = mysql_query($sql) or exit(mysql_error());
	//while($row = mysql_fetch_array($result))
	//{
	//	$currentYear = $row[$field];
	//}
	//mysql_free_result($result);


	//mysqli_* library implemented for php7
	//redirect$conn reference to global in _dbconnect.php
	global $conn;
	$result = $conn->query($sql);
	
	if($result){
	  	while ($row = $result->fetch_row()){
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

	//example call setSessionTimeZone('-7:00');

	$sql = "set @@session.TIME_ZONE='".$utcOffset."'";
	//mysql_query($sql) or exit(mysql_error());
	
	//redirect$conn reference to global in _dbconnect.php
	global $conn;
	$result = $conn->query($sql);
	
	//return new client time zone to confirm function
	return getSessionTimeZone();
}

function addDays($timestamp, $days = 0){
	$sql = "SELECT timestampadd(DAY, ".$days.", '".$timestamp."') as new_time from DUAL";
	$result = mysql_query($sql) or exit(mysql_error());
	while($row = mysql_fetch_array($result))
	{
		$newTime = $row['new_time'];	
	}
	mysql_free_result($result);
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
	$result = mysql_query($sql) or die(mysql_error());	
	while($row = mysql_fetch_array($result))
	{	
		$optionValue = $row["value"];
		$optionCaption = $row["caption"];
		$option = getSelectOption($optionValue,$optionCaption,$selectedValue);
		$allOptions .= $option;
	}
	mysql_free_result($result);
	return $allOptions;	
}

?>
