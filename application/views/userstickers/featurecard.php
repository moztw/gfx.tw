<?php

/*
Remember: sticker html only generates when /editor/save runs
one would have to login and save to get your modifications here.
*/

print '<?xml version="1.0" encoding="UTF-8"?>'
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-TW" lang="zh-TW">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>抓火狐 :: <?php print htmlspecialchars($title) ?>的功能介紹小卡</title>
</head>
<style type="text/css">
html {
	overflow: auto;
}
body {
	width: 200px; border: none;
	margin: 0 auto; padding: 0;
	background-color: #ffffff; color: #000000;
	font-size: 16px;
	overflow: auto;
}
h1, p, a, ol, li {
	font: 1em sans-serif;
	line-height: 1.2em;
	margin: 0; padding: 0;
}
p#logo a {
	display: block;
	background: transparent url('http://stage.gfx.tw/stickerimages/logo-wordmark-195x100.png') center center no-repeat;
	height: 80px; width: 198px; margin: 0 1px;
	text-indent: -10000px;
}
li {
	list-style-position: inside;
	padding: 0 0 0 2.2em;
}
h1 {
	text-align: center;
	font-weight: bold;
	margin: 0.2em 0;
}
p#download a {
	display: block;
	text-align: center;
	border: 2px solid #ccffcc;
	background-color: #ccffcc;
	color: #000000;
	font-weight: bold;
	text-decoration: none;
	outline-width: 0;
	margin: 0.5em 2em;
}
p#download a:hover {
	border: 2px outset #cccccc;
}
p#download a:active {
	border: 2px inset #cccccc;
}

</style>
<body>
<h1><?php print htmlspecialchars($title) ?>推薦你使用</h1>
<p id="logo"><a href="<?php print site_url($name); ?>" onclick="window.open(this.href); return false;">Mozilla Firefox</a></p>
<ol>
<?php

function feature($feature) {
	extract($feature);
?>
	<li><?php print htmlspecialchars($title) ?></li>

<?
}

foreach ($features as $feature) {
	feature($feature);
}
?>
	<li>而且是免費的！</li>
</ol>
<p id="download"><a href="<?php print site_url($name); ?>" onclick="window.open(this.href); return false;">立即下載</a></p>
</body>
</html>