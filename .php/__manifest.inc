<?php
include_once('_echo.class');
//
function showProgress($msg, $show = false){
	$e = new _echo();
	$e->echoPrint($show,$msg,'includes','manifest.inc');
}
//
include_once("_htmlFunctions.php");
include_once("_cssFunctions.php");
include_once("_formFunctions.php");
include_once("_sqlFunctions.php");
showProgress('general functions loaded');
//
include_once("_calendar.class");
include_once("_database.class");
include_once("_links.class");

include_once("__EntityInterfaces.class");
include_once("_entitySQL.class");
include_once("_entityTypeSQL.class");
include_once("_projectChildSQL.class");
include_once("_taskChildSQL.class");

include_once("_entity.class");
include_once("_entityList.class");
//include_once("_entityLinks.class");
include_once("_entityType.class");
include_once("_entityTypeList.class");
include_once("_entityTypeLinks.class");
include_once("__Template.class");
include_once("__TemplatePublic.class");
showProgress('general classes loaded');
//
//classes - secure login
//
include_once("_Menu.class");
include_once("_MenuLinks.class");
showProgress('menu classes loaded');
//
include_once("user.class");
include_once("userList.class");
include_once("userLinks.class");
include_once("userSQL.class");
include_once("userType.class");
include_once("userTypeList.class");
include_once("userTypeLinks.class");
include_once("userTypeSQL.class");
showProgress('user classes loaded');
//
include_once("siteSettings.class");
include_once("siteSettingsLinks.class");
include_once("siteSettingsSQL.class");
showProgress('site settings classes loaded');
//
showProgress('system classes loaded');
//
//data classes - project management
//
include_once("_PublicSite.class");
include_once("_PublicSiteLinks.class");
include_once("_PublicSiteSQL.class");
showProgress('publicsite classes loaded');

include_once("activity.class");
include_once("activityList.class");
include_once("activityLinks.class");
include_once("activitySQL.class");
showProgress('activity classes loaded');
//
include_once("activityType.class");
include_once("activityTypeList.class");
include_once("activityTypeLinks.class");
include_once("activityTypeSQL.class");
showProgress('activity type classes loaded');
//
include_once("location.class");
include_once("locationList.class");
include_once("locationLinks.class");
include_once("locationSQL.class");
include_once("locationType.class");
include_once("locationTypeList.class");
include_once("locationTypeLinks.class");
include_once("locationTypeSQL.class");
showProgress('location classes loaded');
//
include_once("material.class");
include_once("materialList.class");
include_once("materialLinks.class");
include_once("materialSQL.class");
include_once("materialType.class");
include_once("materialTypeList.class");
include_once("materialTypeLinks.class");
include_once("materialTypeSQL.class");
showProgress('material classes loaded');
//
include_once("measure.class");
include_once("measureList.class");
include_once("measureLinks.class");
include_once("measureSQL.class");
include_once("measureType.class");
include_once("measureTypeList.class");
include_once("measureTypeLinks.class");
include_once("measureTypeSQL.class");
include_once("measureTypeUnit.class");
include_once("measureTypeUnitList.class");
include_once("measureTypeUnitLinks.class");
include_once("measureTypeUnitSQL.class");
showProgress('measure classes loaded');
//
include_once("project.class");
include_once("projectList.class");
include_once("projectLinks.class");
include_once("projectSQL.class");
include_once("projectType.class");
include_once("projectTypeList.class");
include_once("projectTypeLinks.class");
include_once("projectTypeSQL.class");
showProgress('project classes loaded');
//
include_once("receipt.class");
include_once("receiptList.class");
include_once("receiptLinks.class");
include_once("receiptSQL.class");
include_once("receiptType.class");
include_once("receiptTypeList.class");
include_once("receiptTypeLinks.class");
include_once("receiptTypeSQL.class");
showProgress('receipt classes loaded');
//
include_once("task.class");
include_once("taskList.class");
include_once("taskLinks.class");
include_once("taskSQL.class");
include_once("taskType.class");
include_once("taskTypeList.class");
include_once("taskTypeLinks.class");
include_once("taskTypeSQL.class");
showProgress('task classes loaded');
//
include_once("unitOfMeasure.class");
include_once("unitOfMeasureList.class");
include_once("unitOfMeasureLinks.class");
include_once("unitOfMeasureSQL.class");
showProgress('unit of measure classes loaded');
//
//data classes - crop management
//
include_once("_Crop.class");
include_once("_CropList.class");
include_once("_CropLinks.class");
include_once("_CropSQL.class");
showProgress('crop classes loaded');
//
include_once("_CropPlan.class");
include_once("_CropPlanList.class");
include_once("_CropPlanLinks.class");
include_once("_CropPlanSQL.class");
showProgress('crop plan classes loaded');
//
include_once("_CropPlanting.class");
include_once("_CropPlantingList.class");
include_once("_CropPlantingLinks.class");
include_once("_CropPlantingSQL.class");
showProgress('crop planting classes loaded');
//
showProgress('exit manifest.inc');
?>
