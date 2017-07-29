<?php


function sessionVariableGET($urlVariable,$defaultValue){
	if (isset($_GET[$urlVariable])){
		$returnValue = $_GET[$urlVariable];
	} else {
		$returnValue = $defaultValue;
	}
	return $returnValue;
}



function sessionVariablePOST($urlVariable,$defaultValue){
	if (isset($_POST[$urlVariable])){
		$returnValue = $_POST[$urlVariable];
	} else {
		$returnValue = $defaultValue;
	}
	return $returnValue;
}



function sessionVariableSESSION($urlVariable,$defaultValue){
	if (isset($_SESSION[$urlVariable])){
		$returnValue = $_SESSION[$urlVariable];
	} else {
		$returnValue = $defaultValue;
	}
	return $returnValue;
}


class _element
{
protected const _NONE = 'none';
protected const _EMPTY = '';
/*using _empty returns empty attributes 
foirmatted as name="" in html*/
protected const _EMPTYATTRIBUTE = '""';
protected const LT = '<';
protected const GT = '>';
protected const SL = '/';
protected const EQ = '=';
protected const SQ = "'";
protected const DQ = '"';
protected const SP = ' ';
protected const _htmlCommentOpen = '<!--';
protected const _htmlCommentClose = '-->';
protected $_content = '';
protected $_markup = '';
protected $_attribs = '';
protected $_tag = '';

protected $_id = '';
protected $_name = '';
protected $_css = '';
protected $_style = '';
protected $_cData = false;

public function __construct($tag, $idName = 'none', $css = 'none') {
	$this->reset();
	$this->setTag($tag);
	$this->setIdName($idName);
	$this->setCSS($css);
}

public function __destruct() {
     $this->reset();
}
public function setContent($content = null){
	if (!is_null($content)){
		$this->_content .= $content;
	} else {
		$this->_content = self::_EMPTY;
	}
}
public function addContent($content = null){
	if (!is_null($content)){
		$this->_content .= $content;
	}
}
public function reset(){
	$this->_tag = self::_NONE;
	$this->_attribs = self::_EMPTY;
	$this->_markup = self::_EMPTY;
	
	$this->_id = self::_NONE;
	$this->_name = self::_NONE;
	$this->_css = self::_NONE;
	$this->$_cData = false;
}

public function formatAttribute(string $name = 'none',  $value = null){
	if ($name != 'none'){
		if ( !is_null($value)){
			$a = self::SP.$name.self::EQ;
			$a .= self::DQ.$value.self::DQ;
		} else {
			//no attribute value
			$a .= self::DQ.self::DQ;
		}
	} else {
		//no attribute name
		$a = self::_EMPTY;
	}

	return $a;
}

public function addAttribute(string $name = 'none', $value = 'none'){
	$a = $this->formatAttribute($name, $value);
	$this->_attribs .= $a;
}

protected function setTag(string $tag){
	$this->_tag = $tag;
}

public function setIdName(string $idName){
	$this->_id = $idName;
	$this->_name = $idName;
	$this->addAttribute('id', $this->_id);
	$this->addAttribute('name', $this->_name);
}

public function setCSS(string $css){
		$this->_css = $css;
		$this->addAttribute('class', $this->_css);
}

public function setStyle(string $style){
		$this->_style = $style;
		$this->addAttribute('style', $this->_style);
}

public function setCData(bool $isCData = false){
		$this->$_cData = $isCData;
}

protected function start(){
	$this->_markup = self::LT.$this->_tag;
	if ($this->_attribs != 'none' && !is_null($this->_attribs)){
		$this->_markup .= $this->_attribs;
	}
}

function open(){
	$this->start();
	$this->_markup .= self::GT;
	return $this->_markup;
}

function empty(){
	$this->start();
	$this->_markup .= self::SP.self::SL.self::GT;
	return $this->_markup;
}

public function close(){
	$this->_markup = self::LT.self::SL.$this->_tag.self::GT;
	return $this->_markup;
}

public function print(){

		$value = $this->open();
		$value .= $this->_content;
		$value .= $this->close();

	return $value;
}

public function wrap($content = null){
	if (is_null($this->_content)){
		$value = $this->empty();
	} else {
		$value = $this->open();
		$value .= $content;
		$value .= $this->close();
	}
	return $value;
}

function commentOpen(){
	return self::_htmlCommentOpen;
}

function commentClose(){
	return self::_htmlCommentClose;
}


}
//end class _element

function openHTML(){
	$e = new _element('html');
	return $e->open();
}

function closeHTML(){
	$e = new _element('html');
	return $e->close();
}

function openHead(){
	$e = new _element('head');
	return $e->open();
}

