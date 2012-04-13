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

		$user = $this->facebook->api('me');

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
			'fb_root' => $this->fb->getFbRoot(),
			'my_name' => isset($user['name']) ? $user['name'] : '',
			'randomapp_settings' => $randomapp_settings
		));
		$this->load->view('play_view');
	}

	function execute(){

		$randomapp_settings = $this->config->item('randomapp_settings');

		$oldsize = $randomapp_settings['profile_image_facebook_size']; //facebook thumbnail size
		$newsize = isset($randomapp_settings['profile_image_size']) ? $randomapp_settings['profile_image_size'] : 50;

		if(!$this->input->post('tagged')) {
			echo 'No friend selected.';
			return false;
		}
		$tagged_facebook_uids = explode(',',$this->input->post('tagged'));

		//1. save images into vars
		$images = $resized = array();
		foreach($tagged_facebook_uids as $key => $value){
			$images[$key] = imagecreatefromstring(file_get_contents("http://graph.facebook.com/{$value}/picture"));
			//Resize if the size is changed
			if($oldsize != $newsize) { 
				$resized[$key] = imagecreatetruecolor($newsize, $newsize);
				imagecopyresampled($resized[$key], $images[$key], 0, 0, 0, 0, $newsize, $newsize, $oldsize, $oldsize);
				// imagecopyresampled(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
			} else {
				$resized[$key] = $images[$key];
			}
		}

		//2. get template image and x,y for each image
		$static_server_path = $this->config->item('static_server_path');
		$random_image_name = $this->input->post('img_name');
		$background_image_url = $static_server_path.'images/random/'.$random_image_name;

		$background_image_size = getimagesize($background_image_url);
		$background_image_width = $background_image_size[0];
		$background_image_height = $background_image_size[1];
		if(strrpos($background_image_url, '.png') !== FALSE) {
			$background_image = imagecreatefrompng($background_image_url);
		} else if(strrpos($background_image_url, '.jpg') !== FALSE) {
			$background_image = imagecreatefromjpeg($background_image_url);
		} else if(strrpos($background_image_url, '.gif') !== FALSE) {
			$background_image = imagecreatefromgif($background_image_url);
		}

		$grey = imagecolorallocate($background_image, 100, 100, 100);

		$tag_x[0] = $randomapp_settings['profile_image_x'];
		$tag_y[0] = $randomapp_settings['profile_image_y'];

		//3. create a new image
		// header ('Content-Type: image/png'); //for test
		// $background_image = @imagecreatetruecolor(600, 400); // create new blank image
		$text_color = imagecolorallocate($background_image, 233, 14, 91);
		// imagestring($background_image, 1, 5, 5,  'U R TAGGED', $text_color);
		imagecopymerge($background_image, $resized[0], $tag_x[0], $tag_y[0], 0, 0, $newsize, $newsize, 100);

		//4. insert name
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

		$user = $this->facebook->api('me');
		if(isset($user['name'])) {	
			//Shadow
			$my_name_po_x = $randomapp_settings['text_1_x'];
			$my_name_po_y = $randomapp_settings['text_1_y'];
			
			imagettftext($background_image, 12, 0, $my_name_po_x, $my_name_po_y+17, $grey, $font_file, $user['name']); 
			
		}

		$friend_name = $this->input->post('my_friend_name');
		if(isset($friend_name)) {
			$my_friend_po_x = $randomapp_settings['text_2_x'];
			$my_friend_po_y = $randomapp_settings['text_2_y'];
			imagettftext($background_image, 12, 0, $my_friend_po_x, $my_friend_po_y+15, $grey, $font_file, $friend_name); 
		}

		//set random filename
		mt_srand();
		$filename = md5(uniqid(mt_rand()));
		imagepng($background_image, $filepath = FCPATH.'uploads/'.$filename.'.png');

		$this->uploadToFacebook($filepath, $tagged_facebook_uids);
	}

	function uploadToFacebook($filepath, $tagged_facebook_uids) {

		//4. upload to facebook, if album not exists, create it
		$randomapp_settings = $this->config->item('randomapp_settings');
		if($user_message = $this->input->post('message')) {
			$user_message .= "\n\n\n";
		}
		$default_message = $randomapp_settings['default_message'];
		$page_id = $this->config->item('mockuphappen_facebook_page_id');
		$facebook_app_id = $this->config->item('facebook_app_id');
		$app_facebook_url = "https://www.facebook.com/profile.php?id={$page_id}&sk=app_{$facebook_app_id}";
		
		//upload image
		$this->facebook->setFileUploadSupport(true);

		$args = array(
			'message' => $user_message.$default_message."\n".$app_facebook_url,
			'image' => '@'.$filepath
		);
		$data = $this->facebook->api('me/photos', 'POST', $args);
		

		//5. tag
		$image_size = @getimagesize($filepath);
		$image_width = $image_size[0];
		$image_height = $image_size[1];
		$tag_x[0] = $randomapp_settings['profile_image_x'];
		$tag_y[0] = $randomapp_settings['profile_image_y'];

		$thumbnail_size = isset($randomapp_settings['profile_image_size']) ? $randomapp_settings['profile_image_size'] : 50;
		
		//assigning users to tag and cordinates
		foreach($tagged_facebook_uids as $key => $value){
			$argstag = array(
				'to' => $value,
				'x' => ($tag_x[$key]+($thumbnail_size/2))*100/$image_width,
				'y' => ($tag_y[$key]+($thumbnail_size/2))*100/$image_height
			);
			//Perform tag
			$datatag = $this->facebook->api('/' . $data['id'] . '/tags', 'post', $argstag);
		}

		$photo = $this->facebook->api($data['id']);

		//6. remove temp file
		if(is_writable($filepath)) {
			unlink($filepath);
		}

		//7. Load success view
		$static_server_enable = $this->config->item('static_server_enable');
		$static_server_path = $this->config->item('static_server_path');

		if(isset($photo['link'])) {
			$user = $this->facebook->api('me');
			if(isset($user['link'])) {
				$facebook_link = $user['link'];
			}	else {
				$facebook_link = 'https://facebook.com/me';
			}
			$this->load->vars(array(
				'facebook_link' => $facebook_link,
				'app_title' => $randomapp_settings['app_title'],
				'app_bgcolor' => $randomapp_settings['app_bgcolor'],
				'static_server_enable' => $static_server_enable,
				'static_server_path' => $static_server_path
			));
			$this->load->view('upload_view');
		} else {
			redirect();
		}
	}
}
