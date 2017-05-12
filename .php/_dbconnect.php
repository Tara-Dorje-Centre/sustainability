<?php
// Set Database connection variables
$dbTime;
$dbServer  = "localhost";
$dbDatabase  = "NameOfDatabase";
$dbUser  = "NameOfDatabaseUser";
$dbPass  = "DatabaseUserPassword";

//mysql_* functions deprecated php 7
//
//open a php connection to mysql
$sqlConnection = mysql_connect($dbServer, $dbUser, $dbPass)
or die("Couldn't connect to database server");
//open the database
$db = mysql_select_db($dbDatabase, $sqlConnection )
or die("Couldn't connect to database $dbDatabase");


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

//$sql = "set @@session.time_zone='-7:00'";
//mysql_query( $sql ) or exit(mysql_error());
//SELECT current_timestamp, the_time, @@session.time_zone FROM test_rows WHERE id = 1;
$sessionTime = getSessionTime();
//$sql = "select CURRENT_TIMESTAMP as db_time from DUAL";
//$result = mysql_query($sql) or exit(mysql_error());
//	while($row = mysql_fetch_array($result))
//{
//$dbTime = $row['db_time'];	
//}

//$mysqli = new mysqli('localhost', 'my_user', 'my_password', 'my_db');
//if (mysqli_connect_error()) {
//    die('Connect Error ('.mysqli_connect_errno().') '. mysqli_connect_error());
//}
//echo 'Success... ' . $mysqli->host_info . "\n";
//$mysqli->close();

//  $link = mysqli_connect('localhost', 'myusr', 'mypass') or die ('Error connecting to mysql: ' . mysqli_error($link));
//  mysqli_select_db($link, 'clips');
//  
//  while ($row = mysqli_fetch_assoc($result))
//  {
//    echo "session_id : {$row['session_id']} <br>";
//    echo "msg        : {$row['msg']} <br>";
//  }


//prepared statement syntax with bind variables
//$mysqli = new mysqli('localhost', 'my_user', 'my_password', 'world');
//if (mysqli_connect_errno()) {
//    printf("Connect failed: %s\n", mysqli_connect_error());
//    exit();
//}
//
//$stmt = $mysqli->prepare("INSERT INTO CountryLanguage VALUES (?, ?, ?, ?)");
//$stmt->bind_param('sssd', $code, $language, $official, $percent);
//$code = 'DEU';
//$language = 'Bavarian';
//$official = "F";
//$percent = 11.2;
//
//$stmt->execute();
//$stmt->close();
//$mysqli->close();
?>
