<?php
namespace html;



/*
function displayLines($value){
	$value = nl2br($value);
	return $value;
}
*/

/*
function br($lines =1){
$b = new _br($lines);
return $b->print();
}
*/

function spacer($spaces = 1){
	$content = '';
	$i = 0;
	while ($i < $spaces){
		$i++;
		$content .= '&nbsp;';
	}
	return $content;
}

/*
function linkSpacer($separator = '|'){
	$spacer = spacer().$separator.spacer();
	return $spacer;
}

//use bold freely in code
//update to span with bold class to deprecate bold
function bold($text){
	$e = new _anyElement('b');
	return $e->print($text);
}

*/

?>
