CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(255) unsigned NOT NULL auto_increment,
  `article_id` int(255) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
