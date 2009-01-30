<?php

class Sticker extends Controller {
	function sticker() {
		parent::Controller();
		$this->load->database();
	}
	function index() {
		if (!$this->session->userdata('id')) {
			header('Location: ' . base_url());
			exit();
		}
		$user = $this->db->query('SELECT * FROM users WHERE `id` = ' . $this->session->userdata('id') . ' LIMIT 1');
		if ($user->num_rows() === 0) {
			//Rare cases where session exists but got deleted.
			$this->session->sess_destroy();
			header('Location: ' . base_url());
			exit();
		}
		$this->load->view('sticker/head.php');
		$this->load->_ci_cached_vars = array(); //Clean up cached vars
		$this->load->view('header.php', $user->row_array()); //Can be fetched from cache but not worth the effort.
		$this->load->_ci_cached_vars = array(); //Clean up cached vars
		$this->load->view('sticker/body.php');
		$this->load->_ci_cached_vars = array(); //Clean up cached vars
		$this->load->view('footer.php', array('db' => 'everything'));
	}
}