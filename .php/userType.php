<?php 
//namespace application;
include_once("_includes.php");


$p = new \application\portalRequest();



echo $p->context.PHP_EOL;
echo $p->scope.PHP_EOL;

//$action = $_GET['pageAction'];
switch ($p->scope){
	case 'detail':
		$t = new application\system\userType();
		break;
	case 'save':
		$um = new application\system\userType();
		$um->collectPostValues();
		$um->saveChanges();
		//omit break and print default list
		//break
	case 'list':
		//omit break and print default list
		//break;
	default:
		$t = new application\system\userTypeList();
}
$t->setIdentifiers($p->pageAction,$p->id,$p->idParent);
$t->getRequestArguments();
$t->setDetails();
$t->printPage();

?>
