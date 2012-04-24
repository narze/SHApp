<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privacy_policy extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	function index() {
		$randomapp_settings = $this->config->item('randomapp_settings');
		$this->load->vars(array(
			'app_title' => $randomapp_settings['app_title']
		));
		$this->load->view('privacy_policy_view');
	}
}
