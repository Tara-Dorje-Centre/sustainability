<?php
namespace application\entities\reference\sql;

class activityTypeSQL extends \application\sql\entityTypeSQL{
	public function __construct(){
		$this->baseTable = 'activity_types';
	}
}

?>
