<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_connect extends CI_Controller {

	function __construct() {
		parent::__construct();
		// Set force=1 to skip app_install_id checking
		if(!$this->input->get('force') === 1) {
			$this->app_install_id = $this->socialhappen->get_app_install_id();
		}
	}

	function index() {
		if(!$facebook_uid = $this->facebook->getUser()){
			$this->load->vars('fb_root', $this->fb->getFbRoot());
			$this->load->view('facebook_connect');
		} else {
			echo "Connected";
		}
	}
}