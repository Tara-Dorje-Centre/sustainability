<?php
namespace html\inputs;


class selectHighlight extends _select{
	
	public function __construct($name, $value, $caption, $onChangeJS = null){
		parent::__construct($name,$value,$caption,$onChangeJS);

		$this->defaultOption('none','no highlight');
		$this->makeOptions();
	}
	protected function makeOptions(){
		$sql = " SELECT style_name as value, style_name as caption ";
		$sql .= " FROM css_highlight_styles ";
		$sql .= " ORDER BY style_name ";
		$this->optionsByQuery($sql);
	}
	

}





?>
