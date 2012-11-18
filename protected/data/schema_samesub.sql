-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 17, 2012 at 08:37 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'samesub'
--

-- --------------------------------------------------------

--
-- Table structure for table 'authassignment'
--

CREATE TABLE authassignment (
  itemname varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  userid varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  bizrule text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (itemname,userid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'authitem'
--

CREATE TABLE authitem (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  description text COLLATE utf8_unicode_ci,
  bizrule text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'authitemchild'
--

CREATE TABLE authitemchild (
  parent varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  child varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (parent,child),
  KEY child (child)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'auth_codes'
--

CREATE TABLE auth_codes (
  `code` varchar(40) NOT NULL,
  client_id varchar(20) NOT NULL,
  redirect_uri varchar(200) NOT NULL,
  expires int(11) NOT NULL,
  scope varchar(250) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'captcha'
--

CREATE TABLE captcha (
  id int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'image',
  question varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  answer varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'comment'
--

CREATE TABLE `comment` (
  id int(10) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL DEFAULT '1',
  user_ip varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  country_id tinyint(4) NOT NULL DEFAULT '1',
  subject_id int(11) NOT NULL,
  `time` int(11) NOT NULL,
  comment_number int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  likes int(11) NOT NULL DEFAULT '0',
  dislikes int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY `time` (`time`),
  KEY user_id (user_id),
  KEY subject_id (subject_id),
  KEY country_id (country_id),
  KEY comment_number (comment_number)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'comment_vote'
--

CREATE TABLE comment_vote (
  id int(11) NOT NULL AUTO_INCREMENT,
  comment_id int(11) NOT NULL,
  user_id int(11) NOT NULL DEFAULT '0',
  vote int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY comment_user_vote (comment_id,user_id,vote)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'content_image'
--

CREATE TABLE content_image (
  id int(11) NOT NULL AUTO_INCREMENT,
  path varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'path where the image is located relative to the site root',
  extension varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'extension of the file name',
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  size int(11) DEFAULT NULL,
  url varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'if its external its url',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'content_text'
--

CREATE TABLE content_text (
  id int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'content_type'
--

CREATE TABLE content_type (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  fullname varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'content_video'
--

CREATE TABLE content_video (
  id int(11) NOT NULL AUTO_INCREMENT,
  embed_code text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'country'
--

CREATE TABLE country (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'ipaddress'
--

CREATE TABLE ipaddress (
  id int(11) NOT NULL AUTO_INCREMENT,
  ip_start varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  ip_end varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  country_id int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY country_id (country_id),
  KEY ip_start (ip_start),
  KEY ip_end (ip_end)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'language'
--

CREATE TABLE `language` (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  enabled tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`),
  KEY enabled (enabled)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'live_comment'
--

CREATE TABLE live_comment (
  comment_number int(11) NOT NULL,
  comment_id int(11) NOT NULL DEFAULT '0',
  subject_id int(11) NOT NULL DEFAULT '0',
  comment_text text COLLATE utf8_unicode_ci NOT NULL,
  comment_time int(11) NOT NULL DEFAULT '0',
  comment_country varchar(200) COLLATE utf8_unicode_ci DEFAULT 'WW',
  username varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  likes int(11) NOT NULL DEFAULT '0',
  dislikes int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (comment_number),
  KEY `time` (comment_time)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'live_subject'
--

CREATE TABLE live_subject (
  id int(11) NOT NULL AUTO_INCREMENT,
  subject_id int(11) NOT NULL DEFAULT '0',
  subject_id_2 int(11) NOT NULL DEFAULT '0',
  comment_id int(11) NOT NULL DEFAULT '0',
  comment_number int(11) NOT NULL DEFAULT '0',
  scheduled_time int(11) NOT NULL DEFAULT '0',
  subject_data text COLLATE utf8_unicode_ci,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'log'
--

CREATE TABLE log (
  id int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  session_id varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  user_id int(11) NOT NULL DEFAULT '0',
  controller varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  uri varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  model varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  model_id int(11) NOT NULL DEFAULT '0',
  theme char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 're',
  PRIMARY KEY (id),
  KEY `time` (`time`),
  KEY session_id (session_id),
  KEY user_id (user_id),
  KEY controller (controller),
  KEY `action` (`action`),
  KEY model (model),
  KEY model_id (model_id),
  KEY theme (theme)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'log_detail'
--

CREATE TABLE log_detail (
  id int(11) NOT NULL AUTO_INCREMENT,
  log_id int(11) NOT NULL DEFAULT '0',
  `session` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  client_ip varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'can be multiple ips: HTTP_X_FORWARDED_FOR',
  client_host varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  request_ip varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  request_host varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  client_country_id int(11) NOT NULL DEFAULT '1',
  request_country_id int(11) NOT NULL DEFAULT '1',
  agent varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  referer varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `charset` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  device char(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'desktop, mobile, etc',
  cronned tinyint(4) NOT NULL DEFAULT '0' COMMENT 'entry has been processed by cron job ie country colum',
  PRIMARY KEY (id),
  KEY client_ip (client_ip),
  KEY request_ip (request_ip),
  KEY client_country_id (client_country_id),
  KEY request_country_id (request_country_id),
  KEY agent (agent),
  KEY referer (referer),
  KEY `charset` (`charset`),
  KEY `language` (`language`),
  KEY device (device),
  KEY log_id (log_id),
  KEY `session` (`session`),
  KEY cronned (cronned)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'message'
--

CREATE TABLE message (
  id int(11) NOT NULL DEFAULT '0',
  `language` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  translation text COLLATE utf8_unicode_ci,
  PRIMARY KEY (id,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'notification'
--

CREATE TABLE notification (
  id int(11) NOT NULL AUTO_INCREMENT,
  notification_type_id smallint(6) NOT NULL DEFAULT '1',
  enabled tinyint(4) NOT NULL DEFAULT '1',
  `fixed` tinyint(4) NOT NULL DEFAULT '0',
  message varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'notification_type'
--

CREATE TABLE notification_type (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  disabled int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'oauth_consumer_registry'
--

CREATE TABLE oauth_consumer_registry (
  ocr_id int(11) NOT NULL AUTO_INCREMENT,
  ocr_usa_id_ref int(11) DEFAULT NULL,
  ocr_consumer_key varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  ocr_consumer_secret varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  ocr_signature_methods varchar(255) NOT NULL DEFAULT 'HMAC-SHA1,PLAINTEXT',
  ocr_server_uri varchar(255) NOT NULL,
  ocr_server_uri_host varchar(128) NOT NULL,
  ocr_server_uri_path varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  ocr_request_token_uri varchar(255) NOT NULL,
  ocr_authorize_uri varchar(255) NOT NULL,
  ocr_access_token_uri varchar(255) NOT NULL,
  ocr_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ocr_id),
  KEY ocr_server_uri (ocr_server_uri),
  KEY ocr_server_uri_host (ocr_server_uri_host,ocr_server_uri_path),
  KEY ocr_usa_id_ref (ocr_usa_id_ref)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'oauth_consumer_token'
--

CREATE TABLE oauth_consumer_token (
  oct_id int(11) NOT NULL AUTO_INCREMENT,
  oct_ocr_id_ref int(11) NOT NULL,
  oct_usa_id_ref int(11) NOT NULL,
  oct_name varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  oct_token varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  oct_token_secret varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  oct_token_type enum('request','authorized','access') DEFAULT NULL,
  oct_token_ttl datetime NOT NULL DEFAULT '9999-12-31 00:00:00',
  oct_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (oct_id),
  UNIQUE KEY oct_ocr_id_ref (oct_ocr_id_ref,oct_token),
  KEY oct_token_ttl (oct_token_ttl)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'oauth_server_nonce'
--

CREATE TABLE oauth_server_nonce (
  osn_id int(11) NOT NULL AUTO_INCREMENT,
  osn_consumer_key varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  osn_token varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  osn_timestamp bigint(20) NOT NULL,
  osn_nonce varchar(80) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (osn_id),
  UNIQUE KEY osn_consumer_key (osn_consumer_key,osn_token,osn_timestamp,osn_nonce)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'oauth_server_registry'
--

CREATE TABLE oauth_server_registry (
  osr_id int(11) NOT NULL AUTO_INCREMENT,
  osr_usa_id_ref int(11) DEFAULT NULL,
  osr_consumer_key varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  osr_consumer_secret varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  osr_enabled tinyint(1) NOT NULL DEFAULT '1',
  osr_status varchar(16) NOT NULL,
  osr_requester_name varchar(64) NOT NULL,
  osr_requester_email varchar(64) NOT NULL,
  osr_callback_uri varchar(255) NOT NULL,
  osr_application_uri varchar(255) NOT NULL,
  osr_application_title varchar(80) NOT NULL,
  osr_application_descr text NOT NULL,
  osr_application_notes text NOT NULL,
  osr_application_type varchar(20) NOT NULL,
  osr_application_commercial tinyint(1) NOT NULL DEFAULT '0',
  osr_issue_date datetime NOT NULL,
  osr_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (osr_id),
  UNIQUE KEY osr_consumer_key (osr_consumer_key),
  KEY osr_usa_id_ref (osr_usa_id_ref)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'oauth_server_token'
--

CREATE TABLE oauth_server_token (
  ost_id int(11) NOT NULL AUTO_INCREMENT,
  ost_osr_id_ref int(11) NOT NULL,
  ost_usa_id_ref int(11) NOT NULL,
  ost_token varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  ost_token_secret varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  ost_token_type enum('request','access') DEFAULT NULL,
  ost_authorized tinyint(1) NOT NULL DEFAULT '0',
  ost_referrer_host varchar(128) NOT NULL DEFAULT '',
  ost_token_ttl datetime NOT NULL DEFAULT '9999-12-31 00:00:00',
  ost_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ost_verifier char(10) DEFAULT NULL,
  ost_callback_url varchar(512) DEFAULT NULL,
  PRIMARY KEY (ost_id),
  UNIQUE KEY ost_token (ost_token),
  KEY ost_osr_id_ref (ost_osr_id_ref),
  KEY ost_token_ttl (ost_token_ttl)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'priority'
--

CREATE TABLE priority (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'share_type'
--

CREATE TABLE share_type (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'sourcemessage'
--

CREATE TABLE sourcemessage (
  id int(11) NOT NULL AUTO_INCREMENT,
  category varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  message text COLLATE utf8_unicode_ci,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'subject'
--

CREATE TABLE `subject` (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  user_ip varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  user_comment text COLLATE utf8_unicode_ci,
  user_country_id tinyint(4) NOT NULL DEFAULT '1',
  title varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  urn varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  content_type_id int(11) NOT NULL COMMENT '1/image 2/text 3/video',
  content_id int(11) NOT NULL COMMENT 'Id of the record on the table associated with the content type',
  country_id varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `language` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  moderator_id int(11) NOT NULL DEFAULT '0',
  moderator_ip varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  moderator_comment varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  moderator_country_id tinyint(4) NOT NULL DEFAULT '1',
  authorizer_id int(11) NOT NULL DEFAULT '0',
  authorizer_ip varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  authorizer_country_id tinyint(4) NOT NULL DEFAULT '1',
  time_submitted int(11) NOT NULL DEFAULT '0',
  time_moderated int(11) NOT NULL DEFAULT '0',
  time_authorized int(11) NOT NULL DEFAULT '0',
  priority_id tinyint(4) NOT NULL DEFAULT '1',
  evaluating tinyint(4) NOT NULL DEFAULT '0',
  approved tinyint(4) NOT NULL DEFAULT '0',
  authorized tinyint(4) NOT NULL DEFAULT '0',
  disabled tinyint(4) NOT NULL DEFAULT '0',
  deleted tinyint(4) NOT NULL DEFAULT '0',
  show_time int(11) NOT NULL DEFAULT '0' COMMENT 'time when the running thread shows the content',
  tag varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  category varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  views int(11) NOT NULL DEFAULT '0',
  live_views int(11) NOT NULL DEFAULT '0',
  `hash` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  position int(11) NOT NULL DEFAULT '0',
  user_position int(11) NOT NULL DEFAULT '0',
  manager_position int(11) NOT NULL DEFAULT '0',
  likes int(11) NOT NULL DEFAULT '0',
  dislikes int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY urn (urn),
  KEY user_id (user_id),
  KEY user_ip (user_ip),
  KEY title (title),
  KEY content_type_id (content_type_id),
  KEY content_id (content_id),
  KEY moderator_id (moderator_id),
  KEY time_submitted (time_submitted),
  KEY show_time (show_time),
  KEY time_moderated (time_moderated),
  KEY country_id (country_id),
  KEY priority_id (priority_id),
  KEY subject_status (approved),
  KEY user_country_id (user_country_id),
  KEY position (position),
  KEY user_position (user_position),
  KEY manager_position (manager_position),
  FULLTEXT KEY tag (tag),
  FULLTEXT KEY category (category)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'subject_category'
--

CREATE TABLE subject_category (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'subject_tag'
--

CREATE TABLE subject_tag (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'subject_vote'
--

CREATE TABLE subject_vote (
  id int(11) NOT NULL AUTO_INCREMENT,
  subject_id int(11) NOT NULL,
  user_id int(11) NOT NULL DEFAULT '0',
  vote int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY subject_user_vote (subject_id,user_id,vote)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'translation'
--

CREATE TABLE translation (
  id int(11) NOT NULL AUTO_INCREMENT,
  language_id int(11) NOT NULL,
  string_id int(11) NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  vote int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  ip_submitted varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  time_submitted int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY language_id (language_id),
  KEY string_id (string_id),
  KEY vote (vote),
  KEY language_id_vote_status (language_id,vote,`status`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'user'
--

CREATE TABLE `user` (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  salt varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  reset_hash varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  reset_time int(11) NOT NULL DEFAULT '0',
  email varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  ip_created varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  ip_last_access varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  user_status_id tinyint(4) NOT NULL DEFAULT '1',
  user_type_id tinyint(4) NOT NULL DEFAULT '2',
  time_created int(11) NOT NULL,
  time_last_access int(11) NOT NULL,
  time_modified int(11) NOT NULL,
  country_id int(11) NOT NULL DEFAULT '1',
  country_id_created int(11) NOT NULL DEFAULT '1',
  notify_subject_live tinyint(4) NOT NULL DEFAULT '1',
  notify_subject_authorized tinyint(4) NOT NULL DEFAULT '1',
  firstname varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  lastname varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  sex tinyint(4) NOT NULL DEFAULT '0',
  birthdate int(11) DEFAULT NULL,
  region varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  city varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  interests text COLLATE utf8_unicode_ci,
  activities text COLLATE utf8_unicode_ci,
  about text COLLATE utf8_unicode_ci,
  image_name varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  share_email tinyint(4) NOT NULL DEFAULT '1',
  share_birthdate tinyint(4) NOT NULL DEFAULT '1',
  share_region tinyint(4) NOT NULL DEFAULT '1',
  share_city tinyint(4) NOT NULL DEFAULT '1',
  share_interests tinyint(4) NOT NULL DEFAULT '1',
  share_activities tinyint(4) NOT NULL DEFAULT '1',
  share_about tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email),
  KEY `password` (`password`),
  KEY username_password (username,`password`),
  KEY user_state_id (user_status_id),
  KEY user_type_id (user_type_id),
  KEY reset_hash (reset_hash),
  KEY reset_time (reset_time)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'user_status'
--

CREATE TABLE user_status (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'user_type'
--

CREATE TABLE user_type (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of users';
