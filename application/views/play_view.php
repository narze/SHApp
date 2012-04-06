<html>
<head>
	<title>ล่า ท้า ผี...คุณจะเจอผีอะไร? by SocialHappen</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
  <link rel="stylesheet" href="<?php echo base_url('assets/css/responsive.css');?>" />
  <style type="text/css">
  	.user-profile {
  		width:<?php echo $img_size;?>px;
  		height:<?php echo $img_size;?>px;
  		position: absolute;
  		left:<?php echo $img_x;?>px;
  		top:<?php echo $img_y;?>px;
  	}
  </style>
	<?php $this->load->view('ga'); ?>
</head>
<body style="background-color:black">
	<center>
		<?php
		echo '<p style="width:403px;height:403px;position:relative;">'.img($image_url);
		echo '<img class="user-profile" src="https://graph.facebook.com/'.$facebook_uid.'/picture" /></p>';
		echo form_open('home/upload', array('class'=>'form-inline'));
		?>
			<input type="hidden" name="img_name" value="<?php echo $img_name;?>" />
			<input style="height:28px;" id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
			<button type="submit" class="btn btn-danger" name="upload">Share!</button>
		<?php

		echo form_close();

		echo form_open('home/play');
		?>
			<input type="hidden" name="img_name" value="<?php echo $img_name;?>" />
			<button type="submit" class="btn" name="upload">เล่นใหม่</button>
		<?
		echo form_close();
	?>
	</center>
</body>
</html>