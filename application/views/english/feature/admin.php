<?php

$this->load->helper('form');

?><div class="window" id="window_admin" title="Manage Feature Introduction ID#<?php print $id; ?>">
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
<script type="text/javascript">
gfx.admin = {
	'bind' : {
		'click' : {
			'#link_manage a' : function () {
				gfx.openWindow('admin');
				return false;
			}
		}
	},
	'onload' : function () {
		if (!gfx.windowOption) {
			gfx.windowOption = {};
		}
		gfx.windowOption.admin = {
			'width' : 800,
			'height' : 450,
			'buttons' : {},
			'position' : ['center', 50],
			'open' : function () {
				$('.content object').css('visibility', 'hidden');
			},
			'close' : function () {
				$('.content object').css('visibility', null);
			}
		};
		setTimeout(
			function () {
				gfx.openWindow('admin');
			},
			550
		);
		$.each(
			gfx.admin.bind,
			function(e, o) {
				$.extend(
					gfx.bind[e] || (gfx.bind[e] = {}),
					o
				);
			}
		);
		gfx.windowOption.admin.buttons[T.BUTTONS.ADMIN_OK] = function () {
			gfx.xhr = $.ajax(
				{
					url: './update',
					data: {
						'token' : $('#token').val(),
						'id' : $('#admin_id').val(),
						'title' : $('#admin_title').val(),
						'name' : $('#admin_name').val(),
						'order' : $('#admin_order').val(),
						'description' : $('#admin_description').val(),
						'content' : $('#admin_content').val()
					},
					success: function (result, status) {
						if (result.error) {
							window.alert(T[result.tag] || result.error);
							return;
						}
						if (result.message) {
							if (result.message.type === 'error') {
								window.alert(result.message.msg);
							} else {
								gfx.message(
									result.message.type,
									result.message.icon,
									result.message.msg
								);
								gfx.closeWindow('admin');
							}
						}
					}
				}
			);
		};
		gfx.windowOption.admin.buttons[T.BUTTONS.ADMIN_DELETEFEATURE] = function () {
			window.alert('TBD coz we don\'t know how to handle users who select this feature.');
			return;
			if (!window.confirm(T.UI.ADMIN_DELETEFEATURE_CONFIRM)) {
				return;
			}
			$('#admin_post').submit();
		}
		$('#link_manage').show();
	}
};
</script>