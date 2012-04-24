<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * NOTICE OF LICENSE
 * 
 * Licensed under the Academic Free License version 3.0
 * 
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

class Welcome extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('fb');
		$this->load->vars('fb_root', $this->fb->getFbRoot());
	}

	public function index(){

		$app_data = $this->input->get('app_data', TRUE);

		if(!$app_data){

			$app_data_array = array(
								'app_id' => 0, 
								'app_secret_key' => 0,
							);
							
		} else {

			/*
			print_r(base64_encode(json_encode(
												array(
														'app_id' => 1, 
														'app_secret_key' => 'asdfghjkasdfghj',
														'user_facebook_id' => '12345678',
														'data' => array('message' => 'message', 'link' => 'link')
													))));
			*/
		
			$app_data_array = json_decode(base64_decode($app_data), TRUE);

		}

		if($user_facebook_id = $this->facebook->getUser()){
			$app_data_array['user_facebook_id'] = $user_facebook_id;
		}

		$sh_user = $this->call_get_user($app_data_array);
		
		if($sh_user['success'] && $user_facebook_id){
			//already member
			//call play app
			$play_app_result = $this->call_play_app($app_data_array);

			$this->load->view('port_view');

		} else {
			//any other case
			$app_data = base64_encode(json_encode($app_data_array));
			$data = compact('app_data','app_data_array');

			try {
				$facebook_user = $this->facebook->api('me');
				$data['user_email'] = $facebook_user['email'];
				$data['facebook_name'] = $facebook_user['name'];
				$data['facebook_image'] = 'http://graph.facebook.com/'.$user_facebook_id.'/picture';
			} catch (FacebookApiException $e) {

			}
			//try request for permission and/or signup
			$this->load->view('signup_view', $data);

			//call play app in play_app_trigger
			
		}
		

		//$this->load->view('welcome_message');

	}

	//called by ajax  from signup_view
	public function signup_trigger(){
		//mandatory parameters
		$app_data = $this->input->post('app_data', TRUE);
		$user_email = $this->input->post('email', TRUE);

		$app_data = json_decode(base64_decode($app_data), TRUE);
		
		$app_id = $app_data['app_id'];
		$app_secret_key = $app_data['app_secret_key'];
		$user_facebook_id = $app_data['user_facebook_id'];

		$args = compact('app_id', 'app_secret_key', 'user_facebook_id', 'user_email');

		//check args
		if(isset($app_id) && isset($app_secret_key) && $user_facebook_id && $user_email){
			$args = compact('app_id', 'app_secret_key', 'user_facebook_id', 'user_email');
			$signup_result = $this->call_signup($args);

			//show result
			if($signup_result['success']){
				if($app_id!=0){
					$app_data = compact('app_id', 'app_secret_key', 'user_facebook_id');
					$play_app_result = $this->call_play_app($app_data);

					if($play_app_result['success']){
						echo json_encode(array('result' => 'ok', 'message' => 'sucessfully log play app', 'data' => $play_app_result));
					} else {
						echo json_encode(array('result' => 'error', 'message' => 'log play app error', 'data' => $play_app_result));
					}
				} else {
					echo json_encode(array('result' => 'ok', 'message' => 'sucessfully sign-up', 'data' => $signup_result));
				}
				
			} else {
				echo json_encode(array('result' => 'error', 'message' => 'signup error', 'data' => $signup_result));
			}
		}
		
	}

	public function sound_check(){
		echo 'api_url : '. $this->config->item('api_url').'<br>';
		echo 'mockuphappen_enable : '. $this->config->item('mockuphappen_enable').'<br>';
		echo 'facebook_app_id : '. $this->config->item('facebook_app_id').'<br>';

	}

	public function play_app_trigger(){
		//view-redirect after signup
		$app_data = $this->input->get('app_data', TRUE);
		$app_data = json_decode(base64_decode($app_data), TRUE);

		//print_r($app_data);
		$play_app_result = $this->call_play_app($app_data);

		$this->load->view('port_view');

	}

	//private functions
	private function call_get_user($args = NULL){
		if($args){
			$result = $this->socialhappen->request('get_user', $args);
			if(isset($result['success'])){
				return $result;
			} else {
				$result['success'] = FALSE;
				return $result;
			}
		}
		return FALSE;
	}

	private function call_play_app($args = NULL){
		if($args){
			$result = $this->socialhappen->request('play_app', $args);

			if(isset($result['success'])){
				return $result;
			} else {
				$result['success'] = FALSE;
				return $result;
			}
		}
		return FALSE;
	}

	private function call_signup($args = NULL){
		if($args){
			$result = $this->socialhappen->request('signup', $args);

			if(isset($result['success'])){
				return $result;
			} else {
				$result['success'] = FALSE;
				return $result;
			}
		}
		return FALSE;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */