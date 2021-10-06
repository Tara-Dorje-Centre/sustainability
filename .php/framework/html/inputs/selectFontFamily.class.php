<?php
namespace html\inputs;

class selectFontFamily extends _select{
	public function __construct($name, $value, $caption, $onChangeJS = null){
		parent::__construct($name,$value,$caption,$onChangeJS);
		$this->defaultOption('none','- no font family');
		$this->makeOptions();
	}
	protected function makeOptions(){
		$sql = " SELECT c.name as value, c.name as caption ";
		$sql .= " FROM css_fonts AS c ";
		$sql .= " ORDER BY value ";
		$this->optionsByQuery($sql);
	}
}

?>
