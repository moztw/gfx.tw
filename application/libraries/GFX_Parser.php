<?php

class GFX_Parser extends CI_Parser {
	function page($data = array(), $session_id = false, $user = array()) {
		$CI =& get_instance();

		$CI->load->config('gfx');

		//If some of the value is missing in $data
		if (!isset($data['messages'])) {
			$data['messages'] = array();
		} else {
			foreach ($data['messages'] as $k => $v) {
				$data['messages'][$k] = array_merge(
					array(
						'type' => '',
						'icon' => '',
						'message' => '',
						'announcement' => ''
					),
					$v
				);
			}
		}
		if ($CI->config->item('gfx_site_wide_message')
			&& !$CI->session->userdata('hide_announcement')) {
			foreach ($CI->config->item('gfx_site_wide_message') as $M) {
				$M['announcement'] = ' announcement';
				array_unshift($data['messages'], $M);
			}
		}
		$data = array_merge(
			array(
				'meta' => '',
				'admin' => '',
				'content' => '<p>Error: No Content.</p>',
				'script' => '',
				'db' => '',
				'expiry' => 0
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
				'message' => ($CI->lang->line('gfx_message_' . $message[2]))?$CI->lang->line('gfx_message_' . $message[2]):$message[2],
				'announcement' => ''
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
			$expiry = $CI->cache->save($session_id, $data['header'], 'header', $CI->config->item('gfx_cache_time'));
		} else {
			$expiry = $CI->cache->get_expiry($session_id, 'header');
		}

		//Send ETag only if there is no flashdata and the controller asks to do so.
		if (!$message && $data['expiry']) {
			header(
				'ETag: ' . 
				md5(
					$data['expiry']
					. ':' . $expiry
					. ':' . $session_id
					. ':' . ($CI->session->userdata('hide_announcement'))?'Y':'N'
				)
			);
			//header('Last-Modified: ' . date('r', max($data['expiry'], $expiry)));
		}
		
		//Print out the entire page
		$this->parse($CI->config->item('language') . '/page_template', $data);
	}
}

/* End of file GFX_Parser.php */
/* Location: .applications/libraries/GFX_Parser.php */ 