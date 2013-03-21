<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.about' . JS_SUFFIX . $this->config->item('gfx_suffix')); ?>" charset="UTF-8"></script>
<div class="window" id="window_admin" title="管理網站簡介頁面 ID#<?php print $id; ?>">
	<form id="admin_form" action="#" method="post">
		<p><label for="admin_title">標題：</label> <?php print form_input(array('id' =>'admin_title', 'name' => 'title', 'value' => $title)); ?>
			<span class="form-desc">啊就標題呀！</p>
		<p><label for="admin_name">網址：</label> <span class="form-prepend"><?php print site_url('about'); ?>/</span><?php print form_input(array('id' =>'admin_name', 'name' => 'name', 'value' => $name)); ?>
			<span class="form-desc">網址 (英數字)，改了之前被加的書籤會失效。使用 <code>index</code> 代表網站簡介主頁面（關於我們）。</span></p>
		<p><label for="admin_content">網頁內容：</label> <textarea id="admin_content" name="content" rows="15"><?php print htmlspecialchars($content); ?></textarea>
			<span class="form-desc">頁面內容 (HTML)。</span></p>
	</form>
</div>
<form id="admin_post" action="/about/delete" method="post">
	<input type="hidden" name="id" id="admin_id" value="<?php print $id; ?>" />
	<input type="hidden" name="token" value="<?php print md5($this->session->userdata('id') . $this->config->item('gfx_token')); ?>" />
</form>
<div id="window_progress" class="window" title="與伺服器通訊中...">
	<img src="images/ajax-progress.gif" alt="處理中..." />
</div>
