<html>
<head>
	<title><?php echo $app_title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
	<?php $this->load->view('ga'); ?>
</head>
<body style="<?php echo $app_bgcolor ? 'background-color:'.$app_bgcolor.';' : ''; ?>">
	<center>
		<div class="alert alert-success" style="margin-top:5px;margin-left:5px;margin-right:5px;">ทำการแชร์ภาพเรียบร้อยแล้ว</div>
		<?php
			echo '<p><a style="cursor: pointer;" class="btn btn-danger btn-large" onclick="top.location=\''.$facebook_link.'\';">คลิกเพื่อดูภาพของคุณ</a></p>';
			echo '<p>'.anchor('home', 'เล่นใหม่', 'class="btn btn-large"').'</p>';
		?>
		<div>
			<p>&nbsp;</p>
			<p>
				<a href="<?php echo $redirect_url;?>"><img src="<?php echo $static_server_enable ? $static_server_path.'images/go-to-socialhappen.gif' : base_url('assets/images/go-to-socialhappen.gif'); ?>" /></a>
			</p>
			<p>
				<a target="_blank" href="<?php echo base_url('privacy_policy');?>">Privacy Policy</a> |
				<a target="_blank" href="<?php echo base_url('terms_of_service');?>">Terms of Service</a>
			</p>
		</div>

	</center>
</body>
</html>