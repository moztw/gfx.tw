<?php

class GFX_Parser extends CI_Parser {
	function page($data = array(), $session_id = false, $user = array()) {
		$CI =& get_instance();

		$CI->load->config('gfx');

		//If some of the value is missing in $data
		if (!isset($data['messages'])) {
			$data['messages'] = array();
		}
		$data['messages'] = array_merge(
			$CI->config->item('gfx_site_wide_message'),
			$data['messages']
		);
		$data = array_merge(
			array(
				'meta' => '',
				'admin' => '',
				'content' => '<p>Error: No Content.</p>',
				'script' => '',
				'db' => ''
			),
			$data
		);
		
		//Get message from flashdata
		$message = $CI->session->flashdata('message');
		if ($message) {
			$message = explode(':', $message ,3);
			$data['messages'][] = array(
				'type' => $message[0], 
				'icon' => $message[1],
				'message' => $message[2]
			);
		}

		//Prepare header
		$CI->load->library('cache');
		$data['header'] = $CI->cache->get($session_id, 'header');
		if (!$data['header']) {
			if (!$user && $session_id) {
				$CI->load->database();
				$auth = $CI->db->query('SELECT * FROM users WHERE `id` = ' . $session_id . ' LIMIT 1');
				$user = $auth->row_array();
			}
			$data['db'] .= 'header ';
			$CI->load->_ci_cached_vars = array(); //Clean up cached vars
			$data['header'] = $CI->load->view($CI->config->item('language') . '/header.php', $user, true);
			$CI->cache->save($session_id, $data['header'], 'header', $CI->config->item('gfx_cache_time'));
		}

		//Print out the entire page
		$this->parse($CI->config->item('language') . '/page_template', $data);
	}
}

/* End of file GFX_Parser.php */
/* Location: .applications/libraries/GFX_Parser.php */ 