<?php

class Auth extends Controller {
	function Auth() {
		parent::Controller();
		$this->load->library('openid');
	}
	function index() {
		$this->login();
	}
	function login() {
		if (!$this->input->post('openid-identifier')) {
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
		$this->openid->authenticate($this->input->post('openid-identifier'));
	}
	function check() {
		$this->config->load('openid');
		$this->load->helper('gfx');
		$this->openid->set_request_to(site_url($this->config->item('openid_request_to')));
		$response = $this->openid->getResponse();
		switch ($response->status) {
			case Auth_OpenID_CANCEL:
				flashdata_message('auth_login_canceled', 'highlight', 'info');
				header('Location: ' . base_url());
				break;
			case Auth_OpenID_FAILURE:
				flashdata_message('auth_login_failed');
				header('Location: ' . base_url());
				break;
			case Auth_OpenID_SUCCESS:
				$open_id = $response->getDisplayIdentifier();

				$this->load->database();
				$user = $this->db->get_where('users', array('login' => $open_id));
				if ($user->num_rows() !== 0) {
					/* User exists */
					$data = $user->row_array();
					flashdata_message('auth_login', 'highlight', 'info');
				} else {
					$this->load->config('gfx');
					if ($this->config->item('gfx_require_pre_authorization')) {
						header('Location: ' . site_url('about/closetest?claimed_id=' . urlencode($open_id)));
						exit();
					}
					/* Create new user */
					flashdata_message('auth_login_new', 'highlight', 'info');
					$sreg = Auth_OpenID_SRegResponse::fromSuccessResponse($response)->contents();
					$data = array(
						'login' => $open_id,
						'name' => '__temp__' . md5($open_id . time()), /* require unique */
						'title' => '',
						'admin' => 'N',
						'avatar' => '',
						'email' => '',
						'bio' => '',
						'web' => '', //$open_id, //I was told that Google/Yahoo generated open id is annoy to be put here.
						'blog' => '', //$open_id,
						'blog_rss' => '',
						'forum_username' => '',
						'count' => 1,
						'visited' => 0
					);
					if (isset($sreg['fullname'])) {
						$data['title'] = $sreg['fullname'];
					} elseif (isset($sreg['nickname'])) {
						$data['title'] = $sreg['nickname'];
					}
					if (isset($sreg['email'])) {
						$data['avatar'] = '(gravatar)';
						$data['email'] = $sreg['email'];
					}
					if (preg_match('/myid\.tw\/$/', $data['login'])) {
						$data['avatar'] = '(myidtw)';
					}
					$this->db->insert('users', $data);
					$data['id'] = $this->db->insert_id();
					$this->db->insert('u2g', array('user_id' => $data['id'], 'group_id' => '1', 'order' => '1'));
					$this->db->insert('u2g', array('user_id' => $data['id'], 'group_id' => '2', 'order' => '2'));
				}
				session_data_set(
					array(
						'id' => $data['id'],
						'name' => $data['name'],
						'admin' => $data['admin'],
						'hide_announcement' => ''
					),
					false
				);
				header('Location: ' . site_url('editor'));
		}
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', 0, '/');
		}
		session_destroy();
	}
	function xrds() {
		header('Content-Type: application/xrds+xml');
		print '<?xml version="1.0" encoding="UTF-8"?>';
?>

<xrds:XRDS xmlns:xrds="xri://$xrds" xmlns:openid="http://openid.net/xmlns/1.0" xmlns="xri://$xrd*($v*2.0)">
	<XRD>
	<Service xmlns="xri://$xrd*($v*2.0)">
		<Type>http://specs.openid.net/auth/2.0/return_to</Type>
		<URI><?php print site_url('auth/check');?></URI>
	</Service>
	</XRD>
</xrds:XRDS>
<?php
	}
	function logout() {
		$this->load->config('gfx');
		$this->load->helper('gfx');
		if (!checkAuth(true, false, 'flashdata')) {
			header('Location: ' . base_url());
			exit();
		}
		session_data_unset();
		header('Location: ' . base_url());
	}
	function skip_announcement() {
		$this->load->helper('gfx');
		session_data_set(array('hide_announcement' => 'Y'), false);
		json_message('ok', 'highlight', 'info');
	}
	function switchto() {
		$this->load->config('gfx');
		$this->load->helper('gfx');
		if (!checkAuth(true, true, 'flashdata')) {
			header('Location: ' . base_url());
			exit();
		}
		$user = $this->db->get_where('users', array('id' => $this->input->post('id')));
		if ($user->num_rows() === 0) {
			flashdata_message('no_such_user');
			header('Location: ' . base_url());
			exit();
		}
		session_data_set(
			array(
				'id' => $user->row()->id,
				'name' => $user->row()->name,
				'admin' => $user->row()->admin
			)
		);
		header('Location: ' . site_url('editor'));
	}
	function forgetopenid() {
		$this->load->helper('gfx');
		//Due to privicy consideration, we will not show any onscreen message indicate email has been send or not.
		//Therefore all the flashdata message will be the same from this point on.
		$this->load->helper('email');
		if (!valid_email($this->input->post('email'))) {
			flashdata_message('openid_query_processed', 'highlight', 'info');
			header('Location: ' . site_url('about/faq'));
			exit();
		}
		$this->load->database();
		$acs = $this->db->query('SELECT `login`, `name` FROM `users` WHERE `email` = ' . $this->db->escape($this->input->post('email')) . ';');
		if ($acs->num_rows() !== 0) {
			$this->load->library('email');
			$this->email->initialize(
				array(
					'mailtype' => 'html'
				)
			);
			$this->load->config('gfx');
			$this->email->from($this->config->item('gfx_mail_from_add'), $this->config->item('gfx_mail_from_name'));
			$this->email->to($this->input->post('email'));
			$this->email->subject($this->lang->line('gfx_email_subject_forgetopenid'));
			$data = array(
				'ip' => ($_SERVER['REMOTE_ADDR'] === '192.168.255.254')?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'],
				'logins' => array(
				)
			);
			foreach($acs->result_array() as $U) {
				if (substr($U['name'], 0, 8) === '__temp__') {
					unset($U['name']);
				}
				$data['logins'][] = $U;
			}	
			$this->email->message(
				$this->load->view(
					$this->config->item('language') . '/forgetopenid.php',
					$data,
					true
				)
			);
			$this->email->send();
			//echo $this->email->print_debugger();
		}
		flashdata_message('openid_query_processed', 'highlight', 'info');
		header('Location: ' . site_url('about/faq'));
	}
}

/* End of file auth.php */
/* Location: ./system/applications/controller/auth.php */ 
