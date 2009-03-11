<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.feature.js'); ?>" charset="UTF-8"></script>
<div class="window" id="window_admin" title="Manage Feature Introduction ID#<?php print $id; ?>">
	<form id="admin_form" action="#" method="post">
		<p><label for="admin_title">Title:</label> <?php print form_input(array('id' =>'admin_title', 'name' => 'title', 'value' => $title)); ?>
			<span class="form-desc">Just the title.</p>
		<p><label for="admin_name">URL:</label> <span class="form-prepend"><?php print site_url('feature'); ?>/</span><?php print form_input(array('id' =>'admin_name', 'name' => 'name', 'value' => $name)); ?>
			<span class="form-desc">URL (alphabetic and numeric chars only), will break bookmark if changes.</span></p>
		<p><label for="admin_order">Order:</label> <?php print form_input(array('id' =>'admin_order', 'name' => 'order', 'value' => $order)); ?>
			<span class="form-desc">List order shown on editor.</span></p>
		<p><label for="admin_description">Description:</label> <textarea id="admin_description" name="description"><?php print htmlspecialchars($description); ?></textarea>
			<span class="form-desc">Brief description shown on user pages (Plain text).</span></p>
		<p><label for="admin_content">Content:</label> <textarea id="admin_content" name="content" rows="15"><?php print htmlspecialchars($content); ?></textarea>
			<span class="form-desc">HTML Content of the full introduction page (HTML).</span></p>
	</form>
</div>
<form id="admin_post" action="./delete" method="post">
	<input type="hidden" name="id" id="admin_id" value="<?php print $id; ?>" />
	<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
</form>
<div id="window_progress" class="window" title="Loading...">
	<img src="images/ajax-progress.gif" alt="Processing..." />
</div>