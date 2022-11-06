<?php
namespace html;

class displayList extends displayDetails{
	public $_list;
	protected $links;
	public function __construct(string $entity = 'entity',string $links = 'links',string $legend = 'legend',string $quickEdit = 'none',string $contextMenu = 'none'){
	     parent::__construct($entity,'list',$legend,$quickEdit,$contextMenu);
	     $this->links = $links;
	     $this->_list = new _table($this->entity.'displayListTable', 'displayListTable');

	}
	public function addRow(_tr &$tr){
		$this->_list->addRow($tr);
	}
	
	public function makeRow($content){
		$this->_list->makeRow($content);
	}
	public function addContent($content){
		$this->makeRow($content);
	}
	protected function open(){
		$this->_fields->addContent($this->links);
		$this->_fields->addContent($this->_list->print());
		parent::open();
	}

}

?>
