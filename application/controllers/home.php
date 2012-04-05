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
			$this->load->vars('fb_root', $this->fb->getFbRoot());
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
				$this->load->view('like_view');
			}
		}
	}

	function play() {
		if((!$facebook_uid = $this->facebook->getUser()) 
			|| !$this->fb->isUserLikedPage($this->config->item('mockuphappen_facebook_page_id'))) {
			redirect();
		}
		$jpgs = glob(FCPATH.'assets/images/random/*.jpg');
		$pngs = glob(FCPATH.'assets/images/random/*.png');
		$gifs = glob(FCPATH.'assets/images/random/*.gif');
		$images = array_merge($jpgs, $pngs, $gifs);
		$random_image_path = $images[mt_rand(0, count($images)-1)];
		$random_image_name = pathinfo($random_image_path, PATHINFO_BASENAME);
		$random_image_url = base_url().'assets/images/random/'.$random_image_name;
		$randomapp_settings = $this->config->item('randomapp_settings');
		$profile_image_size = $randomapp_settings['profile_image_size'];
		$profile_image_x = $randomapp_settings['profile_image_x'];
		$profile_image_y = $randomapp_settings['profile_image_y'];
		$profile_image_type = $randomapp_settings['profile_image_type'];
		$profile_image_facebook_size = $randomapp_settings['profile_image_facebook_size'];

		$cached_profile_picture = FCPATH.'uploads/'.$facebook_uid.'.png';
		if(file_exists($cached_profile_picture)) {
			$user_image = imagecreatefromstring(file_get_contents($cached_profile_picture, FILE_USE_INCLUDE_PATH));
		} else {
			$user_image = imagecreatefromstring(file_get_contents("http://graph.facebook.com/{$facebook_uid}/picture?type={$profile_image_type}"));
			imagepng($user_image, $cached_profile_picture);
		}
		
		if($profile_image_facebook_size != $profile_image_size) { 
			//Native way
			$resized = imagecreatetruecolor($profile_image_size, $profile_image_size);
			imagecopyresampled($resized, $user_image, 0, 0, 0, 0, $profile_image_size, $profile_image_size, $profile_image_facebook_size, $profile_image_facebook_size);
			$user_image = $resized;

			//GD2 way
			// $facebook_profile_image_path = FCPATH.'uploads/'.$facebook_uid.'.png';
			// if(is_writable($facebook_profile_image_path)) {
			// 	unlink($facebook_profile_image_path);
			// }
			// imagepng($user_image, $facebook_profile_image_path);

			// $config['image_library'] = 'gd2';
			// $config['source_image']	= $facebook_profile_image_path;
			// $config['create_thumb'] = TRUE;
			// $config['maintain_ratio'] = TRUE;
			// $config['width']	 = $profile_image_size;
			// $config['height']	= $profile_image_size;

			// $this->load->library('image_lib', $config); 

			// $this->image_lib->resize();

			// $user_image = imagecreatefrompng($facebook_profile_image_path);
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
			imagettftext($background_image, 14, 0, 148, 36, $grey, $font_file, $user['name']);
			imagettftext($background_image, 14, 0, 150, 38, $white, $font_file, $user['name']);
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
		
			$this->load->helper('html');
			$this->load->helper('form');
			$this->load->vars(array(
				'image_url' => $image_url
			));
			$this->load->view('play_view');
		} else {
			exit('Image cannot be saved');
		}
	}

	function upload() {
		if((!$facebook_uid = $this->facebook->getUser()) 
			|| !$this->fb->isUserLikedPage($this->config->item('mockuphappen_facebook_page_id'))) {
			redirect();
		}
		$filename = sha1('SaLt'.$facebook_uid.'TlAs');

		$image_path = FCPATH.'uploads/'.$filename.'.png';
		$user_image_path = FCPATH.'uploads/'.$facebook_uid.'.png';
		$image_url = base_url().'uploads/'.$filename.'.png';
		if(is_writable($image_path)) {
			$randomapp_settings = $this->config->item('randomapp_settings');
			if($user_message = $this->input->post('message')) {
				$user_message .= "\n\n\n";
			}
			$default_message = $randomapp_settings['default_message'];
			$this->facebook->setFileUploadSupport(true);
			$args = array(
				'message' => $user_message.$default_message,
				'image' => '@'.$image_path
			);
			$data = $this->facebook->api('me/photos', 'POST', $args);

			if(is_writable($image_path)) {
				unlink($image_path);
			}
			if(is_writable($user_image_path)) {
				unlink($user_image_path);
			}
			$user = $this->facebook->api('me');
			if(isset($user['link'])) {
				$facebook_link = $user['link'];
			}	else {
				$facebook_link = 'https://facebook.com/'.$facebook_uid;
			}
			$this->load->vars('facebook_link', $facebook_link);
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
