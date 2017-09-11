<?php
namespace application\entities\reference\sql;


class locationTypeSQL extends \application\sql\entityTypeSQL{
	public function __construct(){
		$this->baseTable = 'location_types';
	}
}

?>
