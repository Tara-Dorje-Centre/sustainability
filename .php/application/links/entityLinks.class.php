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
	
	
	final public function setEntityContext(string $entity){
		$this->entityContext = $entity;
		$this->setRequest();
	}
	
	abstract protected function setRequest();
	/*implement per entity*/
	/*
	protected function setRequest(){
		$this->request = new entityRequest($this->entityContext,$this->pagePortal);
	}
	*/
	
	final public function print(){
		return $this->menu->print();
	}
	
	final public function addLink(link $l){
		$this->menu->addLink($l);
	}
	
	final public function getLink(url $url, $caption){
		$l = $this->menu->getLink($url,$caption);
		return $l;	
	}
	
	
	final public function buildLink(string $page, string $caption, $css = 'link-item'){
		return $this->menu->buildLink($page, $caption, $css);
	}
	
	public function listingLinks(string $mode = 'LIST',$id = 0,$idParent = 0,$idType = 0){
		$this->addLink($this->list('List',0,$idParent,0));
		$this->addLink($this->add('Add',0));
		//$this->addLink($this->list('ListChildren',$id,0,0));
		//$this->addLink($this->list('listType',0,0,$idType));
	}
	
	public function editingLinks(string $mode = 'VIEW', $id = 0,$idParent = 0,$idType = 0){
		switch ($mode){
			case 'VIEW':
				$this->addLink($this->list('List',0,0,0));
				$this->addLink($this->edit('Edit',$id));
				$this->addLink($this->add('Add',$idParent));
				//$this->addLink($this->add('AddChild',$id));
				//$this->addLink($this->add('AddSibling',$idParent));
				//$this->addLink($this->copy('Copy',$id));
				break;
			case 'EDIT':
				$this->addLink($this->list('List',0,$idParent,0));
				$this->addLink($this->view('View',$id));
				
				break;
			case 'ADD':
				$this->addLink($this->list('List',0,$idParent,0));
				break;
			default:
				$this->addLink($this->list('List',0,$idParent,0));
				$this->addLink($this->view('View',$id));
		
		}
		
	}
	
	
	public function detail(string $caption,string $action, $id = 0, $idParent=0, $idType=0){
		$url = $this->request->getUrlEntityDetail($action,$id,$idParent,$idType);
		$l = $this->getLink($url,$caption);
		return $l;	
	}	
	public function list(string $caption, $id = 0, $idParent=0, $idType=0){
		$url = $this->request->getUrlEntityList('LIST',$id,$idParent,$idType);
		$l = $this->getLink($url,$caption);
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
	
	public function contextList(string $caption = 'entity-context'){
		$linkCaption = $caption;
		if ($caption == 'entity-context'){
		     $linkCaption = $this->entityContext;
		}
		return $this->list($linkCaption,0,0);
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
