<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if(($userdata = $this->input->get('userdata')) && isset($userdata['id'])) {
			$userdata = json_decode(base64_decode($userdata), TRUE);
			$this->load->model('userdata_model');
			$userdata['facebook_user_id'] = "".$userdata['id'];
			unset($userdata['id']);
			if($result = $this->userdata_model->add($userdata)) {
				echo json_encode(array(
					'success' => TRUE,
					'result' => $result
				));
			} else {
				echo json_encode(array(
					'success' => FALSE,
					'result' => 'Cannot add userdata, maybe duplicated id'
				));
			}
		} else {
			echo json_encode(array(
				'success' => FALSE,
				'input' => $userdata,
				'error' => 'Userdata not included'
			));
		}
	}

	function createIndex() {
		$this->load->model('userdata_model');
		echo json_encode($this->userdata_model->recreateIndex());
	}

	function test() {
		$userdata = array(
			"id" => "713558190",
			"name" => "Manassarn Manoonchai",
			"first_name" => "Manassarn",
			"last_name" => "Manoonchai",
			"link" => "http://www.facebook.com/NarzE",
			"username" => "NarzE",
			"gender" => "male",
			"locale" => "en_US"
		);
		$userdata = base64_encode(json_encode($userdata));
		$this->load->helper('html');

		echo anchor(base_url('?userdata='.$userdata));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */