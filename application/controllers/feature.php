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
	function view($id, $type = '') {
		/* Redirect numeric id */
		if (is_numeric($id)) {
			$this->load->database();
			$query = $this->db->query('SELECT `name` FROM features WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			if ($query->num_rows() === 0) {
				show_404();
			}
			switch ($type) {
				case 'inframe':
				header('Location: ' . site_url('feature/' . $query->row()->name . '/inframe'));
				break;
				default:
				header('Location: ' . site_url('feature/' . $query->row()->name));
				break;
			}
			exit();
		}
		$this->load->library('cache');
		switch ($type) {
			case 'inframe':
			$data = $this->cache->get($id, 'feature-inframe');

			if (!$data) {
				$this->load->database();
				$feature = $this->db->query('SELECT * FROM features WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
				if ($feature->num_rows() === 0) {
					show_404();
				}
				$data = $this->load->view($this->config->item('language') . '/feature/inframe.php', $feature->row_array(), true);
				$this->load->config('gfx');
				$this->cache->save($feature->row()->name, $data, 'feature-inframe', $this->config->item('gfx_cache_time'));
			}
			print $data;
			break;
			default:
			$data = $this->cache->get($id, 'feature');

			if (!$data || $this->session->userdata('admin') === 'Y') {
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
				//$this->cache->save($feature->row()->name, $data, 'feature', $this->config->item('gfx_cache_time'));
				$data['db'] = 'content ';
			}
			if ($this->session->userdata('admin') === 'Y') {
				$this->load->_ci_cached_vars = array();
				$data['admin'] = $this->load->view($this->config->item('language') . '/feature/admin.php', $feature->row_array(), true);
			}
			$this->load->library('parser');
			$this->parser->page($data, $this->session->userdata('id'));
			break;
		}
	}
	function delete() {
		print 'TBD';
	}
	function update() {
		$this->load->config('gfx');
		/* Check token and admin */
		if (
			($this->input->post('token') !== md5($this->session->userdata('id') . $this->config->item('gfx_token')))
			|| ($this->session->userdata('admin') !== 'Y')
		) {
			//$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_wrong_token'));
			//header('Location: ' . base_url());
			print json_encode(
				array(
					'message' => array(
						'type' => 'error',
						'icon' => 'alert',
						'msg' => $this->lang->line('gfx_message_wrong_token')
					)
				)
			);
			exit();
		}
		/* Check is really admin */
		$this->load->database();
		$data = $this->db->query('SELECT `admin` FROM `users` WHERE `id` = ' . $this->session->userdata('id') . ';');
		if ($data->num_rows() === 0 || $data->row()->admin !== 'Y') {
			//$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_wrong_token'));
			//header('Location: ' . base_url());
			print json_encode(
				array(
					'message' => array(
						'type' => 'error',
						'icon' => 'alert',
						'msg' => $this->lang->line('gfx_message_wrong_token')
					)
				)
			);
			exit();
		}
		$data->free_result();
		/* Feature name cannot collide function name */
		if (in_array($this->input->post('name'), array('update', 'delete'))) {
			print json_encode(
				array(
					'message' => array(
						'type' => 'error',
						'icon' => 'alert',
						'msg' => $this->lang->line('gfx_message_error_feature_name')
					)
				)
			);
			exit();
		}
		/* Check whether name already used */
		$data = $this->db->query('SELECT `name` FROM `features` WHERE `id` != ' . $this->input->post('id')
			. ' AND `name` = ' . $this->db->escape($this->input->post('name')) . ';');
		if ($data->num_rows() !== 0) {
			//$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_dup_login'));
			//header('Location: ' . base_url());
			print json_encode(
				array(
					'message' => array(
						'type' => 'error',
						'icon' => 'alert',
						'msg' => $this->lang->line('gfx_message_dup_feature_name')
					)
				)
			);
			exit();
		}
		$data->free_result();
		/* Update data */
		$this->db->update(
			'features',
			array(
				'title' => $this->input->post('title'),
				'name' => $this->input->post('name'),
				'order' => $this->input->post('order'),
				'description' => $this->input->post('description'),
				'content' => $this->input->post('content')
			),
			array(
				'id' => $this->input->post('id')
			)
		);
		$this->load->library('cache');
		$this->cache->remove($this->input->post('name'), 'feature-inframe');
		$this->cache->remove($this->input->post('name'), 'feature');
		//$this->session->set_flashdata('message', 'highlight:info:' . $this->lang->line('gfx_message_user_updated'));
		//header('Location: ' . site_url($data->row()->name));
		print json_encode(
			array(
				'message' => array(
					'type' => 'highlight',
					'icon' => 'info',
					'msg' => $this->lang->line('gfx_message_feature_updated')
				)
			)
		);
	}
}

/* End of file feature.php */
/* Location: ./system/applications/controller/feature.php */ 