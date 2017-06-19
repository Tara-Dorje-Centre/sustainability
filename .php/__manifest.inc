<?php
include_once('_echo.class');
//
function showProgress($msg, $show = false){
	$e = new __echo();
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
include_once("_User.class");
include_once("_UserList.class");
include_once("_UserLinks.class");
include_once("_UserSQL.class");
include_once("userType.class");
include_once("userTypeList.class");
include_once("userTypeLinks.class");
include_once("userTypeSQL.class");
showProgress('user classes loaded');
//
include_once("_SiteSettings.class");
include_once("_SiteSettingsLinks.class");
include_once("_SiteSettingsSQL.class");
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

include_once("_Activity.class");
include_once("_ActivityList.class");
include_once("_ActivityLinks.class");
include_once("_ActivitySQL.class");
showProgress('activity classes loaded');
//
include_once("actvityType.class");
include_once("activityTypeList.class");
include_once("activityTypeLinks.class");
include_once("activityTypeSQL.class");
showProgress('activity type classes loaded');
//
include_once("_Location.class");
include_once("_LocationList.class");
include_once("_LocationLinks.class");
include_once("_LocationSQL.class");
include_once("locationType.class");
include_once("locationTypeList.class");
include_once("locationTypeLinks.class");
include_once("locationTypeSQL.class");
showProgress('location classes loaded');
//
include_once("_Material.class");
include_once("_MaterialList.class");
include_once("_MaterialLinks.class");
include_once("_MaterialSQL.class");
include_once("materialType.class");
include_once("materialTypeList.class");
include_once("materialTypeLinks.class");
include_once("materialTypeSQL.class");
showProgress('material classes loaded');
//
include_once("_Measure.class");
include_once("_MeasureList.class");
include_once("_MeasureLinks.class");
include_once("_MeasureSQL.class");
include_once("measureType.class");
include_once("measureTypeList.class");
include_once("measureTypeLinks.class");
include_once("measureTypeSQL.class");
include_once("_MeasureTypeUnit.class");
include_once("_MeasureTypeUnitList.class");
include_once("_MeasureTypeUnitLinks.class");
include_once("_MeasureTypeUnitSQL.class");
showProgress('measure classes loaded');
//
include_once("_Project.class");
include_once("_ProjectList.class");
include_once("_ProjectLinks.class");
include_once("_ProjectSQL.class");
include_once("projectType.class");
include_once("projectTypeList.class");
include_once("projectTypeLinks.class");
include_once("projectTypeSQL.class");
showProgress('project classes loaded');
//
include_once("_Receipt.class");
include_once("_ReceiptList.class");
include_once("_ReceiptLinks.class");
include_once("_ReceiptSQL.class");
include_once("reciptType.class");
include_once("receiptTypeList.class");
include_once("receiptTypeLinks.class");
include_once("receiptTypeSQL.class");
showProgress('receipt classes loaded');
//
include_once("_Task.class");
include_once("_TaskList.class");
include_once("_TaskLinks.class");
include_once("_TaskSQL.class");
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
