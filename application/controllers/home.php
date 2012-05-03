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
			// echo '<script>top.location = "'."https://www.facebook.com/profile.php?id={$this->facebook_page_id}&sk=app_{$this->facebook_app_id}".'";</script>';
			// exit();
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

		//Todo - get original size
		$facebook_image_url = "https://graph.facebook.com/{$facebook_uid}/picture?type=large";

		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->vars(array(
			'image_url' => $facebook_image_url,
			'filter_name' => 'lomo',
			'facebook_uid' => $facebook_uid,
			'app_title' => $randomapp_settings['app_title'],
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
			|| (!$filter_name = $this->input->post('filter_name'))) {
			redirect();
		}

		$randomapp_settings = $this->config->item('randomapp_settings');

		$times_played = $this->input->cookie($this->cookie_name);
		$maximum_times_played = isset($randomapp_settings['maximum_times_played']) ? $randomapp_settings['maximum_times_played'] : 0;

		if($times_played && $maximum_times_played && ($times_played >= $maximum_times_played)) {
			redirect('home/play?maximum_times_reached=1');
		}

		$static_server_enable = $this->config->item('static_server_enable');
		$static_server_path = $this->config->item('static_server_path');

		

		

		try {
			$albums = $this->facebook->api('me/albums');
			$albums = array_reverse($albums['data']);
			foreach($albums as $album) {
				if($album['type'] === 'profile') {
					$cover_photo = $album['cover_photo'];
					break;
				}
			}

			if(!isset($cover_photo)) {
				exit('You have no profile picture!');
			}

			$multi = $this->facebook->api("?ids={$cover_photo},me");
			$user = $multi['me'];
			$profile_picture = $multi[$cover_photo];
			$image = $profile_picture['source'];

			$user_image = imagecreatefromstring(file_get_contents($image));

		$filename = sha1('SaLt'.$facebook_uid.'TlAs');

		$image_path = FCPATH.'uploads/'.$filename.'.jpg';
		$image_url = base_url().'uploads/'.$filename.'.jpg';

		if(is_writable($image_path)) {
			unlink($image_path);
		}
		if(is_writable(FCPATH.'uploads')) {
			imagejpeg($user_image, $image_path);
			imagedestroy($user_image);
		} else {
			exit('Image cannot be saved');
		}

		//ImageMagick works
		$input = $image_path;
		// $output = FCPATH.'uploads/output.jpg';
		$output = $image_path;
		$temp_path = FCPATH.'uploads/';
		$template_path = FCPATH.'assets/templates/';
		$this->load->library('instagraph');
		$this->instagraph->init($input, $output, $temp_path, $template_path);
		$this->instagraph->{$filter_name}();
		// echo '<img src="'.$image_url.'" />';
		// echo '<img src="'.base_url('uploads/output.jpg').'" />';


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

				$uploaded_image = $this->facebook->api($data['id']);
var_dump($uploaded_image);
				unlink($image_path);

				if(isset($uploaded_image['link'])) {
					$facebook_link = $uploaded_image['link'].'&makeprofile=1';
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
						'new_facebook_image_url' => $uploaded_image['source'],
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
						'new_facebook_image_url' => $uploaded_image['source'],
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
