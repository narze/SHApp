<html>
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/responsive.css');?>" />
</head>
<body style="background-color:black">
	<center>
		<p>&nbsp;</p>
		<?php	
			echo '<p>'.anchor($facebook_link, 'ดูภาพของคุณ', 'class="btn btn-danger"').'</p>';
			echo '<p>'.anchor('home/play', 'เล่นใหม่', 'class="btn"').'</p>';
		?>
	</center>
</body>
</html>