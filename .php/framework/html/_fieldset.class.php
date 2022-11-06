<?php
namespace html;

class _fieldset extends _element{
	protected $_legend;
	public function __construct(string $name,string $legend = 'none', string $css='none'){
		parent::__construct('fieldset',$name,$css);
		$this->_legend = $legend;
	}
	
	public function open(){
		$content = parent::open();
		if ($this->_legend != 'none'){
			$l = new _anyElement('legend','fieldset-legend');
			$l->addContent($this->_legend);
			$content .= $l->print();
		}
		return $content;
	}

}

?>
