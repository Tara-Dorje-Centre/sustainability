<?php
namespace html;


class _br extends _repeatingElement{
	public function __construct($lines = 1){
		parent::__construct('br',$lines);
	}
}

?>
