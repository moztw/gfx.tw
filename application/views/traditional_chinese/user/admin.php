<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.user.js'); ?>" charset="UTF-8"></script>
<div class="window" id="window_admin" title="管理使用者 ID#<?php print $id; ?>">
	<form id="admin_form" action="#" method="post">
		<p><label for="admin_login">Open ID 網址：</label> <?php print form_input(array('id' =>'admin_login', 'name' => 'login', 'value' => $login)); ?>
			<span class="form-desc">不得重複；無法登入的帳號只能用下面的切換使用者功能登入修改資料。</span></p>
		<p><label for="admin_count">Firefox 下載數：</label> <?php print form_input(array('id' =>'admin_count', 'name' => 'count', 'value' => $count)); ?>
			<span class="form-desc">不要幫別人作弊呀 (遠目)。</span></p>
		<p><label for="admin_avatar">個人圖示網址：</label> <?php print form_input(array('id' =>'admin_avatar', 'name' => 'avatar', 'value' => $avatar)); ?>
			<span class="form-desc">輸入相對於 /useravatars/ 的 URL 路徑；清空使用預設圖示，輸入<code>(gravatar)</code>使用 Gravatar。</span></p>
		<p><label for="admin_admin">擁有管理者權限：</label> <input type="checkbox" id="admin_admin" name="admin" <?php if ($admin === 'Y') print 'checked="checked"' ?>/>
			<span class="form-desc">使用者要重新登入才會出現管理介面。</span></p>
	</form>
</div>
<form id="admin_post" action="#" method="post">
	<input type="hidden" name="id" id="admin_id" value="<?php print $id; ?>" />
	<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
</form>
<div id="window_progress" class="window" title="與伺服器通訊中...">
	<img src="images/ajax-progress.gif" alt="處理中..." />
</div>