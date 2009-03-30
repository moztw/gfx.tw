<?php
if (isset($id)) {
	$this->load->config('gfx');
?>
	<div id="header">
		<p id="header_top_link"><a href="<?php print base_url() ?>" title="回首頁">抓火狐</a></p>
		<ul>
 <?php
	if ($name && substr($name, 0, 8) !== '__temp__') {
	/* State 2 header: logged in user with a page */
?>
			<li class="ui-corner-top ui-state-default"><a href="<?php print site_url($name) ?>"><?php print htmlspecialchars($title); ?></a></li>
			<li class="ui-corner-top ui-state-default"><a href="<?php print site_url('editor') ?>">編輯頁面</a></li>
			<li class="ui-corner-top ui-state-default"><a href="<?php print site_url('sticker') ?>">宣傳貼紙</a></li>
<?php
	} else {
	/* State 1 header: logged in user without a page */
?>
			<li class="ui-corner-top ui-state-default"><a href="<?php print site_url('editor') ?>">編輯頁面</a></li>
<?php
	}
?>
			<li class="ui-corner-top ui-state-default ui-state-disabled"><a href="#" id="link-intro">更多...</a></li>
			<li class="ui-corner-all ui-state-default ui-state-disabled"><a href="#" id="link_logout">登出</a></li>
		</ul>
	</div>
<?php
	if ($admin === 'Y') {
?><p id="link_manage" class="ui-state-default ui-corner-all"><a href="#"><span class="ui-icon ui-icon-gear">[*]</span>管理此頁</a></p><?php
	}
?>
	<div id="intro-block" class="ui-state-hover ui-corner-all header-block">
		<div class="header-block-content ui-widget-content ui-corner-bottom">
			<p class="header-block-title">抓火狐推薦頁，隨機發售！</p>
			<div class="random-avatars random-avatars-loading">
			</div>
			<p class="message-link"><a href="/about">關於我們</a> | <a href="/about/legal">使用條款</a> | <a href="/about/faq">常見問題</a></p>
		</div>
	</div>
	<form id="logout_form" action="<?php print site_url('auth/logout'); ?>" method="post">
		<input type="hidden" id="token" name="token" value="<?php print md5($id . $this->config->item('gfx_token')) ?>" />
		<p><input type="submit" value="登出" /></p>
	</form>
<?php } else {
	/* State 0 header: visiter (not logged in)= */
?>
	<div id="header" class="no-margin">
		<p id="header_top_link"><a href="<?php print base_url() ?>" title="回首頁">抓火狐</a></p>
		<ul>
			<li class="ui-corner-all ui-state-default active ui-state-hover"><a href="#" id="link-newcomer-intro">了解本站</a></li>
			<li class="ui-corner-all ui-state-default ui-state-disabled"><a href="#" id="link_login">使用 OpenID 登入</a></li>
		</ul>
	</div>
	<div id="newcomer-intro" class="ui-widget message show no-auto">
		<div class="ui-widget-content ui-corner-all"> 
			<p><a href="#" class="ui-icon ui-icon-circle-close ui-corner-all">
			</a><span class="ui-corner-all ui-state-default" id="newcomer-intro-login">立即加入，免註冊！</span>
「抓火狐」是屬於您的 Firefox 推廣平台。</p>
			<p class="message-desc" id="visitor-intro">&nbsp;</p>
			<div class="random-avatars random-avatars-loading">
			</div>
			<noscript>
				<div class="random-avatars noscript">
					<iframe src="/user/list/random-avatars-frame" border="0" frameborder="0"></iframe>
				</div>
			</noscript>
			<p class="message-link"><a href="/about">關於我們</a> | <a href="/about/legal">使用條款</a> | <a href="/about/faq">常見問題</a></p>
		</div>
	</div>
	<div id="window_login" class="window" title="登入">
		<form action="<?php print site_url('auth/login'); ?>" method="post">
			<p><label for="openid-identifier">您的 OpenID 網址: </label><input type="text" name="openid-identifier" id="openid-identifier" value="" /> <input type="submit" value="登入" /></p>
			<h3>OpenID 是什麼？</h3>
			<p>OpenID 讓您使用其他網站的帳號登入敝站，避免再次記憶帳號與輸入資料的困擾。若您曾在下列網站註冊過，您可以在下方選擇想要使用的 OpenID。</p>
			<p><label for="openid_sp">OpenID 服務商：</label><select id="openid_sp">
				<option value="" label="(選擇服務商)" selected="selected">(選擇服務商)</option>
				<optgroup label="不需修改網址可直接登入">
					<option value="https://www.google.com/accounts/o8/id">Google</option>
					<option value="https://me.yahoo.com">Yahoo!</option>
				</optgroup>
				<optgroup label="需在網址加入帳號">
					<option value="openid.aol.com/[帳號]">AIM</option>
					<option value="[帳號].livejournal.com">LiveJournal</option>
					<option value="[帳號].myid.tw">myID.tw</option>
					<option value="[帳號].myopenid.com">myOpenID</option>
					<option value="profile.typekey.com/[帳號]">TypePad</option>
					<option value="[帳號].wordpress.com">WordPress.com</option>
				</optgroup>
			</select></p>
			<p>若您真的沒有任何 OpenID，或是不願意讓敝站帳號與之連結，您可以到 <a href="http://myid.tw/" class="newwindow">myID.tw</a> 申請一個屬於您的 OpenID。</p>
			<p><strong>注意：</strong>您必須要分別登出服務商網站與抓火狐網站才能完全清除您的認證。</p>
			<p><a href="/about/faq#forgetopenid">忘記使用過的 OpenID 嗎？</a></p>
		</form>
	</div>
<?php
}
?>