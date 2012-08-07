<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->signedRequest = $this->facebook->getSignedRequest();
		$this->facebook_page_id = $this->config->item('mockuphappen_facebook_page_id');
		$this->facebook_app_id = $this->config->item('facebook_app_id');
		$this->cookie_times_played = $this->facebook_page_id.'_'.$this->facebook_app_id.'_times_played';
		$this->cookie_full_name = $this->facebook_page_id.'_'.$this->facebook_app_id.'_full_name';
		$this->cookie_profile_picture = $this->facebook_page_id.'_'.$this->facebook_app_id.'_profile_picture_url';
		$this->cookie_gender = $this->facebook_page_id.'_'.$this->facebook_app_id.'_gender';
	}

  /**
   * Check like in javascript
   */
	function index(){
		$this->_in_page_tab_check();
	  $this->load->view('check_view', array(
	  	'facebook_app_scope' => $this->config->item('facebook_app_scope'),
	  	'static_server_enable' => $this->config->item('static_server_enable'),
			'static_server_path' => $this->config->item('static_server_path')
	  ));
	}

  /**
   * fallback when javascript error occur
   */
  function check(){
		if((!$facebook_uid = $this->facebook->getUser())
			|| !$this->fb->hasPermissions()){
			$this->_login();
		} else if ($this->fb->isUserLikedPage($this->facebook_page_id)){
			$this->play();
		} else {
			$this->_force_like();
		}
  }

	/**
	 * If not in page tab, or not liked, redirect to page tab
	 */
	function _in_page_tab_check() {
		if(!isset($this->signedRequest['page']['id'])
			|| $this->signedRequest['page']['id'] != $this->facebook_page_id) {
			echo '<script>top.location = "'."https://www.facebook.com/profile.php?id={$this->facebook_page_id}&sk=app_{$this->facebook_app_id}".'";</script>';
			exit();
		}
	}

	/**
	 * Forces user to like facebook page by redirecting to facebook page tab
	 */
	function _force_like() {
		if(!$facebook_uid = $this->facebook->getUser()) {
			redirect();
		} else {
			$this->signedRequest = $this->facebook->getSignedRequest();
			//if not have signed request or page mismatch
			if(!isset($this->signedRequest['page']['id'])
				|| ($this->signedRequest['page']['id'] != $this->facebook_page_id)) {
				if($facebook_force_like_app_id = $this->config->item('facebook_force_like_app_id')) {
					$this->facebook_app_id = $facebook_force_like_app_id;
				}
				echo '<script>top.location = "'."https://www.facebook.com/profile.php?id={$this->facebook_page_id}&sk=app_{$this->facebook_app_id}".'";</script>';
				return;
			} else if($this->facebook_app_id != $this->config->item('facebook_force_like_app_id')) {
				echo '<script>top.location = "'."https://www.facebook.com/profile.php?id={$this->facebook_page_id}&sk=app_{$this->facebook_app_id}".'";</script>';
				return;
			} else {
				$randomapp_settings = $this->config->item('randomapp_settings');
				$this->load->vars(array(
					'app_title' => $randomapp_settings['app_title'],
					'app_bgcolor' => $randomapp_settings['app_bgcolor'],
					'static_server_enable' => $this->config->item('static_server_enable'),
					'static_server_path' => $this->config->item('static_server_path')
				));
				$this->load->view('like_view');
			}
		}
	}

  /**
   * send random photo
   */
	function play() {
		if(!$facebook_uid = $this->facebook->getUser()) { // we dont't check page like here
			return $this->_login();
		}

		$randomapp_settings = $this->config->item('randomapp_settings');

		$times_played = $this->input->cookie($this->cookie_times_played);
		$maximum_times_played = isset($randomapp_settings['maximum_times_played']) ? $randomapp_settings['maximum_times_played'] : 0;

		if($times_played && $maximum_times_played && ($times_played >= $maximum_times_played)) {
			$maximum_times_reached = TRUE;
		} else {
			$maximum_times_reached = FALSE;
		}

		//Get big profile picture url from cookie, if cannot, get it from facebook
		if(!$profile_pic = $this->input->cookie($this->cookie_profile_picture)) {
			try {
				$fql = "SELECT src_big FROM photo WHERE pid IN (SELECT cover_pid FROM album WHERE owner = $facebook_uid AND type = 'profile')";
	      $response = $this->facebook->api(array(
					'method' => 'fql.query',
					'query' =>$fql,
				));
				if(isset($response[0]['src_big'])) {
		      $profile_pic = $response[0]['src_big'];
		    } else {
		    	exit('Facebook Error');
		    }

	      //Set cookie
				preg_match('/\/\/[^\/]*\//i', base_url(), $matches);
				$domain = trim($matches[0],'/');
				//Set cookie
				$cookie = array(
					'name' => $this->cookie_profile_picture,
					'value' => $profile_pic,
					'domain' => $domain,
					'expire' => $randomapp_settings['cooldown'],
					'path' => '/',
					'secure' => TRUE
				);
				$this->input->set_cookie($cookie);

	    } catch (FacebookApiException $e) {
	      exit('Facebook Error');
	    }
	  }

	  $gender = '';
	  //Get gender from cookie | facebook
	  if($randomapp_settings['gender_separate']) {
		  if(!$gender = $this->input->cookie($this->cookie_gender)) {
		  	try {
		  		$user = $this->facebook->api('me?fields=gender');
		  		if(isset($user['gender'])) {
		  			$gender = $user['gender'];
		  		} else {
		  			exit('Facebook Error');
		  		}

		  		//Set cookie
					preg_match('/\/\/[^\/]*\//i', base_url(), $matches);
					$domain = trim($matches[0],'/');
					//Set cookie
					$cookie = array(
						'name' => $this->cookie_gender,
						'value' => $gender,
						'domain' => $domain,
						'expire' => $randomapp_settings['cooldown'],
						'path' => '/',
						'secure' => TRUE
					);
					$this->input->set_cookie($cookie);

		  	} catch (FacebookApiException $e) {
		      exit('Facebook Error');
		    }
		  }
		  $gender = $gender . '_';
	  }

	  //Get name from cookie | facebook
	  $name = '';

	  //Add userdata
	  if($randomapp_settings['profile_name_enable']) {
  	  if(!$name = $this->input->cookie($this->cookie_full_name)) {
  	  	try {
  	  		$user = $this->facebook->api('me?fields=name');
  	  		if(isset($user['name'])) {
  	  			$name = $user['name'];
  	  		} else {
  	  			exit('Facebook Error');
  	  		}

  	  		//Set cookie
  				preg_match('/\/\/[^\/]*\//i', base_url(), $matches);
  				$domain = trim($matches[0],'/');
  				//Set cookie
  				$cookie = array(
  					'name' => $this->cookie_full_name,
  					'value' => $name,
  					'domain' => $domain,
  					'expire' => $randomapp_settings['cooldown'],
  					'path' => '/',
  					'secure' => TRUE
  				);
  				$this->input->set_cookie($cookie);

  	  	} catch (FacebookApiException $e) {
  	      exit('Facebook Error');
  	    }
  	  }
  	  $this->load->vars(array(
  	  	'profile_name_color' => $randomapp_settings['profile_name_color'],
  	  	'profile_name_size' => $randomapp_settings['profile_name_size']
  	  ));
	  }

	  //insert name


		$static_server_enable = $this->config->item('static_server_enable');
		$static_server_path = $this->config->item('static_server_path');

		if($static_server_enable) { //From static server
			$exclude_this_image = $this->input->post('img_name');

			//Exclude recent image
			do {
				$random_number = mt_rand(1, $randomapp_settings['max_ramdom_number']);
				$random_image_name = $gender.$random_number.'.'.$randomapp_settings['random_image_extension'];
			} while($random_image_name == $exclude_this_image);

			$random_image_url = $static_server_path.'images/random/'.$random_image_name;
		} else { //From local file
			$jpgs = glob(FCPATH.'assets/images/random/'.$gender.'*.jpg');
			$pngs = glob(FCPATH.'assets/images/random/'.$gender.'*.png');
			$gifs = glob(FCPATH.'assets/images/random/'.$gender.'*.gif');
			$images = array_merge($jpgs, $pngs, $gifs);

			//Exclude recent image if clicked form in play_view
			if(count($images) > 1 && ($exclude_this_image = $this->input->post('img_name'))) {
				$key = array_search(FCPATH.'assets/images/random/'.$exclude_this_image, $images);
				unset($images[$key]);
				$images = array_values($images); //Reindex
			}

			$random_image_path = $images[mt_rand(0, count($images)-1)];
			$random_image_name = pathinfo($random_image_path, PATHINFO_BASENAME);
			$random_image_url = base_url().'assets/images/random/'.$random_image_name;

			//Get random number to calculate score
			preg_match('/(\d+)\.(jpg|png|gif)/', $random_image_name, $matches);
			$random_number = $matches[1];
		}

		//Calculate score
		if($this->config->item('image_scores_enable')) {
			$image_scores = $this->config->item('image_scores');
			list ($score_low, $score_high) = $image_scores[$gender.$random_number];
			$score = mt_rand($score_low, $score_high) . '%';
			$this->load->vars(array(
				'score' => $score,
				'score_x' => $image_scores['position_x'],
				'score_y' => $image_scores['position_y']
			));
		}

		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->vars(array(
			'image_url' => $random_image_url,
			'img_name' => $random_image_name,
			'facebook_uid' => $facebook_uid,
			'img_x'=> $randomapp_settings['profile_image_x']-$randomapp_settings['profile_image_border'],
			'img_y'=> $randomapp_settings['profile_image_y']-$randomapp_settings['profile_image_border'],
			// 'img_size' => $randomapp_settings['profile_image_size'],
			'img_width' => $randomapp_settings['profile_image_width'],
			'img_height' => $randomapp_settings['profile_image_height'],
			'profile_name_x' => $randomapp_settings['profile_name_x'],
			'profile_name_y' => $randomapp_settings['profile_name_y'],
			'app_title' => $randomapp_settings['app_title'],
			'profile_image_type' => $randomapp_settings['profile_image_type'],
			'app_bgcolor' => $randomapp_settings['app_bgcolor'],
			'static_server_enable' => $static_server_enable,
			'static_server_path' => $static_server_path,
			'maximum_times_reached' => $maximum_times_reached,
			'cooldown_hours' => $randomapp_settings['cooldown'] / 60 / 60,
			'maximum_times_played' => $randomapp_settings['maximum_times_played'],
			'profile_image_border' => $randomapp_settings['profile_image_border'],
			'profile_image_border_color' => $randomapp_settings['profile_image_border_color'],
			'profile_picture_url' => $profile_pic,
			'random_image_as_background' => $randomapp_settings['random_image_as_background'],
			'image_scores_enable' => $this->config->item('image_scores_enable'),
			'name' => $name
		));
		$this->load->view('play_view');
	}

  /**
   * user submitted share button
   *
   * @TODO: render and add photo to upload queue instead of upload via PHP
   */
	function upload() {
	  /**
     * full check here
     */
		if((!$facebook_uid = $this->facebook->getUser())
			|| !$this->fb->isUserLikedPage($this->facebook_page_id)
			|| (!$random_image_name = $this->input->post('img_name'))
			|| ($this->config->item('image_scores_enable') && (!$random_image_score = $this->input->post('img_score')))) {
			redirect();
		}

		$custom_message = $this->input->post('message');
		if(is_array($custom_message)) {
			$custom_message = implode(" ", $custom_message);
		}

		$randomapp_settings = $this->config->item('randomapp_settings');

		$image_scores = $this->config->item('image_scores');

		$times_played = $this->input->cookie($this->cookie_times_played);
		$maximum_times_played = isset($randomapp_settings['maximum_times_played']) ? $randomapp_settings['maximum_times_played'] : 0;

		if($times_played && $maximum_times_played && ($times_played >= $maximum_times_played)) {
			redirect('home/play?maximum_times_reached=1');
		}

		$static_server_enable = $this->config->item('static_server_enable');
		$static_server_path = $this->config->item('static_server_path');

		// Get image from Local file
		$random_image_url = FCPATH.'assets/images/random/'.$random_image_name;
		if(!file_exists($random_image_url)) {
			exit('Image not found');
		}

		//Get big profile picture url from cookie, if cannot, get it from facebook
		if(!$profile_pic = $this->input->cookie($this->cookie_profile_picture)) {
			try {
				$fql = "SELECT src_big FROM photo WHERE pid IN (SELECT cover_pid FROM album WHERE owner = $facebook_uid AND type = 'profile')";
	      $response = $this->facebook->api(array(
					'method' => 'fql.query',
					'query' =>$fql,
				));
				if(isset($response[0]['src_big'])) {
		      $profile_pic = $response[0]['src_big'];
		    } else {
		    	exit('Facebook Error');
		    }

	      //Set cookie
				preg_match('/\/\/[^\/]*\//i', base_url(), $matches);
				$domain = trim($matches[0],'/');
				//Set cookie
				$cookie = array(
					'name' => 'profile_picture_url',
					'value' => $profile_pic,
					'domain' => $domain,
					'expire' => $randomapp_settings['cooldown'],
					'path' => '/',
					'prefix' => $this->facebook_page_id.'_'.$this->facebook_app_id.'_',
					'secure' => TRUE
				);
				$this->input->set_cookie($cookie);

	    } catch (FacebookApiException $e) {
	      exit('Facebook Error');
	    }
	  }

		//Prepare config
		$profile_image_width = $randomapp_settings['profile_image_width'];
		$profile_image_height = $randomapp_settings['profile_image_height'];
		$profile_image_x = $randomapp_settings['profile_image_x'];
		$profile_image_y = $randomapp_settings['profile_image_y'];
		$profile_image_type = $randomapp_settings['profile_image_type'];

		//Create Layers
		$layer0 = $this->_getImageResource($random_image_url);
		$layer1 = $this->_getImageResource($profile_pic, $profile_image_width, $profile_image_height);

		//Filename
		$filename = sha1('SaLt'.$facebook_uid.'TlAs');
		$image_path = FCPATH.'uploads/'.$filename.'.jpg';
		$image_url = base_url().'uploads/'.$filename.'.jpg';

		//Create canvas
		$finalImage_width = imagesx($layer0);
		$finalImage_height = imagesy($layer0);
		$finalImage = imagecreatetruecolor($finalImage_width,$finalImage_height);
		imagefill($finalImage, 0, 0, IMG_COLOR_TRANSPARENT);
		imagesavealpha($finalImage, true);
		imagealphablending($finalImage, true);


		//Merge profile picture
		if($randomapp_settings['profile_image_border']) { //Draw border
			$layer1 = $this->_drawBorder($layer1, $randomapp_settings['profile_image_border'], $randomapp_settings['profile_image_border_color']);
			$profile_image_x -= $randomapp_settings['profile_image_border'];
			$profile_image_y -= $randomapp_settings['profile_image_border'];
			$profile_image_width += ($randomapp_settings['profile_image_border']*2);
			$profile_image_height += ($randomapp_settings['profile_image_border']*2);
		}

		if($randomapp_settings['random_image_as_background']) { //BG = Random image
			imagecopy($finalImage, $layer0, 0, 0, 0, 0, $finalImage_width,$finalImage_height);
		}

		imagecopy($finalImage, $layer1, $profile_image_x, $profile_image_y, 0, 0, $profile_image_width, $profile_image_height);

		if(!$randomapp_settings['random_image_as_background']) { //BG = Random image
			imagecopy($finalImage, $layer0, 0, 0, 0, 0, $finalImage_width,$finalImage_height);
		}

		if($this->config->item('image_scores_enable')) {
			//Star image layer
			$layer2 = $this->_getImageResource(FCPATH . 'assets/images/star.png', 82, 85);
			imagecopy($finalImage, $layer2, $image_scores['position_x'] - 15, $image_scores['position_y'] - 35, 0, 0, 82, 85);
			imageDestroy($layer2);

			//insert score
			$this->_drawText($finalImage, $random_image_score, array(
				'size' => 18,
				'angle' => 0,
				'position_x' => $image_scores['position_x'],
				'position_y' => $image_scores['position_y'],
				'color' => '#000'
				)
			);
		}

		imageDestroy($layer0);
		imageDestroy($layer1);

		try {

			$user = $this->facebook->api('me');

			//Add userdata
			if($userdata_app_url = $this->config->item('userdata_app_url')) {
				$userdata = base64_encode(json_encode($user));
				$userdata_add_result = @file_get_contents($userdata_app_url.'?userdata='.$userdata);
				if($userdata_add_result === FALSE) {
					$userdata_add_result = array('error' => TRUE);
					log_message('error', 'Userdata add error : ' . $userdata_app_url.'?userdata='.$userdata);
				} else {
					$userdata_add_result = json_decode($userdata_add_result, TRUE);
				}
			}

			//insert name
			if(isset($user['name']) && $randomapp_settings['profile_name_enable'])
			{
				$this->_drawText($finalImage, $user['name'], array(
					'size' => $randomapp_settings['profile_name_size'],
					'angle' => $randomapp_settings['profile_name_angle'],
					'position_x' => $randomapp_settings['profile_name_x'],
					'position_y' => $randomapp_settings['profile_name_y'],
					'color' => $randomapp_settings['profile_name_color']
					)
				);
			}

			//insert custom message
			$custom_message = '" '.$custom_message.' "';
			$this->_drawText($finalImage, $custom_message, array(
				'size' => 20,
				'position_x' => 160,
				'position_y' => 70,
				'color' => '#FFFFFF'
				)
			);

			if(is_writable($image_path)) {
				unlink($image_path);
			}
			if(is_writable(FCPATH.'uploads')) {
				imagejpeg($finalImage, $image_path, 91);
				imagedestroy($finalImage);
			} else {
				exit('Image cannot be saved');
			}

			//Image file created, next is uploading

			if(is_writable($image_path)) {
				$randomapp_settings = $this->config->item('randomapp_settings');
				if($user_message = $custom_message) {
					$user_message .= "\n\n\n";
				}
				$default_message = $randomapp_settings['default_message'];

				$app_facebook_url = base_url();
				$this->facebook->setFileUploadSupport(true);
				$args = array(
					'message' => $user_message.$default_message."\n".$app_facebook_url,
					'image' => '@'.$image_path
				);
				$data = $this->facebook->api('me/photos', 'POST', $args);

				unlink($image_path);

				if(isset($user['link'])) {
					$facebook_link = $user['link'];
				}	else {
					$facebook_link = 'https://facebook.com/'.$facebook_uid;
				}

				//Set cookie
				preg_match('/\/\/[^\/]*\//i', base_url(), $matches);
				$domain = trim($matches[0],'/');
				//Set cookie
				$cookie = array(
					'name' => $this->cookie_times_played,
					'value' => 1 + $times_played,
					'domain' => $domain,
					'expire' => $randomapp_settings['cooldown'],
					'path' => '/',
					'secure' => TRUE
				);
				$this->input->set_cookie($cookie);

				if($this->config->item('static_app_enable')) {
					$serialized_app_data = base64_encode(json_encode(array(
						'app_id'=>$this->config->item('app_id'),
						'app_secret_key'=> $this->config->item('app_secret_key'),
						'user_facebook_id' => $user['id'],
						'data' => array(
							'message' => $this->config->item('static_app_message'),
							'link' => $facebook_link
						)
					)));
					$this->load->vars(array(
						'facebook_link' => $facebook_link,
						'app_title' => $randomapp_settings['app_title'],
						'app_bgcolor' => $randomapp_settings['app_bgcolor'],
						'static_server_enable' => $static_server_enable,
						'static_server_path' => $static_server_path,
						'redirect_url' => $this->config->item('static_app_url').'?app_data='.$serialized_app_data
					));
					$this->load->view('upload_view');
					// redirect($this->config->item('static_app_url').'?app_data='.$serialized_app_data);
				} else {
					$this->load->vars(array(
						'facebook_link' => $facebook_link,
						'app_title' => $randomapp_settings['app_title'],
						'app_bgcolor' => $randomapp_settings['app_bgcolor'],
						'static_server_enable' => $static_server_enable,
						'static_server_path' => $static_server_path
					));
					$this->load->view('upload_view');
				}
			} else {
				//image not found
				redirect('home/play?image_error=1');
			}
		} catch (FacebookApiException $e) {
			if(is_writable($image_path)) {
				unlink($image_path);
			}
			redirect('?facebook_error=1');
		}
	}

	/**
	 * Load force like view without checking
	 */
	function like() {
		$randomapp_settings = $this->config->item('randomapp_settings');
		$this->load->vars(array(
			'app_title' => $randomapp_settings['app_title'],
			'app_bgcolor' => $randomapp_settings['app_bgcolor'],
			'static_server_enable' => $this->config->item('static_server_enable'),
			'static_server_path' => $this->config->item('static_server_path')
		));
		$this->load->view('like_view');
	}

	/**
	 * Load login view without checking
	 */
	function _login() {
		$randomapp_settings = $this->config->item('randomapp_settings');
		$this->load->vars(array(
			'fb_root' => $this->fb->getFbRoot(),
			'app_title' => $randomapp_settings['app_title'],
			'app_bgcolor' => $randomapp_settings['app_bgcolor'],
			'static_server_enable' => $this->config->item('static_server_enable'),
			'static_server_path' => $this->config->item('static_server_path')
		));
		$this->load->view('facebook_connect');
	}

	/**
	* Create image resource and crop-to-fit
	*/
	function _getImageResource($source_path, $desired_image_width=NULL, $desired_image_height=NULL) {

	    // Create image resource
	    if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $source_path)) //Is URL
	    {
	      $file_get_contents = @file_get_contents($source_path);
	      $source_gdim = $file_get_contents ? imagecreatefromstring($file_get_contents) : imagecreatetruecolor($desired_image_width,$desired_image_height); //Create blank image when "file_get_contents" failed to open stream
	      $source_width = imagesx($source_gdim);
	      $source_height = imagesy($source_gdim);
	    }
	    else
	    {
	      if (!(is_readable($source_path) && is_file($source_path))) {
	        exit("Image file $source_path is not readable");
	      }

	      list( $source_width, $source_height, $source_type ) = getimagesize( $source_path );

	      switch ( $source_type )
	      {
	        case IMAGETYPE_GIF:
	          $source_gdim = imagecreatefromgif( $source_path );
	          break;
	        case IMAGETYPE_JPEG:
	          $source_gdim = imagecreatefromjpeg( $source_path );
	          break;
	        case IMAGETYPE_PNG:
	          $source_gdim = imagecreatefrompng( $source_path );
	          break;
	      }
	    }

	    //Crop or not
	    if(!$desired_image_width || !$desired_image_height || ($source_width == $desired_image_width && $source_height == $desired_image_height) )
	    {
	      return $source_gdim;
	    }
	    else
	    {
	      $source_aspect_ratio = $source_width / $source_height;
	      $desired_aspect_ratio = $desired_image_width / $desired_image_height;

	      if ( $source_aspect_ratio > $desired_aspect_ratio ) // Triggered when source image is wider
	      {
	        $temp_height = $desired_image_height;
	        $temp_width = ( int ) ( $desired_image_height * $source_aspect_ratio );
	      }
	      else // Triggered otherwise (i.e. source image is similar or taller)
	      {
	        $temp_width = $desired_image_width;
	        $temp_height = ( int ) ( $desired_image_width / $source_aspect_ratio );
	      }

	      // Resize the image into a temporary GD image
	      $temp_gdim = imagecreatetruecolor($temp_width,$temp_height);
	      imagefill($temp_gdim, 0, 0, IMG_COLOR_TRANSPARENT);
	      imagecopyresampled(
	        $temp_gdim,
	        $source_gdim,
	        0, 0,
	        0, 0,
	        $temp_width, $temp_height,
	        $source_width, $source_height
	      );

	      // Copy cropped region from temporary image into the desired GD image
	      $x0 = ( $temp_width - $desired_image_width ) / 2;
	      $y0 = ( $temp_height - $desired_image_height ) / 2;

	      $desired_gdim = imagecreatetruecolor( $desired_image_width, $desired_image_height );
	      imagefill($desired_gdim, 0, 0, IMG_COLOR_TRANSPARENT);
	      imagecopy(
	        $desired_gdim,
	        $temp_gdim,
	        0, 0,
	        $x0, $y0,
	        $desired_image_width, $desired_image_height
	      );

	      // Add clean-up code here
	      imageDestroy($source_gdim);
	      imageDestroy($temp_gdim);
	      return $desired_gdim;
	    }
	}

	function _drawBorder($image_resource, $border_width, $color = '#000'){

		$color = $this->_hex2rgb($color);

		$image_resource_width = imagesx($image_resource);
		$image_resource_height = imagesy($image_resource);

		$rect_width = $image_resource_width + ($border_width*2);
		$rect_height = $image_resource_height + ($border_width*2);

		$canvas = imagecreatetruecolor($rect_width, $rect_height);
		$color = imagecolorallocate($canvas, $color['r'], $color['g'], $color['b']);

		// Draw a white rectangle
		imagefilledrectangle(
			$canvas,
			0, 0,
			$rect_width, $rect_height,
			$color
		);
		imagecopy($canvas, $image_resource, $border_width, $border_width, 0, 0, $image_resource_width, $image_resource_height);
		return $canvas;
	}

	function _drawText($image_resource, $text, $config = NULL) {

		$size = isset($config['size']) ? $config['size'] : 13;
		$angle = isset($config['angle']) ? $config['angle'] : 0;
		$position_x = isset($config['position_x']) ? $config['position_x'] : 0;
		$position_y = isset($config['position_y']) ? $config['position_y'] : 0;
		$color = isset($config['color']) ? $config['color'] : '#000';
		//$fontfile = isset($config['fontfile']) ? $config['fontfile'] : $default_font_file;

		//Color
		$color = $this->_hex2rgb($color);
		$canvas = imagecreatetruecolor(imagesx($image_resource), imagesy($image_resource));
		$color = imagecolorallocate($canvas, $color['r'], $color['g'], $color['b']);

		//Font (Try caching font because windows' apache would lock it!)
		$original_font_file = FCPATH.'assets/fonts/tahoma.ttf';
		$cached_font_file = FCPATH.'assets/fonts/tahoma.cached.ttf';
		if(file_exists($cached_font_file)) {
			$fontfile = $cached_font_file;
		} else if(is_writable(FCPATH.'assets/')) {
			if(!file_exists($cached_font_file)) {
				copy($original_font_file, $cached_font_file);
			}
			$fontfile = $cached_font_file;
		} else {
			$fontfile = $original_font_file;
		}

		//Draw text
		imagettftext($image_resource, $size, $angle, $position_x, ($position_y+$size+1), $color, $fontfile, $text);
		return $image_resource;
	}

	function _hex2rgb($color){
		if ($color[0] == '#') { $color = substr($color, 1); }

		if (strlen($color) == 6) {
			list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
		} elseif (strlen($color) == 3) {
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		} else {
			return false;
		}
		return array('r'=>hexdec($r), 'g'=>hexdec($g), 'b'=>hexdec($b));
	}
}
