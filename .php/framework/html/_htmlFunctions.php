<?php
namespace html;

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


/*

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
	$e = new html/_meta($equiv, $content);
	return $e->meta();
}

function metaHttpEquivs(){
	$metaTags = meta('Content-Style-Type','text/css');
	$metaTags .= meta('content-type','text/html; charset=UTF-8');
	return $metaTags;
}


*/

function openInlineStyle(){
	$e = new _inlineStyle();
	return $e->open();
}

function closeInlineStyle(){
	$e = new _inlineStyle();
	return $e->close();
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




function image($src, $alt, $width = 0, $height = 0, $border = 0, $css = 'none'){
	$i = new _img('none',$css);
	$i->setSource($src, $alt);
	$i->setDim($width, $height, $border);
	return $i->emptyTag();
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
