<?php
header('Content-Type: text/javascript');
if (isset($jsonpCallback)) {
	if (!preg_match('/^[a-zA-Z0-9_]+$/', $jsonpCallback)) {
		header("HTTP/1.0 400 Bad Request");
	} else {
		print $jsonpCallback;
		print '(';
		print json_encode($jsonObj);
		print ')';
	}
} else {
	print json_encode($jsonObj);
}
