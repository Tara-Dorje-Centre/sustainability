<?php

include_once("userLinks.class");
include_once("userSQL.class");
include_once("user.class");
include_once("userList.class");


include_once("userType.class");
showProgress('user classes loaded');

include_once("siteSettingsLinks.class");
include_once("siteSettingsSQL.class");
include_once("siteSettings.class");

showProgress('site settings classes loaded');
showProgress('system classes loaded');
//
//data classes - project management
include_once("_publicSiteLinks.class");
include_once("_publicSiteSQL.class");
include_once("_publicSite.class");

showProgress('publicsite classes loaded');

include_once("activityLinks.class");
include_once("activitySQL.class");
include_once("activity.class");
include_once("activityList.class");

showProgress('activity classes loaded');
include_once("activityType.class");
showProgress('activity type classes loaded');

include_once("locationLinks.class");
include_once("locationSQL.class");
include_once("location.class");
include_once("locationList.class");

include_once("locationType.class");
showProgress('location classes loaded');
include_once("materialLinks.class");
include_once("materialSQL.class");
include_once("material.class");
include_once("materialList.class");

include_once("materialType.class");
showProgress('material classes loaded');

include_once("measureLinks.class");
include_once("measureSQL.class");
include_once("measure.class");
include_once("measureList.class");

include_once("measureType.class");

//include_once("measureTypeUnitFields.class");
include_once("measureTypeUnitLinks.class");
include_once("measureTypeUnitSQL.class");
include_once("measureTypeUnit.class");
include_once("measureTypeUnitList.class");
showProgress('measure classes loaded');

include_once("projectLinks.class");
include_once("projectSQL.class");
include_once("project.class");
include_once("projectList.class");

include_once("projectType.class");
showProgress('project classes loaded');

include_once("receiptLinks.class");
include_once("receiptSQL.class");
include_once("receipt.class");
include_once("receiptList.class");

include_once("receiptType.class");
showProgress('receipt classes loaded');
include_once("taskLinks.class");
include_once("taskSQL.class");
include_once("task.class");
include_once("taskList.class");
include_once("taskType.class");
showProgress('task classes loaded');
include_once("quantityType.class");
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
