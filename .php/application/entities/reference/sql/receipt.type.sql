<?php
namespace application\entities\reference\sql;


class receiptTypeSQL extends \application\sql\entityTypeSQL{
	public function __construct(){
		$this->baseTable = 'receipt_types';
	}
}

?>
