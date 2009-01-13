<?php

class Feature extends Controller {
	var $datarule = array(
		array(
			'field'   => 'name',
			'label'   => 'URL',
			'rules'   => 'required|alpha_numeric|max_length[200]|callback_name_check'
		),
		array(
			'field'   => 'title',
			'label'   => 'Title',
			'rules'   => 'required'
		)
	);
	function Feature() {
		parent::Controller();
		$this->load->scaffolding('features');
		$this->load->database();
	}
	function index() {
		//TBD: a nice feature list.
		//show_404();
		header('Content-Type: text/plain');
		print 'TBD';
	}
	function view($id) {
		/* Redirect numeric id instead of showing pages (below) */
		if (is_numeric($id)) {
			$this->load->database();
			$query = $this->db->query('SELECT `name` FROM features WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			if ($query->num_rows() === 0) {
				show_404();
			}
			header('Location: ' . site_url('feature/' . $query->row()->name));
			exit();
		}
		$this->load->library('cache');
		/* See if cache is available or not */
		if ($this->session->userdata('id')) {
			$session_id = $this->session->userdata('id');
		} else {
			$session_id = 0;
		}
		if (!is_numeric($id)) {
			$body = $this->cache->get($id, 'feature');
			$head = $this->cache->get($id, 'feature-head');
		}
		$header = $this->cache->get($session_id, 'header');

		/* Query Database and save views to cache */
		$db = '';
		if (!$body || !$head) {
			$this->load->database();
			if (is_numeric($id)) {
				$feature = $this->db->query('SELECT * FROM features WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			} else {
				$feature = $this->db->query('SELECT * FROM features WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
			}
			if ($feature->num_rows() === 0) {
				show_404();
			}
			if (!$head) {
				$db .= 'head ';
				$head = $this->load->view('feature/head.php', $feature->row_array(), true);
				$this->cache->save($feature->row()->name, $head, 'feature-head', 60);
			}
			if (!$body) {
				$db .= 'body ';
				$this->load->_ci_cached_vars = array(); //Clean up cached vars
				$body = $this->load->view('feature/body.php', $feature->row_array(), true);
				$this->cache->save($feature->row()->name, $body, 'feature', 60);
			}
		}
		if (!$header) {
			$this->load->database();
			if ($session_id !== 0) {
				$auth = $this->db->query('SELECT * FROM users WHERE `id` = ' . $this->session->userdata('id') . ' LIMIT 1');
				$C = $auth->row_array();
				$C['session_id'] = $session_id;
			} else {
				$C = array();
			}
			$db .= 'header ';
			$this->load->_ci_cached_vars = array(); //Clean up cached vars
			$header = $this->load->view('header.php', $C, true);
			$this->cache->save($session_id, $header, 'header', 60);
		}

		/* Output coz everything should be ready by now */
		print $head;
		print $header;
		print $body;
		$footer = $this->load->view('footer.php', array('db' => $db));
	}
	/* Create */
	function create() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->datarule);
		if ($this->form_validation->run() === false) {
			$this->load->view('feature-edit', array('create' => true, 'id' => '', 'name' => '', 'title' => '', 'description' => '', 'content' => ''));
		} else {
			$this->db->insert(
				'features',
				array(
					'name' => $this->input->post('name'),
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'content' => $this->input->post('content')
				)
			);
			header('Location: ' . site_url('feature/' . $this->input->post('name')));
		}
	}
	/* Edit */
	function edit($id) {
		if ($this->input->post('delete')) {
			$this->db->delete('features', array('id' => $this->input->post('id')));
			$this->db->delete('u2f', array('feature_id' => $this->input->post('id')));
			header('Location: ' . site_url('feature'));
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->datarule);
		if ($this->form_validation->run() === false) {
			if (is_numeric($id)) $query = $this->db->query('SELECT * FROM features WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			else $query = $this->db->query('SELECT * FROM features WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
			if ($query->num_rows() === 0) {
				show_404();
			}
			$this->load->view('feature-edit', array_merge(array('create' => false), $query->row_array()));
		} else {
			$this->db->where('id', $this->input->post('id'));
			$this->db->update(
				'features',
				array(
					'name' => $this->input->post('name'),
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'content' => $this->input->post('content')
				)
			);
			header('Location: ' . site_url('feature/' . $this->input->post('name')));
		}
	}
	function name_check($name) {
		if ($this->input->post('id')) $query = $this->db->query('SELECT `id` FROM `features` WHERE `name` = ' . $this->db->escape($name) . 'AND `id` != ' . $this->db->escape($name) . ' LIMIT 1;');
		else $query = $this->db->query('SELECT `id` FROM `features` WHERE `name` = ' . $this->db->escape($name) .  'LIMIT 1;');
		if ($query->num_rows() === 0) return true;
		else return false;
	}
}

/* End of file feature.php */
/* Location: ./system/applications/controller/feature.php */ 