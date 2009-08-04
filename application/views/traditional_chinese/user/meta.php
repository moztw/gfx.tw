	<title><?php print htmlspecialchars($title); ?>的抓火狐推薦頁！</title>
	<meta name="title" content="推薦您和<?php print htmlspecialchars($title); ?>一起抓火狐！" />
	<meta name="description" content="您的網際生活將因 Firefox 更加豐富有趣！Firefox 有許多特色，協助您完成工作、找到資訊。正因為它如此實用，<?php print htmlspecialchars($title); ?>願意推薦您改用 Firefox！以下是<?php print htmlspecialchars($title); ?>最喜歡 Firefox 的三大特點：" />
	<link rel="image_src" href="<?php print site_url('/userstickers/' . dechex(intval($id) >> 12) . '/' . dechex(intval($id & (pow(2,12)-1)))) ?>/featurecard.png" />
