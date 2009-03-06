<?php

class Feature extends Controller {
	function Feature() {
		parent::Controller();
		$this->load->scaffolding('features');
	}
	function index() {
		//TBD: a nice feature list.
		//show_404();
		header('Content-Type: text/plain');
		print 'TBD';
	}
	function view($id) {
		/* Redirect numeric id */
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
		$data = $this->cache->get($id, 'feature');

		if (!$data) {
			$this->load->database();
			$feature = $this->db->query('SELECT * FROM features WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
			if ($feature->num_rows() === 0) {
				show_404();
			}
			$data = array(
				'meta' => $this->load->view($this->config->item('language') . '/feature/meta.php', $feature->row_array(), true),
				'content' => $body = $this->load->view($this->config->item('language') . '/feature/content.php', $feature->row_array(), true)
			);
			$this->load->config('gfx');
			$this->cache->save($feature->row()->name, $data, 'feature', $this->config->item('gfx_cache_time'));
			$data['db'] = 'content ';
		}
		$this->load->library('parser');
		$this->parser->page($data, $this->session->userdata('id'));
	}
}

/* End of file feature.php */
/* Location: ./system/applications/controller/feature.php */ 