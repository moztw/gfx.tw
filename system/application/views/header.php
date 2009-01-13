<body>
	<div id="header">
		<p id="header_top_link"><a href="<?php print base_url() ?>">抓火狐</a></p>
<?php
if (isset($id)) {
?>
		<p id="header_user_functions">Hi, <span id="header_username"><?php print htmlspecialchars($title) ?></span> (
<?php if (substr($name, 0, 8) !== '__temp__') { ?>
	<a href="<?php print site_url($name) ?>">我的頁面</a> / 
<?php } ?>
<a href="<?php print site_url('editor') ?>">編輯</a> / <a href="#" id="link_logout">登出</a>)</p>
	</div>
	<div id="window_logout" class="window">
		<form action="<?php print site_url('auth/logout'); ?>" method="post">
			<input type="hidden" name="token" value="<?php print md5($id . '--check--logout') ?>" />
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