--
-- SQL Dump
--
-- Site: localhost
-- Generation Time: Nov 27, 2011 at 12:14 AM
-- Server Version: 2.2.21
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
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=utf8;

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
INSERT INTO _recent_activity VALUES("84","blog","blog2011-10-16","insert","444","2011-10-16 00:41:50");
INSERT INTO _recent_activity VALUES("85","blog","blog2011-10-16","insert","555","2011-10-16 00:42:03");
INSERT INTO _recent_activity VALUES("86","blog","blog2011-10-16","insert","hihiih","2011-10-16 00:42:11");
INSERT INTO _recent_activity VALUES("87","pages","pages2011-10-16","update","afaaf","2011-10-16 17:19:16");
INSERT INTO _recent_activity VALUES("88","pages","pages2011-10-16","update","afaa11f","2011-10-16 17:19:21");
INSERT INTO _recent_activity VALUES("89","pages","pages2011-10-16","insert","1231","2011-10-16 17:19:25");
INSERT INTO _recent_activity VALUES("90","blog","blog2011-10-16","update","hihiih111","2011-10-16 17:19:49");
INSERT INTO _recent_activity VALUES("91","blog","blog2011-10-16","insert","131414","2011-10-16 17:20:31");
INSERT INTO _recent_activity VALUES("92","uploads","uploads2011-10-16","upload","1315331603599.png","2011-10-16 17:46:08");
INSERT INTO _recent_activity VALUES("93","uploads","uploads2011-10-16","upload","SHOOP_kit.png","2011-10-16 17:46:31");
INSERT INTO _recent_activity VALUES("94","uploads","uploads2011-10-16","upload","File:Implying_troll.png","2011-10-16 17:46:58");
INSERT INTO _recent_activity VALUES("95","uploads","uploads2011-10-16","upload","I_like_turtles_by_pixelperfect.jpeg","2011-10-16 17:46:58");
INSERT INTO _recent_activity VALUES("96","uploads","uploads2011-10-16","upload","No_lazer.jpeg","2011-10-16 17:46:58");
INSERT INTO _recent_activity VALUES("97","pages","pages2011-10-27","insert","hii123","2011-10-27 20:45:04");
INSERT INTO _recent_activity VALUES("98","pages","pages2011-10-27","update","afaa11f","2011-10-27 20:46:22");
INSERT INTO _recent_activity VALUES("99","pages","pages2011-10-27","update","afaa11f","2011-10-27 20:47:35");
INSERT INTO _recent_activity VALUES("100","pages","pages2011-10-27","update","afaa11f","2011-10-27 20:47:39");
INSERT INTO _recent_activity VALUES("101","pages","pages2011-10-27","update","kakka","2011-10-27 20:47:46");
INSERT INTO _recent_activity VALUES("102","pages","pages2011-10-27","update","1231","2011-10-27 20:47:56");
INSERT INTO _recent_activity VALUES("103","pages","pages2011-10-30","insert","Hejsaaa","2011-10-30 10:28:03");
INSERT INTO _recent_activity VALUES("104","pages","pages2011-10-30","update","Home","2011-10-30 11:18:59");
INSERT INTO _recent_activity VALUES("105","pages","pages2011-10-30","update","About","2011-10-30 11:19:19");
INSERT INTO _recent_activity VALUES("106","pages","pages2011-10-30","update","About","2011-10-30 11:20:31");
INSERT INTO _recent_activity VALUES("107","pages","pages2011-10-30","update","Weblog","2011-10-30 11:21:17");



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
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `_uploads_log`
--




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

INSERT INTO active_users VALUES("chrules","1322349260");



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

