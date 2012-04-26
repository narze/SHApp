<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->signedRequest = $this->facebook->getSignedRequest();
		$this->facebook_page_id = $this->config->item('mockuphappen_facebook_page_id');
		$this->facebook_app_id = $this->config->item('facebook_app_id');
		$this->cookie_name = $this->facebook_page_id.'_'.$this->facebook_app_id.'_times_played';
	}
  
  /**
   * Check like in javascript
   */
	function index(){
	  $this->_in_page_tab_check();
	  $this->load->view('check_view', array('facebook_app_scope' => $this->config->item('facebook_app_scope')));
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
					'app_bgcolor' => $randomapp_settings['app_bgcolor']
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
			redirect();
		}

		$randomapp_settings = $this->config->item('randomapp_settings');

		$times_played = $this->input->cookie($this->cookie_name);
		$maximum_times_played = isset($randomapp_settings['maximum_times_played']) ? $randomapp_settings['maximum_times_played'] : 0;

		if($times_played && $maximum_times_played && ($times_played >= $maximum_times_played)) {
			$maximum_times_reached = TRUE;
		} else {
			$maximum_times_reached = FALSE;
		}

		$static_server_enable = $this->config->item('static_server_enable');
		$static_server_path = $this->config->item('static_server_path');

		if($static_server_enable) { //From static server
			$exclude_this_image = $this->input->post('img_name');
			$random_image_name = rand(1, $randomapp_settings['max_ramdom_number']).'.jpg';
			//Exclude recent image if clicked form in play_view
			if($random_image_name == $exclude_this_image) {
				while($random_image_name == $exclude_this_image) {
					$random_image_name = rand(1, $randomapp_settings['max_ramdom_number']).'.jpg';
				}
			}
			$random_image_url = $static_server_path.'images/random/'.$random_image_name;
		} else { //From local file
			$jpgs = glob(FCPATH.'assets/images/random/*.jpg');
			$pngs = glob(FCPATH.'assets/images/random/*.png');
			$gifs = glob(FCPATH.'assets/images/random/*.gif');
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
		}

		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->vars(array(
			'image_url' => $random_image_url,
			'img_name' => $random_image_name,
			'facebook_uid' => $facebook_uid,
			'img_x'=> $randomapp_settings['profile_image_x']-3,
			'img_y'=> $randomapp_settings['profile_image_y']-3,
			'img_size' => $randomapp_settings['profile_image_size'],
			'app_title' => $randomapp_settings['app_title'],
			'profile_image_type' => $randomapp_settings['profile_image_type'],
			'app_bgcolor' => $randomapp_settings['app_bgcolor'],
			'static_server_enable' => $static_server_enable,
			'static_server_path' => $static_server_path,
			'maximum_times_reached' => $maximum_times_reached,
			'cooldown_hours' => $randomapp_settings['cooldown'] / 60 / 60,
			'maximum_times_played' => $randomapp_settings['maximum_times_played']
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
			|| (!$random_image_name = $this->input->post('img_name'))) {
			redirect();
		}

		$randomapp_settings = $this->config->item('randomapp_settings');

		$times_played = $this->input->cookie($this->cookie_name);
		$maximum_times_played = isset($randomapp_settings['maximum_times_played']) ? $randomapp_settings['maximum_times_played'] : 0;

		if($times_played && $maximum_times_played && ($times_played >= $maximum_times_played)) {
			redirect('home/play?maximum_times_reached=1');
		}

		$profile_image_size = $randomapp_settings['profile_image_size'];
		$profile_image_x = $randomapp_settings['profile_image_x'];
		$profile_image_y = $randomapp_settings['profile_image_y'];
		$profile_image_type = $randomapp_settings['profile_image_type'];
		$profile_image_facebook_size = $randomapp_settings['profile_image_facebook_size'];

		$static_server_enable = $this->config->item('static_server_enable');
		$static_server_path = $this->config->item('static_server_path');

		if($static_server_enable) { //From static server
			$random_image_url = $static_server_path.'images/random/'.$random_image_name;
			if (!fopen($random_image_url, "r")) {
				exit('Image not found');
			}
		}
		else { // From Local file
			$random_image_url = base_url().'assets/images/random/'.$random_image_name;
			if(!file_exists(FCPATH.'assets/images/random/'.$random_image_name)) {
				exit('Image not found');
			}
		}

		$user_image = imagecreatefromstring(file_get_contents("http://graph.facebook.com/{$facebook_uid}/picture?type={$profile_image_type}"));

		if($profile_image_facebook_size != $profile_image_size) { 
			//Native way
			$resized = imagecreatetruecolor($profile_image_size, $profile_image_size);
			imagecopyresampled($resized, $user_image, 0, 0, 0, 0, $profile_image_size, $profile_image_size, $profile_image_facebook_size, $profile_image_facebook_size);
			$user_image = $resized;
		} 

		if(strrpos($random_image_url, '.png') !== FALSE) {
			$background_image = imagecreatefrompng($random_image_url);
		} else if(strrpos($random_image_url, '.jpg') !== FALSE) {
			$background_image = imagecreatefromjpeg($random_image_url);
		} else if(strrpos($random_image_url, '.gif') !== FALSE) {
			$background_image = imagecreatefromgif($random_image_url);
		}

		$white = imagecolorallocate($background_image, 255, 255, 255);
		$grey = imagecolorallocate($background_image, 100, 100, 100);

		// Draw a white rectangle
		imagefilledrectangle(
			$background_image,
			$profile_image_x -3,
			$profile_image_y -3,
			$profile_image_x + $profile_image_size + 2,
			$profile_image_y + $profile_image_size + 2,
			$white
		);

		imagecopymerge($background_image, $user_image, $profile_image_x, $profile_image_y, 0, 0, $profile_image_size, $profile_image_size, 100);

		$filename = sha1('SaLt'.$facebook_uid.'TlAs');

		$image_path = FCPATH.'uploads/'.$filename.'.jpg';
		$image_url = base_url().'uploads/'.$filename.'.jpg';

		try {
			//insert name
			$user = $this->facebook->api('me');
			/* Text
			if(isset($user['name'])) {	
				//Try caching font (because windows' apache would lock it!)
				$original_font_file = FCPATH.'assets/fonts/tahoma.ttf';
				$cached_font_file = FCPATH.'assets/fonts/tahoma.cached.ttf';
				
				if(file_exists($cached_font_file)) {
					$font_file = $cached_font_file;
				} else if(is_writable(FCPATH.'assets/')) {
					if(!file_exists($cached_font_file)) {
						copy($original_font_file, $cached_font_file);
					}
					$font_file = $cached_font_file;
				} else {
					$font_file = $original_font_file;
				}
				//Shadow
				imagettftext($background_image, 13, 0, $profile_image_x + $profile_image_size + 15, $profile_image_y + 7, $grey, $font_file, $user['name']);
				imagettftext($background_image, 13, 0, $profile_image_x + $profile_image_size + 17, $profile_image_y + 9, $white, $font_file, $user['name']);
				//imagettftext($background_image, 15, 0, $profile_image_x, $profile_image_y - 21, $white, $font_file, $user['name']);
				//imagettftext($background_image, 15, 0, $profile_image_x, $profile_image_y - 23, $grey, $font_file, $user['name']);
			}
			*/

			if(is_writable($image_path)) {
				unlink($image_path);
			}
			if(is_writable(FCPATH.'uploads')) {
				imagejpeg($background_image, $image_path);
				imagedestroy($background_image);
			} else {
				exit('Image cannot be saved');
			}

			//Image file created, next is uploading

			if(is_writable($image_path)) {
				$randomapp_settings = $this->config->item('randomapp_settings');
				if($user_message = $this->input->post('message')) {
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
					'name' => 'times_played',
					'value' => 1 + $times_played,
					'domain' => $domain,
					'expire' => $randomapp_settings['cooldown'],
					'path' => '/',
					'prefix' => $this->facebook_page_id.'_'.$this->facebook_app_id.'_',
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
			'app_bgcolor' => $randomapp_settings['app_bgcolor']
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
			'app_bgcolor' => $randomapp_settings['app_bgcolor']
		));
		$this->load->view('facebook_connect');
	}
}
