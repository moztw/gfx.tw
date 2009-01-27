<?php

class Download extends Controller {
	function Download() {
		parent::Controller();
		$this->load->database();
		$this->load->config('gfx');
	}
	function index() {
		parse_str($this->input->server('QUERY_STRING'), $G);
		if (isset($G['name'])) {
			$name = $G['name'];
		} elseif ($this->input->server('HTTP_REFERER')) {
			/*
				Try to determene name from http referrer
				Doesn't matter if it's real or not, SQL will take care of that.
			*/
			$name = substr(parse_url($this->input->server('HTTP_REFERER'), PHP_URL_PATH), 1);
		}
		/* remember, $name is an unsafe string; always escape before putting into SQL query string */
		if (isset($name) && $this->session->userdata('id')) {
			/* Should not count download from oneself */
			$this->db->query('UPDATE `users` SET `count` = `count`+1 '
				. 'WHERE `name` = ' . $this->db->escape($name)
				. ' AND `id` != ' . $this->session->userdata('id') . ';');
		} elseif (isset($name)) {
			/* Yes, it's really easy to cheat with wget */
			$this->db->query('UPDATE `users` SET `count` = `count`+1 '
				. 'WHERE `name` = ' . $this->db->escape($name) . ';');
		}
		$dlurl = $this->config->item('gfx_downloadurl');
		if (isset($G['os']) && in_array($G['os'], array('win', 'osx', 'linux'))) {
			$dlurl .= $G['os'];
		} else {
			/* Try to detect os base on user-agent string */
			if ($this->input->server('HTTP_USER_AGENT')) {
				if (strpos($this->input->server('HTTP_USER_AGENT'), 'Windows NT') !== false) {
					$dlurl .= 'win';
				} elseif (strpos($this->input->server('HTTP_USER_AGENT'), 'OS X') !== false) {
					$dlurl .= 'osx';
				} elseif (strpos($this->input->server('HTTP_USER_AGENT'), 'Linux') !== false) {
					$dlurl .= 'linux';
				}
			} else {
				/* Failed, fall back to all platform page */
				$dlurl = $this->config->item('gfx_downloadfallback');
			}
		}
		header('Location: ' . $dlurl);
	}
}

/* End of file download.php */
/* Location: ./system/applications/controller/download.php */ 