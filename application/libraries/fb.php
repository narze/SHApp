<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Facebook wrapper class
 * Call this class : $this->FB->...
 * Call fb-php-sdk directly : $this->facebook->...
 */
class FB {

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('fb-php-sdk/facebook',
			array(
			  'appId'  => $this->CI->config->item('facebook_app_id'),
			  'secret' => $this->CI->config->item('facebook_app_secret')
			),
			'facebook'
		);
		$this->facebook = $this->CI->facebook;
		// channel_url can be used with facebook js sdk
		$this->channel_url = base_url().'assets/channel/fb.php';
	}

	/**
	 * Call facebook graph api
	 */
	function api(/* polymorphic */){
		try {
      return $this->facebook->api(func_get_args());
    } catch (FacebookApiException $e) {
      return FALSE;
    }
	}

	/**
	 * Get fb-root div
	 */
	function getFbRoot(){
		return $this->CI->load->view('fb-root', array(
			'facebook_app_id' => $this->CI->config->item('facebook_app_id'),
			'facebook_channel_url' => $this->channel_url,
			'facebook_app_scope' => $this->CI->config->item('facebook_app_scope')
		), TRUE);
	}

	/**
	 * Check if current user have grant all permissions requested
	 */
	function hasPermissions() {
		try {
			$permissions = $this->CI->facebook->api('me/permissions');
			if(!isset($permissions['data'][0]) || !is_array($permissions['data'][0])) {
				return FALSE;
			}
			foreach(explode(',', $this->CI->config->item('facebook_app_scope')) as $permission) {
				if(!isset($permissions['data'][0][$permission])) {
					return FALSE;
				}
			}
			return TRUE;
		} catch (FacebookApiException $e) {
      return FALSE;
    }
	}

	/**
	 * Check if current user liked the page (required 'user_likes' permission)
	 */
	function isUserLikedPage($facebook_page_id = NULL){
		if(!$facebook_page_id){
			return FALSE;
		} else if ($this->CI->config->item('facebook_force_like') !== TRUE) {
			return TRUE;
		}
		try {
			$likes = $this->CI->facebook->api('me/likes/'.$facebook_page_id);
			return isset($likes['data']) && count($likes['data']);
		} catch (FacebookApiException $e) {
    	return FALSE;
    }
	}

	function getExtendedToken($access_token = NULL) {
		if(!$access_token && (!$access_token = $this->CI->facebook->getAccessToken())) {
				return FALSE;
		}

		//Setup url for extended token
		$app_id = $this->CI->config->item('facebook_app_id');
		$app_secret = $this->CI->config->item('facebook_app_secret');

		$token_url = "https://graph.facebook.com/oauth/access_token?client_id=".$app_id."&client_secret=".$app_secret."&grant_type=fb_exchange_token&fb_exchange_token=".$access_token;

		//Request
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_URL, $token_url);
		$contents = curl_exec($c);
		$err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
		curl_close($c);

		//Parse
		$response_params = null;
		parse_str($contents, $response_params);
		if(!isset($response_params['access_token'])) { return FALSE; }

		$this->CI->facebook->setAccessToken($response_params['access_token']);
		return $response_params['access_token'];
	}
}