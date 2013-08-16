# Install Note

## Environment

* Apache 2 + mod_rewrite + php5(?) + php-gd + php5-curl
* lighttpd should be ok but you have to set up rewrite rule yourself.
* Writable /tmp (can be changed at `application/config/openid.php`
* MySQL > 4.1 with fulltext search ability.
  * You Must turn on `ft_min_word_len=1` in order to search Chinese characters.
  To do so, drop this file as `/etc/mysql/conf.d/fulltext.cnf`

            [mysqld]
    
            # enable chinese search
            ft_min_word_len =1

## Install

1. Check out code
2. Run `git submodule init && git submodule update` to check out `php-openid` library.
2. Create configuration files
    * `./define.php`
    * `./application/config/database.php`
    * `./application/config/gfx.php`
3. Import database from `gfx.sql`. The file is currently out-dated though.
4. Create the first user in the `users` table, and feature selections in `features`, and gropu selections in `groups`.
5. Make these directories writable:
    * `./useravatars`
    * `./system/cache`
    * `./userstickers`
    * `./stickerimages/features`
6. Login with the initial user. Click and save on each of the features so gfx make badge & badge caches for you.
7. Click save on editor page, so gfx can make the user stickers for you.

### On a production site

1. Make sure `PRODUCTION` is set to `TRUE` in `define.php`
2. Make sure `ENCRYPTION_KEY` is set to something secret and unique  in `define.php`

### Javascript Minification

1. run `./tools/minify.sh .`
2. Change `JS_SUFFIX` in `define.php` from `.js` to `.min.js`.

## Localization

To localize this website,

1. Create new directory for your locale in `./application/languages/`, `./system/languages/`, `./application/views/` and translating all files.
   CodeIginter will split errors if any of the file is missing.
   you might want to copy `./system/languages/english` if you don't want to translate them; they don't really show often.
2. Change `$cfg['language']` `./application/config/config.php` to your language.
3. Some of the paramaters in `./application/config/gfx.php` are locale-related. Do remember to change them.
4. You might want to tweak `./style.css` and some images, especially if the locale is RTL.

Note that localisation only affects appearence, not dynamatic content;
it's not pratical to preapre concentrate all locale to a huge table, nor possible to fetch amo content of every locale.
(This means it's safe to delete all other locale files if you don't need them.)

## Known Issue

1. If your protect your setup with HTTP password (which may be set in `.htaccess`),
   the following will break:
   * OpenID 2.0 xrds authorization (the one that suppress Yahoo! login warning)
2. Login into https OpenIDs will fail if your php-curl unable to get local issuer certificate.
   You can try to verify that by wget the same https url, wget will complain the same thing.
   On a Ubuntu, you should install `ca-certificates` package.
