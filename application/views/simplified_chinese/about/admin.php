<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.about.js' . $this->config->item('gfx_suffix')); ?>" charset="UTF-8"></script>
<div class="window" id="window_admin" title="管理网站简介页面 ID#<?php print $id; ?>">
	<form id="admin_form" action="#" method="post">
		<p><label for="admin_title">标题：</label> <?php print form_input(array('id' =>'admin_title', 'name' => 'title', 'value' => $title)); ?>
			<span class="form-desc">啊就标题呀！</p>
		<p><label for="admin_name">网址：</label> <span class="form-prepend"><?php print site_url('about'); ?>/</span><?php print form_input(array('id' =>'admin_name', 'name' => 'name', 'value' => $name)); ?>
			<span class="form-desc">网址 (英数字)，改了之前被加的书签会失效。使用 <code>index</code> 代表网站简介主页面（关于我们）。</span></p>
		<p><label for="admin_content">网页内容：</label> <textarea id="admin_content" name="content" rows="15"><?php print htmlspecialchars($content); ?></textarea>
			<span class="form-desc">页面内容 (HTML)。</span></p>
	</form>
</div>
<form id="admin_post" action="/about/delete" method="post">
	<input type="hidden" name="id" id="admin_id" value="<?php print $id; ?>" />
	<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
</form>
<div id="window_progress" class="window" title="与伺服器通讯中...">
	<img src="images/ajax-progress.gif" alt="处理中..." />
</div>