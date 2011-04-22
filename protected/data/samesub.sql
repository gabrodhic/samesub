-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 22, 2011 at 10:48 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `samesub`
--

-- --------------------------------------------------------

--
-- Table structure for table `authassignment`
--

CREATE TABLE IF NOT EXISTS `authassignment` (
  `itemname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `userid` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `bizrule` text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `authassignment`
--

INSERT INTO `authassignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('user', '3', NULL, 'N;'),
('user', '1', NULL, 'N;'),
('moderator', '3', NULL, 'N;'),
('administrator', '3', NULL, 'N;'),
('super', '2', NULL, 'N;');

-- --------------------------------------------------------

--
-- Table structure for table `authitem`
--

CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `bizrule` text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `authitem`
--

INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('subject_update', 0, 'update a Subject', NULL, 'N;'),
('subject_manage', 0, 'manage a Subject', NULL, 'N;'),
('subject_moderate', 0, 'moderate a Subject', NULL, 'N;'),
('subject_authorize', 0, 'authorize a Subject', NULL, 'N;'),
('subject_delete', 0, 'delete a Subject', NULL, 'N;'),
('subject_updateown', 1, 'update a Subject by creator user himself', 'return Yii::app()->user->id==$params["Subject"]["user_id"];', 'N;'),
('user', 2, '', NULL, 'N;'),
('moderator', 2, '', NULL, 'N;'),
('authorizer', 2, '', NULL, 'N;'),
('administrator', 2, '', NULL, 'N;'),
('super', 2, '', NULL, 'N;');

-- --------------------------------------------------------

--
-- Table structure for table `authitemchild`
--

CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `authitemchild`
--

INSERT INTO `authitemchild` (`parent`, `child`) VALUES
('administrator', 'authorizer'),
('administrator', 'moderator'),
('administrator', 'user'),
('authorizer', 'subject_authorize'),
('authorizer', 'subject_manage'),
('authorizer', 'user'),
('moderator', 'subject_manage'),
('moderator', 'subject_moderate'),
('moderator', 'user'),
('subject_updateown', 'subject_update'),
('super', 'administrator'),
('super', 'subject_delete'),
('super', 'subject_update'),
('user', 'subject_updateown');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `user_ip`, `country_id`, `subject_id`, `time`, `sequence`, `comment`) VALUES
(1, 0, '127.0.0.1', 0, 2, 1303446560, 1, 'hello world');

-- --------------------------------------------------------

--
-- Table structure for table `content_image`
--

CREATE TABLE IF NOT EXISTS `content_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'path where the image is located relative to the site root',
  `extension` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'extension of the file name',
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `content_image`
--

INSERT INTO `content_image` (`id`, `path`, `extension`, `type`, `size`) VALUES
(1, 'img/1', 'png', 'image/png', 1507676),
(2, 'img/1', 'png', 'image/png', 710782),
(3, 'img/1', 'png', 'image/png', 935291);

-- --------------------------------------------------------

--
-- Table structure for table `content_text`
--

CREATE TABLE IF NOT EXISTS `content_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `content_text`
--

INSERT INTO `content_text` (`id`, `text`) VALUES
(1, 'Samesub is a space in which all internet users interact with a Same Subject synchronously.\r\nBecause all users interact with the same subject, that subject has to be representative to general needs of the users. That is why the main type of content will be news that draw attention globally, impact or general interest news, and of course, any unique and valuable content submitted by users.\r\n\r\nOur mission is to transmit one unique content at a time in a synchronous manner to all the world by every medium, format and language.\r\n'),
(2, 'If you google "Repetitive Strain Injury", you will find that it is a situation in which you force your body to excert a position or preasure in a constant and repetitive manner.\r\n\r\nAs you might know, the body isn''t designed to be on such constant situation, since it makes damage to it. The human body was design to move, in fact, some physics phrase says something like that, "so that it can be energy(life) there most be movement". And you can see that principle in action in all the nature. So, go on stand up from that computer and move your body.');

-- --------------------------------------------------------

