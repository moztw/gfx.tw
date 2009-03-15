<?php
$dir = site_url('/userstickers/' . dechex(intval($this->session->userdata('id')) >> 12) . '/' . dechex(intval($this->session->userdata('id') & (pow(2,12)-1))));
?>
<body class="sticker">
	<div class="pageblock">
		<h1>Stickers and Badges</h1>
		<div class="content">
			<div class="first-col">
				<h2>HTML Promo Badge</h2>
				<iframe style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.html"></iframe>
				<textarea readonly="readonly">&lt;iframe style="margin: 5px auto; width: 200px; height: 250px" src="<?php print $dir ?>/featurecard.html"&gt;&lt;/iframe&gt;</textarea>
			</div>
			<div class="col">
				<h2>Image Promo Badge</h2>
				<p>TBD</p>
			</div>
			<div class="col">
				<h2>Stickers</h2>
				<p><a href="<?php print site_url($name); ?>" title="Go to my gfx personal page!"><img src="<?php print site_url('stickerimages/logo-wordmark-195x100.png'); ?>" alt="Mozilla Firefox"/></a></p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="Go to my gfx personal page!"&gt;&lt;img src="<?php print site_url('stickerimages/logo-wordmark-195x100.png'); ?>" alt="Mozilla Firefox"/&gt;&lt;/a&gt;</textarea>
				<p><a href="<?php print site_url($name); ?>" title="Go to my gfx personal page!"><img src="<?php print site_url('stickerimages/logo-wordmark-195x100.png'); ?>" alt="Mozilla Firefox"/></a></p>
				<textarea readonly="readonly">&lt;a href="<?php print site_url($name); ?>" title="Go to my gfx personal page!"&gt;&lt;img src="<?php print site_url('stickerimages/logo-wordmark-195x100.png'); ?>" alt="Mozilla Firefox"/&gt;&lt;/a&gt;</textarea>
			</div>
			<div class="desc">
				<h2>Usage</h2>
				<p>Copy the HTML code to your blog side bar.</p>
				<p>Blah...</p>
			</div>
	</div>