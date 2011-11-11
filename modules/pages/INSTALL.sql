--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `position` int(11) NOT NULL,
  `discussion` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `app` varchar(200) NOT NULL,
  `modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `pages_comments`
--

CREATE TABLE IF NOT EXISTS `pages_comments` (
  `pages_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `time` varchar(20) NOT NULL DEFAULT '00:00 00/00/00',
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Table structure for table `pages_preview`
--

CREATE TABLE IF NOT EXISTS `pages_preview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `position` int(11) NOT NULL,
  `discussion` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `app` varchar(200) NOT NULL,
  `modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `pages_type`
--

CREATE TABLE  `chrules_labs`.`pages_type` (
`id` TINYINT( 4 ) NOT NULL AUTO_INCREMENT ,
`module` VARCHAR( 255 ) NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL ,
`key` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `pages_type` VALUES(null, 'Pages', 'Text page', 'pages');