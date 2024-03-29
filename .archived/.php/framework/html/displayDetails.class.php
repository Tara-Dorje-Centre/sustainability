<?php
namespace html;

class displayDetails {
	protected $entity;
	protected $mode;
	protected $legend;
	protected $_fields;
	protected $_display;
	protected $quickEdit;
	protected $contextMenu;
	
	public function __construct(string $entity = 'entity',string $mode = 'mode',string $legend = 'legend',string $quickEdit = 'none',string $contextMenu = 'none'){
		$this->entity = $entity;
		$this->mode = $mode;
		$this->legend = $legend;
		$this->quickEdit = $quickEdit;
		$this->contextMenu = $contextMenu;
		
		$this->_display = new \html\_div($this->entity.'-display','display'.$this->mode);
		$this->_fields = new \html\_fieldset($this->entity.'-fields', $this->legend,'fieldset');
	}
	protected function open(){
		if ($this->quickEdit != 'none'){
			$d = new \html\_div($this->entity.'-quickEdit','quickEdit');
			$this->_display->addContent($d->printContent($this->quickEdit));
		}
		if ($this->contextMenu != 'none'){
			$d = new \html\_div($this->entity.'-contextMenu','contextMenu');
			$this->_display->addContent($d->printContent($this->contextMenu));
		}
		
		$this->_display->addContent($this->_fields->print());
	
	}
	
	public function setContent($content){

		$this->_fields->setContent($content);

	}
	public function addContent($content){
	
		$this->_fields->addContent($content);
	
	}
	public function print(){
		$this->open();

		return $this->_display->print();
	}
}

?>
