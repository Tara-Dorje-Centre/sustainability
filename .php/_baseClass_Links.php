<?php 
include_once("_htmlFunctions.php");
class _Links{
	private $cssStyleBase;
	public $cssItem;
	private $cssList;
	private $menuType;
	private $menuName;  

	private $saveMenuType;
	private	$saveBaseStyle;
	private	$saveMenuName;

	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		$this->setStyle($styleBase);
		$this->setType($menuType);
	}

	public function setStyle($styleBase = 'menu'){
		$this->cssStyleBase = $styleBase;
		$this->cssItem = $styleBase.'-links-item';
		$this->cssList = $styleBase.'-links-list';
	}

	public function setType($menuType = 'DIV'){
		$this->menuType = $menuType;
	}

	private function formatItem($content){
		if ($this->menuType == 'LIST'){
			//add list item tag and same style
			$item = listItem($content,$this->cssItem.'-li');
		} else {
			$item = $content;
		}
		return $item;		
	}
	public function formatToggleLink($elementId = 'formOptional', $linkText = '+Options'){
		$item = $this->formatHref($linkText,'#!','_self','',"toggle('".$elementId."');");
		return $item;
	}
	public function formatHref($caption,$link,$target = '_self',$cssSuffix = '',$onClickJS = NULL){
		$href = getHref($link, $caption, $this->cssItem.$cssSuffix,$target,$onClickJS);
		$item = $this->formatItem($href);
		return $item;
	}

	public function formatTextItem($text){
		$content = span($text,$this->cssItem);
		$item = $this->formatItem($content);
		return $item;
	}
	
	public function openMenu($name = 'menu-listing'){
		$this->menuName = $name;
		
		$menu = openDiv($this->menuName,$this->cssList);
		if ($this->menuType == 'LIST'){
			$menu .= openList($this->menuName.'-ul',$this->cssList.'-ul');
		}
		return $menu;
	}

	public function closeMenu(){
	
		if ($this->menuType == 'LIST'){
			$menu = closeList().closeDiv();
		} else {
			$menu = closeDiv();
		}
		return $menu;
	}

	public function resetMenu($newLines = 0){
		if ($this->menuType == 'LIST'){
			$menu = closeList();
			if ($newLines > 0){
				$menu .= br($newLines);
			}
			$menu .= openList($this->menuName.'-ul',$this->cssList.'-ul');
		} else {
			if ($newLines == 0){
				$newLines = 1;
			}
			$menu = br($newLines);
		}
		return $menu;	
	}

	private function saveMenuSettings(){
		$this->saveMenuType =$this->menuType;
		$this->saveBaseStyle = $this->cssStyleBase;
		$this->saveMenuName = $this->menuName;		
	}

	private function restoreMenuSettings(){
		$this->setType($this->saveMenuType);
		$this->setStyle($this->saveBaseStyle);	
		$this->menuName = $this->saveMenuName;
	}

	public function getPagedLinks($baseUrl, $recordCount, $recordsPerPage, $currentPage){
		$this->saveMenuSettings();
		$this->setType('DIV');
		$this->setStyle('paged');
		$i = 0;
		$pages = 1;
		$linkset;
		$linkCaption;
		$link;
		$linksAllPages = '';
		$minRecord = 1;
		$maxRecord = 1;
		$recordRange;
		//calculate how many pages results will display in
		if (floor($recordCount/$recordsPerPage)==$recordCount/$recordsPerPage){
			$pages = floor($recordCount/$recordsPerPage);
		} else {
			$pages = floor($recordCount/$recordsPerPage) + 1;
		}
		$linkset = $this->openMenu('paged-linkset');

		//build linksAllPages
		if ($currentPage > 1){
			$linkCaption = spacer().'Prev'.spacer();
			$prevIndex = $currentPage - 1;
			$link = $this->formatHref($linkCaption,$baseUrl.$prevIndex);
			$linksAllPages .= $link;
		}

		while ($i < $pages){
			$i++;
			$minRecord = (($i - 1)* $recordsPerPage) + 1;
			$maxRecord = $i * $recordsPerPage;
			if ($maxRecord > $recordCount){
				$maxRecord = $recordCount;
			}
			if ($minRecord==$maxRecord){
				$recordRange = $minRecord;
			} else {
				$recordRange = $minRecord.'-'.$maxRecord;
			}
			//highlight current page with square brackets and skip hyperlink
			if ($i == $currentPage){
				//$linkCaption = ' ['.$recordRange.'] ';
				$linkCaption = '['.spacer(2).$i.spacer(2).']';
				$link = span($linkCaption,$this->cssItem.'-current');	
			} else {
				//$linkCaption = '('.$recordRange.')';	
				$linkCaption = spacer(2).$i.spacer(2);
				$link = $this->formatHref($linkCaption,$baseUrl.$i);
			}
			if ($i == 1 || ($i >= ($currentPage - 4) && $i <= ($currentPage + 4)) || $i == $pages){ 
		
				if ($i == $pages && ($currentPage + 5) < $pages){
					$linksAllPages .= span('...',$this->cssItem);
				}
				$linksAllPages .= $link;
				if ($i == 1 && ($currentPage - 5) > 1){
					$linksAllPages .= span('...',$this->cssItem);
				}
			}
		}
		
		if ($currentPage < $pages){
			$linkCaption = spacer().'Next'.spacer();
			$prevIndex = $currentPage + 1;
			$link = $this->formatHref($linkCaption,$baseUrl.$prevIndex);
			$linksAllPages .= $link;
		}
			
		if ($pages > 1){
			$linkset .= 'Found '.$recordCount.' results. ';
			$linkset .= 'Page '.$currentPage.' of '.$pages.': ';
			$linkset .= $linksAllPages;
		} else {
			$linkset .= 'Displaying '.$recordCount.' results. ';
		}
	
		$linkset .= $this->closeMenu();
	
		$this->restoreMenuSettings();
		return $linkset;
	}

	public function formatCalendarLink($baseUrl, $year, $month){
		$url = $baseUrl.'&year='.$year.'&month='.$month;
		return $url;
	}
	public function formatCalendarHref($linkCaption, $baseUrl, $year, $month){
		$url = $this->formatCalendarLink($baseUrl,$year,$month);	
		$link = $this->formatHref($linkCaption, $url);
		return $link;	
	}
}
?>