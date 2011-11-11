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