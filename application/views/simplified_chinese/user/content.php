<?php
$this->load->config('gfx');
$this->load->helper('gfx');
$avatar = avatarURL($avatar, $email);

?>
	<div id="titleblock">
		<h1>
			<span id="title-avatar"><img src="<?php print $avatar ?>" alt="[个人小图示]" /></span>
			<span id="title-name"><?php print htmlspecialchars($title) ?></span>
			<span id="title-1">推荐您改用</span>
			<span id="title-2">Firefox</span>
			<span id="title-3">看网页！</span>
		</h1>
		<div class="download">
			<p class="link"><a href="/download">免费下载</a></p>
			<p class="version">3.5 系列最新版</p>
		</div>
		<p class="count"><?php print htmlspecialchars($title) ?>已经推荐<?php print $count ?>人使用了！</p>
		<p class="desc">您的网际生活将因 Firefox 更加丰富有趣！Firefox 有许多特色，协助您完成工作、找到资讯。正因为它如此实用，<?php print htmlspecialchars($title) ?>愿意推荐您改用 Firefox！以下是<?php print htmlspecialchars($title) ?>最喜欢 Firefox 的三大特点：</p>
	</div>
	<div id="window_download" class="window" title="正在启动下载...">
		<h2>感谢您下载 Firefox！</h2>
		<p>下载将在几秒内开始，如果没有启动请按<a href="/download">这里</a>。</p>
		<p>安装完毕后，别忘了使用 Firefox 连到：</p>
		<p class="gfx-url"><?php print site_url($name) ?></p>
		<p>依照<?php print htmlspecialchars($title) ?>的建议，加入附加组件改造属于您的火狐！</p>
	</div>
	<div id="features">
<?php
/* put it into a function scope */
function feature($feature) {
	extract($feature);
?>

		<div class="feature" id="<?php print $name ?>">
			<h2><?php print htmlspecialchars($title) ?></h2>
			<p><?php print htmlspecialchars($description) ?></p>
			<p class="link"><a href="<?php print site_url('feature/' . $name); ?>">More ...</a></p>
		</div>
<?php
}
foreach ($features as $feature) {
	feature($feature);
}
?>
	</div>
	<div id="middleblock">
	<div id="userinfo">
	<h2>关于<?php print htmlspecialchars($title) ?></h2>
	<ul>
		<li><span class="item">抓火狐网址</span> <a class="gfxurl value" href="<?php print site_url($name); ?>"><?php print site_url($name); ?></a></li>
<?php if ($web)  { ?>
		<li><span class="item">网站</span> <a class="web value newwindow" href="<?php print htmlspecialchars($web); ?>" title="<?php print htmlspecialchars($web); ?>"><?php print htmlspecialchars($web); ?></a></li>
<?php } ?>
<?php if ($blog)  { ?>
		<li><span class="item">部落格</span> <a class="blog value newwindow" href="<?php print htmlspecialchars($blog); ?>"  title="<?php print htmlspecialchars($blog); ?>"><?php print htmlspecialchars($blog); ?></a></li>
<?php } ?>
<?php if ($forum_username)  { ?>
		<li><span class="item">MozTW 讨论区 ID</span> <a class="forum-username value newwindow" href="http://forum.moztw.org/memberlist.php?mode=viewprofile&amp;u=<?php print htmlspecialchars($forum_id) ?>"><?php print htmlspecialchars($forum_username); ?></a></li>
<?php } ?>
<?php if ($bio)  { ?>
		<li><span class="item">一行自介</span> <span class="bio value"><?php print htmlspecialchars($bio); ?></span></li>
<?php } ?>
	</ul>
	<p>喜欢<?php print htmlspecialchars($title) ?>推荐的内容？<br /><a href="<?php print site_url($name) ?>" id="push-plurk"><img src="http://www.plurk.com/favicon.ico" alt="[]" /> 扑一下</a> <a href="<?php print site_url($name) ?>" id="push-twitter"><img src="http://twitter.com/favicon.ico" alt="[]" /> 推一下</a>！</p>
	</div>
	<div id="groups-title">
		<h2><?php print htmlspecialchars($title) ?>推荐的附加组件</h2>
		<p>Firefox 浏览器提供使用者上网所需的基本功能；除此之外，全球开发者更设计了各式各样的附加组件，提供使用者自行增加牠的功能。这些附加组件大多与 Firefox 完美结合，让您借由这些有创意的附加组件，自订您专属的「火狐」！</p>
		<p>以下是<?php print htmlspecialchars($title) ?>所推荐的附加组件：</p>
		<p id="groups-show-detail"><input type="checkbox" id="groups-show-detail-box" checked="checked" /> <label for="groups-show-detail-box">显示套件说明</label></p>
	</div>
	<div id="groups" class="detailed">
