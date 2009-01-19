<body>
	<div id="header">
		<p id="header_top_link"><a href="<?php print base_url() ?>">抓火狐</a></p>
<?php
if (isset($id)) {
?>
		<p id="header_user_functions">Hi, <span id="header_username"><?php print htmlspecialchars($title) ?></span>
		(<?php if (substr($name, 0, 8) !== '__temp__') { ?><a href="<?php print site_url($name) ?>">我的頁面</a> / <?php } ?><a href="<?php print site_url('editor') ?>">編輯</a> / <a href="#" id="link_logout">登出</a>)</p>
	</div>
	<form id="logout_form" action="<?php print site_url('auth/logout'); ?>" method="post">
		<input type="hidden" id="token" name="token" value="<?php print md5($id . '--secret-token-good-day-fx') ?>" />
		<p><input type="submit" value="登出" /></p>
	</form>
<?php } else { ?>
		<p id="header_login"><a href="#" id="link_login">使用 OpenID 登入</a>取得您獨一無二的火狐推薦頁！</p>
	</div>
	<div id="window_login" class="window" title="登入">
		<form action="<?php print site_url('auth/login'); ?>" method="post">
			<p>OpenID: <input type="text" name="openid_identifier" value="" /> <input type="submit" value="登入" /></p>
			<h3>What is OpenID?</h3>
			<p>(Some description about openid, maybe helper control for famous BSP openids.)</p>
			<p>(if your .... you can get one at <a href="http://myid.tw/">myID.tw</a>.</p>
		</form>
	</div>
<?php
}
?>