function closeHead(){
	$e = new _element('head');
	return $e->close();
}

function htmlTitle($text){
	$e = new _element('title');
	return $e->wrap($text);
}

function openBody($onLoad = 'none'){
	$e = new _element('body');
	$e->addAttribute('onload', $onLoad);
	return $e->open();
}

function closeBody(){
	$e = new _element('body');
	return $e->close();
}

function doctypeHtml(){
	$declare = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">';
	return $declare;	
}
class _meta extends _element{
	public function __construct(string $equiv,string $content){

		parent::__construct('meta');
		$this->addAttribute('http-equiv',$equiv);
		$this->addAttribute('content',$content);
	}
	public function meta(){
		return $this->empty();
	}

}
function meta($equiv, $content){
	$e = new _meta($equiv, $content);
	return $e->meta();
}

function metaHttpEquivs(){
	$metaTags = meta('Content-Style-Type','text/css');
	$metaTags .= meta('content-type','text/html; charset=UTF-8');
	return $metaTags;
}


class _script extends _element{
	protected $useComment = true;

	public function __construct($language = 'JavaScript', $useComment = true){
		parent::__construct('script');
		$this->useComment = $useComment;
		$this->addAttribute('language', $language);
	}
	public function open(){
		$element = parent::open();
		if ($this->useComment == true){
			$element .= $this->commentOpen();
		}
		return $element;
	}
	public function close(){
		if ($this->useComment == true){
			$element = $this->commentClose();
		} else {
			$element = parent::EMPTY;
		}
		$element .= parent::close();
		return $element;
	}
}
class _inlineStyle extends _element{
	public function __construct(){
		parent::__construct('script');
	}
}
function openInlineStyle(){
	$e = new _inlineStyle();
	return $e->open();
}

function closeInlineStyle(){
	$e = new _inlineStyle();
	return $e->close();
}
class _linkExternalCSS extends _element{
	public function __construct($cssFile){
		parent::__construct('link');
		
		$this->addAttribute('rel','stylesheet');
		$this->addAttribute('type','text/css');
		$this->addAttribute('href',$cssFile);
	}
	public function cssLink(){
		return $this->empty();
	}

}

function LinkStylesheet($cssFile){
	$e = new _linkExternalCSS($cssFile);
	return $e->cssLink();
}

function openScript($language = 'JavaScript', $useComment = true){
	$e = new _script($language, $useComment);
	return $e->open();
}

function closeScript($useComment = true){
	$e = new _script($useComment);
	return $e->close();
}




function displayLines($value){
	$value = nl2br($value);
	return $value;
}

function span($content, $css = 'none'){
	$e = new _element('span');
	$e->setCSS($css);
	return $e->wrap($content);
}

function spanStyled($content, $style = 'none'){
	$e = new _element('span');
	$e->setStyle($style);
	return $e->wrap($content);
}

class _div extends _element{
	public function __construct(string $idName = 'none', string $css = 'none',string $style = 'none'){
		parent::__construct('div', $idName, $css);
		$this->setStyle($style);
	}
	public function div(){
		return $this->print();
	}
}

function openDiv($nameId, $css = 'none',$style = 'none'){
	$e = new _div($nameId, $css,$style);
	return $e->open();
}

function closeDiv(){
	$e = new _div();
	return $e->close();
}

function wrapDiv($content, $nameId, $css = 'none',$style = 'none'){
	$e = new _div($nameId, $css,$style);
	$e->setContent($content);
	return $e->div();
}



class _ul extends _element{
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('ul', $idName, $css);
	}
}

class _ol extends _element{
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('ol', $idName, $css);
	}
}
class _li extends _element{
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('li', $idName, $css);
	}
}


function openList($nameId, $css = 'none'){
	$e = new _ul($nameId, $css);
	return $e->open();
}

function closeList(){
	$e = new _ul();
	return $e->close();
}

function openListOrdered($nameId, $css = 'none'){
	$e = new _ol($nameId, $css);
	return $e->open();
}

function closeListOrdered(){
	$e = new _ol();
	return $e->close();
}

function listItem($value,$css = 'none'){
	$e = new _li('none',$css);
	return $e->wrap($value);
}

class _table extends _element{
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('table', $idName, $css);
	}
}


class _th extends _element{
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('th', $idName, $css);
	}
	
	public function setColspan($colspan = 0){
		if ($colSpan != 0){
			$e->addAttribute('colspan',$colSpan);
		}
	}
	
}

class _tr extends _element{
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('tr', $idName, $css);
	}
	public function setWidth($width = 0){
		if ($width!=0){
			$e->addAttribute('width', $width.'%');
		}
	}
	public function setColspan($colspan = 0){
		if ($colSpan != 0){
			$e->addAttribute('colspan',$colSpan);
		}
	}
}

