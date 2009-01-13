<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['openid_storepath'] = '/tmp';
$config['openid_policy'] = 'auth/policy';
$config['openid_required'] = array('nickname');
$config['openid_optional'] = array('fullname', 'email');
$config['openid_request_to'] = 'auth/check';

?>
