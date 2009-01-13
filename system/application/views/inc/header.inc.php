<?php
print '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-TW" lang="zh-TW">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title><?php print $header_title ?></title>
	<link rel="stylesheet" type="text/css" href="<?php print base_url() ?>style.css" />
	<script type="text/javascript" src="<?php print site_url('js/mootools-1.2.1-core.js'); ?>"></script>
	<script type="text/javascript" src="<?php print site_url('js/global.js'); ?>" charset="UTF-8"></script>
<?php if (uri_string() === '/editor') { ?>
	<script type="text/javascript" src="./js/editor.js" charset="UTF-8"></script>
	<script type="text/javascript" src="./swfupload/swfupload-min.js" charset="UTF-8"></script>
	<script type="text/javascript" src="./js/mootools-1.2-more.js"></script>
<?php } ?>
</head>
<body>
	<div id="header">
		<p id="header_top_link"><a href="<?php print base_url() ?>">抓火狐</a></p>
<?php
if (isset($auth['id'])) {
?>
		<p id="header_user_functions">Hi, <span id="header_username"><?php print htmlspecialchars($auth['title']) ?></span> (
<?php if (substr($name, 0, 8) !== '__temp__') { ?>
	<a href="<?php print site_url($name) ?>">我的頁面</a> / 
<?php } ?>
<a href="<?php print site_url('editor') ?>">編輯</a> / <a href="#" id="link_logout">登出</a>)</p>
	</div>
	<div id="window_logout" class="window">
		<form action="<?php print site_url('auth/logout'); ?>" method="post">
			<input type="hidden" name="session_id" value="<?php print $auth['session_id'] ?>" />
			<p><input type="submit" value="Logout" /></p>
		</form>
	</div>
<?php } else { ?>
		<p id="header_login"><a href="#" id="link_login">使用 OpenID 登入</a>取得您獨一無二的火狐推薦頁！</p>
	</div>
	<div id="window_login" class="window">
		<p class="close"><a href="#">關閉</a></p>
		<div class="window_content">
			<form action="<?php print site_url('auth/login'); ?>" method="post">
				<p>Login with OpenID: <input type="text" name="openid_identifier" value="" /> <input type="submit" /></p>
			</form>
		</div>
	</div>
<?php
}
?>