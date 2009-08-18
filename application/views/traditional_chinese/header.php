<?php
if (isset($id)) {
	$this->load->config('gfx');
?>
	<div id="header">
		<p id="header_top_link"><a href="<?php print base_url() ?>" title="回首頁">抓火狐</a></p>
		<ul>
 <?php
	if ($ready === 'Y') {
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
			<p><label for="openid-identifier">選擇您的 OpenID 帳號，或直接輸入網址：</label>
			<input type="text" name="openid-identifier" id="openid-identifier" class="openid-identifier" value="" /> <input type="submit" value="登入" />
			<span id="openid-username">使用者名稱：<input type="text" size="12" /></span>
			<span id="openid-nousername">點選「登入」按鈕開始登入。</span>
			</p>
			<ul id="openid-sp">
				<li><label><input type="radio" name="username" value="" checked="checked" />(直接輸入)</li>
				<li><label><input type="radio" name="username" value="https://www.google.com/accounts/o8/id"/><span class="sprite google"></span>Google</label>
				<li><label><input type="radio" name="username" value="https://me.yahoo.com"/><span class="sprite yahoo"></span>Yahoo!</label>
				<li><label><input type="radio" name="username" value="openid.aol.com/(username)"/><span class="sprite aim"></span>AIM</label>
				<li><label><input type="radio" name="username" value="(username).livejournal.com"/><span class="sprite livejournal"></span>LiveJournal</label>
				<li><label><input type="radio" name="username" value="(username).myid.tw"/><span class="sprite myidtw"></span>myID.tw</label>
				<li><label><input type="radio" name="username" value="(username).myopenid.com"/><span class="sprite myopenid"></span>myOpenID</label>
				<li><label><input type="radio" name="username" value="profile.typekey.com/(username)"/><span class="sprite typepad"></span>TypePad</label>
				<li><label><input type="radio" name="username" value="(username).wordpress.com"/><span class="sprite wordpress-com"></span>WordPress.com</label>
			</ul>
			<h3>OpenID 是什麼？</h3>
			<p>OpenID 讓您使用其他網站的帳號登入敝站，避免再次記憶帳號與輸入資料的困擾。</p>
			<ul>
				<li>若曾在上列網站註冊，請選擇想要使用的 OpenID。</li>
				<li>若沒有任何 OpenID，或是不願意讓敝站帳號與之連結，您可以至 <a href="http://myid.tw/" id="myid" class="newwindow">myID.tw</a> 申請一個屬於您的 OpenID。</li>
				<li>認證的過程<strong>抓火狐網站不會取得您的帳號密碼</strong>，請放心。</li>
				<li>您必須<strong>要分別登出 OpenID 帳號網站與抓火狐網站才能完全清除您的認證。</strong></li>
			</ul>
			<p><a href="/about/faq#forgetopenid">忘記曾用哪個 OpenID 登入嗎？</a></p>
		</form>
	</div>
<?php
}
?>
