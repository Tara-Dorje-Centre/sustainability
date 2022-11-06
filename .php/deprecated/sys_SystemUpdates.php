<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");


class _SystemUpdate{
	public $displayMode;
	public $year = 0;
	public $month = 0;
	
	public function setDetails($displayMode = 'SYSTEM-UPDATES', $year = 0, $month = 0){
		$this->displayMode = $displayMode;
		$this->year = $year;
		$this->month = $month;
	}
	



	private function runUpdates(){
		
//$sql = "set @@session.time_zone='-7:00'";
$content = 'add activity to materials'.br();
$sql = "ALTER TABLE `materials`  ADD `activity_id` BIGINT(20) NOT NULL DEFAULT '0'";
mysql_query( $sql );// or exit(
$content .= 'errors:'.mysql_error().br();

$content .= 'add activity to measures'.br();
$sql = "ALTER TABLE `measures`  ADD `activity_id` BIGINT(20) NOT NULL DEFAULT '0'";
mysql_query( $sql );// or exit(mysql_error());
$content .= 'errors:'.mysql_error().br();//);

$content .= 'add activity to receipts'.br();
$sql = "ALTER TABLE `receipts`  ADD `activity_id` BIGINT(20) NOT NULL DEFAULT '0'";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);

$content .= 'add highlight style to measure types'.br();
$sql = "ALTER TABLE `measure_types`  ADD `highlight_style` VARCHAR(100) NOT NULL";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);

$content .= 'add display order to measure types'.br();
$sql = "ALTER TABLE `measure_types`  ADD `display_order` INT(4) NOT NULL DEFAULT '0'";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);


$content .= 'add highlight style to activity types'.br();
$sql = "ALTER TABLE `activity_types`  ADD `highlight_style` VARCHAR(100) NOT NULL";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);

$content .= 'add display order to activity types'.br();
$sql = "ALTER TABLE `activity_types`  ADD `display_order` INT(4) NOT NULL DEFAULT '0'";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);

$content .= 'Modify activity comments to store up to 4000 characters'.br();
$sql = "ALTER TABLE `activities` CHANGE `comments` `comments` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);

$content .= 'Modify task description to store up to 4000 characters'.br();
$sql = "ALTER TABLE `tasks` CHANGE `description` `description` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);

$content .= 'Modify task summary to store up to 4000 characters'.br();
$sql = "ALTER TABLE `tasks` CHANGE `summary` `summary` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);


$content .= 'Modify project description to store up to 4000 characters'.br();
$sql = "ALTER TABLE `projects` CHANGE `description` `description` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);

$content .= 'Modify project summary to store up to 4000 characters'.br();
$sql = "ALTER TABLE `projects` CHANGE `summary` `summary` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);

$content .= 'Modify project purpose to store up to 4000 characters'.br();
$sql = "ALTER TABLE `projects` CHANGE `purpose` `purpose` VARCHAR(4000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL";
mysql_query( $sql );
$content .= 'errors:'.mysql_error().br();//);


	
	return $content;
	}

	private function displayUpdates(){
		$entity = 'system-update';
		$legend = 'System Updates';
		$c = new ProjectLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$content = openEditForm($entity,$legend,'sys_Menu.php?displayMode=SYSTEM-UPDATES', $contextMenu);
		$formRequired = 'click options to see update status';
		
		$fields = $this->runUpdates();
//		$fields = 'no optional fields';
		//$formRequired = $fields;
		$formOptional = $fields;
		$formSubmit = 'no submit buttons';
		$content .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);
		return $content;
	
	}

	public function display(){

		
		if ($this->displayMode == 'SYSTEM-UPDATES'){
			$content = $this->displayUpdates();
		} else {
			$content = NULL;
		}
		return $content;
	}


}


?>