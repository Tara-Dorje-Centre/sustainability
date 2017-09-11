<?php
namespace application\entities\reference\sql;


class measureTypeSQL extends \application\sql\entityTypeSQL{
//table missing display order
	public function __construct(){
		$this->baseTable = 'measure_types';
	}
}

?>
