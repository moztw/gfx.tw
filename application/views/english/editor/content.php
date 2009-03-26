<?php

$this->load->config('gfx');
$this->load->helper('gfx');
$avatar = avatarURL($avatar, $email);

?>
	<div id="editor_save">
		<p><button id="editor_save_button">Save your page</button>Save your customization and claim your badges!</p>
	</div>
	<div id="window_almostdone" class="window" title="Almost done...">
		<p>Please set up your gfx URL, alphabets and numbers only:</p>
		<p><?php print base_url() . form_input(array('id' =>'name', 'value' => '')); ?></p>
	</div>
	<div id="window_editcomplete" class="window" title="Done!">
		<p>Thank you for suggesting Mozilla Firefox to your friends, next...</p>
		<ul>
			<li>Go to <a id="window_userpage_url" href="#">your personal gfx page</a>.</li>
			<li>Claim <a href="./sticker">stickers and badges</a>, put them on your blog or as forum signature!</li>
		</ul>
	</div>
	<div id="titleblock">
		<form id="title-name-form" action="#">
			<h1>
				<span id="title-avatar" class="editable"><img src="<?php print $avatar ?>" alt="[Personal Icon]" /></span>
				<span id="title-name" class="editable"><?php print htmlspecialchars($title) ?></span>
				<span id="title-name-edit"><?php print form_input('title', $title); ?></span>
				<span id="title-1">surfs the web with</span>
				<span id="title-2">Firefox</span>
				<span id="title-3">, so why don't you?</span>
			</h1>
		</form>
		<div class="download">
			<p class="link"><a href="/download">Download Now</a></p>
			<p class="version">3.0 series, latest</p>
		</div>
		<p class="count">{Download count will shown here.}</p>
		<p class="desc">The Internet will be more colorful and vivid with Firefox! Firefox contains so much features that could help you with your work, manage information. Because it's so powerful, <span class="title-placeholder">{your name}</span> would like to ask you to surf the web with Firefox! The following is the most attractive features <span class="title-placeholder">{您的名字}</span> thinks of Firefox:</p>
	</div>
	<div id="window_avatar" class="window" title="Choose your personal avatar">
		<div class="avatar_selection">
			<div id="avatar_spfupload" class="avatar_icon">
				<div id="avatar_spfupload_replace">&nbsp;</div>
			</div>
			<p>Upload picture</p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_glavatar" class="avatar_icon">
				<img src="<? print 'http://www.gravatar.com/avatar/' . md5($email) . '?s=60&amp;r=g&amp;d=' . urlencode(site_url('images/keyhole.gif')); ?>" alt="Gravatar" />
			</div>
			<p>Icon on <a href="http://www.gravatar.com/" class="newwindow">Gravatar</a></p>
			<p>(<a href="#" id="change-email">Change E-mail</a>)</p>
		</div>
		<div class="avatar_selection">
			<div id="avatar_default" class="avatar_icon">
				<img src="./images/keyhole.gif" alt="Keyhole firgure" />
			</div>
			<p>Default Icon</p>
		</div>
	</div>
	<div id="window_download" class="window" title="initiating Download ...">
		<h2>Thank you for downloading Firefox！</h2>
		<p>Download should start in a few second, if not, <a href="/download">click here</a>.</p>
		<p>Please rememeber to go to</p>
		<p class="gfx-url"><?php print site_url('{your URL}') ?></p>
		<p>with Firefox, customize your Firefox according to <span class="title-placeholder">{your name}</span>'s addon suggestions!</p>
	</div>
	<div id="featureselection">
		<p class="features-desc">Select three Firefox features you would like to promote with (You can drag the boxes to change the order):</p>
		<ul>
