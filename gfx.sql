-- phpMyAdmin SQL Dump
-- version 2.9.1.1-Debian-2ubuntu1.2
-- http://www.phpmyadmin.net
-- 
-- 主機: localhost
-- 建立日期: Jan 17, 2009, 08:12 PM
-- 伺服器版本: 5.0.38
-- PHP 版本: 5.2.1
-- 
-- 資料庫: `gfx`
-- 

-- --------------------------------------------------------

-- 
-- 資料表格式： `aboutpages`
-- 

CREATE TABLE `aboutpages` (
  `id` int(2) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- 資料表格式： `addons`
-- 

CREATE TABLE `addons` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `amo_id` bigint(20) unsigned NOT NULL,
  `url` varchar(1024) NOT NULL,
  `icon_url` varchar(1024) NOT NULL,
  `description` text NOT NULL,
  `xpi_url` varchar(1024) NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

-- 
-- 資料表格式： `features`
-- 

CREATE TABLE `features` (
  `id` int(2) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `name` varchar(200) NOT NULL,
  `order` int(2) unsigned NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `order` (`order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- 列出以下資料庫的數據： `features`
-- 

INSERT INTO `features` (`id`, `title`, `name`, `order`, `description`, `content`, `modified`) VALUES 
(1, '安全放心', 'security', 1, 'Firefox 留心您的安全與隱私，不讓惡意的間諜程式入侵您的電腦...', '(longer content, in HTML...)', '0000-00-00 00:00:00'),
(2, '隨意自訂', 'personalization', 2, '', '', '0000-00-00 00:00:00'),
(3, '掌握資訊', 'info', 3, '', '', '0000-00-00 00:00:00'),
(4, '文字縮放', 'textzoom', 4, 'blah!', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

-- 
-- 資料表格式： `groups`
-- 

CREATE TABLE `groups` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `title` text NOT NULL,
  `order` int(3) unsigned NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`,`order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- 列出以下資料庫的數據： `groups`
-- 

INSERT INTO `groups` (`id`, `name`, `title`, `order`, `description`) VALUES 
(1, 'google', '咕狗狐', 1, 'Google 是我最好的朋友，我靠他管理我的數位生活！'),
(2, 'knowledge', '知識狐', 2, '網路是我取得新知識的最佳管道！'),
(3, 'yahoo', '「雅」狐', 2, 'Yahoo! 奇摩是我生活的好夥伴。'),
(4, 'acg', '動漫狐', 4, '...'),
(5, 'bbs', '鄉民狐', 5, '一天不上 BBS 就渾身不對勁。');

-- --------------------------------------------------------

-- 
-- 資料表格式： `u2a`
-- 

CREATE TABLE `u2a` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `addon_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` int(3) unsigned NOT NULL,
  `order` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- 資料表格式： `u2f`
-- 

CREATE TABLE `u2f` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` bigint(20) unsigned NOT NULL,
  `feature_id` int(2) unsigned NOT NULL,
  `order` int(2) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- 
-- 列出以下資料庫的數據： `u2f`
-- 

INSERT INTO `u2f` (`id`, `user_id`, `feature_id`, `order`) VALUES 
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 3);

-- --------------------------------------------------------

-- 
-- 資料表格式： `u2g`
-- 

CREATE TABLE `u2g` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` int(3) unsigned NOT NULL,
  `order` int(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- 
-- 列出以下資料庫的數據： `u2g`
-- 

INSERT INTO `u2g` (`id`, `user_id`, `group_id`, `order`) VALUES 
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 3);

-- --------------------------------------------------------

-- 
-- 資料表格式： `users`
-- 

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `login` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `title` text NOT NULL,
  `avatar` varchar(37) character set ascii NOT NULL,
  `email` varchar(200) NOT NULL,
  `bio` text NOT NULL,
  `web` varchar(1024) NOT NULL,
  `blog` varchar(1024) NOT NULL,
  `blog_rss` varchar(1024) NOT NULL,
  `forum_username` varchar(48) NOT NULL,
  `count` bigint(20) unsigned NOT NULL default '1',
  `visited` bigint(20) unsigned NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- 列出以下資料庫的數據： `users`
-- 

INSERT INTO `users` (`id`, `login`, `name`, `title`, `avatar`, `email`, `bio`, `web`, `blog`, `blog_rss`, `forum_username`, `count`, `visited`, `modified`) VALUES 
(1, '', 'foxmosa', '狐耳摩莎', '52501aaf2bcbb9c35696b076bd3b11b8.gif', '', '我是伴隨台灣的火狐愛好者遊山玩水、遨遊網際的狐耳摩莎！', 'http://www.moztw.org/events/foxmosa-tour/', '', '', '', 1, 1, '0000-00-00 00:00:00');
