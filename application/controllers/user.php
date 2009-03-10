<?php

class User extends Controller {
	function User() {
		parent::Controller();
		$this->load->scaffolding('users');
	}
	function index() {
		$this->load->config('gfx');
		$this->view($this->config->item('gfx_home_user'));
	}
	function view($id) {
		/* xrds doc request, usually done by OpenID 2.0 op who checks "Relay Party" */
		if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/xrds+xml') !== false) {
			header('X-XRDS-Location: ' . site_url('auth/xrds'));
			header('Content-Type: text/plain');
			print 'You should find the location of xrds doc in the header. I could place a <meta> tag here but I am lazy and you are dumb.';
			//TBD: <meta http-equiv="X-XRDS-Location" content=""/>
			exit();
		}

		/* Redirect numeric id instead of showing pages (below) */
		if (is_numeric($id)) {
			$this->load->database();
			$user = $this->db->query('SELECT `name` FROM `users` WHERE `id` = ' . $this->db->escape($id) . ';');
			if ($user->num_rows() === 0 || substr($user->row()->name, 0, 8) === '__temp__') {
				show_404();
			} else {
				header('Location: ' . site_url($user->row()->name));
				exit();
			}
		}

		$this->load->library('cache');
		$data = $this->cache->get($id, 'user');
		if (!$data || $this->session->userdata('admin') === 'Y') {
			$data = array();
			$this->load->database();
			//if (is_numeric($id)) {
			//	$user = $this->db->query('SELECT * FROM users WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			//	$features = $this->db->query('SELECT t1.name, t1.title, t1.description FROM features t1, u2f t2 WHERE t2.feature_id = t1.id AND t2.user_id = ' . $this->db->escape($id) . ' ORDER BY t2.order ASC;');
			//} else {
				if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $id) || substr($id, 0, 8) === '__temp__') {
					show_404();
				}
				$user = $this->db->query('SELECT * FROM users WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
				//$features = $this->db->query('SELECT t1.name, t1.title, t1.description FROM features t1, u2f t2, users t3 WHERE t2.feature_id = t1.id AND t2.user_id = t3.id AND t3.name = ' . $this->db->escape($id) . ' ORDER BY t2.order ASC;');
			//}
			if ($user->num_rows() === 0) {
				//TBD: pretty error for userpages
				show_404();
			}
			$features = $this->db->query('SELECT t1.name, t1.title, t1.description FROM features t1, u2f t2 ' 
			. 'WHERE t2.feature_id = t1.id AND t2.user_id = ' . $user->row()->id . ' ORDER BY t2.order ASC;');
			$F = array();
			foreach ($features->result_array() as $feature) {
				$F[] = $feature;
			}
			unset($features, $feature);
			$addons = $this->db->query('SELECT t1.*, t2.group_id FROM addons t1, u2a t2 '
			. 'WHERE t2.addon_id = t1.id AND t2.user_id = ' . $user->row()->id . ' ORDER BY t2.order ASC;');
			$A = array();
			foreach ($addons->result_array() as $addon) {
				if (!isset($A[$addon['group_id']])) $A[$addon['group_id']] = array();
				$A[$addon['group_id']][] = $addon;
			}
			unset($addons, $addon);
			$groups = $this->db->query('SELECT t1.id, t1.name, t1.title, t1.description FROM groups t1, u2g t2 ' 
			. 'WHERE t2.group_id = t1.id AND t2.user_id = ' . $user->row()->id . ' ORDER BY t2.order ASC;');
			$G = array();
			foreach ($groups->result_array() as $group) {
				if (!isset($A[$group['id']])) $A[$group['id']] = array();
				$G[] = $group;
			}
			unset($groups, $group);
			$this->load->_ci_cached_vars = array(); //Clean up cached vars
			$data['meta'] = $this->load->view($this->config->item('language') . '/user/meta.php', $user->row_array(), true);
			$data['content'] = $this->load->view($this->config->item('language') . '/user/content.php', array_merge($user->row_array(), array('features' => $F, 'groups' => $G, 'addons' => $A)), true);
			$this->load->config('gfx');
			$this->cache->save($user->row()->name, $data, 'user', $this->config->item('gfx_cache_time'));

			$data['db'] = 'content ';
		}

		if ($this->session->userdata('name') && $id === $this->session->userdata('name')) {
			$data['messages'] = array(
				array (
					'type' => 'highlight',
					'icon' => 'info',
					'message' => $this->lang->line('gfx_message_userpage_yourpage')
				)
			);
		}

		if ($this->session->userdata('admin') === 'Y') {
			$this->load->_ci_cached_vars = array();
			$data['admin'] = $this->load->view($this->config->item('language') . '/user/admin.php', $user->row_array(), true);
		}
		
		$this->load->library('parser');
		if ($this->session->userdata('id') && isset($user) && $user->row()->id == $this->session->userdata('id')) {
			$this->parser->page($data, $this->session->userdata('id'), $user->row_array());
		} else {
			$this->parser->page($data, $this->session->userdata('id'));
		}
	}
	function delete() {
		$this->load->config('gfx');
		/* Check token */
		if ($this->input->post('token') !== md5($this->session->userdata('id') . $this->config->item('gfx_token'))) {
			$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_wrong_token'));
			header('Location: ' . base_url());
			exit();
		}
		/* Check is really admin or user want to delete him/herself */
		$this->load->database();
		if ($this->input->post('id')
			&& ($this->session->userdata('admin') === 'Y')) {
			$data = $this->db->query('SELECT `admin` FROM `users` WHERE `id` = ' . $this->session->userdata('id') . ';');
			if ($data->num_rows() === 0 || $data->row()->admin !== 'Y') {
				$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_wrong_token'));
				header('Location: ' . base_url());
				exit();
			}
			$data->free_result();
			$id = $this->input->post('id');
		} else {
			$id = $this->session->userdata('id');
		}
		/* Check whether user exists and his/her name */
		$data = $this->db->query('SELECT `name` FROM `users` WHERE `id` = ' . $id . ';');
		if ($data->num_rows() === 0) {
			$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_no_such_user'));
			header('Location: ' . base_url());
			exit();
		}
		/* Actual Deletion */
		$this->db->delete('users', array('id' => $id));
		$this->db->delete('u2a', array('id' => $id));
		$this->db->delete('u2f', array('id' => $id));
		$this->db->delete('u2g', array('id' => $id));
		$this->load->library('cache');
		$this->cache->remove($data->row()->name, 'user');
		$this->cache->remove($id, 'header');
		$d = './userstickers/' . dechex(intval($id) >> 12) . '/' . dechex(intval($id) & (pow(2,12)-1)) . '/';
		if (file_exists($d) && is_dir($d)) {
			foreach (scandir($d) as $filename) {
				if (in_array($filename, array('.', '..'))) continue;
				unlink($d . $filename);
			}
		}
		/* Logout user if its the same id */
		if ($this->session->userdata('id') === $id) {
			$this->session->unset_userdata('id');
			$this->session->unset_userdata('name');
			$this->session->unset_userdata('admin');
		}
		$this->session->set_flashdata('message', 'highlight:info:' . $this->lang->line('gfx_message_user_deleted'));
		header('Location: ' . base_url());
	}
	function update() {
		$this->load->config('gfx');
		/* Check token and admin */
		if (
			($this->input->post('token') !== md5($this->session->userdata('id') . $this->config->item('gfx_token')))
			|| ($this->session->userdata('admin') !== 'Y')
		) {
			//$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_wrong_token'));
			//header('Location: ' . base_url());
			print json_encode(
				array(
					'message' => array(
						'type' => 'error',
						'icon' => 'alert',
						'msg' => $this->lang->line('gfx_message_wrong_token')
					)
				)
			);
			exit();
		}
		/* Check is really admin */
		$this->load->database();
		$data = $this->db->query('SELECT `admin` FROM `users` WHERE `id` = ' . $this->session->userdata('id') . ';');
		if ($data->num_rows() === 0 || $data->row()->admin !== 'Y') {
			//$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_wrong_token'));
			//header('Location: ' . base_url());
			print json_encode(
				array(
					'message' => array(
						'type' => 'error',
						'icon' => 'alert',
						'msg' => $this->lang->line('gfx_message_wrong_token')
					)
				)
			);
			exit();
		}
		$data->free_result();
		/* Check whether login already used */
		$data = $this->db->query('SELECT `login` FROM `users` WHERE `id` != ' . $this->input->post('id')
			. ' AND `login` = ' . $this->db->escape($this->input->post('login')) . ';');
		if ($data->num_rows() !== 0) {
			//$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_dup_login'));
			//header('Location: ' . base_url());
			print json_encode(
				array(
					'message' => array(
						'type' => 'error',
						'icon' => 'alert',
						'msg' => $this->lang->line('gfx_message_dup_login')
					)
				)
			);
			exit();
		}
		$data->free_result();
		/* Check whether user exists and his/her name */
		$data = $this->db->query('SELECT `name` FROM `users` WHERE `id` = ' . $this->input->post('id') . ';');
		if ($data->num_rows() === 0) {
			//$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_no_such_user'));
			//header('Location: ' . base_url());
			print json_encode(
				array(
					'message' => array(
						'type' => 'error',
						'icon' => 'alert',
						'msg' => $this->lang->line('gfx_message_no_such_user')
					)
				)
			);
			exit();
		}
		/* Update data */
		$this->db->update(
			'users',
			array(
				'login' => $this->input->post('login'),
				'count' => $this->input->post('count'),
				'avatar' => $this->input->post('avatar'),
				'admin' => $this->input->post('admin')
			),
			array(
				'id' => $this->input->post('id')
			)
		);
		$this->load->library('cache');
		$this->cache->remove($data->row()->name, 'user');
		//$this->session->set_flashdata('message', 'highlight:info:' . $this->lang->line('gfx_message_user_updated'));
		//header('Location: ' . site_url($data->row()->name));
		print json_encode(
			array(
				'message' => array(
					'type' => 'highlight',
					'icon' => 'info',
					'msg' => $this->lang->line('gfx_message_user_updated')
				)
			)
		);
	}
}

/* End of file user.php */
/* Location: ./system/applications/controller/user.php */ 