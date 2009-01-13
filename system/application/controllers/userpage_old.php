<?php

class Userpage extends Controller {
	function Userpage() {
		parent::Controller();
		$this->load->scaffolding('users');
		$this->load->database();
	}
	function index() {
		$this->view('foxmosa');
	}
	function view($id) {
		if (is_numeric($id)) {
			$user = $this->db->query('SELECT * FROM users WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			$features = $this->db->query('SELECT t1.name, t1.title, t1.description FROM features t1, u2f t2 WHERE t2.feature_id = t1.id AND t2.user_id = ' . $this->db->escape($id) . ' ORDER BY t2.order ASC;');
		} else {
			$user = $this->db->query('SELECT * FROM users WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
			$features = $this->db->query('SELECT t1.name, t1.title, t1.description FROM features t1, u2f t2, users t3 WHERE t2.feature_id = t1.id AND t2.user_id = t3.id AND t3.name = ' . $this->db->escape($id) . ' ORDER BY t2.order ASC;');
		}
		if ($user->num_rows() === 0) {
			show_404();
		}
		//header('Content-Type: text/plain');
		//var_dump($user->row_array());
		//var_dump($features->row());

		$F = array();
		foreach ($features->result_array() as $feature) {
			$F[] = $feature;
		}
		if ($user->row()->id == $this->session->userdata('id')) {
			$C = $user->row_array();
			$C['session_id'] = $this->session->userdata('session_id');
		} elseif ($this->session->userdata('id')) {
			$auth = $this->db->query('SELECT * FROM users WHERE `id` = ' . $this->session->userdata('id') . ' LIMIT 1');
			$C = $auth->row_array();
			$C['session_id'] = $this->session->userdata('session_id');
		} else {
			$C = array();
		}
		$this->load->view('userpage', array_merge($user->row_array(), array('features' => $F, 'auth' => $C)));
	}
}

/* End of file userpage.php */
/* Location: ./system/applications/controller/userpage.php */ 