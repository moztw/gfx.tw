<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.editor.js'); ?>" charset="UTF-8"></script>
<div class="window with-dialog" id="window_admin" title="網站管理">
	<ul class="tabs">
		<li class="ui-corner-top ui-state-default">使用者帳號</li>
		<li class="ui-corner-top ui-state-default">功能推薦</li>
		<li class="ui-corner-top ui-state-default">附加元件</li>
		<li class="ui-corner-top ui-state-default">說明文件</li>
	</ul>
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
