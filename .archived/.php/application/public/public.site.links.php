<?php 
namespace application\links;

class publicSiteLinks extends \application\links\linkMenu{
	public function __construct($menuType = 'LIST',$styleBase = 'site'){
		parent::__construct($menuType,$styleBase);
	}
	
	public function menuLink($caption, $viewMode = 'MAIN', $viewId = 0,$css = 'public-menu-item'){
		$page = $this->menu($viewMode, $viewId);
		$link = $this->buildLink($page, $caption, $css);
		// target="_self" not available via buildLink
		
		return $link;	
	}
	public function menu($viewMode = 'MAIN', $viewId = 0){
		$page = 'public.php';
		$page .= '?viewMode='.$viewMode;
		$page .= '&viewId='.$viewId;
		return $page;
	}
	public function menuPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&paging=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	
	
}
?>
