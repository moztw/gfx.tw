<?php

class About extends Controller {
	function about() {
		parent::Controller();
		$this->load->scaffolding('aboutpages');
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
		$data = $this->cache->get($id, 'about');

		if (!$data) {
			$this->load->database();
			$about = $this->db->query('SELECT * FROM aboutpages WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
			if ($about->num_rows() === 0) {
				show_404();
			}
			$data = array(
				'meta' => $this->load->view('about/meta.php', $about->row_array(), true),
				'content' => $body = $this->load->view('about/content.php', $about->row_array(), true)
			);
			$this->cache->save($about->row()->name, $data, 'about', 60);
			$data['db'] = 'content ';
		}
		$this->load->library('parser');
		$this->parser->page($data, $this->session->userdata('id'));
	}
}