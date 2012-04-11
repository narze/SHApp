<html>
<head>
	<title><?php echo $app_title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if($static_server_enable) { ?>
	<link rel="stylesheet" href="<?php echo $static_server_path.'css/bootstrap.css';?>" />
	<?php } else { ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
	<?php } 
	$this->load->view('ga'); ?>
</head>
<body style="padding-top:50px;<?php echo $app_bgcolor ? 'background-color:'.$app_bgcolor.';' : ''; ?>">
	<center>
		<?php
			echo '<p><a style="cursor: pointer;" class="btn btn-danger btn-large" onclick="top.location=\''.$facebook_link.'\';">ดูภาพของคุณ</a></p>';
			echo '<p>'.anchor('home/play', 'เล่นใหม่', 'class="btn btn-large"').'</p>';
		?>
	</center>
</body>
</html>