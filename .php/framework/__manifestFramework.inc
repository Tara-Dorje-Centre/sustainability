<?php

include_once("_htmlFunctions.php");
include_once("_cssFunctions.php");
include_once("_formFunctions.php");
include_once("_sqlFunctions.php");
showProgress('general functions loaded');
//
include_once("_contentWriter.class");
include_once("_sqlWriter.class");
include_once("_calendar.class");
include_once("_database.class");
include_once("_links.class");

include_once("__EntityInterfaces.class");

include_once("_entitySQL.class");
include_once("_entityTypeSQL.class");
include_once("_projectChildSQL.class");
include_once("_taskChildSQL.class");


include_once("_baseEntityFields.class");
include_once("_baseEntityLinks.class");
include_once("_baseEntity.class");

include_once("_entityFields.class");
include_once("_entityLinks.class");
include_once("_entity.class");
include_once("_entityList.class");

//include_once("_entityTypeFields.class");
include_once("_entityTypeLinks.class");
include_once("_entityType.class");
include_once("_entityTypeList.class");

include_once("__Template.class");
include_once("__TemplatePublic.class");
showProgress('general classes loaded');
//
//classes - secure login
/*
include_once("_MenuLinks.class");
include_once("_Menu.class");

showProgress('menu classes loaded');*/
//

showProgress('exit manifestFramework.inc');
?>
