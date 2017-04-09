<?php
function doctypeHtml(){
	$declare = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">';
	return $declare;	
}
function metaEquiv($equiv, $content){
	$a = attribute('http-equiv',$equiv);
	$a .= attribute('content',$content);
	$meta = emptyTag('meta', $a);
	return $meta;
}
function metaHttpEquivs(){
	$metaTags = metaEquiv('Content-Style-Type','text/css');
	$metaTags .= metaEquiv('content-type','text/html; charset=UTF-8');
	return $metaTags;
}
function htmlCommentOpen(){
	return '<!--';
}
function htmlCommentClose(){
	return '-->';
}
function openScript($language = 'JavaScript', $htmlComment = true){
	$a = attribute('language', $language);
	$tag = openTag('script', $a);
	if ($htmlComment == true){
		$tag .= htmlCommentOpen();
	}
	return $tag;
}
function closeScript($htmlComment = true){
	$tag = '';
	if ($htmlComment == true){
		$tag .= htmlCommentClose();
	}
	$tag .= closeTag('script');
	return $tag;	
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
//	$value = str_replace(array( "\r", "\n", "%0a", "%0d"), '<br/>', $value);
	return $value;
}

function attribute($name, $value = NULL){
	if (!is_null($value)){
		$a = ' '.$name.'="'.$value.'"';
	} else {
		$a = null;
	}
	return $a;
}

function startTag($element, $attributes = 'none'){
	$tag = '<'.$element;
	if ($attributes != 'none' && !is_null($attributes)){
		$tag .= $attributes;
	}
	return $tag;	
}

function openTag($element, $attributes = 'none'){
	$tag = startTag($element, $attributes);
	$tag .= '>';
	return $tag;
}

function emptyTag($element, $attributes = 'none'){
	$tag = startTag($element, $attributes);
	$tag .= ' />';
	return $tag;
}

function closeTag($element){
	$tag = '</'.$element.'>';
	return $tag;
}

function wrapTag($element, $attributes = 'none', $content = NULL){
	if (is_null($content)){
		$value = emptyTag($element, $attributes);
	} else {
		$value = openTag($element, $attributes);
		$value .= $content;
		$value .= closeTag($element);
	}
	return $value;
}

function stylesheet($styleSheetFile){
	$a = attribute('rel','stylesheet');
	$a .= attribute('type','text/css');
	$a .= attribute('href',$styleSheetFile);
	$value = wrapTag('link',$a);
	return $value;
}

function getClass($cssClass = 'none'){
	if ($cssClass != 'none'){
		$a = attribute('class',$cssClass);
	} else {
		$a = null;
	}
	return $a;
}

function getIdName($name){
	$a = attribute('id',$name);
	$a .= attribute('name',$name);
	return $a;
}

function span($value, $cssClass = 'none'){
	$a = getClass($cssClass);
	$s = openTag('span',$a);
	$s .= $value;
	$s .= closeTag('span');
	return $s;
}

function spanStyled($value, $styleArguments = 'none'){
	$a = attribute('style', $styleArguments);
	$s = openTag('span', $a);
	$s .= $value;
	$s .= closeTag('span');
	return $s;
}

function openDiv($divId, $cssClass = 'none'){
	$a = getIdName($divId);
	$a .= getClass($cssClass);
	$div = openTag('div',$a);
	return $div;
}

function closeDiv(){
	$div = closeTag('div');
	return $div;
}

function wrapDiv($divContent, $divId, $cssClass = 'none'){
	$data = openDiv($divId, $cssClass);
	$data .= $divContent;
	$data .= closeDiv();
	return $data;
}

function openTable($tableId, $cssClass = 'none'){
	$element = 'table';
//	$element = 'div';
	$a = getIdName($tableId);
	$a .= getClass($cssClass);
	$table = openTag($element, $a);
	return $table;
}

function closeTable(){
	$element = 'table';
//	$element = 'div';
	$table = closeTag($element);
	return $table;
}

function openList($listId, $cssClass = 'none'){
	$a = getIdName($listId);
	$a .= getClass($cssClass);
	$list = openTag('ul', $a);
	return $list; 
}

function closeList(){
	$list = closeTag('ul');
	return $list;
}

function openListOrdered($listId, $cssClass = 'none'){
	$a = getIdName($listId);
	$a .= getClass($cssClass);
	$list = openTag('ol', $a);
	return $list; 
}

function closeListOrdered(){
	$list = closeTag('ol');
	return $list;
}

function listItem($value,$cssClass = 'none'){
	$a = getClass($cssClass);
	$item = openTag('li',$a);
	$item .= $value;
	$item .= closeTag('li');
	return $item;
}

function wrapTh($caption, $cssClass = 'none',$colSpan = 0){
	$element = 'th';
//	$element = 'div';
	$a = getClass($cssClass);
	if ($colSpan != 0){
		$a .= attribute('colspan',$colSpan);
	}	
	$h = openTag($element,$a).$caption.closeTag($element);
	return $h;
}

function wrapTd($value, $width = 0, $cssClass='none',$colSpan = 0){
	$element = 'td';
//	$element = 'div';	
	$a = getClass($cssClass);
	if ($width!=0){
		$a .= attribute('width', $width.'%');
	}
	if ($colSpan != 0){
		$a .= attribute('colspan',$colSpan);
	}
	$data = openTag($element, $a);
	$data .= $value;
	$data .= closeTag($element);
	return $data;
}

function wrapTr($value, $cssClass = 'none'){
	$element = 'tr';
//	$element = 'div';	
	$a = getClass($cssClass);
	$data = openTag($element,$a);
	$data .= $value;
	$data .= closeTag($element);
	return $data;
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

function getHref($url, $displayText, $cssClass = 'none',$target = '_self',$onClickJS = NULL){
	$a = attribute('href',$url);
	$a .= getClass($cssClass);
	if ($target <> '_self'){
		$a .= attribute('target',$target);
	}
	if (!is_null($onClickJS)){
		$a .= attribute('onclick',$onClickJS);
	}
	$link = openTag('a', $a);
	if (is_null($displayText) or $displayText == ''){
		$link .= '...';
	} else {
		$link .= $displayText;
	}
	$link .= closeTag('a');
	return $link;
}

//use bold freely in code
//update to span with bold class to deprecate bold
function bold($text){
	return openTag('b').$text.closeTag('b');
}

function br($lines = 1){
	$content = '';
	$i = 0;
	while ($i < $lines){
		$i++;
		$content .= emptyTag('br');
	}
	return $content;
}

function hr(){
	return emptyTag('hr');
}

function paragraph($content, $id, $cssClass = 'none'){
	$a = attribute('id',$id);
	$a .= getClass($cssClass);
	$data = openTag('p', $a);
	$data .= $content;
	$data .= closeTag('p');
	return $data;
}

function captionedParagraph($id, $caption, $content, $cssParagraph = 'none',$cssCaption = 'display-caption'){
	$value = span($caption.':',$cssCaption).$content;
	$data = paragraph($value, $id, $cssParagraph);
	return $data;
}

function image($src, $alt, $width = 0, $height = 0, $border = 0, $cssClass = 'none'){
	$a = attribute('src',$src);
	$a .= attribute('alt',$alt);
	$a .= attribute('width',$width);
	$a .= attribute('height',$height);
	$a .= attribute('border',$border);
	$tag = emptyTag('img',$a);
	return $tag;
}
?>