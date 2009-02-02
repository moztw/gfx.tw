<?php

class Userpage extends Controller {
	function Userpage() {
		parent::Controller();
		$this->load->scaffolding('users');
	}
	function index() {
		$this->view('foxmosa');
	}
	function view($id) {
		/* xrds doc request, usually done by OpenID 2.0 op who checks "Relay Party" */
		if (strpos($_SERVER['HTTP_ACCEPT'], 'application/xrds+xml') !== false) {
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
		$data = $this->cache->get($id, 'userpage');
		if (!$data) {
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
			$data['meta'] = $this->load->view('userpage/meta.php', $user->row_array(), true);
			$data['content'] = $this->load->view('userpage/content.php', array_merge($user->row_array(), array('features' => $F, 'groups' => $G, 'addons' => $A)), true);
			$this->cache->save($user->row()->name, $data, 'userpage', 60);

			$data['db'] = 'content ';
		}

		$this->load->library('parser');
		if ($this->session->userdata('id') && isset($user) && $user->row()->id == $this->session->userdata('id')) {
			$this->parser->page($data, $this->session->userdata('id'), $user->row_array());
		} else {
			$this->parser->page($data, $this->session->userdata('id'));
		}
	}
}

/* End of file userpage.php */
/* Location: ./system/applications/controller/userpage.php */ 