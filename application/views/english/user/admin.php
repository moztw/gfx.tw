<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.user.js'); ?>" charset="UTF-8"></script>
<div class="window" id="window_admin" title="Update User ID#<?php print $id; ?>">
	<form id="admin_form" action="/user/update" method="post">
		<p><label for="admin_login">Open ID URL:</label> <?php print form_input(array('id' =>'admin_login', 'name' => 'login', 'value' => $login)); ?>
			<span class="form-desc">Cannot repeat; Use the switch button below to "log in" with these accounts.</span></p>
		<p><label for="admin_count">Firefox download count:</label> <?php print form_input(array('id' =>'admin_count', 'name' => 'count', 'value' => $count)); ?>
			<span class="form-desc">Try not to cheat, ok?</span></p>
		<p><label for="admin_avatar">Avatar URL:</label> <?php print form_input(array('id' =>'admin_avatar', 'name' => 'avatar', 'value' => $avatar)); ?>
			<span class="form-desc">URL relative to /useravatars/. Empty to use default avatar, enter <code>(gravatar)</code> to use Gravatar.</span></p>
		<p><label for="admin_admin">Administrator:</label> <input type="checkbox" id="admin_admin" name="admin" <?php if ($admin === 'Y') print 'checked="checked"' ?>/>
			<span class="form-desc">Users would need to log in again to access admin functions.</span></p>
	</form>
</div>
<form id="admin_post" action="#" method="post">
	<input type="hidden" name="id" value="<?php print $id; ?>" />
	<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
</form>
<div id="window_progress" class="window" title="Loading...">
	<img src="images/ajax-progress.gif" alt="Processing..." />
</div>