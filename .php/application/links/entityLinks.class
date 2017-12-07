<?php
namespace application\links;


abstract class entityLinks implements IentityLinks {
	public $menu;
	public $request;
	public $entityContext = 'entity';
	protected $pagePortal = 'portal.php';
	
	public function __construct(string $entity = 'undefined',string $menuName = 'section-heading'){
		$this->menu = new linkMenu($menuName,'LIST','menu');
		$this->setEntityContext($entity);
	}
	public function setEntityContext(string $entity){
		$this->entityContext = $entity;
		$this->setRequest();
	}
	/*public function print(){
		return $this->menu->print();
	}*/
	protected function setRequest(){
		$this->request = new entityRequest($this->entityContext,$this->portalPage);
	}

	public function listingLinks($mode,$id,$idParent,$idType){
		$this->menu->addLink($this->list('List',0,$idParent,0));
		$this->menu->addLink($this->list('ListChildren',$id,0,0));
		$this->menu->addLink($this->list('listType',0,0,$idType));
	}
	public function editingLinks($mode = 'VIEW', $id = 0,$idParent = 0,$idType = 0){
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
	
	
	public function detail(string $caption, $action, $id,$idParent=0,$idType=0){
		$url = $this->request->getUrlEntityDetail($action,$id,$idParent,$idType);
		$l = $this->menu->getLink($url,$caption);
		return $l;	
	}	
	public function list(string $caption, $id,$idParent=0,$idType=0){
		$url = $this->request->getUrlEntityList($id,$idParent,$idType);
		$l = $this->menu->getLink($url,$caption);
		return $l;	
	}
	
	public function add($caption = 'New', $idParent){
		return $this->detail($caption,'ADD',0,$idParent);
	}
	public function view($caption = 'View', $id){
		return $this->detail($caption,'VIEW',$id);
	}
	public function edit($caption = 'Edit', $id){
		return $this->detail($caption,'EDIT',$id);
	}
	public function copy($caption = 'Copy', $id){
		return $this->detail($caption,'COPY',$id);
	}
	
	
	public function contextList(){
		return $this->list($this->entityContext,0,0);
	}
	
	public function viewEdit($caption, $id,$editCaption='[#]'){
		$d = new \html\_div('list-link','list-item-link');
		$lView = $this->view($caption,$id);
		$lEdit = $this->edit($editCaption,$id);
		$d->addContent($lView->print());
		$d->addContent($lEdit->print());
		return $d->print();
	}	
	
	public function pagedListing($count, $page, $rows, $id=0, $idParent = 0, $idType = 0){
		$p = new linkMenuPaged($rows,'resultsPage');

		$url = $this->request->getUrlEntityList($id,$idParent,$idType);
		return $p->makePagedLinks($url, $count,$page);
	}
		
	
}

?>
