<?php
$dir = site_url('/userstickers/' . dechex(intval($this->session->userdata('id')) >> 12) . '/' . dechex(intval($this->session->userdata('id') & (pow(2,12)-1))));
?>
<body class="sticker">
	<div class="pageblock">
		<h1>宣傳貼紙</h1>
		<div class="content">
			<div class="first-col">
				<h2>HTML 宣傳小卡</h2>
				<iframe style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.html"></iframe>
				<textarea readonly="readonly">&lt;iframe style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.html"&gt;&lt;/iframe&gt;</textarea>
			</div>
			<div class="col">
				<h2>圖片宣傳小卡</h2>
				<p><a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"><img style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.png" alt="連到我的抓火狐推薦頁！" /></a></p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"&gt;&lt;img style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir; ?>/featurecard.png" alt="連到我的抓火狐推薦頁！"/&gt;&lt;/a&gt;</textarea>
			</div>
			<div class="col">
				<h2>小貼紙</h2>
				<p><a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"><img style="margin: 5px auto; width: 200px; height: 100px" src="<?php print $dir ?>/smallsticker.png" alt="連到我的抓火狐推薦頁！" /></a></p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"&gt;&lt;img src="<?php print $dir ?>/smallsticker.png" alt="連到我的抓火狐推薦頁！"/&gt;&lt;/a&gt;</textarea>
<?php
function featuresticker($feature, $user_name) {
	extract($feature);
?>
				<p><a href="<?php print site_url($user_name); ?>" title="連到我的抓火狐推薦頁！"><img src="<?php print site_url('stickerimages/features/' . $name . '.png'); ?>" alt="<?php print htmlspecialchars($title); ?>"/></a></p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($user_name); ?>" title="連到我的抓火狐推薦頁！"&gt;&lt;img src="<?php print site_url('stickerimages/features/' . $name . '.png'); ?>" alt="<?php print htmlspecialchars($title); ?>"/&gt;&lt;/a&gt;</textarea>
<?php
}
foreach ($features as $feature) {
	featuresticker($feature, $name);
}
?>
			</div>
			<div class="desc">
				<h2>使用方法</h2>
				<p>請將下列 HTML 碼複製貼上到您部落格的側邊攔喔！</p>
				<p>Blah...</p>
			</div>
	</div>
