<?php
include_once("_htmlFunctions.php");
include_once("_formFunctions.php");
include_once("_sqlFunctions.php");
//css style format
//.public-detail-item{
//	font-size:14px;
//	font-style:normal;
//	margin:2px;
//}
function wrapStyle($name, $properties){
	$style = $name.'{'.$properties.'}';	
	return $style;
}
function propertySet($property, $setting){
	$value = $property.':'.$setting.';';
	return $value;
}
function propertySetPixels($property, $pixels =0){
	$value = $pixels;
	if ($pixels <> 0){
		$value .= 'px';
	}
	$p = propertySet($property, $value);
	return $p;	
}
function propertySetPercent($property, $percent =0){
	$value = $percent;
	if ($percent <> 0){
		$value .= '%';
	}
	$p = propertySet($property, $value);
	return $p;	
}
function colorBackground($color){
	$p = propertySet('background-color', $color);
	return $p;
}
function colorText($color){
	$p = propertySet('color', $color);
	return $p;	
}
function setMargin($pixels = 0){
	$p = propertySetPixels('margin', $pixels);
	return $p;	
}
function setMarginCustom($side = 'left', $pixels = 0){
	$p = propertySetPixels('margin-'.$side, $pixels);
	return $p;
}
function setPadding($pixels = 0){
	$p = propertySetPixels('padding',  $pixels);
	return $p;
}
function setPaddingCustom($side = 'left', $pixels = 0){
	$p = propertySetPixels('padding-'.$side,  $pixels);
	return $p;
}
function setFontFamily($family = 'Verdana, Arial, Helvetica, sans-serif'){
	$p = propertySet('font-family', $family);
	return $p;
}
function setFont($pixels, $style, $weight){
	$p = propertySetPixels('font-size', $pixels);
	$p .= propertySet('font-style', $style);
	$p .= propertySet('font-weight', $weight);
	return $p;	
}
function textDecoration($type = 'none'){
	$p = propertySet('text-decoration', $type);
	return $p;
}
function textAlign($align = 'left'){
	$p = propertySet('text-align', $align);
	return $p;	
}
function verticalAlign($align = 'top'){
	$p = propertySet('vertical-align', $align);
	return $p;	
}
function heightPixels($pixels){
	$p = propertySetPixels('height',$pixels);
	return $p;
}
function heightPercent($percent){
	$p = propertySetPercent('height', $percent);
	return $p;	
}
function minHeightPixels($pixels){
	$p = propertySetPixels('min-height',$pixels);
	return $p;
}
function widthPixels($pixels){
	$p = propertySetPixels('width',$pixels);
	return $p;	
}
function widthPercent($percent){
	$p = propertySetPercent('width',$percent);
	return $p;	
}
function setPosition($relativeAbsolute = 'relative'){
	$p = propertySet('position', $relativeAbsolute);
	return $p;
}
function setClear($side = 'both'){
	$p = propertySet('clear', $side);
	return $p;
}
function setFloat($side = 'left'){
	$p = propertySet('float', $side);
	return $p;
}
function setDisplay($blockInline = 'block'){
	$p = propertySet('display', $blockInline);
	return $p;
}
function setBorder($style = 'none', $pixels = 0, $color = 'black'){	
	if ($style == 'none'){
		$p = propertySet('border', $style);
	} else {
		$p = propertySet('border-style', $style);
		$p .= propertySetPixels('border-width', $pixels);
		$p .= propertySet('border-color', $color);
	}
	return $p;
}
function clearListStyle(){
	$p = propertySet('list-style-type','none');
	return $p;	
}
function clearMargin(){
	$p = setMargin(0);
	return $p;
}
function clearPadding(){
	$p = setPadding(0);
	return $p;
}
function clearBorder(){
	$p = setBorder('none');
	return $p;
}
function clearMarginPadding(){
	$p = clearMargin();
	$p .= clearPadding();
	return $p;
}
function clearMarginPaddingBorder(){
	$p = clearMarginPadding();
	$p .= clearBorder();
	return $p;	
}


function getHighlightStyleSelectList($selectedValue = 'none', $idName = 'highlightStyle', $disabled = 'false'){
	$sql = " SELECT style_name as value, style_name as caption ";
	$sql .= " FROM css_highlight_styles ";
	if ($disabled == 'true'){
		$sql .= " WHERE style_name = '".$selectedValue."' ";
	}
	$sql .= " ORDER BY style_name ";
	
	$defaultValue = 'none';
	$defaultCaption = '-no highlighting';
	$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
		
	$select = getSelectList($idName,$allOptions,'none',$disabled );
	return $select;
}	

function getColorSelectList($selectedValue = 'none', $idName = 'cssColor', $disabled = 'false'){
	$sql = " SELECT c.name as value, c.name as caption ";
	$sql .= " FROM css_colors AS c ";
	if ($disabled == 'true'){
		$sql .= " WHERE c.name = '".$selectedValue."' ";
	}
	$sql .= " ORDER BY c.name ";

	$defaultValue = 'none';
	$defaultCaption = '-no color selected';
	$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
	if ($selectedValue !== 'none'){
		$sample = spanStyled(spacer(4),colorBackground($selectedValue));	
	} else {
		$sample = spacer(4);
	}
	$select = $sample.getSelectList($idName,$allOptions,'none',$disabled );
	return $select;
}	

function getFontFamilySelectList($selectedValue = 'none', $idName = 'cssFont', $disabled = 'false'){
	$sql = " SELECT c.name as value, c.name as caption ";
	$sql .= " FROM css_fonts AS c ";
	if ($disabled == 'true'){
		$sql .= " WHERE c.name = '".$selectedValue."' ";
	}
	$sql .= " ORDER BY c.name ";

	$defaultValue = 'none';
	$defaultCaption = '-no font selected';
	$allOptions = getSelectOptionsSQL($sql,$selectedValue,$disabled,$defaultValue,$defaultCaption);		
//span with sample adds break displayed in editing table
//setting display to inline doesnt remove it
//see sitewide settings for example of issue
//	if ($selectedValue !== 'none'){
//		$sample = spanStyled('Abc',setFontFamily($selectedValue).setDisplay('inline'));	
//	} else {
//		$sample = spacer(4);
//	}
//	$select = $sample.getSelectList($idName,$allOptions,'none',$disabled );
	$select = getSelectList($idName,$allOptions,'none',$disabled );
	return $select;
}	

function getFontSizeSelectList($selectedValue = 0, $idName = 'cssFontSize', $disabled = 'false'){
	$defaultValue = 0;
	$defaultCaption = '-select font size';
	$allOptions = getSelectOption($defaultValue,$defaultCaption,$selectedValue);
	$allOptions .= getSelectRange(60,$selectedValue,0,10);
	$select = getSelectList($idName,$allOptions,'none',$disabled);
	return $select;
}
?>