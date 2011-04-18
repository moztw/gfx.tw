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
	function view($name = '') {
		
		/* xrds doc request, usually done by OpenID 2.0 op who checks "Relay Party" */
		if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/xrds+xml') !== false) {
			header('X-XRDS-Location: ' . site_url('auth/xrds'));
			header('Content-Type: text/plain');
			//print 'You should find the location of xrds doc in the header. I could place a <meta> tag here but I am lazy and you are dumb.';
			//TBD: <meta http-equiv="X-XRDS-Location" content=""/>
			return;
		}
		
		$this->load->library('cache');
		$this->load->helper('gfx');
		if (checkETag($name, 'user')) return;
		
		$data = $this->cache->get(strtolower($name), 'user');
		//$data = null; // no cache
		
		if (!$data) {
			
			$data = array();
			$this->load->config('gfx');
			if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $name)
				|| strlen($name) < 3
				|| strlen($name) > 200
				|| substr($name, 0, 8) === '__temp__'
				|| in_array($name, $this->config->item('gfx_badname'))
			) {
				show_404();
			}
			$this->load->database();
			$user = $this->db->query('SELECT * FROM users WHERE `ready` = \'Y\' AND `name` = ' . $this->db->escape($name) . ' LIMIT 1');
			if ($user->num_rows() === 0) {
				//TBD: pretty error for userpages, indicate this name is available
				show_404();
			}
			$U = $user->row_array();

			/* Instead of evaluate space before/after case by case
			we add the space and rely on Browser's white-space processing */
			mb_internal_encoding('UTF-8');
			if (mb_strwidth(mb_substr($U['title'], 0, 1)) === 1) $U['title'] = ' ' . $U['title'];
			if (mb_strwidth(mb_substr($U['title'], -1, 1)) === 1) $U['title'] .= ' ';

			$user->free_result();
			$F = array();
			for ($i = 0; $i < 3; $i++) {
				$feature = $this->db->query('SELECT name, title, description FROM features ' 
				. 'WHERE `id` = ' . $U['feature_' . $i] . ';');
				$F[] = $feature->row_array();
				$feature->free_result();
			}
			unset($feature);

			// get groups to see if user has more than one group.
			$groups = $this->db->query('SELECT t1.id, t1.name, t1.title, t1.description FROM groups t1, u2g t2 ' 
			. 'WHERE t2.group_id = t1.id AND t2.user_id = ' . $U['id'] . ' ORDER BY t2.order ASC;');
			$G = array();
			foreach ($groups->result_array() as $group) {
				if (!isset($A[$group['id']])) $A[$group['id']] = array();
				$G[] = $group;
			}
			$groups->free_result();
			unset($groups, $group);
			
			if(sizeof($G) > 1) {
				// more than one group -> old layout
				$addons = $this->db->query('SELECT t1.*, t2.group_id FROM addons t1, u2a t2 '
				. 'WHERE t2.addon_id = t1.id AND t2.user_id = ' . $U['id'] . ' ORDER BY t2.order ASC;');
				$A = array();
				foreach ($addons->result_array() as $addon) {
					if (!isset($A[$addon['group_id']])) $A[$addon['group_id']] = array();
					$A[$addon['group_id']][] = $addon;
				}
				$addons->free_result();
				unset($addons, $addon);
			} else {
				// only one group -> new layout
				$groups = $this->db->query('SELECT t1.id, t1.name, t1.title, t1.description FROM groups t1 WHERE t1.id = 1;');
				$G = array();
				foreach ($groups->result_array() as $group) {
					if (!isset($A[$group['id']])) $A[$group['id']] = array();
					$G[] = $group;
				}
				$groups->free_result();
				unset($groups, $group);
				
				$addons = $this->db->query('SELECT t1.*, t2.group_id FROM addons t1, u2a t2 '
				. 'WHERE t2.addon_id = t1.id AND t2.user_id = ' . $U['id'] . ' ORDER BY t2.order ASC;');
				$A = array();
				foreach ($addons->result_array() as $addon) {
					if (!isset($A[$addon['group_id']])) $A[$addon['group_id']] = array();
					$A[1][] = $addon;
				}
				$addons->free_result();
				unset($addons, $addon);
			}
			$this->load->_ci_cached_vars = array(); //Clean up cached vars
			$data['name'] = $U['name'];
			$data['meta'] = $this->load->view('user/meta.php', $U, true);
			$data['admin'] = $this->load->view('user/admin.php', $U, true);
			$data['content'] = $this->load->view('user/content.php', array_merge($U, array('features' => $F, 'groups' => $G, 'addons' => $A)), true);
			$this->load->config('gfx');
			$data['expiry'] = $this->cache->save(strtolower($U['name']), $data, 'user', $this->config->item('gfx_cache_time'));

			$data['db'] = 'content ';
		} else {
			$data['expiry'] = $this->cache->get_expiry($name, 'user');
		}

		//name caps check
                if (isset($data['name']) && $data['name'] !== $name) {
			header('Location: ' . site_url($data['name']));
			return;
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
		if ($this->session->userdata('id') && isset($user) && $U['id'] == $this->session->userdata('id')) {
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
			return;
		}
		/* Check whether user exists and his/her name */
		$this->load->database();
		$data = $this->db->query('SELECT `name` FROM `users` WHERE `id` = ' . $id . ';');
		if ($data->num_rows() === 0) {
			flashdata_message('no_such_user');
			header('Location: ' . base_url());
			return;
		}
		/* Actual Deletion */
		$this->db->delete('users', array('id' => $id));
		$this->db->delete('u2a', array('id' => $id));
		$this->db->delete('u2g', array('id' => $id));
		$this->load->library('cache');
		$this->cache->remove(strtolower($data->row()->name), 'user');
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
		if (!checkAuth(true, true, 'json')) return;

		/* Check whether login already used */
		$data = $this->db->query('SELECT `login` FROM `users` WHERE `id` != ' . $this->input->post('id')
			. ' AND `login` = ' . $this->db->escape($this->input->post('login')) . ';');
		if ($data->num_rows() !== 0) {
			json_message('dup_login');
			return;
		}
		$data->free_result();
		/* Check whether user exists and his/her name */
		$data = $this->db->query('SELECT `name` FROM `users` WHERE `id` = ' . $this->input->post('id') . ';');
		if ($data->num_rows() === 0) {
			json_message('no_such_user');
			return;
		}
		/* Update data */
		$this->db->update(
			'users',
			array(
				'login' => $this->input->post('login'),
				'count' => $this->input->post('count'),
				'avatar' => $this->input->post('avatar'),
				'admin' => $this->input->post('admin'),
				'shown' => $this->input->post('shown')
			),
			array(
				'id' => $this->input->post('id')
			)
		);
		$this->load->library('cache');
		$this->cache->remove($data->row()->name, 'user');
		json_message('user_updated', 'highlight', 'info');
	}
	function userlist($type = '') { /* function name cannot be list() */
		switch ($type) {
			case 'random-avatars':
				/*
				Here is what we do: 
				random a number, see if the cache exists, if so output it, if not then generate one then saves it.
				these cache have short ttl because we do not check the data within against database.
				*/
				/*
				Prevent users from sending requests at this URL less than 60 secs of peroid.
				Responsible browsers will serve cache to xhr request if request took places less than 60 sec.
				*/
				header('Cache-Control: max-age=60, must-revalidate');
			case 'random-avatars-reload':
				/*
				No Cache-Control header for this URL.
				*/
			case 'random-avatars-frame':
				/*
				Output webpage or json will be decided later.
				*/
				$this->load->library('cache');
				$i = rand(0, 99);
				$users = $this->cache->get($i, 'random-avatars');
				if (!$users) {
					$this->load->database();
					$this->load->helper('gfx');
					/*
						Really expensive query, should change it right away should user > 1000 
						or fill the cache by using crontab instead of user request
					*/
					$query = $this->db->query('SELECT `login`, `name`, `title`, `avatar`, `email` FROM `users` WHERE `avatar` != \'\' AND `ready` = \'Y\' AND `shown` = \'Y\' ORDER BY RAND() LIMIT 10;');
					$users = array();
					foreach ($query->result_array() as $user) {
						$users[] = array(
							'name' => $user['name'],
							'title' => $user['title'],
							'avatar' => avatarURL($user['avatar'], $user['email'], $user['login'], '&')
						);
					}
					$this->cache->save($i, $users, 'random-avatars', 300);
				}
				if ($type === 'random-avatars-frame') {
					$this->load->view('user/random-avatars.php', array('users' => $users));
				} else {
					$this->load->view('json.php', array('jsonObj' => array('users' => $users)));
				}
			break;
			default:
			json_message('Invalid List type');
		}
	}
}

/* End of file user.php */
/* Location: ./system/applications/controller/user.php */ 
