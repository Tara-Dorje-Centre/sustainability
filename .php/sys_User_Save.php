<?php 
include_once("_includes.php");


$u = new User;
if (isset($_POST['submit'])){
	$u->collectPostValues();
	$u->saveChanges();	
} elseif (isset($_POST['submit-pwd-reset'])){
	if (!empty($_POST['login-name'])  && !empty($_POST['login-email'])){
		$login = mysql_real_escape_string($_POST['login-name']);	
		$email = mysql_real_escape_string($_POST['login-email']);
		$u->resetPassword($login, $email);
		$u->id = $_POST['userId'];
	}
}

$_GET['pageAction'] = 'VIEW';
$_GET['userId'] = $u->id;

include_once("sys_User_Detail.php");
?>