<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* Change this config item to empty array if you don't need site wide message */
$config['gfx_site_wide_message'] = array(
	array(
		'type' => 'highlight',
		'icon' => 'comment',
		'message' => '這是個預演(staging)站！請在試玩之後，提供畫面與程式操作互動的意見，謝謝！'
	)
);

/* suffix of css/js files */
$config['gfx_suffix'] = '?20090725';

/* If given OpenID does not exist in the database,
redirect user to a about/closetest page instead of create entry for her/him */
$config['gfx_require_pre_authorization'] = false;
/* This token is used to generate the md5 hash to be send back by POST requests so we can check it's origin.
Change this on a live site will disable all forms; user would have to reload to get the new hash */
$config['gfx_token'] = '--secret-token-good-day-fx';
/* Cache time (in seconds) */
$config['gfx_cache_time'] = 60;
/* Remember to change gfxcode.php on your phpBB installation.
Change this token does not affect forum id and username that already in the database */
$config['gfx_forum_auth_token'] = '--secret-md5-string hash blah kkk';

/* path to font for sticker generations */
$config['gfx_sticker_font'] = '/usr/share/fonts/truetype/ttf-custom/LiHei Pro.ttf';

/* This is the download url that download controller redirects users to.
Usually this goes to Mozilla load balancing bouncer (i.e. download.mozilla.org)
"os" variable will append to the end
Thanks to bug 398366,do remember to change the version every time new version comes out */
$config['gfx_downloadurl'] = 'http://download.mozilla.org/?product=firefox-3.6&lang=zh-TW&os=';
/* Where user will be redirect to if Javascript and User-agent both failed to detect os */
/* TBD: instead redirect to another webpage,show up a dialog and ask for the os from user? */
$config['gfx_downloadfallback'] = 'http://moztw.org/firefox/';

/* API fetch method, which does not support experimential addons
  "0.1" is a fake api version number so that the api will output compatible_os information.
*/
$config['gfx_amo_api_url'] = 'https://services.addons.mozilla.org/zh-TW/firefox/api/0.1/addon/';

/* fetch data by parse addon page;
   AMO url where addon description,title,and xpi address can be fetched. Amo ID will be append. */
$config['gfx_amo_url'] = 'https://addons.mozilla.org/zh-TW/firefox/addon/';
$config['gfx_amo_xpi_url'] = 'https://addons.mozilla.org/zh-TW/firefox/downloads/latest/';

/* RegExp to fetch title (version), description */
$config['gfx_amo_title_regexp'] = '/<h2.*?class="addon">.*?<img src="(.*?)".*?\/>.*?<span>(.*?)([\d\.a-z]+)\s+<\/span>/s';
$config['gfx_amo_desc_regexp'] = '/<div id="addon-summary".*?>.*?<p.*?>(.*?)<\/p>.*?<\/div>/s';

/* Some keyword to find out the support platforms */
$config['gfx_amo_is_exp_regexp'] = '/class=\"primary exp\"/';
$config['gfx_amo_platform_0_regexp'] = '/platform\-ALL/';
$config['gfx_amo_platform_1_regexp'] = '/platform\-BSD/';
$config['gfx_amo_platform_2_regexp'] = '/platform\-Linux/';
$config['gfx_amo_platform_3_regexp'] = '/platform\-MacOSX/';
$config['gfx_amo_platform_4_regexp'] = '/platform\-Solaris/';
$config['gfx_amo_platform_5_regexp'] = '/platform\-Windows/';

/* Only re-fetch add-ons after the data is that old (seconds) */
$config['gfx_amo_fetch_older_than_time'] = 7*24*60*60;
/* Or it's older than a specific date stated here */
$config['gfx_amo_fetch_older_than_date'] = strtotime('2009-03-07 10:00:00');

/* The from: field for all the mails send from the server to the users */
$config['gfx_mail_from_add'] = 'bot-no-reply@gfx.tw';
/* Name */
$config['gfx_mail_from_name'] = 'gfx.tw bot';

/* The user should show on the home page. */
$config['gfx_home_user'] = 'foxmosa';
/* Bad names that user should not use as their gfx url.
Should include all controller and reserve url for future functions */
$config['gfx_badname'] = array(
	'about',
	'addons',
	'auth',
	'badge',
	'badges',
	'blog',
	'comment',
	'comments',
	'doc',
	'docs',
	'download',
	'downloads',
	'editor',
	'event',
	'events',
	'explore',
	'feature',
	'features',
	'highlight',
	'highlights',
	'home',
	'homes',
	'help',
	'helps',
	'list',
	'lists',
	'image',
	'images',
	'js',
	'lobby',
	'random',
	'share',
	'sticker',
	'stickers',
	'system',
	'systems',
	'user',
	'users',
	'useravatar',
	'useravatars',
	'userpage',
	'userpages',
	'view'
);

/* End of file gfx.php */
/* Location: .applications/config/gfx.php */ 
