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
		$allfeatures = $this->db->query(
			'SELECT features.id, features.name, features.title, features.description, G.user_id, G.order FROM features '
			. 'LEFT OUTER JOIN '
			. '( SELECT S.id, K.user_id, K.order FROM features AS S, u2f AS K WHERE S.id = K.feature_id AND K.user_id = ' . $this->session->userdata('id') . ' ) AS G '
			. 'ON features.id = G.id ORDER BY features.order ASC;');
		$A = array();
		foreach ($allfeatures->result_array() as $feature) {
			$A[] = $feature;
		}
		$this->load->view('editor', array_merge($user->row_array(), array('allfeatures' => $A, 'auth' => array_merge($user->row_array(), array('session_id' =>$this->session->userdata('session_id'))))));
	}
	function save() {
		if (!$this->session->userdata('id')) {
			header('Location: ' . base_url());
			exit();
		}
		if (!$this->input->post('name')) {
			header('Content-Type: text/javascript');
			print json_encode(array('error' => 'NO NAME'));
			exit();
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
		$query = $this->db->query('SELECT `id` FROM `users` '
			. 'WHERE `name` = ' . $this->db->escape($this->input->post('name')) . ' AND `id` != ' . $this->session->userdata('id'));
		if (in_array($this->input->post('name'), $this->badname) || $query->num_rows() !== 0) {
			header('Content-Type: text/javascript');
			print json_encode(array('error' => 'BAD NAME'));
			exit();
		}
		$this->db->update('users', $data, array('id' => $this->session->userdata('id')));
		if ($this->input->post('features')) {
			foreach ($this->input->post('features') as $o => $f) {
				//TBD: check # of features selected.
				$query = $this->db->get_where('u2f', array('user_id' => $this->session->userdata('id'), 'order' => $o));
				if ($query->row()->feature_id !== $f) {
					$this->db->delete('u2f', array('id' => $query->row()->id));
					$this->db->insert('u2f', array('feature_id' => $f, 'order' => $o, 'user_id' => $this->session->userdata('id')));
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
		$this->load->library(
			'upload',
			array(
				'upload_path' => './useravatars/',
				'allowed_types' => 'exe|jpg|gif|png', //Flash bug reported by SWFUpload (Flash always send mime_types as application/octet-stream)
				'max_size' => 1024,
				'encrypt_name' => true
			)
		);

		header('Content-Type: text/javascript');

		if (!$this->upload->do_upload('Filedata')) {
			print json_encode(array('error' => $this->upload->display_errors('','')));
//			print json_encode(array('error' => json_encode($this->upload->data())));
		} else {
			$data = $this->upload->data();
			//Check is image of not ourselves
			list($width, $height, $type) = getimagesize($data['full_path']);
			if (!in_array($type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
				print json_encode(array('error' => 'Wrong Type.'));
			} elseif ($width > 500 || $height > 500) {
				print json_encode(array('error' => 'Too Large.'));
			} else {
				//Success!
				print json_encode(array('img' => $data['file_name']));
			}
		}
	}
}


/* End of file editor.php */
/* Location: ./system/applications/controller/editor.php */ 