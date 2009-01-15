<?php

class Auth extends Controller {
	function Auth() {
		parent::Controller();
		$this->lang->load('openid', 'english');
		$this->load->library('openid');
    }
    function index() {
    	$this->login();
    }
    function login() {
		if (!$this->input->post('openid_identifier')) {
			//$this->session->set_flashdata('error', 'no post data');
			header('Location: ' . base_url());
			exit();
		}
        $this->config->load('openid');

        $this->openid->set_request_to(site_url($this->config->item('openid_request_to')));
        $this->openid->set_trust_root(base_url());
        $this->openid->set_args(null);
        $this->openid->set_sreg(
        	true,
        	$this->config->item('openid_required'),
        	$this->config->item('openid_optional'),
        	site_url($this->config->item('openid_policy'))
       	);
        //$this->openid->set_pape(true, $pape_policy_uris);
        $this->openid->authenticate($this->input->post('openid_identifier'));
    }
    function check() {
		$this->config->load('openid');

		$this->openid->set_request_to(site_url($this->config->item('openid_request_to')));
		$response = $this->openid->getResponse();
		switch ($response->status) {
			case Auth_OpenID_CANCEL:
			case Auth_OpenID_FAILURE:
				header('Location: ' . base_url());
				break;
			case Auth_OpenID_SUCCESS:
				$open_id = $response->getDisplayIdentifier();

				$this->load->database();
				$user = $this->db->get_where('users', array('login' => $open_id));
				if ($user->num_rows() !== 0) {
					/* User exists */
					$data = $user->row_array();
				} else {
					/* Create new user */
					$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
					$sreg = $sreg_resp->contents();
					if (!isset($sreg['nickname'])) {
						/* some buggy server that don't even provide data marked as 'required' */
						$sreg['nickname'] = $open_id;
					}
					$this->db->insert(
						'users',
						array(
							'login' => $open_id,
							'avatar' => (isset($sreg['email']))?'(gravatar)':'', //TBD: Gravatar fetcher
							'name' => '__temp__' . md5($sreg['nickname']), /* require unique */
							'title' => (isset($sreg['fullname']))?$sreg['fullname']:$sreg['nickname'],
							'email' => (isset($sreg['email']))?$sreg['email']:'',
							'count' => 1
						)
					);
					$user = $this->db->get_where('users', array('login' => $open_id));
					$data = $user->row_array();
					$this->db->insert('u2f', array('user_id' => $data['id'], 'feature_id' => '1', 'order' => '1'));
					$this->db->insert('u2f', array('user_id' => $data['id'], 'feature_id' => '2', 'order' => '2'));
					$this->db->insert('u2f', array('user_id' => $data['id'], 'feature_id' => '3', 'order' => '3'));
				}
				$this->session->set_userdata(array('id' => $data['id']));
					/*
						We grab anything else from database coz user might open up two session at two places
						Also, you really can't save much thing in the cookie.
					 */
				header('Location: ' . site_url('editor'));
		}
    }
    function logout() {
    	if ($this->input->post('session_id') === $this->session->userdata('session_id')) {
    		header('X-Session: destroyed.');
			$this->session->sess_destroy();
		} elseif ($this->input->post('token') === md5($this->session->userdata('id') . '--secret-token-good-day-fx')) {
    		header('X-Session: destroyed.');
   			$this->session->sess_destroy();
		}
		
		header('Location: ' . base_url());
    }
}

/* End of file auth.php */
/* Location: ./system/applications/controller/auth.php */ 