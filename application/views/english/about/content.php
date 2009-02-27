<body class="about">
	<div class="pageblock">
		<h1 class="<?php print $name ?>"><?php print htmlspecialchars($title) ?></h1>
		<div class="content">
	<?php
	$this->load->helper('typography');
	print auto_typography($content);
	?>
 
		</div>
	</div>