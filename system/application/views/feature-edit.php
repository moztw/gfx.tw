<?php

/* Edit and Create feature */

print '<?xml version="1.0" encoding="UTF-8"?>';

?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-TW" lang="zh-TW">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?php if ($create) { ?>
	<title>Creating new feature suggestion</title>
<?php } else { ?>
	<title>Editing feature #<?php print $id ?></title>
<?php } ?>
</head>

<body class="feature-edit">
<?php
if ($create) { ?>
	<h1>Creating new feature suggestion</h1>
	<?php
	print validation_errors();
	print form_open('feature/edit');
} else { ?>
	<h1>Editing feature #<?php print $id ?></h1>
	<?php
	print validation_errors();
	print form_open('feature/edit/' . $name, '', array('id' => $id));
} ?>

	<p>URL: <code><?php print site_url('feature'); ?>/<?php print form_input('name', set_value('name', $name)); ?></code></p>
<?php if (!$create) { ?>
	<p>Note that links on the net will break if you change the URL.</p>
<?php } ?>
	<p>Title: <?php print form_input('title', set_value('title', $title)); ?></p>
	<p>Description (on the user pages):</p>
		<?php print form_textarea('description', set_value('description', $description)); ?>

	<p>Content (HTML):</p>
		<?php print form_textarea('content', set_value('content', $content)); ?>

	<p><?php print form_submit('', 'Submit'); print form_reset('', 'Reset'); ?></p>
	</form>
<?php if (!$create) { ?>
	<hr />
<?php
	print form_open('feature/edit/' . $name, '', array('id' => $id, 'delete', '1'));
	print form_submit('', 'Delete');
?>


<?php } ?>
</body>
</html>