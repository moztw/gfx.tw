<?php print '<?xml version="1.0" encoding="UTF-8"?>' ?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-TW" lang="zh-TW">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>抓火狐 :: Firefox 功能推荐 :: <?php print htmlspecialchars($title) ?></title>
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style/global.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style/language-zh-TW.css') ?>" />
	<meta name="robots" content="noindex, noarchive" /><!-- Don't index frame page coz it would create dup. -->
</head>
<body class="feature-inframe inframe">
	<script type="text/javascript">
		if (window.parent === window) {
			location.href = window.location.pathname.substr(0, window.location.pathname.length-8) + window.location.hash;
		}
	</script>
	<?php
if ($content) {
	$this->load->helper('typography');
	print auto_typography($content);
} else {
	print '<p>没有内容 (摊手)。</p>';
}
	?>
</body>
</html>