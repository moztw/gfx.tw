<?php

$header_title = $title . '的抓火狐推薦頁！';

require_once('inc/header.inc.php');


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
			<p>免費下載</p>
			<p><?php print htmlspecialchars($title) ?>已經推薦<?php print $count ?>人使用了！</p>
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
<?
	
	require_once('inc/footer.inc.php');