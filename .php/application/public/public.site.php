<?php
namespace application;

class publicSite extends \html\pagePublic{
	private $links;
	private $sql;
	private $viewMode = 'MAIN';
    private $viewId = 0;
	private $paging = 0;
	private $found = 0;
	
	public function __construct(){
		$this->links = new links\publicSiteLinks;
		$this->sql = new sql\publicSiteSQL;	
		
	}

	public function setDetails($viewMode = 'MAIN', $viewId = 0, $paging = 0){

		$this->viewMode = $viewMode;
		$this->viewId = $viewId;
		$this->paging = $paging;
	
		$this->getStyleDetails();
		$this->getSiteContents();	
		$this->getMenuItems();
		$this->getPageContents();
		$this->getPageDetails();
	}

	protected function getStyleDetails(){
		$sql = $this->sql->siteStyles();

		$result = $this->sql->getResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	
			$this->fillColor = $row["public_fill_color"];
			$this->siteColor = $row["public_site_color"];
			$this->pageColor = $row["public_page_color"];
			$this->menuColor = $row["public_menu_color"];
			$this->menuColorHover = $row["public_menu_color_hover"];
			$this->textColor = $row["public_text_color"];
			$this->textColorHover = $row["public_text_color_hover"];
			$this->fontFamily = $row["public_font_family"];
			$this->fontSizeTitle = $row["public_font_size_title"];
			$this->fontSizeHeading = $row["public_font_size_heading"];
			$this->fontSizeMenu = $row["public_font_size_menu"];
			$this->fontSizeText = $row["public_font_size_text"];
					
		}
		$result->close();
		}

	}

	private function getSiteContents(){
		$sql = $this->sql->siteContent();
		
		$result = $this->sql->getResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{	
			$org = $row['organization'];
			$orgUrl = $row['organization_url'];
			$contactName = $row['contact_name'];
			$contactEmail = $row['contact_email'];
			$showPublicSite = $row['show_public_site'];
		}
		$result->close();
		}
		
		if ($showPublicSite == 'no'){
			include('portal.php');
			die;
		}
		
		$this->mainTitle = $org;
		//$this->mainImage = image('images/logo.gif','current site image',150,75,0,'public-site-image');
		
		$sp = \html\spacer();
		$f = new \html\_div('site-footer-details');
		
		$year = date('Y',time());
		$f->addContent('&copy;'.$year.$sp);
		
		$l = $this->links->buildLink($orgUrl, $org, 'none');
		// target="_blank" not supported in build link
		$f->addContent($l->print().$sp);
		
		$href = 'mailto:'.$contactEmail;
		$m = $this->links->buildLink($href, $contactName, 'none');
		$f->addContent('Please contact '.$sp.$m->print().' for more information.');
		
		$l = $this->links->buildLink('portal.php', 'Project Planning Site', 'none');
		//target="_self" not supported n buildLink
		$f->addContent($sp.$l->print());
		
		$this->mainFooter = $f->print();
		//echo $this->mainFooter;
	}
	
	private function getMenuItems(){
		$m = new \html\_div('site-menu-content');

		//$m->addContent(
		$l = $this->links->menuLink('Main Menu');
		$this->links->addLink($l);
		
		$sql = $this->sql->menuItems($this->viewMode, $this->viewId);
		
		$result = $this->sql->getResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{

			$caption = $row['caption'];
			$viewMode = $row['view_mode'];
			$viewId = $row['view_id'];

			$css = 'public-menu-item';
			if ($this->viewMode == $viewMode && $this->viewId == $viewId){
				//menu item is for current page
				$css .= '-current';	
			}
				
			//$m->addContent(
			$l = $this->links->menuLink($caption,$viewMode,$viewId,$css);
			$this->links->addLink($l);
		}		
		$result->close();
		}
		$m->addContent($this->links->print());
		$this->menuItems = $m->print();
	
	}

	private function getPageContents(){
	
		$sql = $this->sql->pageContent($this->viewMode, $this->viewId);
	
		$result = $this->sql->getResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{			
			$this->pageTitle = $row['title'];
			$this->pageContents = nl2br($row['content']);	
		}
		$result->close();
		}
		
	}
	
	private function getPageDetails(){
		$d = new \html\_div();
		$sql = $this->sql->detailContent($this->viewMode, $this->viewId);

		$result = $this->sql->getResult($sql);
		if($result){
		while ($row = $result->fetch_assoc())
		{			
			
			$heading = nl2br($row["heading"]);
			if (strlen($heading) > 0){
				$p = new \html\_p('none','public-detail-title');
				$p->addContent($heading);
				$d->addContent($p->print());
			}
			
			$contents = nl2br($row["content"]);
			if (strlen($contents) > 0){
				$p = new \html\_p('none','public-detail-item');
				$p->addContent($contents);
				$d->addContent($p->print());
			}

			$linkText = $row["link_text"];
			$linkUrl = $row["link_url"];
			if (strlen($linkText) > 0 && strlen($linkUrl) > 0){
				$l = $this->links->buildLink($linkUrl, $linkText, 'none');
				// target="_blank" not available via buildLink
				$p = new \html\_p('none','public-detail-link');
				$p->addContent($l->print());
				$d->addContent($p->print());
			}

		}
		$result->close();
		}

		$this->pageDetails = $d->print();	
	}

}
?>
