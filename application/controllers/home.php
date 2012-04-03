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
			$this->get_random_pic();
		}
	}

	function get_random_pic() {

		$facebook_uid = $this->facebook->getUser();
		$jpgs = glob(FCPATH.'assets/images/random/*.jpg');
		$pngs = glob(FCPATH.'assets/images/random/*.png');
		$gifs = glob(FCPATH.'assets/images/random/*.gif');
		$images = array_merge($jpgs, $pngs, $gifs);
		$random_image_path = $images[array_rand($images)];
		$random_image_name = pathinfo($random_image_path, PATHINFO_BASENAME);
		$random_image_url = base_url().'assets/images/random/'.$random_image_name;
		$user_image = imagecreatefromstring(file_get_contents("http://graph.facebook.com/{$facebook_uid}/picture?return_ssl_resources=1&type=large"));
		$new_user_image_size = 50;
		$x = 1;
		$y = 2;
		if(strrpos($random_image_url, '.png') !== FALSE) {
			$background_image = imagecreatefrompng($random_image_url);
		} else if(strrpos($random_image_url, '.jpg') !== FALSE) {
			$background_image = imagecreatefromjpeg($random_image_url);
		} else if(strrpos($random_image_url, '.gif') !== FALSE) {
			$background_image = imagecreatefromgif($random_image_url);
		}
		imagecopymerge($background_image, $user_image, $x, $y, 0, 0, $new_user_image_size, $new_user_image_size, 100);

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
			$this->facebook->setFileUploadSupport(true);

			$args = array(
				'message' => 'test',
				'image' => '@'.$image_path
			);
			$data = $this->facebook->api('me/photos', 'POST', $args);
			echo '<pre>';
			var_dump($data);
			echo '</pre>';
		} else {
			echo 'no write perm';
		}
	}
}
