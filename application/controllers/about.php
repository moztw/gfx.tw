<?php

class About extends Controller {
	function about() {
		parent::Controller();
		$this->load->scaffolding('aboutpages');
		//$this->load->database();
	}
	function index() {
		$this->view('index');
	}
	function view($id) {
		/* Redirect numeric id instead of showing pages (below) */
		if (is_numeric($id)) {
			$this->load->database();
			$query = $this->db->query('SELECT `name` FROM aboutpages WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			if ($query->num_rows() === 0) {
				show_404();
			}
			header('Location: ' . site_url('about/' . $query->row()->name));
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
			$body = $this->cache->get($id, 'about');
			$head = $this->cache->get($id, 'about-head');
		}
		$header = $this->cache->get($session_id, 'header');

		/* Query Database and save views to cache */
		$db = '';
		if (!$body || !$head) {
			$this->load->database();
			if (is_numeric($id)) {
				$about = $this->db->query('SELECT * FROM aboutpages WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			} else {
				$about = $this->db->query('SELECT * FROM aboutpages WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
			}
			if ($about->num_rows() === 0) {
				show_404();
			}
			if (!$head) {
				$db .= 'head ';
				$head = $this->load->view('about/head.php', $about->row_array(), true);
				$this->cache->save($about->row()->name, $head, 'about-head', 60);
			}
			if (!$body) {
				$db .= 'body ';
				$this->load->_ci_cached_vars = array(); //Clean up cached vars
				$body = $this->load->view('about/body.php', $about->row_array(), true);
				$this->cache->save($about->row()->name, $body, 'about', 60);
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
}