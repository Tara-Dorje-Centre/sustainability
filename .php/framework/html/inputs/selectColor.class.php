<?php
namespace html\inputs;

class selectColor extends _select{
	
	public function __construct($name, $value, $caption, $onChangeJS = null){
		parent::__construct($name,$value,$caption,$onChangeJS);

		$this->defaultOption('none','no color');
		$this->makeOptions();
	}
	protected function makeOptions(){
		$sql = " SELECT c.name as value, c.name as caption ";
		$sql .= " FROM css_colors AS c ";
		$sql .= " ORDER BY value ";
		$this->optionsByQuery($sql);
	}
}

?>
