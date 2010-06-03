CREATE TABLE IF NOT EXISTS `#__users_apikey` (
  `userid` int(11) NOT NULL,
  `apikey` varchar(50) default NULL,
  `secretkey` varchar(50) default NULL,
  `created_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `blocked` tinyint(4) default '0',
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;