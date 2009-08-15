<?php
if (isset($id)) {
	$this->load->config('gfx');
?>
	<div id="header">
		<p id="header_top_link"><a href="<?php print base_url() ?>" title="回首页">抓火狐</a></p>
		<ul>
 <?php
	if ($ready === 'Y') {
	/* State 2 header: logged in user with a page */
?>
			<li class="ui-corner-top ui-state-default"><a href="<?php print site_url($name) ?>"><?php print htmlspecialchars($title); ?></a></li>
			<li class="ui-corner-top ui-state-default"><a href="<?php print site_url('editor') ?>">编辑页面</a></li>
			<li class="ui-corner-top ui-state-default"><a href="<?php print site_url('sticker') ?>">宣传贴纸</a></li>
<?php
	} else {
	/* State 1 header: logged in user without a page */
?>
			<li class="ui-corner-top ui-state-default"><a href="<?php print site_url('editor') ?>">编辑页面</a></li>
<?php
	}
?>
			<li class="ui-corner-top ui-state-default ui-state-disabled"><a href="#" id="link-intro">更多...</a></li>
			<li class="ui-corner-all ui-state-default ui-state-disabled"><a href="#" id="link_logout">登出</a></li>
		</ul>
	</div>
<?php
	if ($admin === 'Y') {
?><p id="link_manage" class="ui-state-default ui-corner-all"><a href="#"><span class="ui-icon ui-icon-gear">[*]</span>管理此页</a></p><?php
	}
?>
	<div id="intro-block" class="ui-state-hover ui-corner-all header-block">
		<div class="header-block-content ui-widget-content ui-corner-bottom">
			<p class="header-block-title">抓火狐推荐页，随机发售！</p>
			<div class="random-avatars random-avatars-loading">
			</div>
			<p class="message-link"><a href="/about">关于我们</a> | <a href="/about/legal">使用条款</a> | <a href="/about/faq">常见问题</a></p>
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
		<p id="header_top_link"><a href="<?php print base_url() ?>" title="回首页">抓火狐</a></p>
		<ul>
			<li class="ui-corner-all ui-state-default active ui-state-hover"><a href="#" id="link-newcomer-intro">了解本站</a></li>
			<li class="ui-corner-all ui-state-default ui-state-disabled"><a href="#" id="link_login">使用 OpenID 登入</a></li>
		</ul>
	</div>
	<div id="newcomer-intro" class="ui-widget message show no-auto">
		<div class="ui-widget-content ui-corner-all"> 
			<p><a href="#" class="ui-icon ui-icon-circle-close ui-corner-all">
			</a><span class="ui-corner-all ui-state-default" id="newcomer-intro-login">立即加入，免注册！</span>
“抓火狐”是属于您的 Firefox 推广平台。</p>
			<p class="message-desc" id="visitor-intro">&nbsp;</p>
			<div class="random-avatars random-avatars-loading">
			</div>
			<noscript>
				<div class="random-avatars noscript">
					<iframe src="/user/list/random-avatars-frame" border="0" frameborder="0"></iframe>
				</div>
			</noscript>
			<p class="message-link"><a href="/about">关于我们</a> | <a href="/about/legal">使用条款</a> | <a href="/about/faq">常见问题</a></p>
		</div>
	</div>
	<div id="window_login" class="window" title="登入">
		<form action="<?php print site_url('auth/login'); ?>" method="post">
			<p><label for="openid-identifier">您的 OpenID 网址: </label><input type="text" name="openid-identifier" id="openid-identifier" value="" /> <input type="submit" value="登入" /></p>
			<h3>OpenID 是什么？</h3>
			<p>OpenID 让您使用其他网站的帐号登入敝站，避免再次记忆帐号与输入资料的困扰。若您曾在下列网站注册过，您可以在下方选择想要使用的 OpenID。</p>
			<p><label for="openid_sp">OpenID 服务商：</label><select id="openid_sp">
				<option value="" label="(选择服务商)" selected="selected">(选择服务商)</option>
				<optgroup label="不需修改网址可直接登入">
					<option value="https://www.google.com/accounts/o8/id">Google</option>
					<option value="https://me.yahoo.com">Yahoo!</option>
				</optgroup>
				<optgroup label="需在网址加入帐号">
					<option value="openid.aol.com/[帐号]">AIM</option>
					<option value="[帐号].livejournal.com">LiveJournal</option>
					<option value="[帐号].myid.tw">myID.tw</option>
					<option value="[帐号].myopenid.com">myOpenID</option>
					<option value="profile.typekey.com/[帐号]">TypePad</option>
					<option value="[帐号].wordpress.com">WordPress.com</option>
				</optgroup>
			</select></p>
			<p>若您真的没有任何 OpenID，或是不愿意让敝站帐号与之连结，您可以到 <a href="http://myid.tw/" id="myid" class="newwindow">myID.tw</a> 申请一个属于您的 OpenID。</p>
			<p><strong>注意：</strong>您必须要分别登出服务商网站与抓火狐网站才能完全清除您的认证。</p>
			<p><a href="/about/faq#forgetopenid">忘记使用过的 OpenID 吗？</a></p>
		</form>
	</div>
<?php
}
?>