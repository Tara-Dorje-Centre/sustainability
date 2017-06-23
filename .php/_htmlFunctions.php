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
protected const LT = '<';
protected const GT = '>';
protected const SL = '/';
protected const EQ = '=';
protected const SQ = "'";
protected const DQ = '"';
protected const SP = ' ';
protected const _htmlCommentOpen = '<!--';
protected const _htmlCommentClose = '-->';

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

public function reset(){
	$this->_tag = self::_NONE;
	$this->_attribs = self::_EMPTY;
	$this->_markup = self::_EMPTY;
	
	$this->_id = self::_NONE;
	$this->_name = self::_NONE;
	$this->_css = self::_NONE;
	$this->$_cData = false;
}

public function formatAttribute($name = 'none', $value = null){
	if ($name != 'none' && !is_null($name)){
		if ($value != 'none' && !is_null($value)){
			$a = self::SP.$name.self::EQ.self::DQ.$value.self::DQ;
		} else {
			//no attribute value
			$a = self::_EMPTY;
		}
	} else {
		//no attribute name
		$a = self::_EMPTY;
	}

	return $a;
}

public function addAttribute($name = 'none', $value = 'none'){
	$a = $this->formatAttribute($name, $value);
	$this->_attribs .= $a;
}

protected function setTag($tag){
	$this->_tag = $tag;
}

public function setIdName($idName){
	$this->_id = $idName;
	$this->_name = $idName;
	$this->addAttribute('id', $this->_id);
	$this->addAttribute('name', $this->_name);
}

public function setCSS($css){
		$this->_css = $css;
		$this->addAttribute('class', $this->_css);
}

public function setStyle($style){
		$this->_style = $style;
		$this->addAttribute('style', $this->_style);
}

public function setCData($isCData = false){
		$this->$_cData = $css;
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

public function wrap($content = null){
	if (is_null($content)){
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

function meta($equiv, $content){
	$e = new _element('meta');
	$e->addAttribute('http-equiv',$equiv);
	$e->addAttribute('content',$content);
	return $e->empty();
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

function openInlineStyle(){
	$e = new _element('style');
	return $e->open();
}

function closeInlineStyle(){
	$e = new _element('style');
	return $e->close();
}


function LinkStylesheet($cssFile){
	$e = new _element('link');
	$e->addAttribute('rel','stylesheet');
	$e->addAttribute('type','text/css');
	$e->addAttribute('href',$cssFile);
	return $e->empty();
}

function openScript($language = 'JavaScript', $useComment = true){
	$e = new _script($language, $useComment);
	return $e->open();
}

function closeScript($useComment = true){
	$e = new _script($useComment);
	return $e->close();
}


/*


*/

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
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('div', $idName, $css);
	}
}

function openDiv($nameId, $css = 'none',$style = 'none'){
	$e = new _div($nameId, $css);
	$e->setStyle($style);
	return $e->open();
}

function closeDiv(){
	$e = new _div();
	return $e->close();
}

function wrapDiv($content, $nameId, $css = 'none',$style = 'none'){
	$e = new _div($nameId, $css);
	$e->setStyle($style);
	return $e->wrap($content);
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

function getHref($url, $displayText, $css = 'none',$target = '_self',$onClickJS = NULL){
	$e = new _element('a','none',$css);

	$e->addAttribute('href',$url);
	if ($target <> '_self'){
		$e->addAttribute('target',$target);
	}
	if (!is_null($onClickJS)){
		$e->addAttribute('onclick',$onClickJS);
	}
	if (is_null($displayText) or $displayText == ''){
		$content = '[]';
	} else {
		$content = $displayText;
	}

	return $e->wrap($content);
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


function captionedParagraph($id, $caption, $content, $cssParagraph = 'none',$cssCaption = 'display-caption'){
return para($id, $caption, $content, $cssParagraph = 'none',$cssCaption = 'display-caption');
}

function para($id, $caption, $content, $cssPara = 'none',$cssCaption = 'display-caption'){
	$value = span($caption.':',$cssCaption).$content;
	$data = paragraph($value, $id, $cssPara);
	return $data;
}


class img extends _element{
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
	$i = new img('none',$css);
	$i->setSource($src, $alt);
	$i->setDim($width, $height, $border);
	return $i->emptyTag();
}



















?>
