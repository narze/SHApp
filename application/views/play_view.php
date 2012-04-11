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
	.tag-pic { cursor:pointer; position: absolute; overflow: hidden; border:1px solid #ccc; width:<?php echo $img_size;?>px; height:<?php echo $img_size;?>px; left:<?php echo $img_x;?>px; top:<?php echo $img_y;?>px; }
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
		cursor:pointer;
	}
	.my-name {
		font-weight:bold;
		position: absolute;
		top: 311px;
		left: 83px;
	}
	.my-friend-name {
		font-weight:bold;
		position: absolute;
		top:350px;
		left:83px;
	}
	</style>

	<link rel="stylesheet" href="<?php echo base_url('assets/js/jquery.facebook.multifriend.select-list.css');?>" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.facebook.multifriend.select.min.js';?>"></script>

	<?php $this->load->view('ga'); ?>
</head>
<body <?php echo $app_bgcolor ? 'style="background-color:'.$app_bgcolor.';"' : ''; ?>>
	<?php echo $fb_root;?>
	<center>
		<div class="img-box">
			<?php echo img($image_url); ?>
			<div class="tag-pic" id="img_1"></div>
			<div class="my-name"></div>
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
			<?php echo form_open('tag/execute', array('class'=>'form-inline', 'id'=>'tag_form')); ?>
			<input name="img_name" type="hidden" value="<?php echo $img_name;?>" />
			<input id="my_friend_name" name="my_friend_name" type="hidden" value="" />
			<input id="tagged" type="hidden" name="tagged" value="<?php echo set_value('tagged'); ?>"  />
			<input style="height:37px;" class="input-large" id="message" type="text" name="message" maxlength="255" value="" <?php echo form_error('message') ? 'class="form-error"':''; ?> placeholder="Message" />
			<button id="submit_form" type="submit" class="btn btn-primary btn-large" name="upload">Share!</button>
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

    <script>
       
        function fbcallback(){
                jfmfs_init();
        }
         
        function jfmfs_init() {
            FB.api('/me', function(response) {
            	//console.log(response);
            	$('.my-name').text(response.name);
                $("#jfmfs-container").jfmfs({ max_selected: 1, max_selected_message: "{0} of {1} selected"});
                $("#loading").hide();
                //$(".friends-selector-popup").show();
                open_friends_list();
            });
        }

        function open_friends_list() {	
            var idStr = $('#img_1').attr('id');
            var imgNo = idStr.charAt( idStr.length-1 );
            bind_jfmfs(imgNo);
            $('.friends-selector-popup').show();
        }

        function getSelectedIds() {
            var img = [];
            img[0] = $('#img_1').data('facebookId');
            return img;
        }

        function bind_jfmfs(imgNo) {            
                $("body").off('click',"#jfmfs-container").on('click',"#jfmfs-container", function(event) {
                    // console.log($(this).data('jfmfs'));
                    $("#tagged").val($(this).data('jfmfs').getSelectedIds().join(','));
                    $(this).data('jfmfs').clearSelected();
                });
                $("body").off('click','#jfmfs-container .jfmfs-friend')
                    .on('click','#jfmfs-container .jfmfs-friend', function(event){
                    var facebookId = $(this).attr('id');

                    //var duplicated = getSelectedIds().join(',').indexOf(facebookId) != -1;
                    //if(!duplicated) {
                        $('#img_'+imgNo).data('facebookId',facebookId).html($('<img/>').attr('src', 'https://graph.facebook.com/'+facebookId+'/picture?type=<?php echo $profile_image_type; ?>'));
                        $('.friends-selector-popup').hide();
                        $('.my-friend-name').text($(this).find('.friend-name').text());
                        $('#my_friend_name').val($(this).find('.friend-name').text());
                    //} else {
                        //alert('ไม่สามารถแท็กคนซ้ำได้');
                    //}
                });

                $(".friends-selector-popup").hover(
                  function () {
                    $(this).addClass("hover");
                  },
                  function () {
                    $(this).removeClass("hover");
                  }
                );
                function hidePopupEvent() {
                    var fsPopup = $('.friends-selector-popup');
                    var popupVisible = fsPopup.is(':visible');
                    if(popupVisible && !fsPopup.hasClass('hover')){
                        fsPopup.hide();
                        $("body").unbind('mouseup');
                    } else if(popupVisible) {
                        $("body").one('mouseup', hidePopupEvent);
                    }
                }
                $("body").one('mouseup', hidePopupEvent);
        }

        $(function(){
            $("body").on('click',"#img_1", function(){
                open_friends_list();
            });
            $('#submit_form').click(function () {
            	$('#share_form').hide();
            	$('#progress_bar').show();
            });
        });
    </script>
</body>
</html>