<?php
namespace application\entities\reference\sql;


class anyTypeSQL extends \application\sql\entityTypeSQL{
//missing display order from table?
	public function __construct(){
		$this->baseTable = 'user_types';
	}
}

?>
