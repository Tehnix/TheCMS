--
-- Table structure for table `_uploads_log`
--

CREATE TABLE IF NOT EXISTS `_uploads_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `log_filename` varchar(128) DEFAULT '',
  `log_size` int(10) DEFAULT '0',
  `log_ip` varchar(24) DEFAULT '',
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `log_filename` (`log_filename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;