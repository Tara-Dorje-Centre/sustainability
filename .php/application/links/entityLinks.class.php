<?php
namespace application\links;


abstract class entityLinks implements IentityLinks {
	public $menu;
	public $request;
	public $entityContext = 'entity';
	public $pagePortal = 'portal.php';
	
	public function __construct(string $entity = 'undefined',string $menuName = 'section-heading-links'){
		$this->menu = new linkMenu($menuName,'LIST','menu');
		$this->setEntityContext($entity);
	}
	/*
	public function formatToggleLink($l,$c){
	
	}
	*/
	public function setEntityContext(string $entity){
		$this->entityContext = $entity;
		$this->setRequest();
	}
	
	protected function setRequest(){
		$this->request = new entityRequest($this->entityContext,$this->pagePortal);
	}
	
	public function print(){
		return $this->menu->print();
	}
	
	public function addLink(link $l){
		$this->menu->addLink($l);
	}
	
	public function getLink(url $url, $caption){
		$l = $this->menu->getLink($url,$caption);
		return $l;	
	}
	
	public function listingLinks(string $mode = 'LIST',$id = 0,$idParent = 0,$idType = 0){
		$this->menu->addLink($this->list('List',0,$idParent,0));
		$this->menu->addLink($this->list('ListChildren',$id,0,0));
		$this->menu->addLink($this->list('listType',0,0,$idType));
	}
	public function editingLinks(string $mode = 'VIEW', $id = 0,$idParent = 0,$idType = 0){
		switch ($mode){
			case 'VIEW':
				$this->menu->addLink($this->list('List',0,0,0));
				$this->menu->addLink($this->edit('Edit',$id));
				$this->menu->addLink($this->add('AddChild',$id));
				$this->menu->addLink($this->add('AddSibling',$idParent));
				$this->menu->addLink($this->copy('Copy',$id));
				break;
			case 'EDIT':
				$this->menu->addLink($this->list('List',0,$idParent,0));
				$this->menu->addLink($this->view('View',$id));
				
				break;
			case 'ADD':
				$this->menu->addLink($this->list('List',0,$idParent,0));
				break;
			default:
				$this->menu->addLink($this->list('List',0,$idParent,0));
				$this->menu->addLink($this->view('View',$id));
		
		}
		
	}
	
	
	public function detail(string $caption,string $action, $id = 0, $idParent=0, $idType=0){
		$url = $this->request->getUrlEntityDetail($action,$id,$idParent,$idType);
		$l = $this->menu->getLink($url,$caption);
		return $l;	
	}	
	public function list(string $caption, $id = 0, $idParent=0, $idType=0){
		$url = $this->request->getUrlEntityList('LIST',$id,$idParent,$idType);
		$l = $this->menu->getLink($url,$caption);
		return $l;	
	}
	
	public function add(string $caption = 'New', $idParent = 0){
		return $this->detail($caption,'ADD',0,$idParent);
	}
	public function view(string $caption = 'View', $id = 0){
		return $this->detail($caption,'VIEW',$id);
	}
	public function edit(string $caption = 'Edit', $id = 0){
		return $this->detail($caption,'EDIT',$id);
	}
	public function copy(string $caption = 'Copy', $id = 0){
		return $this->detail($caption,'COPY',$id);
	}
	
	
	public function contextList(){
		return $this->list($this->entityContext,0,0);
	}
	
	public function viewEdit(string $caption, $id = 0,string $editCaption='[#]'){
		$d = new \html\_div('list-link','list-item-link');
		$lView = $this->view($caption,$id);
		$lEdit = $this->edit($editCaption,$id);
		$d->addContent($lView->print());
		$d->addContent($lEdit->print());
		return $d->print();
	}	
	
	public function pagedListing($count, $page, $rows = 10, $id=0, $idParent = 0, $idType = 0){
		$p = new linkMenuPaged($rows,'results-page');

		$url = $this->request->getUrlEntityList('LIST',$id,$idParent,$idType);
		return $p->makePagedLinks($url, $count,$page);
	}
		
	
}

?>
