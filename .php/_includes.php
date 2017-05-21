<?php 
session_start(); 
//echo 'started session from _includes.php';
//session_regenerate_id(TRUE);

include_once("_htmlFunctions.php");
include_once("_cssFunctions.php");

include_once("_formFunctions.php");
include_once("_sqlFunctions.php");


include_once("__Links.class");
include_once("__Calendar.class");
include_once("__Template.class");
include_once("__TemplatePublic.class");

include_once("_publicSite_Classes.php");

//support secure login system
include_once("sys_Menu_Class.php");



//include_once("sys_User_Classes.php");
include_once("_User.class");
include_once("_UserList.class");
include_once("_UserLinks.class");
include_once("_UserSQL.class");

//include_once("sys_UserType_Classes.php");
include_once("_UserType.class");
include_once("_UserTypeList.class");
include_once("_UserTypeLinks.class");
include_once("_UserTypeSQL.class");

//include_once("sys_SiteSettings_Classes.php");
include_once("_SiteSettings.class");
include_once("_SiteSettingsLinks.class");
include_once("_SiteSettingsSQL.class");



//support project planning system
include_once("_includes_pr.php");

//support crop planning system
include_once("_includes_cp.php");

//finish standard include process by opening active db connection
include_once("_dbconnect.php");
?>
