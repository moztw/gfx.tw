<?php
print '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-TW" lang="zh-TW">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style/global.css' . $this->config->item('gfx_suffix')) ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style/jquery-ui-1.7.custom.css' . $this->config->item('gfx_suffix')) ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print site_url('style/language-zh-TW.css' . $this->config->item('gfx_suffix')) ?>" />
{meta}
<?php 
$gaq = $this->config->item('gfx_gaq');
if ($gaq) {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php print $gaq ?>', 'auto');  // Replace with your property ID.
  ga('send', 'pageview');

</script>
<?php
}
?>
</head>
<body>
{header}
{messages}
<div class="ui-widget message show{announcement}" rel="{rel}">
	<div class="ui-state-{type} ui-corner-all"> 
		<p><a href="#" class="ui-icon ui-icon-circle-close ui-corner-all"></a><!--<span class="ui-icon ui-icon-{icon}"></span>-->
{message}</p>
	</div>
</div>
{/messages}
{content}
	<div id="footer">
		<p>版權所有 &copy; gfx.tw | <a href="<?php print site_url('/about') ?>">關於我們</a> | <a href="<?php print site_url('/about/legal') ?>">使用條款</a> | <a href="<?php print site_url('/about/faq') ?>">常見問題</a> | <a href="https://moztw.org/" id="link-moztw">MozTW，台灣 Mozilla 社群 <img src="https://moztw.org/images/moztw_80x15.png" alt="MozTW" /></a></p>
		<p>Not affiliated with Mozilla. Firefox and the Firefox logos are trademarks of the <a href="https://www.mozilla.org/" id="link-mozilla">Mozilla Fundation</a>.</p>
	</div>
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
	<script type="text/javascript" src="<?php print site_url('js/jquery-ui.custom.min.js' . $this->config->item('gfx_suffix')) ?>"></script>
	<script type="text/javascript" src="<?php print site_url('js/text-zh-TW.js' . $this->config->item('gfx_suffix')) ?>" charset="UTF-8"></script>
	<script type="text/javascript" src="<?php print site_url('js/global' . JS_SUFFIX . $this->config->item('gfx_suffix')) ?>" charset="UTF-8"></script>
{script}
{admin}
	<!-- <?php print 'Elapsed Time: ' . $this->benchmark->elapsed_time() . ', Memory usage: ' . $this->benchmark->memory_usage(); ?>, from DB: {db} -->
	</body>
</html>
