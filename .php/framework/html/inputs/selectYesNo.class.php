<?php
namespace html\inputs;

class selectYesNo extends _select{
	
	public function __construct($name = '', $value = 'no', $caption = '', $onchangeJS = null){
		parent::__construct($name,$value, $caption,$onChangeJS);
		$this->makeOptions();
	}
	protected function makeOptions(){
		$this->makeOption('no','No');
		$this->makeOption('yes','Yes');
	}

}

?>
