<?php

print '<?xml version="1.0" encoding="UTF-8"?>';

?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-TW" lang="zh-TW">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title><?php print htmlspecialchars($title) ?></title>
</head>

<body class="feature">
	<h1 class="<?php print $name ?>"><?php print htmlspecialchars($title) ?></h1>
	<div class="content">
<?php print htmlspecialchars($content) ?>
	</div>
</body>
</html>