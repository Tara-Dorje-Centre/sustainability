<?php
namespace html;


interface IPage{
	public function print();
	public function setDocument(string $title = 'Sustainability',string $styles = 'none',string $scripts = 'none',string $onLoad = 'none');
	public function setMain($contents);
		public function setMainLinks($links);
	public function setFooter(string $links = 'none',string $notices = 'none',string $logo = 'none');
}
class page extends \framework\_echo
implements IPage{
	protected $_document;
	protected $_container;
	protected $title;
	protected $linksHeader;
	protected $mainContents;
	protected $linksFooter;
	protected $notices;
	protected $logo;
	
	public function setDocument(string $title = 'Sustainability',string $styles = 'none',string $scripts = 'none',string $onLoad = 'none'){
		$this->title = $title;
		
		$this->_document = new _document($this->title,$onLoad);
		$this->_document->setStyles($styles);
		$this->_document->setScripts($scripts);
		
	}
	public function setMainLinks($links){
		$this->linksHeading = $links;
	}
	public function setMain($contents){
		$this->mainContents = $contents;
		//$this->linksHeader = $links;
	}
	
	public function setFooter(string $links = 'none',string $notices = 'none',string $logo = 'none'){
		$this->logo = $logo;
		$this->notices = $notices;
		$this->linksFooter = $links;
	}
	public function print(){		
		$content = $this->build();
		echo $content;		
		/*
		//close db connection
		global $conn;
		$conn->close;
		*/
	}

	protected function build(){		

		$this->_container = new _div('site-container');
		$this->_container->addContent($this->formatHeading());
		$this->_container->addContent($this->formatContents());	
		$this->_container->addContent($this->formatFooter());
		
		$this->_document->setBody($this->_container->print());
		return $this->_document->print();		
	}
	
	protected function formatHeading(){
		$h = new _div('site-heading');
		$h->setContent($this->formatTitle());
		$h->addContent($this->formatLinksHeading());
		return $h->print();
	}
	
	protected function formatTitle(){
		$t = new _div('site-heading-title');
		$t->setContent($this->title);
		return $t->print();
	}

	protected function formatLinksHeading(){
		$l = new _div('site-heading-links');
		$l->setContent($this->linksHeading);
		return $l->print();
	}

	protected function formatContents(){
		$c = new _div('site-content');
		$c->setContent($this->mainContents);
		return $c->print();
	}
	
	protected function formatFooter(){
		$f = new _div('site-footer');
		$f->setContent($this->formatLogo());
		$f->addContent($this->formatNotices());
		$f->addContent($this->formatLinksFooter());
		return $f->print();
	}
	
	protected function formatLogo(){
		$l = new _div('site-footer-logo');
		$l->setContent($this->logo);
		return $l->print();
	}
	protected function formatNotices(){
		$n = new _div('site-footer-notices');
		$n->setContent($this->notices);
		return $n->print();
	}
	protected function formatLinksFooter(){	
		$l = new _div('site-footer-links');
		$l->setContent($this->linksFooter);
		return $l->print();
	}

}


?>
