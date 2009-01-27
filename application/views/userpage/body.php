<?php

if (!$avatar) {
	$avatar = './images/keyhole.gif';
} elseif ($avatar === '(gravatar)') {
	$avatar = 'http://www.gravatar.com/avatar/' . md5($email) . '?s=60&amp;r=g&amp;d=' . urlencode(site_url('images/keyhole_edit.gif'));
} else {
	$avatar = './useravatars/' . $avatar;
}

?>
	<div id="titleblock">
		<h1>
			<span id="title-avatar"><img src="<?php print $avatar ?>" alt="[個人小圖示]" /></span>
			<span id="title-name"><?php print htmlspecialchars($title) ?></span>
			<span id="title-1">推薦您改用</span>
			<span id="title-2">Firefox</span>
			<span id="title-3">看網頁！</span>
		</h1>
		<div class="download">
			<p class="link"><a href="/download">免費下載</a></p>
			<p class="version">3.0 系列最新版</p>
		</div>
		<p class="count"><?php print htmlspecialchars($title) ?>已經推薦<?php print $count ?>人使用了！</p>
		<p class="desc">您的網際生活將因 Firefox 更加豐富有趣！Firefox 有許多特色，協助您完成工作、找到資訊。正因為它如此實用，<?php print htmlspecialchars($title) ?>願意推薦您改用 Firefox！以下是<?php print htmlspecialchars($title) ?>最喜歡 Firefox 的三大特點：</p>
	</div>
	<div id="window_download" class="window" title="正在啟動下載...">
		<h2>感謝您下載 Firefox！</h2>
		<p>下載將在幾秒內開始，如果沒有啟動請按<a href="/download">這裡</a>。</p>
		<p>安裝完畢後，別忘了使用 Firefox 連到：</p>
		<p class="gfx-url"><?php print site_url($name) ?></p>
		<p>依照<?php print htmlspecialchars($title) ?>的建議，加入附加元件改造屬於您的火狐！</p>
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
	<h2>關於<?php print htmlspecialchars($title) ?></h2>
	<ul>
		<li><span class="item">抓火狐網址</span> <a class="gfxurl value" href="<?php print site_url($name); ?>"><?php print site_url($name); ?></a></li>
<?php if ($web)  { ?>
		<li><span class="item">網站</span> <a class="web value" href="<?php print htmlspecialchars($web); ?>"><?php print htmlspecialchars($web); ?></a></li>
<?php } ?>
<?php if ($blog)  { ?>
		<li><span class="item">部落格</span> <a class="blog value" href="<?php print htmlspecialchars($blog); ?>"><?php print htmlspecialchars($blog); ?></a></li>
<?php } ?>
<?php if ($forum_username)  { ?>
		<li><span class="item">MozTW 討論區 ID</span> <a class="forum-username value" href="http://forum.moztw.org/profile.php?mode=viewprofile&amp;u=<?php print htmlspecialchars($forum_id) ?>"><?php print htmlspecialchars($forum_username); ?></a></li>
<?php } ?>
<?php if ($bio)  { ?>
		<li><span class="item">一行自介</span> <span class="bio value"><?php print htmlspecialchars($bio); ?></span></li>
<?php } ?>
	</ul>
	</div>
	<div id="groups-title">
		<h2><?php print htmlspecialchars($title) ?>的火狐屬性</h2>
		<p>火狐帶有強大的擴充功能....（descriptive text on addons for new- and non-fx users）</p>
		<p id="groups-show-detail"><input type="checkbox" id="groups-show-detail-box" /> <label for="groups-show-detail-box">顯示細節與快速安裝</label></p>
	</div>
	<div id="groups">
<?php
/* put it into a function scope */
function addon($addon) {
	extract($addon);
	if (!$icon_url) $icon_url = site_url('images/addon_default_icon.png');
	if (!isset($xpi_url)) $xpi_url = '';
	if (!$url && $amo_id) $url = 'https://addons.mozilla.org/zh-TW/firefox/addon/' . $amo_id;
	elseif (!$url && !$amo_id) return;
?>
		<div class="addon">
<?php
	if (!$xpi_url) { ?>
		<p class="install"><input type="checkbox" value="<?php print htmlspecialchars($xpi_url); ?>" id="addon-install-<?php print $id ?>" /><label for="addon-install-<?php print $id ?>">列入安裝清單</label></p>
<?php
	} else { ?>
		<p class="install"><input type="checkbox" value="<?php print htmlspecialchars($xpi_url); ?>" id="addon-install-<?php print $id ?>" disabled="disabled" /><label title="請至套件網站安裝" for="addon-install-<?php print $id ?>">列入安裝清單</label></p>
<?php 
	}
?>
			<p><a href="<?php print htmlspecialchars($url); ?>"><img src="<? print htmlspecialchars($icon_url) ?>" alt="" /><span><?php print htmlspecialchars($title); ?></span></a></p>
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
		<p>blah!</p>
		<div id="groups-install">
			<p>(install description) </p>
		</div>
	</div>
	</div>