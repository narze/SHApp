<html>
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/responsive.css');?>" />
</head>
<body style="background-color:black">
	<center>
		<?php
		echo '<p>'.img($image_url).'</p>';
		echo form_open('home/upload', array('class'=>'form-inline'));
		?>
		
		<input style="height:28px;" id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
		<button type="submit" class="btn btn-danger" name="upload">Share!</button>
		<?php

		echo form_close();

		echo anchor('home/play', 'เล่นใหม่', 'class="btn"');
	?>
	</center>
</body>
</html>