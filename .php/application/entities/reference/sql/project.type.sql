<?php
namespace application\entities\reference\sql;


class projectTypeSQL extends \application\sql\entityTypeSQL{
	public function __construct(){
		$this->baseTable = 'project_types';
	}
}

?>
