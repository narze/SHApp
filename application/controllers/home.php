<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
		$signedRequest = $this->facebook->getSignedRequest();
		// If not liked, redirect to page tab
		if(!isset($signedRequest['page']['liked']) || !$signedRequest['page']['liked']) {
			$page_id = $this->config->item('mockuphappen_facebook_page_id');
			$facebook_app_id = $this->config->item('facebook_app_id');
			// echo '<script>top.location = "'."https://www.facebook.com/profile.php?id={$page_id}&sk=app_{$facebook_app_id}".'";</script>';
		}
		// echo '<pre>';
		// var_dump($signedRequest);
		// echo '</pre>';
	}

	function index(){
		if((!$facebook_uid = $this->facebook->getUser()) 
			|| !$this->fb->hasPermissions()){
			$randomapp_settings = $this->config->item('randomapp_settings');
			$this->load->vars(array(
				'fb_root' => $this->fb->getFbRoot(),
				'app_title' => $randomapp_settings['app_title'],
				'app_bgcolor' => $randomapp_settings['app_bgcolor']
			));
			$this->load->view('facebook_connect');
		} else if ($this->fb->isUserLikedPage($this->config->item('mockuphappen_facebook_page_id'))){			
			$this->play();
		} else {
			$this->like();
		}
	}

	function like() {
		if(!$facebook_uid = $this->facebook->getUser()) {
			redirect();
		} else if($this->fb->isUserLikedPage($this->config->item('mockuphappen_facebook_page_id'))) {
			redirect('home/play');
		} else {
			$signedRequest = $this->facebook->getSignedRequest();
			if(!isset($signedRequest['page']['id']) 
				|| ($signedRequest['page']['id'] != $this->config->item('mockuphappen_facebook_page_id'))) {
				$page_id = $this->config->item('mockuphappen_facebook_page_id');
				$facebook_app_id = $this->config->item('facebook_app_id');
				echo '<script>top.location = "'."https://www.facebook.com/profile.php?id={$page_id}&sk=app_{$facebook_app_id}".'";</script>';
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

	function play() {
		if((!$facebook_uid = $this->facebook->getUser()) 
			|| !$this->fb->isUserLikedPage($this->config->item('mockuphappen_facebook_page_id'))) {
			redirect();
		}

		$randomapp_settings = $this->config->item('randomapp_settings');

		if($this->config->item('static_server_enable')) { //From static server
			$exclude_this_image = $this->input->post('img_name');
			$random_image_name = rand(1, $randomapp_settings['max_ramdom_number']).'.jpg';
			//Exclude recent image if clicked form in play_view
			if($random_image_name == $exclude_this_image) {
				while($random_image_name == $exclude_this_image) {
					$random_image_name = rand(1, $randomapp_settings['max_ramdom_number']).'.jpg';
				}
			}
			$random_image_url = $this->config->item('static_server_path').'images/random/'.$random_image_name;
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
			'app_bgcolor' => $randomapp_settings['app_bgcolor']
		));
		$this->load->view('play_view');
	}

	function upload() {
		if((!$facebook_uid = $this->facebook->getUser()) 
			|| !$this->fb->isUserLikedPage($this->config->item('mockuphappen_facebook_page_id'))
			|| (!$random_image_name = $this->input->post('img_name'))) {
			redirect();
		}

		$randomapp_settings = $this->config->item('randomapp_settings');
		$profile_image_size = $randomapp_settings['profile_image_size'];
		$profile_image_x = $randomapp_settings['profile_image_x'];
		$profile_image_y = $randomapp_settings['profile_image_y'];
		$profile_image_type = $randomapp_settings['profile_image_type'];
		$profile_image_facebook_size = $randomapp_settings['profile_image_facebook_size'];

		if($this->config->item('static_server_enable')) { //From static server
			$random_image_url = $this->config->item('static_server_path').'images/random/'.$random_image_name;
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

		//insert name
		$user = $this->facebook->api('me');
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
			//imagettftext($background_image, 13, 0, $profile_image_x + $profile_image_size + 15, $profile_image_y + 7, $grey, $font_file, $user['name']);
			//imagettftext($background_image, 13, 0, $profile_image_x + $profile_image_size + 17, $profile_image_y + 9, $white, $font_file, $user['name']);
			imagettftext($background_image, 15, 0, $profile_image_x, $profile_image_y - 21, $white, $font_file, $user['name']);
			imagettftext($background_image, 15, 0, $profile_image_x, $profile_image_y - 23, $grey, $font_file, $user['name']);
		}

		$filename = sha1('SaLt'.$facebook_uid.'TlAs');

		$image_path = FCPATH.'uploads/'.$filename.'.png';
		$image_url = base_url().'uploads/'.$filename.'.png';
		if(is_writable($image_path)) {
			unlink($image_path);
		}
		if(is_writable(FCPATH.'uploads')) {
			imagepng($background_image, $image_path);
			imagedestroy($background_image);
		} else {
			exit('Image cannot be saved');
		}

		//Image file created, next is uploading

		$filename = sha1('SaLt'.$facebook_uid.'TlAs');

		$image_path = FCPATH.'uploads/'.$filename.'.png';
		$image_url = base_url().'uploads/'.$filename.'.png';
		if(is_writable($image_path)) {
			$randomapp_settings = $this->config->item('randomapp_settings');
			if($user_message = $this->input->post('message')) {
				$user_message .= "\n\n\n";
			}
			$default_message = $randomapp_settings['default_message'];
			
			$page_id = $this->config->item('mockuphappen_facebook_page_id');
			$facebook_app_id = $this->config->item('facebook_app_id');
			$app_facebook_url = "https://www.facebook.com/profile.php?id={$page_id}&sk=app_{$facebook_app_id}";
			$this->facebook->setFileUploadSupport(true);
			$args = array(
				'message' => $user_message.$default_message."\n".$app_facebook_url,
				'image' => '@'.$image_path
			);
			$data = $this->facebook->api('me/photos', 'POST', $args);

			if(is_writable($image_path)) {
				unlink($image_path);
			}

			$user = $this->facebook->api('me');
			if(isset($user['link'])) {
				$facebook_link = $user['link'];
			}	else {
				$facebook_link = 'https://facebook.com/'.$facebook_uid;
			}
			$this->load->vars(array(
				'facebook_link' => $facebook_link,
				'app_title' => $randomapp_settings['app_title'],
				'app_bgcolor' => $randomapp_settings['app_bgcolor']
			));
			$this->load->view('upload_view');
		} else {
			//image not found
			redirect();
		}
	}

	function invite() {
		echo 'coming soon';
	}
}
