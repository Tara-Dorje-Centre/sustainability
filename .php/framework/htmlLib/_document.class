<?php

namespace html;

class _document {
	protected $html;
	protected $head;
	protected $body;
	protected $bodyIsSet = false;
	protected $title;
	protected $_styles;
	protected $_scripts;
	public function __construct($title, $onLoad = 'none'){
	
	$this->html= new _anyElement('html');
	$this->head = new _anyElement('head');
	$this->title = new _anyElement('title');
		$this->title->setContent($title);
	$this->body = new _anyElement('body');
		$this->body->makeAttribute('onload', $onLoad);
	}
	public function setStyles($styles = null){
		$this->_styles = $styles;
	
	}
	public function setScripts($scripts = null){
		$this->_scripts = $scripts;
	
	}
	protected function doctype(){
		$declare = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">';
		return $declare;	
	}
	
	protected function equivs(){
		$css = new _meta('Content-Style-Type','text/css');
		$text = new _meta('content-type','text/html; charset=UTF-8');
	
		$m = $css->print();
		$m .= $text->print();
		return $m;
	}
	private function buildDocumentHead(){
		$this->head->addContent($this->equivs());		
		$this->head->addContent($this->_styles);
		$this->head->addContent($this->title->print());
		$this->head->addContent($this->_scripts);
	
	}
	public function print(){
		if ($this->bodyIsSet == false){
			die('html document body not set');
		}
		$content = $this->doctype();
		
		$this->buildDocumentHead();
		
		$this->html->setContent($this->head->print());
		$this->html->addContent($this->body->print());
		$content .= $this->html->print();

		return $content;	
	}
	
	public function setBody($content){
		$this->bodyIsSet = true;
		$this->body->setContent($content);
	}
	
}

?>
