<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $app_title; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php $this->load->view('ga'); ?>
	</head>
	<body <?php echo $app_bgcolor ? 'style="background-color:'.$app_bgcolor.';"' : ''; ?>>
		<?php echo $fb_root;?>
		<center>
			<a id="fblogin" onclick="fblogin();" style="cursor:pointer;"><img src="<?php echo $static_server_enable ? $static_server_path.'images/start.jpg' : base_url('assets/images/start.jpg'); ?>" /></a>
			<div style="margin-top:50px;">
				<a style="color:#555;" target="_blank" href="<?php echo base_url('privacy_policy');?>">Privacy Policy</a> | 
				<a style="color:#555;" target="_blank" href="<?php echo base_url('terms_of_service');?>">Terms of Service</a>
			</div>
		</center>
	</body>
</html>
