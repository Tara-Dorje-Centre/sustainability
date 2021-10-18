<?php
namespace html;

class _href extends _anyElement{

	public function __construct(string $url, string $display){
		parent::__construct('a');
		$this->makeAttribute('href',$url);
		if (is_null($display) or ($display == '')){
			$display = $url;
		}
		$this->setContent($display);
	}

	public function setTarget(string  $target = '_self'){
		if (($target <> '_self') && (!is_null($target))){
			$this->makeAttribute('target',$target);
		}
	}
	
	public function setOnClickScript($onClickJS){
		if (!is_null($onClickJS)){
			$this->makeAttribute('onclick',$onClickJS);
		}

	}
	
}




?>
