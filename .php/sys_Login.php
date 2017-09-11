<?php 
//session_start();
include_once("_includes.php");


$u = new \application\system\userLogin('VALIDATE');
$u->validate();

if (isset($_SESSION['logged-in']) && ($_SESSION['logged-in'] == true)){
	//include("pr_Task_List.php");
	include("sys_Menu.php");
} else {
	
	$site = new _htmlSite('LOGIN');
	$site->set();
	$site->print();

}

?>
