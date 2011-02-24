<?php

class Feature extends Controller {
	function Feature() {
		parent::Controller();
		$this->load->scaffolding('features');
	}
	function index() {
		//TBD: a nice feature list.
		show_404();
		//header('Content-Type: text/plain');
		//print 'TBD';
	}
	function view($id, $type = '') {
		/* Redirect numeric id */
		if (is_numeric($id)) {
			$this->load->database();
			$query = $this->db->query('SELECT `name` FROM features WHERE `id` = ' . $this->db->escape($id) . ' LIMIT 1');
			if ($query->num_rows() === 0) {
				show_404();
			}
			switch ($type) {
				case 'inframe':
				header('Location: ' . site_url('feature/' . $query->row()->name . '/inframe'));
				break;
				default:
				header('Location: ' . site_url('feature/' . $query->row()->name));
				break;
			}
			return;
		}
		$this->load->library('cache');
		switch ($type) {
			case 'inframe':
			if (isset($_SERVER['HTTP_IF_NONE_MATCH'])
				&& (
					trim($_SERVER['HTTP_IF_NONE_MATCH'])
					=== md5(
						$this->cache->get_expiry($id, 'feature-inframe')
					)
				)
			) {
				header("HTTP/1.1 304 Not Modified");
				return;
			}

			$data = $this->cache->get($id, 'feature-inframe');
			
			if (!$data) {
				$this->load->database();
				$feature = $this->db->query('SELECT * FROM features WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
				if ($feature->num_rows() === 0) {
					show_404();
				}
				$data = $this->load->view('feature/inframe.php', $feature->row_array(), true);
				$this->load->config('gfx');
				$expiry = $this->cache->save($feature->row()->name, $data, 'feature-inframe', $this->config->item('gfx_cache_time'));
			} else {
				$expiry = $this->cache->get_expiry($id, 'feature-inframe');
			}
			header('ETag: ' . md5($expiry));
			//TBD: this will introduce GZip error
			print $data;
			break;
			default:
			$this->load->helper('gfx');
			if (checkETag($id, 'feature')) return;
			$data = $this->cache->get($id, 'feature');

			if (!$data) {
				$this->load->database();
				$feature = $this->db->query('SELECT * FROM features WHERE `name` = ' . $this->db->escape($id) . ' LIMIT 1');
				if ($feature->num_rows() === 0) {
					show_404();
				}
				$this->load->config('gfx');
				$data = array(
					'meta' => $this->load->view('feature/meta.php', $feature->row_array(), true),
					'content' => $body = $this->load->view('feature/content.php', $feature->row_array(), true),
					'admin' =>$this->load->view('feature/admin.php', $feature->row_array(), true)
				);
				$data['expiry'] = $this->cache->save($feature->row()->name, $data, 'feature', $this->config->item('gfx_cache_time'));
				$data['db'] = 'content ';
			} else {
				$data['expiry'] = $this->cache->get_expiry($id, 'feature');
			}
			if ($this->session->userdata('admin') !== 'Y') {
				unset($data['admin']);
			}
			$this->load->library('parser');
			$this->parser->page($data, $this->session->userdata('id'));
			break;
		}
	}
	function delete() {
		show_404();
		//print 'TBD';
	}
	function update() {
		$this->load->config('gfx');
		$this->load->helper('gfx');
		if (!checkAuth(true, true, 'json')) return;
		/* Feature name cannot collide function name */
		if (in_array($this->input->post('name'), array('update', 'delete'))) {
			json_message('error_feature_name');
			return;
		}
		/* Check whether name already used */
		$data = $this->db->query('SELECT `name` FROM `features` WHERE `id` != ' . $this->input->post('id')
			. ' AND `name` = ' . $this->db->escape($this->input->post('name')) . ';');
		if ($data->num_rows() !== 0) {
			json_message('dup_feature_name');
			return;
		}
		$data->free_result();
		
		/* Update stickers*/
		$this->_update_feature_images($this->input->post('title'), $this->input->post('name'));
		
		/* Update data */
		$this->db->update(
			'features',
			array(
				'title' => $this->input->post('title'),
				'name' => $this->input->post('name'),
				'order' => $this->input->post('order'),
				'description' => $this->input->post('description'),
				'content' => $this->input->post('content')
			),
			array(
				'id' => $this->input->post('id')
			)
		);
		$this->load->library('cache');
		$this->cache->remove($this->input->post('name'), 'feature-inframe');
		$this->cache->remove($this->input->post('name'), 'feature');

		json_message('feature_updated', 'highlight', 'info');
	}
	function _update_feature_images($title, $name) {
		$this->load->config('gfx');

		/* title gd2 cache */
		$text = imagecreatetruecolor(150, 20);
		imagesavealpha($text, true);
		imagefill($text, 0, 0, imagecolorallocatealpha($text, 255, 255, 255, 127));
		imagettftext($text, 12, 0, 0, 16, imagecolorallocate($text, 0, 0, 0), $this->config->item('gfx_sticker_font'), $title);
		imagegd2($text, './stickerimages/features/' . $name . '.gd2');
		//imagepng($text, './stickerimages/features/' . $name . '-text.png');

		/* singlefeature sticker */
		$bg = imagecreatefromgd2('./images/' . $this->config->item('language') . '/singlefeature.gd2');
		imagealphablending($bg, true);
		imagesavealpha($bg, true);

		$D = imagettfbbox(12, 0, $this->config->item('gfx_sticker_font'), $title);
		imagecopy($bg, $text, (150-($D[2]-$D[0]))/2, 7, 0, 0, 150, 20);
		imagepng($bg, './stickerimages/features/' . $name . '.png');
		imagedestroy($text);
		imagedestroy($bg);
	}
}

/* End of file feature.php */
/* Location: ./system/applications/controller/feature.php */ 
