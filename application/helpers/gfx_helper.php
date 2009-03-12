<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function checkAuth($checkOrigin = false, $checkAdmin = false, $errorType = '') {
	$CI =& get_instance();
	$islogin = true;
	/* check is loged in or not */
	if (!$CI->session->userdata('id')) {
		$islogin = false;
	}
	if (!$islogin) {
		switch ($errorType) {
			case 'json':
				json_message('not_logged_in');
			break;
			case 'flashdata':
				flashdata_message('not_logged_in');
			break;
		}
		return false;
	}
	if (!$checkOrigin && !$checkAdmin) {
		return true;
	}

	$CI->load->config('gfx');
	if ($checkOrigin && $CI->input->post('token') !== md5($CI->session->userdata('id') . $CI->config->item('gfx_token'))) {
		$islogin = false;
	}
	if ($checkAdmin) {
		if ($CI->session->userdata('admin') !== 'Y') {
			$islogin = false;
		} else {
			$CI->load->database();
			//query
			$data = $CI->db->query('SELECT `admin` FROM `users` WHERE `id` = ' . $CI->session->userdata('id') . ';');
			if ($data->num_rows() === 0 || $data->row()->admin !== 'Y') {
				$islogin = false;
			}
			$data->free_result();
		}
	}
	if (!$islogin) {
		switch ($errorType) {
			case 'json':
				json_message('login_validation_failed');
			break;
			case 'flashdata':
				flashdata_message('login_validation_failed');
			break;
		}
	}
	return $islogin;
}
function flashdata_message($tag = 'unknown_message', $type = 'error', $icon = 'alert') {
	$CI =& get_instance();
	$CI->session->set_flashdata(
		'message',
		$type . ':' . $icon . ':' . $tag
	);
}
function json_message($tag = 'unknown_message', $type = 'error', $icon = 'alert') {
	$CI =& get_instance();
	header('Content-Type: text/javascript');
	$message = array(
		'type' => $type,
		'icon' => $icon,
		'tag' => strtoupper($tag)
	);
	if ($CI->lang->line('gfx_message_' . $tag)) {
		$message['msg'] = $CI->lang->line('gfx_message_' . $tag);
	} else {
		$message['msg'] = 'Unknown message (' . $tag . ').';
	}
	print json_encode(array('message' => $message));
	exit();
}

/* End of file gfx_helper.php */
/* Location: ./system/applications/helpers/gfx_helper.php */ 