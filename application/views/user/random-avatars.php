<?php
/*

random avatars iframe
intend for <noscript> visitors (including screenshot bots)

*/

print '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-TW" lang="zh-TW">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>抓火狐 :: 推薦頁隨機發售</title>
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style/global.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style/language-zh-TW.css') ?>" />
	<meta name="robots" content="noindex, noarchive" /><!-- Don't index frame page coz it would create dup. -->
</head>
<body class="random-avatars inframe">
<?php
function avatar($user) {
	extract($user);
?>
<p>
	<a href="<?php print site_url($name);?>" target="_top"><img src="<?php print htmlspecialchars($avatar); ?>" alt="<?php print htmlspecialchars($title); ?>" /><span><?php print htmlspecialchars($title); ?></span></a>
</p>
<?php
}
foreach ($users as $user) {
	avatar($user);
}
?>
</body>
</html>
