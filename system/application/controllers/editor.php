<?php

class Editor extends Controller {
	var $badname = array(
		'editor', 
		'userpage', 
		'feature', 
		'auth', 
		'about', 
		'lobby', 
		'view', 
		'sticker', 
		'stickers', 
		'users', 
		'blog', 
		'events', 
		'event', 
		'doc', 
		'docs', 
		'share', 
		'badge', 
		'home',
		'js',
		'useravatars',
		'system',
		'images'
	);
	function Editor() {
		parent::Controller();
		$this->load->scaffolding('u2f');
		$this->load->database();
	}
	function index() {
		if (!$this->session->userdata('id')) {
			header('Location: ' . base_url());
			exit();
		}
		$this->load->helper('form');
		$user = $this->db->query('SELECT * FROM users WHERE `id` = ' . $this->session->userdata('id') . ' LIMIT 1');
		if ($user->num_rows() === 0) {
			//Rare cases where session exists but got deleted.
			$this->session->sess_destroy();
			header('Location: ' . base_url());
			exit();
		}
		$allfeatures = $this->db->query(
			'SELECT features.id, features.name, features.title, features.description, G.user_id, G.order FROM features '
			. 'LEFT OUTER JOIN '
			. '( SELECT S.id, K.user_id, K.order FROM features AS S, u2f AS K WHERE S.id = K.feature_id AND K.user_id = ' . $this->session->userdata('id') . ' ) AS G '
			. 'ON features.id = G.id ORDER BY features.order ASC;');
		$A = array();
		foreach ($allfeatures->result_array() as $feature) {
			$A[] = $feature;
		}
		$this->load->view('editor/head.php');
		$this->load->_ci_cached_vars = array(); //Clean up cached vars
		$this->load->view('header.php', $user->row_array()); //Can be fetched from cache but not worth the effort.
		$this->load->_ci_cached_vars = array(); //Clean up cached vars
		$this->load->view('editor/body.php', array_merge($user->row_array(), array('allfeatures' => $A)));
		$this->load->_ci_cached_vars = array(); //Clean up cached vars
		$this->load->view('footer.php', array('db' => 'everything'));
	}
	function save() {
		if (!$this->session->userdata('id')) {
			$this->json_error('Not Logged In.', 'EDITOR_NOT_LOGGED_IN');
		}
		if (!$this->input->post('name')) {
			$this->json_error('No Name Provided', 'EDITOR_SAVE_NO_NAME');
		}
		if ($this->input->post('token') !== md5($this->session->userdata('id') . '--secret-token-good-day-fx')) {
			$this->json_error('Wrong Token', 'EDITOR_SAVE_ERROR_TOKEN');
		}
		$data = array();
		if ($this->input->post('name')) $data['name'] = $this->input->post('name');
		if ($this->input->post('title')) $data['title'] = $this->input->post('title');
		if ($this->input->post('avatar')) {
			$a = $this->input->post('avatar');
			if (in_array($a, array('(gravatar)', '(default)')) || file_exists('./useravatars/' . $a)) {
				if ($a === '(default)') $a = '';
				$data['avatar'] = $a;
			}
		}
		if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $this->input->post('name'))
			|| strlen($this->input->post('name')) > 200
			|| substr($this->input->post('name'), 0, 8) === '__temp__'
			|| in_array($this->input->post('name'), $this->badname)
			|| $this->db->query('SELECT `id` FROM `users` '
				. 'WHERE `name` = ' . $this->db->escape($this->input->post('name'))
				. ' AND `id` != ' . $this->session->userdata('id'))
				->num_rows() !== 0) {
			$this->json_error('Bad Name', 'EDITOR_BAD_NAME');
		}
		$this->db->update('users', $data, array('id' => $this->session->userdata('id')));
		//Won't work because affected rows is 0 when nothing is actually changed.
		//if ($data && $this->db->affected_rows() !== 1) {
		//	$this->json_error('error' . $this->db->affected_rows());
		//}
		if ($this->input->post('features')) {
			foreach ($this->input->post('features') as $o => $f) {
				if (!is_numeric($f) || !is_numeric($o)) {
					$this->json_error('Feature Content Error.', 'EDITOR_SAVE_FEATURE_ERROR');
				}
				if ($o > 3) {
					$this->json_error('To Many Features?', 'EDITOR_TOO_MANY_FEATURES');
				}
				$query = $this->db->get_where('u2f', array('user_id' => $this->session->userdata('id'), 'order' => $o));
				if ($query->num_rows() === 0) {
					$this->db->insert('u2f', array('feature_id' => $f, 'order' => $o, 'user_id' => $this->session->userdata('id')));
				} elseif ($query->row()->feature_id !== $f) {
					$this->db->update('u2f', array('feature_id' => $f), array('id' => $query->row()->id));
					//$this->db->delete('u2f', array('id' => $query->row()->id));
					//$this->db->insert('u2f', array('feature_id' => $f, 'order' => $o, 'user_id' => $this->session->userdata('id')));
				}
			}
		}
		$this->load->library('cache');
		$this->cache->remove($this->input->post('name'), 'userpage-head');
		$this->cache->remove($this->input->post('name'), 'userpage');
		$this->cache->remove($this->session->userdata('id'), 'header');
		header('Content-Type: text/javascript');
		print json_encode(array('name' => $this->input->post('name')));
	}
	/* Upload Avatar */
	function upload() {
		//Can't check session here becasue of Flash plugin bug.
		//We do not and unable to verify user information, therefore we only process the image are return the filename; the actual submision of avatar is done by save() function.
		$this->load->library(
			'upload',
			array(
				'upload_path' => './useravatars/',
				'allowed_types' => 'exe|jpg|gif|png', //'exe' due to Flash bug reported by SWFUpload (Flash always send mime_types as application/octet-stream)
				'max_size' => 1024,
				'encrypt_name' => true
			)
		);
		if (!$this->upload->do_upload('Filedata')) {
			print json_encode(array('error' => $this->upload->display_errors('','')));
//			print json_encode(array('error' => json_encode($this->upload->data())));
		} else {
			$data = $this->upload->data();
			//Check is image or not ourselves
			list($width, $height, $type) = getimagesize($data['full_path']);
			if (!in_array($type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
				unlink($data['full_path']);
				$this->json_error('Wrong file type.', 'EDITOR_AVATAR_WRONG_FILE_TYPE');
			}
			if ($width > 500 || $height > 500) {
				unlink($data['full_path']);
				$this->json_error('Image Size Too Large.', 'EDITOR_AVATAR_SIZE_TOO_LARGE');
			}
			//Success!
			header('Content-Type: text/javascript');
			print json_encode(array('img' => $data['file_name']));
		}
	}
	function json_error($msg, $tag = false) {
		header('Content-Type: text/javascript');
		if ($tag) print json_encode(array('error' => $msg, 'tag' => $tag));
		else print json_encode(array('error' => $msg));
		exit();
	}
}


/* End of file editor.php */
/* Location: ./system/applications/controller/editor.php */ 