<?php

class Addon extends Controller {
	function Addon() {
		parent::Controller();
		$this->load->scaffolding('addons');
		$this->load->config('gfx');
		$this->load->helper('gfx');
	}
	function index() {
		show_404();
	}
	function query() {
		checkAuth(true, false, 'json');
		$this->load->database();
		//The user specific want to find the addon by its amo_id
		if (is_numeric($this->input->post('q')) && $this->input->post('q') !== '0') {
			$addons = $this->db->query('SELECT id, title, amo_id, amo_version, url, icon_url, description, fetched '
			. 'FROM `addons` WHERE `amo_id` = ' . $this->db->escape($this->input->post('q')) . ';');
			if ($addons->num_rows() === 0) {
				//Couldn't find it, try to fetch from AMO site
				$addon = $this->_update_amo_addon($this->input->post('q'), false, true);
				if ($addon) {
					$this->load->view('json.php', array('jsonObj' => 
						array('addons' => array($addon)))
					);
					exit;
				}
			}
		} else {
			/* since query is only used in editor, we only provide limit information */
			header('X-Line-No: 26');
			$addons = $this->db->query('SELECT id, title, amo_id, amo_version, url, icon_url, description, fetched '
			. 'FROM `addons` WHERE MATCH (`title`,`description`) AGAINST (' . $this->db->escape($this->input->post('q')) . ') ORDER BY `title` ASC;');
			/* fulltest search doesn't give good result sometimes, so we trun to LIKE if none is found */
			header('X-Trac: ' . strlen(trim($this->input->post('q'))));
			if ($addons->num_rows() === 0 && strlen(trim($this->input->post('q'))) >= 3) {
				header('X-Line-No: 30');
				$addons = $this->db->query('SELECT id, title, amo_id, amo_version, url, icon_url, description, fetched '
				. 'FROM `addons` WHERE `title` LIKE \'%' . $this->db->escape_str(trim($this->input->post('q'))) . '%\' ORDER BY `title` ASC;');
			}
		}
		$A = array();
		foreach ($addons->result_array() as $addon) {
			if ($addon['amo_id']) $addon['url'] = $this->config->item('gfx_amo_url') . $addon['amo_id'];
			$A[] = $addon;
		}
		//Pick one of the addons found and send to to re-fetch if it's too old
		//addon picked by amo_id query will always be checked for age of the fetched data
		$r = array_rand($A);
		if (
			count($A) !== 0 &&
			isset($A[$r]['amo_id']) &&
			$A[$r]['amo_id'] !== 0 &&
			strtotime($A[$r]['fetched']) < max(time()-$this->config->item('gfx_amo_fetch_older_than_time'), $this->config->item('gfx_amo_fetch_older_than_date'))
			) {
			$addon = $this->_update_amo_addon($A[$r]['amo_id'], $A[$r]['id'], true);
			if ($addon) $A[$r] = $addon;
		}
		$this->load->view('json.php', array('jsonObj' => array('addons' => $A)));
	}
	function suggest() {
		checkAuth(true, false, 'json');
		if (!is_numeric($this->input->post('g'))) {
			json_message('group_not_number');
			return;
		}
		$this->load->library('cache');
		$A = $this->cache->get($this->input->post('g') ,'addons-suggest');
		if ($A) {
			$this->load->view('json.php', array('jsonObj' => array('addons' => $A)));
			exit();
		}

		$this->load->database();
		/* since suggest is only used in editor, we only provide limit information */
		$addons = $this->db->query('SELECT t1.id, t1.title, t1.amo_id, t1.amo_version, t1.url, t1.icon_url, t1.description, fetched '
			. 'FROM addons t1, u2a t2 '
			. 'WHERE t2.group_id =  ' . $this->db->escape($this->input->post('g')) . ' AND t1.id = t2.addon_id '
			. 'GROUP BY t2.addon_id ORDER BY COUNT(t2.id) DESC, t1.title ASC LIMIT 15;');
		$A = array();
		foreach ($addons->result_array() as $addon) {
			if ($addon['amo_id']) $addon['url'] = $this->config->item('gfx_amo_url') . $addon['amo_id'];
			$A[] = $addon;
		}
		//Pick one of the addons found and send to to re-fetch if it's too old
		$r = array_rand($A);
		if (
			count($A) !== 0 &&
			isset($A[$r]['amo_id']) &&
			$A[$r]['amo_id'] !== 0 &&
			strtotime($A[$r]['fetched']) < max(time()-$this->config->item('gfx_amo_fetch_older_than_time'), $this->config->item('gfx_amo_fetch_older_than_date'))
			) {
			$addon = $this->_update_amo_addon($A[$r]['amo_id'], $A[$r]['id'], true);
			if ($addon) $A[$r] = $addon;
		}
		$this->cache->save($this->input->post('g') ,$A ,'addons-suggest', 300);
		$this->load->view('json.php', array('jsonObj' => array('addons' => $A)));
	}
	function forcefetch() {
		checkAuth(true, true, 'json');
		$addon = $this->db->query('SELECT id FROM `addons` WHERE `amo_id` = ' . $this->db->escape($this->input->post('amo_id')) . ';');
		if ($addon->num_rows() === 0) {
			//Couldn't find it, try to fetch from AMO site
			$A = $this->_update_amo_addon($this->input->post('amo_id'), false, false);
		} else {
			$A = $this->_update_amo_addon($this->input->post('amo_id'), $addon->row()->id, false);
		}
		if ($A) {
			$this->load->view('json.php', array('jsonObj' => array('addons' => $A)));
		} else {
			$this->load->view('json.php', array('jsonObj' => array('addons' => array())));
		}
	}
	function _update_amo_addon($amo_id, $id = false, $cleanoutput = true) {
		if ($amo_id == 0) return false;
		/* fetch data from AMO */
		$xml = @file_get_contents($this->config->item('gfx_amo_api_url') . $amo_id);
		if ($xml && strpos($xml, '<error>') !== false) {
			return false;
		}
		$doc = new DOMDocument();
		$doc->loadXML($xml);
		$dom->preserveWhiteSpace = false;
		$A = array(
			'title' => $doc->getElementsByTagName('name')->item(0)->firstChild->nodeValue,
			'amo_id' => $amo_id,
			'url' => '',
			'xpi_url' => $doc->getElementsByTagName('install')->item(0)->firstChild->nodeValue,
			'xpi_hash' => $doc->getElementsByTagName('install')->item(0)->getAttribute('hash'),
			'amo_version' => $doc->getElementsByTagName('version')->item(0)->firstChild->nodeValue,
			'icon_url' => '',
			'description' => strip_tags($doc->getElementsByTagName('summary')->item(0)->firstChild->nodeValue),
			'available' => 'Y',
			'os_0' => 'N',
			'os_1' => 'N',
			'os_2' => 'N',
			'os_3' => 'N',
			'os_4' => 'N',
			'os_5' => 'N',
			'fetched' => date('Y-m-d H:m:s')
		);
		if ($doc->getElementsByTagName('status')->item(0)->getAttribute('id') !== '4') {
			$A['available'] = 'N';	
		}
		if (strpos($doc->getElementsByTagName('icon')->item(0)->firstChild->nodeValue, 'default_icon') === false) {
			$A['icon_url'] = $doc->getElementsByTagName('icon')->item(0)->firstChild->nodeValue;
		}
		foreach ($doc->getElementsByTagName('all_compatible_os')->item(0)->childNodes as $os) {
			if ($os->nodeName !== 'os') continue;
			switch ($os->firstChild->nodeValue) {
				case 'ALL':
					$A['os_0'] = 'Y';
					break;
				case 'BSD_OS':
					$A['os_1'] = 'Y';
					break;
				case 'Linux':
					$A['os_2'] = 'Y';
					break;
				case 'Darwin':
					$A['os_3'] = 'Y';
					break;
				case 'SunOS':
					$A['os_4'] = 'Y';
					break;
				case 'WINNT':
					$A['os_5'] = 'Y';
					break;
			}
		}

		/* update/insert the record */
		$this->load->database();
		if ($id) {
			$this->db->update('addons', $A, array('id' => $id));
			$A = array_merge(
				array('id' => $id),
				$A
			);
		} else {
			$this->db->insert('addons', $A);
			$A = array_merge(
				array('id' => $this->db->insert_id()),
				$A
			);
		}
		if ($cleanoutput) {
			/* clean up if asked for clean output */
			$A['url'] = $this->config->item('gfx_amo_url') . $A['amo_id'];
			unset($A['xpi_url']);
			unset($A['available']);
			unset($A['os_0']);
			unset($A['os_1']);
			unset($A['os_2']);
			unset($A['os_3']);
			unset($A['os_4']);
			unset($A['os_5']);
		}
		return $A;
	}
}

/* End of file addon.php */
/* Location: ./system/applications/controller/addon.php */
