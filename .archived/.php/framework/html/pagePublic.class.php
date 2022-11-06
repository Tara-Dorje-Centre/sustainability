<?php
namespace html;
class pagePublic extends  page
implements IPage {
	protected $_document;
	public $mainTitle;
	public $mainImage;
	public $mainFooter;
	public $menuItems;
	public $pageTitle;
	public $pageContents;
	public $pageDetails;
	public $fillColor = 'purple';		
	public $siteColor = 'yellow';
	public $pageColor = 'white';
	public $menuColor = 'lightgreen';
	public $menuColorHover = 'green';
	public $textColor = 'black';
	public $textColorHover = 'white';
	public $fontFamily = 'Arial, sans-serif';
	public $fontSizeTitle = 20;
	public $fontSizeHeading = 18;
	public $fontSizeMenu = 16;
	public $fontSizeText = 14;
	
	public function print(){
		
		$content = $this->build();
		echo $content;	
		
		//close database connection
		global $conn;
		$conn->close();

	}
	
	public function setDocument(string $title = 'Sustainability',string $styles = 'none',string $scripts = 'none',string $onLoad = 'none'){
	
	}
	public function setMainLinks($links){
		$this->linksHeader = $links;
	}
	public function setMain($contents){
		$this->mainContents = $contents;
		$this->linksHeader = $links;
	}
	public function setFooter(string $links = 'none',string $notices = 'none',string $logo = 'none'){
	
	}

	
	
	protected function build(){
		$this->_document = new _document($this->mainTitle);
		$this->_document->setStyles($this->siteStyles());
		
		$c = new _div('public-site-container');
		$c->addContent($this->siteHeading());
		$c->addContent($this->siteContent());
		$c->addContent($this->siteFooter());
		$this->_document->setBody($c->print());
		return $this->_document->print();
		
	}

	protected function siteContent(){
		$c = new _div('public-site-content');
		$c->addContent($this->siteMenu());
		$c->addContent($this->pageContent());
		//echo $c->print();
		return $c->print();
	}

	protected function siteMenu(){
		$m = new _div('public-site-menu');
		$m->addContent($this->menuItems);
		//echo $this->menuItems;
		return $m->print();
	}

	protected function pageContent(){
		$c = new _div('public-page-container');
		
		$t = new _div('public-page-title');
		$t->setContent($this->pageTitle);
		$c->addContent($t->print());
		
		$p = new _div('public-page-content');
		$p->setContent($this->pageContents);
		$c->addContent($p->print());
		
		$d = new _div('public-page-details');
		$d->addContent($this->pageDetails);
		$c->addContent($d->print());
	
		return $c->print();
	}

	protected function siteFooter(){
		$f = new _div('public-site-footer');	
		$f->addContent($this->mainFooter);
		//echo $this->mainFooter;
		//echo $f->print();
		return $f->print();
	}

	protected function siteHeading(){
		$h = new _div('public-site-heading');
		
		$i = new _div('public-site-image');
		$i->addContent($this->mainImage);
		$h->addContent($i->print());
		
		$t = new _div('public-site-title');
		$t->addContent($this->mainTitle);
		$h->addContent($t->print());
		
		return $h->print();
	}


	protected function buildInternalStyles(){
	
		$css = '<style>';
		//openInlineStyle();
	
		$clearMarginPadding = \css\clearMarginPadding();		
		$clearMarginPaddingBorder = \css\clearMarginPaddingBorder();
		$marginThin = \css\setMargin(5);
		$marginThick = \css\setMargin(10);
		$marginLeftWide = \css\setMarginCustom('left', 170);
		$paddingThin = \css\setPadding(5);
		$paddingThick = \css\setPadding(10);
		$positionRelative = \css\setPosition('relative');
		$clearTextDecoration = \css\textDecoration('none');
		$widthFull = \css\widthPercent(100);
		$width150 = \css\widthPixels(150);
		$floatLeft = \css\setFloat('left');
		$clearBoth = \css\setClear('both');
		$verticalAlignTop = \css\verticalAlign('top');
		$fontNormal = \css\setFont($this->fontSizeText, 'normal', 'normal');
		$fontBold = \css\setFont($this->fontSizeText, 'normal', 'bold');
		$fontItalic = \css\setFont($this->fontSizeText, 'italic', 'normal');
		$fontTitle = \css\setFont($this->fontSizeTitle, 'normal', 'bold');
		$fontHeading = \css\setFont($this->fontSizeHeading, 'normal', 'normal');
		$fontMenu = \css\setFont($this->fontSizeMenu, 'normal', 'normal');

		$fontFooter = \css\setFont(11, 'normal','normal');
		$textColor = \css\colorText($this->textColor);
		$textColorHover = \css\colorText($this->textColorHover);
		$fillColor = \css\colorBackground($this->fillColor);
		$siteColor = \css\colorBackground($this->siteColor); 
		$pageColor = \css\colorBackground($this->pageColor);
		$menuColor = \css\colorBackground($this->menuColor);
		$menuColorHover = \css\colorBackground($this->menuColorHover);
		
				
		$p = \css\setFontFamily($this->fontFamily);
		$p .= $fontNormal;
		$p .= $textColor;
		$p .= $fillColor;
		$p .= $clearMarginPaddingBorder;
		$css .= \css\wrapStyle('body', $p);
		
		$css .= \css\wrapStyle('div', $clearMarginPaddingBorder);
		$css .= \css\wrapStyle('p', $clearMarginPaddingBorder);
		$css .= \css\wrapStyle('.site-links-list', $clearMarginPaddingBorder);

		$p = $clearMarginPadding;
		$p .= \css\clearListStyle();
		$css .= \css\wrapStyle('.site-links-list-ul', $p);

		$css .= \css\wrapStyle('.site-links-item-li', $clearMarginPadding);
		//define a style matching site-links-item but using hover color and hover text color 
		//menu item that matches the current page uses this style via -current suffix
		$p = $menuColorHover;
		$p .= \css\setDisplay('block');
		$p .= $clearTextDecoration;
		$p .= $textColorHover;
		$p .= $fontMenu;
		$p .= \css\textAlign('left');
		$p .= $paddingThin;
		$css .= \css\wrapStyle('.site-links-item-current', $p);

		$p = \css\setDisplay('block');
		$p .= $clearTextDecoration;
		$p .= $textColor;
		$p .= $fontMenu;
		$p .= \css\textAlign('left');
		$p .= $paddingThin;
		$css .= \css\wrapStyle('.site-links-item', $p);

		$p = $clearTextDecoration;
		$p .= $textColorHover;
		$p .= $menuColorHover;
		$css .= \css\wrapStyle('.site-links-item:hover', $p);

		$p = $positionRelative;
		$p .= $marginThick;
		$p .= $paddingThick;
		$p .= \css\minHeightPixels(400);
		$p .= $siteColor;
		$css .= \css\wrapStyle('#public-site-container', $p);

		$p = $positionRelative;
		$p .= $verticalAlignTop;
		$p .= \css\minHeightPixels(50);
		$p .= $widthFull;
		$p .= $clearBoth;
		$css .= \css\wrapStyle('#public-site-heading', $p);

		$p = $positionRelative;
		$p .= $widthFull;
		$p .= $clearBoth;
		$css .= \css\wrapStyle('#public-site-content', $p);

		$p = $positionRelative;
		$p .= $marginLeftWide;
		$p .= $paddingThick;
		$p .= $fontTitle;
		$css .= \css\wrapStyle('#public-site-title', $p);

		$p = $floatLeft;
		$p .= $width150;
		$css .= \css\wrapStyle('#public-site-image', $p);
		
		$p = $positionRelative;
		$p .= $clearBoth;
		$p .= \css\minHeightPixels(30);
		$p .= $verticalAlignTop;
		$p .= $widthFull;
		$p .= $paddingThick;
		$p .= $fontFooter;
		$css .= \css\wrapStyle('#public-site-footer', $p);

		$p = $positionRelative;
		$p .= $verticalAlignTop;
		$p .= $floatLeft;
		$p .= $width150;
		$p .= $paddingThin;
		$p .= $menuColor;
		$css .= \css\wrapStyle('#public-site-menu', $p);

		$p = $positionRelative;
		$p .= $marginLeftWide;
		$p .= $paddingThick;
		$p .= \css\minHeightPixels(350);
		$p .= $pageColor;
		$css .= \css\wrapStyle('#public-page-container', $p);
		
		$p = $positionRelative;
		$p .= $paddingThick;
		$p .= $fontHeading;
		$css .= \css\wrapStyle('#public-page-title', $p);
		
		//#public-page-content {position:relative;}
		//#public-page-details {position:relative;}

		$p = $fontHeading;
		$p .= $marginThin;
		$css .= \css\wrapStyle('.public-detail-title', $p);

		$p = $fontNormal;
		$p .= $marginThin;
		$css .= \css\wrapStyle('.public-detail-item', $p);
		

		$css .= '</style>';
		//closeInlineStyle();
		

		return $css;

	}

	protected function siteStyles(){
		//use internal stylesheet
		$content = $this->buildInternalStyles();

		//use external stylesheet
		//$content = LinkStylesheet('css/public.css');
		//$content .= stylesheet('css/publicOverrides.css');
		return $content;
		
	}
	
}
?>
