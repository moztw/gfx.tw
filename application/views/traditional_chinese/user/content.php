<?php
$this->load->config('gfx');
$this->load->helper('gfx');
$avatar = avatarURL($avatar, $email);

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
			<p class="version">3.5 系列最新版</p>
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
	<div id="floatblock">
		<p title="和<?php print htmlspecialchars($title) ?>一起推薦 Firefox！">分享</p>
		<ul>
			<li><a class="newwindow" title="分享到 Facebook" href="http://www.facebook.com/sharer.php?u=<?php
/* Facebook fetches sticker image and description from <head> */
print urlencode(site_url($name));
?>"><span class="sprite facebook"></span>臉書</a></li>
			<li><a class="newwindow" title="噗到 Plurk" href="http://plurk.com/?status=<?php 
print urlencode('推薦您和' . htmlspecialchars($title) . '一起抓火狐，使用 Firefox 逛網頁！ ' 
	. site_url(
		'/userstickers/' . dechex(intval($id) >> 12) . '/' . dechex(intval($id & (pow(2,12)-1)))
	)
	. '/featurecard.png '
	. site_url($name)
);
?>"><span class="sprite plurk"></span>噗浪</a></li>
			<li><a class="newwindow" title="推到 Twitter" href="http://twitter.com/home/?status=<?php
print urlencode('推薦您和' . htmlspecialchars($title) . '一起抓火狐，使用 Firefox 逛網頁！ ' . site_url($name));
?>"><span class="sprite twitter"></span>推特</a></li>
			<li><a class="newwindow" title="推薦到 Funp" href="http://funp.com/push/submit/?via=tools&amp;url=<?
/* TBD: push sticker image and description to funp */
print urlencode(site_url($name));
?>"><span class="sprite funp"></span>推推王</a></li>
		</ul>
	</div>
	<div id="middleblock">
	<div id="userinfo">
	<h2>關於<?php print htmlspecialchars($title) ?></h2>
	<ul>
		<li><span class="item">抓火狐網址</span> <a class="gfxurl value" href="<?php print site_url($name); ?>"><?php print site_url($name); ?></a></li>
<?php if ($web)  { ?>
		<li><span class="item">網站</span> <a class="web value newwindow" href="<?php print htmlspecialchars($web); ?>" title="<?php print htmlspecialchars($web); ?>" rel="me nofollow"><?php print htmlspecialchars($web); ?></a></li>
<?php } ?>
<?php if ($blog)  { ?>
		<li><span class="item">部落格</span> <a class="blog value newwindow" href="<?php print htmlspecialchars($blog); ?>" title="<?php print htmlspecialchars($blog); ?>" rel="me nofollow"><?php print htmlspecialchars($blog); ?></a></li>
<?php } ?>
<?php if ($forum_username)  { ?>
		<li><span class="item">MozTW 討論區 ID</span> <a class="forum-username value newwindow" href="http://forum.moztw.org/memberlist.php?mode=viewprofile&amp;u=<?php print htmlspecialchars($forum_id) ?>"><?php print htmlspecialchars($forum_username); ?></a></li>
<?php } ?>
<?php if ($bio)  { ?>
		<li><span class="item">一行自介</span> <span class="bio value"><?php print htmlspecialchars($bio); ?></span></li>
<?php } ?>
	</ul>
	</div>
	<div id="groups-title">
		<h2><?php print htmlspecialchars($title) ?>推薦的附加元件</h2>
		<p>Firefox 瀏覽器提供使用者上網所需的基本功能；除此之外，全球開發者更設計了各式各樣的附加元件，提供使用者自行增加牠的功能。這些附加元件大多與 Firefox 完美結合，讓您藉由這些有創意的附加元件，自訂您專屬的「火狐」！</p>
		<p>以下是<?php print htmlspecialchars($title) ?>所推薦的附加元件：</p>
		<p id="groups-show-detail"><input type="checkbox" id="groups-show-detail-box" checked="checked" /> <label for="groups-show-detail-box">顯示套件說明</label></p>
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
			<input type="checkbox" value="<?php print htmlspecialchars($xpi_url); ?>" id="install-<?php print $id ?>" /><label for="install-<?php print $id ?>">列入安裝清單</label>
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
			<input type="checkbox" disabled="disabled" id="install-<?php print $id ?>" /><label for="install-<?php print $id ?>">請至附加元件網站安裝</label>
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
		<p>這些附加元件的說明主要來自於 <a href="https://addons.mozilla.org/" class="newwindow">Mozilla 附加元件網站</a>，
由作者提供。</p>
		<div id="groups-install">
			<p><button>立刻安裝</button>安裝所有勾選的附加元件！</p>
			<p>在 Mozilla 附加元件網站標示為「實驗中」，或是安裝前需特別同意使用條款、隱私權保護條款的擴充套件無法在此快速安裝；請自行前往各擴充套件網頁。</p>
			<p>
		</div>
	</div>
	</div>
	<div id="window_extinstall" class="window" title="正在安裝...">
		<p><strong>請點選右上角出現的「允許」按鈕，允許敝站為您安裝附加元件。</strong></p>
		<p>根據您所勾選的元件數量，Firefox 可能需要一些時間確認檔案後才會出現「軟體安裝」通知。</p>
	</div>
