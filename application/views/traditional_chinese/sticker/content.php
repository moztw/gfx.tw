<?php
$dir = site_url('/userstickers/' . dechex(intval($this->session->userdata('id')) >> 12) . '/' . dechex(intval($this->session->userdata('id') & (pow(2,12)-1))));
?>
<body class="sticker">
	<div class="pageblock">
		<h1>宣傳貼紙</h1>
		<div class="content">
			<div class="first-col">
				<h2>HTML 宣傳小卡</h2>
				<iframe style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.html?<?php print strtotime($modified) ?>"></iframe>
				<textarea readonly="readonly">&lt;iframe style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.html"&gt;&lt;/iframe&gt;</textarea>
			</div>
			<div class="col">
				<h2>圖片宣傳小卡</h2>
				<p><a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"><img style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.png?<?php print strtotime($modified) ?>" alt="連到我的抓火狐推薦頁！" /></a></p>
				<p>HTML:</p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"&gt;&lt;img style="margin: 5px auto; border: none; width: 200px; height: 250px" src="<?php print $dir; ?>/featurecard.png" alt="連到我的抓火狐推薦頁！"/&gt;&lt;/a&gt;</textarea>
				<p>BBCode:</p>
				<textarea readonly="readonly">[url=<?php print site_url($name); ?>][img]<?php print $dir ?>/featurecard.png[/img][/url]</textarea>
			</div>
			<div class="col">
				<h2>小貼紙</h2>
				<p><a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"><img style="margin: 5px auto; width: 200px; height: 100px" src="<?php print $dir ?>/smallsticker.png" alt="連到我的抓火狐推薦頁！" /></a></p>
				<p>HTML:</p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"&gt;&lt;img style="border: none; height: 100px; width: 200px" src="<?php print $dir ?>/smallsticker.png" alt="連到我的抓火狐推薦頁！"/&gt;&lt;/a&gt;</textarea>
				<p>BBCode:</p>
				<textarea readonly="readonly">[url=<?php print site_url($name); ?>][img]<?php print $dir ?>/smallsticker.png[/img][/url]</textarea>
				<p><a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"><img style="margin: 5px auto; width: 165px; height: 90px" src="<?php print $dir ?>/smallsticker2.png" alt="連到我的抓火狐推薦頁！" /></a></p>
				<p>HTML:</p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"&gt;&lt;img style="border: none; height: 90px; width: 165px" src="<?php print $dir ?>/smallsticker2.png" alt="連到我的抓火狐推薦頁！"/&gt;&lt;/a&gt;</textarea>
				<p>BBCode:</p>
				<textarea readonly="readonly">[url=<?php print site_url($name); ?>][img]<?php print $dir ?>/smallsticker2.png[/img][/url]</textarea>
			</div>
			<div class="desc">
				<h2>橫向貼紙</h2>
				<p><a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"><img src="<?php print $dir ?>/featurecard-h.png?<?php print strtotime($modified) ?>" alt="連到我的抓火狐推薦頁！" /></a></p>
				<p>HTML:</p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="連到我的抓火狐推薦頁！"&gt;&lt;img style="border: none; height: 60px; width: 468px" src="<?php print $dir; ?>/featurecard-h.png" alt="連到我的抓火狐推薦頁！"/&gt;&lt;/a&gt;</textarea>
				<p>BBCode:</p>
				<textarea readonly="readonly">[url=<?php print site_url($name); ?>][img]<?php print $dir ?>/featurecard-h.png[/img][/url]</textarea>
			</div>
			<div class="desc">
				<h2>功能推薦貼紙</h2>
			</div>
<?php
function featuresticker($feature, $user_name) {
	extract($feature);
?>
			<div class="<?php print $class; ?>">
				<p><a href="<?php print site_url($user_name); ?>" title="連到我的抓火狐推薦頁！"><img src="<?php print site_url('stickerimages/features/' . $name . '.png'); ?>" alt="<?php print htmlspecialchars($title); ?>"/></a></p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($user_name); ?>" title="連到我的抓火狐推薦頁！"&gt;&lt;img src="<?php print site_url('stickerimages/features/' . $name . '.png'); ?>" alt="<?php print htmlspecialchars($title); ?>"/&gt;&lt;/a&gt;</textarea>
			</div>
<?php
}
$features[0]['class'] = 'first-col';
$features[1]['class'] = 'col';
$features[2]['class'] = 'col';
foreach ($features as $feature) {
	featuresticker($feature, $name);
}
?>
	</div>
