<?php

include_once("_includes.php");

function openDisplayList(
	$entityName, 
	$legend, 
	$pagingLinks, 
	$quickEdit = NULL,
	$contextMenu = NULL){
	
	$list = openDisplayDetails($entityName, $legend,$quickEdit,'List',$contextMenu);
	
	$list .= $pagingLinks;
	$list .= openTable($entityName.'-displayListTable','displayListTable');
	return $list;
}

function closeDisplayList(){
	$list = closeTable();
	$list .= closeDisplayDetails();
	return $list;
}

function openDisplayDetails(
	$entityName, 
	$legend = 'none',
	$quickEdit = NULL,
	$displayMode = 'Details',
	$contextMenu = NULL){
	
	$detail = openDiv($entityName.'-display'.$displayMode,'display'.$displayMode);

	if (!is_null($quickEdit)){
		$detail .= wrapDiv($quickEdit,$entityName.'-quickEdit','quickEdit');
	}
	if (!is_null($contextMenu)){
		$detail .= wrapDiv($contextMenu,$entityName.'-contextMenu','contextMenu');
	}
	$detail .= openFieldset($legend);

	return $detail;
}

function closeDisplayDetails(){
	$detail = closeFieldset();
	$detail .= closeDiv();
	return $detail;
}

function openEditForm($entityName, $legend, $formAction, $contextMenu = NULL){
	$form = openDisplayDetails($entityName,$legend,NULL,'Edit',$contextMenu);
	$form .= openForm($entityName.'-editForm',$formAction,'editForm');
	//$form .= '<pre>';
	$form .= openDiv($entityName.'-fields','editing-fields');
	return $form;
}


function closeEditForm($entity = '', $required = NULL, $optional = NULL, $submit = NULL){

	$form = wrapDivRequired($entity, $required);
		
	$form .= wrapDivOptional($entity,$optional);
		
	$form .= wrapDivSubmit($entity,$submit);

	//close fields div
	$form .= closeDiv();
	
	//close edit form
	
		//$form .= '</pre>';
	$form .= closeForm();
	
	$form .= closeDisplayDetails();
	return $form;
}
function wrapDivRequired($entity,$formRequired){
	$div = openDiv('formRequired','show');
	$div .= $formRequired.closeDiv();
	return $div;
}

function wrapDivOptional($entity,$formOptional){
	$div = openDiv('formOptional','hide');
	$div .= $formOptional.closeDiv();
	return $div;
}

function wrapDivFieldGrouping($entity,$fields,$css = 'fieldGrouping'){
	$div = openDiv($entity.'-'.$css,$css);
	$div .= $fields.closeDiv();
	return $div;
}

function wrapDivSubmit($entity,$formSubmit){
	$div = openDiv('formSubmit','formSubmit');
	$div .= $formSubmit.closeDiv();
	return $div;
}




function openFieldset($legend = 'none',$cssFieldset = 'none',$cssLegend = 'display-caption'){
	$e = new _element('fieldset','none',$cssFieldset);
	$fields = $e->open();
	if ($legend != 'none'){
		$l = new _element('legend','none',$cssLegend);
		$fields .= $l->wrap($legend);
	}
	return $fields;	
}

function closeFieldset(){
	$e = new _element('fieldset');
	return $e->close();
}

function wrapFieldset($content, $legend = 'none', $cssFieldset = 'none',$cssLegend = 'display-caption'){
	$fields = openFieldset($legend,$cssFieldset,$cssLegend);
	$fields .= $content;
	$fields .= closeFieldset();
	return $fields;
}


function getTextArea($inputName, $inputValue, $maxLength = 1000, $rows = 4, $cols = 60,$tooltip = 'none',$disabled = 'false',$css = 'editing-input-textarea'){

	$e = new _element('textarea',$inputName, $css);
	$e->addAttribute('title',$tooltipText);
	$e->addAttribute('maxlength',$maxLength);
	$e->addAttribute('rows',$rows);
	$e->addAttribute('cols',$cols);
	
	if ($disabled != 'false'){
		$e->addAttribute('disabled',$disabled);
	}
	
	$input = $e->open();
	$input .= $inputValue;
	$input .= $e->close();
	return $input;

}

