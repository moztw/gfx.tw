<?php

class Editor extends Controller {
	function Editor() {
		parent::Controller();
		$this->load->scaffolding('u2f');
		$this->load->database();
	}
	function index() {
		$this->load->helper('gfx');
		if (!checkAuth(false, false, 'flashdata')) {
			header('Location: ' . base_url());
			exit();
		}

		$this->load->helper('form');
		$user = $this->db->query('SELECT * FROM users WHERE `id` = ' . $this->session->userdata('id') . ' LIMIT 1');
		if ($user->num_rows() === 0) {
			//Rare cases where session exists but user got deleted.
			session_data_unset(false);
			flashdata_message('no_such_user');
			header('Location: ' . base_url());
			exit();
		}
		$U = $user->row_array();
		$user->free_result();
		unset($user);
		if (substr($U['name'], 0, 8) === '__temp__') {
			$U['name'] = '';
		}
		$allfeatures = $this->db->query('SELECT `id`, `name`, `title`, `description` FROM `features` ORDER BY `order` ASC;');
		$F = array();
		foreach ($allfeatures->result_array() as $feature) {
			for ($i = 0; $i < 3; $i++) {
				if ($feature['id'] === $U['feature_' . $i]) $feature['user_order'] = $i;
			}
			$F[] = $feature;
		}
		$allfeatures->free_result();
		unset($allfeatures, $feature);
		$addons = $this->db->query('SELECT t1.*, t2.group_id FROM addons t1, u2a t2 '
		. 'WHERE t2.addon_id = t1.id AND t2.user_id = ' . $U['id'] . ' ORDER BY t2.order ASC;');
		$A = array();
		foreach ($addons->result_array() as $addon) {
			if (!isset($A[$addon['group_id']])) $A[$addon['group_id']] = array();
			$A[$addon['group_id']][] = $addon;
		}
		unset($addons, $addon);
		$groups = $this->db->query(
			'SELECT t1.id, t1.name, t1.title, t1.description, G.user_id, G.order FROM groups t1 '
			. 'LEFT OUTER JOIN '
			. '( SELECT S.id, K.user_id, K.order FROM groups AS S, u2g AS K ' 
			. 'WHERE S.id = K.group_id AND K.user_id = ' . $this->session->userdata('id') . ') AS G '
			. 'ON t1.id = G.id ORDER BY G.user_id DESC, G.order ASC, t1.order ASC;');
		$G = array();
		foreach ($groups->result_array() as $group) {
			$G[] = $group;
			if (!isset($A[$group['id']])) $A[$group['id']] = array();
		}
		unset($groups, $group);

		$data = array(
			'meta' => $this->load->view($this->config->item('language') . '/editor/meta.php', $U, true),
			'content' => $this->load->view($this->config->item('language') . '/editor/content.php', array_merge($U, array('allfeatures' => $F, 'allgroups' => $G, 'addons' => $A)), true),
			'script' => '	<script type="text/javascript" src="./js/page.editor.js' . $this->config->item('gfx_suffix') . '" charset="UTF-8"></script>
	<script type="text/javascript" src="./swfupload/swfupload-min.js' . $this->config->item('gfx_suffix') . '" charset="UTF-8"></script>',
			'db' => 'content '
		);

		if ($this->session->userdata('admin') === 'Y') {
			$this->load->_ci_cached_vars = array();
			$data['admin'] = $this->load->view($this->config->item('language') . '/editor/admin.php', $U, true);
		}

		$this->load->library('parser');
		$this->parser->page($data, $this->session->userdata('id'), $U);
	}
	function save() {
		$this->load->helper('gfx');
		checkAuth(true, false, 'json');

		if ($this->input->post('name') === false) {
			json_message('EDITOR_NAME_EMPTY');
		}

		$data = array(
			'name' => $this->input->post('name')
		);
		
		if ($this->input->post('title')) {
			$data['title'] = $this->input->post('title');
		}

		if ($this->input->post('ready')) {
			$data['ready'] = $this->input->post('ready');
		}
		
		if ($this->input->post('avatar')) {
			$a = $this->input->post('avatar');
			switch ($a) {
				case '(default)':
				$data['avatar'] = '';
				break;
				case '(gravatar)':
				$data['avatar'] = '(gravatar)';
				break;
				case '(myidtw)':
				$data['avatar'] = '(myidtw)';
				break;
				default:
				if (preg_match('/^[0-9a-z\/]+\.(gif|jpg|jpeg|png)$/i', $a) && file_exists('./useravatars/' . $a)) {
					$data['avatar'] = $a;
				} else {
					json_message('EDITOR_AVATAR_ERROR');
				}
			}
		}

		$this->load->config('gfx');
		if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $this->input->post('name'))
			|| strlen($this->input->post('name')) < 3
			|| strlen($this->input->post('name')) > 200
			|| substr($this->input->post('name'), 0, 8) === '__temp__'
			|| in_array($this->input->post('name'), $this->config->item('gfx_badname'))
			|| $this->db->query('SELECT `id` FROM `users` '
				. 'WHERE `name` = ' . $this->db->escape($this->input->post('name'))
				. ' AND `id` != ' . $this->session->userdata('id'))
				->num_rows() !== 0) {
			json_message('EDITOR_NAME_BAD');
		}
		$this->db->update('users', $data, array('id' => $this->session->userdata('id')));
		if ($this->db->affected_rows() === 1) {
			$infoChanged = true;
		} else {
			$infoChanged = false;
		}
		$this->session->set_userdata(array('name' => $this->input->post('name')));
		$data = array();
		if ($this->input->post('email') !== false) $data['email'] = $this->input->post('email');
		if (
			$this->input->post('web') !== false &&
			(
				in_array(
					strtolower(substr($this->input->post('web'), 0, strpos($this->input->post('web') ,':'))),
					array('http', 'https', 'telnet', 'irc', 'ftp', 'nntp')
				) ||
				$this->input->post('web') === ''
			)
		) {
			$data['web'] = $this->input->post('web');
		}
		if (
			$this->input->post('blog') !== false &&
			(
				in_array(
					strtolower(substr($this->input->post('blog'), 0, strpos($this->input->post('blog') ,':'))),
					array('http', 'https', 'telnet', 'irc', 'ftp', 'nntp')
				) ||
				$this->input->post('blog') === ''
			)
		) {
			$data['blog'] = $this->input->post('blog');
		}
		if ($this->input->post('bio') !== false) $data['bio'] = $this->input->post('bio');
		if ($this->input->post('forum') !== false
			&& $this->input->post('forum') !== '(keep-the-forum-username)'
			&& $this->input->post('forum') !== '') {
			$F = explode('::', $this->input->post('forum'), 3);
			if (count($F) === 3 && $F[0] === substr(md5($F[1] . $F[2] . substr($this->input->post('token'), 0, 16) . $this->config->item('gfx_forum_auth_token')), 16)) {
				$data['forum_id'] = $F[1];
				$data['forum_username'] = $F[2];
			} else {
				json_message('EDITOR_FORUM_CODE');
			}
		} elseif ($this->input->post('forum') === '') {
				$data['forum_id'] = '';
				$data['forum_username'] = '';
		}		
		if ($this->input->post('features')) {
			$F = $this->input->post('features');
			$v = 0;
			for ($i = 0; $i < 3; $i++) {
				if (!isset($F[$i+1]) || !is_numeric($F[$i+1])) {
					json_message('EDITOR_FEATURE_ERROR');
				}
				$data['feature_' . $i] = $F[$i+1];
				$v |= pow(2, $F[$i+1]);
			}
			$data['features_victor'] = $v;
		}

		if ($data) $this->db->update('users', $data, array('id' => $this->session->userdata('id')));

		if ($this->input->post('groups')) {
			$query = $this->db->query('SELECT `id` FROM `u2g` WHERE `user_id` = ' . $this->session->userdata('id') . ' ORDER BY `order` ASC;');
			$i = 0;
			foreach ($this->input->post('groups') as $g) { // don't care about the keys
				if (!is_numeric($g)) {
					json_message('EDITOR_GROUP_ERROR');
				}
				if ($i < $query->num_rows()) {
					$row = $query->row_array($i);
					$this->db->update('u2g', array('group_id' => $g, 'order' => $i+1), array('id' => $row['id']));
				} else {
					$this->db->insert('u2g', array('group_id' => $g, 'order' => $i+1, 'user_id' => $this->session->userdata('id')));
				}
				$i++;
			}
			while ($i < $query->num_rows()) {
				if ($row = $query->row_array($i)) {
					$this->db->delete('u2g', array('id' => $row['id']));
				}
				$i++;
			}
		}
		if ($this->input->post('addons')) {
			$query = $this->db->query('SELECT `id` FROM `u2a` WHERE `user_id` = ' . $this->session->userdata('id') . ' ORDER BY `order` ASC;');
			$i = 0;
			foreach ($this->input->post('addons') as $a) { // don't care about the keys
				if (!is_numeric($a['id'])) {
					json_message('EDITOR_ADDON_ERROR');
				}
				if ($i < $query->num_rows()) {
					$row = $query->row_array($i);
					$this->db->update('u2a', array('addon_id' => $a['id'], 'group_id' => $a['group'], 'order' => $i+1), array('id' => $row['id']));
				} else {
					$this->db->insert('u2a', array('addon_id' => $a['id'], 'group_id' => $a['group'], 'order' => $i+1, 'user_id' => $this->session->userdata('id')));
				}
				$i++;
			}
			while ($i < $query->num_rows()) {
				if ($row = $query->row_array($i)) {
					$this->db->delete('u2a', array('id' => $row['id']));
				}
				$i++;
			}
		}
		$this->load->library('cache');
		$this->cache->remove(strtolower($this->input->post('name')), 'user');
		$this->cache->remove($this->session->userdata('id'), 'header');
		
		//TBD: hide user id from user
		$d = './userstickers/' . dechex(intval($this->session->userdata('id')) >> 12) . '/' . dechex(intval($this->session->userdata('id') & (pow(2,12)-1))) . '/';
		@mkdir($d, 0755, true);

		if (
			($infoChanged
				|| $this->input->post('features')
				|| !file_exists($d . 'featurecard.png')
				|| !file_exists($d . 'featurecard-h.png')
			) && $data['ready']
		) {
			$F = array();
			for ($i = 0; $i < 3; $i++) {
				$feature = $this->db->query('SELECT features.name, features.title, features.description FROM features ' 
				. 'INNER JOIN users ON features.id = users.feature_' . $i . ' WHERE users.id = ' . $this->session->userdata('id') . ';');
				$F[] = $feature->row_array();
				$feature->free_result();
			}
			unset($feature);
			
			if (!$this->input->post('title')) {
				$user = $this->db->query('SELECT title FROM `users` WHERE `id` = ' . $this->session->userdata('id') . ';');
				$title = $user->row()->title;
			} else {
				$title = $this->input->post('title');
			}
			
			// featurecard.html
			file_put_contents(
				$d . 'featurecard.html',
				$this->load->view(
					$this->config->item('language') . '/userstickers/featurecard.php',
					array(
						'name' => $this->input->post('name'),
						'title' => $title,
						'features' => $F
					),
					true
				)
			);
			
			// featurecard.png
			$card = imagecreatefromgd2(
				'./images/' . $this->config->item('language') . '/featurecard.gd2'
			);
			/* $D = imagettfbbox(14, 0, $this->config->item('gfx_sticker_font'), $title);*/
			imagettftext(
				$card,
				14,
				0,
				30, /*(200-($D[2]-$D[0]))/2,*/ /* centered */
				197,
				imagecolorallocate($card, 0, 0, 0),
				$this->config->item('gfx_sticker_font'),
				$title
			);
			if (file_exists('./stickerimages/features/' . $F[0]['name'] . '.gd2')) {
				imagecopy(
					$card,
					imagecreatefromgd2('./stickerimages/features/' . $F[0]['name'] . '.gd2'),
					55, 75,
					0, 0, 150, 20);
			}
			if (file_exists('./stickerimages/features/' . $F[1]['name'] . '.gd2')) {
				imagecopy(
					$card,
					imagecreatefromgd2('./stickerimages/features/' . $F[1]['name'] . '.gd2'),
					55, 97,
					0, 0, 150, 20);
			}
			if (file_exists('./stickerimages/features/' . $F[2]['name'] . '.gd2')) {
				imagecopy(
					$card,
					imagecreatefromgd2('./stickerimages/features/' . $F[2]['name'] . '.gd2'),
					55, 119,
					0, 0, 150, 20);
			}
			imagealphablending($card, true);
			imagesavealpha($card, true);
			imagepng($card, $d . 'featurecard.png');
			imagedestroy($card);

			// featurecard-h.png
			$card = imagecreatefromgd2(
				'./images/' . $this->config->item('language') . '/featurecard-h.gd2'
			);
			/* $D = imagettfbbox(14, 0, $this->config->item('gfx_sticker_font'), $title);*/
			imagettftext(
				$card,
				11.5,
				0,
				350, /*(200-($D[2]-$D[0]))/2,*/ /* centered */
				28,
				imagecolorallocate($card, 0, 0, 0),
				$this->config->item('gfx_sticker_font'),
				$title
			);
			if (file_exists('./stickerimages/features/' . $F[0]['name'] . '.gd2')) {
				imagecopy(
					$card,
					imagecreatefromgd2('./stickerimages/features/' . $F[0]['name'] . '.gd2'),
					186, 9,
					0, 0, 150, 20);
			}
			if (file_exists('./stickerimages/features/' . $F[1]['name'] . '.gd2')) {
				imagecopy(
					$card,
					imagecreatefromgd2('./stickerimages/features/' . $F[1]['name'] . '.gd2'),
					186, 31,
					0, 0, 150, 20);
			}
			if (file_exists('./stickerimages/features/' . $F[2]['name'] . '.gd2')) {
				imagecopy(
					$card,
					imagecreatefromgd2('./stickerimages/features/' . $F[2]['name'] . '.gd2'),
					272, 9,
					0, 0, 150, 20);
			}
			imagealphablending($card, true);
			imagesavealpha($card, true);
			imagepng($card, $d . 'featurecard-h.png');
			imagedestroy($card);

			//smallsticker.png
			$sticker = imagecreatefromgd2(
				'./images/' . $this->config->item('language') . '/smallsticker.gd2'
			);
			/* $D = imagettfbbox(14, 0, $this->config->item('gfx_sticker_font'), $title);*/
			imagettftext(
				$sticker,
				13,
				0,
				67, /*(200-($D[2]-$D[0]))/2,*/ /* centered */
				70,
				imagecolorallocate($sticker, 0, 0, 0),
				$this->config->item('gfx_sticker_font'),
				$title
			);
			imagealphablending($sticker, true);
			imagesavealpha($sticker, true);
			imagepng($sticker, $d . 'smallsticker.png');
			imagedestroy($sticker);

			//smallsticker2.png
			$sticker = imagecreatefromgd2(
				'./images/' . $this->config->item('language') . '/smallsticker2.gd2'
			);
			/* $D = imagettfbbox(14, 0, $this->config->item('gfx_sticker_font'), $title);*/
			imagettftext(
				$sticker,
				11.5,
				0,
				57, /*(200-($D[2]-$D[0]))/2,*/ /* centered */
				65,
				imagecolorallocate($sticker, 0, 0, 0),
				$this->config->item('gfx_sticker_font'),
				$title
			);
			imagealphablending($sticker, true);
			imagesavealpha($sticker, true);
			imagepng($sticker, $d . 'smallsticker2.png');
			imagedestroy($sticker);
		}

		header('Content-Type: text/javascript');
		print json_encode(
			array(
				'name' => $this->input->post('name')
			)
		);
	}
	/* Upload Avatar */
	function upload() {
		//Can't check session here becasue of Flash plugin bug.
		//We do not and unable to verify user information, therefore we only process the image are return the filename; the actual submision of avatar is done by save() function.
		$subdir = date('Y/m/');
		@mkdir('./useravatars/' . $subdir, 755, true);
		$this->load->library(
			'upload',
			array(
				'upload_path' => './useravatars/' . $subdir,
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
				json_message('EDITOR_AVATAR_WRONG_FILE_TYPE');
			}
			if ($width > 500 || $height > 500) {
				unlink($data['full_path']);
				json_message('EDITOR_AVATAR_SIZE_TOO_LARGE');
			}
			//Success!
			header('Content-Type: text/javascript');
			print json_encode(
				array(
					'img' => $subdir . $data['file_name']
				)
			);
		}
	}
}


/* End of file editor.php */
/* Location: ./system/applications/controller/editor.php */ 
