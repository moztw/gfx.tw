	<title><?php print htmlspecialchars($title); ?>的抓火狐推荐页！</title>
	<meta name="title" content="推荐您和<?php print htmlspecialchars($title); ?>一起抓火狐！" />
	<meta name="description" content="您的网际生活将因 Firefox 更加丰富有趣！Firefox 有许多特色，协助您完成工作、找到资讯。正因为它如此实用，<?php print htmlspecialchars($title); ?>愿意推荐您改用 Firefox！以下是<?php print htmlspecialchars($title); ?>最喜欢 Firefox 的三大特点：" />
	<link rel="image_src" href="<?php print site_url('/userstickers/' . dechex(intval($id) >> 12) . '/' . dechex(intval($id & (pow(2,12)-1)))) ?>/featurecard.png" />