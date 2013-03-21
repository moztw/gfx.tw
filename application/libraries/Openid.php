<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* OpenID Library
*
* @package    CodeIgniter
* @author     bardelot
* @see        http://cakebaker.42dh.com/2007/01/11/cakephp-and-openid/
*             & http://openidenabled.com/php-openid/
*/

class Openid{

  var $storePath = 'tmp';

  var $sreg_enable = false;
  var $sreg_required = null;
  var $sreg_optional = null;
  var $sreg_policy = null;

  var $ax_enable = false;
  var $ax_types = array();

  var $pape_enable = false;
  var $pape_policy_uris = null;

  var $request_to;
  var $trust_root;
  var $ext_args;

    function Openid()
    {
    $CI =& get_instance();
        $CI->config->load('openid');
        $this->storePath = $CI->config->item('openid_storepath');

        session_start();
        $this->_doIncludes();

    log_message('debug', "OpenID Class Initialized");
    }

    function _doIncludes()
    {
    set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());

    require_once "Auth/OpenID/Consumer.php";
    require_once "Auth/OpenID/FileStore.php";
    require_once "Auth/OpenID/SReg.php";
    require_once "Auth/OpenID/AX.php";
    require_once "Auth/OpenID/PAPE.php";
  }

    function set_ax($enable, $types)
    {
    $this->ax_enable = $enable;
    $this->ax_types = $types;
    }

    function set_sreg($enable, $required = null, $optional = null, $policy = null)
    {
    $this->sreg_enable = $enable;
    $this->sreg_required = $required;
    $this->sreg_optional = $optional;
    $this->sreg_policy = $policy;
    }

    function set_pape($enable, $policy_uris = null)
    {
    $this->pape_enable = $enable;
    $this->pape_policy_uris = $policy_uris;
    }

    function set_request_to($uri)
    {
    $this->request_to = $uri;
    }

    function set_trust_root($trust_root)
    {
    $this->trust_root = $trust_root;
    }

    function set_args($args)
    {
    $this->ext_args = $args;
    }

    function _set_message($error, $msg, $val = '', $sub = '%s')
    {
      $CI =& get_instance();
        $CI->lang->load('openid');
		$CI->session->set_flashdata('message', 'error:alert:' .
			htmlspecialchars(str_replace($sub, $val, $CI->lang->line($msg))));
		header('Location: ' . base_url());
        if ($error)
        {
      exit;
    }
    }

    function authenticate($openId, $immediate = false)
    {
    $consumer = $this->_getConsumer();
        $authRequest = $consumer->begin($openId);

        // No auth request means we can't begin OpenID.
    if (!$authRequest)
    {
        $this->_set_message(true,'openid_auth_error');
    }

    if ($this->sreg_enable)
    {
        $sreg_request = Auth_OpenID_SRegRequest::build($this->sreg_required, $this->sreg_optional, $this->sreg_policy);

        if ($sreg_request)
        {
            $authRequest->addExtension($sreg_request);
        }
        else
        {
            $this->_set_message(true,'openid_sreg_failed');
        }
    }

    if ($this->ax_enable) {
        $ax_request = new Auth_OpenID_AX_FetchRequest;
        if ($ax_request) {
            foreach ($this->ax_types as $alias => &$type_url) {
                $ax_request->add(Auth_OpenID_AX_AttrInfo::make($type_url, 1, 1, $alias));
            }
            $authRequest->addExtension($ax_request);
        } else {
            $this->_set_message(true,'openid_ax_failed');
        }
    }

    if ($this->pape_enable)
    {
        $pape_request = new Auth_OpenID_PAPE_Request($this->pape_policy_uris);

        if ($pape_request)
        {
            $authRequest->addExtension($pape_request);
        }
        else
        {
            $this->_set_message(true,'openid_pape_failed');
        }
    }

        if ($this->ext_args != null)
        {
                foreach ($this->ext_args as $extensionArgument)
                {
            if (count($extensionArgument) == 3)
            {
                 $authRequest->addExtensionArg($extensionArgument[0], $extensionArgument[1], $extensionArgument[2]);
            }
                }
    }

        // Redirect the user to the OpenID server for authentication.
    // Store the token for this authentication so we can verify the
    // response.

    // For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
    // form to send a POST request to the server.
    if ($authRequest->shouldSendRedirect())
    {
        $redirect_url = $authRequest->redirectURL($this->trust_root, $this->request_to, $immediate);

        // If the redirect URL can't be built, display an error
        // message.
        if (Auth_OpenID::isFailure($redirect_url))
        {
            $this->_set_message(true,'openid_redirect_failed', $redirect_url->message);
        }
        else
        {
            // Send redirect.
            header("Location: ".$redirect_url);
        }
    }
    else
    {
        // Generate form markup and return it.
        $form_html = $authRequest->formMarkup($this->trust_root, $this->request_to, $immediate, array('id' => 'openid_message'));

        // Display an error if the form markup couldn't be generated;
        // otherwise, render the HTML.
        if (Auth_OpenID::isFailure($form_html))
        {
            $this->_set_message(true,'openid_redirect_failed', $form_html->message);
        }
        else
        {
			return $form_html;
        }
    }

        }

        function getResponse()
        {
      $consumer = $this->_getConsumer();
      $response = $consumer->complete($this->request_to);

      return $response;
        }

        function _getConsumer()
        {
            if (!file_exists($this->storePath) && !mkdir($this->storePath))
            {
          $this->_set_message(true,'openid_storepath_failed', $this->storePath);
            }

            $store = new Auth_OpenID_FileStore($this->storePath);
            $consumer = new Auth_OpenID_Consumer($store);

            return $consumer;
        }
}
?>
