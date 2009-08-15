<?php
$dir = site_url('/userstickers/' . dechex(intval($this->session->userdata('id')) >> 12) . '/' . dechex(intval($this->session->userdata('id') & (pow(2,12)-1))));
?>
<body class="sticker">
	<div class="pageblock">
		<h1>宣传贴纸</h1>
		<div class="content">
			<div class="first-col">
				<h2>HTML 宣传小卡</h2>
				<iframe style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.html?<?php print strtotime($modified) ?>"></iframe>
				<textarea readonly="readonly">&lt;iframe style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.html"&gt;&lt;/iframe&gt;</textarea>
			</div>
			<div class="col">
				<h2>图片宣传小卡</h2>
				<p><a href="<?php print site_url($name); ?>" title="连到我的抓火狐推荐页！"><img style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.png?<?php print strtotime($modified) ?>" alt="连到我的抓火狐推荐页！" /></a></p>
				<p>HTML:</p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="连到我的抓火狐推荐页！"&gt;&lt;img style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir; ?>/featurecard.png" alt="连到我的抓火狐推荐页！"/&gt;&lt;/a&gt;</textarea>
				<p>BBCode:</p>
				<textarea readonly="readonly">[url=<?php print site_url($name); ?>][img]<?php print $dir ?>/featurecard.png[/img][/url]</textarea>
			</div>
			<div class="col">
				<h2>小贴纸</h2>
				<p><a href="<?php print site_url($name); ?>" title="连到我的抓火狐推荐页！"><img style="margin: 5px auto; width: 200px; height: 100px" src="<?php print $dir ?>/smallsticker.png" alt="连到我的抓火狐推荐页！" /></a></p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="连到我的抓火狐推荐页！"&gt;&lt;img src="<?php print $dir ?>/smallsticker.png" alt="连到我的抓火狐推荐页！"/&gt;&lt;/a&gt;</textarea>
<?php
function featuresticker($feature, $user_name) {
	extract($feature);
?>
				<p><a href="<?php print site_url($user_name); ?>" title="连到我的抓火狐推荐页！"><img src="<?php print site_url('stickerimages/features/' . $name . '.png'); ?>" alt="<?php print htmlspecialchars($title); ?>"/></a></p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($user_name); ?>" title="连到我的抓火狐推荐页！"&gt;&lt;img src="<?php print site_url('stickerimages/features/' . $name . '.png'); ?>" alt="<?php print htmlspecialchars($title); ?>"/&gt;&lt;/a&gt;</textarea>
<?php
}
foreach ($features as $feature) {
	featuresticker($feature, $name);
}
?>
			</div>
			<div class="desc">
				<hr style="clear: both" />
			</div>
	</div>