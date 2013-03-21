<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "user";

if (PRODUCTION) {
	$route['scaffolding_trigger'] = ""; # Change this to empty string on Production site
} else {
	$route['scaffolding_trigger'] = "scaffolding"; # Change this to empty string on Production site
}

/* We must explicitly define all routes here because the last route will overwrite them all */

if ($route['scaffolding_trigger']) {
	$route['about/' . $route['scaffolding_trigger']] = "about/" . $route['scaffolding_trigger'];
	$route['about/' . $route['scaffolding_trigger'] . '/(:any)'] = "about/" . $route['scaffolding_trigger'] . '/$1';
	$route['addon/' . $route['scaffolding_trigger']] = "addon/" . $route['scaffolding_trigger'];
	$route['addon/' . $route['scaffolding_trigger'] . '/(:any)'] = "addon/" . $route['scaffolding_trigger'] . '/$1';
	$route['editor/' . $route['scaffolding_trigger']] = "editor/" . $route['scaffolding_trigger'];
	$route['editor/' . $route['scaffolding_trigger'] . '/(:any)'] = "editor/" . $route['scaffolding_trigger'] . '/$1';
	$route['feature/' . $route['scaffolding_trigger']] = "feature/" . $route['scaffolding_trigger'];
	$route['feature/' . $route['scaffolding_trigger'] . '/(:any)'] = "feature/" . $route['scaffolding_trigger'] . '/$1';
	$route['user/' . $route['scaffolding_trigger']] = "user/" . $route['scaffolding_trigger'];
	$route['user/' . $route['scaffolding_trigger'] . '/(:any)'] = "user/" . $route['scaffolding_trigger'] . '/$1';
}
$route['about'] = "about";
$route['about/update'] = "about/update";
$route['about/delete'] = "about/delete";
$route['about/(:any)'] = "about/view/$1";
$route['addon'] = "addon";
$route['addon/(:any)'] = "addon/$1";
$route['api'] = "api/index";
$route['api/(:any)'] = "api/index/$1";
$route['download'] = "download";
$route['download/(:any)'] = "download/$1";
$route['editor'] = "editor";
$route['editor/(:any)'] = "editor/$1";
$route['feature'] = "feature";
$route['feature/update'] = "feature/update";
$route['feature/delete'] = "feature/delete";
$route['feature/(:any)'] = "feature/view/$1";
$route['feature/(:any)/inframe'] = "feature/view/$1/inframe";
$route['sticker'] = "sticker";
$route['auth/(:any)'] = "auth/$1";
$route['user/list'] = "user/userlist";
$route['user/list/(:any)'] = "user/userlist/$1";
$route['user/(:any)'] = "user/$1";
$route['user/(:any)/(:any)'] = "user/$1/$2";

$route['(:any)'] = "user/view/$1";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */
