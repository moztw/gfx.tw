<?php
/*

This is an fraking email. Only inline style works and javascript will not work.
We downgrade ourselves to send only HTML fragment instead of standard HTML doc as most people use webmail anyway.

*/
?>

<div style="font: 0.92em sans-serif; background-color: #ffffff">
	<p>您好，</p>
	<p>來自 <?php print $ip ?> 的使用者查詢了此 E-mail 帳號下，所有抓火狐頁面所使用的 OpenID。
	您現在可以複製下列的 OpenID，至抓火狐網站<a href="<?php print site_url('#login');?>">登入</a>，重新編輯您的抓火狐頁面。</p>
	<ol>
<?php
	foreach ($logins as $L) {
?>
<?php
		if (isset($L['name'])) { 
?>
		<li>推薦頁網址：<a href="<?php print site_url($L['name']); ?>"><?php print site_url($L['name']); ?></a>
<?php
		} else {
?>
		<li>推薦頁網址：(尚未建立)</li>
<?php
		}
?>
			<ul>
				<li>OpenID 網址：<?php print htmlspecialchars($L['login']); ?></li>
				<li><form action="<?php print site_url('auth/login'); ?>" method="post">
					<input type="hidden" name="openid-identifier" value="<?php print htmlspecialchars($L['login']); ?>" />
					<input type="submit" value="直接登入" />
				</form></li><?php
?>
			</ul>
		</li>
<?php
	}
?>
	</ol>
	<p>若您忘記上列 OpenID 的密碼，請逕向 OpenID 服務商查詢。</p>
	<p>若您並沒有向我們查詢您使用過的 OpenID，<strong>請直接忽略此訊息，您的個人資料並沒有被洩漏。</strong></p>
	<p>祝　順心，</p>
	<p>抓火狐網站 同仁敬上</p>
	<hr />
	<p style="font-size: 0.82em; color: #cccccc">版權所有 &copy; gfx.tw</p>
</div>
