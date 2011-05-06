
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;




CREATE TABLE IF NOT EXISTS `authassignment` (
  `itemname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `userid` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `bizrule` text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `bizrule` text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` tinyint(4) NOT NULL DEFAULT '0',
  `subject_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `comment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `user_id` (`user_id`),
  KEY `subject_id` (`subject_id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;



CREATE TABLE IF NOT EXISTS `content_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'path where the image is located relative to the site root',
  `extension` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'extension of the file name',
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `url` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'if its external its url',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;



CREATE TABLE IF NOT EXISTS `content_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;



CREATE TABLE IF NOT EXISTS `content_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;



CREATE TABLE IF NOT EXISTS `content_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `embed_code` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=251 ;



CREATE TABLE IF NOT EXISTS `ipaddress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_start` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ip_end` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `ip_start` (`ip_start`),
  KEY `ip_end` (`ip_end`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `live_comment` (
  `comment_number` int(11) NOT NULL AUTO_INCREMENT,
  `comment_sequence` int(11) NOT NULL,
  `comment_text` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `comment_time` int(11) NOT NULL DEFAULT '0',
  `comment_country` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'WW',
  PRIMARY KEY (`comment_number`),
  KEY `time` (`comment_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;



CREATE TABLE IF NOT EXISTS `live_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id_1` int(11) NOT NULL DEFAULT '0',
  `subject_id_2` int(11) NOT NULL DEFAULT '0',
  `last_comment_number` int(11) NOT NULL DEFAULT '0',
  `comment_sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;



CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL DEFAULT '0',
  `language` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `translation` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `fixed` tinyint(4) NOT NULL DEFAULT '0',
  `message` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;



CREATE TABLE IF NOT EXISTS `priority` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;



CREATE TABLE IF NOT EXISTS `sourcemessage` (
  `id` int(11) NOT NULL,
  `category` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_comment` text COLLATE utf8_unicode_ci,
  `user_country_id` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `urn` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `content_type_id` int(11) NOT NULL COMMENT '1/image 2/text 3/video',
  `content_id` int(11) NOT NULL COMMENT 'Id of the record on the table associated with the content type',
  `country_id` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `language` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `moderator_id` int(11) NOT NULL DEFAULT '0',
  `moderator_ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `moderator_comment` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `moderator_country_id` tinyint(4) NOT NULL DEFAULT '0',
  `authorizer_id` int(11) NOT NULL DEFAULT '0',
  `authorizer_ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorizer_country_id` tinyint(4) NOT NULL DEFAULT '0',
  `time_submitted` int(11) NOT NULL DEFAULT '0',
  `time_moderated` int(11) NOT NULL DEFAULT '0',
  `time_authorized` int(11) NOT NULL DEFAULT '0',
  `priority_id` tinyint(4) NOT NULL DEFAULT '1',
  `evaluating` tinyint(4) NOT NULL DEFAULT '0',
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `authorized` tinyint(4) NOT NULL DEFAULT '0',
  `show_time` int(11) NOT NULL DEFAULT '0' COMMENT 'time when the running thread shows the content',
  PRIMARY KEY (`id`),
  UNIQUE KEY `urn` (`urn`),
  KEY `user_id` (`user_id`),
  KEY `user_ip` (`user_ip`),
  KEY `title` (`title`),
  KEY `content_type_id` (`content_type_id`),
  KEY `content_id` (`content_id`),
  KEY `moderator_id` (`moderator_id`),
  KEY `time_submitted` (`time_submitted`),
  KEY `show_time` (`show_time`),
  KEY `time_moderated` (`time_moderated`),
  KEY `country_id` (`country_id`),
  KEY `priority_id` (`priority_id`),
  KEY `subject_status` (`approved`),
  KEY `user_country_id` (`user_country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;



CREATE TABLE IF NOT EXISTS `translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `string_id` int(11) NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `vote` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `ip_submitted` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_submitted` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `string_id` (`string_id`),
  KEY `vote` (`vote`),
  KEY `language_id_vote_status` (`language_id`,`vote`,`status`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ip_created` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ip_last_access` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_status_id` tinyint(4) NOT NULL DEFAULT '1',
  `user_type_id` tinyint(4) NOT NULL DEFAULT '1',
  `time_created` int(11) NOT NULL,
  `time_last_access` int(11) NOT NULL,
  `time_modified` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `password` (`password`),
  KEY `username_password` (`username`,`password`),
  KEY `user_state_id` (`user_status_id`),
  KEY `user_type_id` (`user_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;



CREATE TABLE IF NOT EXISTS `user_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;



CREATE TABLE IF NOT EXISTS `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of users' AUTO_INCREMENT=6 ;