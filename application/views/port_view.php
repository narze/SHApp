<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * NOTICE OF LICENSE
 * 
 * Licensed under the Academic Free License version 3.0
 * 
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SocialHappen</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/responsive.css">
	<?php $this->load->view('ga'); ?>
</head>
<body>
	<!--<div class="container hero-unit">
		<h1>Port View</h1>
	</div>
	<div class="alert alert-info span12">
		<span class="label label-info">Info</span>
		Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT == 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
	</div>-->

	<div style="width:100%">
		<div class="alert alert-success" style="margin-top:15px;">คุณได้รับคะแนนจาก SocialHappen แล้ว 50 แต้ม!</div>
		<div style="background:url('<?php echo base_url()?>assets/images/header-blank.png');width:810px;height:300px;margin:0 auto">
		</div>
		<div style="background:url('<?php echo base_url()?>assets/images/box-header1.png');width:810px;height:55px;margin:0 auto">
		</div>
		<div style="background:url('<?php echo base_url()?>assets/images/box-sub-header1.png');width:810px;height:72px;margin:0 auto">
		</div>
		<div style="background:url('<?php echo base_url()?>assets/images/box-sub-content1.png');width:810px;height:233px;margin:0 auto">
			<div style="display:block;">
					<div class="show" data-direction="prev" style="position:absolute;cursor:pointer;display:inline-block;margin-top:81px;margin-left:36px;width:22px;height:38px;"></div>
					<div class="item" data-number="1" style="position:absolute;cursor:pointer;display:inline-block;margin-top:11px;margin-left:76px;width:212px;height:213px;"></div>
					<div class="item" data-number="2" style="position:absolute;cursor:pointer;display:inline-block;margin-top:11px;margin-left:302px;width:212px;height:213px;"></div>
					<div class="item" data-number="3" style="background:#fff;position:absolute;display:inline-block;margin-top:11px;margin-left:529px;width:212px;height:213px;"></div>
					<div class="show" data-direction="next" style="position:absolute;cursor:pointer;display:inline-block;margin-top:81px;margin-left:759px;width:22px;height:38px;"></div>
			</div>

		</div>
		<div style="background:url('<?php echo base_url()?>assets/images/box-sub-footer1.png');width:810px;height:26px;margin:0 auto">
		</div>
		<div style="background:url('<?php echo base_url()?>assets/images/footer-blank.png');width:810px;height:250px;margin:0 auto">
		</div>

	</div>

	<script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	<script>
		var item_url = new Array();
		item_url[1] = 'https://www.facebook.com/SocialHappen/app_125984734199028';
		item_url[2] = 'https://www.facebook.com/SocialHappen/app_299915470082039';
		item_url[3] = 'https://www.facebook.com/SocialHappen/app_299915470082039';
		
		/*item_url[1] = 'https://app2.socialhappen.com/ghost';
		item_url[2] = 'https://apps.socialhappen.com/songkran';
		item_url[3] = 'https://app2.socialhappen,com/';*/

		jQuery(document).ready(function(){
			jQuery('.show').click(function(){
				var direction = jQuery(this).attr('data-direction');
				console.log(direction);
			});

			jQuery('.item').click(function(){
				var number = jQuery(this).attr('data-number');
				console.log(number);

				window.top.location = item_url[number];
			});

		});
	</script>
</body>
</html>