function getTextInput($inputName, $inputValue, $size = 100, $maxLength = 100, $tooltip = 'none', $disabled = 'false',$css = 'editing-input-text'){
	$e = new _element('input',$inputName, $css);
	$e->addAttribute('title',$tooltip);
	$e->addAttribute('maxlength',$maxLength);
	$e->addAttribute('size',$size);
	
	if ($disabled != 'false'){
		$e->addAttribute('disabled',$disabled);
	}

	$e->addAttribute('type','text');
	$e->addAttribute('value',$inputValue);

	return $e->empty();
}

function getPasswordInput($inputName, $size = 10, $maxLength = 50,$tooltip = 'Enter Password'){
	$e = new _element('input',$inputName, 'editing-input-password');
	$e->addAttribute('title',$tooltip);
	$e->addAttribute('maxlength',$maxLength);
	$e->addAttribute('size',$size);
/*
	if ($disabled != 'false'){
		$e->addAttribute('disabled',$disabled);
	}
*/
	$e->addAttribute('type','password');
	
	return $e->empty();

}

function getHiddenInput($inputName, $inputValue){

	$e = new _element('input',$inputName, 'editing-input-hidden');
	$e->addAttribute('type','hidden');
	$e->addAttribute('value',$inputValue);

	$input = $e->empty();
	$input .= $inputName.'['.$inputValue.']';
	
	return $input;

}



function getButton($type,$caption,$name){

	$e = new _element('input',$name, 'editing-button');

	//$e->addAttribute('name',$name);
	$e->addAttribute('type',$type);
	$e->addAttribute('value',$caption);

	return $e->empty();
}

function getSubmitButton($caption = 'Submit',$name = 'submit'){
	$b = getButton('submit', $caption, $name);
	return $b;	
}

function getResetButton($resetCaption = 'Reset'){
	$b = getButton('reset', $resetCaption, 'reset');
	return $b;	
}	
function getSubmitResetButtons($submitCaption = 'Submit',$submitName = 'submit', $resetCaption = 'Reset'){
	$b = getSubmitButton($submitCaption, $submitName);
	$b .= getResetButton($resetCaption, 'reset');
	return $b;	
}
 

function getLoginLogoutButton($submitCaption = 'Login',$submitName='submit-login'){
	$b = getSubmitButton($submitCaption, $submitName);
	return $b;
}

function getSaveChangesResetButtons($submitCaption = 'Save',$submitName = 'submit', $resetCaption = 'Reset'){
	$buttons = getSubmitResetButtons($submitCaption,$submitName, $resetCaption);
	return $buttons;
}
function inputFieldTimestamp($entity, $id, $value, $caption = '',$disabled = 'false'){
	$input = getTimestampSelect($id,$value,$disabled);
	$element = captionedInput($caption,$input);		
	return $element;
}

function inputFieldSelect($entity, $select,$caption){
	$element = captionedInput($caption,$select);
	return $element;
}

function inputFieldText(
	$entity, 
	$value, 
	$id = 'text', 
	$caption = '',
	$size = 50,
	$maxLength = 100,
	$tooltip = NULL,
	$disabled = 'false',
	$css = 'editing-input-text'){

	$input = getTextInput($id, $value, $size,$maxLength,$tooltip,$disabled,$css);
	$element = captionedInput($caption,$input);
	return $element;
}

function inputFieldNumber(
	$entity,
	$value,
	$id = 'numberId',
	$caption = '',
	$tooltip = NULL, 
	$disabled = 'false',
	$css = 'editing-input-number'){
		
	$element = inputFieldText($entity,$value,$id,$caption,4,10,$tooltip,$disabled,$css);
	return $element;

}

function inputFieldName(
	$entity, 
	$value, 
	$id = 'name', 
	$caption = 'Name',
	$tooltip = NULL, 
	$disabled = 'false',
	$css = 'editing-input-name'){

	$element = inputFieldText($entity,$value,$id,$caption,30,100,$tooltip,$disabled,$css);
	return $element;
}
function inputFieldDescription(
	$entity, 
	$value, 
	$id = 'description', 
	$caption = 'Description',
	$tooltip = NULL, 
	$disabled = 'false',
	$css = 'editing-input-description'){

	$element = inputFieldTextArea($entity,$value,$id,$caption,1000,$tooltip,$disabled,$css);
	return $element;
}

