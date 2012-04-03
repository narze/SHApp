<html>
<head>
	<title></title>
</head>
<body>
	<?php
		echo img($image_url);
		echo form_open('home/upload');
		?>
		 <li>
				<input id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
			  </li>
		<?php
		echo form_submit('upload', 'Upload');
		echo form_close();

		echo anchor('home/play', 'Play again');
	?>
</body>
</html>