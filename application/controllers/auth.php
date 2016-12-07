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
		// Intentionally break the login function
		header('Location: ' . base_url());
		return;
		//

		if (!$this->input->post('openid-identifier')) {
			header('Location: ' . base_url());
			return;
		}
		$this->config->load('openid');

		$this->openid->set_request_to(site_url('auth/check'));
		$this->openid->set_trust_root(base_url());
		$this->openid->set_args(null);
		$this->openid->set_sreg(
			true,
			array('nickname'),
			array('fullname', 'email'),
			site_url($this->config->item('openid_policy'))
		);
		$this->openid->set_ax(
			true,
			array(
				'firstname' => 'http://axschema.org/namePerson/first',
				'email' => 'http://axschema.org/contact/email',
				'lastname' => 'http://axschema.org/namePerson/last'
                        )
                );
		//$this->openid->set_pape(true, $pape_policy_uris);
		if ($html = $this->openid->authenticate($this->input->post('openid-identifier'), false)) {
			$content = array(
				'title' => $this->lang->line('gfx_auth_login_title'),
				'name' => 'openid_login',
				'content' => $html
			);
			$this->load->library('parser');
			$this->parser->page(
				array(
					'meta' => $this->load->view('page/meta.php', $content, true),
					'content' => $this->load->view('page/content.php', $content, true),
					'script' => '<script type="text/javascript">document.getElementById("openid_message").submit()</script>'
				)
			);
		}
	}
	function check() {
		$this->config->load('openid');
		$this->load->helper('gfx');
		$this->openid->set_request_to(site_url('auth/check'));
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
			//case Auth_OpenID_SETUP_NEEDED:	//Only happens in checkid_immediate mode
			//	flashdata_message('Auth_OpenID_SETUP_NEEDED');
			//	header('Location: ' . base_url());
			//	break;
			case Auth_OpenID_SUCCESS:
				$open_id = $response->getDisplayIdentifier();

				$this->load->database();
				$user = $this->db->get_where('users', array('login' => $open_id));
				$sreg = Auth_OpenID_SRegResponse::fromSuccessResponse($response)->contents();
				$ax_response = Auth_OpenID_AX_FetchResponse::fromSuccessResponse($response);
				if ($ax_response) {
					$ax = $ax_response->data;
				} else {
					$ax = array();
				}
				if ($user->num_rows() !== 0) {
					/* User exists */
					$data = $user->row_array();
					flashdata_message('auth_login', 'highlight', 'info');

					if (!$data['email']) {
						if (isset($sreg['email'])) {
        	                                        $data['email'] = $sreg['email'];
							$this->db->update('users', array('email' => $sreg['email']), array('id' => $data['id']));
                        	                } elseif (isset($ax['http://axschema.org/contact/email'])) {
                                	                $data['email'] = $ax['http://axschema.org/contact/email'][0];
							$this->db->update('users', array('email' => $ax['http://axschema.org/contact/email'][0]), array('id' => $data['id']));
	                                        }
					}
				} else {
					$this->load->config('gfx');
					if ($this->config->item('gfx_require_pre_authorization')) {
						header('Location: ' . site_url('about/closetest?claimed_id=' . urlencode($open_id)));
						return;
					}
					/* Create new user */
					flashdata_message('auth_login_new', 'highlight', 'info');
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
					} elseif (isset($ax['http://axschema.org/namePerson/first'])) {
						$data['title'] = $ax['http://axschema.org/namePerson/first'][0];
					}
					if (isset($sreg['email'])) {
						$data['avatar'] = '(gravatar)';
						$data['email'] = $sreg['email'];
					} elseif (isset($ax['http://axschema.org/contact/email'])) {
						$data['avatar'] = '(gravatar)';
						$data['email'] = $ax['http://axschema.org/contact/email'][0];
					}
					if (preg_match('/myid\.tw\/$/', $data['login'])) {
						$data['avatar'] = '(myidtw)';
					}
					$this->db->insert('users', $data);
					$data['id'] = $this->db->insert_id();
					$this->db->insert('u2g', array('user_id' => $data['id'], 'group_id' => '1', 'order' => '1'));
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
			return;
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
			return;
		}
		$user = $this->db->get_where('users', array('id' => $this->input->post('id')));
		if ($user->num_rows() === 0) {
			flashdata_message('no_such_user');
			header('Location: ' . base_url());
			return;
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
			return;
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
					'forgetopenid.php',
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
