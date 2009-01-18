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
		<div class="downloadframeborder">
			<div class="downloadframe">
				<div class="download">
					<p>免費下載</p>
					<p><?php print htmlspecialchars($title) ?>已經推薦<?php print $count ?>人使用了！</p>
				</div>
			</div>
		</div>
		<p class="desc">您的網際生活將因........///面放為！知利空國看動。者以目該當；聽工龍年影……清實工球能！清像童難喜回下，照獲風時接一！展下停然事漸其歡與態，王親然體分，問象讓它個球作陽的能加球起政活業。大德師但！達是性因，於影通身興師片保原二愛式政由來手紙庭世，獨北見維能本痛半有情當不給福公中！</p>
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
			<p><a href="<?php print site_url('feature/' . $name); ?>">More ...</a></p>
		</div>
<?php
}
foreach ($features as $feature) {
	feature($feature);
}
?>
	</div>
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
		<li><span class="item"><a href="http://forum.moztw.org/">MozTW 討論區</a>ID</span> <span class="forum-username value"><?php print htmlspecialchars($forum_username); ?></span></li>
<?php } ?>
<?php if ($bio)  { ?>
		<li><span class="item">一行自介</span> <span class="bio value"><?php print htmlspecialchars($bio); ?></span></li>
<?php } ?>
	</ul>
	</div>
	<p id="groups-show-detail"><input type="checkbox" id="groups-show-detail-box" /> <label for="groups-show-detail-box">顯示細節與快速安裝</label></p>
	<h2 id="groups-title"><?php print htmlspecialchars($title) ?>的火狐屬性</h2>
	<p>火狐帶有強大的擴充功能....（descriptive text on addons for new- and non-fx users）</p>
	<div id="groups">
<?php
/* put it into a function scope */
function addon($addon) {
	extract($addon);
	if (!isset($icon_url)) $icon_url = '';
	if (!isset($xpi_url)) $xpi_url = '';
	if ($url === '' && $amo_id !== '') $url = 'https://addons.mozilla.org/zh-TW/firefox/addon/' . $amo_id;
	elseif ($url === '' && $amo_id === '') return;
?>
		<div class="addon">
			<p><a href="<?php print htmlspecialchars($url); ?>"><img src="<? print htmlspecialchars($icon_url) ?>" alt="" /><span><?php print htmlspecialchars($title); ?></span></a></p>
<?php
	if (isset($description)) {
?>
			<p class="desc"><?php print htmlspecialchars($description); ?></p>
<?php
	}
?>
			<p class="install"><input type="checkbox" value="<?php print htmlspecialchars($xpi_url); ?>"<?php if (!$xpi_url) print ' disabled="disabled" title="請至套件網站安裝"' ?>/>列入安裝清單</p>
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