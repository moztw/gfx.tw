<?php print '<?xml version="1.0" encoding="UTF-8"?>' ?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style/ui.all.css') ?>" />
	<script type="text/javascript" src="<?php print site_url('js/jquery-1.3.1.min.js') ?>"></script>
	<script type="text/javascript" src="<?php print site_url('js/jquery-ui-personalized-1.6.min.js') ?>"></script>
	<script type="text/javascript" src="<?php print site_url('js/' . $this->config->item('language') . '-text.js') ?>" charset="UTF-8"></script>
	<script type="text/javascript" src="<?php print site_url('js/global.js') ?>" charset="UTF-8"></script>
{meta}
</head>
<body>
{header}
{messages}
<div class="ui-widget message">
	<div class="ui-state-{type} ui-corner-all"> 
		<p><a href="#" class="ui-icon ui-icon-close"></a><span class="ui-icon ui-icon-{icon}"></span>
{message}</p>
	</div>
</div>
{/messages}
{content}
	<div id="footer">
		<p>Copyright &copy; gfx.tw | <a href="<?php print site_url('about') ?>">About Us</a> | <a href="<?php print site_url('about/legal') ?>">Legal</a> | <a href="http://www.moztw.org/">MozTW, Mozilla Taiwan Community</a> <img src="http://www.moztw.org/images/moztw_80x15.png" alt="MozTW" /></p>
		<p>Not affiliated with Mozilla. Firefox and the Firefox logos are trademarks of the <a href="http://www.mozilla.org/">Mozilla Fundation</a>.</p>
	</div>
	<!-- <?php print 'Elapsed Time: ' . $this->benchmark->elapsed_time() . ', Memory usage: ' . $this->benchmark->memory_usage(); ?>, from DB: {db} -->
	</body>
</html>