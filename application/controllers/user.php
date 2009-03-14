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
	function view($name) {
		/* xrds doc request, usually done by OpenID 2.0 op who checks "Relay Party" */
		if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/xrds+xml') !== false) {
			header('X-XRDS-Location: ' . site_url('auth/xrds'));
			header('Content-Type: text/plain');
			print 'You should find the location of xrds doc in the header. I could place a <meta> tag here but I am lazy and you are dumb.';
			//TBD: <meta http-equiv="X-XRDS-Location" content=""/>
			exit();
		}

		$this->load->library('cache');
		$this->load->helper('gfx');
		checkETag($name, 'user');
		$data = $this->cache->get($name, 'user');

		if (!$data) {
			$data = array();
			$this->load->database();
			if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $name) || substr($name, 0, 8) === '__temp__') {
				show_404();
			}
			$user = $this->db->query('SELECT * FROM users WHERE `name` = ' . $this->db->escape($name) . ' LIMIT 1');
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
			$data['admin'] = $this->load->view($this->config->item('language') . '/user/admin.php', $user->row_array(), true);
			$data['content'] = $this->load->view($this->config->item('language') . '/user/content.php', array_merge($user->row_array(), array('features' => $F, 'groups' => $G, 'addons' => $A)), true);
			$this->load->config('gfx');
			$data['expiry'] = $this->cache->save($user->row()->name, $data, 'user', $this->config->item('gfx_cache_time'));

			$data['db'] = 'content ';
		} else {
			$data['expiry'] = $this->cache->get_expiry($name, 'user');
		}

		if ($this->session->userdata('admin') !== 'Y') {
			unset($data['admin']);
		}

		if ($this->session->userdata('name') && $name === $this->session->userdata('name')) {
			$data['messages'] = array(
				array (
					'type' => 'highlight',
					'icon' => 'info',
					'message' => $this->lang->line('gfx_message_userpage_yourpage')
				)
			);
		}
		
		$this->load->library('parser');
		if ($this->session->userdata('id') && isset($user) && $user->row()->id == $this->session->userdata('id')) {
			$this->parser->page($data, $this->session->userdata('id'), $user->row_array());
		} else {
			$this->parser->page($data, $this->session->userdata('id'));
		}
	}
	function delete() {
		$this->load->helper('gfx');
		if (checkAuth(true, true, '')) {
			/* is an admin */
			$id = $this->db->escape($this->input->post('id'));
		} elseif (checkAuth(true, false, 'flashdata')) {
			/* is a user */
			$id = $this->session->userdata('id');
		} else {
			/* is not logged in; flashdata error already injected at last elseif */
			header('Location: ' . base_url());
			exit();
		}
		/* Check whether user exists and his/her name */
		$data = $this->db->query('SELECT `name` FROM `users` WHERE `id` = ' . $id . ';');
		if ($data->num_rows() === 0) {
			flashdata_message('no_such_user');
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
		flashdata_message('user_deleted', 'highlight', 'info');
		header('Location: ' . base_url());
	}
	function update() {
		$this->load->config('gfx');
		$this->load->helper('gfx');
		checkAuth(true, true, 'json');

		/* Check whether login already used */
		$data = $this->db->query('SELECT `login` FROM `users` WHERE `id` != ' . $this->input->post('id')
			. ' AND `login` = ' . $this->db->escape($this->input->post('login')) . ';');
		if ($data->num_rows() !== 0) {
			json_message('dup_login');
		}
		$data->free_result();
		/* Check whether user exists and his/her name */
		$data = $this->db->query('SELECT `name` FROM `users` WHERE `id` = ' . $this->input->post('id') . ';');
		if ($data->num_rows() === 0) {
			json_message('no_such_user');
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
		json_message('user_updated', 'highlight', 'info');
	}
}

/* End of file user.php */
/* Location: ./system/applications/controller/user.php */ 