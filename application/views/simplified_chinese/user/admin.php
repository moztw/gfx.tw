<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.user.js' . $this->config->item('gfx_suffix')); ?>" charset="UTF-8"></script>
<div class="window" id="window_admin" title="管理使用者 ID#<?php print $id; ?>">
	<form id="admin_form" action="#" method="post">
		<p><label for="admin_login">Open ID 网址：</label> <?php print form_input(array('id' =>'admin_login', 'name' => 'login', 'value' => $login)); ?>
			<span class="form-desc">不得重复；无法登入的帐号只能用下面的切换使用者功能登入修改资料。</span></p>
		<p><label for="admin_count">Firefox 下载数：</label> <?php print form_input(array('id' =>'admin_count', 'name' => 'count', 'value' => $count)); ?>
			<span class="form-desc">不要帮别人作弊呀 (远目)。</span></p>
		<p><label for="admin_avatar">个人图示网址：</label> <?php print form_input(array('id' =>'admin_avatar', 'name' => 'avatar', 'value' => $avatar)); ?>
			<span class="form-desc">输入相对于 /useravatars/ 的 URL 路径；清空使用预设图示，输入<code>(gravatar)</code>使用 Gravatar。</span></p>
		<p><label for="admin_admin">拥有管理者权限：</label> <input type="checkbox" id="admin_admin" name="admin" <?php if ($admin === 'Y') print 'checked="checked"' ?>/>
			<span class="form-desc">使用者要重新登入才会出现管理介面。</span></p>
		<p><label for="admin_shown">显示：</label> <input type="checkbox" id="admin_shown" name="shown" <?php if ($shown === 'Y') print 'checked="checked"' ?>/>
			<span class="form-desc">在网站的随机大头等处秀出此页。</span></p>
	</form>
</div>
<form id="admin_post" action="#" method="post">
	<input type="hidden" name="id" id="admin_id" value="<?php print $id; ?>" />
	<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
</form>
<div id="window_progress" class="window" title="与伺服器通讯中...">
	<img src="images/ajax-progress.gif" alt="处理中..." />
</div>