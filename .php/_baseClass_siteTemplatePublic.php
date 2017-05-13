<?php 
include_once("_formFunctions.php");
include_once("_htmlFunctions.php");
include_once("_cssFunctions.php");
include_once("_sqlFunctions.php");

class _SiteTemplatePublic{
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
	
	public function printSite(){
		
		$content = $this->buildSiteTemplate();
		echo $content;	
		
		//close database connection
		mysql_close();
	}
	
	protected function buildSiteTemplate(){
	
		$content = $this->siteContainerOpen();
		$content .= $this->siteHeading();
		$content .= $this->siteContent();
		$content .= $this->siteFooter();
		$content .= $this->siteContainerClose();	
		return $content;
		
	}

	protected function siteContent(){
		$content = openDiv('public-site-content');
		$content .= $this->siteMenu();
		$content .= $this->pageContent();
		$content .= closeDiv();
		return $content;
	}

	protected function siteMenu(){
		$content = openDiv('public-site-menu');
		$content .= $this->menuItems;
		$content .= closeDiv();
		return $content;
	}

	protected function pageContent(){
		$content = openDiv('public-page-container');
		$content .= openDiv('public-page-title');
		$content .= $this->pageTitle;
		$content .= closeDiv();
		$content .= openDiv('public-page-content');
		$content .= $this->pageContents;
		$content .= closeDiv();
		$content .= openDiv('public-page-details');
		$content .= $this->pageDetails;
		$content .= closeDiv();
	
		$content .= closeDiv();
		return $content;
	}

	protected function siteFooter(){
		$content = openDiv('public-site-footer');	
		$content .= $this->mainFooter;
		$content .= closeDiv();
		return $content;
	}

	protected function siteHeading(){
		$content = openDiv('public-site-heading');
		$content .= openDiv('public-site-image');
		$content .= $this->mainImage;
		$content .= closeDiv();
		$content .= openDiv('public-site-title');
		$content .= $this->mainTitle;
		$content .= closeDiv();
		$content .= closeDiv();
		return $content;
	}


