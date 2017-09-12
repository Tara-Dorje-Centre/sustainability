<?php 
include_once("_includes.php");

$context = $_GET['context'];
$scope = $_GET['scope'];
$action = $_GET['pageAction'];
switch ($scope){
	case 'detail':
		/* $action = sessionVariableGET('pageAction','VIEW');
		$id = sessionVariableGET('id',0); */
		$t = new UserType();
		//$action,$id);
		break;
	case 'save':
		$um = new application\entities\reference\userType();
		$um->collectPostValues();
		$um->saveChanges();
		//omit break and print default list
		//break
	case 'list':
		//omit break and print default list
		//break;
	default:
		$t = new application\entities\reference\userTypeList();
		/*$page = sessionVariableGET('resultsPage', 1);
		$details = 10;
		$t->setPaging($page, $details);*/
}

$t->getRequestArguments();
$t->setDetails();
$t->printPage();

?>
