<?php
namespace html;


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

function getButton($type,$caption,$name){
$b = new _button();
	$e = new _element('input',$name, 'editing-button');

	//$e->addAttribute('name',$name);
	$e->addAttribute('type',$type);
	$e->addAttribute('value',$caption);

	return $e->empty();
}

?>
