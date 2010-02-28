<?
header('Content-Type: text/javascript');
if (isset($jsonpCallback)) {
	print $jsonpCallback;
	print '(';
	print json_encode($jsonObj);
	print ')';
} else {
	print json_encode($jsonObj);
}
