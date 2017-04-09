<?php 
//session_start();
include_once("_includes.php");

if (isset($_POST['submit-login'])){
	if (!empty($_POST['login-name']) && !empty($_POST['login-pwd'])){
		$login = mysql_real_escape_string($_POST['login-name']);
		$pass = mysql_real_escape_string($_POST['login-pwd']);
		$_POST['login-pwd'] = 'none';
		
		$u = new User;
		if ($u->validateLogin($login, $pass) == true){
			$_SESSION['login-name'] = $login;
			$_SESSION['logged-in'] = true;
			$_SESSION['client-time-zone'] = $_POST['client-time-zone'];
		} else {
			$_SESSION['logged-in'] = false;
			unset($_SESSION['logged-in']);
			unset($_SESSION['client-time-zone']);
			unset($_SESSION['user-id']);
			unset($_SESSION['is-admin']);
			unset($_SESSION['must-update-pwd']);
			unset($_SESSION['login-name']);
		}
	}
}
if (isset($_POST['submit-pwd-reset'])){
	if (!empty($_POST['login-name'])  && !empty($_POST['login-email'])){
		$login = mysql_real_escape_string($_POST['login-name']);	
		$email = mysql_real_escape_string($_POST['login-email']);
		$u = new User;
		$u->resetPassword($login, $email);
	}
}
if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true){
	include("pr_Task_List.php");
} else {
	
	$site = new _SiteTemplate;
	$site->setSiteTemplateDetails();
	$site->printSite();

}

?>