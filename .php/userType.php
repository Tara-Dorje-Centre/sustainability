<?php 
include_once("_includes.php");

$context = sessionVariableGET('context','entity');
$scope = sessionVariableGET('scope','list');

switch ($scope){
	case 'detail':
		$action = sessionVariableGET('pageAction','VIEW');
		$id = sessionVariableGET('id',0);
		$t = new UserType($action,$id);
		break;
	case 'save':
		$um = new UserType;
		$um->collectPostValues();
		$um->saveChanges();
		//omit break and print default list
		//break
	case 'list':
		//omit break and print default list
		//break;
	default:
		$t = new UserTypeList();
		$page = sessionVariableGET('resultsPage', 1);
		$details = 10;
		$t->setPaging($page, $details);
}

$t->getRequestArguments();
$t->setDetails();
$t->printPage();

?>
