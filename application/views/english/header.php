	<div id="header">
		<p id="header_top_link"><a href="<?php print base_url() ?>" title="Home">gfx</a></p>
<?php
if (isset($id)) {
	$this->load->config('gfx');
?>
		<p id="header_user_functions">Hi, <span id="header_username"><?php
	if ($title !== '') print htmlspecialchars($title);
	elseif (strlen($login) > 40) print htmlspecialchars(substr($login, strpos($login, '//', 5)+2, 30)) . '...';
	else print htmlspecialchars(rtrim(substr($login, strpos($login, '//', 5)+2), '/'));
 ?></span>(
 <?php
	if (substr($name, 0, 8) !== '__temp__') {
?><a href="<?php print site_url($name) ?>">My Page</a> / <a href="<?php print site_url('editor') ?>">Edit</a> / <a href="<?php print site_url('sticker') ?>">Stickers and Badges</a> / <a href="#" id="link_logout">Log out</a>
<?php
	} else { ?><a href="<?php print site_url('editor') ?>">Edit</a> / <a href="#" id="link_logout">Log out</a>
<?php
	} ?>
)</p>
	</div>
	<form id="logout_form" action="<?php print site_url('auth/logout'); ?>" method="post">
		<input type="hidden" id="token" name="token" value="<?php print md5($id . $this->config->item('gfx_token')) ?>" />
		<p><input type="submit" value="Logout" /></p>
	</form>
<?php } else { ?>
		<p id="header_login"><a href="#" id="link_login">Log in with OpenID</a> to get your one and only gfx personal page!</p>
	</div>
	<div id="window_login" class="window" title="Log in">
		<form action="<?php print site_url('auth/login'); ?>" method="post">
			<p><label for="openid-identifier">Your OpenID URL: </label><input type="text" name="openid-identifier" id="openid-identifier" value="" /> <input type="submit" value="Log in" /></p>
			<h3>What is OpenID?</h3>
			<p>OpenID allows you to log in our site with account from other websites, to avoid repeatedly entering information and remembering passwords. If you have registered account on the following website, you can use the account as an OpenID:</p>
			<p><label for="openid_sp">OpenID Provider：</label><select id="openid_sp">
				<option value="" label="(Choose your provider)" selected="selected">(Choose provider)</option>
				<optgroup label="Log in directly w/o entering username">
					<option value="https://www.google.com/accounts/o8/id">Google</option>
					<option value="https://me.yahoo.com">Yahoo!</option>
				</optgroup>
				<optgroup label="Please provide your own username">
					<option value="openid.aol.com/[username]">AIM</option>
					<option value="[username].livejournal.com">LiveJournal</option>
					<option value="[username].myid.tw">myID.tw</option>
					<option value="[username].myopenid.com">myOpenID</option>
					<option value="profile.typekey.com/[username]">TypePad</option>
					<option value="[username].wordpress.com">WordPress.com</option>
				</optgroup>
			</select></p>
			<p>If you really don't have any OpenID, or you don't want to connect this website with any of these credentials, you can create a blank one with <a href="http://myopenid.com/" class="newwindow">myOpenID</a>.</p>
			<p><strong>Note:</strong> You have to log out both site in order to clean up credentials completely.</p>
		</form>
	</div>
<?php
}
?>
