<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->app_install_id = $this->socialhappen->get_app_install_id();
		$bar = $this->socialhappen->get_bar();
		// $this->bar_css = $bar['css'];
		// $this->bar_html = $bar['html'];
	}

	function index() {
		$this->load->view('example');
	}
}