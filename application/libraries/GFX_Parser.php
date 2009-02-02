<?php

class GFX_Parser extends CI_Parser {
	function page($data = array(), $session_id = false, $user = array()) {
		$CI =& get_instance();

		//If some of the value is missing in $data
		$data = array_merge(
			array(
				'meta' => '',
				'content' => '<p>Error: No Content.</p>',
				'db' => ''
			),
			$data
		);

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
			$data['header'] = $CI->load->view('header.php', $user, true);
			$CI->cache->save($session_id, $data['header'], 'header', 60);
		}

		//Print out the entire page
		$this->parse('page_template', $data);
	}
}

/* End of file GFX_Parser.php */
/* Location: .applications/libraries/GFX_Parser.php */ 