<?php
/* put it into a function scope */
function addon($addon) {
	$CI =& get_instance();
	extract($addon);
	/* if there is no icon, insert the default one*/
	if (!$icon_url) $icon_url = site_url('images/addon_default_icon.png');
	/* if it's an AMO addon */
	if ($amo_id) {
		$url = $CI->config->item('gfx_amo_url') . $amo_id;
		$xpi_url = $CI->config->item('gfx_amo_xpi_url') . $amo_id;
	} elseif ($available === 'Y' && !$xpi_url) {
		/* not a AMO addon and marked available BUT without an xpi url => don't show */
		return;
	}
?>
		<div class="addon">
<?php
	if ($available === 'Y') { ?>
		<p class="install<?php
		if ($amo_id) print ' amo-addon';
		if ($os_0 === 'Y') print ' os_0';
		if ($os_1 === 'Y') print ' os_1';
		if ($os_2 === 'Y') print ' os_2';
		if ($os_3 === 'Y') print ' os_3';
		if ($os_4 === 'Y') print ' os_4';
		if ($os_5 === 'Y') print ' os_5';
?>">
			<input type="checkbox" value="<?php print htmlspecialchars($xpi_url); ?>" id="install-<?php print $id ?>" /><label for="install-<?php print $id ?>">列入安装清单</label>
		</p>
<?php
	} else { ?>
		<p class="install disabled<?php
		if ($amo_id) print ' amo-addon';
		if ($os_0 === 'Y') print ' os_0';
		if ($os_1 === 'Y') print ' os_1';
		if ($os_2 === 'Y') print ' os_2';
		if ($os_3 === 'Y') print ' os_3';
		if ($os_4 === 'Y') print ' os_4';
		if ($os_5 === 'Y') print ' os_5';
?>">
			<input type="checkbox" disabled="disabled" id="install-<?php print $id ?>" /><label for="install-<?php print $id ?>">请至附加组件网站安装</label>
		</p>
<?php 
	}
?>
			<p><a href="<?php print htmlspecialchars($url); ?>"><img src="<? print htmlspecialchars($icon_url) ?>" alt="" /><span title="<?php print htmlspecialchars($title . ' ' . $amo_version); ?>"><?php print htmlspecialchars($title); ?></span></a></p>
<?php
	if (isset($description)) {
?>
			<p class="desc"><?php print htmlspecialchars($description); ?></p>
<?php
	}
?>
		</div>
<?php
}
function group($group, $addons) {
	extract($group);
?>

		<div class="group" id="<?php print $name ?>">
			<div class="group-title" id="g_<?php print $id ?>">
				<h3><?php print htmlspecialchars($title) ?></h3>
				<p><?php print htmlspecialchars($description) ?></p>
			</div>
<?php
	if ($addons) {
?>
			<div class="group-addons">
<?php
		foreach ($addons as $addon) {
			if ($addon) addon($addon);
		}
?>
			</div>
<?php
	}
?>
		</div>
<?php
}
foreach ($groups as $group) {
	group($group, $addons[$group['id']]);
}
?>
	</div>
	<div id="groups-tail">
		<p>这些附加组件的说明主要来自于 <a href="https://addons.mozilla.org/" class="newwindow">Mozilla 附加组件网站</a>，
由作者提供。</p>
		<div id="groups-install">
			<p><button>立刻安装</button>安装所有勾选的附加组件！</p>
			<p>在 Mozilla 附加组件网站标示为“实验中”，或是安装前需特别同意使用条款、隐私权保护条款的扩充套件无法在此快速安装；请自行前往各扩充套件网页。</p>
			<p>
		</div>
	</div>
	</div>
	<div id="window_extinstall" class="window" title="正在安装...">
		<p><strong>请点选右上角出现的“允许”按钮，允许敝站为您安装附加组件。</strong></p>
		<p>根据您所勾选的元件数量，Firefox 可能需要一些时间确认档案后才会出现“软体安装”通知。</p>
	</div>
