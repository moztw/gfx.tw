<?php

$this->load->config('gfx');
$this->load->helper('gfx');
$avatar = avatarURL($avatar, $email);

?>
	<div id="editor-save">
		<p><button id="editor-save-button">保存您的页面</button>编辑完毕，保存变更、领取宣传贴纸！</p>
	</div>
	<div id="window_almostdone" class="window" title="快完成了...">
		<p>请设定您的推荐网页的专用网址，需使用英数字：</p>
		<p><?php print base_url() . form_input(array('id' =>'name', 'value' => '')); ?></p>
	</div>
	<div id="window_editcomplete" class="window" title="完成！">
		<p>感谢您向大家推荐 Mozilla Firefox，接下来...</p>
		<ul>
			<li>看看<a class="userpage-url" href="#">您的个人推荐网页</a></li>
			<li>领取<a href="./sticker">宣传贴纸</a>，在部落格、论坛宣传！</li>
			 <li>噗到<a href="#" class="userpage-url" id="push-plurk-mine"><img src="http://www.plurk.com/favicon.ico" alt=" "/>噗浪</a>、推到<a href="#" class="userpage-url" id="push-twitter-mine"><img src="http://twitter.com/favicon.ico" alt=" " />推特</a>！</li>
		</ul>
	</div>
	<div id="titleblock">
		<form id="title-name-form" action="#">
			<h1>
				<input type="text" id="title-avatar-textarea" />
				<span id="title-avatar" class="editable" title="选择个人图示"><img src="<?php print $avatar ?>" alt="[個人小圖示]" /></span>
				<span id="title-name" class="editable"><?php print htmlspecialchars($title) ?></span>
				<span id="title-name-edit"><?php print form_input('title', $title); ?></span>
				<span id="title-1">推荐您改用</span>
				<span id="title-2">Firefox</span>
				<span id="title-3">看网页！</span>				
			</h1>
		</form>
		<div class="download">
			<p class="link"><a href="/download">免费下载</a></p>
			<p class="version">3.5 系列最新版</p>
		</div>
		<p class="count">{您的推荐指数会在这里出现}</p>
		<p class="desc">您的网际生活将因 Firefox 更加丰富有趣！Firefox 有许多特色，协助您完成工作、找到资讯。正因为它如此实用，<span class="title-placeholder">{您的名字}</span>愿意推荐您改用 Firefox！以下是<span class="title-placeholder">{您的名字}</span>最喜欢 Firefox 的三大特点：</p>
	</div>
	<div id="window_avatar" class="window" title="选择个人图示">
		<div class="avatar_selection">
			<div id="avatar_swfupload" class="avatar_icon">
				<div id="avatar_swfupload_replace">&nbsp;</div>
			</div>
			<p>上传图片档案 <strong class="flash-desc">(请安装最新版 <a href="http://www.adobe.com/flashplayer" class="newwindow">Flash Player</a>)</strong></p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_gravatar" class="avatar_icon">
				<img src="<? print 'http://www.gravatar.com/avatar/' . md5($email) . '?s=60&amp;r=g&amp;d=identicon'; ?>" alt="Gravatar" />				
			</div>
			<p><a href="http://www.gravatar.com/" class="newwindow">Gravatar</a>上的图示</p>
			<p>(<a href="#" id="change-email">修改 E-mail</a>)</p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_default" class="avatar_icon">
				<img src="./images/avatar-default.gif" alt="预设图示" />
			</div>
			<p>预设图示</p>
		</div>
	</div>
	<div id="window_download" class="window" title="正在启动下载...">
		<h2>感谢您下载 Firefox！</h2>
		<p>下载将在几秒内开始，如果没有启动请按<a href="/download">这里</a>。</p>
		<p>安装完毕后，别忘了使用 Firefox 连到：</p>
		<p class="gfx-url"><?php print site_url('{您的推荐网址}') ?></p>
		<p>依照<span class="title-placeholder">{您的名字}</span>的建议，加入附加组件改造属于您的火狐！</p>
	</div>
	<div id="featureselection">
		<p class="features-desc">请将想要推荐的功能拖放到您想要显示的位置。</p>
		<ul>
<?php
/* put it into a function scope */
function featureselection($feature) {
	extract($feature);
?>
			<li id="fs-<?php print $name; ?>"<?php if (isset($user_order)) print ' class="selected"';?> rel="fid-<?php print $id; ?>" title="<?php print htmlspecialchars($description); ?>"><?php print htmlspecialchars($title); ?></li>
<?php
}
$features = array();
foreach ($allfeatures as $feature) {
	featureselection($feature);
	if (isset($feature['user_order'])) {
		$features[$feature['user_order']] = $feature;
	}
}
?>
		</ul>
		<p id="featureselection-clear"><button>全部重来</button></p>
	</div>
	<div id="features" class="sortable">
