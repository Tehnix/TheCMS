--
-- SQL Dump
--
-- Site: localhost
-- Generation Time: Oct 16, 2011 at 02:08 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `_uploads_log`
--

INSERT INTO _uploads_log VALUES("11","1316670632863.gif","p16bsdjoah1qng4fslb61e1p10fk4.gif","56462","127.0.0.1","2011-10-12 17:57:02");
INSERT INTO _uploads_log VALUES("12","1315333899708.jpeg","p16bsdlnco13nial03f0vss1p8f4.jpeg","29083","127.0.0.1","2011-10-12 17:58:06");
INSERT INTO _uploads_log VALUES("13","1316675997345.jpeg","p16bsjgormhoa18b01ijb1nj3142p4.jpeg","47038","127.0.0.1","2011-10-12 19:40:18");
INSERT INTO _uploads_log VALUES("10","1316540404806.gif","p16bs8ihs6aup1jqd195715vg1fg25.gif","7671","127.0.0.1","2011-10-12 16:29:02");
INSERT INTO _uploads_log VALUES("9","1315331603599.png","p16bs8ihs5ua0175o183n1m9e12o54.png","91014","127.0.0.1","2011-10-12 16:29:01");
INSERT INTO _uploads_log VALUES("14","1316676885457.jpeg","p16bsjgorn1uob5s316t61bh7vqp5.jpeg","42717","127.0.0.1","2011-10-12 19:40:18");
INSERT INTO _uploads_log VALUES("15","1317327205190.jpeg","p16bsjgorn1o281qtr1hbajhhak6.jpeg","15760","127.0.0.1","2011-10-12 19:40:18");



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

INSERT INTO active_guests VALUES("127.0.0.1","1318723730");



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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO blog_posts VALUES("1","title","post","1","2011-09-20 02:05:20","0","0");
INSERT INTO blog_posts VALUES("2","asd","asd","22","2011-10-12 17:41:45","1","0");
INSERT INTO blog_posts VALUES("3","hiigigig","asdlflasfasf","22","2011-10-12 17:42:24","1","0");
INSERT INTO blog_posts VALUES("4","hiigigig","asdlflasfasf","22","2011-10-12 17:42:29","1","0");
INSERT INTO blog_posts VALUES("5","aaaaaa11111","asdlflasfasf","0","2011-10-12 17:43:38","0","0");
INSERT INTO blog_posts VALUES("6","111","asdasd","0","2011-10-16 00:38:25","1","0");



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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

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



