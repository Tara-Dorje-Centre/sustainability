<?php



//classes - secure login framework entities

include_once("_MenuLinks.class");
include_once("_Menu.class");

showProgress('menu classes loaded');

//
//include_once("userFields.class");
include_once("userLinks.class");
include_once("userSQL.class");
include_once("user.class");
include_once("userList.class");

//include_once("userTypeFields.class");
include_once("userTypeLinks.class");
include_once("userTypeSQL.class");
include_once("userType.class");
include_once("userTypeList.class");


showProgress('user classes loaded');
//
//include_once("siteSettingsFields.class");
include_once("siteSettingsLinks.class");
include_once("siteSettingsSQL.class");
include_once("siteSettings.class");

showProgress('site settings classes loaded');
//
showProgress('system classes loaded');
//
//data classes - project management
//


//include_once("_PublicSiteFields.class");
include_once("_PublicSiteLinks.class");
include_once("_PublicSiteSQL.class");
include_once("_PublicSite.class");

showProgress('publicsite classes loaded');

//include_once("activityFields.class");
include_once("activityLinks.class");
include_once("activitySQL.class");
include_once("activity.class");
include_once("activityList.class");


showProgress('activity classes loaded');
//

//include_once("activityTypeFields.class");
include_once("activityTypeLinks.class");
include_once("activityTypeSQL.class");
include_once("activityType.class");
include_once("activityTypeList.class");

showProgress('activity type classes loaded');
//
//include_once("locationFields.class");
include_once("locationLinks.class");
include_once("locationSQL.class");
include_once("location.class");
include_once("locationList.class");


//include_once("locationTypeFields.class");
include_once("locationTypeLinks.class");
include_once("locationTypeSQL.class");
include_once("locationType.class");
include_once("locationTypeList.class");
showProgress('location classes loaded');
//
//include_once("materialFields.class");
include_once("materialLinks.class");
include_once("materialSQL.class");
include_once("material.class");
include_once("materialList.class");



//include_once("materialTypeFields.class");
include_once("materialTypeLinks.class");
include_once("materialTypeSQL.class");
include_once("materialType.class");
include_once("materialTypeList.class");
showProgress('material classes loaded');
//

//include_once("measureFields.class");
include_once("measureLinks.class");
include_once("measureSQL.class");
include_once("measure.class");
include_once("measureList.class");


//include_once("measureTypeFields.class");
include_once("measureTypeLinks.class");
include_once("measureTypeSQL.class");
include_once("measureType.class");
include_once("measureTypeList.class");



//include_once("measureTypeUnitFields.class");
include_once("measureTypeUnitLinks.class");
include_once("measureTypeUnitSQL.class");
include_once("measureTypeUnit.class");
include_once("measureTypeUnitList.class");
showProgress('measure classes loaded');
//

//include_once("projectFields.class");
include_once("projectLinks.class");
include_once("projectSQL.class");
include_once("project.class");
include_once("projectList.class");


//include_once("projectTypeFields.class");
include_once("projectTypeLinks.class");
include_once("projectTypeSQL.class");
include_once("projectType.class");
include_once("projectTypeList.class");
showProgress('project classes loaded');
//
//include_once("receiptFields.class");
include_once("receiptLinks.class");
include_once("receiptSQL.class");
include_once("receipt.class");
include_once("receiptList.class");


//include_once("receiptTypeFields.class");
include_once("receiptTypeLinks.class");
include_once("receiptTypeSQL.class");
include_once("receiptType.class");
include_once("receiptTypeList.class");
showProgress('receipt classes loaded');
//

//include_once("taskFields.class");
include_once("taskLinks.class");
include_once("taskSQL.class");
include_once("task.class");
include_once("taskList.class");


//include_once("taskTypeFields.class");
include_once("taskTypeLinks.class");
include_once("taskTypeSQL.class");
include_once("taskType.class");
include_once("taskTypeList.class");

showProgress('task classes loaded');
//
//include_once("unitOfMeasureFields.class");
include_once("unitOfMeasureLinks.class");
include_once("unitOfMeasureSQL.class");
include_once("unitOfMeasure.class");
include_once("unitOfMeasureList.class");
showProgress('unit of measure classes loaded');
//
//data classes - crop management
//
include_once("crop.class");
include_once("cropList.class");
include_once("cropLinks.class");
include_once("cropSQL.class");
showProgress('crop classes loaded');
//
include_once("cropPlan.class");
include_once("cropPlanList.class");
include_once("cropPlanLinks.class");
include_once("cropPlanSQL.class");
showProgress('crop plan classes loaded');
//
include_once("cropPlanting.class");
include_once("cropPlantingList.class");
include_once("cropPlantingLinks.class");
include_once("cropPlantingSQL.class");
showProgress('crop planting classes loaded');
//
showProgress('exit manifestEntities.inc');
?>
