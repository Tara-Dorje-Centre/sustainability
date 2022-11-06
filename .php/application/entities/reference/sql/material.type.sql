<?php
namespace application\entities\reference\sql;


class materialTypeSQL extends \application\sql\entityTypeSQL{
	public function __construct(){
		$this->baseTable = 'material_types';
	}
}

?>
