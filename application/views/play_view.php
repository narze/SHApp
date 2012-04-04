<html>
<head>
	<title></title>
</head>
<body bgcolor="black">
	<center>
		<?php
		echo img($image_url);
		echo form_open('home/upload');
		?>
		
		<input id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
		
		<?php
		echo form_submit('upload', 'Share!');
		echo form_close();

		echo anchor('home/play', 'เล่นใหม่');
	?>
</center>
</body>
</html>