<?php
/* put it into a function scope */
function feature($feature) {
	extract($feature);
?>

		<div class="feature" id="<?php print $name ?>">
			<h2 id="featureid-<?php print $id ?>"><?php print htmlspecialchars($title) ?></h2>
			<p><?php print htmlspecialchars($description) ?></p>
			<p class="link"><a href="<?php print site_url('feature/' . $name); ?>">More ...</a></p>
		</div>
<?php
}
for ($i = 0; $i < 3; $i++) {
	if (isset($features[$i])) feature($features[$i]);
	else {
?>
		<div class="feature box">
			<h2>&nbsp;</h2>
			<p>&nbsp;</p>
			<p class="link"><a href="#">More ...</a></p>
		</div>
<?php
	}
}
?>
	</div>
	<div id="middleblock">
	<div id="userinfo">
		<h2>关于<span class="title-placeholder">{您的名字}</span></h2>
		<p>您的个人介绍会出现在此处。<button>编辑</button></p>
	</div>
	<div id="window_info" class="window" title="编辑个人介绍">
		<form id="info_form" action="#">
			<p><label for="info_name">推荐页网址：</label> <span class="form-prepend"><?php print base_url() ?></span><?php print form_input(array('id' =>'info_name', 'value' => $name)); ?>
			<span class="form-desc">您的推荐网页的专用网址，需使用英数字。</span></p>
			<p><label for="info_email">E-mail：</label> <?php print form_input(array('id' =>'info_email', 'value' => $email)); ?>
			<span class="form-desc">不会公开；Gravatar 的图示要在重新载入后才会变更。</span></p>
			<p><label for="info_web">个人首页：</label> <?php print form_input(array('id' =>'info_web', 'value' => $web)); ?></p>
			<p><label for="info_blog">部落格：</label> <?php print form_input(array('id' =>'info_blog', 'value' => $blog)); ?></p>
			<p><label for="info_password">讨论区 ID 认证：</label> <?php print form_password(array('id' =>'info_forum', 'value' => ($forum_id && $forum_username)?'(keep-the-forum-username)':'')); ?>
			<span class="form-desc"><a href="http://forum.moztw.org/gfxcode.php" id="forum_auth">按此处</a>取得认证码；不想显示请清除认证码。</span>
			<span class="form-desc" id="forum_auth_iframe">&nbsp;</span></p>
			<p><label for="info_bio">一行自介：</label>
				<textarea id="info_bio"><?php print htmlspecialchars($bio) ?></textarea>
			</p>
			<p><a href="#" id="info_delete_account">删除我的帐号</a></p>
		</form>
	</div>
	<div id="window_delete" class="window" title="删除帐号">
		<form id="delete_post" action="/user/delete" method="post">
			<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
		</form>
		<p>这会从系统中删除您所有个人资讯，包括自订的套件列表、图片、下载次数纪录等等。</p>
		<p id="delete-url-notice">另外，您的推荐页网址 (<?php print base_url(); ?><span class="name-placeholder"><?php print $name ?></span>/) 也将取消，其他人从此可以选用 <span class="name-placeholder"><?php print $name ?></span> 作为他的网址。</p>
		<p>这个动作无法复原。若确定要删除您的帐号，请按下面的按钮。</p>
	</div>
	<div id="groups-title">
		<h2><span class="title-placeholder">{您的名字}</span>推荐的附加组件</h2>
		<p>请在下方选择符合您想要推荐的附加组件类别，并为其加入您推荐的附加组件：</p>
	</div>
	<div id="groups" class="sortable">
<?php
/* put it into a function scope */
function addon($addon) {
	extract($addon);
	if (!$icon_url) $icon_url = site_url('images/addon_default_icon.png');
	if (!isset($xpi_url)) $xpi_url = '';
	if (!$url && $amo_id) $url = 'https://addons.mozilla.org/zh-TW/firefox/addon/' . $amo_id;
	elseif (!$url && !$amo_id) return;
?>
		<div class="addon" id="a_<?php print $id ?>">
			<p class="del-addon ui-icon ui-icon-close" title="删除">删除</p>
			<p><a href="<?php print htmlspecialchars($url); ?>"><img src="<? print htmlspecialchars($icon_url) ?>" alt="" /><span><?php print htmlspecialchars($title); ?></span></a></p>
		</div>
<?php
}
function group($group, $addons) {
	extract($group);
?>

		<div class="group" id="<?php print $name ?>">
			<div class="group-title<?php print (isset($user_id))?'':' not-selected'; ?>" id="g_<?php print $id ?>">
				<input type="checkbox" <?php print (isset($user_id))?'checked="checked"':''; ?>/>
				<h3><?php print htmlspecialchars($title) ?></h3>
				<p class="group-add-addon"><a href="#" title="在此属性下新增附加组件"><span class="ui-icon ui-icon-circle-plus">&nbsp;</span>新增元件</a></p>
				<p><?php print htmlspecialchars($description) ?></p>
			</div>
			<div class="group-addons">
<?php
		foreach ($addons as $addon) {
			if ($addon) addon($addon);
		}
?>
			</div>
		</div>
<?php
}
foreach ($allgroups as $group) {
	group($group, $addons[$group['id']]);
}
?>
	</div>
	<div id="groups-tail">
			<p>直接从页面安装附加组件的说明会出现在这里。</p>
	</div>
	<div id="window_addons" class="window" title="新增附加组件">
		<form action="#" id="addon_query_form">
			<p>搜寻: <?php print form_input(array('id' =>'addon_query', 'value' => '')); ?> <button type="submit">寻找附加组件</button></p>
		</form>
		<p id="addon_query_desc">&nbsp;</p>
		<p id="addon_query_notfound">没有找到任何附加组件 ，可能是因为该附加组件从未被推荐过。您可以在上框中贴入其在 <a href="https://addons.mozilla.org/" class="newwindow">Firefox 附加组件</a>网站中的地址直接推荐。</p>
		<div id="addon_query_result" class="detailed">&nbsp;</div>
	</div>
	<div id="window_progress" class="window" title="与伺服器通讯中...">
		<img src="images/ajax-progress.gif" alt="处理中..." />
	</div>
	</div>