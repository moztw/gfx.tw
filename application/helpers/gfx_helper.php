<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function checkETag($cache_id, $cache_group) {
	$CI =& get_instance();
	if (isset($_SERVER['HTTP_IF_NONE_MATCH'])
		&& !$CI->session->flashdata('message')
		&& (
			trim($_SERVER['HTTP_IF_NONE_MATCH'])
			=== md5(
				$CI->cache->get_expiry($cache_id, $cache_group)
				. ':' . $CI->cache->get_expiry($CI->session->userdata('id'), 'header')
				. ':' . $CI->session->userdata('id')
				. ':' . (($CI->session->userdata('hide_announcement'))?'Y':'N')
			)
		)
	) {
		header("HTTP/1.1 304 Not Modified");
		exit();
	}
}

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
			$data = $CI->db->query('SELECT `id` FROM `users` WHERE `admin` = \'Y\' AND `id` = ' . $CI->session->userdata('id') . ';');
			if ($data->num_rows() === 0) {
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
function checkChallenge($errorType = '') {
	$CI =& get_instance();
	while (true) {
		if (!$CI->input->post('challenge')
			|| !$CI->input->post('answer')) {
			$isValid = false;
			break;
		}
		$C = explode(':', $CI->input->post('challenge'), 2);
		if (count($C) !== 2) {
			$isValid = false;
			break;
		}
		$CI->load->config('gfx');
		if (intval($C[0]) < time() - 3*60*60
			|| $C[1] !== md5($CI->input->post('answer') . ':' . intval($C[0]) . ':' . $CI->config->item('gfx_token'))) {
			$isValid = false;
			break;
		}
		$isValid = true;
		break;
	}
	if (!$isValid) {
		switch ($errorType) {
			case 'json':
				json_message('captcha_validation_failed');
			break;
			case 'flashdata':
				flashdata_message('captcha_validation_failed');
			break;
		}
	}
	return $isValid;
}
function avatarURL($avatar, $email, $conn = '&amp;') {
	if (!$avatar) {
		$avatar = '/images/avatar-default.gif';
	} elseif ($avatar === '(gravatar)') {
		$avatar = 'http://www.gravatar.com/avatar/' . md5($email) . '?s=65' . $conn . 'r=g' . $conn . 'd=wavatar';
	} else {
		$avatar = '/useravatars/' . $avatar;
	}
	return $avatar;
}

function session_data_set($data, $msg = true) {
	$CI =& get_instance();
	if (isset($data['name']) && substr($data['name'], 0, 8) === '__temp__') {
		unset($data['name']);
		if ($CI->session->userdata('name')) {
			$CI->session->unset_userdata('name');
		}
	}
	$CI->session->set_userdata($data);
	if ($msg) {
		flashdata_message('auth_login', 'highlight', 'info');
	}
}
function session_data_unset($msg = true) {
	$CI =& get_instance();
	$CI->session->unset_userdata(
		array(
			'id' => '',
			'admin' => '',
			'name' => '',
			'hide_announcement' => ''
		)
	);
	if ($msg) {
		flashdata_message('auth_logout', 'highlight', 'info');
	}
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
