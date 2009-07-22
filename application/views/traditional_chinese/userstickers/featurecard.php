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
	border: none;
	margin: 0; padding: 0;
}
#box {
	width: 200px; height: 250px; 
	margin: 0 auto; padding: 0;
	background: #ffffff url('<?php print site_url('/stickerimages/featurecard-bg.png'); ?>') top center no-repeat; color: #000000;
	font-size: 16px;
	overflow: auto;
	position: relative;
}
.hide {
	text-indent: -10000px;
}
h1, p, a, ol, li {
	font: 1em sans-serif;
	line-height: 1.2em;
	margin: 0; padding: 0;
}
ol {
	list-style: none;
	position: absolute;
	top: 75px; left: 52px;
	width: 148px; height: 88px;
}
li {
	list-style: none;
	list-style-position: inside;
	display: block;
	height: 22px; overflow: hidden;
}
#download {
	display: block;
	position: absolute;
	left: 23px; top: 172px;
	width: 154px; height: 56px;
	color: #000000; text-decoration: none;
}
#name {
	display: block;
	position: absolute;
	left: 4px; top: 8px; height: 24px;
	font-weight: bold; font-size: 1.2em;
}
#tell {
	display: block;
	position: absolute;
	left: 4px; top: 32px;
	font-size: 0.82em;
}

</style>
<body>
<div id="box">
	<p class="hide">上網就用</p>
	<h1 class="hide">Firefox</h1>
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
	<p>
		<a id="download" href="<?php print site_url($name); ?>" onclick="window.open(this.href); return false;">
			<span id="name"><?php print htmlspecialchars($title) ?></span>
			<span id="tell">告訴你為什麼</span>
		</a>
	</p>
</div>
</body>
</html>
