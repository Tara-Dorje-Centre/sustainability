<?php
namespace html\inputs;

abstract class __input extends \html\_emptyElement{
use valueAttribute, disabled, tooltip;
	
	public function __construct(string $type, string $name, $css = 'editing-input'){
		parent::__construct('input',$name, $css);
		$this->makeAttribute('type',$type);
	}
	
}

?>
