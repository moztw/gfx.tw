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
		if (is_numeric($id)) {
			$query = $this->db->query('SELECT * FROM features WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
		} else {
			$query = $this->db->query('SELECT * FROM features WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
		}
		if ($query->num_rows() !== 0) {
			//header('Content-Type: text/plain');
			//var_dump($query->row_array());
			$this->load->view('feature', $query->row_array());
		} else {
			show_404();
		}
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