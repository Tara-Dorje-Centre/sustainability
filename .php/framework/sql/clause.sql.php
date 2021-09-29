<?php 
namespace framework\sql;


class clause extends \framework\_contentWriter{
	public $type;
	public function __construct($type){
		$this->type = $type;
	}

	public function print(){
		$this->getContent();
	}
}



?>
