<?php
namespace html\inputs;

class reset extends __button{
	public function __construct(string $caption = 'Reset'){
		parent::__construct('reset','reset', $caption);
	}
}
class cancel extends reset{
	public function __construct(string $caption = 'Cancel'){
		parent::__construct($caption);
	}
}
?>
