<body>
	<div id="header">
		<p id="header_top_link"><a href="<?php print base_url() ?>">抓火狐</a></p>
<?php
if (isset($id)) {
?>
		<p id="header_user_functions">Hi, <span id="header_username"><?php if ($title !== '') print htmlspecialchars($title); else print htmlspecialchars($login); ?></span>
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
			<p><label for="openid-identifier">您的 OpenID 網址: </label><input type="text" name="openid-identifier" id="openid-identifier" value="" /> <input type="submit" value="登入" /></p>
			<h3>OpenID 是什麼？</h3>
			<p>OpenID 讓您使用其他網站的帳號登入敝站，避免再次記憶帳號與輸入資料的困擾。若您曾在下列網站註冊過，您可以選擇想要使用的 OpenID：</p>
			<p><label for="openid_sp">OpenID 服務商: </label><select id="openid_sp">
				<option value="" label="(選擇服務商)" selected="selected">(選擇服務商)</option>
				<optgroup label="不需修改網址可直接登入">
					<option value="https://www.google.com/accounts/o8/id">Google</option>
					<option value="https://me.yahoo.com">Yahoo!</option>
				</optgroup>
				<optgroup label="需在網址加入帳號">
					<option value="openid.aol.com/[帳號]">AIM</option>
					<option value="[帳號].livejournal.com">LiveJournal</option>
					<option value="[帳號].myid.tw">myID.tw</option>
					<option value="profile.typekey.com/[帳號]">TypePad</option>
					<option value="[帳號].wordpress.com">WordPress.com</option>
				</optgroup>
			</select></p>
			<p>若您真的沒有任何 OpenID，或是不願意讓敝站帳號與之連結，您可以到 <a href="http://myid.tw/" class="newwindow">myID.tw</a> 申請一個屬於您的 OpenID。</p>
		</form>
	</div>
<?php
}
?>
