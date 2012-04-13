<html>
<head>
	<title><?php echo $app_title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if($static_server_enable) { ?>
	<link rel="stylesheet" href="<?php echo $static_server_path.'css/bootstrap.css';?>" />
	<?php } else { ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
	<?php } ?>
	<style type="text/css">
	.img-box { width:403px;height:403px;margin-bottom:20px;position:relative; }
	.tag-pic { cursor:pointer; position: absolute; overflow: hidden; background-color:#fbfbfb; border:1px solid #ccc; width:<?php echo $img_size;?>px; height:<?php echo $img_size;?>px; left:<?php echo $img_x;?>px; top:<?php echo $img_y;?>px; }
    .tag-pic img {min-width:<?php echo $img_size;?>px;}
	.friends-selector-popup {
		display:none;
		position: absolute;
		top:50px;
		left:87px;
		background-color: #fff;
		min-height:280px;
		padding:10px;
		-webkit-border-radius:4px;
		-moz-border-radius:4px;
		border-radius:4px;
	}
	.my-name {
		font-weight:bold;
		position: absolute;
		top: <?php echo $randomapp_settings['text_1_y']; ?>px;
		left: <?php echo $randomapp_settings['text_1_x']; ?>px;
	}
	.my-friend-name {
		font-weight:bold;
		position: absolute;
		top:<?php echo $randomapp_settings['text_2_y']; ?>px;
		left:<?php echo $randomapp_settings['text_2_x']; ?>px;
	}
    .jfmfs-friend {cursor: pointer;}
    .jfmfs-friend:hover {background-color:#fbfbfb;}
	</style>

	<link rel="stylesheet" href="<?php echo base_url('assets/js/jquery.facebook.multifriend.select-list.css');?>" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.facebook.multifriend.select.min.js';?>"></script>

	<?php $this->load->view('ga'); ?>
</head>
<body <?php echo $app_bgcolor ? 'style="background-color:'.$app_bgcolor.';"' : ''; ?>>
	<?php echo $fb_root;?>
	<center>
		<div class="img-box">
			<?php echo img($image_url); ?>
			<div class="tag-pic" id="img_1"></div>
			<div class="my-name"><?php echo $my_name?></div>
			<div class="my-friend-name"></div>

			<div class="friends-selector-popup">
				<div id="loading" style="">
				<!-- Loading Friend List -->
				</div>
				<div>
		    		<div id="jfmfs-container"></div>
				</div>
		    </div>
		</div>

		<div id="share_form">
			<?php echo form_open('home/execute', array('class'=>'form-inline', 'id'=>'tag_form')); ?>
			<input name="img_name" type="hidden" value="<?php echo $img_name;?>" />
			<input id="my_friend_name" name="my_friend_name" type="hidden" value="" />
			<input id="tagged" type="hidden" name="tagged" value="<?php echo set_value('tagged'); ?>"  />
			<input style="height:37px;" class="input-large" id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
            <button id="select_friend" type="button" class="btn btn-large">เลือกเพื่อน</button>
			<button id="submit_form" type="submit" class="btn btn-primary btn-large" name="upload" style="display:none;">Share!</button>
			<?php echo form_close();
			echo form_open('home/play'); ?>
			<button type="submit" class="btn" name="upload">เล่นใหม่</button>
			<?php echo form_close(); ?>
		</div>


		<div id="progress_bar" style="display:none;">
			<p>Loading...</p>
			<div class="progress progress-striped progress-info active span4 offset3">
				<div class="bar" style="width: 100%;"></div>
			</div>
		</div>
	</center>

    <script type="text/javascript">
          
        function fbcallback(){
            $("#jfmfs-container").jfmfs({ max_selected: 1, max_selected_message: "{0} of {1} selected"});
        }

        $(document).ready(function() {
            
            function bind_jfmfs() {            
                $("body").off('click','#jfmfs-container .jfmfs-friend').on('click','#jfmfs-container .jfmfs-friend', function(event) 
                {
                    var facebookId = $(this).attr('id');

                    $('#img_1').data('facebookId',facebookId).html($('<img/>').attr('src', 'https://graph.facebook.com/'+facebookId+'/picture?type=<?php echo $profile_image_type; ?>'));
                    $("#tagged").val(facebookId);
                    $('.friends-selector-popup').hide();
                    $('.my-friend-name').text($(this).find('.friend-name').text());
                    $('#my_friend_name').val($(this).find('.friend-name').text());
                    $('#select_friend').hide();
                    $('#submit_form').show();
                }); 
            }

            $('#select_friend, .tag-pic').click(function () {
                bind_jfmfs();
                $('.friends-selector-popup').show();
            });

            $('#tag_form').submit(function () {
                $('#share_form').hide();
                $('#progress_bar').show();
            });
            
        });
    </script>
</body>
</html>