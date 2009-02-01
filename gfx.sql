-- phpMyAdmin SQL Dump
-- version 2.9.1.1-Debian-2ubuntu1.2
-- http://www.phpmyadmin.net
-- 
-- 主機: localhost
-- 建立日期: Feb 02, 2009, 12:51 AM
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 資料表格式： `addons`
-- 

CREATE TABLE `addons` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `amo_id` bigint(20) unsigned NOT NULL,
  `amo_version` varchar(255) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `icon_url` varchar(1024) NOT NULL,
  `description` text NOT NULL,
  `xpi_url` varchar(1024) NOT NULL,
  `fetched` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`,`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 資料表格式： `users`
-- 

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `login` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `title` text NOT NULL,
  `avatar` varchar(45) character set ascii NOT NULL,
  `email` varchar(200) NOT NULL,
  `bio` text NOT NULL,
  `web` varchar(1024) NOT NULL,
  `blog` varchar(1024) NOT NULL,
  `blog_rss` varchar(1024) NOT NULL,
  `forum_id` bigint(20) NOT NULL,
  `forum_username` varchar(48) NOT NULL,
  `count` bigint(20) unsigned NOT NULL default '1',
  `visited` bigint(20) unsigned NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
