<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->app_install_id = $this->socialhappen->get_app_install_id();
		$bar = $this->socialhappen->get_bar();
		// Use these if you don't want to use default header view
		// $this->bar_css = $bar['css'];
		// $this->bar_html = $bar['html'];
	}

	function index() {
		// $this->load->view('header'); // Included in example view
		$this->load->view('example');
		// $this->load->view('footer'); // Included in example view
	}
}