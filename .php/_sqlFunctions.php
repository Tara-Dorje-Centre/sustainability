<?php
function sqlLimitClause($resultPage, $rowsPerPage){
	$limitSQL = " LIMIT ";
	$limitOffset = ($resultPage - 1) * $rowsPerPage;
	$limitSQL .= $limitOffset.", ".$rowsPerPage;
	return $limitSQL;	
}

function getScalarQueryResult($sql, $field){


//mysqli
	$result = $db->query($sql);
	if($result){
	  	while ($row = $result->fetch_object()){
		
			$value = $row[$field];
		}
	    // Free result set
	    $result->close();
	    $db->next_result();
	}


	return $value;

}
function getSQLCount($sqlCount, $fieldName){
	$i = 0;
	
	//mysql deprecated php 7
//	$result = mysql_query($sqlCount) or die(mysql_error());
//	while ($row = mysql_fetch_array($result))
//	{
//	$i = $row[$fieldName];
//	}
//	mysql_free_result($result);
	
	//mysqli
	$result = $db->query($sqlCount);
	if($result){
	  	while ($row = $result->fetch_object()){
		
			$i = $row[$fieldName];
		}
	    // Free result set
	    $result->close();
	    $db->next_result();
	}
	
	return $i;
}

function getSessionYear(){
	
	$sql = "select year(CURRENT_TIMESTAMP) as db_year from DUAL";
	
	//mysql deprecated php 7
	//$result = mysql_query($sql) or exit(mysql_error());
	//while($row = mysql_fetch_array($result))
	//{
	//	$currentYear = $row['db_year'];
	//}
	//mysql_free_result($result);
	
	//mysqli
	$result = $db->query($sql);
	if($result){
    	 // Cycle through results
    	while ($row = $result->fetch_object()){
    	
    		$currentYear = $row['db_year'];
    		
    	}
    // Free result set
    $result->close();
    $db->next_result();
	}
	
	return $currentYear;
}

function getSessionTime(){
	
	$sql = "select CURRENT_TIMESTAMP as db_time from DUAL";
	
	
	//mysql deprecated php 7
	//$result = mysql_query($sql) or exit(mysql_error());
	//while($row = mysql_fetch_array($result))
	//{
	//	$currentTimestamp = $row['db_time'];	
	//}
//	mysql_free_result($result);
	
	//mysqli
	$result = $db->query($sql);
	if($result){
    	 // Cycle through results
    	while ($row = $result->fetch_object()){
    	
    		$currentTimestamp = $row['db_time'];
    		
    	}
    // Free result set
    $result->close();
    $db->next_result();
	}
	
	return $currentTimestamp;
}

function getSessionTimeZone(){
	$sql = "select @@session.TIME_ZONE as db_time_zone from DUAL";
	
	//deprecated php7
	//$result = mysql_query($sql) or exit(mysql_error());
	//while($row = mysql_fetch_array($result))
	//{
	//	$currentTimeZone = $row['db_time_zone'];	
	//}
	//mysql_free_result($result);
	
	
	//mysqli
	$result = $db->query($sql);
	if($result){
    	 // Cycle through results
    	while ($row = $result->fetch_object()){
    	
    		$currentTimeZone = $row['db_time_zone'];
    		
    	}
    // Free result set
    $result->close();
    $db->next_result();
	}
	
	
	return $currentTimeZone;
}

function setSessionTimeZone($utcOffset){
	//example call setSessionTimeZone('-7:00');

	$sql = "set @@session.time_zone='".$utcOffset."'";
	//mysql_query($sql) or exit(mysql_error());
	
		$result = $db->query($sql);
	
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