--
-- Table structure for table `content_type`
--

CREATE TABLE IF NOT EXISTS `content_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `content_type`
--

INSERT INTO `content_type` (`id`, `name`, `fullname`) VALUES
(1, 'image', 'Image'),
(2, 'text', 'Text'),
(3, 'video', 'Video');

-- --------------------------------------------------------

--
-- Table structure for table `content_video`
--

CREATE TABLE IF NOT EXISTS `content_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `embed_code` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `content_video`
--


-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=251 ;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `code`) VALUES
(1, 'AFGHANISTAN', 'AF'),
(2, 'ALAND ISLANDS', 'AX'),
(3, 'ALBANIA', 'AL'),
(4, 'ALGERIA', 'DZ'),
(5, 'AMERICAN SAMOA', 'AS'),
(6, 'ANDORRA', 'AD'),
(7, 'ANGOLA', 'AO'),
(8, 'ANGUILLA', 'AI'),
(9, 'ANTARCTICA', 'AQ'),
(10, 'ANTIGUA AND BARBUDA', 'AG'),
(11, 'ARGENTINA', 'AR'),
(12, 'ARMENIA', 'AM'),
(13, 'ARUBA', 'AW'),
(14, 'AUSTRALIA', 'AU'),
(15, 'AUSTRIA', 'AT'),
(16, 'AZERBAIJAN', 'AZ'),
(17, 'BAHAMAS', 'BS'),
(18, 'BAHRAIN', 'BH'),
(19, 'BANGLADESH', 'BD'),
(20, 'BARBADOS', 'BB'),
(21, 'BELARUS', 'BY'),
(22, 'BELGIUM', 'BE'),
(23, 'BELIZE', 'BZ'),
(24, 'BENIN', 'BJ'),
(25, 'BERMUDA', 'BM'),
(26, 'BHUTAN', 'BT'),
(27, 'BOLIVIA, PLURINATIONAL STATE OF', 'BO'),
(28, 'BONAIRE, SAINT EUSTATIUS AND SABA', 'BQ'),
(29, 'BOSNIA AND HERZEGOVINA', 'BA'),
(30, 'BOTSWANA', 'BW'),
(31, 'BOUVET ISLAND', 'BV'),
(32, 'BRAZIL', 'BR'),
(33, 'BRITISH INDIAN OCEAN TERRITORY', 'IO'),
(34, 'BRUNEI DARUSSALAM', 'BN'),
(35, 'BULGARIA', 'BG'),
(36, 'BURKINA FASO', 'BF'),
(37, 'BURUNDI', 'BI'),
(38, 'CAMBODIA', 'KH'),
(39, 'CAMEROON', 'CM'),
(40, 'CANADA', 'CA'),
(41, 'CAPE VERDE', 'CV'),
(42, 'CAYMAN ISLANDS', 'KY'),
(43, 'CENTRAL AFRICAN REPUBLIC', 'CF'),
(44, 'CHAD', 'TD'),
(45, 'CHILE', 'CL'),
(46, 'CHINA', 'CN'),
(47, 'CHRISTMAS ISLAND', 'CX'),
(48, 'COCOS (KEELING) ISLANDS', 'CC'),
(49, 'COLOMBIA', 'CO'),
(50, 'COMOROS', 'KM'),
(51, 'CONGO', 'CG'),
(52, 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'CD'),
(53, 'COOK ISLANDS', 'CK'),
(54, 'COSTA RICA', 'CR'),
(55, 'COTE D''IVOIRE', 'CI'),
(56, 'CROATIA', 'HR'),
(57, 'CUBA', 'CU'),
(58, 'CURACAO', 'CW'),
(59, 'CYPRUS', 'CY'),
(60, 'CZECH REPUBLIC', 'CZ'),
(61, 'DENMARK', 'DK'),
(62, 'DJIBOUTI', 'DJ'),
(63, 'DOMINICA', 'DM'),
(64, 'DOMINICAN REPUBLIC', 'DO'),
(65, 'ECUADOR', 'EC'),
(66, 'EGYPT', 'EG'),
(67, 'EL SALVADOR', 'SV'),
(68, 'EQUATORIAL GUINEA', 'GQ'),
(69, 'ERITREA', 'ER'),
(70, 'ESTONIA', 'EE'),
(71, 'ETHIOPIA', 'ET'),
(72, 'FALKLAND ISLANDS (MALVINAS)', 'FK'),
(73, 'FAROE ISLANDS', 'FO'),
(74, 'FIJI', 'FJ'),
(75, 'FINLAND', 'FI'),
(76, 'FRANCE', 'FR'),
(77, 'FRENCH GUIANA', 'GF'),
(78, 'FRENCH POLYNESIA', 'PF'),
(79, 'FRENCH SOUTHERN TERRITORIES', 'TF'),
(80, 'GABON', 'GA'),
(81, 'GAMBIA', 'GM'),
(82, 'GEORGIA', 'GE'),
(83, 'GERMANY', 'DE'),
(84, 'GHANA', 'GH'),
(85, 'GIBRALTAR', 'GI'),
(86, 'GREECE', 'GR'),
(87, 'GREENLAND', 'GL'),
(88, 'GRENADA', 'GD'),
(89, 'GUADELOUPE', 'GP'),
(90, 'GUAM', 'GU'),
(91, 'GUATEMALA', 'GT'),
(92, 'GUERNSEY', 'GG'),
(93, 'GUINEA', 'GN'),
(94, 'GUINEA-BISSAU', 'GW'),
(95, 'GUYANA', 'GY'),
(96, 'HAITI', 'HT'),
(97, 'HEARD ISLAND AND MCDONALD ISLANDS', 'HM'),
(98, 'HOLY SEE (VATICAN CITY STATE)', 'VA'),
(99, 'HONDURAS', 'HN'),
(100, 'HONG KONG', 'HK'),
(101, 'HUNGARY', 'HU'),
(102, 'ICELAND', 'IS'),
(103, 'INDIA', 'IN'),
(104, 'INDONESIA', 'ID'),
(105, 'IRAN, ISLAMIC REPUBLIC OF', 'IR'),
(106, 'IRAQ', 'IQ'),
(107, 'IRELAND', 'IE'),
(108, 'ISLE OF MAN', 'IM'),
(109, 'ISRAEL', 'IL'),
(110, 'ITALY', 'IT'),
(111, 'JAMAICA', 'JM'),
(112, 'JAPAN', 'JP'),
(113, 'JERSEY', 'JE'),
(114, 'JORDAN', 'JO'),
(115, 'KAZAKHSTAN', 'KZ'),
(116, 'KENYA', 'KE'),
(117, 'KIRIBATI', 'KI'),
(118, 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', 'KP'),
(119, 'KOREA, REPUBLIC OF', 'KR'),
(120, 'KUWAIT', 'KW'),
(121, 'KYRGYZSTAN', 'KG'),
(122, 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', 'LA'),
(123, 'LATVIA', 'LV'),
(124, 'LEBANON', 'LB'),
(125, 'LESOTHO', 'LS'),
(126, 'LIBERIA', 'LR'),
(127, 'LIBYAN ARAB JAMAHIRIYA', 'LY'),
(128, 'LIECHTENSTEIN', 'LI'),
(129, 'LITHUANIA', 'LT'),
(130, 'LUXEMBOURG', 'LU'),
(131, 'MACAO', 'MO'),
(132, 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'MK'),
(133, 'MADAGASCAR', 'MG'),
(134, 'MALAWI', 'MW'),
(135, 'MALAYSIA', 'MY'),
(136, 'MALDIVES', 'MV'),
(137, 'MALI', 'ML'),
(138, 'MALTA', 'MT'),
(139, 'MARSHALL ISLANDS', 'MH'),
(140, 'MARTINIQUE', 'MQ'),
(141, 'MAURITANIA', 'MR'),
(142, 'MAURITIUS', 'MU'),
(143, 'MAYOTTE', 'YT'),
(144, 'MEXICO', 'MX'),
(145, 'MICRONESIA, FEDERATED STATES OF', 'FM'),
(146, 'MOLDOVA, REPUBLIC OF', 'MD'),
(147, 'MONACO', 'MC'),
(148, 'MONGOLIA', 'MN'),
(149, 'MONTENEGRO', 'ME'),
(150, 'MONTSERRAT', 'MS'),
(151, 'MOROCCO', 'MA'),
(152, 'MOZAMBIQUE', 'MZ'),
(153, 'MYANMAR', 'MM'),
(154, 'NAMIBIA', 'NA'),
(155, 'NAURU', 'NR'),
(156, 'NEPAL', 'NP'),
(157, 'NETHERLANDS', 'NL'),
(158, 'NEW CALEDONIA', 'NC'),
(159, 'NEW ZEALAND', 'NZ'),
(160, 'NICARAGUA', 'NI'),
(161, 'NIGER', 'NE'),
(162, 'NIGERIA', 'NG'),
(163, 'NIUE', 'NU'),
(164, 'NORFOLK ISLAND', 'NF'),
(165, 'NORTHERN MARIANA ISLANDS', 'MP'),
(166, 'NORWAY', 'NO'),
(167, 'OMAN', 'OM'),
(168, 'PAKISTAN', 'PK'),
(169, 'PALAU', 'PW'),
(170, 'PALESTINIAN TERRITORY, OCCUPIED', 'PS'),
(171, 'PANAMA', 'PA'),
(172, 'PAPUA NEW GUINEA', 'PG'),
(173, 'PARAGUAY', 'PY'),
(174, 'PERU', 'PE'),
(175, 'PHILIPPINES', 'PH'),
(176, 'PITCAIRN', 'PN'),
(177, 'POLAND', 'PL'),
(178, 'PORTUGAL', 'PT'),
(179, 'PUERTO RICO', 'PR'),
(180, 'QATAR', 'QA'),
(181, 'REUNION', 'RE'),
(182, 'ROMANIA', 'RO'),
(183, 'RUSSIAN FEDERATION', 'RU'),
(184, 'RWANDA', 'RW'),
(185, 'SAINT BARTHELEMY', 'BL'),
(186, 'SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA', 'SH'),
(187, 'SAINT KITTS AND NEVIS', 'KN'),
(188, 'SAINT LUCIA', 'LC'),
(189, 'SAINT MARTIN (FRENCH PART)', 'MF'),
(190, 'SAINT PIERRE AND MIQUELON', 'PM'),
(191, 'SAINT VINCENT AND THE GRENADINES', 'VC'),
(192, 'SAMOA', 'WS'),
(193, 'SAN MARINO', 'SM'),
(194, 'SAO TOME AND PRINCIPE', 'ST'),
(195, 'SAUDI ARABIA', 'SA'),
(196, 'SENEGAL', 'SN'),
(197, 'SERBIA', 'RS'),
(198, 'SEYCHELLES', 'SC'),
(199, 'SIERRA LEONE', 'SL'),
(200, 'SINGAPORE', 'SG'),
(201, 'SINT MAARTEN (DUTCH PART)', 'SX'),
(202, 'SLOVAKIA', 'SK'),
(203, 'SLOVENIA', 'SI'),
(204, 'SOLOMON ISLANDS', 'SB'),
(205, 'SOMALIA', 'SO'),
(206, 'SOUTH AFRICA', 'ZA'),
(207, 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'GS'),
(208, 'SPAIN', 'ES'),
(209, 'SRI LANKA', 'LK'),
(210, 'SUDAN', 'SD'),
(211, 'SURINAME', 'SR'),
(212, 'SVALBARD AND JAN MAYEN', 'SJ'),
(213, 'SWAZILAND', 'SZ'),
(214, 'SWEDEN', 'SE'),
(215, 'SWITZERLAND', 'CH'),
(216, 'SYRIAN ARAB REPUBLIC', 'SY'),
(217, 'TAIWAN, PROVINCE OF CHINA', 'TW'),
(218, 'TAJIKISTAN', 'TJ'),
(219, 'TANZANIA, UNITED REPUBLIC OF', 'TZ'),
(220, 'THAILAND', 'TH'),
(221, 'TIMOR-LESTE', 'TL'),
(222, 'TOGO', 'TG'),
(223, 'TOKELAU', 'TK'),
(224, 'TONGA', 'TO'),
(225, 'TRINIDAD AND TOBAGO', 'TT'),
(226, 'TUNISIA', 'TN'),
(227, 'TURKEY', 'TR'),
(228, 'TURKMENISTAN', 'TM'),
(229, 'TURKS AND CAICOS ISLANDS', 'TC'),
(230, 'TUVALU', 'TV'),
(231, 'UGANDA', 'UG'),
(232, 'UKRAINE', 'UA'),
(233, 'UNITED ARAB EMIRATES', 'AE'),
(234, 'UNITED KINGDOM', 'GB'),
(235, 'UNITED STATES', 'US'),
(236, 'UNITED STATES MINOR OUTLYING ISLANDS', 'UM'),
(237, 'URUGUAY', 'UY'),
(238, 'UZBEKISTAN', 'UZ'),
(239, 'VANUATU', 'VU'),
(240, 'VENEZUELA, BOLIVARIAN REPUBLIC OF', 'VE'),
(241, 'VIET NAM', 'VN'),
(242, 'VIRGIN ISLANDS, BRITISH', 'VG'),
(243, 'VIRGIN ISLANDS, U.S.', 'VI'),
(244, 'WALLIS AND FUTUNA', 'WF'),
(245, 'WESTERN SAHARA', 'EH'),
(246, 'YEMEN', 'YE'),
(247, 'ZAMBIA', 'ZM'),
(248, 'ZIMBABWE', 'ZW');

-- --------------------------------------------------------

--
-- Table structure for table `ipaddress`
--

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

--
-- Dumping data for table `ipaddress`
--


-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `language`
--


-- --------------------------------------------------------

--
-- Table structure for table `live_comment`
--

CREATE TABLE IF NOT EXISTS `live_comment` (
  `comment_number` int(11) NOT NULL AUTO_INCREMENT,
  `comment_sequence` int(11) NOT NULL,
  `comment_text` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `comment_time` int(11) NOT NULL DEFAULT '0',
  `comment_country` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'WW',
  PRIMARY KEY (`comment_number`),
  KEY `time` (`comment_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `live_comment`
--

INSERT INTO `live_comment` (`comment_number`, `comment_sequence`, `comment_text`, `comment_time`, `comment_country`) VALUES
(1, 1, 'hello world', 1303446560, 'WW');

-- --------------------------------------------------------

--
-- Table structure for table `live_subject`
--

CREATE TABLE IF NOT EXISTS `live_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id_1` int(11) NOT NULL DEFAULT '0',
  `subject_id_2` int(11) NOT NULL DEFAULT '0',
  `last_comment_number` int(11) NOT NULL DEFAULT '0',
  `comment_sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `live_subject`
--

INSERT INTO `live_subject` (`id`, `subject_id_1`, `subject_id_2`, `last_comment_number`, `comment_sequence`) VALUES
(1, 2, 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL DEFAULT '0',
  `language` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `translation` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `message`
--


-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `fixed` tinyint(4) NOT NULL DEFAULT '0',
  `message` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `enabled`, `fixed`, `message`) VALUES
(1, 1, 1, 'samesub is a space where one and only one subject its transmitted at a time, so everyone connected can interact with that same subject.'),
(2, 1, 0, 'Please give us your feedback.'),
(5, 1, 0, 'You can add a subject by clicking on the link "add subject" on the top.'),
(6, 1, 0, 'Did you find a bug. Please report it to us.');

-- --------------------------------------------------------

--
-- Table structure for table `priority`
--

CREATE TABLE IF NOT EXISTS `priority` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `priority`
--

INSERT INTO `priority` (`id`, `name`) VALUES
(1, 'Low'),
(2, 'Medium'),
(3, 'High'),
(4, 'Urgent');

-- --------------------------------------------------------

--
-- Table structure for table `sourcemessage`
--

CREATE TABLE IF NOT EXISTS `sourcemessage` (
  `id` int(11) NOT NULL,
  `category` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sourcemessage`
--


-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `user_id`, `user_ip`, `user_comment`, `user_country_id`, `title`, `urn`, `content_type_id`, `content_id`, `country_id`, `language`, `moderator_id`, `moderator_ip`, `moderator_comment`, `moderator_country_id`, `authorizer_id`, `authorizer_ip`, `authorizer_country_id`, `time_submitted`, `time_moderated`, `time_authorized`, `priority_id`, `evaluating`, `approved`, `authorized`, `show_time`) VALUES
(1, 2, '127.0.0.1', 'This our first official record on the database, finally!!. We are very happy, after all this time of development, testing, idea modeling, conceptualization, design and hard work, we are finally live.\r\nI hope that God allows us to make this dream.\r\nThank you all.', 0, 'so....what is samesub all about?', 'sowhat_is_samesub_all_about', 2, 1, '0', '0', 0, NULL, NULL, 0, 0, NULL, 0, 1303435728, 0, 0, 3, 0, 1, 1, 1303445208),
(2, 2, '127.0.0.1', 'Hey, here I put our initial sketch for our web design on the homepage(live stream). As you can see, it changed a little bit from the current one. Anyway, hope you enjoy knowing a little bit from behind the scene of samesub.', 0, 'Samesub initial design: Our initial sketch', 'Samesub_initial_design_Our_initial_sketch', 1, 1, '0', '0', 0, NULL, '', 0, 0, NULL, 0, 1303439365, 0, 0, 3, 0, 1, 1, 1303445374),
(3, 2, '127.0.0.1', 'I just got this effect last night while driving, took the camera, and there you are.', 0, 'Nice effect with neon lights', 'Nice_effect_with_neon_lights', 1, 2, '0', '0', 0, NULL, '', 0, 0, NULL, 0, 1303442985, 0, 0, 1, 0, 1, 1, 1303445388),
(4, 2, '127.0.0.1', '', 0, 'Ladies and gentleman: MOTOGEAR MAN', 'Ladies_and_gentleman_MOTOGEAR_MAN', 1, 3, '0', '0', 0, NULL, '', 0, 0, NULL, 0, 1303444653, 0, 0, 1, 0, 1, 1, 0),
(5, 2, '127.0.0.1', 'give it a try!', 0, 'My pc, Repetitive Strain Injury, and Life', 'My_pc_Repetitive_Strain_Injury_and_Life', 2, 2, '0', '0', 0, NULL, '', 0, 0, NULL, 0, 1303445181, 0, 0, 1, 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `translation`
--

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

--
-- Dumping data for table `translation`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

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

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `salt`, `email`, `ip_created`, `ip_last_access`, `user_status_id`, `user_type_id`, `time_created`, `time_last_access`, `time_modified`, `country_id`) VALUES
(1, 'guest', '2e5c7db760a33498023813489cfadc0b', '28b206548469ce62182048fd9cf91760', 'guest@guest.com', '127.0.0.1', '127.0.0.1', 1, 5, 0, 0, 0, 0),
(2, 'super', '728c1309e928f14d63ccc63966a6251c', 'mysalt', 'na@na.com', '127.0.0.1', '127.0.0.1', 1, 1, 0, 0, 0, 0),
(3, 'admin', '728c1309e928f14d63ccc63966a6251c', 'mysalt', 'as@as.com', '127.0.0.1', '127.0.0.1', 1, 2, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_status`
--

CREATE TABLE IF NOT EXISTS `user_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_status`
--

INSERT INTO `user_status` (`id`, `name`) VALUES
(1, 'Active'),
(2, 'Inactive'),
(3, 'Canceled');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE IF NOT EXISTS `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of users' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`) VALUES
(1, 'Super'),
(2, 'Administrator'),
(3, 'Moderator'),
(4, 'User'),
(5, 'Guest');
