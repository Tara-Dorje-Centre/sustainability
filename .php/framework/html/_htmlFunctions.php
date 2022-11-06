<?php
namespace html;



/*
function displayLines($value){
	$value = nl2br($value);
	return $value;
}
*/


function br($lines = 1){
$b = new _br($lines);
return $b->print();
}


function hr(){
$h = new _hr();
return $h->print();
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


?>
