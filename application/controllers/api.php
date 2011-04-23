<?php

class Api extends Controller {
	function index($version = '0', $operation = null, $var = null) {
		switch ($operation) {
			case 'user':
			$this->load->library('cache');
			$this->load->helper('gfx');
			$jsonObj = $this->cache->get(strtolower($var), 'user-api');
			if (!$jsonObj) {
				$jsonObj = array();
				$this->load->config('gfx');
				if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $var)
					|| strlen($var) < 3
					|| strlen($var) > 200
					|| substr($var, 0, 8) === '__temp__'
					|| in_array($var, $this->config->item('gfx_badname'))
				) {
					$jsonObj['error'] = 'No such user.';
					break;
				}
				$this->load->database();
				$user = $this->db->query('SELECT login, id, name, email, title, avatar, bio, web, blog, recommendation, forum_id, forum_username, feature_0, feature_1, feature_2, count '
				. 'FROM users WHERE `ready` = \'Y\' AND `name` = ' . $this->db->escape($var) . ' LIMIT 1');
				if ($user->num_rows() === 0) {
					$jsonObj['error'] = 'No such user.';
					break;
				}
				$jsonObj['user'] = $user->row_array();
				$user->free_result();
				$jsonObj['user']['avatar'] = avatarURL($jsonObj['user']['avatar'], $jsonObj['user']['email'], $jsonObj['user']['login'], '&');
				$jsonObj['user']['features'] = array();
				for ($i = 0; $i < 3; $i++) {
					$feature = $this->db->query('SELECT name, title, description FROM features ' 
					. 'WHERE `id` = ' . $jsonObj['user']['feature_' . $i] . ';');
					$jsonObj['user']['features'][] = $feature->row_array();
					$feature->free_result();
				}
				unset($feature);
				$addons = $this->db->query('SELECT t1.amo_id, t1.amo_version, t1.description, t1.icon_url, t1.title, '
				. 't2.group_id FROM addons t1, u2a t2 '
				. 'WHERE t2.addon_id = t1.id AND t2.user_id = ' . $jsonObj['user']['id'] . ' ORDER BY t2.order ASC;');
				$A = array();
				foreach ($addons->result_array() as $addon) {
					if (!isset($A[$addon['group_id']])) $A[$addon['group_id']] = array();
					$A[$addon['group_id']][] = $addon;
				}
				$addons->free_result();
				unset($addons, $addon);
				$groups = $this->db->query('SELECT t1.id, t1.name, t1.title, t1.description FROM groups t1, u2g t2 ' 
				. 'WHERE t2.group_id = t1.id AND t2.user_id = ' . $jsonObj['user']['id'] . ' ORDER BY t2.order ASC;');
				$jsonObj['user']['groups']  = array();
				foreach ($groups->result_array() as $group) {
					$group['addons'] = $A[$group['id']];
					unset($group['id']);
					$jsonObj['user']['groups'][] = $group;
				}
				$groups->free_result();
				unset($groups, $group, $A);
				unset($jsonObj['user']['id'], $jsonObj['user']['login'], $jsonObj['user']['email'], $jsonObj['user']['feature_0'], $jsonObj['user']['feature_1'], $jsonObj['user']['feature_2']);
				$this->load->_ci_cached_vars = array(); //Clean up cached vars
				$this->load->config('gfx');
				$jsonObj['cache_expiry'] = $this->cache->save(strtolower($var), $jsonObj, 'user-api', $this->config->item('gfx_cache_time'));
			} else {
				$jsonObj['expiry'] = $this->cache->get_expiry($var, 'user-api');
			}
			break;
			default:
			$jsonObj['error'] = 'Operation undefined.';
			break;
		}
		parse_str($this->input->server('QUERY_STRING'), $G);
		if (isset($G['callback'])) {
			$this->load->view('json.php', array('jsonObj' => $jsonObj, 'jsonpCallback' => $G['callback']));
		} else {
			$this->load->view('json.php', array('jsonObj' => $jsonObj));
		}
	}
}

/* End of file user.php */
/* Location: ./system/applications/controller/user.php */ 
