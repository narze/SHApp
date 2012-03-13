<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $header;?></title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
</head>
<body>
	<?php //Please run $this->socialhappen->get_bar() in controller
		if(isset($socialhappen_bar_css) && isset($socialhappen_bar_html)) : ?>
		<div style="position:absolute; top:0; left:0 width:520px; margin:0;">
			<link rel="stylesheet" type="text/css" href="<?php echo $socialhappen_bar_css; ?>">
			<?php echo $socialhappen_bar_html; ?>
		</div>
	<?php endif; ?>