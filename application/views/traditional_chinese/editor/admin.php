<?php

$this->load->helper('form');

?>
	<script type="text/javascript" src="<?php print site_url('js/admin.editor.js' . $this->config->item('gfx_suffix')); ?>" charset="UTF-8"></script>
<div class="window with-dialog" id="window_admin" title="網站管理">
	<ul class="tabs">
		<li class="ui-corner-top ui-state-default">統計</li>
<!--		<li class="ui-corner-top ui-state-default">使用者帳號</li>
		<li class="ui-corner-top ui-state-default">功能推薦</li>
		<li class="ui-corner-top ui-state-default">附加元件</li>
		<li class="ui-corner-top ui-state-default">說明文件</li>-->
	</ul>
	<div class="tab-content ui-widget-content">
		<h3>使用者</h3>
		<ul>
			<li>使用者人數：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users`;')->row()->count;
?>
</li>
			<li>推薦頁數目：<?php
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `ready` = \'Y\';')->row()->count;
?>
</li>
			<li>隨機露出人數：<?php
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `avatar` != \'\' AND `ready` = \'Y\' AND `shown` = \'Y\';')->row()->count;
?>
</li>
			<li>推薦頁使用上傳頭像功能數量：<?php
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `avatar` != \'\' AND `avatar` != \'(gravatar)\' AND `ready` = \'Y\';')->row()->count;
?>
</li>
			<li>有填 E-mail 的人數：<?php
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `email` != \'\';')->row()->count;
?>
</li>
			<li>總下載數（每位使用者從 1 開始）：<?php
print $this->db->query('SELECT SUM(`count`) AS count FROM `users`;')->row()->count;
?>
</li>
			<li>最多人瀏覽的推薦者：要去 Google Analytics 撈。</li>
		</ul>
		<h3>附加元件</h3>
		<ul>
			<li>資料庫內附加元件數（曾經執行抓取的附加元件）：<?php
print $this->db->query('SELECT COUNT(`id`) AS count FROM `addons`;')->row()->count;
?>
</li>
			<li>最受歡迎套件：請去按各分類的建議。</li>
		</ul>
		<h3>功能推薦</h3>
		<ul>
<?php
		foreach (
			$this->db->query('SELECT features.title, count(users.id) AS count FROM features, users WHERE users.feature_0=features.id OR users.feature_1=features.id OR users.feature_2 = features.id GROUP BY features.id ORDER BY count DESC;')->result_array()
			as
			$feature
		) {
			print '<li>' . htmlspecialchars($feature['title']) . '：' . $feature['count'] . '</li>';
		}
?>
		</ul>
		<h3>OpenID 使用統計</h3>
		<ul>
			<li>Google：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `login` LIKE \'%google.com%\';')->row()->count;
?>
</li>
			<li>Yahoo!：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `login` LIKE \'%yahoo.com%\';')->row()->count;
?>
</li>
			<li>AIM：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `login` LIKE \'%aol.com%\';')->row()->count;
?>
</li>
			<li>LiveJournal：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `login` LIKE \'%livejournal.com%\';')->row()->count;
?>
</li>
			<li>myID.tw：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `login` LIKE \'%myid.tw%\';')->row()->count;
?>
</li>
			<li>myOpenID：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `login` LIKE \'%myopenid.com%\';')->row()->count;
?>
</li>
			<li>TypePad：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `login` LIKE \'%typekey.com%\';')->row()->count;
?>
</li>
			<li>WordPress.com：<?php 
print $this->db->query('SELECT COUNT(`id`) AS count FROM `users` WHERE `login` LIKE \'%wordpress.com%\';')->row()->count;
?>
</li>
		</ul>
	</div>
<!--	<div class="tab-content ui-widget-content">
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
	</div>-->
</div>
