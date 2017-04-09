<?php 
session_start(); 
//echo 'started session from _includes.php';
//session_regenerate_id(TRUE);

include_once("_htmlFunctions.php");
include_once("_cssFunctions.php");

include_once("_formFunctions.php");
include_once("_sqlFunctions.php");


include_once("_baseClass_Links.php");
include_once("_baseClass_Calendar.php");
include_once("_baseClass_siteTemplate.php");

//support secure login system
include_once("sys_Menu_Class.php");
include_once("sys_User_Classes.php");
include_once("sys_UserType_Classes.php");
include_once("sys_SitewideSettings_Classes.php");

//support project planning system
include_once("_includes_pr.php");

//support crop planning system
include_once("_includes_cp.php");

//finish standard include process by opening active db connection
include_once("_dbconnect.php");
?>