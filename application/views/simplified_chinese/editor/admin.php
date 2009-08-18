<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.editor.js' . $this->config->item('gfx_suffix')); ?>" charset="UTF-8"></script>
<div class="window with-dialog" id="window_admin" title="网站管理">
	<ul class="tabs">
		<li class="ui-corner-top ui-state-default">统计</li>
		<li class="ui-corner-top ui-state-default">使用者帐号</li>
		<li class="ui-corner-top ui-state-default">功能推荐</li>
		<li class="ui-corner-top ui-state-default">附加组件</li>
		<li class="ui-corner-top ui-state-default">说明文件</li>
	</ul>
	<div class="tab-content ui-widget-content">
		<ul>
			<li>使用者人数：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users`;')->row()->count;
?>
</li>
			<li>推荐页数目：<?php
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `ready` = \'Y\';')->row()->count;
?>
</li>
			<li>总下载数（每位使用者从 1 开始）：<?php
print $this->db->query('SELECT SUM(`count`) AS count FROM `users`;')->row()->count;
?>
</li>
			<li>最受欢迎套件：请去按各分类的建议。</li>
			<li>最多人浏览的推荐者：要去 Google Analytics 捞。</li>
		</ul>
	</div>
	<div class="tab-content ui-widget-content">
		<p>Users</p>
	</div>
	<div class="tab-content ui-widget-content">
		<p>Features</p>
	</div>
	<div class="tab-content ui-widget-content">
		<p>Add-ons</p>
	</div>
	<div class="tab-content ui-widget-content">
		<p>About</p>
	</div>
</div>