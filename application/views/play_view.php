<html>
<head>
	<title><?php echo $app_title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
	<style type="text/css">
	.img-box { width:403px;height:403px;margin-bottom:20px;position:relative; }
	.user-profile { position: absolute; overflow: hidden; border:<?php echo $profile_image_border; ?>px solid <?php echo $profile_image_border_color; ?>; width:<?php echo $img_width;?>px; height:<?php echo $img_height;?>px; left:<?php echo $img_x;?>px; top:<?php echo $img_y;?>px; background-size:cover;}
	.user-name { font-size: <?php echo $profile_name_size;?>px; text-align: left; position: absolute; width: 200px; left:<?php echo $profile_name_x;?>px; top:<?php echo $profile_name_y;?>px; color: <?php echo $profile_name_color; ?>}
	<?php if($image_scores_enable) : ?>
		.score { position: absolute; overflow: hidden; font-size:20px ; left:<?php echo $score_x;?>px; top:<?php echo $score_y+7;?>px; }
		.star { position: absolute; overflow: hidden; left:<?php echo $score_x - 15;?>px; top:<?php echo $score_y - 30;?>px; }
	<?php endif; ?>
	</style>
	<?php $this->load->view('ga'); ?>
</head>
<body <?php echo $app_bgcolor ? 'style="background-color:'.$app_bgcolor.';"' : ''; ?>>

	<center>
		<?php if($maximum_times_reached) : ?>
			<div class="alert alert-error" style="margin-top:5px;margin-left:5px;margin-right:5px;">ทุก <?php echo $cooldown_hours;?> ชั่วโมง สามารถแชร์ภาพได้ <?php echo $maximum_times_played;?> ภาพนะจ๊ะ</div>
		<?php endif ;?>
		<div class="img-box">

			<?php if($random_image_as_background) { echo "<img src=\"$image_url\" style=\"position: absolute; top:0; left:0\" />\n"; } ?>

			<?php if(isset($profile_picture_url)) :?>
				<div class="user-profile" style="background-image:url(<?php echo $profile_picture_url;?>)"></div>
			<?php else : ?>
				<div class="user-profile" style="background-image:url(https://graph.facebook.com/<?php echo $facebook_uid;?>/picture?type=<?php echo $profile_image_type ?>);"></div>
			<?php endif; ?>

			<?php if(!$random_image_as_background) { echo "<img src=\"$image_url\" style=\"position: absolute; top:0; left:0\" />\n"; } ?>

			<div class="user-name"><?php echo $name; ?></div>

			<?php if($image_scores_enable) : ?>
				<img class="star" src="<?php echo base_url('assets/images/star.png');?>"></img>
				<div class="score"><?php echo $score;?></div>
			<?php endif; ?>
		</div>

		<div id="share_form">
			<?php
				echo form_open('home/upload', array('class'=>'form-inline')); ?>
					<input type="hidden" name="img_name" value="<?php echo $img_name;?>" />
					<?php if($image_scores_enable) : ?>
						<input type="hidden" name="img_score" value="<?php echo $score;?>" />
					<?php endif; ?>
					<?php if($maximum_times_reached) : ?>
						<input disabled="true" style="height:37px;" class="input-large" id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
						<button disabled="true" id="share_button" type="submit" class="btn btn-danger btn-large" name="upload">Share!</button>
					<?php else : ?>
						<input style="height:37px;" class="input-large" id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
						<button id="share_button" type="submit" class="btn btn-danger btn-large" name="upload">Share!</button>
					<?php endif;
				echo form_close();

			echo form_open('home'); ?>
			<input type="hidden" name="img_name" value="<?php echo $img_name;?>" />
			<button type="submit" class="btn" name="upload">เล่นใหม่</button>
			<?php echo form_close(); ?>
		</div>

		<div id="progress_bar" style="height:101px;width:403px;margin:0 auto;display:none;">
			<p>Loading...</p>
			<div class="progress progress-striped progress-info active">
				<div class="bar" style="width: 100%;"></div>
			</div>
		</div>

		<div>
			<p>
				<a href="<?php echo $this->config->item('static_app_url');?>"><img src="<?php echo $static_server_enable ? $static_server_path.'images/go-to-socialhappen.gif' : base_url('assets/images/go-to-socialhappen.gif'); ?>" /></a>
			</p>
			<p>
				<a target="_blank" href="<?php echo base_url('privacy_policy');?>">Privacy Policy</a> |
				<a target="_blank" href="<?php echo base_url('terms_of_service');?>">Terms of Service</a>
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