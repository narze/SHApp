<html>
<head>
	<title><?php echo $app_title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
	<style type="text/css">
	.img-box { width:403px;height:403px;margin-bottom:20px;position:relative; }
	.user-profile { position: absolute; overflow: hidden; width:<?php echo $img_size;?>px; height:<?php echo $img_size;?>px; left:<?php echo $img_x;?>px; top:<?php echo $img_y;?>px; }
	</style>
	<?php $this->load->view('ga'); ?>
</head>
<body>
	<center>
		<div class="img-box">
			<?php echo img($image_url); ?>
			<div class="user-profile"><img src="https://graph.facebook.com/<?php echo $facebook_uid;?>/picture?type=<?php echo $profile_image_type ?>" /></div>
		</div><?php
		echo form_open('home/upload', array('class'=>'form-inline')); ?>
			<input type="hidden" name="img_name" value="<?php echo $img_name;?>" />
			<input style="height:37px;" class="input-large" id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
			<button type="submit" class="btn btn-primary btn-large" name="upload">Share!</button>
		<?php echo form_close();
		echo form_open('home/play'); ?>
			<input type="hidden" name="img_name" value="<?php echo $img_name;?>" />
			<button type="submit" class="btn" name="upload">เล่นใหม่</button>
		<?php echo form_close(); ?>
	</center>
</body>
</html>