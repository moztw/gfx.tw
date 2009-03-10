<?php

$this->load->helper('form');

?><div class="window" id="window_admin" title="管理功能推薦 ID#<?php print $id; ?>">
	<form id="admin_form" action="#" method="post">
		<p><label for="admin_title">標題：</label> <?php print form_input(array('id' =>'admin_title', 'name' => 'title', 'value' => $title)); ?>
			<span class="form-desc">啊就標題呀！</p>
		<p><label for="admin_name">網址：</label> <span class="form-prepend"><?php print site_url('feature'); ?>/</span><?php print form_input(array('id' =>'admin_name', 'name' => 'name', 'value' => $name)); ?>
			<span class="form-desc">網址 (英數字)，改了之前被加的書籤會失效。</span></p>
		<p><label for="admin_order">順序：</label> <?php print form_input(array('id' =>'admin_order', 'name' => 'order', 'value' => $order)); ?>
			<span class="form-desc">顯示在編輯頁面的順序。</span></p>
		<p><label for="admin_description">簡短說明：</label> <textarea id="admin_description" name="description"><?php print htmlspecialchars($description); ?></textarea>
			<span class="form-desc">會出現在使用者頁面的說明 (純文字)。</span></p>
		<p><label for="admin_content">網頁內容：</label> <textarea id="admin_content" name="content" rows="15"><?php print htmlspecialchars($content); ?></textarea>
			<span class="form-desc">介紹頁的內容 (HTML)。</span></p>
	</form>
</div>
<form id="admin_post" action="./delete" method="post">
	<input type="hidden" name="id" id="admin_id" value="<?php print $id; ?>" />
	<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
</form>
<div id="window_progress" class="window" title="與伺服器通訊中...">
	<img src="images/ajax-progress.gif" alt="處理中..." />
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