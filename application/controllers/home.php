<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
		
	}

	function index(){
		
	}

	function get_random_pic() {
		$jpgs = glob(FCPATH.'assets/images/random/*.jpg');
		$pngs = glob(FCPATH.'assets/images/random/*.png');
		$gifs = glob(FCPATH.'assets/images/random/*.gif');
		$images = array_merge($jpgs, $pngs, $gifs);
		$random_image_path = $images[array_rand($images)];
		$random_image_name = pathinfo($random_image_path, PATHINFO_BASENAME);
		$random_image_url = base_url().'assets/images/random/'.$random_image_name;
		echo '<img src="'.$random_image_url.'" />';
	}
}
