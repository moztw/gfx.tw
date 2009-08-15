<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.feature.js' . $this->config->item('gfx_suffix')); ?>" charset="UTF-8"></script>
<div class="window" id="window_admin" title="管理功能推荐 ID#<?php print $id; ?>">
	<form id="admin_form" action="#" method="post">
		<p><label for="admin_title">标题：</label> <?php print form_input(array('id' =>'admin_title', 'name' => 'title', 'value' => $title)); ?>
			<span class="form-desc">啊就标题呀！</p>
		<p><label for="admin_name">网址：</label> <span class="form-prepend"><?php print site_url('feature'); ?>/</span><?php print form_input(array('id' =>'admin_name', 'name' => 'name', 'value' => $name)); ?>
			<span class="form-desc">网址 (英数字)，改了之前被加的书签会失效。</span></p>
		<p><label for="admin_order">顺序：</label> <?php print form_input(array('id' =>'admin_order', 'name' => 'order', 'value' => $order)); ?>
			<span class="form-desc">显示在编辑页面的顺序。</span></p>
		<p><label for="admin_description">简短说明：</label> <textarea id="admin_description" name="description"><?php print htmlspecialchars($description); ?></textarea>
			<span class="form-desc">会出现在使用者页面的说明 (纯文字)。</span></p>
		<p><label for="admin_content">网页内容：</label> <textarea id="admin_content" name="content" rows="15"><?php print htmlspecialchars($content); ?></textarea>
			<span class="form-desc">介绍页的内容 (HTML)。</span></p>
	</form>
</div>
<form id="admin_post" action="./delete" method="post">
	<input type="hidden" name="id" id="admin_id" value="<?php print $id; ?>" />
	<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
</form>
<div id="window_progress" class="window" title="与伺服器通讯中...">
	<img src="images/ajax-progress.gif" alt="处理中..." />
</div>