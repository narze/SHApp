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
	<title>Welcome to CodeIgniter</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/responsive.css">
</head>
<body>
	<!--<div class="container hero-unit">
		<h1>Persuade for signup</h1>
		<h2><a href="">Continue permission then sign up</a></h2>
		<h3><a href="<?php echo base_url()?>welcome/play_app_trigger/?app_data=<?php echo $app_data; ?>">After Signup -> Redirect to Call Play App and go to Port_view</a></h3>
	</div>
	<div class="alert alert-info span12">
		<span class="label label-info">Info</span>
		Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT == 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
	</div>-->
	<div style="width:100%">
		<div style="background:url('<?php echo base_url()?>assets/images/header.png');width:810px;height:300px;margin:0 auto">
			<div class="progress-signup" style="position:absolute;cursor:pointer;display:inline-block;margin-top:210px;margin-left:568px;width:179px;height:64px;"></div>
		</div>
		<div style="background:url('<?php echo base_url()?>assets/images/content1.png');width:810px;height:687px;margin:0 auto">
		</div>
		<div style="background:url('<?php echo base_url()?>assets/images/footer.png');width:810px;height:250px;margin:0 auto">
			<div style="display:block;padding-top:121px;">
				<div class="link-page" style="position:absolute;cursor:pointer;display:inline-block;margin-left:176px;width:177px;height:47px;"></div>
				<div class="progress-signup" style="position:absolute;cursor:pointer;display:inline-block;margin-left:464px;width:177px;height:47px;"></div>
			</div>
		</div>
		<div class="signup-form" style="z-index:1000;width:100%;
			display: block;	position:absolute; display:none;">
			<div class="signup-form-wrapper" style="background:#fff;width:350px;padding:15px;margin:0 auto;">
				<div style="height:30px;">
					<span style="width:100px;display: inline-block;">Email : </span>
					<span style="width:200px;display: inline-block;"><input type="text" name="email" id="input-email" / ></span>
				</div>
				<div style="height:30px;">
					<span style="width:100px;display: inline-block;">Password : </span>
					<span style="width:200px;display: inline-block;"><input type="password" name="password" id="input-password" / ></span>
				</div>
				<div style="height:30px;">
					<span style="width:100px;display: inline-block;"></span>
					<span style="width:200px;display: inline-block;"><button id="submit-signup">Sign Up</button></span>
				</div>
				<div class ="signup-result" style="display:none;height:30px;">

				</div>
			</div>
		</div>
	</div>

	<?php echo $fb_root;?>
	
	<div class="box-overlay" style="z-index:100;display: none;position: absolute;	top:0;	left:0;	width:100%;	height:1500px;	background-color: transparent;	background-color: rgba(200, 200, 200, 0.6);	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99FFFFFF,endColorstr=#99FFFFFF);	zoom: 1;"></div>

	<script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

	<script>	
		var user_facebook_id = 0;

		jQuery(document).ready(function(){
			jQuery('.link-page').click(function(){
				window.top.location = 'http://www.facebook.com/socialhappen';
			});

			jQuery('.progress-signup').click(function(){
				//check fb permission status to app
				if(user_facebook_id==0){
					//if no permission -> show permission request -> then redirect back to this page again
					fblogin();
				}else{
					//else -> show signup-form
					show_signup_form();
				}

			});

			jQuery('.box-overlay').click(function(){
				hide_signup_form();
			});

			jQuery('#submit-signup').click(function(){
				var email = jQuery('#input-email').val();
				var password = jQuery('#input-password').val();

				var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  				if(regex.test(email) && password != ''){
					jQuery.ajax({
						url: '<?php echo base_url(); ?>welcome/signup_trigger',
						type: "POST",
						data: {
							app_data : '<?php echo $app_data; ?>',
							email: email,
							password: password
						},
						dataType: "json",
						success:function(data){
							console.log(data);
							if(data.result=='ok'){
								//redirect to play_app_trigger
								jQuery('.signup-result').html('Sign Up Successful <a href="<?php echo base_url()?>welcome/play_app_trigger?app_data=<?php echo $app_data; ?>">Continue</a>');
								jQuery('.signup-result').show('slow');
							}else{
								jQuery('.signup-result').html('Sign Up Failed: ' + data.message + ' <a href="<?php echo base_url()?>welcome/play_app_trigger?app_data=<?php echo $app_data; ?>">Continue</a>');
								jQuery('.signup-result').show('slow');
							}
						}
					});
				}
				
			});

		});
	
		function fbcallback(data){
			console.log(data.authResponse.userID);
			user_facebook_id = data.authResponse.userID;
			show_signup_form();
		}
		
		function show_signup_form(){
			jQuery('.box-overlay').show('fast');
			var windowY = window.scrollY;
			var top = 300;
			jQuery('.signup-form').css('top', windowY+top);
			jQuery('.signup-form').show('slow');

		}

		function hide_signup_form(){
			jQuery('.signup-form').hide('fast');
			jQuery('.box-overlay').hide('slow');

		}
	</script>
</body>
</html>