function inputFieldUser(
	$entity,
	$value,
	$id = 'user',
	$caption = 'Done By',
	$tooltip = NULL, 
	$disabled = 'false',
	$css = 'editing-input-user'){
		
	$element = inputFieldText($entity,$value,$id,$caption,8,50,$tooltip,$disabled,$css);
	return $element;
	
}

function inputFieldTextArea(
	$entity, 
	$value, 
	$id = 'textArea', 
	$caption = '',
	$maxLength = 1000,
	$tooltip = NULL, 
	$disabled = 'false',
	$css = 'editing-input-textarea'){

	$input = getTextArea($id, $value, $maxLength,4,80,$tooltip,$disabled,$css);
	$element = captionedInput($caption,$input);
	return $element;
}

function inputFieldComments(
	$entity, 
	$value, 
	$id = 'comments', 
	$caption = 'Comments',
	$maxLength = 1000,
	$tooltip = NULL, 
	$disabled = 'false',
	$css = 'editing-input-comments'){


	$element = inputFieldTextArea($entity,$value,$id,$caption,$maxLength,$tooltip,$disabled,$css);
	return $element;
}

function inputFieldNotes(
	$entity, 
	$value, 
	$id = 'notes', 
	$caption = 'Notes',
	$maxLength = 1000,
	$tooltip = NULL, 
	$disabled = 'false',
	$css = 'editing-input-notes'){


	$element = inputFieldTextArea($entity,$value,$id,$caption,$maxLength,$tooltip,$disabled,$css);
	return $element;
}


function inputGroupWebLink(
	$entity,
	$textValue,
	$urlValue,
	$textId = 'linkText',
	$urlId = 'linkUrl',
	$textCaption = 'Link Caption',
	$urlCaption = 'Link Url'){
	
	$css = 'input-webLinkText';
	$webLinkText = inputFieldText($entity,$textValue,$textId,$textCaption,20,50,NULL,'false',$css);
	
	$css = 'input-webLinkURL';
	$webLinkURL = inputFieldText($entity,$urlValue,$urlId,$urlCaption,30,255,NULL,'false',$css);
	
	$linkInfo = wrapDivFieldGrouping($entity,$webLinkText.$webLinkURL,'webLink-fieldGroup');
		
	return $linkInfo;
}

function inputFieldHighlightstyle($entity,$value,$id = 'highlightStyle',$caption = 'Highlight Style',$tooltip = NULL, $disabled = 'false'){
	
		$select = getHighlightStyleSelectList($value,$id);
		$element =inputFieldSelect($entity, $select,$caption);
		return $element;
}



function captionedInput($caption, $input){
	$s = openDiv('captioned-input');
	if ($caption != ''){
		$s .= wrapDiv($caption,'caption');
	}
	$s .= wrapDiv($input,'input');
	$s .= closeDiv();
	return $s;
}


function getSelectOption($optionValue = 0, $optionCaption = '', $selectedValue = 0){


	if ( (!is_null($optionValue)) && (!($optionValue == '') )) {
		$v = $optionValue;
	} else {
		$v = 0;
	}
		
	$e = new _element('option');
	$e->addAttribute('value',$optionValue);
	
	if ($selectedValue == $v){
		$e->addAttribute('selected','selected');
	}
	
	return $e->wrap($optionCaption);
	

}

function getSelectList($selectName, $selectOptions, $tooltip = 'none', $disabled = 'false',$onChangeJS = NULL,$css = 'editing-input-select'){
	
	$e = new _element('select',$selectName, $css);
	$e->addAttribute('title',$tooltip);
	
	if ($disabled != 'false'){
		$e->addAttribute('disabled',$disabled);
	}

	$e->addAttribute('onChange',$onChangeJS);	
	
	$select = $e->open();
	$select .= $selectOptions;
	$select .= $e->close();
	return $select;

	
}

function getSelectYesNo($selectName,$value = 'no', $tooltip = 'none', $disabled = 'false',$onChangeJS = NULL){
	$options = getSelectOption('no','no',$value);
	$options .= getSelectOption('yes','yes',$value);
	$select = getSelectList($selectName,$options,$tooltip,$disabled,$onChangeJS);
	return $select;
}

