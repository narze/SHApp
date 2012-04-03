<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
		
	}

	function index(){
		if((!$facebook_uid = $this->facebook->getUser()) || !$this->fb->hasPermissions()){
			$this->load->vars('fb_root', $this->fb->getFbRoot());
			$this->load->view('facebook_connect');
		} else {			
			$this->play();
		}
	}

	function play() {
		if(!$facebook_uid = $this->facebook->getUser()) {
			redirect();
		}
		$jpgs = glob(FCPATH.'assets/images/random/*.jpg');
		$pngs = glob(FCPATH.'assets/images/random/*.png');
		$gifs = glob(FCPATH.'assets/images/random/*.gif');
		$images = array_merge($jpgs, $pngs, $gifs);
		$random_image_path = $images[array_rand($images)];
		$random_image_name = pathinfo($random_image_path, PATHINFO_BASENAME);
		$random_image_url = base_url().'assets/images/random/'.$random_image_name;
		$randomapp_settings = $this->config->item('randomapp_settings');
		$profile_image_size = $randomapp_settings['profile_image_size'];
		$profile_image_x = $randomapp_settings['profile_image_x'];
		$profile_image_y = $randomapp_settings['profile_image_y'];
		$profile_image_type = $randomapp_settings['profile_image_type'];
		$profile_image_facebook_size = $randomapp_settings['profile_image_facebook_size'];
		$user_image = imagecreatefromstring(file_get_contents("http://graph.facebook.com/{$facebook_uid}/picture?type={$profile_image_type}"));
		
		if($profile_image_facebook_size != $profile_image_size) { 
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
		imagecopymerge($background_image, $user_image, $profile_image_x, $profile_image_y, 0, 0, $profile_image_size, $profile_image_size, 100);

		$filename = sha1('SaLt'.$facebook_uid.'TlAs');

		$image_path = FCPATH.'uploads/'.$filename.'.png';
		$image_url = base_url().'uploads/'.$filename.'.png';
		if(is_writable($image_path)) {
			unlink($image_path);
		}
		if(is_writable(FCPATH.'uploads')) {
			imagepng($background_image, $image_path);
			echo '<img src="'.base_url().'uploads/'.$filename.'.png" />';
			//upload to facebook

			// $photo_message = $setting_data['photo_message'];
		
			//upload image
			echo anchor('home/upload', 'Upload');
			echo anchor('home/play', 'Play again');
		} else {
			echo 'no write perm';
		}
	}

	function upload() {
		if(!$facebook_uid = $this->facebook->getUser()) {
			redirect();
		}
		$filename = sha1('SaLt'.$facebook_uid.'TlAs');

		$image_path = FCPATH.'uploads/'.$filename.'.png';
		$image_url = base_url().'uploads/'.$filename.'.png';
		if(is_writable($image_path)) {
			$this->facebook->setFileUploadSupport(true);
			$message = 'test';
			$args = array(
				'message' => $message,
				'image' => '@'.$image_path
			);
			$data = $this->facebook->api('me/photos', 'POST', $args);
			echo '<pre>';
			var_dump($data);
			echo '</pre>';
		} else {
			//image not found
			redirect();
		}
	}
}
