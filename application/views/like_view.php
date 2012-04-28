<html>
<head>
	<title><?php echo $app_title; ?></title>
	<?php $this->load->view('ga'); ?>
</head>
<body style="margin:0;<?php echo $app_bgcolor ? 'background-color:'.$app_bgcolor.';' : ''; ?>">
	<img src="<?php echo $static_server_enable ? $static_server_path.'images/like.jpg' : base_url('assets/images/like.jpg'); ?>" />
</body>
</html>