class _td extends _element{
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('td', $idName, $css);
	}
	public function setWidth($width = 0){
		if ($width!=0){
			$this->addAttribute('width', $width.'%');
		}
	}
	public function setColspan($colspan = 0){
		if ($colSpan != 0){
			$this->addAttribute('colspan',$colSpan);
		}
	}
}

function openTable($nameId, $css = 'none'){
	$e = new _table($nameId, $css);
	return $e->open();
}

function closeTable(){
	$e = new _table();
	return $e->close();
}

function wrapTh($caption, $css = 'none',$colSpan = 0){
	$e = new _th('none',$css);
	$e->setColspan($colSpan);
	return $e->wrap($caption);
}

function wrapTr($content, $css = 'none', $colspan = 0){
	$e = new _tr('none',$css);
	$e->setColspan($colSpan);
	return $e->wrap($content);
}

function wrapTd($value, $width = 0, $css='none',$colSpan = 0){
	$e = new _td('none',$css);
	$e->setWidth($width);
	$e->setColspan($colspan);
	return $e->wrap($value);
}

function openTd($idName = 'none', $css = 'none', $width = 0, $colSpan = 0){
	$td = new _td($idName, $css);
	$e->setWidth($width);
	$e->setColspan($colspan);
	return $e->open();
}


function spacer($spaces = 1){
	$content = '';
	$i = 0;
	while ($i < $spaces){
		$i++;
		$content .= '&nbsp;';
	}
	return $content;
}

function linkSpacer($separator = '|'){
	$spacer = spacer().$separator.spacer();
	return $spacer;
}
class _href extends _element{
	protected $displayText = '';

	public function __construct($url, $displayText, $css = 'none',$target = '_self',$onClickJS = NULL){
		parent::__construct('a', 'none', $css);
		$this->addAttribute('href',$url);
		if ($target <> '_self'){
			$this->addAttribute('target',$target);
		}
		if (!is_null($onClickJS)){
			$this->addAttribute('onclick',$onClickJS);
		}
		
		if (is_null($displayText) or ($displayText == '')){
			$content = '[???]';
		} else {
			$content = $displayText;
		}
		$this->displayText = $content;
	}

	public function href(){
		return $this->wrap($this->displayText);
	}
}
function getHref($url, $displayText, $css = 'none',$target = '_self',$onClickJS = NULL){

	$e = new _href($url, $displayText, $css,$target,$onClickJS);
	return $e->href();
}

//use bold freely in code
//update to span with bold class to deprecate bold
function bold($text){
	$e = new _element('b');
	return $e->wrap($text);
}

function br($lines = 1){
	$e = new _element('br');
	$content = '';
	$i = 0;
	while ($i < $lines){
		$i++;
		$content .= $e->empty();
	}
	return $content;
}

function hr(){
	$e = new _element('hr');
	return $e->empty();
}

function paragraph($content, $id, $css = 'none'){
	$e = new _element('p', $id, $css);
	return $e->wrap($content);
}


function captionedParagraph($caption, $content, $cssParagraph = 'none',$cssCaption = 'display-caption'){
return para('', $caption, $content, $cssParagraph = 'none',$cssCaption = 'display-caption');
}

function para( $caption, $content, $cssPara = 'none',$cssCaption = 'display-caption'){
	$value = span($caption.':',$cssCaption).$content;
	$data = paragraph($value, '', $cssPara);
	return $data;
}


class _img extends _element{
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('img', $idName, $css);
	}
	
	public function setSource($src, $alt){
		$e->addAttribute('src',$src);
		$e->addAttribute('alt',$alt);
	}
	public function setDim($width = 0, $height = 0, $border = 0){
		$e->addAttribute('width',$width);
		$e->addAttribute('height',$height);
		$e->addAttribute('border',$border);
	}
}

function image($src, $alt, $width = 0, $height = 0, $border = 0, $css = 'none'){
	$i = new _img('none',$css);
	$i->setSource($src, $alt);
	$i->setDim($width, $height, $border);
	return $i->emptyTag();
}




class _form extends _element{
	public function __construct($action = 'none', $idName = 'none', $css = 'none'){
		parent::__construct('form', $idName, $css);
		$this->addAttribute('enctype', 'multipart/form-data');
		$this->addAttribute('action', $action);
		$this->addAttribute('method', 'post');
	}
}



function openForm($idName, $action, $css = 'none'){
	$e = new _form($action, $idName, $css);
	return $e->open();
}

function closeForm(){
	$e = new _form();
	return $e->close();
}












?>
