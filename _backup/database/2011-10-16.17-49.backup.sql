--
-- SQL Dump
--
-- Site: localhost
-- Generation Time: Oct 16, 2011 at 05:49 PM
-- Server Version: 2.2.17
-- PHP Version: 5.3.6

--
-- Database: `chrules_labs`
--

-- --------------------------------------------------------

--
-- Table structure for table `_recent_activity`
--

DROP TABLE IF EXISTS `_recent_activity`;
CREATE TABLE `_recent_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `grouping` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `additional` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `_recent_activity`
--

INSERT INTO _recent_activity VALUES("71","pages","pages2011-10-13","insert","","2011-10-13 07:13:05");
INSERT INTO _recent_activity VALUES("72","pages","pages2011-10-13","insert","afa","2011-10-13 07:13:26");
INSERT INTO _recent_activity VALUES("73","pages","pages2011-10-13","update","afaaf","2011-10-13 07:14:42");
INSERT INTO _recent_activity VALUES("74","blog","blog2011-10-13","insert","asdasd","2011-10-13 07:17:21");
INSERT INTO _recent_activity VALUES("75","blog","blog2011-10-13","update","aaaaaa11111","2011-10-13 07:18:25");
INSERT INTO _recent_activity VALUES("76","uploads","uploads2011-10-13","upload","1315331603599.png","2011-10-13 07:21:13");
INSERT INTO _recent_activity VALUES("77","blog","blog2011-10-16","insert","111","2011-10-16 00:32:23");
INSERT INTO _recent_activity VALUES("78","blog","blog2011-10-16","insert","111","2011-10-16 00:32:24");
INSERT INTO _recent_activity VALUES("79","blog","blog2011-10-16","insert","111","2011-10-16 00:33:35");
INSERT INTO _recent_activity VALUES("80","blog","blog2011-10-16","insert","1asdasd","2011-10-16 00:34:07");
INSERT INTO _recent_activity VALUES("81","blog","blog2011-10-16","insert","111","2011-10-16 00:38:25");
INSERT INTO _recent_activity VALUES("82","blog","blog2011-10-16","insert","222","2011-10-16 00:39:30");
INSERT INTO _recent_activity VALUES("83","blog","blog2011-10-16","insert","11143","2011-10-16 00:41:43");



--
-- Table structure for table `_uploads_log`
--

DROP TABLE IF EXISTS `_uploads_log`;
CREATE TABLE `_uploads_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `log_originalname` varchar(255) NOT NULL,
  `log_filename` varchar(128) DEFAULT '',
  `log_size` int(10) DEFAULT '0',
  `log_ip` varchar(24) DEFAULT '',
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `log_filename` (`log_filename`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `_uploads_log`
--

INSERT INTO _uploads_log VALUES("27","No_lazer.jpeg","p16c6mk65k1bohhico1t10q78e16.jpeg","34776","127.0.0.1","2011-10-16 17:46:58");
INSERT INTO _uploads_log VALUES("26","I_like_turtles_by_pixelperfect.jpeg","p16c6mk65jc5v2k1aqnclp13f5.jpeg","57347","127.0.0.1","2011-10-16 17:46:58");
INSERT INTO _uploads_log VALUES("25","File:Implying_troll.png","p16c6mk65i1f2n1fj11q10r76vot4.png","250088","127.0.0.1","2011-10-16 17:46:58");



--
-- Table structure for table `active_guests`
--

DROP TABLE IF EXISTS `active_guests`;
CREATE TABLE `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `active_guests`
--

INSERT INTO active_guests VALUES("127.0.0.1","1318780162");



--
-- Table structure for table `active_users`
--

DROP TABLE IF EXISTS `active_users`;
CREATE TABLE `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `active_users`
--




--
-- Table structure for table `archive`
--

DROP TABLE IF EXISTS `archive`;
CREATE TABLE `archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `archive`
--




--
-- Table structure for table `banned_users`
--

DROP TABLE IF EXISTS `banned_users`;
CREATE TABLE `banned_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `banned_users`
--




--
-- Table structure for table `blog_post_archive`
--

DROP TABLE IF EXISTS `blog_post_archive`;
CREATE TABLE `blog_post_archive` (
  `blog_post_id` int(11) NOT NULL DEFAULT '0',
  `archive_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_post_archive`
--




--
-- Table structure for table `blog_post_categories`
--

DROP TABLE IF EXISTS `blog_post_categories`;
CREATE TABLE `blog_post_categories` (
  `blog_post_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_post_categories`
--

INSERT INTO blog_post_categories VALUES("1","1");
INSERT INTO blog_post_categories VALUES("1","2");



--
-- Table structure for table `blog_post_comments`
--

DROP TABLE IF EXISTS `blog_post_comments`;
CREATE TABLE `blog_post_comments` (
  `blog_post_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_post_comments`
--

INSERT INTO blog_post_comments VALUES("3","1");
INSERT INTO blog_post_comments VALUES("3","2");



--
-- Table structure for table `blog_post_tags`
--

DROP TABLE IF EXISTS `blog_post_tags`;
CREATE TABLE `blog_post_tags` (
  `blog_post_id` int(11) NOT NULL DEFAULT '0',
  `tag_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_post_tags`
--




--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `post` text NOT NULL,
  `author_id` int(11) NOT NULL DEFAULT '0',
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discussion` tinyint(1) NOT NULL DEFAULT '1',
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO blog_posts VALUES("1","title","post","1","2011-09-20 02:05:20","0","0");
INSERT INTO blog_posts VALUES("2","asd","asd","22","2011-10-12 17:41:45","1","0");
INSERT INTO blog_posts VALUES("3","hiigigig","asdlflasfasf","22","2011-10-12 17:42:24","1","0");
INSERT INTO blog_posts VALUES("4","hiigigig","asdlflasfasf","22","2011-10-12 17:42:29","1","0");
INSERT INTO blog_posts VALUES("5","aaaaaa11111","asdlflasfasf","0","2011-10-12 17:43:38","0","0");
INSERT INTO blog_posts VALUES("6","111","asdasd","0","2011-10-16 00:38:25","1","0");
INSERT INTO blog_posts VALUES("7","222","asdasd","0","2011-10-16 00:39:30","1","0");



--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `trash` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO categories VALUES("1","Cataalkmsdlkmalkd","0");



--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `time` varchar(20) NOT NULL DEFAULT '00:00 00/00/00',
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO comments VALUES("1","1","Hjesa ;)","00:00 00/00/00","Active","0");
INSERT INTO comments VALUES("2","1","hiihihih","00:00 00/00/00","Active","0");
INSERT INTO comments VALUES("3","1","okay?","00:00 00/00/00","Active","0");



--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
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
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO pages VALUES("1","title","","0","0","1","1","","2011-09-19 20:07:07","0");
INSERT INTO pages VALUES("2","title","content","0","0","1","1","","2011-09-19 20:08:48","0");
INSERT INTO pages VALUES("3","title","content","1","0","1","1","other","2011-10-01 14:57:49","0");
INSERT INTO pages VALUES("4","title","content","2","1","1","1","","2011-09-19 20:09:08","0");
INSERT INTO pages VALUES("5","title","content","3","1","1","0","text","2011-09-19 20:10:18","0");
INSERT INTO pages VALUES("6","test","asdad","4","0","1","0","text","2011-10-12 18:19:43","0");
INSERT INTO pages VALUES("7","Okay ??????","hejsa &lt;3aaa","5","0","1","0","text","2011-10-12 18:24:49","0");
INSERT INTO pages VALUES("8","asdasd","","6","0","1","0","text","2011-10-12 19:39:19","0");
INSERT INTO pages VALUES("9","sadasd","","7","0","1","0","text","2011-10-12 19:39:22","0");



--
-- Table structure for table `pages_comments`
--

DROP TABLE IF EXISTS `pages_comments`;
CREATE TABLE `pages_comments` (
  `pages_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages_comments`
--

INSERT INTO pages_comments VALUES("7","4");



--
-- Table structure for table `pages_preview`
--

DROP TABLE IF EXISTS `pages_preview`;
CREATE TABLE `pages_preview` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages_preview`
--




--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `sitetitle` varchar(200) NOT NULL DEFAULT 'TheCMS',
  `url` varchar(200) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  `startpage` varchar(50) NOT NULL DEFAULT '',
  `membership` tinyint(1) NOT NULL DEFAULT '0',
  `theme` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO settings VALUES("1","TheCMS","","","8","0","0");



--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `trash` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--




--
-- Table structure for table `usersdb`
--

DROP TABLE IF EXISTS `usersdb`;
CREATE TABLE `usersdb` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `regkey` varchar(90) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usersdb`
--

INSERT INTO usersdb VALUES("1","Hejsa","adasd","Firststs","Laststst","","9","","","0");



