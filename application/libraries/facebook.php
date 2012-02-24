<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Facebook wrapper class
 * Call this class : $this->facebook->...
 * Call facebook-fb-sdk directly : $this->FB->...
 */
class Facebook {

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('fb-php-sdk/facebook',
			array(
			  'appId'  => $this->CI->config->item('facebook_app_id'),
			  'secret' => $this->CI->config->item('facebook_api_secret')
			),
			'FB'
		);
		$this->FB = $this->CI->FB;
		// channel_url can be used with facebook js sdk
		// $this->channel_url = base_url().'assets/channel/fb.php';
	}

	/**
	 * Call facebook graph api
	 */
	function api(/* polymorphic */){
		return $this->FB->api(func_get_args());
	}
}