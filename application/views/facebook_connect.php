<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $app_title; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php $this->load->view('ga'); ?>
	</head>
	<body>
		<?php echo $fb_root;?>
		<center><a id="fblogin" onclick="fblogin();" style="cursor:pointer;"><img src="<?php echo base_url('assets/images/start.gif');?>" /></a></center>
	</body>
</html>