INSERT INTO blog_post_comments VALUES("14","1");
INSERT INTO blog_post_comments VALUES("14","2");



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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO blog_posts VALUES("13","Socializing","<p>Man kan selvfølgelig ikke gå rundt og have et firma uden at have en twitter account til den, så det blev der da lige sat op, under username <a href=\"https://twitter.com/#!/ZealDev\">ZealDev</a>. Udover det, så anskaffede jeg også lige mig selv en <a href=\"http://www.linkedin.com/pub/christian-kj%C3%A6r-laustsen/42/359/86\">linkedin profil</a> og satte mail op til zealdev med blandt andet&nbsp;contact@zealdev.dk.</p><p>Så skulle man være sat lidt op til at netværke ;) ...</p>","2","2011-10-16 17:20:31","1","0");
INSERT INTO blog_posts VALUES("14","Content, content, content !","<p>Så er <a href=\"http://thesite.dk/\">TheSite.dk</a> oppe og kører igen (dvs at <a href=\"https://github.com/ZealDev/TheCMS\">TheCMS</a> faktisk er begyndt at være brugbart i produktion :D ). Næste skridt må så være at få noget content ind, og der mangler dog også lige et par moduler til referencer og projekter. Men alt til sin tid jo...</p>","2","2011-11-09 17:18:44","1","0");



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
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO comments VALUES("1","1","Hjesa ;)","0000-00-00 00:00:00","Active","0");
INSERT INTO comments VALUES("2","1","hiihihih","0000-00-00 00:00:00","Active","0");
INSERT INTO comments VALUES("3","1","okay?","0000-00-00 00:00:00","Active","0");



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
  `type` varchar(200) NOT NULL,
  `modify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO pages VALUES("20","Weblog","","18","0","1","1","blog","2011-10-30 11:21:17","0");
INSERT INTO pages VALUES("16","Home","<p><p><strong>To Do List (TheCMS)</strong></p><ul \\\\\\\\?\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\?\\\\\\\\?\\\\??=\\\"\\\" list-style-type:\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?=\\\"\\\\\\\" \\\\\\\\?\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\?\\\\\\\\?\\\\??=\\\"\\\" square;\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?=\\\"\\\\\\\"><li>Fix tags og category (kommer ikke med fordi de er disabled)</li><li><strike>Fix extra space i blog indlæg (nok pga paragraph tags)</strike></li><li>Add \\\"checking\\\" state til backup script</li><li>Lav to-do list modul som erstatter home page (denne side)</li><li>Implementer AJAX i systemet så det kan ændres som en setting</li><li>Epay-modul?</li><li>Viderebyg comments modul</li><li><strike>Addslashes og stripslashes på blog-&gt;title (og kig lige på pages-&gt;title)</strike></li><li><strike>Fix anchor tags fra WYSIWYG editoren til content.</strike></li></ul><p><strong>To Do List (General)</strong></p><ul \\\\\\\\?\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\?\\\\\\\\?\\\\??=\\\"\\\" list-style-type:\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?=\\\"\\\\\\\" \\\\\\\\?\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?\\\\\\\\\\\\\\\\?\\\\\\\\?\\\\??=\\\"\\\" square;\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\?=\\\"\\\\\\\"><li>Cleanup i DB til thesite.dk</li></ul></p>","14","0","1","0","pages","2011-11-15 16:07:22","0");



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
-- Table structure for table `pages_type`
--

DROP TABLE IF EXISTS `pages_type`;
CREATE TABLE `pages_type` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages_type`
--

INSERT INTO pages_type VALUES("1","Blog","Blog","blog");



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
  `googleanalytics` tinyint(1) NOT NULL DEFAULT '0',
  `analyticscode` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO settings VALUES("1","TheSite","","","20","2","0","1","  var _gaq = _gaq || [];\n  _gaq.push([\'_setAccount\', \'UA-19146082-1\']);\n  _gaq.push([\'_setDomainName\', \'thesite.dk\']);\n  _gaq.push([\'_trackPageview\']);\n\n  (function() {\n    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;\n    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';\n    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);\n  })();");



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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usersdb`
--

INSERT INTO usersdb VALUES("3","testing","827ccb0eea8a706c4c34a16891f84e7b","chrelle","kjaer","0","1","christianlaustsen@gmail.com","0","1320940007");