function getSelectRange($max,$selectedValue,$padding = 0,$start=0){
	$range = '';
	$i = $start;
	while ($i < $max){
		$i++;
		$value = str_pad($i,$padding,'0',STR_PAD_LEFT);
		$option = getSelectOption($value,$value,$selectedValue);
		$range .= $option;
	}
	return $range;
}

function getYearSelect($selectName,$selectedYear,$currentYear,$tooltip = 'Year'){
	$minYear = $currentYear - 7;
	$maxYear = $currentYear + 4;
	$years = getSelectRange($maxYear,$selectedYear,4,$minYear);
	$options = getSelectList($selectName, $years,$tooltip,'false',null,'editing-input-date');	
	return $options;	
}

function getMonthSelect($selectName,$selectedMonth,$tooltip = 'Month'){
	$months = getSelectRange(12,$selectedMonth,2);
	$options = getSelectList($selectName, $months,$tooltip,'false',null,'editing-input-date');		
	return $options;
}

function getDaySelect($selectName,$selectedDay,$tooltip = 'Day'){
	$days = getSelectRange(31,$selectedDay,2);
	$options = getSelectList($selectName, $days,$tooltip,'false',null,'editing-input-date');		
	return $options;
}

function getHourSelect($selectName,$selectedHour,$tooltip = 'Hour'){
	$hours = getSelectRange(23,$selectedHour,2,-1);
	$options = getSelectList($selectName,$hours,$tooltip,'false',null,'editing-input-time');		
	return $options;
}

function getMinuteSelect($selectName,$selectedMinute,$tooltip = 'Minutes'){
	$mins = getSelectRange(59,$selectedMinute,2,-1);
	$options = getSelectList($selectName,$mins,$tooltip,'false',null,'editing-input-time');		
	return $options;
}

function getTimestampPart_Year($timestamp){
	$part = substr($timestamp,0,4);
	return $part;
}

function getTimestampSelect($selectName,$time, $disabled = 'false'){
	//timestamp format 
	//0000-00-00 00:00:00
	//0123456789012345678
	//0000-55-88-11-44-77
	$year = getTimestampPart_Year($time);
	$month = substr($time,5,2);
	$day = substr($time,8,2);
	$hour = substr($time,11,2);
	$min = substr($time,14,2);

	global $sessionTime;
	$currentYear = getTimestampPart_Year($sessionTime);

	$select = getYearSelect($selectName.'_YYYY',$year,$currentYear);
	$select .= getMonthSelect($selectName.'_MM',$month);
	$select .= getDaySelect($selectName.'_DD',$day);
	$select .= getHourSelect($selectName.'_HH',$hour);
	$select .= getMinuteSelect($selectName.'_mm',$min);
	return $select;
}

function getTimestampDate($timestamp){
	$date = substr($timestamp,0,10);
	return $date;	
}

function getTimestampMonth($timestamp,$trim = 'NO'){
	$month = substr($timestamp,5,2);
	if ($trim != 'NO'){
		$month = ltrim($month,'0');
	}
	return $month;	
}

function getTimestampYear($timestamp){
	return getTimestampPart_Year($timestamp);
}

function getTimestampDay($timestamp,$trim = 'NO'){
	$day = substr($timestamp,8,2);
	if ($trim != 'NO'){
		$day = ltrim($day,'0');
	}
	return $day;
}
function getTimestampValues($selectName){
	$year = $_POST[$selectName.'_YYYY'];
	$month = $_POST[$selectName.'_MM'];
	$day = $_POST[$selectName.'_DD'];
	$hour = $_POST[$selectName.'_HH'];
	$min = $_POST[$selectName.'_mm'];
	$time = $year.'-'.$month.'-'.$day.' '.$hour.':'.$min.':00';
	return $time;
} 
function getTimestampPostValues($selectName){
	$year = $_POST[$selectName.'_YYYY'];
	$month = $_POST[$selectName.'_MM'];
	$day = $_POST[$selectName.'_DD'];
	$hour = $_POST[$selectName.'_HH'];
	$min = $_POST[$selectName.'_mm'];
	$time = $year.'-'.$month.'-'.$day.' '.$hour.':'.$min.':00';
	return $time;
} 
?>
