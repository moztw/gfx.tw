<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* Change this config item to empty array if you don't need site wide message */
$config['gfx_site_wide_message'] = array(
	array(
		'type' => 'highlight',
		'icon' => 'comment',
		'message' => '這是個預演(staging)站！請在試玩之後，提供畫面與程式操作互動的意見，謝謝！'
	)
);
/* This token is used to generate the md5 hash to be send back by POST requests so we can check it's origin.
Change this on a live site will disable all forms; user would have to reload to get the new hash */
$config['gfx_token'] = '--secret-token-good-day-fx';
/* Cache time (in seconds) */
$config['gfx_cache_time'] = 60*5;
/* Remember to change gfxcode.php on your phpBB installation.
Change this token does not affect forum id and username that already in the database */
$config['gfx_forum_auth_token'] = '--secret-md5-string hash blah kkk';

/* path to font for sticker generations */
$config['gfx_sticker_font'] = '/usr/share/fonts/truetype/ttf-custom/LiHei Pro.ttf';

/* This is the download url that download controller redirects users to.
Usually this goes to Mozilla load balancing bouncer (i.e. download.mozilla.org)
"os" variable will append to the end
Thanks to bug 398366, do remember to change the version every time new version comes out */
$config['gfx_downloadurl'] = 'http://download.mozilla.org/?product=firefox-3.0.7&lang=zh-TW&os=';
/* Where user will be redirect to if Javascript and User-agent both failed to detect os */
/* TBD: instead redirect to another webpage, show up a dialog and ask for the os from user? */
$config['gfx_downloadfallback'] = 'http://www.moztw.org/firefox/';

/* AMO url where addon description, title, and xpi address can be fetched. Amo ID will be append. */
$config['gfx_amo_url'] = 'https://addons.mozilla.org/zh-TW/firefox/addon/';
/* RegExp to fetch title (version), description, and xpi */
$config['gfx_amo_title_regexp'] = '/<h3 class=\"name\"[^>]*><img src=\"([\w\.\/\-]+)\" class=\"addon-icon\" alt=\"\" \/>([^<]+) ([\d\.a-z]+)<\/h3>/';
$config['gfx_amo_desc_regexp'] = '/<p class=\"desc\"[^>]*>([^<]+)(<\/p>|<br \/>)/';
$config['gfx_amo_xpi_regexp'] = '/<a href=\"([^\"]+)\"  id=\"installTrigger/';
/* Only re-fetch add-ons after the data is that old (seconds) */
$config['gfx_amo_fetch_older_than_time'] = 7*24*60*60;
/* Or it's older than a specific date stated here */
$config['gfx_amo_fetch_older_than_date'] = strtotime('2009-03-07 10:00:00');

/* The user should show on the home page. */
$config['gfx_home_user'] = 'foxmosa';
/* Bad names that user should not use as their gfx url.
Should include all controller and reserve url for future functions */
$config['gfx_badname'] = array(
	'editor', 
	'userpage', 
	'feature', 
	'auth', 
	'addons',
	'about', 
	'lobby', 
	'view', 
	'sticker', 
	'stickers',
	'user',
	'users', 
	'blog', 
	'events', 
	'event', 
	'doc', 
	'docs', 
	'download',
	'downloads',
	'share', 
	'badge', 
	'home',
	'js',
	'useravatars',
	'system',
	'images'
);

/* End of file gfx.php */
/* Location: .applications/config/gfx.php */ 