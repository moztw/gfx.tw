<?php

class Sticker extends Controller {
	function sticker() {
		parent::Controller();
		$this->load->database();
	}
	function index() {
		if (!$this->session->userdata('id')) {
			$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_sticker_notlogin'));
			header('Location: ' . base_url());
			exit();
		}
		$user = $this->db->query('SELECT * FROM users WHERE `id` = ' . $this->session->userdata('id') . ' LIMIT 1');
		if ($user->num_rows() === 0) {
			//Rare cases where session exists but got deleted.
			$this->session->unset_userdata('id');
			header('Location: ' . base_url());
			exit();
		}
		if (substr($user->row()->name, 0, 8) === '__temp__') {
			$this->session->set_flashdata('message', 'error:alert:' . $this->lang->line('gfx_message_sticker_nopage'));
			header('Location: ' . base_url());
			exit();
		}
		$data = array(
			'meta' => $this->load->view('sticker/meta.php', $user->row_array(), true),
			'content' => $this->load->view('sticker/content.php', $user->row_array(), true),
			'db' => 'content '
		);
		$this->load->library('parser');
		$this->parser->page($data, $this->session->userdata('id'), $user->row_array());
	}
}