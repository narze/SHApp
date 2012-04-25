<html>
<head>
	<title><?php echo $app_title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if($static_server_enable) { ?>
	<link rel="stylesheet" href="<?php echo $static_server_path.'css/bootstrap.css';?>" />
	<?php } else { ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
	<?php } ?>
	<style type="text/css">
	.img-box { width:403px;height:403px;margin-bottom:20px;position:relative; }
	.user-profile { position: absolute; overflow: hidden; border:3px solid #fff; width:<?php echo $img_size;?>px; height:<?php echo $img_size;?>px; left:<?php echo $img_x;?>px; top:<?php echo $img_y;?>px; }
	.user-profile img { width:<?php echo $img_size;?>px; height:<?php echo $img_size;?>px; }
	</style>
	<?php $this->load->view('ga'); ?>
</head>
<body <?php echo $app_bgcolor ? 'style="background-color:'.$app_bgcolor.';"' : ''; ?>>
	<center>
		<div class="img-box">
			<?php echo img($image_url); ?>
			<div class="user-profile"><img src="https://graph.facebook.com/<?php echo $facebook_uid;?>/picture?type=<?php echo $profile_image_type ?>" /></div>
		</div>

		<div id="share_form">
			<?php echo form_open('index.php/home/upload', array('class'=>'form-inline')); ?>
			<input type="hidden" name="img_name" value="<?php echo $img_name;?>" />
			<input style="height:37px;" class="input-large" id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
			<button id="share_button" type="submit" class="btn btn-danger btn-large" name="upload">Share!</button>
			<?php echo form_close();
			echo form_open('index.php/home/play'); ?>
			<input type="hidden" name="img_name" value="<?php echo $img_name;?>" />
			<button type="submit" class="btn" name="upload">เล่นใหม่</button>
			<?php echo form_close(); ?>
		</div>

		<div id="progress_bar" style="display:none;">
			<p>Loading...</p>
			<div class="progress progress-striped progress-info active span4 offset3">
				<div class="bar" style="width: 100%;"></div>
			</div>
		</div>

		<div>
			<p>
				<a target="_blank" href="<?php echo base_url('privacy_policy');?>">Privacy Policy</a> | 
				<a target="_blank" href="<?php echo base_url('terms_of_service');?>">Terms of Service</a>
			</p>
		</div>
		<div>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>
				<a href="<?php echo $this->config->item('static_app_url');?>"><img src="<?php echo base_url('assets/images/go-to-socialhappen.gif');?>" /></a>
			</p>
		</div>
	</center>
	<script type="text/javascript">
		document.getElementById("share_button").addEventListener("click", shareToFB);
		function shareToFB () {
			document.getElementById('share_form').style.display = 'none';
			document.getElementById('progress_bar').style.display = 'block';
		}
	</script>
</body>
</html>