<?php

if (!$avatar) {
	$avatar = './images/keyhole.gif';
} elseif ($avatar === '(gravatar)') {
	$avatar = 'http://www.gravatar.com/avatar/' . md5($email) . '?s=65&amp;r=g&amp;d=' . urlencode(site_url('images/keyhole_edit.gif'));
} else {
	$avatar = './useravatars/' . $avatar;
}

?>
	<div id="titleblock">
		<h1>
			<span id="title-avatar"><img src="<?php print $avatar ?>" alt="[Personal Icon]" /></span>
			<span id="title-name"><?php print htmlspecialchars($title) ?></span>
			<span id="title-1">surfs the web with</span>
			<span id="title-2">Firefox</span>
			<span id="title-3">, so why don't you?</span>
		</h1>
		<div class="download">
			<p class="link"><a href="/download">Download</a></p>
			<p class="version">3.0 series, latest</p>
		</div>
		<p class="count"><?php print $count ?> people(s) downloaded and counting!</p>
		<p class="desc">The Internet will be more colorful and vivid with Firefox! Firefox contains so much features that could help you with your work, manage information. Because it's so powerful, <?php print htmlspecialchars($title) ?> would like to ask you to surf the web with Firefox! The following is the most attractive features <?php print htmlspecialchars($title) ?> thinks of Firefox:</p>
	</div>
	<div id="window_download" class="window" title="initiating Download ...">
		<h2>Thank you for downloading Firefox！</h2>
		<p>Download should start in a few second, if not, <a href="/download">click here</a>.</p>
		<p>Please rememeber to go to</p>
		<p class="gfx-url"><?php print site_url($name) ?></p>
		<p>with Firefox, customize your Firefox according to <?php print htmlspecialchars($title) ?>'s addon suggestions!</p>
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
	<h2>About <?php print htmlspecialchars($title) ?></h2>
	<ul>
		<li><span class="item">gfx URL</span> <a class="gfxurl value" href="<?php print site_url($name); ?>"><?php print site_url($name); ?></a></li>
<?php if ($web)  { ?>
		<li><span class="item">Web</span> <a class="web value newwindow" href="<?php print htmlspecialchars($web); ?>" title="<?php print htmlspecialchars($web); ?>"><?php print htmlspecialchars($web); ?></a></li>
<?php } ?>
<?php if ($blog)  { ?>
		<li><span class="item">Blog</span> <a class="blog value newwindow" href="<?php print htmlspecialchars($blog); ?>"  title="<?php print htmlspecialchars($blog); ?>"><?php print htmlspecialchars($blog); ?></a></li>
<?php } ?>
<?php if ($forum_username)  { ?>
		<li><span class="item">MozTW forum ID</span> <a class="forum-username value newwindow" href="http://forum.moztw.org/profile.php?mode=viewprofile&amp;u=<?php print htmlspecialchars($forum_id) ?>"><?php print htmlspecialchars($forum_username); ?></a></li>
<?php } ?>
<?php if ($bio)  { ?>
		<li><span class="item">Bio</span> <span class="bio value"><?php print htmlspecialchars($bio); ?></span></li>
<?php } ?>
	</ul>
	</div>
	<div id="groups-title">
		<h2>Gangs <?php print htmlspecialchars($title) ?>'s Firefox belongs to</h2>
		<p>Firefox comes with powerful costomable features.... (descriptive text on addons for new- and non-fx users)</p>
		<p id="groups-show-detail"><input type="checkbox" id="groups-show-detail-box" checked="checked" /> <label for="groups-show-detail-box">Show add-on descriptions</label></p>
	</div>
	<div id="groups" class="detailed">
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
	if ($xpi_url) { ?>
		<p class="install"><input type="checkbox" value="<?php print htmlspecialchars($xpi_url); ?>" id="addon-install-<?php print $id ?>" /><label for="addon-install-<?php print $id ?>">Install this add-on</label></p>
<?php
	} else { ?>
		<p class="install disabled"><input type="checkbox" id="addon-install-<?php print $id ?>" disabled="disabled" /><label for="addon-install-<?php print $id ?>">Cannot install here</label></p>
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
		<p>These add-ons and descriptions are mainly from <a href="https://addons.mozilla.org/" class="newwindow">Mozilla Add-ons</a>,
provided by add-on developers.</p>
		<div id="groups-install">
			<p><button>Install Now</button>Install all checked add-ons!</p>
			<p>"Experimential" add-ons on Mozilla Add-ons or add-ons comes with special terms of use or privacy policies cannot be installed from here. Please go to their respective websites.</p>
		</div>
	</div>
	</div>
	<div id="window_extinstall" class="window" title="Installing...">
		<p><strong>Please press "Allow" button on the top-right corner to allow add-on installation.</strong></p>
		<p>Depend on number of add-ons selected, Firefox might need some time to check files before "Software Install" dialog could show up.</p>
	</div>