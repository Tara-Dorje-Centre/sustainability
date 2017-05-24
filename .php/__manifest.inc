<?php
//
function showProgress($msg){
	//printLine('manfest.inc->'.$msg);
}
//
include_once("_htmlFunctions.php");
include_once("_cssFunctions.php");
include_once("_formFunctions.php");
include_once("_sqlFunctions.php");
showProgress('general functions loaded');
//
include_once("__Calendar.class");
include_once("__Database.class");
include_once("__Links.class");
include_once("__Entity.class");
include_once("__EntityType.class");
include_once("__EntityList.class");
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
include_once("_UserType.class");
include_once("_UserTypeList.class");
include_once("_UserTypeLinks.class");
include_once("_UserTypeSQL.class");
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
include_once("_ActivityType.class");
include_once("_ActivityTypeList.class");
include_once("_ActivityTypeLinks.class");
include_once("_ActivityTypeSQL.class");
showProgress('activity type classes loaded');
//
include_once("_Location.class");
include_once("_LocationList.class");
include_once("_LocationLinks.class");
include_once("_LocationSQL.class");
include_once("_LocationType.class");
include_once("_LocationTypeList.class");
include_once("_LocationTypeLinks.class");
include_once("_LocationTypeSQL.class");
showProgress('location classes loaded');
//
include_once("_Material.class");
include_once("_MaterialList.class");
include_once("_MaterialLinks.class");
include_once("_MaterialSQL.class");
include_once("_MaterialType.class");
include_once("_MaterialTypeList.class");
include_once("_MaterialTypeLinks.class");
include_once("_MaterialTypeSQL.class");
showProgress('material classes loaded');
//
include_once("_Measure.class");
include_once("_MeasureList.class");
include_once("_MeasureLinks.class");
include_once("_MeasureSQL.class");
include_once("_MeasureType.class");
include_once("_MeasureTypeList.class");
include_once("_MeasureTypeLinks.class");
include_once("_MeasureTypeSQL.class");
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
include_once("_ProjectType.class");
include_once("_ProjectTypeList.class");
include_once("_ProjectTypeLinks.class");
include_once("_ProjectTypeSQL.class");
showProgress('project classes loaded');
//
include_once("_Receipt.class");
include_once("_ReceiptList.class");
include_once("_ReceiptLinks.class");
include_once("_ReceiptSQL.class");
include_once("_ReceiptType.class");
include_once("_ReceiptTypeList.class");
include_once("_ReceiptTypeLinks.class");
include_once("_ReceiptTypeSQL.class");
showProgress('receipt classes loaded');
//
include_once("_Task.class");
include_once("_TaskList.class");
include_once("_TaskLinks.class");
include_once("_TaskSQL.class");
include_once("_TaskType.class");
include_once("_TaskTypeList.class");
include_once("_TaskTypeLinks.class");
include_once("_TaskTypeSQL.class");
showProgress('task classes loaded');
//
include_once("_UnitOfMeasure.class");
include_once("_UnitOfMeasureList.class");
include_once("_UnitOfMeasureLinks.class");
include_once("_UnitOfMeasureSQL.class");
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
