<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Like extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	function index(){
		//If liked, redirect to home page
		$signedRequest = $this->facebook->getSignedRequest();
		if(isset($signedRequest['page']['liked']) && $signedRequest['page']['liked']) {
			redirect('home');
		} else {
			$this->load->view('like_view');
		}
	}
}
