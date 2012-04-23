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
</head>
<body>
	<?php echo $fb_root;?>
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
		<?php if(isset($app_data['data']['message']) && isset($app_data['data']['link'])) : ?>
			<div class="alert alert-success" style="margin-top:15px;"><a target="_blank" href="<?php echo $app_data['data']['link'];?>"><?php echo $app_data['data']['message'];?></a></div>
		<?php endif;?>
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

		<div class="popup-container" style="z-index:1000;width:100%;display:none;position:absolute;">

			<div class="form-horizontal signup-form" style="background:#fff;width:455px;margin:0 auto;padding:0 15px">

				<legend>Sign up</legend>

				<?php if(isset($facebook_image) && isset($facebook_image)) :?>
					<div class="control-group" style="margin-bottom:0;">
						<label class="control-label"><img src="<?php echo $facebook_image;?>" alt="" style="background-color:#ccc;width:50px;height:50px;"></label>
						<div class="controls">
							<p style="padding-top:20px;"><b><?php echo $facebook_name;?></b></p>
						</div>
					</div>
				<?php endif; ?>

				<div class="control-group">
					<label class="control-label" for="input01">Email</label>
					<div class="controls">
						<input type="text" class="input-xlarge" name="email" id="input-email" / >
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="input01">Password</label>
					<div class="controls">
						<input type="password" class="input-xlarge" name="password" id="input-password" / >
					</div>
				</div>

				<div class="form-actions">
					<button class="btn btn-primary" id="submit-signup">Sign Up</button>
				</div>

				

			</div>

			<div id="progress_bar" style="margin:0 auto;width:400px;display:none;">
				<p style="text-align:center">Loading...</p>
				<div class="progress progress-striped progress-info active">
					<div class="bar" style="width: 100%;"></div>
				</div>
			</div>

			<div class="signup-result alert" style="margin:0 auto;width:400px;display:none;"></div>

		</div>

	</div>
	
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
  					$('.signup-form').hide();
  					$('#progress_bar').show();
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
							$('#progress_bar').hide();
							if(data.result=='ok'){
								//redirect to play_app_trigger
								jQuery('.signup-result').addClass('alert-success').html('Sign Up Successful <a href="<?php echo base_url()?>welcome/play_app_trigger?app_data=<?php echo $app_data; ?>">Continue</a>');
								jQuery('.signup-result').show('slow');
							}else{
								jQuery('.signup-result').addClass('alert-error').html('Sign Up Failed: ' + data.message + ' <a href="<?php echo base_url()?>welcome/play_app_trigger?app_data=<?php echo $app_data; ?>">Continue</a>');
								jQuery('.signup-result').show('slow');
							}
						}
					});
				}
				//return false;
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
			jQuery('.popup-container').css('top', windowY+top);
			jQuery('.popup-container').show('slow');

		}

		function hide_signup_form(){
			jQuery('.popup-container').hide('fast');
			jQuery('.box-overlay').hide('slow');

		}
	</script>
</body>
</html>