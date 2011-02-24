<?php

class About extends Controller {
	function about() {
		parent::Controller();
		$this->load->scaffolding('aboutpages');
	}
	function index() {
		$this->view('index');
	}
	function view($name) {
		/* Redirect numeric id instead of showing pages (below) */
		if (is_numeric($name)) {
			$this->load->database();
			$query = $this->db->query('SELECT `name` FROM aboutpages WHERE `id` = ' . $this->db->escape($name) . ' LIMIT 1');
			if ($query->num_rows() === 0) {
				show_404();
			}
			header('Location: ' . site_url('about/' . $query->row()->name));
			exit();
		}
		
		$this->load->library('cache');
		$this->load->helper('gfx');
		checkETag($name, 'about');
		$data = $this->cache->get($name, 'about');

		if (!$data) {
			$this->load->database();
			$about = $this->db->query('SELECT * FROM aboutpages WHERE `name` = ' . $this->db->escape($name) . ' LIMIT 1');
			if ($about->num_rows() === 0) {
				show_404();
			}
			$this->load->config('gfx');
			$data = array(
				'meta' => $this->load->view('about/meta.php', $about->row_array(), true),
				'content' => $this->load->view('about/content.php', $about->row_array(), true),
				'admin' => $this->load->view('about/admin.php', $about->row_array(), true)
			);
			$data['expiry'] = $this->cache->save($about->row()->name, $data, 'about', $this->config->item('gfx_cache_time'));
			$data['db'] = 'content ';
		} else {
			$data['expiry'] = $this->cache->get_expiry($name, 'about');
		}
		if ($this->session->userdata('admin') !== 'Y') {
			unset($data['admin']);
		}
		$this->load->library('parser');
		$this->parser->page($data, $this->session->userdata('id'));
	}
	function update() {
		$this->load->config('gfx');
		$this->load->helper('gfx');
		if (!checkAuth(true, true, 'json')) return;
		/* About name cannot collide function name */
		if (in_array($this->input->post('name'), array('update', 'delete'))) {
			json_message('error_about_name');
			return;
		}
		/* Check whether name already used */
		$data = $this->db->query('SELECT `name` FROM `aboutpages` WHERE `id` != ' . $this->input->post('id')
			. ' AND `name` = ' . $this->db->escape($this->input->post('name')) . ';');
		if ($data->num_rows() !== 0) {
			json_message('dup_about_name');
			return;
		}
		$data->free_result();
				
		/* Update data */
		$this->db->update(
			'aboutpages',
			array(
				'title' => $this->input->post('title'),
				'name' => $this->input->post('name'),
				'content' => $this->input->post('content')
			),
			array(
				'id' => $this->input->post('id')
			)
		);
		$this->load->library('cache');
		$this->cache->remove($this->input->post('name'), 'about');

		json_message('about_updated', 'highlight', 'info');
	}
	function delete () {
		$this->load->config('gfx');
		$this->load->helper('gfx');
		if (!checkAuth(true, true, 'flashdata')) {
			header('Location: ' . site_url('about'));
			exit();
		}
		$this->load->database();
		$this->db->delete('aboutpages', array('id' => $this->input->post('id')));
		flashdata_message('about_deleted', 'highlight', 'info');
		header('Location: ' . site_url('about'));
	}
}