<?php
/* put it into a function scope */
function featureselection($feature) {
	extract($feature);
?>
			<li><?php
	print form_checkbox(array('id' => 'fs_' . $name, 'name' => 'fs_' . $id, 'checked' => isset($user_id)));
	print form_label($title, 'fs_' . $name, array('title' => $description));
	unset($user_id);
?></li>

<?php
}
$features = array();
foreach ($allfeatures as $feature) {
	featureselection($feature);
	if (isset($feature['order'])) {
		$features[intval($feature['order'])] = $feature;
	}
}
?>
		</ul>
		<p id="featureselection_save"><button>OK</button></p>
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
//cannot foreach
$i = 1;
while(isset($features[$i])) {
	feature($features[$i]);
	$i++;
}
?>
	</div>
	<div id="middleblock">
	<div id="userinfo">
		<h2>About <span class="title-placeholder">{your name}</span></h2>
		<p>Your personal intro will be shown here. <button>Edit</button></p>
	</div>
	<div id="window_info" class="window" title="Edit personal intro">
		<form id="info_form" action="#">
			<p><label for="info_name">gfx URL:</label> <span class="form-prepend"><?php print base_url() ?></span><?php print form_input(array('id' =>'info_name', 'value' => (substr($name, 0, 8) === '__temp__')?'':$name)); ?>
			<span class="form-desc">Your gfx URL; alphabets and numbers only.</span></p>
			<p><label for="info_email">E-mail: </label> <?php print form_input(array('id' =>'info_email', 'value' => $email)); ?>
			<span class="form-desc">Will not published; Glavatar avatar will change after reload.</span></p>
			<p><label for="info_web">Website:</label> <?php print form_input(array('id' =>'info_web', 'value' => $web)); ?></p>
			<p><label for="info_blog">Blog:</label> <?php print form_input(array('id' =>'info_blog', 'value' => $blog)); ?></p>
			<p><label for="info_forum">Forum ID:</label> <?php print form_password(array('id' =>'info_forum', 'value' => ($forum_id && $forum_username)?'(keep-the-forum-username)':'')); ?>
			<span class="form-desc">Get your authorization code <a href="http://forum.moztw.org/gfxcode.php" id="forum_auth">code</a>; remove the code to hide your forum username.</span>
			<span class="form-desc" id="forum_auth_iframe">&nbsp;</span></p>
			<p><label for="info_bio">One line bio:</label>
				<textarea id="info_bio"><?php print htmlspecialchars($bio) ?></textarea>
			</p>
			<p><a href="#" id="info_delete_account">Delete My Account</a></p>
		</form>
	</div>
	<div id="window_delete" class="window" title="Delete Account">
		<form id="delete_post" action="/user/delete" method="post">
			<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
		</form>
		<p>Account deletion will remove all information, and release your gfx URL for others to pick.</p>
		<p>Information CANNOT BE RECOVERED. Press the button below only if you know what you are doing.</p>
	</div>
	<div id="groups-title">
		<h2>Gangs <span class="title-placeholder">{Your name}</span>'s Firefox belongs to</h2>
		<p>Choose the "gangs" according to your Internet activity, and suggest addons accordingly.</p>
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
			<p class="del-addon ui-icon ui-icon-close" title="Delete">Delete</p>
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
				<p class="group-add-addon"><a href="#" class="ui-icon ui-icon-circle-plus" title="Suggest addons under this gang">Add</a></p>
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
			<p>Instructions to install addons from your gfx personal page will shown here.</p>
	</div>
	<div id="window_addons" class="window" title="Add New Add-ons">
		<form action="#" id="addon_query_form">
			<p>Search: <?php print form_input(array('id' =>'addon_query', 'value' => '')); ?> <button type="submit">Search Add-ons</button></p>
		</form>
		<p id="addon_query_desc">&nbsp;</p>
		<p id="addon_query_notfound">Add-on not found; it might be due to the fact that the addon your addon was never suggested by people. You can paste <a href="https://addons.mozilla.org/" class="newwindow">Mozilla Add-ons</a> URL on the search box to suggest directly.</p>
		<div id="addon_query_result" class="detailed">&nbsp;</div>
	</div>
	<div id="window_progress" class="window" title="Communting with the Server...">
		<img src="images/ajax-progress.gif" alt="Processing..." />
	</div>
	</div>