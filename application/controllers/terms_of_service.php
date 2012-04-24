<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms_of_service extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	function index() {
		$randomapp_settings = $this->config->item('randomapp_settings');
		$this->load->vars(array(
			'app_title' => $randomapp_settings['app_title']
		));
		$this->load->view('terms_of_service_view');
	}
}
