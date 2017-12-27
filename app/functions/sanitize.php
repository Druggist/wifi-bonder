<?php
function escape($string) {
	$text = htmlentities($string, ENT_NOQUOTES | ENT_IGNORE, 'UTF-8');
	$text = str_replace('&oacute;', 'ó', $text);
	$text = str_replace('&Oacute;', 'Ó', $text);    
	return $text;
}

function escape_quota($string){
	$text = htmlentities($string, ENT_QUOTES | ENT_IGNORE, 'UTF-8');
	return $text;
}
?>