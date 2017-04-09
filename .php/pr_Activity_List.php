<?php 
include_once("_includes.php");
$detailsPerPage = 15;

$myActivity = sessionVariableGET('myActivity','NO');
$showCalendar = sessionVariableGET('showCalendar','NO');
$activityMonth = sessionVariableGET('month',0);
$activityYear = sessionVariableGET('year',0);
$projectId = sessionVariableGET('projectId',0);
$taskId = sessionVariableGET('taskId',0);
$resultPage = sessionVariableGET('resultsPageActivity',1);
if ($showCalendar == 'YES'){
	if ($myActivity == 'YES'){
		$_SESSION['currentView'] = 'MY_CALENDAR';
	} elseif ($myActivity == 'GROUP'){
		$_SESSION['currentView'] = 'GROUP_CALENDAR';
	}
} elseif ($showCalendar == 'NO'){
	if ($myActivity == 'YES'){
		$_SESSION['currentView'] = 'MY_ACTIVITY';
	} elseif ($myActivity == 'GROUP'){
		$_SESSION['currentView'] = 'GROUP_ACTIVITY';
	}
}
$al = new ActivityList;
$al->setDetails($taskId, $resultPage, $detailsPerPage,$myActivity,$showCalendar,$projectId);
$al->setCalendarRange($activityYear, $activityMonth);

$al->printPage();
?>