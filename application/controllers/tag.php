<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag extends CI_Controller {

	function __construct(){
		parent::__construct();
		/*
		$segments = $this->uri->segment_array();
		$this->app_install_id = end($segments);
		if(!is_numeric($this->app_install_id)){
			echo json_encode("false");
			exit();
		} else {
			$this->load->vars('app_install_id', $this->app_install_id);
		}
		*/
		$this->load->library('fb');
		/*
		if(!$this->facebook_uid = $this->facebook->getUser()){
			redirect();
		} else {
			$this->load->model('user_model');
			if($this->user = $this->user_model->getOne(array(
					'app_install_id' => $this->app_install_id,
					'facebook_uid' => $this->facebook_uid))){

				} else {
				redirect('register/'.$this->app_install_id);
			}
		}
		*/
	}

	function index(){
		date_default_timezone_set('UTC');
		$this->load->model('setting_model');
		$setting = $this->setting_model->getOne(array('app_install_id' => $this->app_install_id));
		if(!$setting) {
			redirect($this->app_install_id);
		} else if (isset($this->setting['data']['start']) &&  date('Y-m-d H:i:s') < $this->setting['data']['start']) {
			redirect($this->app_install_id);
		} else if (isset($setting['data']['end']) && $setting['data']['end'] <= date('Y-m-d H:i:s')) {
			redirect($this->app_install_id);
		}
		
		$this->load->library('form_validation');
		$this->load->vars(array(
			'template_name' => $setting['data']['template_name'],
			'template_images' => $setting['data']['template_images'],
			'setting_data' => $setting['data'],
			'facebook_uid' => $this->facebook_uid,
			'fb_root' => $this->fb->getFbRoot()
		));
		$this->load->view('tag');
	
	}

	function execute(){
		/*
		//Remove old upload image if exists
		$user = $this->user;
		if(isset($user['tag_image'])){
			$image_path = FCPATH.'uploads/'.$user['tag_image'].'.png';
			if(is_writable($image_path)) {
				unlink($image_path);
			}
		}
		*/

		$randomapp_settings = $this->config->item('randomapp_settings');

		//$this->load->model('setting_model');
		//$setting = $this->setting_model->getOne(array('app_install_id' => $this->app_install_id));
		//if(!$setting) {
			//redirect($this->app_install_id);
		//}
		$oldsize = $randomapp_settings['profile_image_facebook_size']; //facebook thumbnail size
		$newsize = isset($randomapp_settings['profile_image_size']) ? $randomapp_settings['profile_image_size'] : 50;
		//$setting_data = $setting['data'];

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
		//$tag_x[0] = $setting_data['tag_1_x'];
		//$tag_y[0] = $setting_data['tag_1_y'];
		//$tag_x[1] = $setting_data['tag_2_x'];
		//$tag_y[1] = $setting_data['tag_2_y'];
		//$tag_x[2] = $setting_data['tag_3_x'];
		//$tag_y[2] = $setting_data['tag_3_y'];
		//$tag_x[3] = $setting_data['tag_4_x'];
		//$tag_y[3] = $setting_data['tag_4_y'];
		//$tag_x[4] = $setting_data['tag_5_x'];
		//$tag_y[4] = $setting_data['tag_5_y'];

		//3. create a new image
		// header ('Content-Type: image/png'); //for test
		// $background_image = @imagecreatetruecolor(600, 400); // create new blank image
		$text_color = imagecolorallocate($background_image, 233, 14, 91);
		// imagestring($background_image, 1, 5, 5,  'U R TAGGED', $text_color);
		imagecopymerge($background_image, $resized[0], $tag_x[0], $tag_y[0], 0, 0, $newsize, $newsize, 100);
		//imagecopymerge($background_image, $resized[1], $tag_x[1], $tag_y[1], 0, 0, $newsize, $newsize, 100);
		//imagecopymerge($background_image, $resized[2], $tag_x[2], $tag_y[2], 0, 0, $newsize, $newsize, 100);
		//imagecopymerge($background_image, $resized[3], $tag_x[3], $tag_y[3], 0, 0, $newsize, $newsize, 100);
		//imagecopymerge($background_image, $resized[4], $tag_x[4], $tag_y[4], 0, 0, $newsize, $newsize, 100);
		// imagecopymerge(dst_im, src_im, dst_x, dst_y, src_x, src_y, src_w, src_h, pct)


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
			$my_name_po_x = 83; //Hardcode position, temporary use
			$my_name_po_y = 311; //Hardcode position, temporary use
			
			imagettftext($background_image, 12, 0, $my_name_po_x, $my_name_po_y+17, $grey, $font_file, $user['name']); 
			
		}

		$friend_name = $this->input->post('my_friend_name');
		if(isset($friend_name)) {
			$my_friend_po_x = 83; //Hardcode position, temporary use
			$my_friend_po_y = 350; //Hardcode position, temporary use
			imagettftext($background_image, 12, 0, $my_friend_po_x, $my_friend_po_y+15, $grey, $font_file, $friend_name); 
		}

		//set random filename
		mt_srand();
		$filename = md5(uniqid(mt_rand()));
		imagepng($background_image, $filepath = FCPATH.'uploads/'.$filename.'.png');

		// $this->load->view('tag_execute');
		//redirect('tag/uploadToFacebook/'.$this->app_install_id);
		$this->uploadToFacebook($filepath, $tagged_facebook_uids);
	}

	function uploadToFacebook($filepath, $tagged_facebook_uids) {
		

		//$tagged_facebook_uids = $user['prepare_tag_list'];
		//$filepath = FCPATH.'uploads/'.$user['tag_image'].'.png';
		
		/*
		$this->load->model('setting_model');
		$setting = $this->setting_model->getOne(array('app_install_id' => $this->app_install_id));
		if(!$setting) {
			redirect($this->app_install_id);
		}
		$setting_data = $setting['data'];
		*/

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
		//$tag_x[1] = $setting_data['tag_2_x'];
		//$tag_y[1] = $setting_data['tag_2_y'];
		//$tag_x[2] = $setting_data['tag_3_x'];
		//$tag_y[2] = $setting_data['tag_3_y'];
		//$tag_x[3] = $setting_data['tag_4_x'];
		//$tag_y[3] = $setting_data['tag_4_y'];
		//$tag_x[4] = $setting_data['tag_5_x'];
		//$tag_y[4] = $setting_data['tag_5_y'];

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
				$facebook_link = 'https://facebook.com/'.$facebook_uid;
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