	protected function buildInternalStyles(){
		$css = openTag('style');


		$clearMarginPadding = clearMarginPadding();		
		$clearMarginPaddingBorder = clearMarginPaddingBorder();
		$marginThin = setMargin(5);
		$marginThick = setMargin(10);
		$marginLeftWide = setMarginCustom('left', 170);
		$paddingThin = setPadding(5);
		$paddingThick = setPadding(10);
		$positionRelative = setPosition('relative');
		$clearTextDecoration = textDecoration('none');
		$widthFull = widthPercent(100);
		$width150 = widthPixels(150);
		$floatLeft = setFloat('left');
		$clearBoth = setClear('both');
		$verticalAlignTop = verticalAlign('top');
		$fontNormal = setFont($this->fontSizeText, 'normal', 'normal');
		$fontBold = setFont($this->fontSizeText, 'normal', 'bold');
		$fontItalic = setFont($this->fontSizeText, 'italic', 'normal');
		$fontTitle = setFont($this->fontSizeTitle, 'normal', 'bold');
		$fontHeading = setFont($this->fontSizeHeading, 'normal', 'normal');
		$fontMenu = setFont($this->fontSizeMenu, 'normal', 'normal');

		$fontFooter = setFont(11, 'normal','normal');
		$textColor = colorText($this->textColor);
		$textColorHover = colorText($this->textColorHover);
		$fillColor = colorBackground($this->fillColor);
		$siteColor = colorBackground($this->siteColor); 
		$pageColor = colorBackground($this->pageColor);
		$menuColor = colorBackground($this->menuColor);
		$menuColorHover = colorBackground($this->menuColorHover);
		
				
		$p = setFontFamily($this->fontFamily);
		$p .= $fontNormal;
		$p .= $textColor;
		$p .= $fillColor;
		$p .= $clearMarginPaddingBorder;
		$css .= wrapStyle('body', $p);
		
		$css .= wrapStyle('div', $clearMarginPaddingBorder);
		$css .= wrapStyle('p', $clearMarginPaddingBorder);
		$css .= wrapStyle('.site-links-list', $clearMarginPaddingBorder);

		$p = $clearMarginPadding;
		$p .= clearListStyle();
		$css .= wrapStyle('.site-links-list-ul', $p);

		$css .= wrapStyle('.site-links-item-li', $clearMarginPadding);
		//define a style matching site-links-item but using hover color and hover text color 
		//menu item that matches the current page uses this style via -current suffix
		$p = $menuColorHover;
		$p .= setDisplay('block');
		$p .= $clearTextDecoration;
		$p .= $textColorHover;
		$p .= $fontMenu;
		$p .= textAlign('left');
		$p .= $paddingThin;
		$css .= wrapStyle('.site-links-item-current', $p);

		$p = setDisplay('block');
		$p .= $clearTextDecoration;
		$p .= $textColor;
		$p .= $fontMenu;
		$p .= textAlign('left');
		$p .= $paddingThin;
		$css .= wrapStyle('.site-links-item', $p);

		$p = $clearTextDecoration;
		$p .= $textColorHover;
		$p .= $menuColorHover;
		$css .= wrapStyle('.site-links-item:hover', $p);

		$p = $positionRelative;
		$p .= $marginThick;
		$p .= $paddingThick;
		$p .= minHeightPixels(400);
		$p .= $siteColor;
		$css .= wrapStyle('#public-site-container', $p);

		$p = $positionRelative;
		$p .= $verticalAlignTop;
		$p .= minHeightPixels(50);
		$p .= $widthFull;
		$p .= $clearBoth;
		$css .= wrapStyle('#public-site-heading', $p);

		$p = $positionRelative;
		$p .= $widthFull;
		$p .= $clearBoth;
		$css .= wrapStyle('#public-site-content', $p);

		$p = $positionRelative;
		$p .= $marginLeftWide;
		$p .= $paddingThick;
		$p .= $fontTitle;
		$css .= wrapStyle('#public-site-title', $p);

		$p = $floatLeft;
		$p .= $width150;
		$css .= wrapStyle('#public-site-image', $p);
		
		$p = $positionRelative;
		$p .= $clearBoth;
		$p .= minHeightPixels(30);
		$p .= $verticalAlignTop;
		$p .= $widthFull;
		$p .= $paddingThick;
		$p .= $fontFooter;
		$css .= wrapStyle('#public-site-footer', $p);

		$p = $positionRelative;
		$p .= $verticalAlignTop;
		$p .= $floatLeft;
		$p .= $width150;
		$p .= $paddingThin;
		$p .= $menuColor;
		$css .= wrapStyle('#public-site-menu', $p);

		$p = $positionRelative;
		$p .= $marginLeftWide;
		$p .= $paddingThick;
		$p .= minHeightPixels(350);
		$p .= $pageColor;
		$css .= wrapStyle('#public-page-container', $p);
		$p = $positionRelative;
		$p .= $paddingThick;
		$p .= $fontHeading;
		$css .= wrapStyle('#public-page-title', $p);
		
		//#public-page-content {position:relative;}
		//#public-page-details {position:relative;}

		$p = $fontHeading;
		$p .= $marginThin;
		$css .= wrapStyle('.public-detail-title', $p);

		$p = $fontNormal;
		$p .= $marginThin;
		$css .= wrapStyle('.public-detail-item', $p);

		$css .= closeTag('style');
		return $css;

	}

	protected function siteStyles(){
		//use internal stylesheet
		$content = $this->buildInternalStyles();

		//use external stylesheet
		$content .= stylesheet('css/publicOverrides.css');
		return $content;
		
	}

	protected function siteScripts(){
		$scripts = openScript();	
		//start oputput buffering
		ob_start();
		//include scripts to buffer
		include('_siteJavascriptFunctions.php');		
		//clear buffer to local variable
		$scripts .= ob_get_clean();  
		$scripts .= closeScript();
		return $scripts;
	}


	protected function siteContainerOpen(){
		$content = doctypeHtml();
		$content .= openTag('html');
		$content .= openTag('head');
		$content .= metaHttpEquivs();
		$content .= $this->siteStyles();
		$content .= wrapTag('title',null,$this->mainTitle);
		//$content .= $this->siteScripts();
		$content .= closeTag('head');
		$content .= openTag('body');
		$content .= openDiv('public-site-container');
		return $content;	
	}

	
	protected function siteContainerClose(){
		//close site page container
		$content = closeDiv();
		$content .= closeTag('body');
		$content .= closeTag('html');
		return $content;
	}
	
	
}
?>
