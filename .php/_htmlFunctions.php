<?php

class _element{

protected const _none = "NONE";
protected const _lt = "<";
protected const _gt = ">";
protected const _sl = "/";
protected const _eq = "=";
protected const _sq = "'";
protected const _dq = '"';
protected const _sp = ' ';
protected const _emptyString = '';

protected const _htmlCommentOpen = '<!--';
protected const _htmlCommentClose = '-->';

protected $_markup = "";
protected $_tag = "undefined-node";
protected $_attribs = "";


protected $_id  = "";
protected $_name = "";
protected $_css = "";

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
	$this->_tag = self::_emptyString;
	$this->_attribs = self::_emptyString;
	$this->_markup = self::_emptyString;
	
	$this->_id = self::_emptyString;
	$this->_name = self::_emptyString;
	$this->_css = self::_emptyString;
	
}

public function formatAttribute($name, $value = NULL){
	if (!is_null($value)){
		$a = self::_sp.$name.self::_eq.self::_dq.$value.self::_dq;
	} else {
		$a = self::_emptyString;
	}
	return $a;
}

public function addAttribute($name, $value = NULL){
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

protected function setAttributes($attributes = "none"){
	if ($attributes != 'none' && !is_null($attributes)){
		$this->_attribs = $attributes;
	}
}

protected function start(){
	$this->_markup = self::_lt.$this->_tag;
	if ($this->_attribs != 'none' && !is_null($this->_attribs)){
		$this->_markup .= $_attribs;
	}
}

function open(){
	$this->start();
	$this->_markup .= self::_gt;
	return $this->_markup;
}

function empty(){
	$this->start();
	$this->_markup .= self::_sp.self::_sl.self::_gt;
	return $this->_markup;
}

public function close(){
	$this->_markup = self::_lt.$this->_tag.self::_gt;
	return $this->_markup;
}

public function wrap($content = NULL){
	if (is_null($content)){
		$value = $this->empty();

	} else {
		$value = $this->open();
		$value .= $content;
		$value = $this->close();
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

function openTag($tag){
$e = new _element($tag);
return $e->open();
}

function closeTag($tag){
$e = new _element($tag);
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
			$element = parent::_emptyString;
		}
		$element .= parent::close();
		return $element;
	}
}


function openScript($language = 'JavaScript', $useComment = true){
	$e = new _script($language, $useComment);
	return $e->open();
}

function closeScript($useComment = true){
	$e = new _script($useComment);
	return $e->close();
}

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

function displayLines($value){
	$value = nl2br($value);
	return $value;
}

function stylesheet($cssFile){
	$e = new _element('link');
	$e->addAttribute('rel','stylesheet');
	$e->addAttribute('type','text/css');
	$e->addAttribute('href',$cssFile);
	return $e->empty();
	
	//$a = attribute('rel','stylesheet');
	//$a .= attribute('type','text/css');
	//$a .= attribute('href',$styleSheetFile);
	//$value = wrapTag('link',$a);
	//return $value;
}


function span($content, $css = 'none'){
	$e = new _element('span');
	$e->setCSS($css);
	return $e->wrap($content);
}

function spanStyled($content, $style = 'none'){
	$e = new _element('span');
	$e->addAttribute('style', $style);
	return $e->wrap($content);
}

class _div extends _element{
	public function __construct($idName, $css = 'none'){
		parent::__construct1('div', $idName, $css);
	}
}

function openDiv($nameId, $css = 'none'){
	//$e = new _div($nameId, $css);
	$e = new _element('div', $nameId, $css);
	return $e->open();
}

function closeDiv(){
	//$e = new _div();
	$e = new _element('div');
	return $e->close();
}

function wrapDiv($content, $nameId, $css = 'none'){
	//$e = new _div($nameId, $css);
	$e = new _element('div', $nameId, $css);
	return $e->wrap($content);
}

class _table extends _element{
	public function __construct($idName, $css = 'none'){
		parent::__construct1('table', $idName, $css);
	}
}

function openTable($nameId, $css = 'none'){
	//$e = new _table($nameId, $css);
	$e = new _element('table', $nameId, $css);
	return $e->open();
}

function closeTable(){
	//$e = new _table();
	$e = new _element('table');
	return $e->close();
}

class _ul extends _element{
	public function __construct($idName, $css = 'none'){
		parent::__construct1('ul', $idName, $css);
	}
}

class _ol extends _element{
	public function __construct($idName, $css = 'none'){
		parent::__construct1('ol', $idName, $css);
	}
}

function openList($nameId, $css = 'none'){
	//$e = new _ul($nameId, $css);
	$e = new _element('ul',$nameId,$css);
	return $e->open();
}

function closeList(){
	//$e = new _ul();
	$e = new _element('ul');
	return $e->close();
}

function openListOrdered($nameId, $css = 'none'){
	//$e = new _ol($nameId, $css);
	$e = new _element('ol',$nameId,$css);
	return $e->open();
}

function closeListOrdered(){
	//$e = new _ol();
	$e = new _element('ol');
	return $e->close();
}

function listItem($value,$css = 'none'){
	$e = new _element('li');
	$e->setCSS($css);
	return $e->wrap($value);
}

function wrapTh($caption, $css = 'none',$colSpan = 0){
	$e = new element('th');
	$e->setCSS($css);
	if ($colSpan != 0){
		$e->addAttribute('colspan',$colSpan);
	}	
	return $e->wrap($caption);
}

function wrapTd($value, $width = 0, $css='none',$colSpan = 0){
	$e = new element('td');
	$e->setCSS($css);
	if ($width!=0){
		$e->addAttribute('width', $width.'%');
	}
	if ($colSpan != 0){
		$e->addAttribute('colspan',$colSpan);
	}
	return $e->wrap($value);
}

function wrapTr($content, $css = 'none'){
	$e = new _element('tr');
	$e->setCSS($css);
	return $e->wrap($content);
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
	$e = new _element('a');
	$e->setCSS($css);
	$e->addAttribute('href',$url);
	if ($target <> '_self'){
		$e->addAttribute('target',$target);
	}
	if (!is_null($onClickJS)){
		$e->addAttribute('onclick',$onClickJS);
	}
	if (is_null($displayText) or $displayText == ''){
		$content = '...';
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
	$value = span($caption.':',$cssCaption).$content;
	$data = paragraph($value, $id, $cssParagraph);
	return $data;
}

function image($src, $alt, $width = 0, $height = 0, $border = 0, $css = 'none'){
	$e = new _element('img');
	$e->setCSS($css);
	$e->addAttribute('src',$src);
	$e->addAttribute('alt',$alt);
	$e->addAttribute('width',$width);
	$e->addAttribute('height',$height);
	$e->addAttribute('border',$border);
	return $e->emptyTag();
}



















?>
